<?php declare(strict_types=1);

namespace SwagOrderMailDistributor;

use Shopware\Core\Content\MailTemplate\Aggregate\MailTemplateType\MailTemplateTypeEntity;
use Shopware\Core\Content\Rule\RuleEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class OrderMailDistributionEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string|null
     */
    protected $mailTemplateTypeId;

    /**
     * @var MailTemplateTypeEntity|null
     */
    protected $mailTemplateType;

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

    public function getMailTemplateType(): ?MailTemplateTypeEntity
    {
        return $this->mailTemplateType;
    }

    public function setMailTemplateTypeType(MailTemplateTypeEntity $mailTemplateType): void
    {
        $this->mailTemplateType = $mailTemplateType;
    }

    public function getMailTemplateTypeId(): ?string
    {
        return $this->mailTemplateTypeId;
    }

    public function setMailTemplateTypeId(?string $mailTemplateTypeId): void
    {
        $this->mailTemplateTypeId = $mailTemplateTypeId;
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
