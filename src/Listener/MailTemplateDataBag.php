<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\OrderMailDistributor\Listener;

use Shopware\Core\Content\MailTemplate\MailTemplateEntity;
use Shopware\Core\Framework\Validation\DataBag\DataBag;

class MailTemplateDataBag extends DataBag
{
    public static function createFromEntity(
        MailTemplateEntity $template,
        array $recipients,
        ?string $salesChannelId
    ): MailTemplateDataBag {
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
