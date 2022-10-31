<?php

namespace CleverReachCore\DataAccess;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Language\LanguageEntity;

/**
 * Class LanguageRepository
 *
 * @package CleverReachCore\DataAccess
 */
class LanguageRepository
{
    private EntityRepositoryInterface $languageRepository;

    public function __construct(EntityRepositoryInterface $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * Finds language by name.
     *
     * @param string $name
     *
     * @return LanguageEntity|null
     */
    public function findLanguageByName(string $name): ?LanguageEntity
    {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('name', $name));

        return $this->languageRepository->search($criteria, Context::createDefaultContext())->first();
    }

    /**
     * Returns default language.
     *
     * @return LanguageEntity
     */
    public function getDefaultLanguage(): LanguageEntity
    {
        return $this->languageRepository->search(new Criteria(), Context::createDefaultContext())->first();
    }
}
