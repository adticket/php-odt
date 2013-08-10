<?php

namespace ODTCreator\Document;

use ODTCreator\Element\Element;

class Content implements File
{
    const NAMESPACE_DRAW = 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0';
    const NAMESPACE_OFFICE = 'urn:oasis:names:tc:opendocument:xmlns:office:1.0';
    const NAMESPACE_SVG = 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0';
    const NAMESPACE_TEXT = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
    const NAMESPACE_XLINK = 'http://www.w3.org/1999/xlink';

    /**
     * @var \ODTCreator\Element\Element[]
     */
    private $elements = array();

    public function addElement(Element $element)
    {
        $this->elements[] = $element;
    }

    /**
     * @return string The file content
     */
    public function render()
    {
        $domDocument = $this->createDOMDocument();

        foreach ($this->elements as $element) {
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
