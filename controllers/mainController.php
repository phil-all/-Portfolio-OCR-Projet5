<?php

namespace Over_Code\Controllers;

use Over_Code\Libraries\Jwt;
use Over_Code\Libraries\Twig;
use Over_Code\Models\UserModel;
use Over_Code\Libraries\Routes\UrlParser;

/**
 * Generals methods for specialised controllers
 */
abstract class MainController
{
    use \Over_Code\Libraries\Helpers;
    use \Over_Code\Libraries\User\Tests;

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
        $jwt = new Jwt();
        $token = '';
        $userToTwig = [];

        if (!empty($this->getCOOKIE('token'))) {
            $token =  $this->getCOOKIE('token');
        }

        if ($jwt->isJWT($token) && $jwt->isSignatureCorrect($token)) {
            $user = new UserModel();

            $payload = $jwt->decodeDatas($token, 1);
            $ipLog = $user->readIpLog($payload['email']);
            $remoteIp = $this->getSERVER('REMOTE_ADDR');

            if ($jwt->isNotExpired($payload) && ($ipLog === $remoteIp)) {
                $user->hydrate('renewal', $payload['email'], 900); // exp 15 min

                $this->setCOOKIE('token', $user->getToken());
                $this->setCOOKIE('token_obj', 'renewal');

                $this->uri = new UrlParser();

                $userToTwig = array(
                    'user'     => $user->userInArray($payload['email']),
                    'admin'    => $this->isAdmin($payload['email']),
                    'section'  => $this->uri->getControllerClass(),
                    'uriToken' => $jwt->tokenToUri($token)
                );
            }
        }

        $this->$action($params);

        $this->twig = new Twig();

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
