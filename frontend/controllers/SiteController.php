<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\mail\MailerInterface;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly MailerInterface $mailer,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@', '?'], // Izinkan guest untuk keperluan mock login
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post', 'get'], // Izinkan GET sementara untuk mock
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Displays Membership Join page.
     *
     * @return string
     */
    public function actionJoin(): string
    {
        return $this->render('join');
    }

    /**
     * Logs in a user.
     *
     * @return string|Response
     */
    public function actionLogin(): string|Response
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return Response
     */
    public function actionTestLogin()
    {
        Yii::$app->session->set('mock_login', true);
        return $this->redirect(['site/index']);
    }

    public function actionSettings(): string|Response
    {
        if (Yii::$app->user->isGuest && !Yii::$app->session->has('mock_login')) {
            return $this->redirect(['site/login']);
        }
        return $this->render('settings');
    }

    public function actionLogout(): Response
    {
        Yii::$app->session->remove('mock_login');
        Yii::$app->user->logout(); // Tetap panggil aslinya untuk jaga-jaga
        return $this->goHome();
    }

    public function actionSubmitJoin()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $postData = $request->post();
            
            // TODO: Tim Backend - Tambahkan logika validasi & simpan ke DB di sini
            
            // Simulasi login setelah daftar
            Yii::$app->session->set('mock_login', true);
            Yii::$app->session->set('mock_user_name', $postData['full_name'] ?? 'Member Baru');

            // Simulasi sukses (kembalikan data ke view via Flash)
            Yii::$app->session->setFlash('success_join', [
                'name' => $postData['full_name'] ?? 'Member',
                'branch' => 'Cabang ' . ($postData['selected_branch'] ?? '-'),
                'phone' => '+62 ' . ($postData['whatsapp_no'] ?? '-'),
                'ticket_code' => 'HERC-' . rand(1000, 9999),
            ]);
            
            return $this->redirect(['site/join']);
        }
        return $this->redirect(['site/index']);
    }

    /**
     * Displays contact page.
     *
     * @return string|Response
     */
    public function actionContact(): string|Response
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $sent = $model->sendEmail(
                $this->mailer,
                Yii::$app->params['adminEmail'],
                Yii::$app->params['senderEmail'],
                Yii::$app->params['senderName'],
            );

            if ($sent) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout(): string
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return string|Response
     */
    public function actionSignup(): string|Response
    {
        $model = new SignupForm();

        $signed = $model->load(Yii::$app->request->post()) && $model->signup(
            $this->mailer,
            Yii::$app->params['supportEmail'],
            Yii::$app->name,
        );

        if ($signed) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return string|Response
     */
    public function actionRequestPasswordReset(): string|Response
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $sent = $model->sendEmail(
                $this->mailer,
                Yii::$app->params['supportEmail'],
                Yii::$app->name,
            );

            if ($sent) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return string|Response
     * @throws BadRequestHttpException
     */
    public function actionResetPassword(string $token): string|Response
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail(string $token): Response
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->verifyEmail()) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return string|Response
     */
    public function actionResendVerificationEmail(): string|Response
    {
        $model = new ResendVerificationEmailForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $sent = $model->sendEmail(
                $this->mailer,
                Yii::$app->params['supportEmail'],
                Yii::$app->name,
            );

            if ($sent) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model,
        ]);
    }

    /**
     * Displays membership page.
     *
     * @return mixed
     */
    public function actionMembership()
    {
        return $this->render('membership');
    }

    public function actionCheckout()
    {
        return $this->render('checkout');
    }

    /**
     * Displays personal trainer page.
     *
     * @return string
     */
    public function actionTrainer(): string
    {
        return $this->render('trainer');
    }

    public function actionLocationBatam(): string
    {
        return $this->render('location-batam');
    }

    public function actionLocationBali(): string
    {
        return $this->render('location-bali');
    }

    public function actionLocationDetail($id)
    {
        // Mock data for the specific branch
        $branchData = [
            'batu-ampar' => [
                'name' => 'Batu Ampar',
                'address' => 'Komplek macadam, Kec. Batu Ampar, Kota Batam, Kepulauan Riau 29444',
                'bg' => '/img/hero.jpg',
                'desc' => 'Cabang utama Hercules Fitness dengan fasilitas terlengkap di pusat kota Batam.',
                'lat' => 1.1610,
                'lng' => 104.0150,
            ],
            'batu-besar' => [
                'name' => 'Batu Besar',
                'address' => 'Ruko Bukit Citra Lestari, Jl. Dang Merdu No.1 blok K, Batu Besar, Kecamatan Nongsa, Kota Batam, Kepulauan Riau 29466',
                'bg' => '/img/hero2.jpg',
                'desc' => 'Suasana latihan asri dan strategis untuk warga Nongsa dan sekitarnya.',
                'lat' => 1.1180,
                'lng' => 104.1160,
            ],
            'mtc' => [
                'name' => 'MTC Batam',
                'address' => 'Kawasan Mega Tekno City (MTC) Blok C1 No.10-11, Kebun Terdong, Kec. Batu Aji, Kota Batam, Kepulauan Riau 29424',
                'bg' => '/img/lingkungan_gym.png',
                'desc' => 'Gym modern di area Batu Aji dengan parkir yang luas dan aman.',
                'lat' => 1.0505,
                'lng' => 104.0305,
            ],
            'canggu' => [
                'name' => 'Canggu',
                'address' => 'Jl. Raya Canggu No.163, Tibubeneng, Kec. Kuta Utara, Kabupaten Badung, Bali 80361',
                'bg' => '/img/clip4.jpeg',
                'desc' => 'Nikmati fitness premium dengan suasana tropis Canggu.',
                'lat' => -8.6478,
                'lng' => 115.1385,
            ],
            'kuta' => [
                'name' => 'Kuta',
                'address' => 'Banjar Jaba Jero Kuta, Jl. Raya Kuta No.20, Kuta, Kec. Kuta, Kabupaten Badung, Bali 80361',
                'bg' => '/img/clip5.jpeg',
                'desc' => 'Lokasi gym strategis di pusat hiburan Kuta.',
                'lat' => -8.7185,
                'lng' => 115.1686,
            ],
            'sriwijaya' => [
                'name' => 'Sriwijaya',
                'address' => 'Jl. Sriwijaya, Kp. Pelita, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau 29444',
                'bg' => '/img/lingkungan_gym.png',
                'desc' => 'Pusat kebugaran terlengkap dan strategis di area Pelita, Batam.',
                'lat' => 1.1378,
                'lng' => 104.0156,
            ],
        ];

        // If ID not found, redirect to home or show 404 (for simplicity, we fallback to batu-ampar if invalid, or we throw 404)
        if (!isset($branchData[$id])) {
            throw new \yii\web\NotFoundHttpException("Cabang tidak ditemukan.");
        }

        $branch = $branchData[$id];

        // Mock Facilities
        $facilities = [
            ['icon' => 'fas fa-wifi', 'title' => 'High-Speed Wi-Fi', 'desc' => 'Akses internet gratis dan cepat di seluruh area.'],
            ['icon' => 'fas fa-shower', 'title' => 'Toilet & Hot Shower', 'desc' => 'Air panas dan hair dryer tersedia.'],
            ['icon' => 'fas fa-lock', 'title' => 'Loker Pribadi', 'desc' => 'Simpan barang dengan aman selama berlatih.'],
            ['icon' => 'fas fa-parking', 'title' => 'Area Parkir', 'desc' => 'Parkir mobil dan motor luas.'],
            ['icon' => 'fas fa-couch', 'title' => 'Lounge Area', 'desc' => 'Tempat istirahat nyaman.'],
            ['icon' => 'fas fa-tint', 'title' => 'Dispenser Air', 'desc' => 'Gratis isi ulang botol minum.'],
        ];

        // Mock Equipment
        $equipments = [
            'Machines' => ['Smith Machine', 'Leg Press', 'Chest Press', 'Cable Cross', 'Lat Pulldown', 'Pec Deck'],
            'Free Weights' => ['Dumbbells up to 50kg', 'Olympic Barbells', 'Kettlebells', 'Bumper Plates'],
            'Cardio' => ['Treadmills', 'Spinning Bikes', 'Rowing Machines', 'Ellipticals'],
            'Lifting Bars' => ['Olympic Bar', 'EZ Curl Bar', 'Hex / Trap Bar'],
            'Others' => ['Yoga Mats', 'Bosu Ball', 'Resistance Bands', 'TRX'],
        ];

        $otherBranches = [];
        $cityBranches = in_array($id, ['canggu', 'kuta']) ? ['canggu', 'kuta'] : ['batu-ampar', 'batu-besar', 'mtc'];
        foreach ($cityBranches as $branchId) {
            if ($branchId !== $id && isset($branchData[$branchId])) {
                $otherBranches[$branchId] = $branchData[$branchId];
            }
        }

        return $this->render('location-detail', [
            'id' => $id,
            'branch' => $branch,
            'facilities' => $facilities,
            'equipments' => $equipments,
            'otherBranches' => $otherBranches,
        ]);
    }
}
