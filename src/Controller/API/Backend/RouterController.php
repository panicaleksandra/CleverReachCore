<?php

namespace CleverReachCore\Controller\API\Backend;

use CleverReachCore\Core\BusinessLogic\Authorization\Tasks\Composite\ConnectTask;
use CleverReachCore\Core\BusinessLogic\TaskExecution\QueueService;
use CleverReachCore\Core\Infrastructure\Logger\Logger;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Utility\Initializer;
use Shopware\Core\Framework\Api\Response\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Exception;
use CleverReachCore\Core\BusinessLogic\Authorization\Contracts\AuthorizationService as BaseAuthorizationService;
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
    public function __construct(Initializer $initializer) {
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
        return $this->isAuthorized() ? self::DASHBOARD_STATE_CODE : self::LANDING_STATE_CODE;
    }

    /**
     * Check if user is authorized with CleverReach.
     *
     * @return bool
     */
    private function isAuthorized(): bool
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
