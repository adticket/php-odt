<?php

use ODTCreator\Content\LineBreak;
use ODTCreator\Element\Frame;
use ODTCreator\Element\Paragraph;
use ODTCreator\Content\Text;
use ODTCreator\Style\Factory;
use ODTCreator\Style\TextStyle;
use ODTCreator\Value\Color;
use ODTCreator\Value\FontSize;
use ODTCreator\Value\Length;
use ODTToPDFRenderer\ODTToPDFRenderer;
use PDFToPNGRenderer\PDFToPNGRenderer;

require_once __DIR__ . '/../vendor/autoload.php';

$outputDir = __DIR__ . '/output';
$odtFile = new SplFileInfo($outputDir . '/hello_world.odt');

//exec("rm -fr $outputDir");
//mkdir($outputDir);


$dummyText = file_get_contents(__DIR__ . '/dummyText.txt');

// Create an ODT file
$startTime = microtime(true);
$odt = new \ODTCreator\ODTCreator();


// Adresse

$frame = new Frame('Frame-1', new Length('2cm'), new Length('2.7cm'), new Length('8.5cm'), new Length('4.5cm'));
$p = new Paragraph('FP');
$p->addContent(new Text('AD ticket GmbH'));
$p->addContent(new LineBreak());
$p->addContent(new Text('Herr Dinko Bicvic'));
$p->addContent(new LineBreak());
$p->addContent(new Text('Kaiserstr. 69'));
$p->addContent(new LineBreak());
$p->addContent(new LineBreak());
$p->addContent(new Text('60329 Frankfurt am Main'));

$frame->addSubElement($p);
$odt->addElement($frame);


// Datum

$frame = new Frame('Frame-1', new Length('13cm'), new Length('8cm'), new Length('8cm'), new Length('2cm'));
$p = new Paragraph();
$p->addContent(new Text('Frankfurt, den ' . date('d.m.Y')));
$frame->addSubElement($p);
$odt->addElement($frame);


// Betreff

$p = new Paragraph('P1');
$style = new TextStyle('T123');
$style->setBold();
$text = new Text('Ihr neues Serienbriefmodul', $style);
$p->addContent($text);
$odt->addElement($p);


// Anrede

$p = new Paragraph();
$p->addContent(new LineBreak());
$p->addContent(new LineBreak());
$p->addContent(new Text('Sehr geehrter Herr Bicvic,'));
$p->addContent(new LineBreak());
$odt->addElement($p);


// Inhalt

//$styleFactory = new Factory();
//$paragraphStyle = $styleFactory->createParagraphStyle();
//$p = new Paragraph($paragraphStyle);
$p = new Paragraph();

// Convert line breaks

$dummyText = str_replace("\r\n", "\n", $dummyText);
$dummyText = str_replace("\r", "\n", $dummyText);
$lines = explode("\n", $dummyText);
$isFirstLine = true;
foreach ($lines as $line) {
    if ($isFirstLine) {
        $isFirstLine = false;
    } else {
        $p->addContent(new LineBreak());
    }
    if ('' !== $line) {
        $p->addContent(new Text($line));
    }
}

//    $textStyle = new TextStyle('t' . $i);
//
//    switch ($i % 4) {
//        case 0:
//            $color = '#ff0000';
//            break;
//        case 1:
//            $color = '#00ff00';
//            break;
//        case 2:
//            $color = '#0000ff';
//            break;
//        default:
//            $color = '#000000';
//            break;
//    }
//    $textStyle->setColor(new Color($color));
//    $textStyle->setBold();
//    $textStyle->setFontSize(new FontSize(($i * 2) + 6));
//    $odt->addTextStyle($textStyle);

for ($i = 0; $i < 4; $i++) {
    $odt->addElement($p);
}


// Grußformel

$p = new Paragraph();
$p->addContent(new Text('Mit freundlichen Grüßen'));
$odt->addElement($p);


// Render to ODT

$odt->save($odtFile);
$renderTimeODT = microtime(true) - $startTime;

$unzipDir = substr($odtFile->getPathname(), 0, -4);
system("rm -fr {$unzipDir}");
system("unzip {$odtFile->getPathname()} -d {$unzipDir}");

system("cd " . __DIR__ . "/../tests/ODTCreator/Test/EndToEnd/odf-validator && ./validator --file=" . $odtFile->getPathname());


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
