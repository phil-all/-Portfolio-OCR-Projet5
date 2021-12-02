<?php

namespace Over_Code\Controllers\Admin;

use Over_Code\Models\UserModel;
use Over_Code\Controllers\MainController;

/**
 * Admin users controller
 */
class AdminUserController extends MainController
{
    /**
     * Set template for active and suspended users list
     *
     * @param array $params
     *
     * @return void
     */
    public function liste(array $params): void
    {
        $this->template = $this->template = 'admin' . DS . 'dashboard.twig';

        $paramsTest = count($params) === 1 && $params[0] === $this->getCOOKIE('CSRF');

        if ($this->userToTwig['admin'] && $paramsTest) {
            $this->params['valid'] = 'active';

            $this->listing('all');
        }
    }

    /**
     * set template for pending users list
     *
     * @param array $params
     *
     * @return void
     */
    public function pending(array $params): void
    {
        $this->template = $this->template = 'admin' . DS . 'dashboard.twig';

        $paramsTest = count($params) === 1 && $params[0] === $this->getCOOKIE('CSRF');

        if ($this->userToTwig['admin'] && $paramsTest) {
            $this->params['pending'] = 'active';

            $this->listing('pending');
        }
    }

    /**
     * Set listing users template
     *
     * @param string $type all for valid users and pending for pendig users
     *
     * @return void
     */
    public function listing(string $type): void
    {
        $user = new UserModel();

        $userList = ($type === 'all') ? $user->readValid() : $user->readPending();

        $this->params['users'] = $userList;

        $this->userToTwig['template'] = 'admin';

        $this->preventCsrf();

        $this->template = $this->template = 'admin' . DS . 'user-list.twig';
    }

    /**
     * Update user status to active
     *
     * @param array $params
     *
     * @return void
     */
    public function valid(array $params): void
    {
        $paramsTest = count($params) === 2 &&
            $this->onlyInteger($params[0]) &&
            $params[1] === $this->getCOOKIE('CSRF');

        if ($this->userToTwig['admin'] && $paramsTest) {
            $user = new UserModel();
            $user->statusUpdate((int)$params[0], 2);
        }

        $this->redirect(SITE_ADRESS . '/adminUser/liste/' . $this->getCOOKIE('CSRF'));
    }

    /**
     * Update user status to suspended
     *
     * @param array $params
     *
     * @return void
     */
    public function suspend(array $params): void
    {
        $paramsTest = count($params) === 2 &&
            $this->onlyInteger($params[0]) &&
            $params[1] === $this->getCOOKIE('CSRF');

        if ($this->userToTwig['admin'] && $paramsTest) {
            $user = new UserModel();
            $user->statusUpdate((int)$params[0], 3);
        }

        $this->redirect(SITE_ADRESS . '/adminUser/liste/' . $this->getCOOKIE('CSRF'));
    }

    /**
     * Delete user
     *
     * @param array $params
     *
     * @return void
     */
    public function delete(array $params): void
    {
        $paramsTest = count($params) === 2 &&
            $this->onlyInteger($params[0]) &&
            $params[1] === $this->getCOOKIE('CSRF');

        if ($this->userToTwig['admin'] && $paramsTest) {
            $user = new UserModel();
            $user->delete((int)$params[0]);
        }

        $this->redirect(SITE_ADRESS . '/adminUser/liste/' . $this->getCOOKIE('CSRF'));
    }
}
