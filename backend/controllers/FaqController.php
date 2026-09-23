<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use common\models\Faq;

class FaqController extends Controller
{
    public $successUrl = 'index';

    public function init()
    {
        parent::init();
        Yii::$app->language = Yii::$app->lang->getLang();
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'update' => ['GET', 'POST'],
                ],
            ],
        ];
    }

    protected function findModel($id)
    {
        if (($model = Faq::findOne($id)) !== null) {
            return $model;
        } else {
            throw new \yii\web\NotFoundHttpException('The requested FAQ does not exist.');
        }
    }


    public function actionIndex($type = null, $id = null)
    {
        $faqtype = [
            'pengguna' => 'Pengguna',
            'akun' => 'Akun',
            'mitra' => 'Mitra',
        ];

        $faqType = Faq::getTypes();
        $search = Yii::$app->request->get('search', '');
        $query = Faq::find();

        if (!empty($type)) {
            $query->andWhere(['faqtype' => $type]); // Filter berdasarkan jenis FAQ
        }

        if (!empty($search)) {
            $query->andWhere([
                'or',
                ['ilike', 'question', $search],
                ['ilike', 'answer', $search]
            ]);
        }

        $faqList = $query->orderBy(['created_at' => SORT_DESC])->all();

        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'success' => true,
                'html' => $this->renderPartial('index', [
                    'faqList' => $faqList,
                    'search' => $search,
                    'faqType' => $faqType,
                    'selectedType' => $type,
                    'terpopuler' => [],
                    'palingMembantu' => [],
                    'model' => null
                ])
            ]);
        }

        $terpopuler = Faq::find()->orderBy(['view_count' => SORT_DESC])->limit(10)->all();
        $palingMembantu = Faq::find()->orderBy(['helpful' => SORT_DESC])->limit(10)->all();
        $model = $id ? Faq::findOne($id) : new Faq();

        return $this->render('index', [
            'faqList' => $faqList,
            'terpopuler' => $terpopuler,
            'palingMembantu' => $palingMembantu,
            'faqtype' => $faqtype,
            'selectedType' => $type,
            'model' => $model,
            'search' => $search,
        ]);
    }

    public function actionData()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $search = trim(Yii::$app->request->get('search', ''));
            $faqtype = trim(Yii::$app->request->get('faqtype', ''));

            $baseQuery = Faq::find()
                ->select(['faqid', 'faqtype', 'question', 'answer', 'view_count', 'helpful', 'created_at']);

            if (!empty($faqtype) && in_array($faqtype, array_keys(Faq::getTypes()))) {
                $baseQuery->andWhere(['faqtype' => $faqtype]);
            }

            if (!empty($search)) {
                $baseQuery->andWhere([
                    'or',
                    ['ilike', 'question', $search],
                    ['ilike', 'answer', $search]
                ]);
            }

            $faqList = $baseQuery->orderBy(['created_at' => SORT_DESC])->all();
            $terpopuler = Faq::find()->orderBy(['view_count' => SORT_DESC])->limit(10)->all();
            $palingMembantu = Faq::find()->orderBy(['helpful' => SORT_DESC])->limit(10)->all();

            $formatFaq = function ($faq) {
                return [
                    'faqid' => $faq->faqid,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'faqtype' => $faq->faqtype ?? '',
                    'view_count' => (int)($faq->view_count ?? 0),
                    'helpful' => (int)($faq->helpful ?? 0),
                    'created_at' => $faq->created_at,
                ];
            };

            return [
                'success' => true,
                'data' => array_map($formatFaq, $faqList),
                'terpopuler' => array_map($formatFaq, $terpopuler),
                'palingMembantu' => array_map($formatFaq, $palingMembantu),
                'filters' => ['search' => $search, 'faqtype' => $faqtype],
                'total' => count($faqList)
            ];
        } catch (\Exception $e) {
            Yii::error('Error in actionData: ' . $e->getMessage(), __METHOD__);
            return [
                'success' => false,
                'message' => 'Failed to load FAQ data: ' . $e->getMessage(),
                'data' => [],
                'terpopuler' => [],
                'palingMembantu' => [],
                'total' => 0
            ];
        }
    }

    public function actionCreate()
    {
        $model = new Faq();

        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return [
                    'success' => true,
                    'message' => 'Data Berhasil Disimpan',
                    'id' => $model->faqid,
                    'question' => $model->question
                ];
            } else {
                return [
                    'success' => false,
                    'message' => implode("<br/>(X) ", $model->getFirstErrors())
                ];
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', ['model' => $model]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']); // Redirect to list instead of index
        }

        return $this->render('_form', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!Yii::$app->user->identity || Yii::$app->user->identity->role_id !== 0) {
            throw new \yii\web\ForbiddenHttpException('Akses ditolak.');
        }

        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return [
                    'success' => true,
                    'pesan' => 'Data Berhasil Diperbarui',
                    'id' => $model->faqid,
                    'question' => $model->question
                ];
            } else {
                return [
                    'success' => false,
                    'pesan' => implode("<br/>(X) ", $model->getFirstErrors())
                ];
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', ['model' => $model]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']); // Redirect to list instead of index
        }

        return $this->render('_form', ['model' => $model]);
    }

    public function actionValidate($id = null)
    {
        $model = $id === null ? new \common\models\Faq() : \common\models\Faq::findOne($id);

        if (!$model) {
            throw new \yii\web\NotFoundHttpException('Data tidak ditemukan.');
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
    }

    public function actionView($id)
    {
        $model = Faq::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("FAQ tidak ditemukan.");
        }

        $model->updateCounters(['views' => 1]);

        return $this->render('detail', [
            'model' => $model,
        ]);
    }

    public function actionDelete()
    {
        if (!Yii::$app->user->identity || Yii::$app->user->identity->role_id !== 0) {
            throw new ForbiddenHttpException('Access denied.');
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isPost) {
            return ['success' => false, 'message' => 'Hanya metode POST yang diizinkan.'];
        }

        $faqid = Yii::$app->request->post('faqid');
        $csrfToken = Yii::$app->request->post('_csrf-backend');

        if (empty($faqid)) {
            return ['success' => false, 'message' => 'FAQ ID tidak ditemukan.'];
        }

        if (!Yii::$app->request->validateCsrfToken($csrfToken)) {
            Yii::error('CSRF Token tidak valid: ' . ($csrfToken ?: 'kosong'), __METHOD__);
            return ['success' => false, 'message' => 'Token CSRF tidak valid. Silakan muat ulang halaman.'];
        }

        try {
            $model = $this->findModel($faqid);

            if (!$model) {
                return ['success' => false, 'message' => 'Data FAQ tidak ditemukan.'];
            }

            if ($model->delete()) {
                Yii::info('FAQ dengan ID ' . $faqid . ' berhasil dihapus.', __METHOD__);
                return [
                    'success' => true,
                    'message' => 'Data berhasil dihapus dari semua kategori.'
                ];
            }

            return ['success' => false, 'message' => 'Gagal menghapus data. Silakan coba lagi.'];
        } catch (\Exception $e) {
            Yii::error('Error saat menghapus FAQ ID ' . $faqid . ': ' . $e->getMessage(), __METHOD__);
            return ['success' => false, 'message' => 'Terjadi error saat menghapus data: ' . $e->getMessage()];
        }
    }

    public function actionGetType()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $query = Yii::$app->request->get('q', '');
            $page = (int)Yii::$app->request->get('page', 1);
            $limit = 10;
            $offset = ($page - 1) * $limit;

            $queryBuilder = \common\models\Faq::find()
                ->select(['faqtype AS id', 'faqtype AS text'])
                ->distinct()
                ->where(['not', ['faqtype' => null]])
                ->andWhere(['!=', 'faqtype', '']);

            if (!empty($query)) {
                $queryBuilder->andWhere(['ilike', 'faqtype', '%' . $query . '%']);
            }

            $categories = $queryBuilder
                ->limit($limit)
                ->offset($offset)
                ->asArray()
                ->all();

            $totalQuery = clone $queryBuilder;
            $total = $totalQuery->count();

            if (empty($categories) && empty($query)) {
                $types = Faq::getTypes();
                $categories = array_map(function ($key, $value) {
                    return ['id' => $key, 'text' => $value];
                }, array_keys($types), $types);
                $total = count($categories);
            }

            return [
                'success' => true,
                'items' => $categories,
                'more' => ($offset + $limit) < $total,
                'total' => $total
            ];
        } catch (\Exception $e) {
            Yii::error('Error in actionGetType: ' . $e->getMessage(), __METHOD__);
            return [
                'success' => false,
                'items' => [],
                'more' => false,
                'error' => 'Failed to load types'
            ];
        }
    }
}
