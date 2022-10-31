<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Configuration\Configuration;

/**
 * Class ConfigService
 *
 * @package CleverReachCore\Business\Service
 */
class ConfigService extends Configuration
{
    public const INTEGRATION_NAME = 'Shopware 6';
    public const CLIENT_ID = 'n4VcLY2We5';
    public const CLIENT_SECRET = 'xUWd13GnxnXHmKy6dL1qvpvqBgpEdDKK';
    public const DEFAULT_QUEUE_NAME = 'Shopware 6 - Default';
    public const MIN_LOG_LEVEL = 2;

    /**
     * Retrieves client secret of the integration.
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return self::CLIENT_SECRET;
    }

    /**
     * Retrieves client id of the integration.
     *
     * @return string
     */
    public function getClientId(): string
    {
        return self::CLIENT_ID;
    }

    /**
     * Returns base url of the integrated system.
     *
     * @return string
     */
    public function getSystemUrl(): string
    {
        return 'http://6-4-dev.shopware.localhost';
    }

    /**
     * Returns async process starter url, always in http.
     *
     * @param string $guid
     *
     * @return string
     */
    public function getAsyncProcessUrl($guid): string
    {
        return 'http://6-4-dev.shopware.localhost/storefront/async/' . $guid;
    }

    /**
     * Returns default queue name.
     *
     * @return string
     */
    public function getDefaultQueueName(): string
    {
        return self::DEFAULT_QUEUE_NAME;
    }

    /**
     * Retrieves integration name.
     *
     * @return string
     */
    public function getIntegrationName(): string
    {
        return self::INTEGRATION_NAME;
    }
}
