<?php

namespace Over_Code\Libraries;

use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Template rendering
 */
class Twig
{
    /**
     * Twig environment object: template to load & twig configuration
     *
     * @var object
     */
    private $twig;

    /**
     * Defines twig environment object
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(VIEWS_PATH);

        $twig = new() \Twig\Environment($loader, [
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
     */
    public function twigRender(string $template, array $params = [])
    {
        print_r($this->twig->render($template, $params));
    }

    /**
     * Return twig environment object
     *
     * @return object
     */
    public function getTwig(): object
    {
        return $this->twig;
    }
}
