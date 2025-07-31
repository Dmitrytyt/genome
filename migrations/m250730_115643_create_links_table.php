<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%links}}`.
 */
class m250730_115643_create_links_table extends Migration
{
    public const TABLE = '{{%links}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'original_url' => $this->text()->notNull()->comment('Ссылка'),
            'token' => $this->string()->unique()->comment('Токен'),
            'short_url' => $this->string()->unique()->comment('Короткая ссылка'),
            'clicks' => $this->integer()->defaultValue(0)->notNull()->comment('Количество переходов'),
            'created_at' => $this->integer()->notNull()->comment('Время создания в unixtime'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable(self::TABLE);
    }
}
