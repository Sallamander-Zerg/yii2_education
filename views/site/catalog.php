<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Каталог товаров';
?>
<div class="catalog-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <ul>
    <?php foreach ($categories as $category): ?>
        <li><?= $category->name; ?></li>
    <?php endforeach; ?>
    </ul>
</div>