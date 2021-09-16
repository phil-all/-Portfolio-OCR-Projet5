<?php

namespace P5\Controllers;

use P5\Libraries\twig;

class MainController
{
    protected $action;
    protected $params = [];
    protected $twig;

    public function __construct($action, $params = [])
    {
        $this->action = $action;
        $this->params = $params;
        $this->twig = new Twig;
    }

    public function display()
    {
        echo $this->twig->twigRender($this->atcion, $this->params);
    }
}