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
}
