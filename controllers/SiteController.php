<?php

namespace app\controllers;

use app\core\entities\Link;
use app\core\forms\LinkForm;
use app\core\forms\LinkLogForm;
use app\core\services\LinkLogService;
use app\core\services\LinkService;
use app\core\services\QRService;
use DomainException;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\ErrorAction;

class SiteController extends Controller
{
    private LinkService $linkService;
    private LinkLogService $linkLogService;
    private QRService $qrService;

    public function __construct(
        $id,
        $module,
        LinkService $serviceLink,
        LinkLogService $serviceLinkLog,
        QRService $serviceQR,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);

        $this->linkService = $serviceLink;
        $this->linkLogService = $serviceLinkLog;
        $this->qrService = $serviceQR;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $url = Yii::$app->request->url;

        if (mb_strlen($url) === Link::TOKEN_LENGTH + 1) {
            $this->actionView($url);
        }

        $linkForm = new LinkForm();

        if (Yii::$app->request->isAjax && $linkForm->load(Yii::$app->request->post())) {
            if (!$linkForm->validate()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($linkForm);
            }

            try {
                $link = $this->linkService->add($linkForm);

                return $this->asJson([
                    'id' => $link->id,
                    'shortUrl' => $link->short_url,
                    'qrCode' => $this->qrService->generateQRCode($link->short_url, $link->token),
                ]);
            } catch (DomainException $e) {
                $linkForm->addError($linkForm->getInputId('link'), $e->getMessage());
                return $this->asJson($linkForm->getErrors());
            }
        }

        return $this->render('index', [
            'model' => $linkForm,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(string $tokenPart = null): Response
    {
        if ($tokenPart === null) {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }

        $token = mb_substr($tokenPart, 1, Link::TOKEN_LENGTH);
        $linkModel = $this->findModel($token);

        try {
            $link = $this->linkService->addClick($linkModel);
        } catch (DomainException $e) {
            throw new NotFoundHttpException('Ошибка отображения ссылки.');
        }

        $linkLogForm = new LinkLogForm();
        $linkLogForm->load(['linkId' => $link->id, 'ipAddress' => Yii::$app->request->userIP]);

        try {
            $this->linkLogService->add($linkLogForm);
        } catch (DomainException $e) {
            throw new NotFoundHttpException('Ошибка отображения ссылки.');
        }

        return $this->redirect($link->original_url);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModel($token): Link
    {
        if (($model = Link::findOne(['token' => $token])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }
}
