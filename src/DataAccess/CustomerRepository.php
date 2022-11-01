<?php

namespace CleverReachCore\DataAccess;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

/**
 * Class CustomerRepository
 *
 * @package CleverReachCore\DataAccess
 */
class CustomerRepository
{
    private EntityRepositoryInterface $customerRepository;

    /**
     * Creates CustomerRepository.
     *
     * @param EntityRepositoryInterface $customerRepository
     */
    public function __construct(EntityRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Retrieves number of customers.
     *
     * @return int
     */
    public function count(): int
    {
        $criteria = (new Criteria())
            ->addAssociations($this->getCustomerAssociationsArray());

        return $this->customerRepository->search($criteria, Context::createDefaultContext())->count();
    }

    /**
     * @return string[]
     */
    private function getCustomerAssociationsArray(): array
    {
        return [
            'language',
            'salutation',
            'salesChannel',
            'salesChannel.domains',
            'defaultShippingAddress',
            'defaultShippingAddress.country',
            'defaultShippingAddress.countryState',
        ];
    }

    /**
     * Finds customers by emails.
     *
     * @param array $emails
     *
     * @return EntityCollection
     */
    public function getByEmails(array $emails): EntityCollection
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsAnyFilter('email', $emails))
            ->addAssociations($this->getCustomerAssociationsArray());

        return $this->customerRepository->search($criteria, Context::createDefaultContext())
            ->getEntities();
    }

    /**
     * Finds customer by id.
     *
     * @param string $id
     *
     * @return CustomerEntity|null
     */
    public function getById(string $id): ?CustomerEntity
    {
        $criteria = new Criteria([$id]);

        return $this->customerRepository->search($criteria, Context::createDefaultContext())->first();
    }

    /**
     * Finds customers by ids.
     *
     * @param array $ids
     *
     * @return EntityCollection
     */
    public function getByIds(array $ids): EntityCollection
    {
        $criteria = new Criteria($ids);

        return $this->customerRepository->search($criteria, Context::createDefaultContext())->getEntities();
    }

    /**
     * Retrieves customer emails.
     *
     * @return array
     */
    public function getEmails(): array
    {
        $criteria = (new Criteria())
            ->addAssociations($this->getCustomerAssociationsArray());
        $entities = $this->customerRepository->search($criteria, Context::createDefaultContext())->getEntities();

        return $this->getEmailsFromEntityCollection($entities);
    }

    /**
     * @param EntityCollection $collection
     *
     * @return array
     */
    private function getEmailsFromEntityCollection(EntityCollection $collection): array
    {
        $emails = [];

        foreach ($collection as $item) {
            /**@var CustomerEntity $item */
            $emails[] = $item->getEmail();
        }

        return $emails;
    }

    /**
     * Creates customer.
     *
     * @param array $data
     *
     * @return void
     */
    public function create(array $data): void
    {
        $this->customerRepository->create([$data], Context::createDefaultContext());
    }

    /**
     * Updates customer
     *
     * @param array $data
     *
     * @return void
     */
    public function update(array $data): void
    {
        $this->customerRepository->update([$data], Context::createDefaultContext());
    }

    /**
     * Finds customer by email.
     *
     * @param string $email
     *
     * @return CustomerEntity|null
     */
    public function getByEmail(string $email): ?CustomerEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('email', $email))
            ->addAssociations($this->getCustomerAssociationsArray());

        return $this->customerRepository->search($criteria, Context::createDefaultContext())->first();
    }
}
