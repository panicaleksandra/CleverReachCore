<?php

namespace CleverReachCore\DataAccess;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Salutation\SalutationEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Context;

/**
 * Class SalutationRepository
 *
 * @package CleverReachCore\DataAccess
 */
class SalutationRepository
{
    private EntityRepositoryInterface $salutationRepository;

    private const DEFAULT_SALUTATION = 'undefined';

    /**
     * Creates SalutationRepository.
     *
     * @param EntityRepositoryInterface $salutationRepository
     */
    public function __construct(EntityRepositoryInterface $salutationRepository)
    {
        $this->salutationRepository = $salutationRepository;
    }

    /**
     * Finds salutation by key
     *
     * @param string $key
     *
     * @return SalutationEntity|null
     */
    public function findSalutationByKey(string $key): ?SalutationEntity
    {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('salutationKey', $key));

        return $this->salutationRepository->search($criteria, Context::createDefaultContext())->first();
    }

    /**
     * Returns default salutation
     *
     * @return SalutationEntity
     */
    public function findDefaultSalutation(): SalutationEntity
    {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('salutationKey', self::DEFAULT_SALUTATION));

        return $this->salutationRepository->search($criteria, Context::createDefaultContext())->first();
    }
}
