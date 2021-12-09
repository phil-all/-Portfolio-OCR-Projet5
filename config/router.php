<?php

namespace Over_Code\Config;

use Over_Code\Libraries\Routes\UrlChecker;

/**
 * Instantiate the good controller
 * call method corresponding to action
 * pass attributes needed with good type
 */
class Router
{
    /**
     * Run the routing, in finding the good controller, the good method and
     * passing the good attributes
     *
     * @return mixed
     */
    public function __construct()
    {
        $this->match = new UrlChecker();
        
        $controller = $this->getClass();

        //$params = (empty($this->getParams())) ? NULL : $this->getParams();
        $params = $this->getParams();

        $controller = new $controller($this->getMethod(), $params);
    }

    /**
     * Return class for routing
     *
     * @return string
     */
    private function getClass(): string
    {
        if (!$this->match->controllerCheck() || !$this->match->methodCheck()) {
            return '\Over_Code\controllers\PageNotFoundController';
        }

        return $this->match->class;
    }

    /**
     * Retrun method for routing
     *
     * @return string
     */
    private function getMethod(): string
    {
        if (!$this->match->methodCheck()) {
            return 'methodNotFound';
        }

        return $this->match->method;
    }

    /**
     * Return attributes for routing
     *
     * @return array
     */
    private function getParams(): array
    {
        return $this->match->params;
    }
}
