<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\HelloWidget;

$this->title = 'Все сообщения';
?>
<div class="page-index">
    <?= HelloWidget::widget(); ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <p><a href="<?= Url::toRoute(['page/insert']); ?>">Добавить сообщение</a></p>
    <?php if (!empty($messages)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Имя</th>
                    <th>E-mail</th>
                    <th>Сообщение</th>
                    <th>Редактировать</th>
                    <th>Удалить</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($messages as $message): ?>
                <tr>
                    <td><?= $message['id'] ?></td>
                    <td><?= $message['name'] ?></td>
                    <td><?= $message['email'] ?></td>
                    <td><?= $message['body'] ?></td>
                    <?php $url = Url::toRoute(['page/update', 'id' => $message['id']]); ?>
                    <td><a href="<?= $url; ?>">Редактировать</a></td>
                    <?php $url = Url::toRoute(['page/delete', 'id' => $message['id']]); ?>
                    <td><a href="<?= $url; ?>">Удалить</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>