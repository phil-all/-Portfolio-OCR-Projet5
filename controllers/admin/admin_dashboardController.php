<?php

namespace Over_Code\Controllers\Admin;

use Over_Code\Libraries\Jwt;
use Over_Code\Controllers\MainController;

/**
 * Admin dashboard controller
 */
class admin_dashboardController extends MainController
{
    use \Over_Code\Libraries\User\Tests;

    /**
     * Set template for admin dashboard
     *
     * @param array $params uri parameters after .../dashboard/
     * 
     * @return void
     */
    public function index(): void
    {
        if ($this->userToTwig['admin']) {
            $this->template = $this->template = 'admin' . DS . 'dashboard.twig';
        }
    }
}