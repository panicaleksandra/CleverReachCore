<?php

namespace CleverReachCore\Controller\API\Backend;

use CleverReachCore\Business\Service\SyncStatusService;
use CleverReachCore\Core\BusinessLogic\SecondarySynchronization\Tasks\Composite\SecondarySyncTask;
use CleverReachCore\Core\BusinessLogic\TaskExecution\QueueService;
use CleverReachCore\Core\Infrastructure\Configuration\Configuration;
use CleverReachCore\Core\Infrastructure\Logger\Logger;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
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
    private SyncStatusService $syncStatusService;

    /**
     * Creates SyncInformationController.
     *
     * @param Initializer $initializer
     * @param QueueService $queueService
     * @param SyncStatusService $syncStatusService
     */
    public function __construct(
        Initializer $initializer,
        QueueService $queueService,
        SyncStatusService $syncStatusService) {
        $this->initializer = $initializer;
        $this->queueService = $queueService;
        $this->syncStatusService = $syncStatusService;
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
            $this->initializer->init();

            return new JsonApiResponse([
                'clientId' => $this->syncStatusService->getClientId(),
                'syncStatus' => $this->syncStatusService->getSyncStatus()]);
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
            $this->initializer->init();

            $configService = ServiceRegister::getService(Configuration::class);
            $this->queueService->enqueue($configService->getDefaultQueueName(), new SecondarySyncTask());

            return new JsonApiResponse([
                'clientId' => $this->syncStatusService->getClientId(),
                'syncStatus' => SyncStatusService::SYNC_STATUS_IN_PROGRESS,
            ]);
        } catch (Exception $exception) {
            Logger::logError($exception->getMessage());

            return new JsonApiResponse();
        }
    }
}
