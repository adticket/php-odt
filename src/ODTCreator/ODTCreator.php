<?php

namespace ODTCreator;

use ODTCreator\Document\Content;
use ODTCreator\Document\File;
use ODTCreator\Document\Manifest;
use ODTCreator\Document\Meta;
use ODTCreator\Document\Settings;
use ODTCreator\Document\Styles;
use ODTCreator\Element\Element;

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

    public function addElement(Element $element)
    {
        $this->styles->addElement($element);
        $this->content->addElement($element);
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
}
