<?php

use ODTCreator\Paragraph;
use ODTCreator\Style\TextStyle;
use ODTToPDFRenderer\ODTToPDFRenderer;
use PDFToPNGRenderer\PDFToPNGRenderer;

require_once __DIR__ .'/../vendor/autoload.php';

$outputDir = __DIR__ . '/output';
$odtFile = new SplFileInfo($outputDir . '/hello_world.odt');

exec("rm -fr $outputDir");
mkdir($outputDir);


// Create an ODT file
$startTime = microtime(true);
$odt = \ODTCreator\ODTCreator::getInstance();

$dummyText = file_get_contents(__DIR__ . '/dummyText.txt');
for ($i = 0; $i < 20; $i++) {
    $textStyle = new TextStyle('t' . $i);

    switch ($i % 4) {
        case 0:
            $color = '#ff0000';
            break;
        case 1:
            $color = '#00ff00';
            break;
        case 2:
            $color = '#0000ff';
            break;
        default:
            $color = '#000000';
            break;
    }
    $textStyle->setColor($color);
    $textStyle->setBold();
    $textStyle->setFontSize(($i * 2) + 6);
    $p = new Paragraph();
    $p->addText($dummyText, $textStyle);
}

$odt->save($odtFile);
$renderTimeODT = microtime(true) - $startTime;

// Render ODT file to PDF file
$startTime = microtime(true);
$libreOfficeBinary = new SplFileInfo('/Applications/LibreOffice.app/Contents/MacOS/soffice');
$pdfRenderer = new ODTToPDFRenderer($libreOfficeBinary);
$pdfFileInfo = $pdfRenderer->render($odtFile);

var_dump($pdfFileInfo->getPathname());
$renderTimePDF = microtime(true) - $startTime;


// Render PDF pages to PNG files
$startTime = microtime(true);
$ghostscriptBinary = new SplFileInfo('/usr/local/bin/gs');
$pngRenderer = new PDFToPNGRenderer($ghostscriptBinary);
$pngFileInfos = $pngRenderer->render($pdfFileInfo, 20, 22);

foreach ($pngFileInfos as $pngFileInfo) {
    var_dump($pngFileInfo->getPathname());
}
$renderTimePNG = microtime(true) - $startTime;


echo "Rendering ODT: " . $renderTimeODT . " msec\n";
echo "Rendering PDF: " . $renderTimePDF . " msec\n";
echo "Rendering PNG: " . $renderTimePNG . " msec\n";
