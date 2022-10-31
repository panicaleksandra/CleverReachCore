<?php

namespace CleverReachCore\Controller\API\Backend;

use CleverReachCore\Business\Bootstrap;
use CleverReachCore\Business\Service\AuthorizationService;
use CleverReachCore\Core\BusinessLogic\Authorization\Contracts\AuthorizationService as BaseAuthorizationService;
use CleverReachCore\Core\BusinessLogic\SecondarySynchronization\Tasks\Composite\SecondarySyncTask;
use CleverReachCore\Core\BusinessLogic\TaskExecution\QueueService;
use CleverReachCore\Core\Infrastructure\Configuration\Configuration;
use CleverReachCore\Core\Infrastructure\Logger\Logger;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Core\Infrastructure\TaskExecution\QueueItem;
use CleverReachCore\Task\InitialSync\InitialSyncTask;
use CleverReachCore\Utility\Initializer;
use Exception;
use Shopware\Core\Framework\Api\Response\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;

/**
 * Class SyncInformationController
 *
 * @package CleverReachCore\Controller\API\Backend
 */
class SyncInformationController extends AbstractController
{
    private Initializer $initializer;
    private QueueService $queueService;

    /**
     * Creates SyncInformationController.
     *
     * @param Initializer $initializer
     * @param QueueService $queueService
     */
    public function __construct(
        Initializer $initializer,
        QueueService $queueService
    ) {
        $this->initializer = $initializer;
        $this->queueService = $queueService;
    }

    /**
     * Checks status.
     * @RouteScope(scopes={"api"})
     * @Route(path="/api/cleverreach/getsyncstatus",
     *        name="api.cleverreach.getsyncstatus",
     *        methods={"GET", "POST"})
     *
     * @return JsonApiResponse
     */
    public
    function checkStatus(): JsonApiResponse
    {
        try {
            Bootstrap::init();
            $this->initializer->registerServices();

            /** @var AuthorizationService $authService */
            $authService = ServiceRegister::getService(BaseAuthorizationService::class);
            $clientId = $authService->getUserInfo()->getId();

            /** @var QueueItem $secondarySyncItem */
            $secondarySyncItem = $this->queueService->findLatestByType(SecondarySyncTask::getClassName());

            if ($secondarySyncItem) {
                $syncStatus = $secondarySyncItem->getStatus() === 'queued' ?
                    'In progress' : $secondarySyncItem->getStatus();

                return new JsonApiResponse(['clientId' => $clientId ?? '', 'syncStatus' => $syncStatus]);
            }

            $initialSyncItem = $this->queueService->findLatestByType(InitialSyncTask::getClassName());
            if ($initialSyncItem) {
                $syncStatus = $initialSyncItem->getStatus() === 'queued' ?
                    'In progress' : $initialSyncItem->getStatus();

                return new JsonApiResponse(['clientId' => $clientId ?? '', 'syncStatus' => $syncStatus]);
            }

            $syncStatus = 'In progress';

            return new JsonApiResponse(['clientId' => $clientId ?? '', 'syncStatus' => $syncStatus]);
        } catch (Exception $exception) {
            Logger::logError($exception->getMessage());

            return new JsonApiResponse();
        }
    }

    /**
     * Handles request for manual sync.
     * @RouteScope(scopes={"api"})
     * @Route(path="/api/cleverreach/manualsync",
     *        name="api.cleverreach.manualsync",
     *        methods={"GET", "POST"})
     *
     * @return JsonApiResponse
     */
    public
    function handleManualSync(): JsonApiResponse
    {
        try {
            Bootstrap::init();
            $this->initializer->registerServices();

            /** @var AuthorizationService $authService */
            $authService = ServiceRegister::getService(BaseAuthorizationService::class);
            $clientId = $authService->getUserInfo()->getId();

            $configService = ServiceRegister::getService(Configuration::class);
            $this->queueService->enqueue($configService->getDefaultQueueName(), new SecondarySyncTask());

            return new JsonApiResponse([
                'clientId' => $clientId,
                'syncStatus' => 'In progress',
            ]);
        } catch (Exception $exception) {
            Logger::logError($exception->getMessage());

            return new JsonApiResponse();
        }
    }
}
