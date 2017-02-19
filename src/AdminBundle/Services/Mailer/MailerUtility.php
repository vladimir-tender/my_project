<?php

namespace AdminBundle\Services\Mailer;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MailerUtility
{
    private $mailer_user;
    private $mailer_password;

    /**
     * @var TokenStorageInterface $security
     */
    private $security;

    /**
     * @var \Swift_Mailer $swiftmailer
     */
    private $swiftmailer;

    public function __construct($mailer_user, $mailer_password, $security, $swiftMailer)
    {
        $this->mailer_password = $mailer_password;
        $this->mailer_user = $mailer_user;
        $this->security = $security;
        $this->swiftmailer = $swiftMailer;
    }

    public function sendReportUserAction($message_body)
    {
        $user = $this->security->getToken()->getUser();
        $currentUserMail = $user->getEmail();

        $message_body .= " Пользователь: " . $user->getUsername()
            . ". IP: " . $_SERVER['REMOTE_ADDR']
            . ". User-Agent: " . $_SERVER['HTTP_USER_AGENT'];

        $message = \Swift_Message::newInstance()
            ->setSubject('User Action Report')
            ->setFrom($currentUserMail)
            ->setTo($this->mailer_user)
            ->setBody($message_body,'text/html')
        ;

        $this->swiftmailer->send($message);

        return $this;
    }
}