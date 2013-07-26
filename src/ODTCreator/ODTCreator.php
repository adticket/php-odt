<?php

namespace ODTCreator;

use ODTCreator\File\Manifest;
use ODTCreator\File\Meta;
use ODTCreator\File\Styles;

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
     * @var \DOMDocument
     */
    private $documentContent;

    /**
     * @var \DOMElement
     */
    private $officeText;

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
        $this->createDocumentContent();
    }

    private function createDocumentContent()
    {
        $this->documentContent = new \DOMDocument('1.0', 'UTF-8');
        $this->documentContent->substituteEntities = true;

        $root = $this->documentContent->createElement('office:document-content');
        $root->setAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $root->setAttribute('xmlns:text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
        $root->setAttribute('xmlns:draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
        $root->setAttribute('xmlns:svg', 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0');
        $root->setAttribute('xmlns:table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $root->setAttribute('xmlns:style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
        $root->setAttribute('xmlns:fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');
        $root->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        $this->documentContent->appendChild($root);

        $officeAutomaticStyles = $this->documentContent->createElement('office:automatic-styles');
        $root->appendChild($officeAutomaticStyles);

        $officeBody = $this->documentContent->createElement('office:body');
        $root->appendChild($officeBody);

        $this->officeText = $this->documentContent->createElement('office:text');
        $officeBody->appendChild($this->officeText);
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
    public function getDocumentContent()
    {
        return $this->documentContent;
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

        $document->addFromString('content.xml', $this->documentContent->saveXML());

        $document->close();
    }
}
