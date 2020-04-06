<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\OrderMailDistributor\Migration;

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
            CREATE TABLE IF NOT EXISTS `swag_order_mail_distribution` (
              `id` BINARY(16) NOT NULL,
              `active` TINYINT(1) NOT NULL DEFAULT 0,
              `mail_to` VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
              `mail_template_type_id` BINARY(16) NOT NULL,
              `rule_id` BINARY(16) NOT NULL,
              `created_at`  DATETIME(3) NOT NULL,
              `updated_at`  DATETIME(3) NULL,
              PRIMARY KEY (`id`),
              CONSTRAINT `fk.order_mail_distribution.mail_template_type_id`
                FOREIGN KEY (`mail_template_type_id`) REFERENCES `mail_template_type` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
              CONSTRAINT `fk.order_mail_distribution.rule_id`
                FOREIGN KEY (`rule_id`) REFERENCES `rule` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
