<?php declare(strict_types=1);

namespace SwagOrderMailDistributor\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1585133580OrderMailDistribution extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1585133580;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `order_mail_distribution` (
              `id` BINARY(16) NOT NULL,
              `active` TINYINT(1) NOT NULL DEFAULT 0,
              `mail_to` VARCHAR(255) COLLATE utf8mb4_unicode_ci NULL,
              `mail_template_id` BINARY(16) NOT NULL,
              `rule_id` BINARY(16) NOT NULL,
              `created_at`  DATETIME(3)                             NOT NULL,
              `updated_at`  DATETIME(3)                             NULL,
              PRIMARY KEY (`id`),
              CONSTRAINT `fk.order_mail_distribution.mail_template_id`
                FOREIGN KEY (`id`) REFERENCES `mail_template` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
