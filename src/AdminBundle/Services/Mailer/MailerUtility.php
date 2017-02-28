<?php

namespace AdminBundle\Services\Mailer;

use MyShopBundle\Entity\User;

class MailerUtility
{
    private $mailer_user;
    private $mailer_password;


    /**
     * @var \Swift_Mailer $swiftmailer
     */
    private $swiftmailer;

    public function __construct($mailer_user, $mailer_password, $swiftMailer)
    {
        $this->mailer_password = $mailer_password;
        $this->mailer_user = $mailer_user;
        $this->swiftmailer = $swiftMailer;
    }

    public function sendReportUserAction($message_body, $user)
    {
        /**
         * @var User $user
         */
        $currentUser = $user->getUsername();
        $currentUserMail = $user->getEmail();

        $message_body .= " Пользователь: " . $currentUser
            . ". IP: " . $_SERVER['REMOTE_ADDR']
            . ". User-Agent: " . $_SERVER['HTTP_USER_AGENT'];

        $message = \Swift_Message::newInstance()
            ->setSubject('User Action Report')
            ->setFrom($currentUserMail)
            //->setTo('igorstokolos@gmail.com')
            ->setTo($this->mailer_user)
            ->setBody($message_body, 'text/html');


        //////////////////
/*
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
            ->setUsername('igorphphillel@gmail.com')
            ->setPassword('1mZasUinFYt7iPP');

        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance('Wonderful Subject')
            ->setFrom($currentUserMail)
            ->setTo($this->mailer_user)
            ->setBody('This is the text of the mail send by Swift using SMTP transport.');
        $numSent = $mailer->send($message);*/
        //////////////////


        //temporarily off
        /**@var \Swift_Mime_Message $message */
        /*$this->swiftmailer->send($message, $failedRecipients);*/

        return $this;
    }
}