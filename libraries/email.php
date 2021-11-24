<?php

namespace Over_Code\Libraries;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Contain method used to send e-mail
 */
class Email
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Use in sending mails methods. Sets :
     * - a transport
     * - a mailer using created Transport
     * - send the message
     *
     * @param Swift_Message $message contain informations to, from, title/subject, content
     *
     * @return void
     */
    private function send(Swift_Message $message): void
    {
        $transport = (new Swift_SmtpTransport($this->getENV('SMTP_SERVER'), $this->getENV('SMTP_PORT')))
            ->setUsername($this->getENV('SMTP_USERNAME'))
            ->setPassword($this->getENV('SMTP_PASSWORD'));
        
        $mailer = new Swift_Mailer($transport);

        $mailer->send($message);
    }

    /**
     * Send an HTML email
     *
     * @param array $reciever address(es) targeted by this email
     * @param string $title email title
     * @param string $body html email source code
     *
     * @return void
     */
    public function sendHtmlEmail(string $reciever, string $title, string $body): void
    {
        $message = (new Swift_Message($title))
            ->setFrom([self::getENV('ADMIN_MAIL') => self::getENV('ADMIN_NAME')])
            ->setTo([$reciever])
            ->setBody($body, 'text/html');

        $this->send($message);
    }

    /**
     * Send a text email to site admin
     *
     * @param string $f_Name first name
     * @param string $l_Name last name
     * @param string $email
     * @param string $subject
     * @param string $content
     *
     * @return void
     */
    public function sendTextEmail(string $f_Name, string $l_Name, string $email, string $subject, string $content): void
    {
        $message = (new Swift_Message($subject))
        ->setFrom([$email => $f_Name . ' ' . $l_Name])
        ->setTo([self::getENV('ADMIN_MAIL') => self::getENV('ADMIN_NAME')])
        ->setBody($content);

        $this->send($message);
    }
}
