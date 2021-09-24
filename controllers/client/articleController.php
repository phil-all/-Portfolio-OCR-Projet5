<?php

namespace P5\Controllers\Client;

use P5\Libraries\Twig;
use P5\Libraries\Helpers;
use P5\Models\ArticleModel;
use P5\Controllers\MainController;

class ArticleController extends MainController
{
    public function __construct($action, $params = [])
    {
        $article = new ArticleModel;

        if(self::pageValidator($params, $article)) {
            $this->action = $action;

            $this->params = $article->getSingleArticle($params[0]);
        } else {
            $this->action = 'client' . DS . 'pageNotFound.twig';

            $this->params = array();
        }

        $this->twig = new Twig;
    }

    /**
     * Check URI parameters conformity
     * and redirect on good URI if mistake on slug
     *
     * @param array $params
     * @param object $article
     * 
     * @return boolean
     */
    public static function pageValidator($params, $article)
    {
        // Check count parameters and id
        $test1 = (count($params) === 2) ? true : false;

        $test2 = (empty(preg_replace('~[0-9]+~', '', $params[0]))) ? true : false;

        $test3 = ($article->isExist((int)$params[0]) == '1') ? true : false;

        if(!$test1 || !$test2 || !$test3) {
            return false;
        }

        // Check slug
        $slug = Helpers::slugedLink($article->getTitle((int)$params[0]));

        $test4 = ( $slug === $params[1]) ? true : false;

        if(!$test4) {
            header('Location: ' . SITE_ADRESS . DS . 'article' . DS . $params[0] . DS . $slug);
            exit();
        }

        return true;
    }
}