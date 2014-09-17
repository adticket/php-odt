<?php

namespace Juit\PhpOdt\OdtCreator\Element;

use DOMDocument;
use DOMElement;
use Juit\PhpOdt\OdtCreator\Style\GraphicStyle;
use Juit\PhpOdt\OdtCreator\Style\StyleFactory;
use Juit\PhpOdt\OdtCreator\Value\Length;

class Frame implements Element
{
    /**
     * @var \Juit\PhpOdt\OdtCreator\Style\GraphicStyle
     */
    private $graphicStyle;

    /**
     * @var StyleFactory
     */
    private $styleFactory;

    /**
     * @var Length
     */
    private $xCoordinate;

    /**
     * @var \Juit\PhpOdt\OdtCreator\Value\Length
     */
    private $yCoordinate;

    /**
     * @var \Juit\PhpOdt\OdtCreator\Value\Length
     */
    private $width;

    /**
     * @var \Juit\PhpOdt\OdtCreator\Value\Length
     */
    private $height;

    public function __construct(
        GraphicStyle $graphicStyle,
        StyleFactory $styleFactory,
        Length $xCoordinate,
        Length $yCoordinate,
        Length $width,
        Length $height
    ) {
        $this->graphicStyle = $graphicStyle;
        $this->styleFactory = $styleFactory;
        $this->xCoordinate  = $xCoordinate;
        $this->yCoordinate  = $yCoordinate;
        $this->width        = $width;
        $this->height       = $height;
    }

    /**
     * @var Element[]
     */
    protected $subElements = array();

    /**
     * @return Paragraph
     */
    public function createParagraph()
    {
        $paragraph           = new Paragraph($this->styleFactory);
        $this->subElements[] = $paragraph;

        return $paragraph;
    }

    /**
     * @param DOMDocument $document
     * @param DOMElement|null $parent
     * @return void
     */
    public function renderToContent(DOMDocument $document, DOMElement $parent = null)
    {
        $frame = $document->createElement('draw:frame');
        $frame->setAttribute('draw:style-name', $this->graphicStyle->getStyleName());
        $frame->setAttribute('text:anchor-type', 'page');
        $frame->setAttribute('text:anchor-page-number', '1');
        $frame->setAttribute('svg:x', $this->xCoordinate->getValue());
        $frame->setAttribute('svg:y', $this->yCoordinate->getValue());
        $frame->setAttribute('svg:width', $this->width->getValue());
        $frame->setAttribute('svg:height', $this->height->getValue());
        $frame->setAttribute('draw:z-index', '0');

        $xPath = new \DOMXPath($document);
        $xPath->query('//office:text')->item(0)->appendChild($frame);

        $textBox = $document->createElement('draw:text-box');
        $frame->appendChild($textBox);

        foreach ($this->subElements as $subElement) {
            $subElement->renderToContent($document, $textBox);
        }
    }
}
