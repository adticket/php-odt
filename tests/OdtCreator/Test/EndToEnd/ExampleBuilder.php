<?php

namespace OdtCreator\Test\EndToEnd;

use Juit\PhpOdt\OdtCreator\Content\LineBreak;
use Juit\PhpOdt\OdtCreator\Content\Text;
use Juit\PhpOdt\OdtCreator\Element\Frame;
use Juit\PhpOdt\OdtCreator\Element\Paragraph;
use Juit\PhpOdt\OdtCreator\OdtFile;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use Juit\PhpOdt\OdtCreator\Style\TextStyle;
use Juit\PhpOdt\OdtCreator\Value\Color;
use Juit\PhpOdt\OdtCreator\Value\FontSize;
use Juit\PhpOdt\OdtCreator\Value\Length;
use Juit\PhpOdt\OdtToPdfRenderer\InstantRenderer;

class ExampleBuilder
{
    /**
     * @var \SplFileinfo
     */
    private $outputDirInfo;

    /**
     * @var OdtFile
     */
    private $odtFile;

    public function __construct(\SplFileinfo $outputDirInfo)
    {
        $this->outputDirInfo = $outputDirInfo;
        $this->odtFile       = new OdtFile();
    }

    /**
     * @param string $basename
     * @return \SplFileInfo
     */
    public function build($basename)
    {
        $odt = new \SplFileInfo("{$this->outputDirInfo}/{$basename}.odt");
        $pdf = new \SplFileInfo("{$this->outputDirInfo}/{$basename}.pdf");

        $this->createOdtFile($odt);
        $this->renderPdf($odt, $pdf);

        return $pdf;
    }

    /**
     * @param \SplFileInfo $odtFileInfo
     */
    private function createOdtFile(\SplFileInfo $odtFileInfo)
    {
        $this->setDefaultStyles();
        $this->setPageBorders();
        $this->addAddressFrame();
        $this->addDateFrame();
        $this->addSubject();
        $this->addSalutation();
        $this->addContent();
        $this->addColoredContent();
        $this->addBoldContent();
        $this->addBiggerContent();
        $this->addContentWithACombinationOfFormats();
        $this->addRegards();

        $this->odtFile->save($odtFileInfo);
    }

    private function setDefaultStyles()
    {
        $this->odtFile->getDefaultTextStyle()->setFontName('Arial');
        $this->odtFile->getDefaultTextStyle()->setFontSize(new FontSize('11pt'));
        $this->odtFile->getDefaultParagraphStyle()->setMarginBottom(new Length('12pt'));
    }

    private function setPageBorders()
    {
        $this->odtFile->getPageStyle()->setMarginTop(new Length('3cm'));
        $this->odtFile->getPageStyle()->setMarginTopOnFirstPage(new Length('11.3cm'));
        $this->odtFile->getPageStyle()->setMarginLeft(new Length('2.5cm'));
        $this->odtFile->getPageStyle()->setMarginRight(new Length('2.5cm'));
        $this->odtFile->getPageStyle()->setMarginBottom(new Length('3cm'));
    }

    private function addAddressFrame()
    {
        $xCoordinate  = new Length('2cm');
        $yCoordinate  = new Length('2.7cm');
        $width        = new Length('8.5cm');
        $height       = new Length('4.5cm');
        $addressFrame = $this->odtFile->createFrame($xCoordinate, $yCoordinate, $width, $height);

        $paragraph = $addressFrame->createParagraph();
        $paragraph->createTextElement('Mustermann GmbH');
        $paragraph->createLineBreak();
        $paragraph->createTextElement('Herr Max Mustermann');
        $paragraph->createLineBreak();
        $paragraph->createTextElement('Musterstr. 1');
        $paragraph->createLineBreak();
        $paragraph->createLineBreak();
        $paragraph->createTextElement('12345 Musterstadt');
    }

    private function addDateFrame()
    {
        $dateFrame   = $this->odtFile->createFrame(
            new Length('14cm'),
            new Length('8cm'),
            new Length('5cm'),
            new Length('2cm')
        );
        $paragraph = $dateFrame->createParagraph();
        $paragraph->createTextElement('Musterdorf, den 02.05.2014');
    }

    private function addSubject()
    {
        $frame       = $this->odtFile->createFrame(
            new Length('2cm'),
            new Length('10cm'),
            new Length('17cm'),
            new Length('0.8cm')
        );
        $paragraph   = $frame->createParagraph();
        $textElement = $paragraph->createTextElement('Ihr Schreiben');
        $textElement->getStyle()->setBold();
    }

    private function addSalutation()
    {
        $paragraph = $this->odtFile->createParagraph();
        $paragraph->createTextElement('Sehr geehrter Herr Mustermann,');
    }

    private function addContent()
    {
        $this->createParagraphs($this->getDummyText());
    }

    /**
     * @return string
     */
    private function getDummyText()
    {
        return file_get_contents(__DIR__ . '/fixtures/dummy_text.txt');
    }

    /**
     * @param string $dummyText
     */
    private function createParagraphs($dummyText)
    {
        $textBlocks       = explode("\n", $dummyText);
        foreach ($textBlocks as $text) {
            $paragraph = $this->odtFile->createParagraph();
            $paragraph->createTextElement($text);
        }
    }

    private function addColoredContent()
    {
        $paragraph = $this->odtFile->createParagraph();
        $textElement = $paragraph->createTextElement("Dies ist roter Text.");
        $textElement->getStyle()->setColor(new Color('#ff0000'));
    }

    private function addBoldContent()
    {
        $paragraph = $this->odtFile->createParagraph();
        $textElement = $paragraph->createTextElement("Dies ist fett gedruckter Text.");
        $textElement->getStyle()->setBold();
    }

    private function addBiggerContent()
    {
        $paragraph = $this->odtFile->createParagraph();
        $textElement = $paragraph->createTextElement("Dies ist größerer Text.");
        $textElement->getStyle()->setFontSize(new FontSize('16pt'));
    }

    private function addContentWithACombinationOfFormats()
    {
        $paragraph = $this->odtFile->createParagraph();
        $textElement = $paragraph->createTextElement("Dies ist größerer, fett gedruckter, blauer Text.");

        $textStyle = $textElement->getStyle();
        $textStyle->setFontSize(new FontSize('16pt'));
        $textStyle->setBold();
        $textStyle->setColor(new Color('#0000ff'));
    }

    private function addRegards()
    {
        $paragraph = $this->odtFile->createParagraph();
        $paragraph->createTextElement('Mit freundlichen Grüßen');
    }

    /**
     * @param \SplFileInfo $odtFileInfo
     * @param \SplFileInfo $pdfFileInfo
     * @return \SplFileInfo
     */
    private function renderPdf(\SplFileInfo $odtFileInfo, \SplFileInfo $pdfFileInfo)
    {
        $pdfRenderer = new InstantRenderer();
        $pdfRenderer->render($odtFileInfo, $pdfFileInfo);
    }
}
