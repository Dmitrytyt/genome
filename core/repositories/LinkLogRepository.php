<?php

namespace app\core\repositories;

use app\core\entities\LinkLog;
use RuntimeException;

final class LinkLogRepository
{
    /**
     * @throws \Throwable
     */
    public function add(LinkLog $model): void
    {
        if (!$model->getIsNewRecord()) {
            throw new RuntimeException('Adding existing model.');
        }

        if (!$model->insert()) {
            throw new RuntimeException('Saving error.');
        }
    }
}
