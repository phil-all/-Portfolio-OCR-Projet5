<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Libraries\Jwt;
use Over_Code\Models\UserModel;
use Over_Code\Models\CommentModel;
use Over_Code\Controllers\MainController;

/**
 * Manage comment form
 */
class CommentController extends MainController
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Json Web Token manager
     *
     * @var object $jwt
     */
    private $jwt;

    /**
     * User
     *
     * @var object $user
     */
    private $user;

    /**
     * JWT token
     *
     * @var string $token
     */
    private $token;

    /**
     * JWT token payload
     *
     * @var array $payloaod
     */
    private $payload;

    /**
     * Uri parameters
     *
     * @var array
     */
    private $uriParams;

    /**
     * Create an article comment from POST.
     *
     * @param array $params
     *
     * @return void
     */
    public function post(array $params): void
    {
        $this->uriParams = $params;

        $this->getToken();

        $this->jwt = new Jwt();

        $this->user = new UserModel();

        $this->payload = $this->jwt->decodeDatas($this->token, 1);

        if ($this->validator()) {
            $articleParam = $this->getArticleParams();

            $this->user->hydrate('renewal', $this->payload['email'], 900);
    
            $this->setCOOKIE('token', $this->user->getToken());
            $this->setCOOKIE('token_obj', 'renewal');
    
            $content = $this->getPOST('comment');
            $serial  = $this->user->getSerial();
            $article = explode('/', $articleParam)[0];
    
            $comment = new commentModel();
            $comment->create($content, $serial, $article);
    
            $this->redirect(SINGLE_ARTICLE . $articleParam . "/#comment");
        }
        
        $this->redirect(SITE_ADRESS);
    }

    /**
     * Gets cookie token end set token attribute.
     *
     * @return void
     */
    private function getToken(): void
    {
        $this->token = (empty($this->getCOOKIE('token'))) ? '' : $this->getCOOKIE('token');
    }

    /**
     * Checks uri parameters.
     *
     * @return boolean
     */
    private function isUriValid(): bool
    {
        return count($this->uriParams) === 1 &&
            $this->uriParams[0] === $this->getCOOKIE('CSRF');
    }

    /**
     * Gets parameters regarding article from the HTTP referer uri.
     *
     * @return string
     */
    private function getArticleParams(): string
    {
        return preg_replace(
            '~' . SINGLE_ARTICLE . '~',
            '',
            $this->getSERVER('HTTP_REFERER')
        );
    }

    /**
     * Checks token and IP adress.
     *
     * @return boolean
     */
    private function validator(): bool
    {
        return $this->tokenTest() && $this->ipTest() && $this->isUriValid();
    }
    
    /**
     * Checks if token is valid.
     *
     * @return boolean
     */
    private function tokenTest(): bool
    {
        return $this->jwt->isJWT($this->token) &&
            $this->jwt->isSignatureCorrect($this->token) &&
            $this->jwt->isNotExpired($this->payload);
    }

    /**
     * Verifies if user IP correspond to token stored IP.
     *
     * @return boolean
     */
    private function ipTest(): bool
    {
        $ipLog    = $this->user->readIpLog($this->payload['email']);
        $remoteIp = $this->getSERVER('REMOTE_ADDR');

        return $ipLog === $remoteIp;
    }
}
