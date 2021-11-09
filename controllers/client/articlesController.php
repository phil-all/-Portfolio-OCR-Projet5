<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Models\CommentModel;
use Over_Code\Models\ArticlesModel;
use Over_Code\Controllers\MainController;

/**
 * Articles pages controller
 */
class ArticlesController extends MainController
{
    use \Over_Code\Libraries\Helpers;

    private array $articles;
    private int $perPage = 4;
    private int $totalPages;
    private int $currentPage;

    /**
     * Sets params and template to twig, about single article page
     *
     * @param array $params slug given in URL
     *
     * @return void
     */
    public function numero(array $params): void
    {
        $model = new ArticlesModel();

        $this->template = 'pageNotFound.twig';

        if ($model->idExist($params[0])) {
            $slug = $this->toSlug($model->getTitle((int)$params[0]));

            if ($slug != $params[1]) {
                $url = SITE_ADRESS . DS . 'articles' . DS . 'numero' . DS . $params[0] . DS . $slug;
                $this->redirect($url);
            }

            $this->template = 'client' . DS . 'single-article.twig';
            $this->params = $model->getSingleArticle($params[0]);

            $comment = new CommentModel();
            if (!empty($comment->readValidated($params[0]))) {
                $this->params['comments'] =  $comment->readValidated($params[0]);
            }
        }
    }
    
    /**
     * Sets params and template to twig, about list of articles:
     * - all articles
     * - articles from a category
     *
     * @param array $params category or all, and page number, given in URL
     *
     * @return void
     */
    public function liste(array $params): void
    {
        $model = new ArticlesModel();
        
        $this->currentPage = (int)(explode('-', $params[1]))[1];
        $this->totalPosts = $model->getCount($params[0]);
        $this->totalPages = ceil($this->totalPosts / $this->perPage);
        $this->template = 'pageNotFound.twig';

        if ($this->currentPage <= $this->totalPages && explode('-', $params[1])[0] === 'page') {
            $this->articles = $model->getArticlesList($this->currentPage, $this->perPage, $params[0]);
            $this->template = 'client' . DS . 'articles.twig';

            if ($model->categoryExist($params[0])) {
                $this->template = 'client' . DS . 'articles-by-category.twig';
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
