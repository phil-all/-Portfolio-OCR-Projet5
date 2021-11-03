<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Libraries\Email;
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
    public function login(): void
    {
        $user = new UserModel();
        $logmail = $this->get_POST('logmail');
        $logpass = $this->get_POST('logpass');

        $auth = $user->auth($logmail, $logpass);

        $status = ($auth) ? $user->getStatus($logmail) : 'authentification-error';

        if ($status === 'active') {
            $user->store_ipLog($logmail);
            $user->hydrate($logmail, 'login', 900); // exp 15 min
            
            $this->set_COOKIE('token', $user->get_token());
            $this->set_COOKIE('token_obj', 'login');

            $this->redirect(SITE_ADRESS);
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
        $this->set_COOKIE('token', '');
        $this->set_COOKIE('token_obj', 'logout');
        
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

            $twigMail = new Twig;
            $mailTemplate = 'emails'. DS . 'validation-link.twig';
            $params = [
                'token' => $jwt->tokenToUri($token)
            ];

            $reciever = [$this->get_POST('email')];
            $title = 'Confirmation d\'inscription - [Ne pas répondre]';
            $body = $twigMail->getTwig()->render($mailTemplate, $params);

            Email::sendHtmlEmail($reciever, $title, $body);
            
            $user = new UserModel();
            $user->createUser($token, $date['date_time']);

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