<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\User;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class HomeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {

        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        // опробовать созданные роли и разрешения, создать соответствующие действия для проверки и настроить rules
        //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


        return [
//            'authenticator' => [
//                'class' => HttpBearerAuth::class,
//                'except' => ['login', 'error', 'index'
////                    , 'adduser'
//                ],
//
//            ],
            'access' => [
                'class' => AccessControl::class,
//                'only' => ['login', 'logout', 'signup'],
//                'denyCallback' => function ($rule, $action) {
//                    throw new \Exception('У вас нет доступа к этой странице');
//                },
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'index'],
                        'allow' => true, // авторизовать ли пользователя
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
//                        'roles' => ['?'], // означает гостя
                        'roles' => ['@'], // означает авторизованного пользователя
                    ],
                    [
                        'actions' => ['admin'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['seller'],
                        'allow' => true,
                        'roles' => ['seller'],
                    ],
                    [
                        'actions' => ['create-product'],
                        'allow' => true,
                        'roles' => ['admin', 'seller'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
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
                'class' => \yii\web\ErrorAction::class,
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
        $allUsers = User::find()->all();
        $users = [];

        foreach ($allUsers as $user) {
            $users[] = $user->toArray();
        }

        return $this->render('index', compact('users'));
    }

    public function actionAdmin()
    {
        $admin = Yii::$app->user->identity->toArray();

        return $this->render('admin', compact('admin'));
    }

    public function actionSeller()
    {
        $seller = Yii::$app->user->identity->toArray();

        return $this->render('seller', compact('seller'));
    }

    public function actionCreateProduct()
    {
        $user = Yii::$app->user->identity->toArray();

        return $this->render('create-product', compact('user'));
    }

//    public function beforeAction($action)
//    {
//        if (Yii::$app->user->isGuest) {
//            return $this->redirect(Yii::$app->user->loginUrl)->send();
//        }
//    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

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
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
