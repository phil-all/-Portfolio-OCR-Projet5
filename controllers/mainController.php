<?php

namespace Over_Code\Controllers;

use Over_Code\Libraries\Jwt;
use Over_Code\Libraries\Csrf;
use Over_Code\Libraries\Twig;
use Over_Code\Models\UserModel;
use Over_Code\Libraries\Routes\UrlParser;
use Twig\Template;

/**
 * Generals methods for specialised controllers
 */
abstract class MainController
{
    use \Over_Code\Libraries\Helpers;
    use \Over_Code\Libraries\User\Tests;

    /**
     * Controller method called
     *
     * @var string $action
     */
    protected $action;

    /**
     * Parameters for template
     *
     * @var array $params
     */
    protected $params = [];

    /**
     * Template rendering object
     *
     * @var object $twig
     */
    protected $twig;

    /**
     * Twig template file
     *
     * @var string $template
     */
    protected $template = 'pageNotFound.twig';

    /**
     * Json Web Token manager
     *
     * @var object $jwt
     */
    protected $jwt;

    /**
     * User
     *
     * @var object $user
     */
    protected $user;

    /**
     * JWT token
     *
     * @var string $token
     */
    protected $token;

    /**
     * JWT token payload
     *
     * @var array $payloaod
     */
    protected $payload;

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
        $this->uriParams = $params;

        $this->userToTwig['admin'] = false;

        $this->getToken();

        $this->jwt = new Jwt();

        $this->user = new UserModel();

        $this->setPayload();

        if ($this->validator()) {
            $this->user->hydrate('renewal', $this->payload['email'], 900); // exp 15 min

            $this->setCOOKIE('token', $this->user->getToken());
            $this->setCOOKIE('token_obj', 'renewal');

            $this->uri = new UrlParser();

            $this->userToTwig = array(
                'user'     => $this->user->userInArray($this->payload['email']),
                'admin'    => $this->isAdmin($this->payload['email']),
                'uriToken' => $this->jwt->tokenToUri($this->token)
            );
        }

        $this->$action($params);

        $this->twig = new Twig();

        $this->params = array_merge($this->userToTwig, $this->params);

        $this->twig->twigRender($this->template, $this->params);
    }

    /**
     * Gets cookie token and set token attribute.
     *
     * @return void
     */
    protected function getToken(): void
    {
        $this->token = (empty($this->getCOOKIE('token'))) ? 'empty.token' : $this->getCOOKIE('token');
    }

    /**
     * Set template to pageNotFound if method sent in constructor is not found.
     */
    public function methodNotFound()
    {
        $this->template = 'pageNotFound.twig';
    }

    /**
     * Sets a CSRF token and put it in CSRF twig param to be added to specific links.
     *
     * @return void
     */
    protected function preventCsrf(): void
    {
        $csrf = new Csrf();

        $this->params['CSRF'] = $csrf->get();
    }

    /**
     * Checks token, user IP and uri paramters.
     *
     * @return boolean
     */
    protected function validator(): bool
    {
        return $this->tokenTest() && $this->ipTest();
    }

    /**
     * Checks token validity and expiration.
     *
     * @return boolean
     */
    protected function tokenTest(): bool
    {
        return $this->isTokenCorrect() &&
            $this->jwt->isNotExpired($this->payload);
    }

    /**
     * Checks token validity.
     *
     * @return boolean
     */
    protected function isTokenCorrect(): bool
    {
        return $this->jwt->isJWT($this->token) &&
            $this->jwt->isSignatureCorrect($this->token);
    }

    /**
     * Checks user IP.
     *
     * @return boolean
     */
    protected function ipTest(): bool
    {
        $ipLog    = $this->user->readIpLog($this->payload['email']);
        $remoteIp = $this->getSERVER('REMOTE_ADDR');

        return $ipLog === $remoteIp;
    }

    /**
     * Sets payload attribute.
     *
     * @return void
     */
    protected function setPayload()
    {
        $this->payload = ($this->isTokenCorrect()) ? $this->jwt->decodeDatas($this->token, 1) : null;
    }
}
