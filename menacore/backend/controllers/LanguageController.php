<?php

namespace backend\controllers;

use Yii;
use common\models\Language;
use common\models\ContentLang;
use common\models\Block;
use yii\data\ActiveDataProvider;

use backend\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LanguageController implements the CRUD actions for Language model.
 */
class LanguageController extends Controller
{
    public $available_languages=[
        "es-ES"=>"Español",

        "en-US"=>"‭English (American)",

        "de-DE"=>"Deutsch",

        "fr-FR"=>"Français",

        "ar-OM"=>"‭Arabic (Oman) ‮(عربية)"

        ,"ar-SY"=>"‭Arabic (Syria) ‮(عربية)"

        ,"id-ID"=>"Bahasa Indonesia"

        ,"bs-BA"=>"Bosanski"

        ,"bg-BG"=>"‭Bulgarian (Български)"

        ,"cs-CZ"=>"Český"

        ,"zh-CN"=>"‭Chinese (Simplified) (简体中文)"

        ,"zh-TW"=>"‭Chinese (Traditional) (正體中文)"

        ,"da-DK"=>"Dansk"

        ,"et-EE"=>"Eesti"

        ,"fa-IR"=>"‭Farsi (Persian) ‮(فارسی)"

        ,"el-GR"=>"‭Greek (Ελληνικά)"

        ,"he-IL"=>"‭Hebrew ‮(עברית)"

        ,"hr-HR"=>"Hrvatski"

        ,"is-IS"=>"Íslenska"

        ,"it-IT"=>"Italiano"

        ,"ja-JP"=>"‭Japanese (日本語)"

        ,"km-KH"=>"‭Khmer (ខមែរ)"

        ,"ko-KR"=>"‭Korean (한국어)"

        ,"lv-LV"=>"Latviešu"

        ,"lt-LT"=>"Lietuvių"

        ,"mk-MK"=>"‭Macedonian (Македонски)"

        ,"hu-HU"=>"Magyar"

        ,"nl-NL"=>"Nederlands"

        ,"nb-NO"=>"‭Norsk (bokmål)"

        ,"nn-NO"=>"‭Norsk (nynorsk)"

        ,"pl-PL"=>"Polski"

        ,"pt-PT"=>"Português"

        ,"pt-BR"=>"Português do Brasil"

        ,"ro-RO"=>"Română"

        ,"ru-RU"=>"‭Russian (Русский)"

        ,"sk-SK"=>"Slovenský"

        ,"sl-SI"=>"Slovensko"

        ,"fi-FI"=>"Suomi"

        ,"sv-SE"=>"Svenska"

        ,"th-TH"=>"‭Thai (ภาษาไทย)"

        ,"uk-UA"=>"‭Ukrainian (Українська)"];
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

    /**
     * Lists all Language models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Language::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Language model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Language model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Language();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_lang]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Language model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_lang]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Language model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionDelete()
    {
		$dta = Yii::$app->request->post();
        $id = $dta['id'];
		if($this->findModel($id)->delete()){
           die(json_encode(array('success' => true,'id_lang'=>$id)));
       }else {
           throw new \yii\web\HttpException(500, Yii::t('app', 'Error deletinglang'));

       }
    }
    public function actionToggleactive()
    {

        $dta = Yii::$app->request->post();
        $id = $dta['id'];

        if ($id != '') {
            $model = $this->findModel($id);
        }
        $data['Language']['active'] = $model['active']? 0 : 1;

        if ($model->load($data) && $model->save()){
            die(json_encode(array('success' => true, 'status' => $data['Language']['active'],'id'=>$id,'iso'=>$model->iso_code,'name'=>$model->name)));

        } else {
            throw new \yii\web\HttpException(500, Yii::t('app', 'Error updating lang'));

        }
    }
    public function actionAddlang()
    {

        $dta = Yii::$app->request->post();

        $value=$dta['value'];
        $iso=explode('-',$value);
        $country_code=$iso[1];
        $iso=$iso[0];

        $isset=Language::findByIso($iso);
        if(!$isset){
            //No está el lenguaje instalado
            $ref_lang='en-US';
            $id_ref_lang=1;


            $new_model = new Language();
            $new_model->name=$this->available_languages[$dta['value']];
            $new_model->iso_code=$iso;
            $new_model->country_code=$country_code;
            $new_model->active=1;
            $new_model->img=$iso.'.png';

            if($new_model->save()){
                $id=$new_model->id_lang;
                //añado por cada content existente su traduccion en el nuevo idioma
                $to_translate=ContentLang::find()->where(['id_lang'=>$id_ref_lang])->all();
                foreach($to_translate as $ref_content){
                    $ml=new ContentLang();
                    $ml->id_lang=$id;
                    $ml->id_content=$ref_content->id_content;
                    $ml->title=$ref_content->title;
                    $ml->meta_title=$ref_content->meta_title;
                    $ml->meta_description=$ref_content->meta_description;
                    $ml->link_rewrite=$ref_content->link_rewrite;
                    $ml->menu_text=$ref_content->menu_text;
                    $ml->save();
                }


                //Comented for doing test of addLang callback

                $blocks=Block::find()->all();
                foreach($blocks as $block){
                    if(is_dir (Yii::getAlias('@blocks').'/'.$block->prefix.'/messages')) {
                        if(!is_dir(Yii::getAlias('@blocks').'/'.$block->prefix.'/messages/'.$iso.'-'.$country_code)){
                            mkdir(Yii::getAlias('@blocks').'/'.$block->prefix.'/messages/'.$iso.'-'.$country_code);
                        }
                        if (file_exists(Yii::getAlias('@blocks') . '/' . $block->prefix . '/messages/'.$ref_lang.'/' . $block->prefix . '.php')) {
                            if(!file_exists(Yii::getAlias('@blocks').'/'.$block->prefix.'/messages/'.$iso.'-'.$country_code.'/'.$block->prefix.'.php')){
                                copy(Yii::getAlias('@blocks').'/'.$block->prefix.'/messages/'.$ref_lang.'/'.$block->prefix.'.php',Yii::getAlias('@blocks').'/'.$block->prefix.'/messages/'.$iso.'-'.$country_code.'/'.$block->prefix.'.php');
                            }
                        }
                    }
                }
                if(!is_dir(Yii::getAlias('@common').'/messages/'.$iso.'-'.$country_code)){
                    mkdir(Yii::getAlias('@common').'/messages/'.$iso.'-'.$country_code);
               }
               if(!file_exists(Yii::getAlias('@common').'/messages/'.$iso.'-'.$country_code.'/app.php')){
                   copy(Yii::getAlias('@common').'/messages/'.$ref_lang.'/app.php',Yii::getAlias('@common').'/messages/'.$iso.'-'.$country_code.'/app.php');
               }
               if(!file_exists(Yii::getAlias('@common').'/messages/'.$iso.'-'.$country_code.'/yii.php')){
                    copy(Yii::getAlias('@common').'/messages/'.$ref_lang.'/yii.php',Yii::getAlias('@common').'/messages/'.$iso.'-'.$country_code.'/yii.php');
               }

            }
            die(json_encode(array('success' => true, 'already_installed' =>false,'id'=> $id,'iso'=>$iso,'name'=> $new_model->name)));
        }else{
            //El lenguaje ya está instalado
            die(json_encode(array('success' => true, 'already_installed' =>true)));
        }
    }
    /**
     * Finds the Language model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Language the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Language::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app','The requested page does not exist.'));
        }
    }
}
