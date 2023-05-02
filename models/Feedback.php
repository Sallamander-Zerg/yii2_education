<?php
namespace app\models;

use yii\base\Model;

class Feedback extends Model {

    public $name;
    public $email;
    public $body;

    public function attributeLabels() {
        return [
            'name' => 'Ваше имя',
            'email' => 'Ваш e-mail',
            'body' => 'Ваше сообщение',
        ];
    }

    public function rules() {
        return [
            // удалить пробелы для всех трех полей формы
            [['name', 'email', 'body'], 'trim'],
            // поле name обязательно для заполнения
            ['name', 'required', 'message' => 'Поле «Ваше имя» обязательно для заполнения'],
            // поле email обязательно для заполнения
            ['email', 'required', 'message' => 'Поле «Ваш email» обязательно для заполнения'],
            // поле email должно быть корректным адресом почты
            ['email', 'email', 'message' => 'Поле «Ваш email» должно быть адресом почты'],
            // поле body обязательно для заполнения
            ['body', 'required', 'message' => 'Поле «Сообщение» обязательно для заполнения'],
            // поля name и email должны быть не более 50 символов
            [
                ['name', 'email'],
                'string',
                'max' => 50,
                'tooLong' => 'Поле должно быть длиной не более 50 символов'
            ],
            [
                ['name'],
                'string',
                'min' => 4,
                'tooShort' => 'Поле должно быть длиной не более 50 символов'
            ],
            // поле body должно быть не более 1000 символов
            [
                'body',
                'string',
                'max' => 1000,
                'tooLong' => 'Сообщение должно быть длиной не более 1000 символов'
            ],
        ];
    }
}