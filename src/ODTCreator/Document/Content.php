<?php

namespace ODTCreator\Document;

use ODTCreator\Paragraph;

class Content implements File
{
    /**
     * @var Paragraph[]
     */
    private $contentElements = array();

    public function addParagraph(Paragraph $paragraph)
    {
        $this->contentElements[] = $paragraph;
    }

    /**
     * @return string The file content
     */
    public function render()
    {
        $domDocument = $this->createDOMDocument();

        foreach ($this->contentElements as $element) {
            $element->renderTo($domDocument);
        }

        return $domDocument->saveXML();
    }

    /**
     * @return \DOMDocument
     */
    private function createDOMDocument()
    {
        $domDocument = new \DOMDocument('1.0', 'UTF-8');
        $domDocument->load(__DIR__ . '/templates/content.xml');

        return $domDocument;
    }

    /**
     * @return string The relative path of the file within the ODT structure
     */
    public function getRelativePath()
    {
        return 'content.xml';
    }
}
