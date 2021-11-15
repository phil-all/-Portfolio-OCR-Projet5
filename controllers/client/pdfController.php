<?php

namespace Over_Code\Controllers\Client;

use Dompdf\Dompdf;
use Over_Code\Libraries\Pdf;
use Dompdf\Options;
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
        $param = array(
            'public' => PUBLIC_PATH
        );

        $pdf = new Pdf();
        $pdf->generatePdf('CV-Philippe dev back', $twig->getTwig()->render($template, $param));
    }
}