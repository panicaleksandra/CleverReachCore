<?php

namespace CleverReachCore\Business\Repository;

use CleverReachCore\Core\BusinessLogic\Receiver\DTO\Receiver;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * Interface CustomerRepositoryInterface
 *
 * @package CleverReachCore\Business\Repository
 */
interface CustomerRepositoryInterface
{
    /**
     * Retrieves number of customers.
     *
     * @return int
     */
    public function count(): int;

    /**
     * Finds customer by email.
     *
     * @param string $email
     *
     * @return CustomerEntity|null
     */
    public function getByEmail(string $email): ?CustomerEntity;

    /**
     * Finds customers by emails.
     *
     * @param array $emails
     *
     * @return EntityCollection
     */
    public function getByEmails(array $emails): EntityCollection;

    /**
     * Finds customer by id.
     *
     * @param string $id
     *
     * @return CustomerEntity|null
     */
    public function getById(string $id): ?CustomerEntity;

    /**
     * Finds customers by ids.
     *
     * @param array $ids
     *
     * @return EntityCollection
     */
    public function getByIds(array $ids): EntityCollection;

    /**
     * Retrieves customer emails.
     *
     * @return array
     */
    public function getEmails(): array;

    /**
     * Creates customer.
     *
     * @param array $data
     *
     * @return void
     */
    public function create(array $data): void;

    /**
     * Updates customer
     *
     * @param array $data
     *
     * @return void
     */
    public function update(array $data): void;
}
