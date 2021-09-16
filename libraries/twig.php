<?php

namespace P5\Libraries;

use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class twig
{
    private $twig;

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

    public function twigRender($template, $params = [])
    {
        return $this->twig->render($template, $params);
    }
}