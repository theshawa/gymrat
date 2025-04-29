<?php

require_once __DIR__ . "/vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

function generate_html($fileName, $htmlBody)
{
    $html = <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>$fileName</title>
        <style>
            *{
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }
            
            body{
                padding: 16px;
                font-family: 'Arial', sans-serif;
                font-size: 14px;
                line-height: 1.6;
                color: black;
                background-color: white;
            }

            h1, h2, h3, h4, h5, h6 {
                margin: 0 0 10px;
            }

            p {
                margin: 0 0 10px;
            }
            
            ul {
                margin: 0 0 10px;
                padding-left: 20px;
            }
            li {
                margin: 0 0 5px;
            }
            a {
                color: black;
                text-decoration: underline;
            }
            img {
                max-width: 100%;
                height: auto;
            }
            .footer {
                margin-top: 20px;
                text-align: center;
                font-size: 12px;
                color: black;
            }
            table{
                width: 100%; 
                border-collapse: collapse;
                border: 1px solid black;
            }
            th,td{
                padding: 10px;
                border: 1px solid black;
                text-align: left;
            }
        </style>
    </head>
    <body>
        $htmlBody
        <div class="footer">
            &copy; 2025 GYMRAT. All rights reserved.
        </div>
    </body>
    </html>
    HTML;
    return $html;
}

function generate_pdf($htmlBody, $filename = "document.pdf", $download = true)
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('isFontSubsettingEnabled', true);
    $options->set('defaultFont', 'sans-serif');

    $dompdf = new Dompdf($options);

    $html = generate_html($filename, $htmlBody);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream($filename, array("Attachment" => $download));
}
