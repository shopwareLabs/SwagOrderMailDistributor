<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\OrderMailDistributor\Test\Listener;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Cart\Event\CheckoutOrderPlacedEvent;
use Shopware\Core\Checkout\Cart\Price\Struct\CartPrice;
use Shopware\Core\Checkout\Cart\Tax\Struct\CalculatedTaxCollection;
use Shopware\Core\Checkout\Cart\Tax\Struct\TaxRuleCollection;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Content\MailTemplate\Aggregate\MailTemplateType\MailTemplateTypeDefinition;
use Shopware\Core\Content\MailTemplate\Service\Event\MailSentEvent;
use Shopware\Core\Content\Rule\RuleDefinition;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Adapter\Twig\StringTemplateRenderer;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Event\BusinessEventDispatcher;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextFactory;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Swag\OrderMailDistributor\Listener\RuleMailListener;
use Swag\OrderMailDistributor\OrderMailDistribution\OrderMailDistributionDefinition;

class RuleMailListenerTest extends TestCase
{
    use IntegrationTestBehaviour;

    /**
     * @var SalesChannelContext
     */
    private $salesChannelContext;

    /**
     * @var RuleMailListener
     */
    private $ruleMailListener;

    /**
     * @var BusinessEventDispatcher
     */
    private $dispatcher;

    /**
     * @var EntityRepositoryInterface
     */
    private $mailDistributorRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $mailTemplateTypeRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $ruleRepository;

    protected function setUp(): void
    {
        $salesChannelContextFactory = $this->getContainer()->get(SalesChannelContextFactory::class);
        $this->salesChannelContext = $salesChannelContextFactory->create(
            Uuid::randomHex(),
            Defaults::SALES_CHANNEL
        );
        $this->ruleMailListener = $this->getContainer()->get(RuleMailListener::class);
        $this->dispatcher = $this->getContainer()->get(BusinessEventDispatcher::class);
        $this->mailDistributorRepository = $this->getContainer()->get(OrderMailDistributionDefinition::ENTITY_NAME . '.repository');
        $this->mailTemplateTypeRepository = $this->getContainer()->get(MailTemplateTypeDefinition::ENTITY_NAME . '.repository');
        $this->ruleRepository = $this->getContainer()->get(RuleDefinition::ENTITY_NAME . '.repository');
    }

    public function testOrderPlacedWithoutRules(): void
    {
        $orderPlacedEvent = new CheckoutOrderPlacedEvent(
            $this->salesChannelContext->getContext(),
            new OrderEntity(),
            $this->salesChannelContext->getSalesChannel()->getId()
        );

        $phpunit = $this;
        $eventDidRun = false;
        $listenerClosure = function (MailSentEvent $event) use (&$eventDidRun, $phpunit): void {
            $eventDidRun = true;
            $phpunit->assertStringContainsString('Shipping costs: €0.00', $event->getContents()['text/html']);
        };

        $this->dispatcher->addListener(MailSentEvent::class, $listenerClosure);

        $this->ruleMailListener->orderPlaced($orderPlacedEvent);

        $this->dispatcher->removeListener(MailSentEvent::class, $listenerClosure);

        static::assertFalse($eventDidRun, 'The mail.sent Event did run');
    }

    public function testOrderPlaced(): void
    {
        $phpunit = $this;
        $eventDidRun = false;
        $listenerClosure = function (MailSentEvent $event) use (&$eventDidRun, $phpunit): void {
            $eventDidRun = true;
            $phpunit->assertStringContainsString('Shipping costs:', $event->getContents()['text/html']);
        };

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('technicalName', 'order_confirmation_mail'));
        $mailTemplateTypeId = $this->mailTemplateTypeRepository->searchIds($criteria, $this->salesChannelContext->getContext())->firstId();

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', 'All customers'));
        $allCustomerRuleId = $this->ruleRepository->searchIds($criteria, $this->salesChannelContext->getContext())->firstId();

        $this->mailDistributorRepository->create(
            [
                [
                    'mailTo' => 'foo@bar.com',
                    'active' => true,
                    'mailTemplateTypeId' => $mailTemplateTypeId,
                    'ruleId' => $allCustomerRuleId,
                ],
            ],
            $this->salesChannelContext->getContext()
        );

        $order = $this->getOrderEntity();
        $context = $this->salesChannelContext->getContext();
        $context->setRuleIds([$allCustomerRuleId]);
        $orderPlacedEvent = new CheckoutOrderPlacedEvent(
            $context,
            $order,
            $this->salesChannelContext->getSalesChannel()->getId()
        );

        $renderer = $this->getContainer()->get(StringTemplateRenderer::class);
        $renderer->enableTestMode();

        $this->dispatcher->addListener(MailSentEvent::class, $listenerClosure);

        $this->ruleMailListener->orderPlaced($orderPlacedEvent);

        $this->dispatcher->removeListener(MailSentEvent::class, $listenerClosure);

        static::assertTrue($eventDidRun, 'The mail.sent Event did not run');
    }

    public function testOrderPlacedWithInvalidTemplate(): void
    {
        $phpunit = $this;
        $eventDidRun = false;
        $listenerClosure = function (MailSentEvent $event) use (&$eventDidRun, $phpunit): void {
            $eventDidRun = true;
            $phpunit->assertStringContainsString('Shipping costs:', $event->getContents()['text/html']);
        };

        $mailTemplateTypeId = Uuid::randomHex();
        $this->mailTemplateTypeRepository->create([
            [
                'id' => $mailTemplateTypeId,
                'name' => 'Testing-Type',
                'technicalName' => 'testing_type',
            ],
        ], $this->salesChannelContext->getContext());

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', 'All customers'));
        $allCustomerRuleId = $this->ruleRepository->searchIds($criteria, $this->salesChannelContext->getContext())->firstId();

        $this->mailDistributorRepository->create(
            [
                [
                    'mailTo' => 'foo@bar.com',
                    'active' => true,
                    'mailTemplateTypeId' => $mailTemplateTypeId,
                    'ruleId' => $allCustomerRuleId,
                ],
            ],
            $this->salesChannelContext->getContext()
        );

        $order = $this->getOrderEntity();
        $context = $this->salesChannelContext->getContext();
        $context->setRuleIds([$allCustomerRuleId]);
        $orderPlacedEvent = new CheckoutOrderPlacedEvent(
            $context,
            $order,
            $this->salesChannelContext->getSalesChannel()->getId()
        );

        $renderer = $this->getContainer()->get(StringTemplateRenderer::class);
        $renderer->enableTestMode();

        $this->dispatcher->addListener(MailSentEvent::class, $listenerClosure);

        $this->ruleMailListener->orderPlaced($orderPlacedEvent);

        $this->dispatcher->removeListener(MailSentEvent::class, $listenerClosure);

        static::assertFalse($eventDidRun, 'The mail.sent Event did run');
    }

    private function getOrderEntity(): OrderEntity
    {
        $order = new OrderEntity();
        $order->setOrderNumber('testing');
        $order->setOrderDateTime(new \DateTime());
        $order->setAmountNet(100);
        $order->setAmountTotal(100);
        $order->setBillingAddressId(Uuid::randomHex());
        $order->setPrice(new CartPrice(100, 100, 100, new CalculatedTaxCollection(), new TaxRuleCollection(), ''));

        return $order;
    }
}
