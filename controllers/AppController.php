<?php
/*
 * Файл /controllers/AppController.php 
 */
namespace app\controllers;
use yii\web\Controller;

class AppController extends Controller {
    protected function debug($data, $return = false) {
        $result = '<pre>' . print_r($data, true) . '</pre>';
        if ( ! $return) {
            echo $result;
        }
        return $result;
    }
}
?>