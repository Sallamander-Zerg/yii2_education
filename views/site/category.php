<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = $category->name;
?>
<div class="catalog-category">
    <h1><?= Html::encode($category->name) ?></h1>
        <ul>
        <?php foreach ($products as $product): ?>
            <li><?= Html::encode($product->name); ?></li>
        <?php endforeach; ?>
        </ul>   
</div>