<?php

namespace CleverReachCore\DataAccess;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

/**
 * Class SalesChannelRepository
 *
 * @package CleverReachCore\DataAccess
 */
class SalesChannelRepository
{
    const DEFAULT_SALES_CHANNEL = 'Storefront';
    private EntityRepositoryInterface $salutationRepository;

    /**
     * Creates new repository
     *
     * @param EntityRepositoryInterface $salutationRepository
     */
    public function __construct(EntityRepositoryInterface $salutationRepository)
    {
        $this->salutationRepository = $salutationRepository;
    }

    /**
     * Finds sales channel by name
     *
     * @param string $name
     *
     * @return SalesChannelEntity|null
     */
    public function findSalesChannelByName(string $name): ?SalesChannelEntity
    {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('name', $name));

        return $this->salutationRepository->search($criteria, Context::createDefaultContext())->first();
    }

    /**
     * Returns default sales channel
     *
     * @return SalesChannelEntity
     */
    public function getDefaultSalesChannel(): SalesChannelEntity
    {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('name', self::DEFAULT_SALES_CHANNEL));

        return $this->salutationRepository->search($criteria, Context::createDefaultContext())->first();
    }
}
