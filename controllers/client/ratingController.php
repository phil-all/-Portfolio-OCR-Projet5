<?php

namespace Over_Code\Controllers\client;

use Over_Code\Libraries\Email;
use Over_Code\Libraries\FormTest;
use Over_Code\Controllers\MainController;
use Over_Code\Models\RatingModel;

/**
 * Manage rating process (as a like)
 */
class RatingController extends MainController
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Switch on add or unlike methods from rating model, and redirect to
     * the article rating section
     *
     * @return void
     */
    public function index(): void
    {

        if (array_key_exists('user', $this->userToTwig)) {
            $rating = new RatingModel();

            $user = (int)$this->userToTwig['user']['serial'];
            $articleId = preg_replace('~[a-zA-Z\/\:\_\-]~', '', $this->getSERVER('HTTP_REFERER'));

            if ($rating->isUserRate($user, $articleId)) {
                $rating->unLike($user, $articleId);
                $this->redirect($this->getSERVER('HTTP_REFERER') . '#rating');
            }

            if (!$rating->isUserRate($user, $articleId)) {
                $rating->addLike($user, $articleId);
                $this->redirect($this->getSERVER('HTTP_REFERER') . '#rating');
            }

            $this->redirect($this->getSERVER('HTTP_REFERER') . '#rating');
        }
    }
}
