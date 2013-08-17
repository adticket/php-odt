<?php

namespace ODTCreator;

use ODTCreator\Document\Content;
use ODTCreator\Document\File;
use ODTCreator\Document\Manifest;
use ODTCreator\Document\Meta;
use ODTCreator\Document\Settings;
use ODTCreator\Document\Styles;
use ODTCreator\Element\Element;
use ODTCreator\Style\StyleFactory;

class ODTCreator
{
    const GENERATOR = 'PHP-ODTCreator 0.1';

    /**
     * @var StyleFactory
     */
    private $styleFactory;

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
        $this->styleFactory = new StyleFactory();
        $this->styles = new Styles($this->styleFactory);
        $this->content = new Content();
    }

    /**
     * @return \ODTCreator\Style\StyleFactory
     */
    public function getStyleFactory()
    {
        return $this->styleFactory;
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
