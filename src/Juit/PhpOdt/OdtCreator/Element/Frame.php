<?php

namespace Juit\PhpOdt\OdtCreator\Element;

use Juit\PhpOdt\OdtCreator\Document\ContentFile;
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
    )
    {
        $this->graphicStyle = $graphicStyle;
        $this->styleFactory = $styleFactory;
        $this->xCoordinate = $xCoordinate;
        $this->yCoordinate = $yCoordinate;
        $this->width = $width;
        $this->height = $height;
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
        $paragraph = new Paragraph($this->styleFactory);
        $this->subElements[] = $paragraph;

        return $paragraph;
    }

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderToContent(\DOMDocument $domDocument, \DOMElement $parentElement = null)
    {
        $frameElement = $domDocument->createElementNS(ContentFile::NAMESPACE_DRAW, 'draw:frame');
        $frameElement->setAttributeNS(
            ContentFile::NAMESPACE_DRAW,
            'draw:style-name',
            $this->graphicStyle->getStyleName()
        );
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_TEXT, 'text:anchor-type', 'page');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_TEXT, 'text:anchor-page-number', '1');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:x', $this->xCoordinate->getValue());
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:y', $this->yCoordinate->getValue());
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:width', $this->width->getValue());
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:height', $this->height->getValue());
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_DRAW, 'draw:z-index', '0');
        $domDocument->getElementsByTagNameNS(ContentFile::NAMESPACE_OFFICE, 'text')->item(0)->appendChild($frameElement);

        $textBoxElement = $domDocument->createElementNS(ContentFile::NAMESPACE_DRAW, 'draw:text-box');
        $frameElement->appendChild($textBoxElement);

        foreach ($this->subElements as $subElement) {
            $subElement->renderToContent($domDocument, $textBoxElement);
        }
    }
}
