<?php

namespace OdtCreator\Element;

use OdtCreator\Document\Content as ContentFile;
use OdtCreator\Document\Styles;
use OdtCreator\Style\GraphicStyle;
use OdtCreator\Value\Length;

class Frame implements Element
{
    /**
     * @var GraphicStyle
     */
    private $graphicStyle;

    /**
     * @var Length
     */
    private $xCoordinate;

    /**
     * @var Length
     */
    private $yCoordinate;

    /**
     * @var \OdtCreator\Value\Length
     */
    private $width;

    /**
     * @var \OdtCreator\Value\Length
     */
    private $height;

    public function __construct(
        GraphicStyle $graphicStyle,
        Length $xCoordinate,
        Length $yCoordinate,
        Length $width,
        Length $height
    )
    {
        $this->graphicStyle = $graphicStyle;
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
     * @param Element $element
     */
    public function addSubElement(Element $element)
    {
        $this->subElements[] = $element;
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
