<?php

namespace Over_Code\Controllers\client;

use Over_Code\Libraries\Email;
use Over_Code\Libraries\FormTest;
use Over_Code\Controllers\MainController;

/**
 * Manage contact mail process
 */
class ContactController extends MainController
{
    use \Over_Code\Libraries\Helpers;

    public function mail()
    {
        $form = new FormTest();

        if ($form->contactTest()) {
            $firstName = $this->getPOST('first_name');
            $lastName = $this->getPOST('last_name');
            $email = $this->getPOST('email');
            $subject = $this->getPOST('subject');
            $content = $this->getPOST('content');
            
            $mail = new Email();
            $mail->sendTextEmail(
                $firstName,
                $lastName,
                $email,
                $subject,
                $content
            );

            $this->template = 'client' . DS . 'contact/message-sent.twig';
        }
    }
}
