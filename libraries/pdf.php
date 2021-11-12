<?php

namespace Over_Code\Libraries;

use Dompdf\Dompdf;

/**
 * Used to generate pdf from html content
 */
class Pdf
{
    /**
     * Streams a pdf file generated from a given template
     *
     * @param string $content
     * 
     * @return void
     */
    public function generatePdf(string $content): void
    {
        $pdf = new Dompdf();

        $pdf->loadHtml($content);

        $pdf->setPaper('A4');

        $pdf->render();

        $pdf->stream();
    }
}