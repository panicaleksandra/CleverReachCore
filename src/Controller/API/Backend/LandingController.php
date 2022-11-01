<?php

namespace CleverReachCore\Controller\API\Backend;

use CleverReachCore\Business\Service\AuthorizationService;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Utility\Initializer;
use Shopware\Core\Framework\Api\Response\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use CleverReachCore\Core\BusinessLogic\Authorization\Contracts\AuthorizationService as BaseAuthService;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;

/**
 * Class LandingController
 *
 * @package CleverReachCore\Controller\API\Backend
 */
class LandingController extends AbstractController
{
    private Initializer $initializer;

    public const HOSTNAME = 'https://rest.cleverreach.com/';
    public const AUTHORIZE_URI =
        'oauth/authorize.php?client_id=n4VcLY2We5&grant=basic&response_type=code&redirect_uri=';

    /**
     * Creates LandingController.
     *
     * @param Initializer $initializer
     */
    public function __construct(Initializer $initializer)
    {
        $this->initializer = $initializer;
    }

    /**
     * Returns url.
     * @RouteScope(scopes={"api"})
     * @Route(path="/api/cleverreach/geturl",
     *        name="api.cleverreach.geturl",
     *        methods={"GET", "POST"})
     *
     * @return JsonApiResponse
     */
    public function handle(): JsonApiResponse
    {
        $this->initializer->init();

        /** @var AuthorizationService $authService */
        $authService = ServiceRegister::getService(BaseAuthService::class);
        $url = self::HOSTNAME . self::AUTHORIZE_URI . $authService->getRedirectURL();

        return new JsonApiResponse(['returnUrl' => $url]);
    }
}
