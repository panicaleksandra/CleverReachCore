<?php
declare(strict_types=1);

namespace CleverReachCore\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1665665269CleverReachEntityTableCreate extends MigrationStep
{
    public const ENTITY_TABLE = 'cleverreach_entity';

    /**
     * @inheritDoc
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1603810036;
    }

    /**
     * @inheritDoc
     *
     * @param Connection $connection
     *
     * @throws DBALException
     */
    public function update(Connection $connection): void
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `' . self::ENTITY_TABLE . '` (
        `shopware_id` BINARY(16) NOT NULL,
        `id` INT NOT NULL AUTO_INCREMENT,
        `type` VARCHAR(128) NOT NULL,
        `index_1` VARCHAR(255),
        `index_2` VARCHAR(255),
        `index_3` VARCHAR(255),
        `index_4` VARCHAR(255),
        `index_5` VARCHAR(255),
        `index_6` VARCHAR(255),
        `index_7` VARCHAR(255),
        `index_8` VARCHAR(255),
        `index_9` VARCHAR(255),
        `index_10` VARCHAR(255),
        `data` TEXT,
        PRIMARY KEY (`id`)
        )
        ENGINE = InnoDB
        DEFAULT CHARSET = utf8
        COLLATE = utf8_general_ci;';

        $connection->executeUpdate($sql);

        $this->addIndexes($connection);
    }

    /**
     * @param Connection $connection
     *
     * @throws DBALException
     */
    private function addIndexes(Connection $connection): void
    {
        $sql = "CREATE INDEX last_update ON " . self::ENTITY_TABLE . "(type, index_7)";
        $connection->executeUpdate($sql);

        $sql = "CREATE INDEX type_index1 ON " . self::ENTITY_TABLE . "(type, index_1)";
        $connection->executeUpdate($sql);
    }

    /**
     * @inheritDoc
     *
     * @param Connection $connection
     *
     * @throws DBALException
     */
    public function updateDestructive(Connection $connection): void
    {
        $sql = 'DROP TABLE IF EXISTS `' . self::ENTITY_TABLE . '`';
        $connection->executeUpdate($sql);
    }
}
