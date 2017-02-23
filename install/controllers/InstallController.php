<?php
/**
 * Install controller
 */

class InstallController
{
    public  $db;
    public $installed_languages=[
        ["iso"=>"es-ES","name"=>"Español","id"=>1],

        ["iso"=>"en-US","name"=>"‭English (American)","id"=>2],

        ["iso"=>"de-DE","name"=>"Deutsch","id"=>3],

        ["iso"=>"fr-FR","name"=>"Français","id"=>4]];

    public $languages=[
        ["iso"=>"es-ES","name"=>"Español","id"=>1],

        ["iso"=>"en-US","name"=>"‭English (American)","id"=>2],

        ["iso"=>"de-DE","name"=>"Deutsch","id"=>3],

        ["iso"=>"fr-FR","name"=>"Français","id"=>4],

        ["iso"=>"ar-OM","name"=>"‭Arabic (Oman) ‮(عربية)"]

        ,["iso"=>"ar-SY","name"=>"‭Arabic (Syria) ‮(عربية)"]

        ,["iso"=>"id-ID","name"=>"Bahasa Indonesia"]

        ,["iso"=>"bs-BA","name"=>"Bosanski"]

        ,["iso"=>"bg-BG","name"=>"‭Bulgarian (Български)"]

        ,["iso"=>"cs-CZ","name"=>"Český"]

        ,["iso"=>"zh-CN","name"=>"‭Chinese (Simplified) (简体中文)"]

        ,["iso"=>"zh-TW","name"=>"‭Chinese (Traditional) (正體中文)"]

        ,["iso"=>"da-DK","name"=>"Dansk"]

        ,["iso"=>"et-EE","name"=>"Eesti"]

        ,["iso"=>"fa-IR","name"=>"‭Farsi (Persian) ‮(فارسی)"]

        ,["iso"=>"el-GR","name"=>"‭Greek (Ελληνικά)"]

        ,["iso"=>"he-IL","name"=>"‭Hebrew ‮(עברית)"]

        ,["iso"=>"hr-HR","name"=>"Hrvatski"]

        ,["iso"=>"is-IS","name"=>"Íslenska"]

        ,["iso"=>"it-IT","name"=>"Italiano"]

        ,["iso"=>"ja-JP","name"=>"‭Japanese (日本語)"]

        ,["iso"=>"km-KH","name"=>"‭Khmer (ខមែរ)"]

        ,["iso"=>"ko-KR","name"=>"‭Korean (한국어)"]

        ,["iso"=>"lv-LV","name"=>"Latviešu"]

        ,["iso"=>"lt-LT","name"=>"Lietuvių"]

        ,["iso"=>"mk-MK","name"=>"‭Macedonian (Македонски)"]

        ,["iso"=>"hu-HU","name"=>"Magyar"]

        ,["iso"=>"nl-NL","name"=>"Nederlands"]

        ,["iso"=>"nb-NO","name"=>"‭Norsk (bokmål)"]

        ,["iso"=>"nn-NO","name"=>"‭Norsk (nynorsk)"]

        ,["iso"=>"pl-PL","name"=>"Polski"]

        ,["iso"=>"pt-PT","name"=>"Português"]

        ,["iso"=>"pt-BR","name"=>"Português do Brasil"]

        ,["iso"=>"ro-RO","name"=>"Română"]

        ,["iso"=>"ru-RU","name"=>"‭Russian (Русский)"]

        ,["iso"=>"sk-SK","name"=>"Slovenský"]

        ,["iso"=>"sl-SI","name"=>"Slovensko"]

        ,["iso"=>"fi-FI","name"=>"Suomi"]

        ,["iso"=>"sv-SE","name"=>"Svenska"]

        ,["iso"=>"th-TH","name"=>"‭Thai (ภาษาไทย)"]

        ,["iso"=>"uk-UA","name"=>"‭Ukrainian (Українська)"]];

