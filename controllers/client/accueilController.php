<?php

namespace P5\Controllers\Client;

use P5\Libraries\Twig;
use P5\Libraries\Helpers;
use P5\Models\ArticlesModel;
use P5\Controllers\MainController;

/**
 * Home page controller
 */
class AccueilController extends MainController
{
    public function __construct($action, $params = [])
    {
        $this->action = $action;
        
        $lastNews = new ArticlesModel;

        $this->lastNews = $lastNews->getNews(4);

        foreach($this->lastNews as $key) { 
            $key['slug'] = Helpers::toSlug($key['title']);
            
            array_shift($this->lastNews);

            array_push($this->lastNews, $key);
        }

        $this->params = array(
            'lastNews' => $this->lastNews
        );

        $this->twig = new Twig;
    }
}