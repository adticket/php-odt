<?php

namespace OdtCreator;

//TODO: Create Bundle for that

use OdtCreator\Document\Content;
use OdtCreator\Document\File;
use OdtCreator\Document\Manifest;
use OdtCreator\Document\Meta;
use OdtCreator\Document\Settings;
use OdtCreator\Document\Styles;
use OdtCreator\Element\Element;
use OdtCreator\Style\StyleFactory;

class OdtCreator
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
        $this->reset();
    }

    public function reset()
    {
        $this->styleFactory = new StyleFactory();
        $this->styles = new Styles($this->styleFactory);
        $this->content = new Content();
    }

    /**
     * @return \OdtCreator\Style\StyleFactory
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
