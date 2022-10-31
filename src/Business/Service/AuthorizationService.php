<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Authorization\AuthorizationService as BaseAuthorizationService;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class AuthorizationService
 *
 * @package CleverReachCore\Business\Service
 */
class AuthorizationService extends BaseAuthorizationService
{
    private const ROUTE_NAME = 'storefront.authorization';

    /**
     * Retrieves authorization redirect url.
     *
     * @param bool $isRefresh Specifies whether url is retrieved for token refresh.
     *
     * @return string Authorization redirect url.
     */
    public function getRedirectURL($isRefresh = false): string
    {
        $params = ['type' => 'param'];
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = ServiceRegister::getService(UrlGeneratorInterface::class);

        return $urlGenerator->generate(self::ROUTE_NAME, $params, UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
