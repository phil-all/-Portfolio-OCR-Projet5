<?php

namespace P5\Controllers\Client;

/**
 * Home page controller
 */
class AccueilController extends \P5\Controllers\MainController
{
    /**
     * Apply twig render method
     *
     * @return void
     */
    public function display()
    {
        echo $this->twig->twigRender($this->action, $this->params);
    }
}