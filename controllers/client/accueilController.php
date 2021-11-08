<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Models\ArticlesModel;
use Over_Code\Controllers\MainController;

/**
 * Home page controller
 */
class AccueilController extends MainController
{
    use \Over_code\Libraries\Helpers;

    /**
     * Sets params and template to twig, about home page
     *
     * @return void
     */
    public function index(): void
    {
        $this->template = 'client' . DS . 'accueil.twig';
        
        $lastNews = new ArticlesModel();
        $this->lastNews = $lastNews->getNews(4);

        foreach ($this->lastNews as $key) {
            $key['slug'] = $this->toSlug($key['title']);
            array_shift($this->lastNews);
            array_push($this->lastNews, $key);
        }

        $this->params = array(
            'lastNews' => $this->lastNews
        );
    }
}
