<?php

namespace CleverReachCore\Controller\API\Frontend;

use CleverReachCore\Business\Service\AuthorizationService;
use CleverReachCore\Core\BusinessLogic\Authorization\Contracts\AuthorizationService as BaseAuthorizationService;
use CleverReachCore\Core\BusinessLogic\Authorization\Tasks\Composite\ConnectTask;
use CleverReachCore\Core\BusinessLogic\TaskExecution\QueueService;
use CleverReachCore\Core\Infrastructure\Configuration\Configuration;
use CleverReachCore\Core\Infrastructure\Logger\Logger;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Task\InitialSync\InitialSyncTask;
use CleverReachCore\Utility\Initializer;
use Exception;
use HttpException;
use Shopware\Core\Framework\Api\Response\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;

/**
 * Class AuthController
 *
 * @package CleverReachCore\Controller\API\Frontend
 */
class AuthController extends AbstractController
{
    private Initializer $initializer;
    private QueueService $queueService;

    /**
     * Creates AuthController.
     *
     * @param Initializer $initializer
     * @param QueueService $queueService
     */
    public function __construct(
        Initializer $initializer,
        QueueService $queueService) {
        $this->initializer = $initializer;
        $this->queueService = $queueService;
    }

    /**
     * Handles request for authorization and initial synchronization.
     * @RouteScope(scopes={"storefront"})
     * @Route(path="/storefront/authorization",
     *        name="storefront.authorization",
     *        defaults={"auth_required"=false},
     *        methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return JsonApiResponse
     */
    public function handle(Request $request): JsonApiResponse
    {
        try {
            $this->initializer->init();

            $code = $request->query->get('code');

            if (!$code) {
                throw new HttpException('Code not set.', 400);
            }

            $authService = $this->getAuthService();
            $authService->authorize($code);

            $configService = ServiceRegister::getService(Configuration::class);
            $this->queueService->enqueue($configService->getDefaultQueueName(), new ConnectTask());
            $this->queueService->enqueue($configService->getDefaultQueueName(), new InitialSyncTask());

            return new JsonApiResponse(['success' => true], 200);
        } catch (Exception $exception) {
            Logger::logError($exception->getMessage());

            return new JsonApiResponse(['success' => false], 400);
        }
    }

    /**
     * @return AuthorizationService
     */
    private function getAuthService(): AuthorizationService
    {
        /** @var AuthorizationService $authService */
        $authService = ServiceRegister::getService(BaseAuthorizationService::class);

        return $authService;
    }
}
