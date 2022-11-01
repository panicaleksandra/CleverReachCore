<?php

namespace CleverReachCore\Utility;

use CleverReachCore\Business\Bootstrap;
use CleverReachCore\Business\Service\ReceiverService;
use CleverReachCore\Business\Service\WebhookService;
use CleverReachCore\Core\Infrastructure\Logger\Interfaces\ShopLoggerAdapter;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Infrastructure\Service\LoggerService;
use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * class Initializer
 *
 * @package CleverReachCore\Utility
 */
class Initializer
{
    private Connection $connection;
    private EntityRepositoryInterface $entityRepository;
    private LoggerService $loggerService;
    private UrlGeneratorInterface $urlGenerator;
    private ReceiverService $receiverService;
    private WebhookService $webhookService;
    private RequestContext $requestContext;

    /**
     * @param Connection $connection
     * @param EntityRepositoryInterface $entityRepository
     * @param LoggerService $loggerService
     * @param UrlGeneratorInterface $urlGenerator
     * @param ReceiverService $receiverService
     * @param WebhookService $webhookService
     * @param RequestContext $requestContext
     */
    public function __construct(
        Connection $connection,
        EntityRepositoryInterface $entityRepository,
        LoggerService $loggerService,
        UrlGeneratorInterface $urlGenerator,
        ReceiverService $receiverService,
        WebhookService $webhookService,
        RequestContext $requestContext
    ) {
        $this->connection = $connection;
        $this->entityRepository = $entityRepository;
        $this->loggerService = $loggerService;
        $this->urlGenerator = $urlGenerator;
        $this->receiverService = $receiverService;
        $this->webhookService = $webhookService;
        $this->requestContext = $requestContext;
    }

    /**
     * Initializes components.
     *
     * @return void
     */
    public function init(): void {
        Bootstrap::init();
        $this->registerServices();
    }

    /**
     * @return void
     */
    private function registerServices(): void
    {
        ServiceRegister::registerService(
            Connection::class,
            function() {
                return $this->connection;
            }
        );
        ServiceRegister::registerService(
            EntityRepositoryInterface::class,
            function() {
                return $this->entityRepository;
            }
        );
        ServiceRegister::registerService(
            ShopLoggerAdapter::class,
            function() {
                return $this->loggerService;
            }
        );
        ServiceRegister::registerService(
            UrlGeneratorInterface::class,
            function() {
                return $this->urlGenerator;
            }
        );
        ServiceRegister::registerService(
            ReceiverService::class,
            function() {
                return $this->receiverService;
            }
        );
        ServiceRegister::registerService(
            WebhookService::class,
            function() {
                return $this->webhookService;
            }
        );
        ServiceRegister::registerService(
            RequestContext::class,
            function() {
                return $this->requestContext;
            }
        );
    }
}
