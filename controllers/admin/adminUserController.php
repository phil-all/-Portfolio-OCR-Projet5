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
     * @return void
     */
    public function index(): void
    {
        $this->params = [
            'valid' => 'active'
        ];

        $this->listing('all');
    }

    /**
     * set template for pending users list
     */
    public function pending(): void
    {
        $this->params = [
            'pending' => 'active'
        ];

        $this->listing('pending');
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
        $this->template = 'client' . DS . 'accueil.twig';
        
        if ($this->userToTwig['admin']) {
            $user = new UserModel();

            $userList = ($type === 'all') ? $user->readValid() : $user->readPending();

            $addParams = [
                'users'  => $userList
            ];

            $this->params = array_merge($this->params, $addParams);

            $this->userToTwig['template'] = 'admin';

            $this->template = $this->template = 'admin' . DS . 'user-list.twig';
        }
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
        if ($this->userToTwig['admin'] && count($params) === 1 && $this->onlyInteger($params[0])) {
            $user = new UserModel();
            $user->statusUpdate((int)$params[0], 2);
        }

        $this->redirect(SITE_ADRESS . '/adminUser');
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
        if ($this->userToTwig['admin'] && count($params) === 1 && $this->onlyInteger($params[0])) {
            $user = new UserModel();
            $user->statusUpdate((int)$params[0], 3);
        }

        $this->redirect(SITE_ADRESS . '/adminUser');
    }

    public function delete(array $params): void
    {
        if ($this->userToTwig['admin'] && count($params) === 1 && $this->onlyInteger($params[0])) {
            $user = new UserModel();
            $user->delete((int)$params[0]);
        }

        $this->redirect(SITE_ADRESS . '/adminUser');
    }
}
