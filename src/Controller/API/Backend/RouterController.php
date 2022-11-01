<?php

namespace CleverReachCore\Controller\API\Backend;

use CleverReachCore\Business\Service\RouterService;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Utility\Initializer;
use Shopware\Core\Framework\Api\Response\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;

/**
 * Class RouterController
 *
 * @package CleverReachCore\Controller\API\Backend
 */
class RouterController extends AbstractController
{
    public const LANDING_STATE_CODE = 'landing';
    public const DASHBOARD_STATE_CODE = 'dashboard';

    private Initializer $initializer;

    /**
     * Creates RouterController.
     *
     * @param Initializer $initializer
     */
    public function __construct(Initializer $initializer)
    {
        $this->initializer = $initializer;
    }

    /**
     * Returns page for display.
     * @RouteScope(scopes={"api"})
     * @Route(path="/api/cleverreach/router",
     *        name="api.cleverreach.router",
     *        methods={"GET", "POST"})
     *
     * @return JsonApiResponse
     */
    public function handle(): JsonApiResponse
    {
        $this->initializer->init();

        return new JsonApiResponse(['page' => $this->getPage()]);
    }

    /**
     * Returns page code.
     *
     * @return string
     */
    private function getPage(): string
    {
        /** @var RouterService $routerService */
        $routerService = ServiceRegister::getService(RouterService::class);

        return $routerService->isAuthorized() ? self::DASHBOARD_STATE_CODE : self::LANDING_STATE_CODE;
    }
}
