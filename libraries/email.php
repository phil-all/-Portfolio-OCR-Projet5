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
     * Send an HTML email
     *
     * @param array $reciever address(es) targeted by this email
     * @param string $title email title
     * @param string $body html email source code
     *
     * @return void
     */
    public function sendHtmlEmail(array $reciever, string $title, string $body): void
    {
        $transport = (new Swift_SmtpTransport($this->getENV('SMTP_SERVER'), $this->getENV('SMTP_PORT')))
            ->setUsername($this->getENV('SMTP_USERNAME'))
            ->setPassword($this->getENV('SMTP_PASSWORD'));

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($title))
            ->setFrom(['team.overcode@example.com' => 'Over_code Team'])
            ->setTo($reciever)
            ->setBody($body, 'text/html');

        $mailer->send($message);
    }

    /**
     * Send a text email from contact form to site admin
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $subject
     * @param string $content
     * 
     * @return void
     */
    public function sendTextEmail(string $firstName, string $lastName, string $email, string $subject, string $content): void
    {
        $transport = (new Swift_SmtpTransport($this->getENV('SMTP_SERVER'), $this->getENV('SMTP_PORT')))
            ->setUsername($this->getENV('SMTP_USERNAME'))
            ->setPassword($this->getENV('SMTP_PASSWORD'));

        $mailer = new Swift_Mailer($transport);

        // Create a message
        $message = (new Swift_Message($subject))
        ->setFrom([$email => $firstName . ' ' . $lastName])
        ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
        ->setBody($content);

        $mailer->send($message);
    }
}
