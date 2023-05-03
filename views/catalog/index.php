<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Каталог товаров';
?>
<div class="catalog-index">
<h1><?= Html::encode($this->title) ?></h1>
    <ul>
    <?php foreach ($categories as $category): ?>
        <li>
        <?php
            echo $category->name;
            // данные о дочерних категориях
            $children = $category->children;
            if (!empty($children)) {
                echo '<ul>';
                foreach ($children as $child) {
                    echo '<li>', $child->name, '</li>';
                }
                echo '</ul>';
            }
        ?>
        </li>

    <?php endforeach; ?>
    </ul>
</div>