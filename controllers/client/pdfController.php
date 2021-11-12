<?php

namespace Over_Code\Controllers\Client;

use Over_Code\Libraries\Pdf;
use Over_Code\Libraries\Twig;
use Over_Code\Controllers\MainController;

/**
 * Class to manage pdf call
 */
class pdfController extends MainController
{
    /**
     * Call the pdf generator and set the CV template to render
     *
     * @return void
     */
    public function CV(): void
    {
        $twig = new Twig();
        $template = 'client' . DS . 'pdf' . DS . 'curriculum-vitae.twig';
        $param = [];

        $pdf = new Pdf();

        $pdf->generatePdf($twig->getTwig()->render($template, $param));
    }
}