<?php

namespace CleverReachCore\Subscriber;

use CleverReachCore\Business\Bootstrap;
use CleverReachCore\Business\Service\CustomerSubscriberService;
use CleverReachCore\Core\Infrastructure\Logger\Logger;
use CleverReachCore\Utility\Initializer;
use Exception;
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
    private Initializer $initializer;
    private CustomerSubscriberService $customerSubscriberService;

    /**
     * Creates CustomerSubscriber.
     *
     * @param Initializer $initializer
     * @param CustomerSubscriberService $customerSubscriberService
     */
    public function __construct(
        Initializer $initializer,
        CustomerSubscriberService $customerSubscriberService
    ) {
        $this->initializer = $initializer;
        $this->customerSubscriberService = $customerSubscriberService;
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

            $this->customerSubscriberService->handleCustomerUpsertEvent($payload['id']);
        } catch (Exception $exception) {
            Logger::logError($exception->getMessage());
        }
    }
}
