<?php

namespace Over_Code\Controllers\Client;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
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
        $model = new UserModel();

        $auth = $model->auth();

        $status = 'authentification-error';
        
        if ($auth) {
            $status = $model->getStatus();

            if ($status === 'active') {            
                $model->hydrate();
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
        $model = new userModel();

        $model->set_tokenNull();
        
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
        $model = new UserModel();

        if ($model->registration_test()) {
            $model->createUser();

            $twigMail = new Twig;
            $params = [];
            $mailTemplate = 'emails'. DS . 'validation-link.twig';
            $title = 'Confirmation d\'inscription - [Ne pas répondre]';
            
            // Create the Transport
            $transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 2525))
                ->setUsername('3933404713c11d')
                ->setPassword('f3f1b649535189');

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
        }  else {
            $this->template = 'client' . DS . 'registration-failed.twig';
        }
    }

    public function validation()
    {
        // on récupère le mail et le token en url
        // on vérifie en base de données

        // si mail et token ok, mail date token périmée
        // page votre lien de validation est périmé

        // si mail token et date ok
        // connexion puis page bienvenue avec lien vers l'accueil

        // si mail et ou token non valides
        // page désolé, le lien que vous avez cliqué est corrompu
        // nous ne pouvons faire suite à votre demande
    }
}