<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
$this->title = 'Обратная связь';

/*
 * Если данные формы не прошли валидацию, получаем из сессии сохраненные
 * данные, чтобы заполнить ими поля формы, не заставляя пользователя
 * заполнять форму повторно
 */
$js =
<<<JS
$('#feedback').on('beforeSubmit', function() {
    var form = $(this);
    var data = form.serialize();
    // отправляем данные на сервер
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: data
    })
    .done(function(data) {
        if (data.success) {
            // данные прошли валидацию, сообщение было отправлено
            $('#response').html(data.message);
            form.children('.has-success').removeClass('has-success');
            form[0].reset();
        }
    })
    .fail(function () {
        alert('Произошла ошибка при отправке данных!');
    })
    return false; // отменяем отправку данных формы
});
JS;
$this->registerJs($js, $this::POS_READY);
$name = '';
$email = '';
$body = '';
if (Yii::$app->session->hasFlash('feedback-data')) {
    $data = Yii::$app->session->getFlash('feedback-data');
    $name = Html::encode($data['name']);
    $email = Html::encode($data['email']);
    $body = Html::encode($data['body']);
}
?>

<div class="container">
    <?php
    $success = false;
    if (Yii::$app->session->hasFlash('feedback-success')) {
        $success = Yii::$app->session->getFlash('feedback-success');
    }
    ?>
    <div id="response">
        <?php if (!$success): ?>
            <?php if (Yii::$app->session->hasFlash('feedback-errors')): ?>
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close"
                            data-dismiss="alert" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <p>При заполнении формы допущены ошибки</p>
                    <?php $allErrors = Yii::$app->session->getFlash('feedback-errors'); ?>
                    <ul>
                        <?php foreach ($allErrors as $errors): ?>
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error; ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close"
                        data-dismiss="alert" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p>Ваше сообщение успешно отправлено</p>
            </div>
        <?php endif; ?>
    </div>

    <?php $form = ActiveForm::begin(['id' => 'feedback', 'class' => 'form-horizontal']); ?>
        <?= $form->field($model, 'name')
        ->textInput(['placeholder' => 'Ваше имя и фамилия'])
        ->hint('Ваше имя и фамилия')
        ->label('Имя, Фамилия');
        ?>
        <?= $form->field($model, 'email')
        ->input('email', ['placeholder' => 'Ваш адрес почты'])
        ->hint('Ваш адрес почты')
        ->label('Адрес почты');
        ?>
        <?= $form->field($model, 'body')
        ->textarea(['rows' => 5, 'placeholder' => 'Введите ваше сообщение'])
        ->hint('Введите ваше сообщение')
        ->label('Ваше сообщение');
        ?>
       <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']); ?>
    <?php ActiveForm::end(); ?>
</div>