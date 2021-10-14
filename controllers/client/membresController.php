<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Libraries\Twig;
use Over_Code\Controllers\MainController;

/**
 * Manage resgistration and log-in / out
 */
class MembresController extends MainController
{
    public function inscriptionConnexion()
    {
        $this->template = 'client' . DS . 'signin-login.twig';
    }
}