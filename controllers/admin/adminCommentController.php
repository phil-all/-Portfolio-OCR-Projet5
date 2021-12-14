<?php

namespace Over_Code\Controllers\Admin;

use Over_Code\Models\CommentModel;
use Over_Code\Controllers\MainController;

/**
 * Admin comment controller
 */
class AdminCommentController extends MainController
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Set Template for pending comments list
     *
     * @param array $params
     *
     * @return void
     */
    public function liste(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $paramsTest = count($params) === 1 && $params[0] === $this->getCOOKIE('CSRF');

        if ($this->userToTwig['admin'] && $paramsTest) {
            $comment     = new CommentModel();
            $commentList = $comment->readPending();

            $articlesJoinPending = $comment->getPendingJoinArticles();

            $this->params = [
                'empty' => true
            ];

            if (!empty($commentList)) {
                $this->params = [
                    'list'     => $commentList,
                    'articles' => $articlesJoinPending
                ];
            }

            $this->userToTwig['template'] = 'admin';

            $this->preventCsrf();

            $this->template = $this->template = 'admin' . DS . 'comment-validation.twig';
        }
    }

    /**
     * Set a comment status as follow and redicrect to admin comment list section.
     *
     * Status are as follow :
     * - 0 = pending
     * - 1 = validated
     * - 2 = suspended.
     *
     * @param array $uriParams
     * @param integer $newStatusId
     *
     * @return void
     */
    public function updateStatus(array $uriParams, int $newStatusId): void
    {
        $testParams = count($uriParams) === 2 && $uriParams[1] === $this->getCOOKIE('CSRF');

        if ($this->userToTwig['admin'] && $testParams) {
            $comment = new CommentModel();
            $comment->statusUpdate((int)$uriParams[0], $newStatusId);
        }

        $this->redirect(SITE_ADRESS . '/adminComment/liste/' . $this->getCOOKIE('CSRF'));
    }

    /**
     * Comment validation process
     *
     * @param array $params uri params
     *
     * @return void
     */
    public function valid(array $params): void
    {
        $this->updateStatus($params, 2);
    }

    /**
     * Suspend comment process
     *
     * @param array $params uri params
     *
     * @return void
     */
    public function suspend(array $params): void
    {
        $this->updateStatus($params, 3);
    }
}
