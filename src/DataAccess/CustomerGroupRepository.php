<?php

namespace CleverReachCore\DataAccess;

use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

/**
 * Class CustomerGroupRepository
 *
 * @package CleverReachCore\DataAccess
 */
class CustomerGroupRepository
{
    private EntityRepositoryInterface $customerGroupRepository;

    /**
     * Creates new repository
     *
     * @param EntityRepositoryInterface $customerGroupRepository
     */
    public function __construct(EntityRepositoryInterface $customerGroupRepository)
    {
        $this->customerGroupRepository = $customerGroupRepository;
    }

    /**
     * Returns default customer group
     *
     * @return CustomerGroupEntity
     */
    public function getDefaultCustomerGroup(): CustomerGroupEntity
    {
        return $this->customerGroupRepository->search(new Criteria(), Context::createDefaultContext())->first();
    }
}
