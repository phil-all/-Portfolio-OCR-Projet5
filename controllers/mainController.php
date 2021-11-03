<?php

namespace Over_Code\Controllers;

use Over_Code\Libraries\Jwt;
use Over_Code\Libraries\Twig;
use Over_Code\Models\UserModel;

/**
 * Generals methods for specialised controllers
 */
abstract class MainController
{
    use \Over_Code\Libraries\Helpers;

    protected string $action;
    protected array $params = [];
    protected object $twig;

    /**
     * Call method action, passing parameters, and send it to the display method
     *
     * @param string $action
     * @param array $params
     * 
     * @return void
     */
    public function __construct(string $action, array $params = [])
    {
        $jwt = new Jwt;
        $token = '';
        $userToTwig = [];

        if (!empty($this->get_COOKIE('token'))) {
            $token =  $this->get_COOKIE('token');
        }

        if ($jwt->isJWT($token) && $jwt->isSignatureCorrect($token)) {
            $user = new UserModel;

            $payload = $jwt->decode_data($token, 1);
            $ipLog = $user->read_ipLog($payload['email']);
            $remoteIp = $this->get_SERVER('REMOTE_ADDR');

            if ($jwt->isNotExpired($payload) && ($ipLog === $remoteIp)) {
                $user->hydrate($payload['email'], 'renewal', 900); // exp 15 min

                $this->set_COOKIE('token', $user->get_token());
                $this->set_COOKIE('token_obj', 'renewal');

                $userToTwig = array(
                    'user' => $user->userInArray($payload['email'])
                );
            }
        }

        $this->$action($params);

        $this->twig = new Twig;

        $this->params = array_merge($userToTwig, $this->params);

        $this->twig->twigRender($this->template, $this->params);
    }

    /**
     * Set template to pageNotFound if method sent in constructor is not fpound
     */
    public function methodNotFound()
    {
        $this->template = 'pageNotFound.twig';
    }
}