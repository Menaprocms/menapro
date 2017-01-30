<?php

namespace backend\controllers;


use common\widgets\Pagesbar;
use Yii;
use common\models\Block;
use common\models\BlockLang;
use common\models\Language;
use common\models\Configuration;
use backend\models\User;
use yii\filters\VerbFilter;

use backend\components\Controller;


class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
            'class' => \yii\filters\AccessControl::className(),
            'rules' => [
                // allow authenticated users
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
                // everything else is denied
            ],
        ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {

        return parent::beforeAction($action);
    }

    public function actionUnlockfields(){
       $dta = Yii::$app->request->post();

       $pass=$dta['value'];

       $user_id= Yii::$app->user->getId();
       $user=User::find()->where(['id'=>$user_id])->one();
       $vars = array('autosave' => Configuration::getValue('_AUTOSAVE_'));
       if($user->validatePassword($pass)){
           die(json_encode(array('success' => true,'vars'=>$vars)));
       }else{
           throw new \yii\web\HttpException(500, Yii::t('app', 'Not authorized'));
       }
    }
    public function actionChangepass(){
        $dta = Yii::$app->request->post();

        $newpass=$dta['value'];
        $user_id= Yii::$app->user->getId();
        $user=User::find()->where(['id'=>$user_id])->one();
        $vars = array('autosave' => Configuration::getValue('_AUTOSAVE_'));
        $user->updatePassword($newpass);


        if($user->save()){
            die(json_encode(array('success' => true,'vars'=>$vars)));
        }else{
            throw new \yii\web\HttpException(500, Yii::t('app', 'Can´t change user password'));
        }

    }
    public function actionChangeusername(){
        $dta = Yii::$app->request->post();
        $newusername=$dta['value'];
        $user_id= Yii::$app->user->getId();
        $user=User::find()->where(['id'=>$user_id])->one();

        $user->username=$newusername;
        $vars = array('autosave' => Configuration::getValue('_AUTOSAVE_'));

        if($user->save()){
            die(json_encode(array('success' => true,'vars'=>$vars)));
        }else{
            throw new \yii\web\HttpException(500, Yii::t('app', 'Can´t change username'));
        }

    }
    public function actionChangeemail(){
        $dta = Yii::$app->request->post();
        $newemail=$dta['value'];
        $user_id= Yii::$app->user->getId();
        $user=User::find()->where(['id'=>$user_id])->one();

        $user->email=$newemail;
        $vars = array('autosave' => Configuration::getValue('_AUTOSAVE_'));



        if($user->save()){
            die(json_encode(array('success' => true,'vars'=>$vars)));
        }else{
            throw new \yii\web\HttpException(500, Yii::t('app', 'Can´t change user email'));
        }

    }
    public function actionChangelang(){
        $dta = Yii::$app->request->post();

        $lang=$dta['value'];
        $user_id= Yii::$app->user->getId();
        $user=User::find()->where(['id'=>$user_id])->one();

        $user->lang=$lang;
        $vars = array('autosave' => Configuration::getValue('_AUTOSAVE_'));



        if($user->save()){
            die(json_encode(array('success' => true,'vars'=>$vars)));
        }else{
            throw new \yii\web\HttpException(500, Yii::t('app', 'Can´t change user language'));
        }

    }




}
