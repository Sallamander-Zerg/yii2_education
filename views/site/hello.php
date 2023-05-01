<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
$this->registerJSFile('@web/js/hello.js',
[
'depends'=>'yii\web\YiiAsset',
'position'=>$this::POS_HEAD
]
);
$this->title = 'Hello';
?>
<div class="site-hello">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Это страница «Hello». Вы можете изменять эту страницу, редактируя файл шаблона:</p>
    <code><?= __FILE__ ?></code>
</div>