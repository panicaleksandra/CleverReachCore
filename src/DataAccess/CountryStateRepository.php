<?php

namespace CleverReachCore\DataAccess;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Country\Aggregate\CountryState\CountryStateEntity;

/**
 * Class CountryStateRepository
 *
 * @package CleverReachCore\DataAccess
 */
class CountryStateRepository
{
    private EntityRepositoryInterface $countryStateRepository;

    /**
     * Creates new repository
     *
     * @param EntityRepositoryInterface $countryStateRepository
     */
    public function __construct(EntityRepositoryInterface $countryStateRepository)
    {
        $this->countryStateRepository = $countryStateRepository;
    }

    /**
     * Finds CountryStateEntity by name
     *
     * @param string $name
     *
     * @return CountryStateEntity|null
     */
    public function findCountryStateByName(string $name): ?CountryStateEntity
    {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('name', $name));

        return $this->countryStateRepository->search($criteria, Context::createDefaultContext())->first();
    }

    /**
     * Returns default CountryStateEntity
     *
     * @return CountryStateEntity
     */
    public function getDefaultCountryState(): CountryStateEntity
    {
        return $this->countryStateRepository->search(new Criteria(), Context::createDefaultContext())->first();
    }
}
