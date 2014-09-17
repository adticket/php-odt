<?php

namespace Juit\PhpOdt\OdtCreator\Element;

use DOMDocument;
use DOMElement;
use DOMXPath;

class Header extends AbstractElementWithContent
{
    /**
     * @var int
     */
    private $outlineLevel;

    public function __construct($outlineLevel = 1)
    {
        $this->outlineLevel = $outlineLevel;
    }

    /**
     * @param DOMDocument $document
     * @param DOMElement|null $parent
     * @return void
     */
    public function renderToContent(DOMDocument $document, DOMElement $parent = null)
    {
        $header = $document->createElement('text:h');
        $header->setAttribute('text:outline-level', $this->outlineLevel);

        foreach ($this->contents as $text) {
            $text->renderTo($document, $header);
        }

        if (!$parent) {
            $xPath = new DOMXPath($document);
            $parent = $xPath->query('//office:text')->item(0);
        }
        $parent->appendChild($header);
    }
}
