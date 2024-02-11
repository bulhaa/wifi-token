<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\data\ActiveDataProvider;
use app\models\Tokens;
use app\models\User;
use app\models\Devices;

class SiteController extends Controller
{
    

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $this->enableCsrfValidation = false;
        return [
            // 'access' => [
            //     'class' => AccessControl::className(),
            //     'only' => ['logout'],
            //     'rules' => [
            //         [
            //             'actions' => ['logout'],
            //             'allow' => true,
            //             'roles' => ['@'],
            //         ],
            //     ],
            // ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'logout' => ['post'],
            //     ],
            // ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            // 'error' => [
            //     'class' => 'yii\web\ErrorAction',
            // ],
            // 'captcha' => [
            //     'class' => 'yii\captcha\CaptchaAction',
            //     'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            // ],
        ];
    }

    private function randomPassword() {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 6; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(isset($_POST['token']) && Yii::$app->user->isGuest){
            if(strtoupper($_POST['token']) == 'JRJZ48'){
                $token = new Tokens();
                $token->password = 'JRJZ48';
                $token->max_allowed = 0;
                $token->used_today = 0;
                $token->total_used = 0;
                $token->validity_in_minutes = 60 * 24 * 30 * 12 * 10; // 10 years
                $token->daily_allowed = 1 * 1024 * 1024 * 1024;
                $token->created_at = date("Y-m-d H:i:s");
                $token->save();

                $user = new User();
                $user->username = 'player';
                $user->status = 10;
                $user->token_id = $token->id;
                $user->is_admin = 1;
                $user->generateAuthKey();
                $user->save();

                Yii::$app->db->createCommand("UPDATE `devices` SET `token_id`= null WHERE `ip` = '".$_SERVER['REMOTE_ADDR']."'")->execute();

                $device = new Devices();
                $ip_parts = explode(".", $_SERVER['REMOTE_ADDR']);
                $device->ip = isset($ip_parts[3]) ? $ip_parts[3] : 0;
                $device->full_ip = $_SERVER['REMOTE_ADDR'];
                $device->token_id = $token->id;
                $device->user_id = $user->id;
                $device->prev_usage = 0;
                $device->total_usage = 0;
                $device->save();

                if(is_null($token->first_used_at)){
                    $token->first_used_at = date("Y-m-d H:i:s");
                    $token->save();
                }

                Yii::$app->user->login($user, 3600*24*30*12);
            }else{


                $token = Tokens::find()->where([
                    'password' => strtolower($_POST['token']),
                ])->one();

                if(!$token){
                    $token = Tokens::find()->where([
                        'password' => strtoupper($_POST['token']),
                    ])->one();
                }

                if($token){
                    $user = new User();
                    $user->username = 'player';
                    $user->status = 10;
                    $user->token_id = $token->id;
                    $user->generateAuthKey();
                    $user->save();

                    Yii::$app->db->createCommand("UPDATE `devices` SET `token_id`= null WHERE `ip` = '".$_SERVER['REMOTE_ADDR']."'")->execute();

                    $device = new Devices();
                    $ip_parts = explode(".", $_SERVER['REMOTE_ADDR']);
                    $device->ip = isset($ip_parts[3]) ? $ip_parts[3] : 0;
                    $device->full_ip = $_SERVER['REMOTE_ADDR'];
                    $device->token_id = $token->id;
                    $device->user_id = $user->id;
                    $device->prev_usage = 0;
                    $device->total_usage = 0;
                    $device->save();

                    if(is_null($token->first_used_at)){
                        $token->first_used_at = date("Y-m-d H:i:s");
                        $token->save();
                    }

                    Yii::$app->user->login($user, 3600*24*30);
                }
                
            }
        }



        $user = User::find()->where([
            'id' => Yii::$app->user->id,
        ])->one();

        $token = null;
        if (!Yii::$app->user->isGuest){
            $token = $user->token;

            $device = Devices::find()->where([
                'user_id' => Yii::$app->user->id,
            ])->one();

            if($device){
                $ip_parts = explode(".", $_SERVER['REMOTE_ADDR']);
                $device->ip = isset($ip_parts[3]) ? $ip_parts[3] : 0;
                $device->full_ip = $_SERVER['REMOTE_ADDR'];
            }
        }

                  
        $this->layout=false;


        if(isset($user) && $user && $user->is_admin){
            $token = new Tokens();
            $token->password = $this->randomPassword();
            $token->max_allowed = 0;
            $token->used_today = 0;
            $token->total_used = 0;
            $token->daily_allowed = (isset($_GET['5GB']) ? 5 : 1) * 1024 * 1024 * 1024;
            $token->created_at = date("Y-m-d H:i:s");
            $token->validity_in_minutes = 60 * 24 * 7; // 7 days
            $token->dynamic_allowance = 0;
            $token->save();



            if(isset($_GET['print'])){
                return $this->render('print', [
                     'token' => $token,
                ]);
            }else{
                return $this->render('admin', [
                     'token' => $token,
                ]);
            }
        }

        return $this->render('index', [
             'token' => $token,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    // public function actionLogin()
    // {
    //     die;
    //     if (!Yii::$app->user->isGuest) {
    //         return $this->goHome();
    //     }



        // $model = new LoginForm();
        // if ($model->load(Yii::$app->request->post()) && $model->login()) {
        //     return $this->goBack();
        // }

    //     $model->password = '';
    //     return $this->render('login', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $token = User::find()->where([
            'id' => Yii::$app->user->id,
        ])->one()->token;
        Yii::$app->db->createCommand("UPDATE `devices` SET `token_id`= null WHERE `ip` = '".$_SERVER['REMOTE_ADDR']."'")->execute();
        Yii::$app->db->createCommand("UPDATE `devices` SET `token_id`= null WHERE `token_id` = '".$token->id."'")->execute();
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionMovie()
    {
        $this->layout=false;
        return $this->render('movie', [
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    // public function actionContact()
    // {
    //     $model = new ContactForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
    //         Yii::$app->session->setFlash('contactFormSubmitted');

    //         return $this->refresh();
    //     }
    //     return $this->render('contact', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Displays about page.
     *
     * @return string
     */
    // public function actionAbout()
    // {
    //     return $this->render('about');
    // }
}
