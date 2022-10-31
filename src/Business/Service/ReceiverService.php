<?php

namespace CleverReachCore\Business\Service;

use CleverReachCore\Business\Repository\CustomerRepositoryInterface;
use CleverReachCore\Core\BusinessLogic\Receiver\DTO\Receiver;
use CleverReachCore\Core\BusinessLogic\Receiver\DTO\Tag\Tag;
use CleverReachCore\Core\BusinessLogic\Receiver\ReceiverService as BaseReceiverService;
use DateTime;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerAddress\CustomerAddressEntity;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;

/**
 * Class ReceiverService
 *
 * @package CleverReachCore\Business\Service
 */
class ReceiverService extends BaseReceiverService
{
    public const TAG = 'Tag';
    public const STORE = 'SalesChannel';
    public const GROUP = 'CustomerGroup';

    private CustomerRepositoryInterface $baseRepository;

    /**
     * Creates receiver service.
     *
     * @param CustomerRepositoryInterface $baseRepository
     */
    public function __construct(CustomerRepositoryInterface $baseRepository)
    {
        parent::__construct();

        $this->baseRepository = $baseRepository;
    }

    /**
     * Retrieves receiver from the integrated system.
     *
     * @param string $email Receiver identifier.
     * @param bool $isServiceSpecificDataRequired
     *
     * @return Receiver | null
     */
    public function getReceiver($email, $isServiceSpecificDataRequired = false): ?Receiver
    {
        $entity = $this->baseRepository->getByEmail($email);

        if (!$entity) {
            return null;
        }

        $receiver = $this->formatReceiver($entity);
        $this->setAddressAndCompanyInfo($entity, $receiver);

        return $receiver;
    }

    /**
     * Retrieves a batch of receivers.
     *
     * @param string[] $emails List of receiver emails used for retrieval.
     * @param bool $isServiceSpecificDataRequired Specifies whether service should provide service specific data.
     *
     * @return Receiver[]
     */
    public function getReceiverBatch(array $emails, $isServiceSpecificDataRequired = false): array
    {
        $validEmails = array_filter(filter_var_array($emails, FILTER_VALIDATE_EMAIL, false));
        if (empty($validEmails)) {
            return [];
        }

        $receiversCollection = $this->baseRepository->getByEmails($validEmails);

        if (!$receiversCollection) {
            return [];
        }

        $receivers = [];

        foreach ($receiversCollection as $item) {
            $receivers[] = $this->formatReceiver($item);
        }

        return $receivers;
    }

    /**
     * Retrieves list of receiver emails provided by the integration.
     *
     * @return string[]
     */
    public function getReceiverEmails(): array
    {
        return $this->baseRepository->getEmails();
    }

    /**
     * @param Entity $entity
     * @param Receiver $receiver
     *
     * @return void
     */
    private function setAddressAndCompanyInfo(Entity $entity, Receiver $receiver): void
    {
        $address = $this->getAddress($entity);
        if ($address) {
            $streetAndNumber = explode(' ', $address->getStreet());
            $receiver->setZip($address->getZipcode());
            $receiver->setCity($address->getCity());
            $receiver->setCompany($address->getCompany() ?? '');
            $receiver->setStreet(implode(' ', array_slice($streetAndNumber, 0, -1)));
            $receiver->setStreetNumber($streetAndNumber[count($streetAndNumber) - 1]);
            $country = $address->getCountry() ? $address->getCountry()->getName() : '';
            $receiver->setCountry($country);
            $state = ($address->getCountryState() && $address->getCountryState()->getName()) ?
                $address->getCountryState()->getName() : '';
            $receiver->setState($state);
        }
    }

    /**
     * @param Entity $entity
     *
     * @return CustomerAddressEntity|null
     */
    private function getAddress(Entity $entity): ?CustomerAddressEntity
    {
        return $entity->getDefaultShippingAddress() ?: $entity->getDefaultBillingAddress();
    }

    /**
     * @param CustomerEntity $entity
     *
     * @return Receiver
     */
    private function formatReceiver(CustomerEntity $entity): Receiver
    {
        $receiver = new Receiver();

        $receiver->setEmail($entity->getEmail());

        $date = new DateTime();
        $date->setTimestamp($entity->getCreatedAt()->getTimestamp());
        $receiver->setRegistered($date);
        $receiver->setActivated($date);

        if ($entity->getSalesChannel()->getDomains()->first()) {
            $receiver->setSource($entity->getSalesChannel()->getDomains()->first()->getUrl());
        }

        $receiver->setTotalSpent($entity->getOrderTotalAmount() ?? '');
        $receiver->setLanguage(
            ($entity->getLanguage() && $entity->getLanguage()->getName()) ?
                $entity->getLanguage()->getName() : ''
        );
        $receiver->setSource(
            ($entity->getSalesChannel() &&
                $entity->getSalesChannel()->getDomains() &&
                $entity->getSalesChannel()->getDomains()->first()) ?
                $entity->getSalesChannel()->getDomains()->first()->getUrl() : ''
        );
        $receiver->setSalutation(
            ($entity->getSalutation() && $entity->getSalutation()->getDisplayName()) ?
                $entity->getSalutation()->getDisplayName() : ''
        );
        $receiver->setFirstName($entity->getFirstName());
        $receiver->setLastName($entity->getLastName());
        $receiver->setShop($entity->getSalesChannel()->getName());
        $receiver->setPhone($entity->getDefaultShippingAddress()->getPhoneNumber());
        $receiver->setCustomerNumber($entity->getCustomerNumber());
        $receiver->setOrderCount($entity->getOrderCount());

        if ($entity->getSalesChannel()) {
            $salesChannelTag = new Tag('Shopware 6', $entity->getSalesChannel()->getName());
            $salesChannelTag->setType(self::STORE);
            $receiver->addTag($salesChannelTag);
        }

        return $receiver;
    }
}
