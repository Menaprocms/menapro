<?php

namespace frontend\controllers;



use Yii;
use common\models\Block;
use common\models\BlockLang;
use common\models\Configuration;
use yii\filters\VerbFilter;
use backend\models\UploadblockForm;
use yii\web\UploadedFile;
use common\components\Tools;
use backend\components\Controller;
use ReflectionClass;
use yii\captcha\Captcha;
use yii\captcha\CaptchaAction;
use yii\captcha\CaptchaValidator;
use yii\helpers\Url;

/**
 * ContentController implements the CRUD actions for Content model.
 */
class BlockController extends Controller
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
                [
                    'actions' => ['block'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
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
        public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    public function beforeAction($action)
    {

        return parent::beforeAction($action);
    }
    public function actionBlock(){
        $data=Yii::$app->request->post();
        if(empty($data)){
            $data=Yii::$app->request->get();        
        }

        if(isset($data['block']) && isset($data['action'])){
            $block=$data['block'];
            $action=$data['action'];
            $ok=false;
            if(isset($data['captcha'])) {
                $captcha =$data['captcha'];

                $cval=new CaptchaValidator();

                if ($cval->validate($captcha)) {
                    $ok=true;
                }else{
                    die(json_encode(array('success'=>false,'captcha'=>false)));
                }
            }else{
                $ok=true;
            }


            if($ok) {
				$this->callModule($block,$action,$data);
            }
        }else{
            die('params missed');
        }

    }

protected function callModule($block,$action,$data){
        $name=$block.'module';
        $class = '\blocks\\' . $block . '\frontend\\'.$name.'\\'.ucfirst($block);
        if (file_exists(Yii::getAlias('@blocks/'). $block . '/frontend/'.$name.'/'.ucfirst($block). '.php')) {
            $contr_class='\blocks\\' . $block . '\frontend\\'.$name.'\\controllers\\'.ucfirst($block).'Controller';
            if (file_exists(Yii::getAlias('@blocks/'). $block . '/frontend/'.$name.'/controllers/'.ucfirst($block).'Controller.php')) {
                $reflection = new \ReflectionClass($contr_class);
                if ($reflection->hasMethod('action'.ucfirst($action))) {
                    Yii::$app->setModule($name, ['class' => $class]);
                    $module = Yii::$app->getModule($name);
                    $module->runAction($block . '/' . $action, ['data' => $data]);
                }else{
                    die(json_encode(array('success'=>false,'msg'=>Yii::t('app','Cannot found function in controller.'))));
                }
            }else{
                die(json_encode(array('success'=>false,'msg'=>Yii::t('app','Cannot found Module´s controller file.'))));
            }
        }else{
            die(json_encode(array('success'=>false,'msg'=>Yii::t('app','Cannot found Module class file.'))));
        }
    }

    /**
     * Lists all Content models.
     * @return mixed
     */
    public function actionIndex()
    {

    }

    /**
     * Displays a single Content model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)    {


        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
}
