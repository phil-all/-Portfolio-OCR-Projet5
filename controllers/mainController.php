<?php

namespace P5\Controllers;

use P5\Libraries\Twig;

/**
 * Generals methods for specialised controllers
 */
abstract class MainController
{
    protected $action;
    protected $params = [];
    protected $twig;

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
        echo $this->twig->twigRender($this->action, $this->params);
    }
}