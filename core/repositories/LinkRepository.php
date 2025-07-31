<?php

namespace app\core\repositories;

use app\core\entities\Link;
use RuntimeException;
use yii\db\StaleObjectException;

final class LinkRepository
{
    /**
     * @throws \Throwable
     */
    public function add(Link $model): void
    {
        if (!$model->getIsNewRecord()) {
            throw new RuntimeException('Adding existing model.');
        }

        if (!$model->insert()) {
            throw new RuntimeException('Saving error.');
        }
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function save(Link $model): void
    {
        if ($model->getIsNewRecord()) {
            throw new RuntimeException('Saving new model.');
        }

        if ($model->update(false) === false) {
            throw new RuntimeException('Saving error.');
        }
    }
}
