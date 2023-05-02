<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Feedback;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
   
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionHello()
    {   
        $this->view->title = 'Заголовок страницы';
        $this->view->registerMetaTag(
            ['name' => 'keywords', 'content' => 'Ключевые слова страницы'], 'keywords'
        );
        $this->view->registerMetaTag(
            ['name' => 'description', 'content' => 'Краткое описание страницы'], 'description'
        );
        $this->layout = 'base';
        return $this->render('hello');
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
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
        $this->layout = 'feedback';
        return $this->render('feedback', ['model' => $model]);
    }

    /* ... */
}
