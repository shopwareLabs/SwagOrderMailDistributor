<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\OrderMailDistributor\Test\Service;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\MailTemplate\Aggregate\MailTemplateType\MailTemplateTypeDefinition;
use Shopware\Core\Content\MailTemplate\Aggregate\MailTemplateType\MailTemplateTypeEntity;
use Shopware\Core\Content\MailTemplate\MailTemplateDefinition;
use Shopware\Core\Content\MailTemplate\MailTemplateEntity;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Swag\OrderMailDistributor\Service\MailTemplateLoader;

class MailTemplateLoaderTest extends TestCase
{
    use IntegrationTestBehaviour;

    /**
     * @var MailTemplateLoader
     */
    private $mailTemplateLoader;

    /**
     * @var EntityRepositoryInterface
     */
    private $mailTemplateTypeRepository;

    /**
     * @var Context
     */
    private $context;

    protected function setUp(): void
    {
        $this->context = Context::createDefaultContext();
        $this->mailTemplateTypeRepository = $this->getContainer()->get(MailTemplateTypeDefinition::ENTITY_NAME . '.repository');
        $this->mailTemplateLoader = $this->getContainer()->get(MailTemplateLoader::class);
    }

    public function testGetWithValidTemplate(): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('technicalName', 'order_confirmation_mail'));

        /** @var MailTemplateTypeEntity $type */
        $type = $this->mailTemplateTypeRepository->search(
            $criteria,
            $this->context
        )->first();

        $mailTemplate = $this->mailTemplateLoader->get($type->getId(), $this->context, null);

        static::assertNotNull($mailTemplate);
        static::assertSame($type->getId(), $mailTemplate->getMailTemplateTypeId());
    }

    public function testGetWithValidTemplateAndSalesChannel(): void
    {
        /** @var EntityRepositoryInterface $mailTemplateRepository */
        $mailTemplateRepository = $this->getContainer()->get(MailTemplateDefinition::ENTITY_NAME . '.repository');
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('mailTemplateType.technicalName', 'order_confirmation_mail'));

        /** @var MailTemplateEntity $mailTemplate */
        $mailTemplate = $mailTemplateRepository->search($criteria, $this->context)->first();

        $mailTemplateRepository->update(
            [
                [
                    'id' => $mailTemplate->getId(),
                    'salesChannels' => [
                        [
                            'mailTemplateTypeId' => $mailTemplate->getMailTemplateTypeId(),
                            'salesChannelId' => Defaults::SALES_CHANNEL,
                        ],
                    ],
                ],
            ],
            $this->context
        );

        /** @var string $mailTemplateId */
        $mailTemplateId = $mailTemplate->getMailTemplateTypeId();
        $mailTemplate = $this->mailTemplateLoader->get($mailTemplateId, $this->context, Defaults::SALES_CHANNEL);

        static::assertNotNull($mailTemplate);
        static::assertSame($mailTemplate->getMailTemplateTypeId(), $mailTemplate->getMailTemplateTypeId());
    }

    public function testGetWithValidTemplateAndInvalidSalesChannel(): void
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('technicalName', 'order_confirmation_mail'));

        /** @var MailTemplateTypeEntity $type */
        $type = $this->mailTemplateTypeRepository->search(
            $criteria,
            $this->context
        )->first();

        $mailTemplate = $this->mailTemplateLoader->get($type->getId(), $this->context, Uuid::randomHex());

        static::assertNotNull($mailTemplate);
        static::assertSame($type->getId(), $mailTemplate->getMailTemplateTypeId());
    }
}
