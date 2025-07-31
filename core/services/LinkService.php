<?php

namespace app\core\services;

use app\core\forms\LinkForm;
use app\core\entities\Link;
use app\core\repositories\LinkRepository;
use DomainException;
use Yii;
use yii\base\Exception;

final class LinkService
{
    private LinkRepository $linkRepository;

    public function __construct(LinkRepository $linkRepository)
    {
        $this->linkRepository = $linkRepository;
    }

    /**
     * Добавляет новую ссылку в БД
     * @param LinkForm $form
     * @return Link
     * @throws DomainException
     */
    public function add(LinkForm $form): Link
    {
        try {
            $token = $this->generateLinkToken();
        } catch (Exception $e) {
            throw new DomainException($e->getMessage());
        }

        $shortUrl = $this->getShortLink($token);
        $entityLink = Link::make($form->link, $token, $shortUrl);

        try {
            $this->linkRepository->add($entityLink);
        } catch (\Throwable $e) {
            throw new DomainException('Ошибка генерации ссылки.');
        }

        return $entityLink;
    }

    /**
     * Сохраняет переход по ссылке
     * @param Link $link
     * @return Link
     */
    public function addClick(Link $link): Link
    {
        $link->boostClicks();

        try {
            $this->linkRepository->save($link);
        } catch (\Throwable $e) {
            throw new DomainException('Ошибка сохранения клика.');
        }

        return $link;
    }

    /**
     * Генерирует уникальный токен
     * @param int $maxAttempts
     * @return string
     * @throws Exception
     */
    public function generateLinkToken(int $maxAttempts = 10): string
    {
        $attempt = 0;

        do {
            $token = Yii::$app->security->generateRandomString(Link::TOKEN_LENGTH);
            $exists = Link::find()->where(['token' => $token])->exists();

            if (!$exists) {
                return $token;
            }

            $attempt++;
        } while ($attempt < $maxAttempts);


        $msg = 'Невозможно сгенерировать уникальный токен после ' . $maxAttempts . ' попыток';
        Yii::error($msg, 'generateLinkToken');
        throw new Exception($msg);
    }

    /**
     * Возвращает короткую ссылку
     * @param string $token
     * @return string
     */
    public function getShortLink(string $token): string
    {
        return Yii::$app->request->hostInfo.'/'.$token;
    }
}
