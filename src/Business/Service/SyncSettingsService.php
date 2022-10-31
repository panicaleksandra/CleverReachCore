<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Receiver\DTO\Config\SyncService;
use CleverReachCore\Core\BusinessLogic\SyncSettings\SyncSettingsService as BaseService;
use CleverReachCore\Core\Infrastructure\ServiceRegister;

/**
 * Class SyncSettingsService
 *
 * @package CleverReachCore\Business\Service
 */
class SyncSettingsService extends BaseService
{
    /**
     * Retrieves all available services that can be enabled by user.
     *
     * @return SyncService[]
     */
    public function getAvailableServices(): array
    {
        return [
            ServiceRegister::getService(ReceiverSyncService::class),
        ];
    }
}
