<?php

namespace ODTCreator;

use ODTCreator\Document\Content;
use ODTCreator\Document\File;
use ODTCreator\Document\Manifest;
use ODTCreator\Document\Meta;
use ODTCreator\Document\Settings;
use ODTCreator\Document\Styles;
use ODTCreator\Style\TextStyle;

class ODTCreator
{
    const GENERATOR = 'PHP-ODTCreator 0.1';

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

    public function addParagraph(Paragraph $paragraph)
    {
        $this->content->addParagraph($paragraph);
    }

    public function save(\SplFileInfo $targetFile)
    {
        $document = new \ZipArchive();
        $document->open($targetFile->getPathname(), \ZipArchive::OVERWRITE);

        $files = array(
            new Manifest(),
            $this->content,
            new Meta(),
            new Settings(),
            $this->styles
        );
        foreach ($files as $file) {
            /** @var $file File */
            $document->addFromString($file->getRelativePath(), $file->render());
        }

        $document->close();
    }

    public function addTextStyle(TextStyle $textStyle)
    {
        $this->styles->addTextStyle($textStyle);
    }
}
