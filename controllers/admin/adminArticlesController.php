<?php

namespace Over_Code\Controllers\Admin;

use Over_Code\Models\CommentModel;
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

    private array $articles;
    private int $perPage = 4;
    private int $totalPages;
    private int $currentPage;

    /**
     * Sets params and template to twig, about create new article
     *
     * @return void
     */
    public function nouveau(): void
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

    /**
     * Processes article creation from form post
     *
     * @return void
     */
    public function post(): void
    {
        if ($this->userToTwig['admin']) {
            $upload = $this->uploadArticleImg();

            if ($upload['img_name'] !== null) {
                $user = $this->userToTwig['user']['serial'];

                $img = $upload['img_name'];

                $article = new articlesModel();
                $article->createArticle($user, $img);

                $this->userToTwig['template'] = 'admin';

                $this->template = 'admin' . DS . 'post-confirmation.twig';
            }
        }
    }

    /**
     * Sets params and template to twig, about list of articles:
     * - all articles, uri : .../adminArticles/liste/tous/page..
     * - articles from a category, uri : .../adminArticles/liste/category_name/page..
     *
     * @param array $params category or all, and page number, given in URL
     *
     * @return void
     */
    public function liste(array $params): void
    {
        if ($this->userToTwig['admin']) {
            $this->userToTwig['template'] = 'admin';

            $model = new ArticlesModel();
        
            $this->currentPage = (int)(explode('-', $params[1]))[1];
            $this->totalPosts = $model->getCount($params[0]);
            $this->totalPages = ceil($this->totalPosts / $this->perPage);
    
            if ($this->currentPage <= $this->totalPages && explode('-', $params[1])[0] === 'page') {
                $this->articles = $model->getArticlesList($this->currentPage, $this->perPage, $params[0]);
                $this->template = 'admin' . DS . 'articles.twig';
    
                if ($model->categoryExist($params[0])) {
                    $this->template = 'admin' . DS . 'articles-by-category.twig';
                    $this->params['category'] = $params[0];
                }
    
                foreach ($this->articles as $key) {
                    $key['slug'] = $this->toSlug($key['title']);
                    array_shift($this->articles);
                    array_push($this->articles, $key);
                }
    
                $this->params = array_merge($this->params, array(
                    'page'       => $this->currentPage,
                    'totalPages' => $this->totalPages,
                    'articles'   => $this->articles,
                    'statePrev'  => ($this->currentPage === 1) ? ' disabled' : '',
                    'stateNext'  => ($this->currentPage === $this->totalPages) ? ' disabled' : '',
                    'prev'       => ($this->currentPage === 1) ? 1 : $this->currentPage - 1,
                    'next'       => ($this->currentPage === $this->totalPages) ? $this->totalPages : $this->currentPage + 1
                ));
            }
        }
    }

    /**
     * Sets params and template to twig, about single article page
     *
     * @param array $params slug given in URL
     *
     * @return void
     */
    public function numero(array $params): void
    {
        if ($this->userToTwig['admin']) {
            $this->userToTwig['template'] = 'admin';

            $model = new ArticlesModel();

            if ($model->idExist($params[0])) {
                $slug = $this->toSlug($model->getTitle((int)$params[0]));
    
                if ($slug != $params[1]) {
                    $url = SITE_ADRESS . DS . 'articles' . DS . 'numero' . DS . $params[0] . DS . $slug;
                    $this->redirect($url);
                }
    
                $this->template = 'admin' . DS . 'single-article.twig';
                $this->params = $model->getSingleArticle($params[0]);
    
                $comment = new CommentModel();
                if (!empty($comment->readValidated($params[0]))) {
                    $this->params['comments'] =  $comment->readValidated($params[0]);
                }
            }
        }
    }
}
