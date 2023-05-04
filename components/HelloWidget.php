<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;

class HelloWidget extends Widget
{
   public $message;

    public function init() {
        parent::init();
        if (!is_null($this->message)) {
            return;
        }
        $hour = date('G');
        if ($hour >= 0 && $hour < 6) {
            $this->message = 'Доброй ночи!';
        } elseif ($hour >= 6 && $hour < 12) {
            $this->message = 'Доброе утро!';
        } elseif ($hour >= 12 && $hour < 18) {
            $this->message = 'Добрый день!';
        } else {
            $this->message = 'Добрый вечер!';
        }
    }

    public function run() {
        return $this->render('hello', ['message' => $this->message]);
    }
}