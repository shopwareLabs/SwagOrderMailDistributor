<?php declare(strict_types=1);

namespace SwagOrderMailDistributor;

use Shopware\Core\Content\Rule\RuleEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class OrderMailDistributionEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string|null
     */
    protected $mailTemplateId;

    /**
     * @var MailTemplateEntity|null
     */
    protected $mailTemplate;

    /**
     * @var string|null
     */
    protected $ruleId;

    /**
     * @var RuleEntity|null
     */
    protected $rule;

    /**
     * @var string
     */
    protected $mailTo;

    /**
     * @var bool
     */
    protected $active;


    public function getMailTemplate(): ?MailTemplateEntity
    {
        return $this->mailTemplate;
    }

    public function setMailTemplateType(MailTemplateEntity $mailTemplate): void
    {
        $this->mailTemplate = $mailTemplate;
    }

    public function getMailTemplateId(): ?string
    {
        return $this->mailTemplate;
    }

    public function setMailTemplateId(?string $mailTemplateId): void
    {
        $this->mailTemplateId = $mailTemplateId;
    }


    public function getApiAlias(): string
    {
        return 'order_mail_distribution';
    }

    /**
     * @return string
     */
    public function getMailTo(): string
    {
        return $this->mailTo;
    }

    /**
     * @param string $mailTo
     */
    public function setMailTo(string $mailTo): void
    {
        $this->mailTo = $mailTo;
    }

    public function getRule(): ?RuleEntity
    {
        return $this->rule;
    }

    public function setRule(?RuleEntity $rule): void
    {
        $this->rule = $rule;
    }

    public function getRuleId(): ?string
    {
        return $this->ruleId;
    }

    public function setRuleId(?string $ruleId): void
    {
        $this->ruleId = $ruleId;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
