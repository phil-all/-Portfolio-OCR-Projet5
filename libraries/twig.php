<?php

namespace P5\Libraries;

use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Renders a template based on params from router
 */
class twig
{
    /**
     * Twig environment object: template to load & twig configuration
     *
     * @var object
     */
    private $twig;

    /**
     * Construct magic method: define twig environment object
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(VIEWS_PATH);
        // rechercher doc sur addPath
        //$loader->addPath(VIEWS_PATH . 'admin', 'admin');
        //$loader->addPath(VIEWS_PATH . 'client', 'client');

        $twig = new \Twig\Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);

        $twig->addExtension(new DebugExtension());

        $this->twig = $twig;
    }

    /**
     * Loads the template passed as a first argument and renders it with the variables passed as a second argument
     *
     * @param string $template
     * @param array $params
     * @return void
     */
    public function twigRender($template, $params = [])
    {
        return $this->twig->render($template, $params);
    }
}