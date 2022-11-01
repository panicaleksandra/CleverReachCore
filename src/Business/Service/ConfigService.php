<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Configuration\Configuration;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

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
    public const ASYNC_PROCESS_ROUTE_NAME = 'storefront.async';

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
        /** @var RequestContext $requestContext */
        $requestContext = ServiceRegister::getService(RequestContext::class);

        return $requestContext->getBaseUrl();
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
        return $this->getUrlGenerator()->generate(
          self::ASYNC_PROCESS_ROUTE_NAME,
          ['guid' => $guid],
          UrlGeneratorInterface::ABSOLUTE_URL
        );
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

    /**
     * @return UrlGeneratorInterface
     */
    private function getUrlGenerator(): UrlGeneratorInterface
    {
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = ServiceRegister::getService(UrlGeneratorInterface::class);

        return $urlGenerator;
    }
}
