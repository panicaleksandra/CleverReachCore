<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Core\BusinessLogic\Receiver\Tasks\Composite\Configuration\SyncConfiguration;
use CleverReachCore\Core\BusinessLogic\Receiver\Tasks\Composite\ReceiverSyncTask;
use CleverReachCore\Core\BusinessLogic\TaskExecution\QueueService;
use CleverReachCore\Core\Infrastructure\Configuration\Configuration;
use CleverReachCore\Core\Infrastructure\ServiceRegister;
use CleverReachCore\Core\Infrastructure\TaskExecution\Exceptions\QueueStorageUnavailableException;
use CleverReachCore\DataAccess\CustomerRepository;
use Shopware\Core\Checkout\Customer\CustomerEntity;

/**
 * Class CustomerSubscriberService
 *
 * @package CleverReachCore\Business\Service
 */
class CustomerSubscriberService
{
    private CustomerRepository $customerRepository;
    private QueueService $queueService;

    /**
     * Creates CustomerSubscriberService.
     *
     * @param CustomerRepository $customerRepository
     * @param QueueService $queueService
     */
    public function __construct(CustomerRepository $customerRepository, QueueService $queueService)
    {
        $this->customerRepository = $customerRepository;
        $this->queueService = $queueService;
    }

    /**
     * Handles request for creating/updating customer.
     *
     * @param string $id
     *
     * @return void
     * @throws QueueStorageUnavailableException
     */
    public function handleCustomerUpsertEvent(string $id): void
    {
        $email = $this->getCustomerEmail($id);
        /** @var ConfigService $configService */
        $configService = ServiceRegister::getService(Configuration::class);

        $this->queueService->enqueue(
            $configService->getDefaultQueueName(),
            new ReceiverSyncTask(new SyncConfiguration([$email]))
        );
    }

    /**
     * @param string $id
     *
     * @return string
     */
    private function getCustomerEmail(string $id): string
    {
        /** @var CustomerEntity $customer */
        $customer = $this->customerRepository->getById($id);

        return $customer->getEmail();
    }
}
