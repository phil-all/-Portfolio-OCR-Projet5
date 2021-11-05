<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Libraries\Jwt;
use Over_Code\Libraries\Twig;
use Over_Code\Libraries\Email;
use Over_Code\Models\UserModel;
use Over_Code\Controllers\UserController;

/**
 * Manage user access: resgistration, log-in and log-out
 */
class MembresController extends UserController
{
    use \Over_Code\Libraries\Helpers;
    use \Over_Code\Libraries\User\Register;
    use \Over_Code\Libraries\User\ResetPassword;

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
            $jwt = new Jwt();
            $token = $jwt->generateToken('registration', $this->get_POST('email'), 900); // 900s = 15 min

            $twigMail = new Twig;
            $mailTemplate = 'emails'. DS . 'validation-link.twig';
            $params = [
                'token' => $jwt->tokenToUri($token)
            ];

            $mail = new Email;
            $mail->sendHtmlEmail(
                [$this->get_POST('email')],
                'Confirmation d\'inscription - [Ne pas répondre]',
                $twigMail->getTwig()->render($mailTemplate, $params)
            );
            
            $user = new UserModel();
            $user->createUser($token, date('Y-m-d H:i:s'));

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

            $email = $payload['email'];
                
            if ($this->isPending($email) && (time() < $payload['exp'])) {
                $this->accountValidation($email);
                $this->template = 'client' . DS . 'new-user-welcome.twig';
            }

            if (time() > $payload['exp']) {
                $this->template = 'client' . DS . 'validation-expired.twig';
            }
        }
    }
    
    /**
     * Sets twig template with forgotten-password.twig to display page to
     * give email address to send reset password link enquiry
     *
     * @return void
     */
    public function forgottenPassword(): void
    {
        $this->template = 'client' . DS . 'pass/forgotten-password.twig';
    }

    /**
     * Send an email to reset password to email address in POST,
     * and sets twig template with reset-password-enquiry-sent.twig
     * 
     * @return void
     */
    public function resetPasswordEnquiry(): void
    {
        $jwt = new Jwt();
        $token = $jwt->generateToken('reset password enquiry', $this->get_POST('email'), 900); // 900s = 15 min

        $twigMail = new Twig;
        $mailTemplate = 'emails'. DS . 'reset-password-enquiry.twig';
        $params = [
            'token' => $jwt->tokenToUri($token)
        ];

        $mail = new Email;
        $mail->sendHtmlEmail(
            [$this->get_POST('email')],
            'Récupération de compte - [Ne pas répondre]',
            $twigMail->getTwig()->render($mailTemplate, $params)
        );

        $this->template = 'client' . DS . 'pass/reset-password-enquiry-sent.twig';
    }

    /**
     * Sets twig template with reset-password.twig
     *
     * @param array $params uri friendly token header/payload/signature
     * @return void
     */
    public function resetPassword(array $params): void
    {
        $this->template = 'client' . DS . 'invalid-validation-link.twig';

        $jwt = new Jwt();      
        $token = $jwt->uriToToken($params);

        if ($jwt->isJWT($token) && $jwt->isSignatureCorrect($token)) {
            $payload = $jwt->decode_data($token, 1);

            $email = $payload['email'];
                
            if ((time() < $payload['exp'])) {                
                $jwt = new Jwt();
                $token = $jwt->generateToken('reset password', $email, 900); // 900s = 15 min

                $this->params = array(
                    'email' => $email,
                    'tokenToUri' => $jwt->tokenToUri($token)
                );
                $this->template = 'client' . DS . 'pass/reset-password.twig';
            }

            if (time() > $payload['exp']) {
                $this->template = 'client' . DS . 'validation-expired.twig';
            }
        }
    }

    public function updatePassword(array $params):void
    {
        $this->template = 'client' . DS . 'invalid-validation-link.twig';

        if ($this->newPass_form_test()) {
            $jwt = new Jwt();
            $token = $jwt->uriToToken($params);

            if ($jwt->isJWT($token) && $jwt->isSignatureCorrect($token)) {
                $payload = $jwt->decode_data($token, 1);

                $email = $payload['email'];
                    
                if ((time() < $payload['exp'])) {
                    $this->newPassValidation($email);
                    $this->template = 'client' . DS . 'pass/reset-password-confirmation.twig';
                }

                if (time() > $payload['exp']) {
                    $this->template = 'client' . DS . 'validation-expired.twig';
                }
            }
        }
    }
}