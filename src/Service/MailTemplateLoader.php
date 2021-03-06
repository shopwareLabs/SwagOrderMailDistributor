<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\OrderMailDistributor\Service;

use Shopware\Core\Content\MailTemplate\MailTemplateEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class MailTemplateLoader implements MailTemplateLoaderInterface
{
    /**
     * @var EntityRepositoryInterface
     */
    private $repository;

    public function __construct(EntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function get(string $typeId, Context $context, ?string $salesChannelId): ?MailTemplateEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('mailTemplateTypeId', $typeId));
        $criteria->setLimit(1);

        if (!$salesChannelId) {
            return $this->repository->search($criteria, $context)->first();
        }

        $criteria->addFilter(new EqualsFilter('mail_template.salesChannels.salesChannel.id', $salesChannelId));

        /** @var MailTemplateEntity|null $mailTemplate */
        $mailTemplate = $this->repository->search($criteria, $context)->first();

        if ($mailTemplate) {
            return $mailTemplate;
        }

        // Fallback if no template for the sales channel is found
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('mailTemplateTypeId', $typeId));
        $criteria->setLimit(1);

        /* @var MailTemplateEntity|null $mailTemplate */
        return $this->repository->search($criteria, $context)->first();
    }
}
