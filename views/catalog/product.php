<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = $product->name;
?>
<div class="catalog-product">
    <h1><?= Html::encode($product->name) ?></h1>
    <?php $category = $product->category; /* родительская категория */ ?>
    <?php if (!empty($category)): ?>
        <p>Родительская категория: <?= Html::encode($category->name); ?></p>
        <?php $products = $category->products; /* другие товары в родительской категории */ ?>
        <?php if (!empty($products)): ?>
            <ul>
            <?php foreach ($products as $item): ?>
                <li><?= Html::encode($item->name); ?></li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>   
    <?php endif; ?>
</div>