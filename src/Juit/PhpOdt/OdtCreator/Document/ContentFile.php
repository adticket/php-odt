<?php

namespace Juit\PhpOdt\OdtCreator\Document;

use Juit\PhpOdt\OdtCreator\Element\Element;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;

class ContentFile implements File
{
    const NAMESPACE_DRAW = 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0';
    const NAMESPACE_OFFICE = 'urn:oasis:names:tc:opendocument:xmlns:office:1.0';
    const NAMESPACE_SVG = 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0';
    const NAMESPACE_TEXT = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';
    const NAMESPACE_XLINK = 'http://www.w3.org/1999/xlink';

    /**
     * @var \Juit\PhpOdt\OdtCreator\Element\Element[]
     */
    private $elements = array();

    /**
     * @var StyleFactory
     */
    private $styleFactory;

    public function __construct(StyleFactory $styleFactory)
    {
        $this->styleFactory = $styleFactory;
    }

    /**
     * @param Element[] $elements
     */
    public function setElements(array $elements)
    {
        $this->elements = $elements;
    }

    /**
     * @return string The file content
     */
    public function render()
    {
        $domDocument = $this->createDOMDocument();

        foreach ($this->elements as $element) {
            $element->renderToContent($domDocument);
        }

        $this->styleFactory->renderToContentFile($domDocument);

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
