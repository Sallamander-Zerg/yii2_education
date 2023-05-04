<?php

namespace app\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Feedback;

class PageController extends AppController
{
    public function actionIndex() {
        $messages = Feedback::find()->asArray()->all();
        return $this->render('index', ['messages' => $messages]);
    }

    public function beforeAction($action) {
        if ($action->id == 'feedback') {
            // отключаем проверку CSRF-токена
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionFeedback() {
        $model = new Feedback();
        /*
         * Если пришли post-данные, загружаем их в модель...
         */
        if ($model->load(Yii::$app->request->post())) {
            // ...и проверяем эти данные
            if ( ! $model->validate()) {
                /*
                 * Данные не прошли валидацию
                 */
                Yii::$app->session->setFlash(
                    'feedback-success',
                    false
                );
                // сохраняем в сессии введенные пользователем данные
                Yii::$app->session->setFlash(
                    'feedback-data',
                    [
                        'name' => $model->name,
                        'email' => $model->email,
                        'body' => $model->body
                    ]
                );
                /*
                 * Сохраняем в сессии массив сообщений об ошибках. Массив имеет вид
                 * [
                 *     'name' => [
                 *         'Поле «Ваше имя» обязательно для заполнения',
                 *     ],
                 *     'email' => [
                 *         'Поле «Ваш email» обязательно для заполнения',
                 *         'Поле «Ваш email» должно быть адресом почты'
                 *     ]
                 * ]
                 */
                Yii::$app->session->setFlash(
                    'feedback-errors',
                    $model->getErrors()
                );
            } else {
                /*
                 * Данные прошли валидацию
                 */

                // отправляем письмо на почту администратора
                $textBody = 'Имя: ' . strip_tags($model->name) . PHP_EOL;
                $textBody .= 'Почта: ' . strip_tags($model->email) . PHP_EOL . PHP_EOL;
                $textBody .= 'Сообщение: ' . PHP_EOL . strip_tags($model->body);

                $htmlBody = '<p><b>Имя</b>: ' . strip_tags($model->name) . '</p>';
                $htmlBody .= '<p><b>Почта</b>: ' . strip_tags($model->email) . '</p>';
                $htmlBody .= '<p><b>Сообщение</b>:</p>';
                $htmlBody .= '<p>' . nl2br(strip_tags($model->body)) . '</p>';

                Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['senderEmail'])
                    ->setTo(Yii::$app->params['adminEmail'])
                    ->setSubject('Заполнена форма обратной связи')
                    ->setTextBody($textBody)
                    ->setHtmlBody($htmlBody)
                    ->send();

                // это обычный POST-запрос или это AJAX-запрос?
                if (Yii::$app->request->isAjax) {
                    $message =
<<<HTML
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close"
            data-dismiss="alert" aria-label="Закрыть">
        <span aria-hidden="true">&times;</span>
    </button>
    <p>Ваше сообщение успешно отправлено</p>
</div>
HTML;
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response = [
                        'success' => true,
                        'message' => $message
                    ];
                    return $response;
                } else {
                    // данные прошли валидацию, отмечаем этот факт
                    Yii::$app->session->setFlash(
                        'feedback-success',
                        true
                    );
                }
            }
            // выполняем редирект, чтобы избежать повторной отправки формы
            return $this->refresh();
        }
        return $this->render('feedback', ['model' => $model]);
    }
 /*
     * Список всех сообщений
     */

    /*
     * Добавляет новое сообщение
     */
    public function actionInsert() {
        $message = new Feedback();
        
        // если пришли post-данные
        if ($message->load(Yii::$app->request->post())) {
            // проверяем и сохраняем эти данные
            if ($message->insert()) {
                // данные прошли валидацию и записаны в БД
                return $this->redirect(['page/index']);
            }
            // данные не прошли валидацию
            Yii::$app->session->setFlash(
                'success',
                false
            );
        }
        return $this->render('feedback', ['message' => $message]);
    }

    /*
     * Позволяет обновить сообщение
     */
    public function actionUpdate($id) {
        if (!ctype_digit($id)) {
            return $this->redirect(['page/index']);
        }
        $message = Feedback::findOne($id);
        // если пришли post-данные
        if ($message->load(Yii::$app->request->post())) {
            // проверяем и сохраняем эти данные
            if ($message->update()) {
                // данные прошли валидацию и записаны в БД
                return $this->redirect(['page/index']);
            }
            // данные не прошли валидацию
            Yii::$app->session->setFlash(
                'success',
                false
            );
        }
        return $this->render('feedback', ['message' => $message]);
    }

    /*
     * Позволяет удалить сообщение
     */
    public function actionDelete($id) {
        if (!ctype_digit($id)) {
            return $this->redirect(['page/index']);
        }
        $message = Feedback::findOne($id);
        $message->delete();
        return $this->redirect(['page/index']);
    }
    /* ... */
}
?>