<?php

namespace app\controllers;

use yii\web\Controller;

class PageController extends AppController
{
    public function actionIndex()
    {
        $data = 'Lorem ipsum dolor sit amet';
        $users = [
            ['surname' => 'Иванов', 'email' => 'ivanov@mail.ru'],
            ['surname' => 'Петров', 'email' => 'petrov@mail.ru'],
        ];
        return $this->render('index', ['lorem' => $data, 'users' => $users]);
    }
}
?>