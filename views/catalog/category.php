<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = $category->name;
?>
<div class="catalog-category">
    <h1><?= Html::encode($category->name) ?></h1>
    <?php if (!empty($products)): ?>
        <ul>
        <?php foreach ($products as $product): ?>
            <li><?= Html::encode($product->name); ?></li>
        <?php endforeach; ?>
        </ul> 
        <?php else: ?>
        <p>Нет товаров в этой категории</p>
       <?php endif; ?>

    <?php $parent = $category->parent; /* родительская категория */ ?>
    <?php if (!empty($parent)): ?>
        <p>Родительская категория: <?= Html::encode($parent->name); ?></p>
    <?php endif; ?>  
</div>