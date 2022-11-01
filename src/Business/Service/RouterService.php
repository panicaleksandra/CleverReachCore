<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Authorization\Contracts\AuthorizationService as BaseAuthorizationService;
use CleverReachCore\Core\Infrastructure\Logger\Logger;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use Exception;

/**
 * Class RouterService
 *
 * @package CleverReachCore\Business\Service
 */
class RouterService
{
    /**
     * Check if user is authorized with CleverReach.
     *
     * @return bool
     */
    public function isAuthorized(): bool
    {
        $result = true;

        try {
            (ServiceRegister::getService(BaseAuthorizationService::class))->getAuthInfo();
        } catch (Exception $e) {
            $result = false;

            Logger::logInfo($e->getMessage());
        }

        return $result;
    }
}
