<?php

namespace Over_Code\Controllers\Client;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Over_Code\Libraries\Jwt;
use Over_Code\Libraries\Twig;
use Over_Code\Models\UserModel;
use Over_Code\Controllers\UserController;

/**
 * Manage user access: resgistration, log-in and log-out
 */
class MembresController extends UserController
{
    use \Over_Code\Libraries\Helpers;
    use \Over_Code\Libraries\User\Register;

    public function inscriptionConnexion()
    {
        $this->template = 'client' . DS . 'signin-login.twig';
    }
//
//
//
    public function login(?string $email = NULL, ?string $pass = NULL): void
    {
        $user = new UserModel();

        $user->set_logmail($email);
        $user->set_logPass($pass);

        $auth = $user->auth($user->get_logmail(), $user->get_logpass());

        $status = ($auth) ? $user->getStatus() : 'authentification-error';

        if ($status === 'active') {            
            $user->hydrate();
            
            if ($email === NULL && $pass === NULL) {
                $this->redirect(SITE_ADRESS);
            }
        }

        $this->template = ($email === NULL && $pass === NULL)
            ? 'client' . DS . $status . '-user.twig'
            : 'client' . DS . 'new-user-welcome.twig'
        ;        
    }

    /**
     * Log-out an user and set on null its token in database
     *
     * @return void
     */
    public function deconnexion(): void
    {
        $user = new userModel();

        $user->set_tokenNull();
        
        session_unset();
        session_destroy();

        $this->redirect(SITE_ADRESS);
    }

    /**
     * Create a user in database, with pending status and send him activation mail,
     * if registrations_test return true.
     *
     * @return void
     */
    public function register()
    {
          $this->template = 'client' . DS . 'registration-failed.twig';

        if ($this->registration_form_test()) {
            $date = $this->arrayDate(900); // 900s = 15 min

            $jwt = new Jwt();
            $claims = [
                'sub' => 'registration',
                'iat' => $date['timestamp'],
                'exp' => $date['expiration'],
                'email' => $this->get_POST('email')
            ];
            $token = $jwt->generateToken($claims);

            $user = new UserModel();
            $user->createUser($token, $date['date_time']);

            $twigMail = new Twig;
            $params = [
                'token' => $jwt->tokenToUri($token)
            ];
            $mailTemplate = 'emails'. DS . 'validation-link.twig';
            $title = 'Confirmation d\'inscription - [Ne pas répondre]';
           
            // Create the Transport
            $transport = (new Swift_SmtpTransport($_ENV['SMTP_SERVER'], $_ENV['SMTP_PORT']))
                ->setUsername($_ENV['SMTP_USERNAME'])
                ->setPassword($_ENV['SMTP_PASSWORD']);

            // Create the Mailer using created Transport
            $mailer = new Swift_Mailer($transport);

            // Create a message            
            $message = (new Swift_Message($title))
                ->setFrom(['team.overcode@example.com' => 'Over_code Team'])
                ->setTo(['adresse.test@test.org'])
                ->setBody($twigMail->getTwig()->render($mailTemplate, $params),'text/html');

            // Send the message
            $mailer->send($message);

            $this->template = 'client' . DS . 'validation-link-sent.twig';
        }
    }

    public function validation(array $params)
    {
        $this->template = 'client' . DS . 'invalid-validation-link.twig';

        $jwt = new Jwt();      
        $token = $jwt->uriToToken($params);

        if ($jwt->isJWT($token) && $jwt->isSignatureCorrect($token)) {
            $payload = $jwt->decode_data($token, 1);

            $date = $this->arrayDate();
            $timestamp = $date['timestamp'];

            $email = $payload['email'];
                
            if ($this->isPending($email) && ($timestamp < $payload['exp'])) {
                $this->accountValidation($email);
                $this->template = 'client' . DS . 'new-user-welcome.twig';
            }

            if ($timestamp > $payload['exp']) {
                $this->template = 'client' . DS . 'validation-expired.twig';
            }
        }
    }
}