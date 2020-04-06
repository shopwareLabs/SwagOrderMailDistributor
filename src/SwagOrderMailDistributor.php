<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\OrderMailDistributor;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class SwagOrderMailDistributor extends Plugin
{
    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            parent::uninstall($uninstallContext);

            return;
        }

        /** @var Connection $connection */
        $connection = $this->container->get(Connection::class);
        $connection->exec('
DROP TABLE IF EXISTS swag_order_mail_distribution;
');

        parent::uninstall($uninstallContext);
    }
}
