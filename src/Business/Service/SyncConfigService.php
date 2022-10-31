<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Receiver\Contracts\SyncConfigService as BaseSyncConfigService;
use CleverReachCore\Core\BusinessLogic\Receiver\DTO\Config\SyncService;

/**
 * Class SyncConfigService
 *
 * @package CleverReachCore\Business\Service
 */
class SyncConfigService implements BaseSyncConfigService
{
    /**
     * Retrieves enabled services.
     *
     * @return SyncService[]
     */
    public function getEnabledServices(): array
    {
        return [new ReceiverSyncService()];
    }

    /**
     * Sets enabled services.
     *
     * @param SyncService[] $services
     *
     * @return void
     */
    public function setEnabledServices(array $services): void
    {
    }
}
