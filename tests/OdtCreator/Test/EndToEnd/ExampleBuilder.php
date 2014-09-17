<?php

namespace OdtCreator\Test\EndToEnd;

use Juit\PhpOdt\OdtCreator\OdtFile;
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
        $this->addContentFromHtml();
        $this->addImages();
        $this->addTextFrames();
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
        $xCoordinate = new Length('2cm');
        $yCoordinate = new Length('2.7cm');
        $width       = new Length('8.5cm');
        $height      = new Length('4.5cm');
        $this->odtFile->createFrameFromHtml(
            '<p>Mustermann GmbH<br>Herr Max Mustermann<br>Musterstr. 1<br><br><strong>12345 Musterstadt</strong></p>',
            $xCoordinate,
            $yCoordinate,
            $width,
            $height
        );
    }

    private function addDateFrame()
    {
        $this->odtFile->createFrameFromHtml(
            '<p>Musterdorf, <em>den 02.05.2014</em></p>',
            new Length('14cm'),
            new Length('8cm'),
            new Length('5cm'),
            new Length('2cm')
        );
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
        $textBlocks = explode("\n", $dummyText);
        foreach ($textBlocks as $text) {
            $paragraph = $this->odtFile->createParagraph();
            $paragraph->createTextElement($text);
        }
    }

    private function addColoredContent()
    {
        $paragraph   = $this->odtFile->createParagraph();
        $textElement = $paragraph->createTextElement("Dies ist roter Text.");
        $textElement->getStyle()->setColor(new Color('#ff0000'));
    }

    private function addBoldContent()
    {
        $paragraph   = $this->odtFile->createParagraph();
        $textElement = $paragraph->createTextElement("Dies ist fett gedruckter Text.");
        $textElement->getStyle()->setBold();
    }

    private function addBiggerContent()
    {
        $paragraph   = $this->odtFile->createParagraph();
        $textElement = $paragraph->createTextElement("Dies ist größerer Text.");
        $textElement->getStyle()->setFontSize(new FontSize('16pt'));
    }

    private function addContentWithACombinationOfFormats()
    {
        $paragraph   = $this->odtFile->createParagraph();
        $textElement = $paragraph->createTextElement("Dies ist größerer, fett gedruckter, blauer Text.");

        $textStyle = $textElement->getStyle();
        $textStyle->setFontSize(new FontSize('16pt'));
        $textStyle->setBold();
        $textStyle->setColor(new Color('#0000ff'));
    }

    private function addContentFromHtml()
    {
        $this->odtFile->createParagraphsFromHtml('<p>Dieser Text <u>ist per <strong>HTML</strong> erstellt</u>.<br>Er kann <em>Zeilenumbrüche</em> enthalten.</p><p>Mehrere Absätze <span style="font-family: \'Times New Roman\'">und <span style="font-size: 20px">Schriftformate</span></span> sind ebenfalls möglich.</p>');
    }

    private function addImages()
    {
        $paragraph = $this->odtFile->createParagraph();
        $paragraph->getStyle()->setPageBreakBefore(true);
        $paragraph->createTextElement('Seitenumbruch');

        $paragraph = $this->odtFile->createParagraph();
        $image     = $paragraph->createImage(new \SplFileInfo(__DIR__ . '/fixtures/logo.png'));
        $image->setWidth(new Length('7cm'));
        $image->getStyle()->setMarginTop(new Length('0.5cm'));
        $image->getStyle()->setMarginLeft(new Length('1cm'));
        $image->getStyle()->setMarginRight(new Length('1.5cm'));
        $image->getStyle()->setMarginBottom(new Length('2cm'));
        $paragraph->createTextElement('This text should not be wrapped.');

        $paragraph = $this->odtFile->createParagraph();
        $image     = $paragraph->createImage(new \SplFileInfo(__DIR__ . '/fixtures/logo.png'));
        $image->getStyle()->setAlignCenter();
        $paragraph->createTextElement('This text should not be wrapped.');

        $paragraph = $this->odtFile->createParagraph();
        $image     = $paragraph->createImage(new \SplFileInfo(__DIR__ . '/fixtures/logo.png'));
        $image->setHeight(new Length('0.5cm'));
        $image->getStyle()->setAlignRight();
        $paragraph->createTextElement('This text should not be wrapped.');

        $paragraph = $this->odtFile->createParagraph();
        $image     = $paragraph->createImage(new \SplFileInfo(__DIR__ . '/fixtures/logo.png'));
        $image->getStyle()->setWrapParallel();
        $image->getStyle()->setMarginRight(new Length('0.5cm'));
        $paragraph->createTextElement(
            'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.'
        );

        $paragraph = $this->odtFile->createParagraph();
        $image     = $paragraph->createImage(new \SplFileInfo(__DIR__ . '/fixtures/logo.png'));
        $image->getStyle()->setWrapParallel();
        $image->getStyle()->setAlignCenter();
        $image->getStyle()->setMarginLeft(new Length('0.5cm'));
        $image->getStyle()->setMarginRight(new Length('0.5cm'));
        $paragraph->createTextElement(
            'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.'
        );

        $paragraph = $this->odtFile->createParagraph();
        $image     = $paragraph->createImage(new \SplFileInfo(__DIR__ . '/fixtures/logo.png'));
        $image->getStyle()->setWrapParallel();
        $image->getStyle()->setAlignRight();
        $image->getStyle()->setMarginLeft(new Length('0.5cm'));
        $image->getStyle()->setMarginRight(new Length('0.5cm'));
        $paragraph->createTextElement(
            'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.'
        );
    }

    private function addTextFrames()
    {
        $paragraph = $this->odtFile->createParagraph();
        $paragraph->getStyle()->setPageBreakBefore(true);
        $paragraph->createTextElement('This paragraph has a page break before.');

        $paragraph = $this->odtFile->createParagraph();
        $textFrame = $paragraph->createTextFrame();
        $textFrame->setHeight(new Length('3cm'));
        $textFrame->setWidth(new Length('4cm'));
        $paragraph->createTextElement('Frame 1 - This text should not be wrapped.');

        $paragraph = $this->odtFile->createParagraph();
        $textFrame = $paragraph->createTextFrame();
        $textFrame->setHeight(new Length('3cm'));
        $textFrame->setWidth(new Length('4cm'));
        $textFrame->getStyle()->setBorderWidth(new Length('0.06pt'));
        $paragraph->createTextElement('Frame 2 - This text should not be wrapped.');

        $paragraph = $this->odtFile->createParagraph();
        $textFrame = $paragraph->createTextFrame();
        $textFrame->setHeight(new Length('2cm'));
        $textFrame->setWidth(new Length('2cm'));
        $textFrame->getStyle()->setBorderWidth(new Length('5pt'));
        $textFrame->getStyle()->setBorderColor(new Color('#ff0000'));
        $paragraph->createTextElement('Frame 3 - This text should not be wrapped.');

        $paragraph = $this->odtFile->createParagraph();
        $textFrame = $paragraph->createTextFrame();
        $textFrame->setHeight(new Length('2cm'));
        $textFrame->setWidth(new Length('2cm'));
        $textFrame->getStyle()->setBorderWidth(new Length('5pt'));
        $textFrame->getStyle()->setBorderColor(new Color('#ff0000'));
        $textFrame->getStyle()->setAlignCenter();
        $paragraph->createTextElement('Frame 4 - This text should not be wrapped.');

        $paragraph = $this->odtFile->createParagraph();
        $textFrame = $paragraph->createTextFrame();
        $textFrame->setHeight(new Length('2cm'));
        $textFrame->setWidth(new Length('2cm'));
        $textFrame->getStyle()->setBorderWidth(new Length('5pt'));
        $textFrame->getStyle()->setBorderColor(new Color('#ff0000'));
        $textFrame->getStyle()->setAlignRight();
        $paragraph->createTextElement('Frame 5 - This text should not be wrapped.');

        $paragraph = $this->odtFile->createParagraph();
        $paragraph->getStyle()->setPageBreakBefore(true);
        $paragraph->createTextElement('This paragraph has a page break before.');

        $paragraph = $this->odtFile->createParagraph();
        $textFrame = $paragraph->createTextFrame();
        $textFrame->setHeight(new Length('1.5cm'));
        $textFrame->setWidth(new Length('10cm'));
        $textFrame->getStyle()->setBorderWidth(new Length('5pt'));
        $textFrame->getStyle()->setBorderColor(new Color('#ff0000'));
        $textFrame->getStyle()->setWrapParallel();
        $paragraph->createTextElement(
            'Frame 6 - Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.'
        );


        $paragraph = $this->odtFile->createParagraph();
        $textFrame = $paragraph->createTextFrame();
        $textFrame->setHeight(new Length('1.5cm'));
        $textFrame->setWidth(new Length('10cm'));
        $textFrame->getStyle()->setBorderWidth(new Length('5pt'));
        $textFrame->getStyle()->setBorderColor(new Color('#ff0000'));
        $textFrame->getStyle()->setWrapParallel();
        $textFrame->getStyle()->setAlignCenter();
        $paragraph->createTextElement(
            'Frame 7 - Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.'
        );


        $paragraph = $this->odtFile->createParagraph();
        $textFrame = $paragraph->createTextFrame();
        $textFrame->setHeight(new Length('1.5cm'));
        $textFrame->setWidth(new Length('10cm'));
        $textFrame->getStyle()->setBorderWidth(new Length('5pt'));
        $textFrame->getStyle()->setBorderColor(new Color('#ff0000'));
        $textFrame->getStyle()->setWrapParallel();
        $textFrame->getStyle()->setAlignRight();
        $paragraph->createTextElement(
            'Frame 8 - Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.'
        );
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
