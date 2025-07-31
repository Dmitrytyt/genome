<?php

namespace app\core\services;

use app\core\entities\LinkLog;
use app\core\forms\LinkLogForm;
use app\core\repositories\LinkLogRepository;
use DomainException;

final class LinkLogService
{
    private LinkLogRepository $linkLogRepository;

    public function __construct(LinkLogRepository $linkRepository)
    {
        $this->linkLogRepository = $linkRepository;
    }

    /**
     * @param LinkLogForm $form
     * @return LinkLog
     */
    public function add(LinkLogForm $form): LinkLog
    {
        $entityLinkLog = LinkLog::make($form->linkId, $form->ipAddress);

        try {
            $this->linkLogRepository->add($entityLinkLog);
        } catch (\Throwable $e) {
            throw new DomainException('Ошибка сохранения лога.');
        }

        return $entityLinkLog;
    }
}
