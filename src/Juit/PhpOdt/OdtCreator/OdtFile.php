<?php

namespace Juit\PhpOdt\OdtCreator;

use Juit\PhpOdt\OdtCreator\Document\ContentFile;
use Juit\PhpOdt\OdtCreator\Document\File;
use Juit\PhpOdt\OdtCreator\Document\ManifestFile;
use Juit\PhpOdt\OdtCreator\Document\MetaFile;
use Juit\PhpOdt\OdtCreator\Document\SettingsFile;
use Juit\PhpOdt\OdtCreator\Document\StylesFile;
use Juit\PhpOdt\OdtCreator\Element\ElementFactory;
use Juit\PhpOdt\OdtCreator\Element\Frame;
use Juit\PhpOdt\OdtCreator\Style\ParagraphStyle;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use Juit\PhpOdt\OdtCreator\Style\TextStyle;
use Juit\PhpOdt\OdtCreator\Value\Length;

class OdtFile
{
    const GENERATOR = 'PHP-ODTCreator 0.1';

    /**
     * @var StyleFactory
     */
    private $styleFactory;

    /**
     * @var ElementFactory
     */
    private $elementFactory;

    /**
     * @var StylesFile
     */
    private $styles;

    /**
     * @var ContentFile
     */
    private $content;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->styleFactory   = new StyleFactory();
        $this->elementFactory = new ElementFactory($this->styleFactory);
        $this->styles         = new StylesFile($this->styleFactory);
        $this->content        = new ContentFile($this->styleFactory);
    }

    /**
     * @return Style\PageStyle
     */
    public function getPageStyle()
    {
        return $this->styleFactory->getPageStyle();
    }

    /**
     * @return TextStyle
     */
    public function getDefaultTextStyle()
    {
        return $this->styleFactory->getDefaultTextStyle();
    }

    /**
     * @return ParagraphStyle
     */
    public function getDefaultParagraphStyle()
    {
        return $this->styleFactory->getDefaultParagraphStyle();
    }

    /**
     * @param Length $xCoordinate
     * @param Length $yCoordinate
     * @param Length $width
     * @param Length $height
     * @return Frame
     */
    public function createFrame(Length $xCoordinate, Length $yCoordinate, Length $width, Length $height)
    {
        return $this->elementFactory->createFrame($xCoordinate, $yCoordinate, $width, $height);
    }

    public function createParagraph()
    {
        return $this->elementFactory->createParagraph();
    }

    public function save(\SplFileInfo $targetFile)
    {
        $document = new \ZipArchive();
        $document->open($targetFile->getPathname(), \ZipArchive::OVERWRITE);

        $this->content->setElements($this->elementFactory->getElements());

        $files = array(
            new ManifestFile(),
            $this->content,
            new MetaFile(),
            new SettingsFile(),
            $this->styles
        );
        foreach ($files as $file) {
            /** @var $file File */
            $document->addFromString($file->getRelativePath(), $file->render());
        }

        $document->close();
    }
}
