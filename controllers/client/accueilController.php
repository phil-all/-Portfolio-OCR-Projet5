<?php

namespace P5\Controllers\Client;

class AccueilController extends \P5\Controllers\MainController
{
    public function display()
    {
        echo $this->twig->twigRender($this->action, $this->params);
    }
}