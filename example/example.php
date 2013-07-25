<?php

require_once __DIR__ .'/../vendor/autoload.php';

$outputDir = __DIR__ . '/output';
$odtFile = new SplFileInfo($outputDir . '/hello_world.odt');

exec("rm -fr $outputDir");
mkdir($outputDir);

// Create an ODT file
$odt = \ODTCreator\ODTCreator::getInstance();
for ($i = 0; $i < 100; $i++) {
    $p = new \ODTCreator\Paragraph();
    $p->addText('Hello World!');
}

$odt->save($odtFile);


// Render ODT file to PDF file
$libreOfficeBinary = new SplFileInfo('/Applications/LibreOffice.app/Contents/MacOS/soffice');
$pdfRenderer = new \ODTToPDFRenderer\ODTToPDFRenderer($libreOfficeBinary);
$pdfFileInfo = $pdfRenderer->render($odtFile);

var_dump($pdfFileInfo->getPathname());


// Render PDF pages to PNG files
$ghostscriptBinary = new SplFileInfo('/usr/local/bin/gs');
$pngRenderer = new \PDFToPNGRenderer\PDFToPNGRenderer($ghostscriptBinary);
$pngFileInfos = $pngRenderer->render($pdfFileInfo);

foreach ($pngFileInfos as $pngFileInfo) {
    var_dump($pngFileInfo->getPathname());
}
