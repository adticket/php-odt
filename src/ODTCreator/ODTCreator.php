<?php

namespace ODTCreator;

use ODTCreator\Document\Content;
use ODTCreator\Document\Manifest;
use ODTCreator\Document\Meta;
use ODTCreator\Document\Styles;
use ODTCreator\Style\TextStyle;

class ODTCreator
{
    const GENERATOR = 'PHP-ODTCreator 0.1';

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

    public function __construct()
    {
        $this->styles = new Styles();
        $this->content = new Content();
    }

    /**
     * @param Meta $meta
     */
    public function setMeta(Meta $meta)
    {
        $this->meta = $meta;
    }

    /**
     * @return Meta
     */
    private function getMeta()
    {
        if (null === $this->meta) {
            $this->meta = new Meta();
        }
        return $this->meta;
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

        // TODO: Check for usage of styles that were not added or have them created and managed by a factory
        $content = $this->content;
        $document->addFromString($content->getRelativePath(), $content->render());

        $document->close();
    }

    public function addTextStyle(TextStyle $textStyle)
    {
        $this->styles->addTextStyle($textStyle);
    }
}
