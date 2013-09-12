<?php

namespace OdtCreator\Test\EndToEnd;

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
use Juit\PhpOdt\PdfToPngRenderer\PdfToPngRenderer;
use Juit\ShellCommandExecutor\ShellCommandExecutor;

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

    /**
     * @var ShellCommandExecutor
     */
    private $shellCommandExecutor;

    public function __construct(\SplFileinfo $outputDirInfo)
    {
        $this->outputDirInfo = $outputDirInfo;
        $this->odtFile = new OdtFile();
        $this->styleFactory = $this->odtFile->getStyleFactory();
        $this->shellCommandExecutor = new ShellCommandExecutor();
    }

    public function build()
    {
        $odtFileInfo = new \SplFileInfo($this->outputDirInfo->getPathname() . '/example.odt');
        $pdfFileInfo = new \SplFileInfo($this->outputDirInfo->getPathname() . '/example.pdf');

        $this->cleanUp();
        $this->createOdtFile($odtFileInfo);
        $this->renderPdf($odtFileInfo, $pdfFileInfo);
        $this->renderPngs($pdfFileInfo);
    }

    private function cleanUp()
    {
        if ($this->outputDirInfo->isDir()) {
            $this->shellCommandExecutor->execute("rm -fr {$this->outputDirInfo->getPathname()}/*");
        }
    }

    /**
     * @param \SplFileInfo $odtFileInfo
     */
    private function createOdtFile(\SplFileInfo $odtFileInfo)
    {
        $this->addAddressFrame();
        $this->addDateFrame();
        $this->addSubject();
        $this->addSalutation();
        $this->addContent();
        $this->addRegards();

        // Render to ODT
        $this->odtFile->save($odtFileInfo);

        $unzipDir = substr($odtFileInfo->getPathname(), 0, -4);
        $this->shellCommandExecutor->execute("rm -fr {$unzipDir}");
        $this->shellCommandExecutor->execute("unzip {$odtFileInfo->getPathname()} -d {$unzipDir}");

        $this->validateOdtFile($odtFileInfo);
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

        $xCoordinate = new Length('11cm');
        $yCoordinate = new Length('8cm');
        $width = new Length('8cm');
        $height = new Length('2cm');
        $dateFrame = new Frame($frameStyle, $xCoordinate, $yCoordinate, $width, $height);

        $paragraph = new Paragraph();
        $content = new Text(
            'Musterdorf, den ' . date('d.m.Y') . ' um ' . date('H:i:s') . ' Uhr',
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
        return 'überall dieselbe alte Leier. Das Layout ist fertig, der Text lässt auf sich warten.
Damit das Layout nun nicht nackt im Raume steht und sich klein und leer vorkommt, springe ich ein: der Blindtext. Genau zu diesem Zwecke erschaffen, immer im Schatten meines großen Bruders »Lorem Ipsum«, freue ich mich jedes Mal, wenn Sie ein paar Zeilen lesen. Denn esse est percipi - Sein ist wahrgenommen werden. Und weil Sie nun schon die Güte haben, mich ein paar weitere Sätze lang zu begleiten, möchte ich diese Gelegenheit nutzen, Ihnen nicht nur als Lückenfüller zu dienen, sondern auf etwas hinzuweisen, das es ebenso verdient wahrgenommen zu werden: Webstandards nämlich.
Sehen Sie, Webstandards sind das Regelwerk, auf dem Webseiten aufbauen. So gibt es Regeln für HTML, CSS, JavaScript oder auch XML; Worte, die Sie vielleicht schon einmal von Ihrem Entwickler gehört haben. Diese Standards sorgen dafür, dass alle Beteiligten aus einer Webseite den größten Nutzen ziehen. Im Gegensatz zu früheren Webseiten müssen wir zum Beispiel nicht mehr zwei verschiedene Webseiten für den Internet Explorer und einen anderen Browser programmieren. Es reicht eine Seite, die - richtig angelegt - sowohl auf verschiedenen Browsern im Netz funktioniert, aber ebenso gut für den Ausdruck oder die Darstellung auf einem Handy geeignet ist. Wohlgemerkt: Eine Seite für alle Formate. Was für eine Erleichterung. Standards sparen Zeit bei den Entwicklungskosten und sorgen dafür, dass sich Webseiten später leichter pflegen lassen. Natürlich nur dann, wenn sich alle an diese Standards halten. Das gilt für Browser wie Firefox, Opera, Safari und den Internet Explorer ebenso wie für die Darstellung in Handys.
Und was können Sie für Standards tun? Fordern Sie von Ihren Designern und Programmieren einfach standardkonforme Webseiten. Ihr Budget wird es Ihnen auf Dauer danken. Ebenso möchte ich Ihnen dafür danken, dass Sie mich bin zum Ende gelesen haben. Meine Mission ist erfüllt. Ich werde hier noch die Stellung halten, bis der geplante Text eintrifft. Ich wünsche Ihnen noch einen schönen Tag. Und arbeiten Sie nicht zuviel!';
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
     */
    private function validateOdtFile(\SplFileInfo $odtFileInfo)
    {
        // TODO: This
        $command =
            "cd " . __DIR__ . "/../../../../bin/odf-validator && "
            . "./validator --file=" . $odtFileInfo->getPathname();
        $this->shellCommandExecutor->execute($command);
    }

    /**
     * @param \SplFileInfo $odtFileInfo
     * @param \SplFileInfo $pdfFileInfo
     * @return \SplFileInfo
     */
    private function renderPdf(\SplFileInfo $odtFileInfo, \SplFileInfo $pdfFileInfo)
    {
        // TODO: Make configurable
        $libreOfficeBinary = new \SplFileInfo('/usr/bin/soffice');
        $pdfRenderer = new InstantRenderer($libreOfficeBinary);
        $pdfRenderer->render($odtFileInfo, $pdfFileInfo);
    }

    /**
     * @param \SplFileInfo $pdfFileInfo
     */
    private function renderPngs(\SplFileInfo $pdfFileInfo)
    {
        // TODO: Make configurable
        $ghostscriptBinary = new \SplFileInfo('/usr/bin/gs');
        $pngRenderer = new PdfToPngRenderer($ghostscriptBinary);
        $pngRenderer->render($pdfFileInfo);
    }
}
