<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Libraries\Twig;
use Over_Code\Libraries\Helpers;
use Over_Code\Models\ArticlesModel;
use Over_Code\Controllers\MainController;

/**
 * Home page controller
 */
class AccueilController extends MainController
{
    private array $lastNews;

    /**
     * Defines parameters to send to display method
     *
     * @param string $action
     */
    public function __construct(string $action)
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