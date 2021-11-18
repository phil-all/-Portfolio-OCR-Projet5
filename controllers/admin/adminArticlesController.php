<?php

namespace Over_Code\Controllers\Admin;

use Over_Code\Models\ArticlesModel;
use Over_Code\Models\CategoryModel;
use Over_Code\Controllers\MainController;

/**
 * Admin articles controller, used to display:
 * - new article template uri: SITE_ADRESS/adminArticle/nouveau/
 * - update article uri: SITE_ADRESS/adminArticle/update/-serial-
 * - delete article uri: SITE_ADRESS/adminArticle/delete/-serial
 * - list of all articles uri: SITE_ADRESS/adminArticle/liste/all
 * - list of articles by category uri: SITE_ADRESS/adminArticle/liste/all
 */
class AdminArticlesController extends MainController
{
    use \Over_Code\Libraries\Upload;

    public function nouveau()
    {
        if ($this->userToTwig['admin']) {
            $category = new CategoryModel();
            $categories = $category->readAll();

            $this->params = [
                'categories' => $categories
            ];

            $this->userToTwig['template'] = 'admin';
            
            $this->template = $this->template = 'admin' . DS . 'new-article.twig';
        }
    }

    public function post()
    {
        if ($this->userToTwig['admin']) {
            $upload = $this->uploadArticleImg();

            if (!is_null($upload['img_name'])) {
                $user = $this->userToTwig['user']['serial'];

                $img = $upload['img_name'];

                $article = new articlesModel();
                $article->createArticle($user, $img);

                $this->userToTwig['template'] = 'admin';

                $this->template = 'admin' . DS . 'post-confirmation.twig';
            }
        }
    }
}
