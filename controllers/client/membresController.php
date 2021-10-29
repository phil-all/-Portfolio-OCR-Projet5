<?php

namespace Over_Code\Controllers\Client;

use DateTime;
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

    public function inscriptionConnexion()
    {
        $this->template = 'client' . DS . 'signin-login.twig';
    }

    /**
     * Log-in an user and redirect to home page
     *
     * @return void
     */
    public function login(): void
    {
        $user = new UserModel();

        $auth = $user->auth();

        $status = 'authentification-error';
        
        if ($auth) {
            $status = $user->getStatus();

            if ($status === 'active') {            
                $user->hydrate();
                $this->redirect(SITE_ADRESS);
            }
        }

        $this->template = 'client' . DS . $status . '-user.twig';        
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
        $user = new UserModel();

        $now = new \DateTime();
        $date_time = $now->format('Y-m-d H:i:s');
        $timestamp = $now->getTimestamp();
        $expiration = $timestamp + 900; // 15 minutes

        $this->template = 'client' . DS . 'registration-failed.twig';

        if ($user->registration_conditions($timestamp)) {
            $jwt = new Jwt();
            $claims = [
            'sub' => 'registration',
            'iat' => $timestamp,
            'exp' => $expiration,
            'email' => $this->get_POST('email')
            ];
            $token = $jwt->generateToken($claims);

            $user->createUser($token, $date_time);

            $twigMail = new Twig;
            $params = [
                'token' => $user->uriRegistrationToken($token)
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
        $user = new UserModel();
        $token = $user->uriToJwt_token($params);

        $jwt = new Jwt();

        $this->template = 'client' . DS . 'invalid-validation-link.twig';

        if ($jwt->isJWT($token) && $jwt->isSignatureCorrect($token)) {
            $payload = $jwt->decode_data($token, 1);

            $now = new DateTime();
            $timestamp = $now->getTimestamp();

            $email = $payload['email'];
                
            if ($user->isPending($email)) {
                $user->accountValidation($email);
                $this->template = 'client' . DS . 'new-user-welcome.twig';
            }

            if ($timestamp > $payload['exp']) {
                $this->template = 'client' . DS . 'validation-expired.twig';
            }
            
            
        }
    }
}