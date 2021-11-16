<?php

namespace Over_Code\Controllers\Admin;

use Over_Code\Libraries\Jwt;
use Over_Code\Controllers\MainController;

/**
 * Manage admin pages
 */
class AdminController extends MainController
{
    use \Over_Code\Libraries\User\Tests;

    public function dashboard(array $params)
    {
        $this->template = 'pageNotFound.twig';

        $jwt = new Jwt();
        
        $token = $jwt->uriToToken($params);
        
        if ($jwt->isSignatureCorrect($token)) {
            $payload = $jwt->decodeDatas($token, 1);

            if ($this->isAdmin($payload['email'])) {
                $this->template = 'admin' . DS . 'dashboard.twig';
            }
        }


                // 4 infos: nombre de commentaires à valider, nombre de membres incrits en attente, actifs et suspendus.
                // 5 item crud articles (sous menus): new, parcourrir les articles (pour read, update ou delete)
                // 6 item crud catégories
                // 7 stats articles lus, top 3 des articles likés, et consultation cv (http://www.finalclap.com/faq/71-php-compteur-visites-audience)
                // bouton retour interface membre
                // bouton déconnexion
    }
}