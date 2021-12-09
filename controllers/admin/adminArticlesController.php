<?php

namespace Over_Code\Controllers\Admin;

use Over_Code\Models\CommentModel;
use Over_Code\Models\ArticlesModel;
use Over_Code\Models\CategoryModel;
use Over_Code\Controllers\MainController;

/**
 * Admin articles controller
 */
class AdminArticlesController extends MainController
{
    use \Over_Code\Libraries\Upload;

    private array $articles;
    private int $perPage = 4;
    private int $totalPages;
    private int $currentPage;

    /**
     * Sets params and template to twig, about create new article
     *
     * @param array $params
     *
     * @return void
     */
    public function nouveau(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $paramsTest = count($params) === 1 && $params[0] === $this->getCOOKIE('CSRF');
        
        if ($this->userToTwig['admin'] && $paramsTest) {
            $category   = new CategoryModel();
            $categories = $category->readAll();

            $this->params['categories'] = $categories;

            $this->userToTwig['template'] = 'admin';

            $this->preventCsrf();

            $this->template = $this->template = 'admin' . DS . 'new-article.twig';
        }
    }

    /**
     * Processes article creation from form post
     *
     * @param array $params
     *
     * @return void
     */
    public function post(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $paramsTest = count($params) === 1 && $params[0] === $this->getCOOKIE('CSRF');

        if ($this->userToTwig['admin'] && $paramsTest) {
            $upload = $this->uploadArticleImg();

            $img = '0000'; // default article image

            if ($upload['message'] === 0) { // 3: no file exist
                $img = $upload['img_name'];
            }
            
            $user = $this->userToTwig['user']['serial'];

            $article = new articlesModel();
            $article->createArticle($user, $img);

            $this->userToTwig['template'] = 'admin';

            $this->preventCsrf();

            $this->template = 'admin' . DS . 'article-post-confirmation.twig';
        }
    }

    /**
     * Sets params and template to twig, about list of articles.
     * 
     * - all articles, uri : .../adminArticles/liste/tous/page..
     * - articles from a category, uri : .../adminArticles/liste/category_name/page..
     *
     * @param array $params category or all, and page number, given in URL
     *
     * @return void
     */
    public function liste(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        if ($this->isListeAccessible($params)) {
            $this->userToTwig['template'] = 'admin';

            $model = new ArticlesModel();
        
            $this->currentPage = (int)(explode('-', $params[1]))[1];
            $this->totalPosts  = $model->getCount($params[0]);
            $this->totalPages  = ceil($this->totalPosts / $this->perPage);
    
            if ($this->paginationTest($params)) {
                $this->articles = $model->getArticlesList($this->currentPage, $this->perPage, $params[0]);
                $this->template = 'admin' . DS . 'articles.twig';

                $this->setCategoryTemplate($model, $params[0]);            
                $this->slugify();
                $this->preventCsrf();
    
                $this->params = array_merge($this->params, array(
                    'page'       => $this->currentPage,
                    'totalPages' => $this->totalPages,
                    'articles'   => $this->articles,
                    'statePrev'  => ($this->currentPage === 1) ? ' disabled' : '',
                    'stateNext'  => ($this->currentPage === $this->totalPages) ? ' disabled' : '',
                    'prev'       => ($this->currentPage === 1) ? 1 : $this->currentPage - 1,
                    'next' => ($this->currentPage === $this->totalPages) ? $this->totalPages : $this->currentPage + 1
                ));
            }
        }
    }

    /**
     * Create a title slug key in article list array with slug of article title
     *
     * @return void
     */
    private function slugify(): void
    {
        foreach ($this->articles as $article) {
            $article['slug'] = $this->toSlug($article['title']);

            array_shift($this->articles);

            array_push($this->articles, $article);
        }
    }

    /**
     * Checks if uri params are valids and user is admin.
     *
     * @param array $params uri parameters
     *
     * @return boolean
     */
    private function isListeAccessible(array $params): bool
    {
        return count($params) === 3 &&
            $params[2] === $this->getCOOKIE('CSRF') &&
            $this->userToTwig['admin'];
    }

    /**
     * Checks if pagination parameters are corrects.
     *
     * @param array $params uri parameters
     *
     * @return boolean
     */
    private function paginationTest(array $params): bool
    {
        return $this->currentPage <= $this->totalPages && explode('-', $params[1])[0] === 'page';
    }

