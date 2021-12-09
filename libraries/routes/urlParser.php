<?php

namespace Over_Code\Libraries\Routes;

/**
 * Parse url to obtain controller, method, and attributes
 */
class UrlParser
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Sets uri attributes as an array containg uri params
     */
    public function __construct()
    {
        $this->uri = explode('/', trim($this->getGET('url_params')));
    }

    /**
     * Return a controller class, based on url
     *
     * @return string
     */
    public function getControllerClass(): string
    {
        return  ($this->uri[0] === '') ? 'Accueil' : ucfirst($this->uri[0]);
    }

    /**
     * Return a methodName, based on url
     *
     * @return string
     */
    public function getMethodName(): string
    {
        $method =  ($this->uri[1]) ?? 'index';
        $method = (strlen($method) === 0) ? 'index' : $method;

        return $method;
    }

    /**
     * Returns part of url parameters corresonding to attributes method
     *
     * @return array|null
     */
    public function getAttributesList(): ?array
    {
        return (array_splice($this->uri, 2)) ?? null;
    }
}
