<?php

namespace CleverReachCore\Subscriber;

use CleverReachCore\Business\Bootstrap;
use CleverReachCore\Business\Repository\CustomerRepositoryInterface;
use CleverReachCore\Business\Service\ConfigService;
use CleverReachCore\Core\BusinessLogic\Receiver\Tasks\Composite\Configuration\SyncConfiguration;
use CleverReachCore\Core\BusinessLogic\Receiver\Tasks\Composite\ReceiverSyncTask;
use CleverReachCore\Core\BusinessLogic\TaskExecution\QueueService;
use CleverReachCore\Core\Infrastructure\Configuration\Configuration;
use CleverReachCore\Core\Infrastructure\Logger\Logger;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Utility\Initializer;
use Exception;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\CustomerEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CustomerSubscriber
 *
 * @package CleverReachCore\Subscriber
 */
class CustomerSubscriber implements EventSubscriberInterface
{
    private QueueService $queueService;
    private Initializer $initializer;
    private CustomerRepositoryInterface $customerRepository;

    /**
     * Creates CustomerSubscriber.
     *
     * @param QueueService $queueService
     * @param Initializer $initializer
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        QueueService $queueService,
        Initializer $initializer,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->queueService = $queueService;
        $this->initializer = $initializer;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CustomerEvents::CUSTOMER_WRITTEN_EVENT => 'handleCustomerUpsertEvents',
        ];
    }

    /**
     * Customer created or modified.
     *
     * @param EntityWrittenEvent $event
     *
     * @return void
     */
    public function handleCustomerUpsertEvents(EntityWrittenEvent $event): void
    {
        try {
            Bootstrap::init();
            $this->initializer->registerServices();

            $payloads = $event->getPayloads();
            $payload = reset($payloads);

            if (empty($payload['id'])) {
                return;
            }

            /** @var CustomerEntity $customer */
            $customer = $this->customerRepository->getById($payload['id']);
            $email = $customer->getEmail();
            /** @var ConfigService $configService */
            $configService = ServiceRegister::getService(Configuration::class);

            $this->queueService->enqueue(
                $configService->getDefaultQueueName(),
                new ReceiverSyncTask(new SyncConfiguration([$email]))
            );
        } catch (Exception $exception) {
            Logger::logError($exception->getMessage());
        }
    }
}
