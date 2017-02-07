<?php
/*
*   ****************************
*   *       MenaPro 1.0        *
*   ****************************
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@menapro.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade MenaPro to newer
* versions in the future. If you wish to customize MenaPro for your
* needs please refer to http://menapro.com for more information.
*
*  @author Xenon media Burgos <contact25@menapro.com>
*  @copyright  2016 Xenon Media
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*
*  Proudly made in Burgos, Spain.
*
*/

namespace backend\controllers;

use Yii;
use common\models\Configuration;
use backend\models\UploadForm;
use backend\components\Controller;
use yii\helpers\FileHelper;
use yii\validators\EmailValidator;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\Url;
use backend\models\UploadthemeForm;
use common\components\Tools;
use yii\helpers\Json;
use common\models\Content;


/**
 * ConfigurationController implements the CRUD actions for Configuration model.
 */
class ConfigurationController extends Controller
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

        ];
    }


    public function actionWebname()
    {
        $post = Yii::$app->request->post();

        if (trim($post['value']) == "") {
            $this->jsonResponse(false, null, ['error' => Yii::t('app', 'Web name cannot be empty')]);
        } else {
            $this->updateConfigParam("_WEB_NAME_", $post['value']);
        }

    }

    public function actionGeneraltheme()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_DEFAULT_THEME_", $post['value']);

    }

    public function actionToggleautosave()
    {

        $post = Yii::$app->request->post();
        $this->updateConfigParam("_AUTOSAVE_", isset($post['value']) ? '1' : '0');

    }

    public function actionFacebook()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_FACEBOOK_", $post['value']);

    }

    public function actionTwitter()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_TWITTER_", $post['value']);

    }

    public function actionInstagram()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_INSTAGRAM_", $post['value']);

    }

    public function actionPinterest()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_PINTEREST_", $post['value']);

    }

    public function actionYoutube()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_YOUTUBE_", $post['value']);

    }

    public function actionUaanalytics()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_UA_ANALYTICS_", $post['value']);

    }

    public function actionTogglecookiesnotification()
    {
        $post = Yii::$app->request->post();
        $this->clearcache();
        $this->updateConfigParam("_COOKIES_NOTIFICATION_",(isset($post['value']) && $post['value']!="")?"1":"0");

    }
    public function actionTogglecache()
    {
        $post = Yii::$app->request->post();
        if(isset($post['value']) && $post['value']==""){
            $this->clearcache();
        }
        $this->updateConfigParam("_ENABLE_CACHE_",(isset($post['value']) && $post['value']!="")?"1":"0");


    }

    public function actionTogglebootstrap()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_BOOTSTRAP4_",(isset($post['value']) && $post['value']!="")?"1":"0");

    }

    public function actionTogglecompressor()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_COMPRESS_HTML_",(isset($post['value']) && $post['value']!="")?"1":"0");

    }

    public function actionGmapapikey()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_GMAP_API_KEY_", $post['value']);

    }

    public function actionEmail()
    {
        $post = Yii::$app->request->post();

        $validator = new EmailValidator();
        if ($validator->validate($post['value'], $error)) {
            $this->updateConfigParam("_EMAIL_", $post['value']);
        } else
            $this->jsonResponse(false, null, ['error' => $error]);

    }

    public function actionAddress()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_ADDRESS_", $post['value']);

    }


    public function actionPhone()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_PHONE_", $post['value']);

    }

    public function actionMobilephone()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_MOBILE_PHONE_", $post['value']);

    }

    public function actionOpeninghours()
    {
        $post = Yii::$app->request->post();
        $this->updateConfigParam("_OPENING_HOURS_", $post['value']);

    }

    /**
     * Finds the Configuration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Configuration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Configuration::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpload()
    {
        $dta = Yii::$app->request->post();
        $name = $dta['name'];
        $model = new UploadForm();
        $ext = 'png';
        if ($name == 'favicon') {
            $ext = 'ico';
        }
        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstanceByName('imageFile[0]');

            $model->imageFile->name = Yii::getAlias('@menaBase') . '/img/' . $name . '.' . $model->imageFile->extension;
            if ($model->upload()) {
                if ($model->imageFile->extension == 'jpeg' || $model->imageFile->extension == 'jpg') {
                    chmod($model->imageFile->name, 0777);
                    $img = imagecreatefromjpeg($model->imageFile->name);
                    $newFilename = Yii::getAlias('@menaBase') . '/img/' . $name . '.png';
                    imagepng($img, $newFilename);
                    chmod($newFilename, 0777);
                    unlink($model->imageFile->name);
                }
                die(json_encode(array('success' => true, 'image' => Url::base() . '/../img/' . $name . '.' . $ext.'?'.intval(microtime(true)), 'name' => $name)));
            } else {
				 $this->jsonResponse(false, null, ['error' => Yii::t('app','Error uploading file')]);
				//die(json_encode(array('success' => false,'error'=>'Error uploading file.')));
            }
        }
    }


    /*********************************FUNCTIONS FOR THEME INSTALLER & REMOVER******************************************/
    public function actionAddtheme()
    {
        $dta = Yii::$app->request->post();

        $hashed_password = crypt(time(), time());
        $secure = sha1($hashed_password);
        $name = $secure;
        $model = new UploadthemeForm();


        if (Yii::$app->request->isPost) {
            $model->themeFile = UploadedFile::getInstanceByName('themeFile[0]');

            $model->themeFile->name = Yii::getAlias('@menaBase') . '/upload/' . $name . '.zip';
            if ($model->upload()) {
                chmod($model->themeFile->name, 0777);
                if (Tools::ZipExtract(Yii::getAlias('@menaBase') . '/upload/' . $name . '.zip', Yii::getAlias('@menaBase') . '/extract/' . $name . '/')) {

                    chmod(Yii::getAlias('@menaBase') . '/extract/' . $name, 0777);
                    $files = glob(Yii::getAlias('@menaBase') . '/extract/' . $name . '/*', GLOB_ONLYDIR);

                    foreach ($files as $file) {

                        if (is_dir($file)) {
                            if ($this->checkTheme($file)) {
                                if ($this->validateTheme($file)) {
                                    //copy files and add block in db
                                    if ($this->copyThemeFiles($file)) {
                                        if ($this->deleteThemeTempFiles($file)) {
                                            die(json_encode(array('success' => true)));
                                        } else {
											  $this->jsonResponse(false, null, ['error' => Yii::t('app','Error deleting files')]);
                                            //die(json_encode(array('success' => false, 'error' => 'Error deleting files')));
                                        }
                                    } else {
										  $this->jsonResponse(false, null, ['error' => Yii::t('app','Error copying files')]);
                                        //die(json_encode(array('success' => false, 'error' => 'Error copying files')));
                                    }

                                } else {
									 $this->jsonResponse(false, null, ['error' =>  Yii::t('app','Error invalid theme files')]);
                                    //die(json_encode(array('success' => false, 'error' => 'Error invalid files')));
                                }
                            } else {
								 $this->jsonResponse(false, null, ['error' => Yii::t('app','Error unofficial theme')]);
                                //die(json_encode(array('success' => false, 'error' => 'Error unofficial theme')));
                            }


                        }
                    }
                } else {
					 $this->jsonResponse(false, null, ['error' => Yii::t('app','Error extracting files')]);
                    //die(json_encode(array('success' => false, 'error' => 'Error extracting files')));
                }

            } else {
				 $this->jsonResponse(false, null, ['error' => Yii::t('app','Error uploading files')]);
                //die(json_encode(array('success' => false, 'error' => 'Error uploading files')));
            }
        }
    }

    public function checkTheme($file_path)
    {
        return true;
    }

    public function validateTheme($theme_path)
    {
        $theme_name = explode('/', $theme_path);
        $theme_name = $theme_name[sizeof($theme_name) - 1];
        $check_sum = 0;
//        if($this->checkBlock($block_path)){
        if (file_exists($theme_path . '/views/')) {
            $check_sum++;
        }
        if (file_exists($theme_path . '/views/layouts/')) {
            $check_sum++;
        }
        if (file_exists($theme_path . '/views/layouts/main.php')) {
            $check_sum++;
        }

        if ($check_sum >= 3) {
            return true;
        } else {
            return false;
        }

    }

    public function copyThemeFiles($theme_tmp_path)
    {
        $theme_name = explode('/', $theme_tmp_path);
        $theme_name = $theme_name[sizeof($theme_name) - 1];

        mkdir(Yii::getAlias('@menaBase') . '/themes/' . $theme_name);//@todo: if exits¿?
        chmod(Yii::getAlias('@menaBase') . '/themes/' . $theme_name, 0777);
        FileHelper::copyDirectory($theme_tmp_path, Yii::getAlias('@menaBase') . '/themes/' . $theme_name);
        if(is_dir( Yii::getAlias('@menaBase') . '/themes/' . $theme_name)){
            return true;
        }else{
            return false;
        }
        //$res = Tools::recurse_copy($theme_tmp_path, Yii::getAlias('@menaBase') . '/themes/' . $theme_name);
        //return $res;
    }

    public function deleteThemeTempFiles($theme_tmp_path)
    {
        $theme_name = explode('/', $theme_tmp_path);
        $tmp_folder_name = $theme_name[sizeof($theme_name) - 2];
        $theme_name = $theme_name[sizeof($theme_name) - 1];
        $tmp_folder_path = str_replace('/' . $theme_name, '', $theme_tmp_path);

        //$res = Tools::delete_directory($theme_tmp_path);
        FileHelper::removeDirectory($theme_tmp_path);
        if (is_dir($theme_tmp_path)) {
       // if (!$res) {
            return false;
        } else {
            if (!rmdir($tmp_folder_path)) {
                return false;
            } else {
                if (!unlink(Yii::getAlias('@menaBase') . '/upload/' . $tmp_folder_name . '.zip')) {
                    return false;
                }
                return true;
            }
        }

    }

    public function actionDeletetheme()
    {
        $dta = Yii::$app->request->post();

        $theme_name = $dta['id'];

        FileHelper::removeDirectory(Yii::getAlias('@menaBase') . '/themes/' . $theme_name);
        if (!is_dir(Yii::getAlias('@menaBase') . '/themes/' . $theme_name)) {

            $vars = array('autosave' => Configuration::getValue('_AUTOSAVE_'));
            die(json_encode(array('success' => true, 'vars' => $vars)));

        } else {
            die(json_encode(array('success' => false, 'msg' => 'Error deleting files')));
        }
    }

    public function actionClearcache()
    {
        die(json_encode(['success' => $this->clearcache()]));
    }
    public function clearcache()
    {
        Content::updateAll(['date_upd'=>date('Y-m-d H:i')]);
        FileHelper::removeDirectory(Yii::getAlias('@menaBase/public_assets/css-compress'));
        FileHelper::removeDirectory(Yii::getAlias('@menaBase/public_assets/js-compress'));
        FileHelper::removeDirectory(Yii::getAlias('@menaBase/thumbs/i'));
        return Yii::$app->cacheFrontend->flush();
    }

    /**
     * @param $param
     * @param $value
     * @param $id
     */
    private function updateConfigParam($param, $value)
    {
        $model = Configuration::findOne([
            'name' => $param
        ]);

        if ($model->value != $value) {
            $model->value = $value;

            if ($model->update()) {
                Yii::$app->cacheFrontend->flush();
                $this->jsonResponse(true, $model, [
                    'value' => $model->value
                ]);
            } else {
                $this->jsonResponse(false, $model,
                    [
                        'error' => Yii::t('app', "Could not update this parameter"),
                        'value' => $model->value
                    ]);
            }

        }

        $this->jsonResponse(true, $model, [
            'value' => $model->value
        ]);

        return;
    }

    private function jsonResponse($success = true, $model = null, array $additionalData = null)
    {
        $response = [];
        if (!is_null($model))
            $response['model'] = $model;

        if (!is_null($additionalData))
            $response = array_merge($response, $additionalData);


        $response['success'] = $success;

        echo Json::encode($response);
        Yii::$app->end();


    }

}
