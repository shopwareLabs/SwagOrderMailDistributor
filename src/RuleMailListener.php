<?php

namespace SwagOrderMailDistributor;

use Shopware\Core\Checkout\Cart\Event\CheckoutOrderPlacedEvent;
use Shopware\Core\Content\MailTemplate\Service\MailServiceInterface;
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

    public function __construct(EntityRepositoryInterface $orderMailDistRepository, MailServiceInterface $mailService)
    {
        $this->mailService = $mailService;
        $this->repository = $orderMailDistRepository;
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

        $mails = $this->repository->search($criteria, $context);

//        foreach ($mails as $mail) {
//            $this->mailService->send(
//                $mail->getMailTemplateId(),
//                $mail->getTo()
//            );
//        }
    }
}
