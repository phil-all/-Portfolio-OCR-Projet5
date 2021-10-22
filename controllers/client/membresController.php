<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Libraries\Twig;
use Over_Code\Controllers\UserController;
use Over_Code\Models\UserModel;
use Over_Code\Config\User;

/**
 * Manage resgistration, log-in and log-out
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

        if (!$auth) {
            $status = 'authentification-error';
        } else {
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

    public function register()
    {
        $model = new UserModel();

        if ($model->registration_test()) {
            $model->createUser();
            echo 'create user'; die;
        }  else {
            echo 'validation ne marche pas';die;
        } 
        
        // on envoie un mail de validation avec un lien contenant le token et l'adresse mail
        // page vous avec reçu un mail de validation, confirmez dans les 20 minutes

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