<?php

namespace frontend\controllers;


use Yii;
use common\models\Content;
use common\models\Configuration;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use frontend\components\Controller;

/**
 * ContentController implements the CRUD actions for Content model.
 */
class ToolsController extends Controller
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

    /**
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Content::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

   public function actionSendmail(){

       $post = Yii::$app->request->post();
       $data=$post['data'];
       $para = $this->config['_EMAIL_'];
       $de = $data['email'];
       $mensaje = $data['message'];
       $name = $data['name'];
       $cabeceras = 'From: '.$de. "\r\n" .
//           'Reply-To: webmaster@example.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

       $succ=mail($para,$name, $mensaje, $cabeceras);
       die(json_encode(
           array('success' => $succ)));
   }
}
