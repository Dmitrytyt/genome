<?php

namespace app\core\entities;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $original_url - Ссылка, которую ввёл пользователь
 * @property string $token - Токен, уникальный
 * @property string $short_url - Короткая ссылка
 * @property integer $clicks - Количество переходов
 * @property int $created_at - Дата создания ссылки в unixtime
 */
final class Link extends ActiveRecord
{
    public const TOKEN_LENGTH = 6;

    /** {@inheritdoc} */
    public static function tableName(): string
    {
        return '{{%links}}';
    }

    /** {@inheritdoc} */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'original_url' => 'Ссылка',
            'token' => 'Токен',
            'short_url' => 'Короткая ссылка',
            'clicks' => 'Количество переходов',
            'created_at' => 'Время создания в unixtime',
        ];
    }

    /**
     * @param string $originalUrl - ссылка, введённая пользователем
     * @param string $token - сгенерированный токен
     * @param string $shortUrl - сгенерированная короткая ссылка
     * @return Link
     */
    public static function make(string $originalUrl, string $token, string $shortUrl): Link
    {
        $entity = new Link();
        $entity->original_url = $originalUrl;
        $entity->token = $token;
        $entity->short_url = $shortUrl;
        $entity->clicks = 0;
        $entity->created_at = time();

        return $entity;
    }

    /**
     * Увеличивает количество переходов
     */
    public function boostClicks(): void
    {
        ++$this->clicks;
    }
}
