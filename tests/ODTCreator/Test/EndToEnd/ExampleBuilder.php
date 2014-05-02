<?php

namespace OdtCreator\Test\EndToEnd;

use JUIT\PdfUtil\PdfToImageRenderer;
use Juit\PhpOdt\OdtCreator\Content\LineBreak;
use Juit\PhpOdt\OdtCreator\Content\Text;
use Juit\PhpOdt\OdtCreator\Element\Frame;
use Juit\PhpOdt\OdtCreator\Element\Paragraph;
use Juit\PhpOdt\OdtCreator\OdtFile;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use Juit\PhpOdt\OdtCreator\Style\TextStyle;
use Juit\PhpOdt\OdtCreator\Value\FontSize;
use Juit\PhpOdt\OdtCreator\Value\Length;
use Juit\PhpOdt\OdtToPdfRenderer\InstantRenderer;
use Symfony\Component\Process\Process;

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
        $this->odtFile = new OdtFile();
        $this->styleFactory = $this->odtFile->getStyleFactory();
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
        $this->addRegards();

        $this->odtFile->save($odtFileInfo);
    }

    private function setPageBorders()
    {
        $this->styleFactory->setMarginRight(new Length('2cm'));
    }

    private function addAddressFrame()
    {
        $frameStyle = $this->styleFactory->createGraphicStyle();
        $textStyle = $this->createDefaultTextStyle();

        $xCoordinate = new Length('2cm');
        $yCoordinate = new Length('2.7cm');
        $width = new Length('8.5cm');
        $height = new Length('4.5cm');
        $addressFrame = new Frame($frameStyle, $xCoordinate, $yCoordinate, $width, $height);

        $paragraph = new Paragraph();
        $paragraph->addContent(new Text('Mustermann GmbH', $textStyle));
        $paragraph->addContent(new LineBreak());
        $paragraph->addContent(new Text('Herr Max Mustermann', $textStyle));
        $paragraph->addContent(new LineBreak());
        $paragraph->addContent(new Text('Musterstr. 1', $textStyle));
        $paragraph->addContent(new LineBreak());
        $paragraph->addContent(new LineBreak());
        $paragraph->addContent(new Text('12345 Musterstadt', $textStyle));

        $addressFrame->addSubElement($paragraph);
        $this->odtFile->addElement($addressFrame);
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
        $frameStyle = $this->styleFactory->createGraphicStyle();

        $xCoordinate = new Length('14cm');
        $yCoordinate = new Length('8cm');
        $width = new Length('5cm');
        $height = new Length('2cm');
        $dateFrame = new Frame($frameStyle, $xCoordinate, $yCoordinate, $width, $height);

        $paragraph = new Paragraph();
        $content = new Text(
            'Musterdorf, den 02.05.2014',
            $this->createDefaultTextStyle());
        $paragraph->addContent($content);

        $dateFrame->addSubElement($paragraph);
        $this->odtFile->addElement($dateFrame);
    }

    private function addSubject()
    {
        $paragraphStyle = $this->styleFactory->createParagraphStyle();

        // TODO: This must not be required explicitely
        $paragraphStyle->setMasterPageName('First_20_Page');
        $paragraphStyle->setMarginBottom(new Length('24pt'));
        $paragraph = new Paragraph($paragraphStyle);

        $textStyle = $this->createDefaultTextStyle();
        $textStyle->setBold();
        $text = new Text('Ihr Schreiben', $textStyle);

        $paragraph->addContent($text);
        $this->odtFile->addElement($paragraph);
    }

    private function addSalutation()
    {
        $paragraph = new Paragraph($this->createDefaultParagraphStyle());

        $paragraph->addContent(new Text('Sehr geehrter Herr Mustermann,', $this->createDefaultTextStyle()));

        $this->odtFile->addElement($paragraph);
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
        $dummyText = $this->getDummyText();
        $paragraphs = $this->createParagraphs($dummyText);

        foreach ($paragraphs as $paragraph) {
            $this->odtFile->addElement($paragraph);
        }
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
     * @return \Juit\PhpOdt\OdtCreator\Element\Paragraph[]
     */
    private function createParagraphs($dummyText)
    {
        $paragraphs = array();
        $textBlocks = explode("\n", $dummyText);
        $defaultTextStyle = $this->createDefaultTextStyle();
        $paragraphStyle = $this->createDefaultParagraphStyle();
        foreach ($textBlocks as $text) {
            $paragraph = new Paragraph($paragraphStyle);
            $paragraph->addContent(new Text($text, $defaultTextStyle));
            $paragraphs[] = $paragraph;
        }

        return $paragraphs;
    }

    private function addRegards()
    {
        $paragraph = new Paragraph();
        $paragraph->addContent(new Text('Mit freundlichen Grüßen', $this->createDefaultTextStyle()));

        $this->odtFile->addElement($paragraph);
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
