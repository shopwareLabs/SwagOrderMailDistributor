<?php

namespace SwagOrderMailDistributor;

use Shopware\Core\Content\MailTemplate\MailTemplateEntity;
use Shopware\Core\Framework\Validation\DataBag\DataBag;

class MailTemplateDataBag extends DataBag
{
    public static function createFromEntity(
        MailTemplateEntity $template,
        array $recipients,
        ?string $salesChannelId
    ): MailTemplateDataBag
    {
        $data = new self();

        $data->set('recipients', $recipients);
        if ($salesChannelId) {
            $data->set('salesChannelId', $salesChannelId);
        }

        $data->set('senderName', $template->getTranslation('senderName'));
        $data->set('templateId', $template->getId());
        $data->set('customFields', $template->getCustomFields());
        $data->set('contentHtml', $template->getTranslation('contentHtml'));
        $data->set('contentPlain', $template->getTranslation('contentPlain'));
        $data->set('subject', $template->getTranslation('subject'));
        $data->set('mediaIds', []);

        return $data;
    }
}
