<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Libraries\Twig;
use Over_Code\Controllers\UserController;
use Over_Code\Models\UserModel;
use Over_Code\Config\User;

/**
 * Manage resgistration and log-in / out
 */
class MembresController extends UserController
{
    use \Over_Code\Libraries\Helpers;

    public function inscriptionConnexion()
    {
        $this->template = 'client' . DS . 'signin-login.twig';
    }

    /**
     * login an user and redirect to home page
     *
     * @return void
     */
    public function login()
    {
        $model = new UserModel;

        $this->logmail = $this->POST('logmail');
        $this->logpass = $this->POST('logpass');

        $auth = $model->auth($this->logmail, $this->logpass);

        if ($auth) {            

            $user = new User($model->auth($this->logmail, $this->logpass));

            $this->redirect(SITE_ADRESS);

        } else {
            //$this->redirect(SITE_ADRESS . 'membres/inscription-connexion');
            $this->template = 'client' . DS . 'authentification-error.twig';
        }
    }

    public function deconnexion()
    {
        session_unset();
        session_destroy();

        $this->redirect(SITE_ADRESS);
    }
}