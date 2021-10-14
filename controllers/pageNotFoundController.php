<?php

namespace Over_Code\Controllers;

use Over_Code\Controllers\MainController;

class PageNotFoundController extends MainController
{
    public function index()
    {
        $this->template = 'pageNotFound.twig';
    }
}