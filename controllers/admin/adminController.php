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

    /**
     * Set template for admin dashboard
     *
     * @param array $params uri parameters after .../dashboard/
     * 
     * @return void
     */
    public function dashboard(array $params)
    {
        $this->template = 'pageNotFound.twig';

        $jwt = new Jwt();

        $token = $jwt->uriToToken($params);
        $payload = $jwt->decodeDatas($token, 1);
        
        if ($jwt->isSignatureCorrect($token) &&
            $jwt->isNotExpired($payload) &&
            $this->isAdmin($payload['email'])) {

                $this->template = 'admin' . DS . 'dashboard.twig';
        }
    }
}