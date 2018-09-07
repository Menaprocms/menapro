<?php

namespace backend\controllers;


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
use yii\helpers\FileHelper;
use yii\helpers\Json;

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
                        'roles' => ['@'],
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

    public function beforeAction($action)
    {

        return parent::beforeAction($action);
    }

    public function actionBlock()
    {
        $data = Yii::$app->request->post();
        if (empty($data)) {
            $data = Yii::$app->request->get();
        } else {
            if (isset($data['data'])) {
                $data = $data['data'];
            }
        }

        if (isset($data['block']) && isset($data['action'])) {
            $block = $data['block'];
            $action = $data['action'];

            $class = 'blocks\\' . $block . '\backend\controllers\\' . ucfirst($block) . 'Controller';

            $reflection = new ReflectionClass($class);
            if ($reflection->hasMethod('action' . ucfirst($action))) {
                call_user_func(array($class, 'action' . ucfirst($action)));
            }


        }

        die('params missed');


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
    public function actionView($id)
    {


        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDelete()
    {
        $dta = Yii::$app->request->post();

        $block_id = $dta['id'];
        $block = Block::find()->where(['id' => $block_id])->one();
        $name = $block->prefix;
        FileHelper::removeDirectory(Yii::getAlias('@menaBase') . '/blocks/' . $name);
        if (!is_dir(Yii::getAlias('@menaBase') . '/blocks/' . $name)) {
            if ($block->delete()) {
                foreach (Yii::$app->params['all_langs'] as $lang) {
                    $block_lang = BlockLang::find()->where(['id_block' => $block_id])->andWhere(['id_lang' => $lang->id_lang])->one();
                    if (!$block_lang) {
                    } else {
                        if (!$block_lang->delete()) {
                            die(json_encode(array('success' => false, 'msg' => Yii::t('app', 'Error deleting block_lang model'))));
                        }
                    }
                }
                $vars = array('autosave' => Configuration::getValue('_AUTOSAVE_'));
                die(json_encode(array('success' => true, 'vars' => $vars)));
            } else {
                die(json_encode(array('success' => false, 'msg' => Yii::t('app', 'Error deleting block model'))));
            }
        } else {
            die(json_encode(array('success' => false, 'msg' => Yii::t('app', 'Error deleting block files'))));
        }


    }

    public function addBlockLangFields($id_block, $name)
    {
        $success = true;
        foreach (Yii::$app->params['all_langs'] as $lang) {
            $block_lang = new BlockLang();
            $dataL['BlockLang']['id_lang'] = $lang->id_lang;
            $dataL['BlockLang']['id_block'] = $id_block;
            $dataL['BlockLang']['name'] = (string)$name;
            if ($block_lang->load($dataL) && $block_lang->save()) {

            } else {
                $success = false;
            }
        }
        if (!$success) {
            foreach (Yii::$app->params['all_langs'] as $lang) {
                $block_lang = BlockLang::find()->where(['id_block' => $id_block])->andWhere(['id_lang' => $lang->id_lang])->one();
                if (!$block_lang) {

                } else {
                    $block_lang->delete();
                }
            }
            return $success;
        } else {
            return $success;
        }


    }

    public function actionUpload()
    {
        $dta = Yii::$app->request->post();

        $hashed_password = crypt(time(), time());
        $secure = sha1($hashed_password);
        $name = $secure;
        $model = new UploadblockForm();


        if (Yii::$app->request->isPost) {
            $model->blockFile = UploadedFile::getInstanceByName('blockFile[0]');

            $model->blockFile->name = Yii::getAlias('@menaBase') . '/upload/' . $name . '.zip';
            if ($model->upload()) {
                chmod($model->blockFile->name, 0777);
                if (Tools::ZipExtract(Yii::getAlias('@menaBase') . '/upload/' . $name . '.zip', Yii::getAlias('@menaBase') . '/extract/' . $name . '/')) {

                    chmod(Yii::getAlias('@menaBase') . '/extract/' . $name, 0777);
                    $files = glob(Yii::getAlias('@menaBase') . '/extract/' . $name . '/*', GLOB_ONLYDIR);

                    foreach ($files as $file) {

                        if (is_dir($file)) {
                            if (file_exists($file . '/config.xml')) {
                                @chmod($file . '/config.xml', 0777);
                                if ($xml_data = simplexml_load_file($file . '/config.xml')) {
                                    if (!Block::isBlockInstalled((string)$xml_data->prefix, (string)$xml_data->version)) {
                                        if ($this->checkBlock($file)) {
                                            if ($this->validateBlock($file)) {
                                                //copy files and add block in db
                                                if ($this->copyBlockFiles($file)) {


                                                    $block = new Block();
                                                    $data['Block']['active'] = 1;
                                                    $data['Block']['block_default'] = 0;
                                                    $data['Block']['prefix'] = (string)$xml_data->prefix;
                                                    $data['Block']['position'] = (int)$xml_data->position;
                                                    $data['Block']['version'] = (string)$xml_data->version;
                                                    $data['Block']['configurable'] = (int)$xml_data->configurable;

                                                    if ($block->load($data) && $block->save()) {
                                                        if ($this->addBlockLangFields($block->id, $xml_data->name)) {
                                                            if ($this->deleteBlockTempFiles($file)) {
                                                                die(json_encode(array('success' => true)));
                                                            } else {
                                                                $this->deleteBlockTempFiles($file);
                                                                $this->jsonResponse(false, null, ['error' => Yii::t('app', 'Error deleting files')]);
                                                            }
                                                        } else {
                                                            $this->deleteBlockTempFiles($file);
                                                            $this->jsonResponse(false, null, ['error' => Yii::t('app', 'Error saving block_lang fields on DB')]);
                                                        }

                                                    } else {
                                                        $this->deleteBlockTempFiles($file);
                                                        $this->jsonResponse(false, null, ['error' => Yii::t('app', 'Error saving block on DB')]);
                                                    }

                                                } else {
                                                    $this->deleteBlockTempFiles($file);
                                                    $this->jsonResponse(false, null, ['error' => Yii::t('app', 'Error copying files')]);
                                                }

                                            } else {
                                                $this->deleteBlockTempFiles($file);
                                                $this->jsonResponse(false, null, ['error' => Yii::t('app', 'Error invalid block structure')]);
                                            }
                                        } else {
                                            $this->deleteBlockTempFiles($file);
                                            $this->jsonResponse(false, null, ['error' => Yii::t('app', 'Error unofficial block')]);
                                        }
                                    } else {
                                        $this->deleteBlockTempFiles($file);
                                        $this->jsonResponse(false, null, ['error' => Yii::t('app', 'This block is already installed')]);
                                    }
                                } else {
                                    $this->deleteBlockTempFiles($file);
                                    $this->jsonResponse(false, null, ['error' => Yii::t('app', 'Can not read config.xml')]);
                                }
                            } else {
                                $this->deleteBlockTempFiles($file);
                                $this->jsonResponse(false, null, ['error' => Yii::t('app', 'File config.xml does not exists.')]);
                            }

                        }
                    }
                } else {
                    //@todo:all files if has been extracted
                    $this->deleteBlockTempFiles(Yii::getAlias('@menaBase') . '/upload/' . $name . '.zip');
                    $this->jsonResponse(false, null, ['error' => Yii::t('app', 'Error extracting files')]);
                }

            } else {
                $this->jsonResponse(false, null, ['error' => Yii::t('app', 'Error uploading file')]);
            }
        }
    }

    public function copyBlockFiles($block_tmp_path)
    {
        $block_name = explode('/', $block_tmp_path);
        $block_name = $block_name[sizeof($block_name) - 1];

        //fixme: Usar alias de menablocks
        mkdir(Yii::getAlias('@menaBase') . '/blocks/' . $block_name);//@todo: if exits¿?
        chmod(Yii::getAlias('@menaBase') . '/blocks/' . $block_name, 0777);

        FileHelper::copyDirectory($block_tmp_path, Yii::getAlias('@menaBase') . '/blocks/' . $block_name);
        if (is_dir(Yii::getAlias('@menaBase') . '/blocks/' . $block_name)) {
            return true;
        } else {
            return false;
        }

    }

    public function deleteBlockTempFiles($block_tmp_path)
    {
        $block_name = explode('/', $block_tmp_path);
        $tmp_folder_name = $block_name[sizeof($block_name) - 2];
        $block_name = $block_name[sizeof($block_name) - 1];
        $tmp_folder_path = str_replace('/' . $block_name, '', $block_tmp_path);


        FileHelper::removeDirectory($block_tmp_path);
        if (is_dir($block_tmp_path)) {
            return false;
        } else {
            FileHelper::removeDirectory($tmp_folder_path);
            if (is_dir($tmp_folder_path)) {
                return false;
            } else {
                FileHelper::removeDirectory(Yii::getAlias('@menaBase') . '/upload/' . $tmp_folder_name . '.zip');
                if (is_dir(Yii::getAlias('@menaBase') . '/upload/' . $tmp_folder_name . '.zip')) {
                    return false;
                }
                return true;
            }
        }

    }

    public function validateBlock($block_path)
    {
        $block_name = explode('/', $block_path);
        $block_name = $block_name[sizeof($block_name) - 1];
        $check_sum = 0;

        if (file_exists($block_path . '/config.xml')) {
            $check_sum++;
        }
        if (file_exists($block_path . '/backend/')) {
            $check_sum++;
        }
        if (file_exists($block_path . '/backend/js/')) {
            $check_sum++;
        }
        if (file_exists($block_path . '/backend/js/' . $block_name . '.js')) {
            $check_sum++;
        }
        if (file_exists($block_path . '/backend/views/')) {
            $check_sum++;
        }
        if (file_exists($block_path . '/backend/views/' . $block_name . '_panel.php')) {
            $check_sum++;
        }
        if (file_exists($block_path . '/frontend/')) {
            $check_sum++;
        }
        if (file_exists($block_path . '/frontend/views/')) {
            $check_sum++;
        }
        if (file_exists($block_path . '/frontend/views/' . $block_name . '.php')) {
            $check_sum++;
        }

        if ($check_sum >= 9) {
            return true;
        } else {
            return false;
        }


    }

    public function checkBlock($file_path)
    {
        return true;
    }

    public function actionMapsuggestion()
    {
        $dta = Yii::$app->request->get();
        $address = trim($dta['q']);
        $address = str_replace(' ', '+', $address);
        $url = 'https://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false&search=true';
        $res = file_get_contents($url);
        $arr = json_decode($res);

        $response = array();
        foreach ($arr->results as $k => $v) {
            $response[] = array('value' => $v->formatted_address);
        }
        die(json_encode($response));
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
