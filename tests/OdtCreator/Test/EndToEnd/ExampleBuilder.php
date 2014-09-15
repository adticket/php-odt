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
     * @var StyleFactory
     */
    private $styleFactory;

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
        $this->styleFactory  = $this->odtFile->getStyleFactory();
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

    private function setPageBorders()
    {
        $this->styleFactory->setMarginRight(new Length('2cm'));
        $this->styleFactory->setMarginTopOnFirstPage(new Length('11.3cm'));
    }

    private function addAddressFrame()
    {
        $xCoordinate  = new Length('2cm');
        $yCoordinate  = new Length('2.7cm');
        $width        = new Length('8.5cm');
        $height       = new Length('4.5cm');
        $addressFrame = $this->odtFile->createFrame($xCoordinate, $yCoordinate, $width, $height);

        $textStyle = $this->createDefaultTextStyle();
        $paragraph = new Paragraph();
        $paragraph->createTextElement('Mustermann GmbH', $textStyle);
        $paragraph->createLineBreak();
        $paragraph->createTextElement('Herr Max Mustermann', $textStyle);
        $paragraph->createLineBreak();
        $paragraph->createTextElement('Musterstr. 1', $textStyle);
        $paragraph->createLineBreak();
        $paragraph->createLineBreak();
        $paragraph->createTextElement('12345 Musterstadt', $textStyle);

        $addressFrame->addSubElement($paragraph);
    }

    /**
     * @return TextStyle
     */
    private function createDefaultTextStyle()
    {
        $textStyle = $this->styleFactory->createTextStyle();
        $textStyle->setFontSize(new FontSize('11pt'));
        $textStyle->setFontName('Arial');

        return $textStyle;
    }

    private function addDateFrame()
    {
        $xCoordinate = new Length('14cm');
        $yCoordinate = new Length('8cm');
        $width       = new Length('5cm');
        $height      = new Length('2cm');
        $dateFrame   = $this->odtFile->createFrame($xCoordinate, $yCoordinate, $width, $height);

        $paragraph = new Paragraph();
        $paragraph->createTextElement(
            'Musterdorf, den 02.05.2014',
            $this->createDefaultTextStyle()
        );

        $dateFrame->addSubElement($paragraph);
    }

    private function addSubject()
    {
        $frame     = $this->odtFile->createFrame(
            new Length('2cm'),
            new Length('10cm'),
            new Length('17cm'),
            new Length('0.8cm')
        );
        $paragraph = new Paragraph();

        $textStyle = $this->createDefaultTextStyle();
        $textStyle->setBold();
        $paragraph->createTextElement('Ihr Schreiben', $textStyle);
        $frame->addSubElement($paragraph);
    }

    private function addSalutation()
    {
        $paragraph = $this->createDefaultParagraph();
        $paragraph->createTextElement('Sehr geehrter Herr Mustermann,', $this->createDefaultTextStyle());
    }

    /**
     * @return Paragraph
     */
    private function createDefaultParagraph()
    {
        return $this->odtFile->createParagraph($this->createDefaultParagraphStyle());
    }

    /**
     * @return \Juit\PhpOdt\OdtCreator\Style\ParagraphStyle
     */
    private function createDefaultParagraphStyle()
    {
        $paragraphStyle = $this->styleFactory->createParagraphStyle();
        $paragraphStyle->setMarginBottom(new Length('12pt'));

        return $paragraphStyle;
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
        $defaultTextStyle = $this->createDefaultTextStyle();
        foreach ($textBlocks as $text) {
            $paragraph = $this->createDefaultParagraph();
            $paragraph->createTextElement($text, $defaultTextStyle);
        }
    }

    private function addColoredContent()
    {
        $paragraph = $this->createDefaultParagraph();
        $style     = $this->createDefaultTextStyle();
        $style->setColor(new Color('#ff0000'));
        $paragraph->createTextElement("Dies ist roter Text.", $style);
    }

    private function addBoldContent()
    {
        $paragraph = $this->createDefaultParagraph();
        $style     = $this->createDefaultTextStyle();
        $style->setBold();
        $paragraph->createTextElement("Dies ist fett gedruckter Text.", $style);
    }

    private function addBiggerContent()
    {
        $paragraph = $this->createDefaultParagraph();
        $style     = $this->createDefaultTextStyle();
        $style->setFontSize(new FontSize('16pt'));
        $paragraph->createTextElement("Dies ist größerer Text.", $style);
    }

    private function addContentWithACombinationOfFormats()
    {
        $paragraph = $this->createDefaultParagraph();
        $style     = $this->createDefaultTextStyle();
        $style->setFontSize(new FontSize('16pt'));
        $style->setBold();
        $style->setColor(new Color('#0000ff'));
        $paragraph->createTextElement("Dies ist größerer, fett gedruckter, blauer Text.", $style);
    }

    private function addRegards()
    {
        $paragraph = $this->createDefaultParagraph();
        $paragraph->createTextElement('Mit freundlichen Grüßen', $this->createDefaultTextStyle());
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
