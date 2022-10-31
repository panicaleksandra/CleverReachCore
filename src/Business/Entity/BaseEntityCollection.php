<?php

namespace CleverReachCore\Business\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * Class BaseEntityCollection
 *
 * @package CleverReachCore\Business\Entity
 */
class BaseEntityCollection extends EntityCollection
{
    /**
     * Returns BaseEntity class.
     *
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return BaseEntity::class;
    }
}
