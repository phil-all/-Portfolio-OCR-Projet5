<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Libraries\Jwt;
use Over_Code\Libraries\Twig;
use Over_Code\Libraries\Email;
use Over_Code\Models\UserModel;
use Over_Code\Libraries\FormTest;
use Over_Code\Controllers\UserController;

/**
 * Manage user access: resgistration, log-in and log-out
 */
class MembresController extends UserController
{
    use \Over_Code\Libraries\Helpers;
    use \Over_Code\Libraries\User\Process\Register;
    use \Over_Code\Libraries\User\Process\ResetPassword;

    /**
     * Sets params and template to twig, regiser/login page
     *
     * @return void
     */
    public function inscriptionConnexion(): void
    {
        $this->params['register'] = true;
        
        $this->template = 'client' . DS . 'signin-login.twig';
    }

    /**
     * Login an user, and sets template to twig
     *
     * @return void
     */
    public function login(): void
    {
        $user    = new UserModel();
        $logmail = $this->getPOST('logmail');
        $logpass = $this->getPOST('logpass');
        $auth    = $user->auth($logmail, $logpass);

        $status = ($auth) ? $user->getStatus($logmail) : 'authentification-error';

        if ($status === 'active') {
            $user->updateIpLog($logmail);
            $user->hydrate('login', $logmail, 900); // exp 15 min
         
            $this->setCOOKIE('token', $user->getToken());
            $this->setCOOKIE('token_obj', 'login');

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
        $this->setCOOKIE('token', '');
        $this->setCOOKIE('token_obj', 'logout');
        
        $this->redirect(SITE_ADRESS);
    }

    /**
     * Create a user in database, with pending status and send him activation mail,
     * if registrations_test return true.
     *
     * @return void
     */
    public function register(): void
    {
        $this->template = 'client' . DS . 'registration-failed.twig';

        $form = new FormTest();

        if ($form->registerTest()) {
            $jwt = new Jwt();
            
            $token = $jwt->generateToken('registration', $this->getPOST('email'), 900); // 900s = 15 min

            $twigMail = new Twig();

            $mailTemplate = 'emails' . DS . 'validation-link.twig';

            $params = [
                'token' => $jwt->tokenToUri($token)
            ];

            $mail = new Email();
            $mail->sendHtmlEmail(
                $this->getPOST('email'),
                'Confirmation d\'inscription - [Ne pas répondre]',
                $twigMail->getTwig()->render($mailTemplate, $params)
            );
            
            $user = new UserModel();
            $user->createUser($token, date('Y-m-d H:i:s'));

            $this->template = 'client' . DS . 'validation-link-sent.twig';
        }
    }

    /**
     * Validates an user registration
     *
     * @param array $params uri friendly JWT token header/payload/signature
     *
     * @return void
     */
    public function validation(array $params): void
    {
        $this->template = 'client' . DS . 'invalid-validation-link.twig';

        $jwt   = new Jwt();
        $token = $jwt->uriToToken($params);

        if ($jwt->isJWT($token) && $jwt->isSignatureCorrect($token)) {
            $payload = $jwt->decodeDatas($token, 1);

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
        $jwt   = new Jwt();
        $token = $jwt->generateToken('reset password enquiry', $this->getPOST('email'), 900); // 900s = 15 min

        $twigMail = new Twig();

        $mailTemplate = 'emails' . DS . 'reset-password-enquiry.twig';

        $params = [
            'token' => $jwt->tokenToUri($token)
        ];

        $mail = new Email();
        $mail->sendHtmlEmail(
            $this->getPOST('email'),
            'Récupération de compte - [Ne pas répondre]',
            $twigMail->getTwig()->render($mailTemplate, $params)
        );

        $this->template = 'client' . DS . 'pass/reset-password-enquiry-sent.twig';
    }

    /**
     * Sets twig template with reset-password.twig
     *
     * @param array $params uri friendly JWT token header/payload/signature, given in URL
     *
     * @return void
     */
    public function resetPassword(array $params): void
    {
        $this->template = 'client' . DS . 'invalid-validation-link.twig';

        $jwt = new Jwt();
        $token = $jwt->uriToToken($params);

        if ($jwt->isJWT($token) && $jwt->isSignatureCorrect($token)) {
            $payload = $jwt->decodeDatas($token, 1);

            $email = $payload['email'];
                
            if ((time() < $payload['exp'])) {
                $jwt   = new Jwt();
                $token = $jwt->generateToken('reset password', $email, 900); // 900s = 15 min

                $this->params = array(
                    'email'      => $email,
                    'tokenToUri' => $jwt->tokenToUri($token)
                );
                
                $this->template = 'client' . DS . 'pass/reset-password.twig';
            }

            if (time() > $payload['exp']) {
                $this->template = 'client' . DS . 'validation-expired.twig';
            }
        }
    }

    /**
     * Updates user password.
     * Use in forgotten password process
     *
     * @param array $params uri friendly JWT token header/payload/signature
     *
     * @return void
     */
    public function updatePassword(array $params): void
    {
        $this->template = 'client' . DS . 'invalid-validation-link.twig';

        $form = new FormTest();

        if ($form->newPassTest()) {
            $jwt = new Jwt();
            $token = $jwt->uriToToken($params);

            if ($jwt->isJWT($token) && $jwt->isSignatureCorrect($token)) {
                $payload = $jwt->decodeDatas($token, 1);

                $this->updatePassProcess($payload['exp'], $payload['email']);
            }
        }
    }

    /**
     * Update user password and set template.
     *
     * @param string $expiration
     * @param string $email
     * @return void
     */
    private function updatePassProcess(string $expiration, string $email): void
    {
        if (time() < $expiration) {
            $this->newPassValidation($email);
            $this->template = 'client' . DS . 'pass/reset-password-confirmation.twig';
        }

        if (time() > $expiration) {
            $this->template = 'client' . DS . 'validation-expired.twig';
        }
    }
}
