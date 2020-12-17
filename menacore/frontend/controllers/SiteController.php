<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use frontend\components\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Content;
use yii\web\UrlRule;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public function beforeAction($action) {
        if($action->id == 'accept-cookies'){
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'accept-cookies'],
                'rules' => [
                    [
                        'actions' => ['accept-cookies'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'accept-cookies' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'foreColor'=>3355443,
                'transparent'=>true,
                'height'=>35,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }





//    /**
//     * Requests password reset.
//     *
//     * @return mixed
//     */
//    public function actionRequestPasswordReset()
//    {
//        $model = new PasswordResetRequestForm();
//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            if ($model->sendEmail()) {
//                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
//
//                return $this->goHome();
//            } else {
//                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
//            }
//        }
//
//        return $this->render('requestPasswordResetToken', [
//            'model' => $model,
//        ]);
//    }


    public function addPrettyUrlRules(){
        $pages=Content::findAll(['in_trash'=>0]);

        $rules=array();
        foreach($pages as $k=>$page){
            $rules[]=array(
                'pattern'=>$page->langFields[0]->link_rewrite,
                'route'=>'content/view?id='.$page->id);
        }
        Yii::$app->urlManager->addRules($rules,false);
    }

    public function actionAcceptCookies(){
        Yii::$app->session->set('accept_cookies', true);
        return true;
    }
}
