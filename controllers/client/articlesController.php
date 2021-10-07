<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Libraries\Twig;
use Over_Code\Models\ArticlesModel;
use Over_Code\Controllers\MainController;

class ArticlesController extends MainController
{
    use \Over_Code\Libraries\Helpers;

    private array $articles;
    private int $perPage = 4;
    private int $totalPages;
    private int $page;
    private string $statePrevLink;
    private string $stateNextLink;
    private string $prevLink;
    private string $nextLink;
    

    /**
     * Defines parameters to send to display method
     *
     * @param string $action
     * @param array $params
     */
    public function __construct(string $action, array $params = [])
    {
        $articles = new ArticlesModel;

        $this->totalPages = (int)ceil($articles->getCount('article') / $this->perPage);

        $dispatch = $this->articlesDispatcher($params, $this->totalPages, $articles);

        switch ($dispatch[0]) {
            case '404':

                $this->action = 'client' . DS . 'pageNotFound.twig';

                $this->params = array();

                break;

            case 'List':

                switch ($dispatch[1]) {
                    case 'All':

                        $this->page = (int)(explode('-', $params[0]))[1];

                        $this->articles = $articles->getAllArticles($this->page, $this->perPage);

                        $this->action = $action;

                        break;

                    case 'Category':

                        $this->page = (int)(explode('-', $params[1]))[1];
                        
                        $this->articles = $articles->getCategoryArticles($this->page, $this->perPage, $params[0]);

                        $this->totalPages = (int)ceil($articles->getCategoryCount($params[0]) / $this->perPage);

                        $this->action = 'client' . DS . 'articles-by-category.twig';

                        $this->params['category'] = $params[0];

                        break;
                }

                foreach($this->articles as $key) { 
                    $key['slug'] = $this->toSlug($key['title']);
                    
                    array_shift($this->articles);

                    array_push($this->articles, $key);
                }

                $this->statePrevLink = ($this->page === 1) ? ' disabled' : '';

                $this->stateNextLink = ($this->page === $this->totalPages) ? ' disabled' : '';

                $this->prevLink = ($this->page === 1) ? 1 : $this->page - 1;

                $this->nextLink = ($this->page === $this->totalPages) ? $this->totalPages : $this->page + 1;

                $this->params = array_merge($this->params, array(
                    'page'       => $this->page,
                    'totalPages' => (int)$this->totalPages,
                    'articles'   => $this->articles,
                    'statePrev'  => $this->statePrevLink,
                    'stateNext'  => $this->stateNextLink,
                    'prev'       => $this->prevLink,
                    'next'       => $this->nextLink
                ));

                break;

            case 'One':

                $this->action = 'client' . DS . 'single-article.twig';

                $this->params = $articles->getSingleArticle($params[0]);

                break;
        }

        $this->twig = new Twig;
    }

    /**
     * Return articles type page, ex : all articles, articles from a category, one article
     *
     * @param array $params
     * @param int $totalPages
     * @param object $object instance of articles model
     * 
     * @return array
     */
    public function articlesDispatcher(array $params, int $total, object $object): array
    {
        $result[0] = '404';

        $count = count($params);

        switch ($count) {
            case 0: // --- Redirect to .../articles/pages-1 ---

                $url = SITE_ADRESS . '/articles/page-1';
                
                $this->redirect($url);
                
                break;

            case 1: // --- All articles ---

                if (self::uriPaginationTest($params[0], $total)) {

                    $result = array(
                        0 => 'List',
                        1 => 'All'
                    );

                }
                break;

            case 2:
                // --- params[0] is a category ---

                if ($object->categoryExist($params[0])) {

                    $total = $object->getCategoryCount($params[0]);

                    if (self::uriPaginationTest($params[1], $total)) {

                        $result = array(
                            0 => 'List',
                            1 => 'Category'
                        );

                    }

                // --- params[0] is an article id ---
                } elseif ($object->idExist($params[0], $object)) {

                    $slug = $this->toSlug($object->getTitle((int)$params[0]));

                    if ($slug != $params[1]) {

                        $url = SITE_ADRESS . DS . 'article' . DS . $params[0] . DS . $slug;

                        $this->redirect($url);

                    }

                    $result[0] = 'One';

                };
                break;
        }

        return $result;
    }

    /**
     * Test pagination in URI
     * - syntax have to be like page-1
     * - the number of the page have to be less or equal to the totalpage
     *
     * @param string $uriPart for example page-1
     * @param int $total count of all pages or a single category
     * 
     * @return boolean
     */
    public static function uriPaginationTest(string $uriPart, int $total): bool
    {
        $explode = explode('-', $uriPart);

        $test1 = (count($explode) === 2) ? true : false;

        switch ($test1) {

            case true:

                $deleteInt = preg_replace("~[0-9]~", "", $explode[1]);

                $test2 = ($explode[0] === 'page') ? true : false;

                $test3 = (empty($deleteInt) && $explode[1] != '') ? true : false;
                
                return ($test2 && $test3) ? ($explode[1] <= $total) : false;

                break;

            case false:

                return false;

                break;
        }
    }
}