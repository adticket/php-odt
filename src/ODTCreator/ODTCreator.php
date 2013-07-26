<?php

namespace ODTCreator;

use ODTCreator\File\Manifest;
use ODTCreator\File\Styles;
use ODTCreator\Style\Style;

class ODTCreator
{
    const GENERATOR = 'PHP-ODTCreator 0.1';

    /**
     * @var null|ODTCreator
     */
    private static $instance = null;

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
     * @var string|null
     */
    private $creator = null;

    /**
     * @var string|null
     */
    private $title = null;

    /**
     * @var string|null
     */
    private $description = null;

    /**
     * @var string|null
     */
    private $subject = null;

    /**
     * @var array
     */
    private $keywords = array();

    /**
     * @var \DateTime
     */
    private $creationDate;

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
        $this->creationDate = new \DateTime();
        $this->styles = new Styles();
        $this->createDocumentContent();
    }

    /**
     * @return \DOMDocument
     */
    private function createMetadata()
    {
        $metadata = new \DOMDocument('1.0', 'UTF-8');

        $root = $metadata->createElement('office:document-meta');
        $root->setAttribute('xmlns:meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');
        $root->setAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $root->setAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
        $metadata->appendChild($root);

        $officeMeta = $metadata->createElement('office:meta');

        $generator = $metadata->createElement('meta:generator', self::GENERATOR);
        $officeMeta->appendChild($generator);

        $creationDate = $metadata->createElement(
            'meta:creation-date',
            $this->creationDate->format('Y-m-d\TH:i:s')
        );
        $officeMeta->appendChild($creationDate);

        if (null !== $this->creator) {
            $creatorElement = $metadata->createElement('meta:initial-creator', $this->creator);
            $officeMeta->appendChild($creatorElement);
        }

        if (null !== $this->title) {
            $titleElement = $metadata->createElement('dc:title', $this->title);
            $officeMeta->appendChild($titleElement);
        }

        if (null !== $this->description) {
            $descriptionElement = $metadata->createElement('dc:description', $this->description);
            $officeMeta->appendChild($descriptionElement);
        }

        if (null !== $this->subject) {
            $subjectElement = $metadata->createElement('dc:subject', $this->subject);
            $officeMeta->appendChild($subjectElement);
        }

        foreach ($this->keywords as $keyword) {
            $keywordElement = $metadata->createElement('meta:keyword', $keyword);
            $officeMeta->appendChild($keywordElement);
        }

        $root->appendChild($officeMeta);

        return $metadata;
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
     * Sets the title of the document
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Sets a description for the document
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Sets the subject of the document
     *
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Sets the keywords related to the document
     *
     * @param array $keywords
     */
    public function setKeywords(array $keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Specifies the name of the person who created the document initially
     *
     * @param string $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
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

        $document->addFromString('meta.xml', $this->createMetadata()->saveXML());
        $document->addFromString('content.xml', $this->documentContent->saveXML());

        $document->close();
    }
}