    /**
     * Defines twig template file and a category param for a given category, if category exists
     *
     * @param ArticlesModel $model
     * @param string $param
     *
     * @return void
     */
    private function setCategoryTemplate(ArticlesModel $model, string $param): void
    {
        if ($model->categoryExist($param)) {
            $this->template = 'admin' . DS . 'articles-by-category.twig';

            $this->params['category'] = $param;
        }
    }

    /**
     * Sets params and template to twig, about single article page
     *
     * @param array $params
     *
     * @return void
     */
    public function numero(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';        
        
        $article = new ArticlesModel();

        if ($this->isNumeroAccessible($params, $article)) {
            $this->userToTwig['template'] = 'admin';
            
            $this->slugRedirection($params, $this->toSlug($article->getTitle((int)$params[0])));

            $this->template = 'admin' . DS . 'single-article.twig';
            $this->params   = $article->getSingleArticle($params[0]);

            $this->displayComments($params);

            $this->preventCsrf();
        }
    }

    /**
     * Set comments to display if exists
     *
     * @param array $params uri parameters
     *
     * @return void
     */
    private function displayComments(array $params): void
    {
        $comment = new CommentModel();

        if (!empty($comment->readValidated($params[0]))) {
            $this->params['comments'] =  $comment->readValidated($params[0]);
        }
    }

    /**
     * Redirect with on correct uri if uri slug incorrect.
     *
     * @param array $uriParams
     *
     * @param string correctSlug
     *
     * @return void
     */
    private function slugRedirection(array $uriParams, string $correctSlug): void
    {
        if ($correctSlug != $uriParams[1]) {
            $url = SITE_ADRESS . DS . 'articles' . DS . 'numero' . DS . $uriParams[0] . DS . $correctSlug;
            $this->redirect($url);
        }
    }

    /**
     * Checks if uri params are valids and user is admin.
     *
     * @param array $params uri parameters
     *
     * @param object $article
     *
     * @return boolean
     */
    private function isNumeroAccessible(array $params, ArticlesModel $article): bool
    {
        return count($params) === 3 &&
        $params[2] === $this->getCOOKIE('CSRF') &&
        $this->userToTwig['admin'] &&
        $article->idExist($params[0]);
    }

    /**
     * Try to delete an article and set template failed or success
     *
     * @param array $params
     *
     * @return void
     */
    public function delete(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $paramsTest = count($params) === 2 && $params[1] === $this->getCOOKIE('CSRF');

        $article = new ArticlesModel();
        
        if ($this->userToTwig['admin'] && $article->idExist((int)$params[0]) && $paramsTest) {
            $this->userToTwig['template'] = 'admin';

            $this->preventCsrf();

            $this->template = 'admin' . DS . 'article-deletion-failed.twig';

            if ($article->deleteArticle((int)$params[0])) {
                $this->template = 'admin' . DS . 'article-deleted.twig';
            }
        }
    }

    /**
     * Update article and set template
     *
     * @param array $params
     *
     * @return void
     */
    public function modifier(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $paramsTest = count($params) === 2 && $params[1] === $this->getCOOKIE('CSRF');

        $article = new ArticlesModel();
        
        if ($this->userToTwig['admin'] && $article->idExist((int)$params[0]) && $paramsTest) {
            $this->userToTwig['template'] = 'admin';

            $category   = new CategoryModel();
            $categories = $category->readAll();

            $this->params = [
                'categories' => $categories,
                'id'         => $params[0]
            ];

            $this->params = array_merge($this->params, $article->getSingleArticle($params[0]));

            $this->preventCsrf();

            $this->template = 'admin' . DS . 'update-article.twig';
        }
    }

    /**
     * Processes article update from form post
     *
     * @param array $params
     *
     * @return void
     */
    public function update(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $paramsTest = count($params) === 2 && $params[1] === $this->getCOOKIE('CSRF');

        if ($this->userToTwig['admin'] && $paramsTest) {
            $article = new articlesModel();
            
            $article->updateArticle($params[0]);

            $this->preventCsrf();

            $this->userToTwig['template'] = 'admin';

            $this->template = 'admin' . DS . 'article-update-confirmation.twig';
        }
    }
}
