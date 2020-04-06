<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\OrderMailDistributor\Service;

use Shopware\Core\Content\MailTemplate\MailTemplateEntity;
use Shopware\Core\Framework\Context;

interface MailTemplateLoaderInterface
{
    public function get(string $typeId, Context $context, ?string $salesChannelId): ?MailTemplateEntity;
}
