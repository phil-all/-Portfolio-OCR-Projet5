<?php

namespace Over_Code\Libraries;

use Dompdf\Dompdf;

/**
 * Used to generate pdf from html content
 */
class Pdf
{
    /**
     * Streams a pdf file generated from html.
     *
     * @param string $name name of the generated pdf
     * @param string $content html to convert
     * 
     * @return void
     */
    public function generatePdf(string $name, string $content): void
    {
        $pdf = new Dompdf();

        $pdf->getOptions()->setChroot(PUBLIC_PATH);

        $pdf->loadHtml($content);

        $pdf->setPaper('A4');

        $pdf->render();

        $pdf->stream($name);
    }
}