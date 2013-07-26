<?php

namespace ODTCreator\File;

use ODTCreator\ODTCreator;

class Meta implements FileInterface
{
    /**
     * @var \DateTime
     */
    protected $creationDate;

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

    public function __construct()
    {
        $this->creationDate = new \DateTime();
    }

    /**
     * @return string The file content
     */
    public function render()
    {
        $metadata = new \DOMDocument('1.0', 'UTF-8');

        $root = $metadata->createElement('office:document-meta');
        $root->setAttribute('xmlns:meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');
        $root->setAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $root->setAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
        $metadata->appendChild($root);

        $officeMeta = $metadata->createElement('office:meta');

        $generator = $metadata->createElement('meta:generator', ODTCreator::GENERATOR);
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

        return $metadata->saveXML();
    }

    /**
     * @return string The relative path of the file within the ODT structure
     */
    public function getRelativePath()
    {
        return 'meta.xml';
    }

    /**
     * @param string $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @param array $keywords
     */
    public function setKeywords(array $keywords)
    {
        $this->keywords = $keywords;
    }
}
