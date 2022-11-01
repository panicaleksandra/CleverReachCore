<?php

namespace CleverReachCore\Controller\API\Backend;

use CleverReachCore\Business\Service\AuthorizationService;
use CleverReachCore\Core\BusinessLogic\Authorization\Contracts\AuthorizationService as BaseAuthorizationService;
use CleverReachCore\Core\Infrastructure\Logger\Logger;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Utility\Initializer;
use Exception;
use Shopware\Core\Framework\Api\Response\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;

/**
 * Class StatusController
 *
 * @package CleverReachCore\Controller\API\Backend
 */
class StatusController extends AbstractController
{
    private Initializer $initializer;

    /**
     * Creates StatusController.
     *
     * @param Initializer $initializer
     */
    public function __construct(Initializer $initializer)
    {
        $this->initializer = $initializer;
    }

    /**
     * Checks status.
     * @RouteScope(scopes={"api"})
     * @Route(path="/api/cleverreach/status",
     *        name="api.cleverreach.status",
     *        methods={"GET"})
     *
     * @return JsonApiResponse
     */
    public function handle(): JsonApiResponse
    {
        try {
            $this->initializer->init();

            /** @var AuthorizationService $authService */
            $authService = ServiceRegister::getService(BaseAuthorizationService::class);
            $token = $authService->getAuthInfo()->getAccessToken();

            return new JsonApiResponse(['status' => $token !== '']);
        } catch (Exception $exception) {
            Logger::logError($exception->getMessage());

            return new JsonApiResponse(['status' => false],400);
        }
    }
}
