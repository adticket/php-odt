<?php

namespace OdtCreator\Document;

use OdtCreator\Element\Element;
use OdtCreator\Style\StyleFactory;

class Styles implements File
{
    const NAMESPACE_FO = 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0';
    const NAMESPACE_NAME = 'urn:oasis:names:tc:opendocument:xmlns:office:1.0';
    const NAMESPACE_OFFICE = 'urn:oasis:names:tc:opendocument:xmlns:office:1.0';
    const NAMESPACE_STYLE = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
    const NAMESPACE_SVG = 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0';
    const NAMESPACE_TEXT = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';

    /**
     * @var Element[]
     */
    private $elements;

    /**
     * @var StyleFactory
     */
    private $styleFactory;

    public function __construct(StyleFactory $styleFactory)
    {
        $this->styleFactory = $styleFactory;
    }

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
        $this->styleFactory->renderAllStylesTo($domDocument);

        return $domDocument->saveXML();
    }

    private function createDOMDocument()
    {
        $domDocument = new \DOMDocument();
        $domDocument->load(__DIR__ . '/templates/styles.xml');

        return $domDocument;
    }

    /**
     * @return string The relative path of the file within the ODT structure
     */
    public function getRelativePath()
    {
        return 'styles.xml';
    }
}
