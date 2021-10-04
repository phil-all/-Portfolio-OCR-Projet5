<?php

namespace Over_Code\Controllers;

use Over_Code\Libraries\Twig;

/**
 * Generals methods for specialised controllers
 */
abstract class MainController
{
    protected string $action;
    protected array $params = [];
    protected object $twig;

    /**
     * Defines parameters to send to display method
     *
     * @param string $action
     * @param array $params
     * 
     * @return void
     */
    public function __construct(string $action, array $params = [])
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
    public function display(): void
    {
        echo $this->twig->twigRender($this->action, $this->params);
    }
}