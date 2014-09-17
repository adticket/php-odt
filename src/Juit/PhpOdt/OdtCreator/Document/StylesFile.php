<?php

namespace Juit\PhpOdt\OdtCreator\Document;

use Juit\PhpOdt\OdtCreator\Style\StyleFactory;

class StylesFile implements File
{
    const NAMESPACE_DRAW = 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0';
    const NAMESPACE_FO = 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0';
    const NAMESPACE_NAME = 'urn:oasis:names:tc:opendocument:xmlns:office:1.0';
    const NAMESPACE_OFFICE = 'urn:oasis:names:tc:opendocument:xmlns:office:1.0';
    const NAMESPACE_STYLE = 'urn:oasis:names:tc:opendocument:xmlns:style:1.0';
    const NAMESPACE_SVG = 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0';
    const NAMESPACE_TEXT = 'urn:oasis:names:tc:opendocument:xmlns:text:1.0';

    /**
     * @var \Juit\PhpOdt\OdtCreator\Style\StyleFactory
     */
    private $styleFactory;

    public function __construct(StyleFactory $styleFactory)
    {
        $this->styleFactory = $styleFactory;
    }

    /**
     * @return string The file content
     */
    public function render()
    {
        $domDocument = $this->createDOMDocument();
        $this->styleFactory->renderStyles($domDocument);

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