    public function renderPartial($path,$params=[]){
        extract($params);
        ob_start();
        include($path);
        return ob_get_clean();
    }
    private function jsonResponse($success = true,array $additionalData = null)
    {
        $response = [];
        if (!is_null($additionalData))
            $response = array_merge($response, $additionalData);


        $response['success'] = $success;
        die(json_encode($response));
    }
    public function runAction($action){
        date_default_timezone_set('UTC');
        $baseDir = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        $partial=false;
        $success=false;
        switch($action) {
            case 'index':

                $checkWritable = array();
                $checkWritable[]=__DIR__.'/../../config/config_data.php';
                $checkWritable[]=__DIR__.'/../../config/core_routes.php';
                $checkWritable[]=__DIR__.'/../../config/core_hashes.php';
                $checkWritable[]=__DIR__.'/../../config/';
                $checkWritable[]=__DIR__.'/../../manager/';
                $checkWritable[]=__DIR__.'/../../manager/assets/';
                $checkWritable[]=__DIR__.'/../../'.CORE_ROUTE.'/';
                $checkWritable[]=__DIR__.'/../';//install
                $checkWritable[]=__DIR__.'/../../';//rootfolder


                $give_permissions=array();

                $permissions_ok=true;

                $baseToReplace=$_SERVER['DOCUMENT_ROOT'];
                foreach($checkWritable as $k=>$v){
                    if(!is_writable($v)){
                        $permissions_ok=false;

                        $give_permissions[]=str_replace($baseToReplace,'',realpath($v));
                    }
                }
                if($permissions_ok) {

                    if(is_dir(__DIR__ . '/../../menacore')) {
                        $oldname = __DIR__ . '/../../menacore';
                        $newName = $this->generate_random_password(6);


                        if (!$rename = @rename($oldname, $oldname . $newName)) {
                            echo 'Could not rename menacore folder';
                        } else {
                            $path = __DIR__ . '/../../config/core_routes.php';
                            $text = "<?php

                        if(defined('MENAPRO675')){
                                defined('CORE_ROUTE') or define('CORE_ROUTE','menacore" . $newName . "');
                            }else{
                            die();
                        }";
                            if (file_put_contents($path, $text) == false) {
                                echo 'no copia el archivo core_routes.php';
                            }
                        }
                    }
                    $view = 'index.php';
                    include(__DIR__ . '/../views/layouts/main.php');
                }else{
                    if(sizeof($give_permissions)>1){
                        $html="Please give permissions to these files:<br>";
                    }else{
                        $html="Please give permissions to this file:<br>";
                    }

                    foreach($give_permissions as $k=>$v){
                        $html.= '- '.$v.'<br>';

                    }
                    echo $html;
                }
                break;
            case 'license';
                try {
                    if(!$license_text = file_get_contents(__DIR__ . '/../files/license.txt')){
                        $this->jsonResponse(false,array('errormsg'=>'Can not find License Agreement file.'));
                    }else{
                        $view =$this->renderPartial(__DIR__.'/../views/install/license.php',['license_text'=>$license_text]);
                        $this->jsonResponse(true,array('view'=>$view,'license_text'=>$license_text));
                    }
                }catch (\yii\base\Exception $e){
                    $this->jsonResponse(false,array('errormsg'=>'Can not find License Agreement file.'));
                }
                break;
            case 'firststep':
                $view =$this->renderPartial(__DIR__.'/../views/install/first_step.php',['langs'=>$this->languages]);
                $this->jsonResponse(true,array('view'=>$view));
                break;
            case 'secondstep':
                $this->secondstep();
                break;
            case 'checkdb':
                $data=$_POST['data'];
                $success=$this->checkDb($data);
                $this->jsonResponse($success);
                break;
            case 'removeinstall':
                $delete=$this->removeinstall();
                $this->jsonResponse(true,['delete'=>$delete]);
                break;
        }


    }
    public function checkDb($data){

        try {

            if($db=$this->getConnectionDb($data)){
                if ($db->connect_errno) {
                    return false;
                }else{
                    return true;
                }

            }else{
                return false;
            }

        } catch (yii\db\Exception $e) {
            $errorMsg = $e->getMessage();
            return false;
        }
    }
    public function generate_random_password($length = 10) {
        $alphabets = range('A','Z');
        $alphabets_min = range('a','z');
        $numbers = range('0','9');

        $final_array = array_merge($alphabets,$alphabets_min,$numbers);

        $password = '';

        while($length--) {
            $key = array_rand($final_array);
            $password .= $final_array[$key];
        }

        return $password;
    }
    public function getConnectionDb($data){
        $db_name=$data['db_name'];
        $db_user=$data['db_user'];
        $db_pass=$data['db_password'];
        $db_prefix=$data['db_prefix'];
        $db_server=$data['db_server'];

        return new mysqli($db_server,$db_user,$db_pass,$db_name);


    }
    public function writeConfigData($data){
        $lang=$data['language'];//substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5);
        $cookieValidationKey=$this->generate_random_password(32);
        $path=__DIR__.'/../../config/config_data.php';
        $text="<?php
        return [
            'language'=>'".$lang."',
            'components' => [
                 'db'=>[
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=".$data['db_server'].";dbname=".$data['db_name']."',
                    'username' => '".$data['db_user']."',
                    'password' => '".$data['db_password']."',
                     'tablePrefix' => '".$data['db_prefix']."',
                    'charset' => 'utf8',
                ],
                'request'=>[
                     'cookieValidationKey' => '".$cookieValidationKey."'
                ],
            ]
         ]
        ?>";
        if(@file_put_contents($path,$text)==false){
            return false;
        }else{
            return true;
        }

    }
    public function findLangData($iso){
        foreach($this->languages as $k=>$v){
            if($v['iso']===$iso){
                return $v;
            }
        }
        return false;
    }
    public function setDefaultLang($id_lang,$data){
        $db=$this->getConnectionDb($data);
        $prefix=$data['db_prefix'];
        $query="UPDATE ".$prefix."configuration SET value='".$id_lang."' WHERE name LIKE '_DEFAULT_LANG_'";
        if(!$db->query($query)){
            $db->close();
            return false;
        }
        $query2="UPDATE ".$prefix."language SET active=1 WHERE id_lang=".$id_lang.";";
        if(!$db->query($query2)){
            $db->close();
            return false;
        }
        $db->close();
        return true;
    }
    public function otherLangDbData($data){
        $prefix=$data['db_prefix'];
        $lang=$data['language'];
        $lang=$this->findLangData($lang);

        $iso=explode("-",$lang['iso']);

        $country_code=$iso[1];
        $iso=$iso[0];

        $db=$this->getConnectionDb($data);
        $query="INSERT INTO ".$prefix."language (`id_lang`, `iso_code`, `country_code`, `name`, `img`,`active`) VALUES ('5','".$iso."','".$country_code."','".$lang['name']."','other.png','0')";
        if(!$db->query($query)){

            return false;
        }else{
            $query2="SELECT * FROM ".$prefix."block_lang WHERE id_lang=2";
            if(!$result=$db->query($query2)){
                return false;
            }else{
                while ($row = mysqli_fetch_array($result)) {
                    $id_block=$row['id_block'];
                    $name=$row['name'];
                    $query="INSERT INTO ".$prefix."block_lang (`id_block`, `id_lang`, `name`) VALUES ('".$id_block."','5','".$name."')";
                    if(!$db->query($query)){
                        return false;
                    }
                }
                $query3="SELECT * FROM ".$prefix."content_lang WHERE id_lang=2";
                if(!$result2=$db->query($query3)){
                    return false;
                }else{
                    while ($row = mysqli_fetch_array($result2)) {
                        $id_content=$row['id_content'];
                        $title=$row['title'];
                        $meta_title=$row['meta_title'];
                        $meta_description=$row['meta_description'];
                        $link_rewrite=$row['link_rewrite'];
                        $menu_text=$row['menu_text'];
                        $query="INSERT INTO ".$prefix."content_lang (`id_block`, `id_lang`, `title`, `meta_title`, `meta_description`, `link_rewrite`, `menu_text`) VALUES ('".$id_content."','5','".$title."','".$meta_title."','".$meta_description."','".$link_rewrite."','".$menu_text."')";
                        if(!$db->query($query)){
                            return false;
                        }
                    }
                }

            }
            $db->close();
            if($this->setDefaultLang(5,$data)){
                return true;
            }
            return false;

        }

    }
    /*This function is not used*/
    /*public function getDbBasicData($prefix,$lang){

        $lang=$this->findLangData($lang);

        $existinglang=false;
        if(!$lang || !(isset($lang['id']))){
            $path = __DIR__.'/../dbfiles/db_basic_data_other.sql';
        }else{
            $path = __DIR__.'/../dbfiles/db_basic_data_' . $lang['id'] . '.sql';
            $existinglang=true;
        }

        $iso=explode("-",$lang['iso']);

        $country_code=$iso[1];
        $iso=$iso[0];

        if (!$text = file_get_contents($path)) {
            return false;
        } else {
            $text_r = str_replace("{PREFIX}", $prefix, $text);

            if($existinglang){

                return $text_r;
            }else{
                $text_r = str_replace("{LANG_ISO}",$iso, $text_r);
                $text_r = str_replace("{LANG_NAME}",$lang['name'], $text_r);
                $text_r = str_replace("{LANG_ZONE}",$country_code, $text_r);

                return $text_r;
            }

        }
    }*/
    public function getDbStructure($prefix){
        $path=__DIR__.'/../dbfiles/db_structure.sql';
        if(!$text=file_get_contents($path)){
            return false;
        }else{
            $text_r = str_replace("{PREFIX}",$prefix,$text);
            return $text_r;
        }

    }
    public function writeCoreHashes(){
        $path=__DIR__.'/../../config/core_hashes.php';
        $text="<?php
            defined('SESSION_HASH') or define('SESSION_HASH','".$this->generateRandomString()."');
            defined('LIVEVIEW_HASH') or define('LIVEVIEW_HASH','".$this->generateRandomString()."');
            defined('FILEMANAGER_HASH') or define('FILEMANAGER_HASH','".$this->generateRandomString()."');
            defined('COOKIE_HASH') or define('COOKIE_HASH','".$this->generateRandomString()."');
        ?>";
        if(@file_put_contents($path,$text)==false){
            return false;
        }else{
            return true;
        }

    }
    public function generateRandomString($length = 32){
        if (function_exists('random_bytes')) {
            $key= random_bytes($length);
        }else{
            if(function_exists('openssl_random_pseudo_bytes')){

                $key=openssl_random_pseudo_bytes($length,$cryptoStrong);
                if ($cryptoStrong === false) {
                    $this->jsonResponse(false,['errormsg'=>'openssl_random_pseudo_bytes() set $crypto_strong false. Your PHP setup is insecure.']);
                }

            }
        }
        return strtr(substr(base64_encode($key), 0, $length), '+/', '_-');
    }
    public function generatePasswordHash($password, $cost = null)
    {
        if ($cost === null) {
            $cost = 13;//$this->passwordHashCost;
        }

        if (function_exists('password_hash')) {
            /** @noinspection PhpUndefinedConstantInspection */
            return password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
        }

        $salt = $this->generateSalt($cost);
        $hash = crypt($password, $salt);
        // strlen() is safe since crypt() returns only ascii
        if (!is_string($hash) || strlen($hash) !== 60) {
            $this->jsonResponse(false,['errormsg'=>'Unknown error occurred while generating hash.']);
        }
        return $hash;
    }
    protected function generateSalt($cost = 13)
    {
        $cost = (int) $cost;
        if ($cost < 4 || $cost > 31) {
            $this->jsonResponse(false,['errormsg'=>'Cost must be between 4 and 31.']);
        }

        // Get a 20-byte random string
        $rand = $this->generateRandomString(20);
        // Form the prefix that specifies Blowfish (bcrypt) algorithm and cost parameter.
        $salt = sprintf("$2y$%02d$", $cost);
        // Append the random salt data in the required base64 format.
        $salt .= str_replace('+', '.', substr(base64_encode($rand), 0, 22));

        return $salt;
    }
    public function createUser($data){

        $hash = $this->generatePasswordHash($data['password']);

        $username=$data['user'];
        $email=$data['email'];

        $password_hash=$hash;
        $created_at=date('Y-m-d H:i');

        $lang=$data['language'];

        $lang=$this->findLangData($lang);


        $lang_id='';
        if(!isset($lang['id'])){
            $lang_id=5;
        }else{
            $lang_id=$lang['id'];
        }

        $status=10;

        $prefix=$data['db_prefix'];
        try {
            $db=$this->getConnectionDb($data);
            $query="INSERT INTO ".$prefix."user (username , email, password_hash,lang ,created_at, updated_at,status )VALUES ('".$username."','".$email."','".$password_hash."','".$lang_id."','".$created_at."','".$created_at."','".$status."')";
            if(!$db->query($query)){
                return false;
            }else{
                $db->close();
                return true;
            }

        } catch(Exception $e){
            $errorMsg = $e->getMessage();
            return false;
        }
    }
    public function checkIntegrity($data){
        $dataToCheck=array('block'=>14,'block_lang'=>14,'configuration'=>20,'content'=>0,'content_lang'=>0,'language'=>4,'user'=>1);

        $query='SHOW TABLES';
        $db=$this->getConnectionDb($data);
        if(!$tables=$db->query($query)){
            $this->jsonResponse(false,['errormsg'=>'Cannot check db integrity']);
        }
        $ok=true;
        if($tables->num_rows==8){
            $prefix=$data['db_prefix'];

            foreach($dataToCheck as $table=>$ncols){
                $db1=$this->getConnectionDb($data);
                $query='SELECT * FROM '.$prefix.$table;
                $cols = $db1->query($query);

                if($cols->num_rows < $ncols){
                    $ok=false;
                }
            }
        }else{
            $ok=false;
        }
        return $ok;
    }
    public function writeInstalled(){
        $path=__DIR__.'/../../config/installed.txt';
        $text="Installation successful";
        if(@file_put_contents($path,$text)==false){
            return false;
        }else{
            return true;
        }
    }
    public function delete_directory($dirname,&$deleted)
    {
        if(!is_link($dirname)){
            if (is_dir($dirname)) {
                @chmod($dirname, 0777);
                $dir_handle = opendir($dirname);
            }
            if (!$dir_handle)
                return false;
            while ($file = readdir($dir_handle)) {
                if ($file != "." && $file != "..") {
                    if (!is_dir($dirname . "/". $file)) {

                        @chmod($dirname . "/" . $file, 0777);
                        $deleted[$dirname][]= "/" .$file;
                        if (!@unlink($dirname . "/" . $file)) {

                            return false;
                        }else{
                            $deleted['files_deleted'][]=$dirname . "/". $file;
                        }
                    } else {
                        $deleted['id_dir'][]=$dirname ."/". $file;
                        $this->delete_directory($dirname ."/". $file,$deleted);
                    }
                }
            }
            closedir($dir_handle);
            if (!@rmdir($dirname)) {
                return false;
            } else {
                return true;
            }
        }else{
            return false;
        }

    }
    /****************INSTALL STEPS*********************/
    public function secondstep(){
        $data=$_POST['data'];

        if($this->checkDb($data)){
            if($this->writeConfigData($data) && $this->writeCoreHashes()){

                if(!$query=$this->getDbStructure($data['db_prefix'])){
                    $this->jsonResponse(false,['errormsg'=>'Error get db structure']);
                }else{
                    if(!$db=$this->getConnectionDb($data)){

                        $this->jsonResponse(false,['errormsg'=>'Error getting connection db. ']);
                    }else{
                        $db->set_charset("utf8");
                        if(!$db->multi_query($query)){
                            $this->jsonResponse(false,['errormsg'=>'Error trying to create db structure. '.var_dump($db->error_list)]);
                        }else{
                            do{
                            } while ($db->more_results() && $db->next_result());

                            $lang=$this->findLangData($data['language']);
                            if(!$lang || !(isset($lang['id']))){
                                //si no es lang de los que vienen por defecto
                                if(!$d=$this->otherLangDbData($data)){
                                    $this->jsonResponse(false,['errormsg'=>'Error writing lang fields.'.var_dump($d)]);
                                }
                            }else{
                                if(!$this->setDefaultLang($lang['id'],$data)){
                                    $this->jsonResponse(false,['errormsg'=>'Error setting default lang']);
                                }
                            }
                            if($this->createUser($data)){
                                if($this->checkIntegrity($data)){
                                    $this->finishstep();
                                }else{
                                    $this->jsonResponse(false,['errormsg'=>'Error integrity']);
                                }
                            }else{
                                $this->jsonResponse(false,['errormsg'=>'Error creating user '.$this->db]);
                            }

                        }
                    }
                }
            }else{
                $this->jsonResponse(false,['errormsg'=>'Error writing config data']);
            }
        }else{
            $this->jsonResponse(false,['errormsg'=>'Error checking db']);
        }

    }
    public function finishstep(){

        $oldname = __DIR__.'/../../manager';
        $newName = $this->generate_random_password(3);

        if(!$rename = @rename($oldname, $oldname . $newName)){
            $this->jsonResponse(false,['errormsg'=>'Can not rename manager folder']);
        }

        if (!$this->writeInstalled()) {
            $this->jsonResponse(false,['errormsg'=>'Can not write installed.txt']);
        }
        $view =$this->renderPartial(__DIR__.'/../views/install/second_step.php');
        $this->jsonResponse(true,['rename' => $rename, 'delete' => false, 'newname' => $newName,'view'=>$view]);
    }
    public function removeinstall(){
        $deleted=[];
        $delete_dir =realpath(__DIR__.'/../../install');
        $delete_install =$this->delete_directory($delete_dir,$deleted);
        if($delete_install){
            return true;
        }else{
            return false;

        }



    }
    /**************************************************************/
}

