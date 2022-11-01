<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Authorization\AuthorizationService as BaseAuthorizationService;
use CleverReachCore\Core\BusinessLogic\Authorization\Http\AuthProxy;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use Exception;
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
     * Sets auth info.
     *
     * @param string $code
     *
     * @return void
     * @throws Exception
     */
    public function authorize(string $code): void
    {
        $authProxy = $this->getAuthProxy();
        $authInfo = $authProxy->getAuthInfo($code, $this->getRedirectURL());
        $this->setAuthInfo($authInfo);
    }
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

    /**
     * @return AuthProxy
     */
    private function getAuthProxy(): AuthProxy
    {
        /** @var AuthProxy $authProxy */
        $authProxy = ServiceRegister::getService(AuthProxy::class);

        return $authProxy;
    }
}
