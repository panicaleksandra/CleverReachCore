<?php

namespace CleverReachCore\DataAccess;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Country\CountryEntity;

/**
 * Class CountryRepository
 *
 * @package CleverReachCore\DataAccess
 */
class CountryRepository
{
    private EntityRepositoryInterface $countryRepository;

    /**
     * Creates new repository
     *
     * @param EntityRepositoryInterface $countryRepository
     */
    public function __construct(EntityRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * Finds country by name
     *
     * @param string $name
     *
     * @return CountryEntity|null
     */
    public function findCountryByName(string $name): ?CountryEntity
    {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('name', $name));

        return $this->countryRepository->search($criteria, Context::createDefaultContext())->first();
    }

    /**
     * Returns default country
     *
     * @return CountryEntity
     */
    public function getDefaultCountry(): CountryEntity
    {
        return $this->countryRepository->search(new Criteria(), Context::createDefaultContext())->first();
    }
}
