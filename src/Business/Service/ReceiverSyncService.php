<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Receiver\DTO\Config\SyncService;

/**
 * Class ReceiverService
 *
 * @package CleverReachCore\Business\Service
 */
class ReceiverSyncService extends SyncService
{
    /**
     * Creates receiver sync service.
     */
    public function __construct()
    {
        parent::__construct('receiver-service', 1, ReceiverService::class);
    }
}
