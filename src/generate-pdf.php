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
            
            :root {
                --color-violet-50: #f1e6ff;
                --color-violet-100: #e3ccff;
                --color-violet-200: #c799ff;
                --color-violet-300: #aa66ff;
                --color-violet-400: #8e33ff;
                --color-violet-500: #7200ff;
                --color-violet-600: #6700e6;
                --color-violet-700: #5b00cc;
                --color-violet-800: #5000b3;
                --color-violet-900: #440099;
                --color-violet-950: #2e0066;

                --color-zinc-50: #fafafa;
                --color-zinc-100: #f4f4f5;
                --color-zinc-200: #e4e4e7;
                --color-zinc-300: #d4d4d8;
                --color-zinc-400: #a1a1aa;
                --color-zinc-500: #71717a;
                --color-zinc-600: #52525b;
                --color-zinc-700: #3f3f46;
                --color-zinc-800: #27272a;
                --color-zinc-900: #18181b;
                --color-zinc-950: #09090b;
            }
            body{
                padding: 16px;
                font-family: 'Arial', sans-serif;
                font-size: 14px;
                line-height: 1.6;
                color: var(--color-zinc-200);
                background-color: var(--color-zinc-950);
            }

            h1, h2, h3, h4, h5, h6 {
                margin: 0 0 10px;
            }

            .colored{
                color: var(--color-violet-500);
            }

            .grayed{
                color: var(--color-zinc-400);
            }

            p {
                margin: 0 0 10px;
                color: var(--color-zinc-300);
            }
            ul {
                margin: 0 0 10px;
                padding-left: 20px;
            }
            li {
                margin: 0 0 5px;
            }
            a {
                color:var(--color-violet-500);
                text-decoration: none;
            }
            a:hover {
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
                color: var(--color-zinc-400);
            }
            table{
                width: 100%; 
                border-collapse: collapse;
                border: 1px solid var(--color-zinc-800);
            }
            th,td{
                padding: 10px;
                border: 1px solid var(--color-zinc-800);
                text-align: left;
            }
            .panelled{
                background-color: var(--color-zinc-900);
                color: var(--color-zinc-50);
                padding: 10px;
                border-radius: 12px;
                text-align: center;
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

function generate_pdf($htmlBody, $filename = "document.pdf")
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
    $dompdf->stream($filename, array("Attachment" => false));
}
