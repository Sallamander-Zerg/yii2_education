<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = $product->name;
?>
<div class="catalog-product">
    <h1><?= Html::encode($product->name) ?></h1>
</div>