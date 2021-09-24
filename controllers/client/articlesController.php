<?php

namespace P5\Controllers\Client;

use P5\Libraries\Twig;
use P5\Libraries\Helpers;
use P5\Models\ArticleModel;
use P5\Controllers\MainController;

class ArticlesController extends MainController
{
    private $perPage = 4; // int
    private $totalPages; // int
    private $page; // int
    private $articles; // array
    private $statePrevLink; // string
    private $stateNextLink; // string
    private $prevLink; // string
    private $nextLink; // string
    private $slug; // string

    /**
     * Construct magic method: Set values to attributes
     *
     * @param string $action
     * @param array $params
     * 
     * @return void
     */
    public function __construct($action, $params = [])
    {
        $articles = new ArticleModel;

        $this->totalPages = (int)ceil($articles->getCount() / $this->perPage);

        if(self::pageValidator($params, $this->totalPages)) {
            $this->action = $action;

            $this->page = (int)(explode('-', $params[0]))[1];

            $this->articles = $articles->getAllArticles($this->page, $this->perPage);

            foreach($this->articles as $key) {
                $key['slug'] = Helpers::slugedLink($key['title']);

                array_shift($this->articles);
                
                array_Push($this->articles, $key);
            }

            $this->statePrevLink = ($this->page === 1) ? ' disabled' : '';

            $this->stateNextLink = ($this->page === $this->totalPages) ? ' disabled' : '';

            $this->prevLink = ($this->page === 1) ? 1 : $this->page - 1;

            $this->nextLink = ($this->page === $this->totalPages) ? $this->totalPages : $this->page + 1;

            $this->params = array(
                'page'       => $this->page,
                'totalPages' => $this->totalPages,
                'articles'   => $this->articles,
                'slug'       => $this->slug,
                'statePrev'  => $this->statePrevLink,
                'stateNext'  => $this->stateNextLink,
                'prev'       => $this->prevLink,
                'next'       => $this->nextLink
            );
        } else {
            $this->action = 'client' . DS . 'pageNotFound.twig';

            $this->params = array();
        }
        
        $this->twig = new Twig;
    }

    /**
     * Check URI parameters conformity
     *
     * @param array  $params :
     *                  - parameters from URI, after ...articles/
     *                  - have to be : 'page-' . integer
     * @param int $totalPages
     * 
     * @return boolean
     */
    static public function pageValidator($params, $totalPages)
    {
        $test1 = (!empty($params) && count($params) === 1) ? true : false;

        if(!$test1) {
            return false;
        }

        $test2 = (preg_match(('~[\-]~'), $params[0])) ? true : false;

        if(!$test2) {
            return false;
        }

        $page = explode('-', $params[0]);

        $test3 = (count($page) === 2 && $page[0] === 'page') ? true : false;

        $test4 = (!empty($page[1]) && preg_replace("~[0-9]~", "", $page[1]) === '') ?true : false;

        $test5 = ($page[1] <= $totalPages) ? true : false;

        if(!$test3 || !$test4 || !$test5) {
            return false;
        } else {
            return true;
        }
    }
}