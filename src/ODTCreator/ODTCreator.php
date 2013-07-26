<?php

namespace ODTCreator;

use ODTCreator\File\Content;
use ODTCreator\File\Manifest;
use ODTCreator\File\Meta;
use ODTCreator\File\Styles;
use ODTCreator\Style\TextStyle;

class ODTCreator
{
    const GENERATOR = 'PHP-ODTCreator 0.1';

    /**
     * @var null|ODTCreator
     */
    private static $instance = null;

    /**
     * @var Meta
     */
    private $meta;

    /**
     * @var Styles
     */
    private $styles;

    /**
     * @var Content
     */
    private $content;

    /**
     * @return ODTCreator
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new ODTCreator();
        }
        return self::$instance;
    }

    public static function resetInstance()
    {
        // TODO: Refactor the whole lib and get rid of this singleton crap
        self::$instance = null;
    }

    private function __construct()
    {
        $this->styles = new Styles();
        $this->content = new Content();
    }

    /**
     * @param \ODTCreator\File\Meta $meta
     */
    public function setMeta(Meta $meta)
    {
        $this->meta = $meta;
    }

    /**
     * @return \ODTCreator\File\Meta
     */
    private function getMeta()
    {
        if (null === $this->meta) {
            $this->meta = new Meta();
        }
        return $this->meta;
    }

    /**
     * @return \DOMDocument The document containing all the styles
     */
    public function getStyleDocument()
    {
        // TODO: Remove this method as soon as all its users are refactored
        return $this->styles->getDOMDocument();
    }

    /**
     * @return \DOMDocument
     */
    public function getContentDocument()
    {
        // TODO: Remove this method as soon as all its users are refactored
        return $this->content->getDOMDocument();
    }

    public function addParagraph(Paragraph $paragraph)
    {
        $this->content->addParagraph($paragraph);
        // TODO: Register style?
    }

    public function save(\SplFileInfo $targetFile)
    {
        $document = new \ZipArchive();
        $document->open($targetFile->getPathname(), \ZipArchive::OVERWRITE);

        $manifest = new Manifest();
        $document->addFromString($manifest->getRelativePath(), $manifest->render());

        $styles = $this->styles;
        $document->addFromString($styles->getRelativePath(), $styles->render());

        $meta = $this->getMeta();
        $document->addFromString($meta->getRelativePath(), $meta->render());

        // TODO: Check for usage of styles that were not added
        $content = $this->content;
        $document->addFromString($content->getRelativePath(), $content->render());

        $document->close();
    }

    public function addTextStyle(TextStyle $textStyle)
    {
        $this->styles->addTextStyle($textStyle);
    }
}
