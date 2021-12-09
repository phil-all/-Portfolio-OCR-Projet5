<?php

namespace Over_Code\Controllers;

use Over_Code\Controllers\MainController;

class PageNotFoundController extends MainController
{
    /**
     * Set page not found template
     *
     * @return void
     */
    public function index(): void
    {
        $this->template = 'pageNotFound.twig';
    }
}
