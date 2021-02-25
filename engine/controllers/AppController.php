<?php
/**
 * Created by PhpStorm.
 * User: Ace
 * Date: 05.09.2018
 * Time: 9:42
 */

namespace app\controllers;


use Yii;
use yii\web\Controller;

class AppController extends Controller
{
    public function checkIfEmpty($string){
        if($string === null || $string === '' || preg_match('/^\s+$/', $string)){
            return true;
        }
        return false;
    }

    private $uploadFolder;

    public function getUploadFolder(){
        $uploadFolder = Yii::$app->basePath.'/../uploads/';
        return $uploadFolder;
    }

}