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
            $element->appendTo($domDocument);
        }

        return $domDocument->saveXML();
    }

    /**
     * @return \DOMDocument
     */
    private function createDOMDocument()
    {
        $domDocument = new \DOMDocument('1.0', 'UTF-8');
        $domDocument->substituteEntities = true;

        $rootElement = $domDocument->createElement('office:document-content');
        $rootElement->setAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $rootElement->setAttribute('xmlns:text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
        $rootElement->setAttribute('xmlns:draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
        $rootElement->setAttribute('xmlns:svg', 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0');
        $rootElement->setAttribute('xmlns:table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $rootElement->setAttribute('xmlns:style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
        $rootElement->setAttribute('xmlns:fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');
        $rootElement->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        $domDocument->appendChild($rootElement);

        $officeAutomaticStyles = $domDocument->createElement('office:automatic-styles');
        $rootElement->appendChild($officeAutomaticStyles);

        $officeBody = $domDocument->createElement('office:body');
        $rootElement->appendChild($officeBody);

        $officeText = $domDocument->createElement('office:text');
        $officeBody->appendChild($officeText);

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
