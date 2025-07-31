<?php

namespace app\core\entities;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $link_id - ID ссылки
 * @property string $ip_address - IP адрес пользователя, перешедшего по ссылке
 * @property int $created_at - Дата создания ссылки в unixtime
 */
final class LinkLog extends ActiveRecord
{
    /** {@inheritdoc} */
    public static function tableName(): string
    {
        return '{{%link_log}}';
    }

    /** {@inheritdoc} */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'link_id' => 'ID ссылки',
            'ip_address' => 'IP адрес',
            'created_at' => 'Время создания в unixtime',
        ];
    }

    /**
     * @param int $linkId
     * @param string $ipAddress
     * @return LinkLog
     */
    public static function make(int $linkId, string $ipAddress): LinkLog
    {
        $entity = new LinkLog();
        $entity->link_id = $linkId;
        $entity->ip_address = $ipAddress;
        $entity->created_at = time();

        return $entity;
    }
}
