<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Authorization\Contracts\AuthorizationService as BaseAuthorizationService;
use CleverReachCore\Core\BusinessLogic\SecondarySynchronization\Tasks\Composite\SecondarySyncTask;
use CleverReachCore\Core\BusinessLogic\TaskExecution\QueueService;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Task\InitialSync\InitialSyncTask;
use Exception;

/**
 * Class SyncStatusService
 *
 * @package CleverReachCore\Business\Service
 */
class SyncStatusService
{
    public const SYNC_STATUS_IN_PROGRESS = 'In progress';
    public const SYNC_STATUS_QUEUED = 'queued';
    private QueueService $queueService;

    /**
     * Creates SyncStatusService.
     *
     * @param QueueService $queueService
     */
    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }

    /**
     * Returns synchronization status.
     *
     * @return string
     */
    public function getSyncStatus(): string
    {
        $secondarySyncItem = $this->queueService->findLatestByType(SecondarySyncTask::getClassName());

        if ($secondarySyncItem) {
            return $secondarySyncItem->getStatus() === self::SYNC_STATUS_QUEUED ?
                self::SYNC_STATUS_IN_PROGRESS : $secondarySyncItem->getStatus();
        }

        $initialSyncItem = $this->queueService->findLatestByType(InitialSyncTask::getClassName());

        if ($initialSyncItem) {
            return $initialSyncItem->getStatus() === self::SYNC_STATUS_QUEUED ?
                self::SYNC_STATUS_IN_PROGRESS : $initialSyncItem->getStatus();
        }

        return self::SYNC_STATUS_IN_PROGRESS;
    }

    /**
     * Returns client id.
     *
     * @return string
     * @throws Exception
     */
    public function getClientId(): string
    {
        /** @var AuthorizationService $authService */
        $authService = ServiceRegister::getService(BaseAuthorizationService::class);

        return $authService->getUserInfo()->getId() ?? '';
    }
}
