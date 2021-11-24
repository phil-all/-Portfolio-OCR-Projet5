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
     * @return void
     */
    public function nouveau(): void
    {
        $this->template = 'client' . DS . 'accueil.twig';
        
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
        $this->template = 'client' . DS . 'accueil.twig';

        if ($this->userToTwig['admin']) {
            $upload = $this->uploadArticleImg();

            $img = '0000'; // default article image

            if ($upload['message'] === 0) { // 3: no file exist
                $img = $upload['img_name'];
            }
            
            $user = $this->userToTwig['user']['serial'];

            $article = new articlesModel();
            $article->createArticle($user, $img);

            $this->userToTwig['template'] = 'admin';

            $this->template = 'admin' . DS . 'article-post-confirmation.twig';
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
        $this->template = 'client' . DS . 'accueil.twig';

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
                    'next' => ($this->currentPage === $this->totalPages) ? $this->totalPages : $this->currentPage + 1
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
        $this->template = 'client' . DS . 'accueil.twig';
        
        $article = new ArticlesModel();

        if ($this->userToTwig['admin'] && $article->idExist($params[0])) {
            $this->userToTwig['template'] = 'admin';

            $slug = $this->toSlug($article->getTitle((int)$params[0]));
    
            if ($slug != $params[1]) {
                $url = SITE_ADRESS . DS . 'articles' . DS . 'numero' . DS . $params[0] . DS . $slug;
                $this->redirect($url);
            }

            $this->template = 'admin' . DS . 'single-article.twig';
            $this->params = $article->getSingleArticle($params[0]);

            $comment = new CommentModel();
            if (!empty($comment->readValidated($params[0]))) {
                $this->params['comments'] =  $comment->readValidated($params[0]);
            }
        }
    }

    /**
     * Try to delete an article and set template failed or success
     *
     * @param array $params [0 => article id]
     *
     * @return void
     */
    public function delete(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $article = new ArticlesModel();
        
        if ($this->userToTwig['admin'] && $article->idExist((int)$params[0])) {
            $this->userToTwig['template'] = 'admin';

            $this->template = 'admin' . DS . 'article-deletion-failed.twig';

            if ($article->deleteArticle((int)$params[0])) {
                $this->template = 'admin' . DS . 'article-deleted.twig';
            }
        }
    }

    /**
     * Update article and set template
     *
     * @param array $params [0 => article id]
     *
     * @return void
     */
    public function modifier(array $params): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        $article = new ArticlesModel();
        
        if ($this->userToTwig['admin'] && $article->idExist((int)$params[0])) {
            $this->userToTwig['template'] = 'admin';

            $category = new CategoryModel();
            $categories = $category->readAll();

            $this->params = [
                'categories' => $categories
            ];

            $this->params = array_merge($this->params, $article->getSingleArticle($params[0]));

            $this->template = 'admin' . DS . 'update-article.twig';
        }
    }

    /**
     * Processes article update from form post
     *
     * @return void
     */
    public function update(): void
    {
        $this->template = 'client' . DS . 'accueil.twig';

        if ($this->userToTwig['admin']) {
            $articleId = preg_replace('~[a-zA-Z\/\:\_\-]~', '', $this->getSERVER('HTTP_REFERER'));

            $img = null;

            $article = new articlesModel();

            if ($this->getFILES('image')['error'] !== 4) { // error 4: UPLOAD_ERR_NO_FILE no download file
                echo "ici";
                $upload = $this->uploadArticleImg($article->getImg($articleId));
            }
            
            $article->updateArticle($articleId, $img);

            $this->userToTwig['template'] = 'admin';

            $this->template = 'admin' . DS . 'article-update-confirmation.twig';
        }
    }
}
