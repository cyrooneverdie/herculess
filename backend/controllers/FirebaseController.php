<?php

namespace backend\controllers;

use common\models\Coas;
use yii\web\Response;
use Yii;
use yii\web\Controller;
use common\models\Firebase;
use common\models\Tran;
use common\models\TranDetail;
use common\models\FirebaseDetail;
use common\models\Contact;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\db\Query;
/**
 * FirebaseController implements the CRUD actions for Firebase model.
 */
class FirebaseController extends Controller
{
    public $successUrl = '';

    public function init()
    {
        parent::init();
        Yii::$app->language = Yii::$app->lang->getLang();
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($action->id == 'error') {
                $this->layout = 'error';
            }
            return true;
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'list',
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Firebase models.
     * @return mixed
     */


    public function actionList()
    {
        //  echo Yii::getAlias('@common/config/firebase.json');
        // exit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = [];

        $firestore = Yii::$app->firebase->firestore();
         var_dump($firestore);exit;
        $users = $firestore->collection('users')->documents();

        // var_dump($users);
        foreach ($users as $user) {
            if ($user->exists()) {
                echo $user->id() . ': ' . $user['name'] . '<br>';
            }
        }


        // var_dump($sql, $params);exit;
        return ['data' => array_values($data)];
    }


    public function actionCreate()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = [];

        $firestore = Yii::$app->firebase->firestore();
        $docRef = $firestore->collection('users')->add([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'created_at' => new \DateTime()
        ]);


        // var_dump($sql, $params);exit;
        return ['data' => array_values($data)];
    }

    public function actionUpdate()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = [];


        $firestore = Yii::$app->firebase->firestore();
        $docRef = $firestore->collection('users')->add([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'created_at' => new \DateTime()
        ]);


        // var_dump($sql, $params);exit;
        return ['data' => array_values($data)];
    }

    /**
     * Creates a new Firebase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
}
