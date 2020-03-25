<?php declare(strict_types=1);

namespace SwagOrderMailDistributor;

use Shopware\Core\Content\MailTemplate\MailTemplateDefinition;
use Shopware\Core\Content\Rule\RuleDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class OrderMailDistributionDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'order_mail_distribution';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return OrderMailDistributionEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->setFlags(new PrimaryKey(), new Required()),

            (new StringField('mail_to', 'mailTo'))->setFlags(new Required()),
            (new BoolField('active', 'active'))->setFlags(new Required()),

            (new FkField('mail_template_id', 'mailTemplateId', MailTemplateDefinition::class))->setFlags(new Required()),
            (new FkField('rule_id', 'ruleId', RuleDefinition::class))->setFlags(new Required()),

            new ManyToOneAssociationField('mailTemplate', 'mail_template_id', MailTemplateDefinition::class, 'id'),
            new ManyToOneAssociationField('rule', 'rule_id', RuleDefinition::class, 'id'),
        ]);
    }
}
