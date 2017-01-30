<?php
/**
 * Created by PhpStorm.
 * User: silvia
 * Date: 21/11/2016
 * Time: 11:37
 */
namespace blocks\contactform\frontend\contactformmodule\controllers;

use Yii;
use yii\web\Controller;
use common\models\Configuration;

class ContactformController extends Controller
{
    public function actionSendmail(array $data){


        $domain=Yii::$app->request->hostName;
        $para = Configuration::getValue('_EMAIL_');

        $de='noreply@'.$domain;
        $email = $data['email'];
        $mensaje = $data['message'];

        $UN_SALTO="\r\n";
        $mensaje.=$UN_SALTO;
        $mensaje.='Email contacto: '.$email;

        $name = $data['name'];
        $cabeceras = 'From: '.$de. "\r\n" .
//           'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $succ=mail($para,$name, $mensaje, $cabeceras);
        die(json_encode(
            array('success' => $succ)));
    }

}