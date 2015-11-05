<?php

namespace Rj\EmailBundle\Mailer;

use Rj\EmailBundle\Swift\MessageFactory;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use Rj\EmailBundle\Entity\EmailTemplateManager;
use Rj\EmailBundle\Swift\Message;

/**
 * @author Jeremy Marc <jeremy.marc@me.com>
 */
class TwigSwiftMailer implements MailerInterface
{
    protected $mailer;
    protected $router;
    protected $parameters;
    protected $manager;

    public function __construct($mailer, RouterInterface $router, EmailTemplateManager $manager, MessageFactory $factory, array $parameters)
    {
        $this->mailer     = $mailer;
        $this->router     = $router;
        $this->manager    = $manager;
        $this->factory    = $factory;
        $this->parameters = $parameters;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['confirmation'];
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);

        $message = $this->factory->generate($template, null, array(
            'username' => $user->getUsername(),
            'confirmationUrl' =>  $url,
        ));

        $message->setTo($user->getEmail());

        $this->mailer->send($message);
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['resetting'];
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);

        $message = $this->factory->generate($template, null, array(
            'username' => $user->getUsername(),
            'confirmationUrl' =>  $url,
        ));

        $message->setTo($user->getEmail());

        $this->mailer->send($message);
    }
}
