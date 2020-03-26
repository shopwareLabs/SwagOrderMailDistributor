<?php

namespace SwagOrderMailDistributor;

use Shopware\Core\Checkout\Cart\Event\CheckoutOrderPlacedEvent;
use Shopware\Core\Content\MailTemplate\Service\MailServiceInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RuleMailListener implements EventSubscriberInterface
{
    /**
     * @var EntityRepositoryInterface
     */
    private $repository;

    /**
     * @var MailServiceInterface
     */
    private $mailService;

    /**
     * @var MailTemplateLoader
     */
    private $mailTemplateLoader;

    public function __construct(
        EntityRepositoryInterface $orderMailDistRepository,
        MailServiceInterface $mailService,
        MailTemplateLoader $mailTemplateLoader
    )
    {
        $this->mailService = $mailService;
        $this->repository = $orderMailDistRepository;
        $this->mailTemplateLoader = $mailTemplateLoader;
    }

    public static function getSubscribedEvents()
    {
        return [
            CheckoutOrderPlacedEvent::class => 'orderPlaced'
        ];
    }

    public function orderPlaced(CheckoutOrderPlacedEvent $event)
    {
        $context = $event->getContext();

        if (empty($context->getRuleIds())) {
            return;
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsAnyFilter('ruleId', $context->getRuleIds()));
        $criteria->addFilter(new EqualsFilter('active', true));

        $distributions = $this->repository->search($criteria, $context);

        /** @var OrderMailDistributionEntity $distribution */
        foreach ($distributions as $distribution) {
            $this->send($event, $distribution, $context);
        }
    }

    private function send(CheckoutOrderPlacedEvent $event, OrderMailDistributionEntity $distribution, Context $context): void
    {
        $template = $this->mailTemplateLoader->get(
            $distribution->getMailTemplateTypeId(),
            $context,
            $event->getSalesChannelId()
        );

        if (!$template) {
            // log error
            return;
        }

        $data = MailTemplateDataBag::createFromEntity($template, [$distribution->getMailTo() => $distribution->getMailTo()], $event->getSalesChannelId());

        $this->mailService->send(
            $data->all(),
            $context,
            ['order' => $event->getOrder()]
        );
    }
}
