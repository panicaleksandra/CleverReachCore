<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Group\GroupService as BaseGroupService;
use CleverReachCore\Core\Infrastructure\Configuration\Configuration;
use CleverReachCore\Core\Infrastructure\ServiceRegister;

/**
 * Class GroupService
 *
 * @package CleverReachCore\Business\Service
 */
class GroupService extends BaseGroupService
{
    private const BLACKLISTED_EMAILS_SUFFIX = '-Shopware-6';

    /**
     * Retrieves integration specific group name.
     *
     * @return string Integration provided group name.
     */
    public function getDefaultName(): string
    {
        /** @var ConfigService $configService */
        $configService = ServiceRegister::getService(Configuration::class);

        return $configService->getIntegrationName();
    }

    /**
     * Retrieves integration specific blacklisted emails suffix.
     *
     * @NOTICE SUFFIX MUST START WITH DASH (-)!
     * @return string Blacklisted emails suffix.
     */
    public function getBlacklistedEmailsSuffix(): string
    {
        return self::BLACKLISTED_EMAILS_SUFFIX;
    }
}
