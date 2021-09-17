<?php

namespace P5\Controllers;

use P5\Libraries\twig;

/**
 * Generals  functions for specialised controllers
 */
class MainController
{
    protected $action;
    protected $params = [];
    protected $twig;

    /**
     * Construct magic method
     *
     * @param string $action
     * @param array $params
     * 
     * @return void
     */
    public function __construct($action, $params = [])
    {
        $this->action = $action;
        $this->params = $params;
        $this->twig = new Twig;
    }

    /**
     * Apply twigRender method on $twig object
     * 
     * @return void
     */
    public function display()
    {
        echo $this->twig->twigRender($this->atcion, $this->params);
    }
}