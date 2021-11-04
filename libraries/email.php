<?php

namespace Over_Code\Libraries;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Email
{
    /**
     * Send an HTML email
     *
     * @param array $reciever address(es) targeted by this email, can be string or array if multiple
     * @param string $title email title
     * @param string $body html email source code
     * 
     * @return void
     */
    public function sendHtmlEmail(array $reciever, string $title, string $body): void
    {
        $transport = (new Swift_SmtpTransport($_ENV['SMTP_SERVER'], $_ENV['SMTP_PORT']))
            ->setUsername($_ENV['SMTP_USERNAME'])
            ->setPassword($_ENV['SMTP_PASSWORD']);

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($title))
            ->setFrom(['team.overcode@example.com' => 'Over_code Team'])
            ->setTo($reciever)
            ->setBody($body,'text/html');

        $mailer->send($message);
    }
}