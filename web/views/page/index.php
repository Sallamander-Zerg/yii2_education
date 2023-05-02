<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Страница';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Переменная, полученная от контроллера: <?= $lorem; ?></p>
    <?php if (!empty($users)): ?>
        <h3>Пользователи</h3>
        <ul>
        <?php foreach ($users as $user): ?>
            <li>Фамилия <?= $user['surname']; ?>, e-mail <?= $user['email']; ?></li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <p>Вы можете изменять эту страницу, редактируя файл шаблона:</p>
    <code><?= __FILE__ ?></code>
</div>