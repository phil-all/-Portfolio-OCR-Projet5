<?php

namespace Over_Code\Controllers;

use Over_Code\Libraries\Twig;

/**
 * Generals methods for specialised controllers
 */
abstract class MainController
{
    use \Over_Code\Libraries\Helpers;

    protected string $action;
    protected array $params = [];
    protected object $twig;

    /**
     * Call method action, passing parameters, and send it to the display method
     *
     * @param string $action
     * @param array $params
     * 
     * @return void
     */
    public function __construct(string $action, array $params = [])
    {
        $this->$action($params);

        $this->twig = new Twig;

        $this->twig->twigRender($this->template, $this->params);
    }

    /**
     * Set template to pageNotFound if method sent in constructor is not fpound
     */
    public function methodNotFound()
    {
        $this->template = 'pageNotFound.twig';
    }
}