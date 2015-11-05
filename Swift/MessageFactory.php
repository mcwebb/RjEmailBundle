<?php

namespace Rj\EmailBundle\Swift;

use Rj\EmailBundle\Entity\EmailTemplateManager;

class MessageFactory
{
    /**
     * @var EmailTemplateManager
     */
    private $templateManager;

    /**
     * @param EmailTemplateManager $templateManager
     */
    public function __construct(EmailTemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    /**
     * @param string $templateName
     * @param array $params
     * @param string|null $locale
     *
     * @return Message
     */
    public function generate($templateName, $params = array(), $locale = null)
    {
        $emailParts = $this->templateManager->renderEmail($templateName, $params, $locale);
        return Message::fromArray($emailParts);
    }
}