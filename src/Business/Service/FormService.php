<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Form\FormService as BaseFormService;
use CleverReachCore\Core\Infrastructure\Configuration\Configuration;
use CleverReachCore\Core\Infrastructure\ServiceRegister;

/**
 * Class FormService
 *
 * @package CleverReachCore\Business\Service
 */
class FormService extends BaseFormService
{
    /**
     * Retrieves the integration's default form name.
     *
     * @return string
     */
    public function getDefaultFormName(): string
    {
        /** @var ConfigService $configService */
        $configService = ServiceRegister::getService(Configuration::class);

        return $configService->getIntegrationName();
    }
}
