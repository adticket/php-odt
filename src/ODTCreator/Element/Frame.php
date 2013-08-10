<?php

namespace ODTCreator\Element;

use ODTCreator\Document\Content as ContentFile;
use ODTCreator\Document\Styles;
use ODTCreator\Value\Length;

class Frame implements Element
{
    /**
     * @var
     */
    private $styleName;

    /**
     * @var Length
     */
    private $x;

    /**
     * @var Length
     */
    private $y;

    public function __construct($styleName, Length $x, Length $y)
    {
        $this->styleName = $styleName;
        $this->x = $x;
        $this->y = $y;
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
    public function renderToStyle(\DOMDocument $domDocument, \DOMElement $parentElement = null)
    {
        $element = $domDocument->createElementNS(Styles::NAMESPACE_STYLE, 'style:style');
        $element->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:name', $this->styleName);
        $element->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:family', 'graphic');
        $domDocument->getElementsByTagNameNS(Styles::NAMESPACE_OFFICE, 'styles')->item(0)->appendChild($element);

        $graphicPropertiesElement = $domDocument->createElementNS(Styles::NAMESPACE_STYLE, 'style:graphic-properties');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_TEXT, 'text:anchor-type', 'paragraph');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_SVG, 'svg:anchor-type', 'paragraph');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_SVG, 'svg:x', '0cm');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_SVG, 'svg:y', '0cm');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_FO, 'fo:margin-left', '0.2cm');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_FO, 'fo:margin-right', '0.2cm');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_FO, 'fo:margin-top', '0.2cm');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_FO, 'fo:margin-bottom', '0.2cm');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:wrap', 'parallel');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:number-wrapped-paragraphs', 'no-limit');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:wrap-contour', 'false');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:vertical-pos', 'from-top');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:vertical-rel', 'page');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:horizontal-pos', 'from-left');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:horizontal-rel', 'page');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_FO, 'fo:padding', '0cm');
        $graphicPropertiesElement->setAttributeNS(Styles::NAMESPACE_FO, 'fo:border', 'none');
        $element->appendChild($graphicPropertiesElement);

        '
        <style:style style:name="Frame-1" style:family="graphic">
            <style:graphic-properties text:anchor-type="paragraph" svg:x="0cm" svg:y="0cm" fo:margin-left="0.2cm"
                                      fo:margin-right="0.2cm" fo:margin-top="0.2cm" fo:margin-bottom="0.2cm"
                                      style:wrap="parallel" style:number-wrapped-paragraphs="no-limit"
                                      style:wrap-contour="false" style:vertical-pos="from-top" style:vertical-rel="page"
                                      style:horizontal-pos="from-left" style:horizontal-rel="page" fo:padding="0cm"
                                      fo:border="none"/>
        </style:style>
        ';

    }

    /**
     * @param \DOMDocument $domDocument
     * @param \DOMElement|null $parentElement
     * @return void
     */
    public function renderToContent(\DOMDocument $domDocument, \DOMElement $parentElement = null)
    {
        $frameElement = $domDocument->createElementNS(ContentFile::NAMESPACE_DRAW, 'draw:frame');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_DRAW, 'draw:style-name', $this->styleName);
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_TEXT, 'text:anchor-type', 'page');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_TEXT, 'text:anchor-page-number', '1');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:x', $this->x->getValue());
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:y', $this->y->getValue());
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:width', '8.5cm');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_SVG, 'svg:height', '4.5cm');
        $frameElement->setAttributeNS(ContentFile::NAMESPACE_DRAW, 'draw:z-index', '0');
        $domDocument->getElementsByTagNameNS(ContentFile::NAMESPACE_OFFICE, 'text')->item(0)->appendChild($frameElement);

        $textBoxElement = $domDocument->createElementNS(ContentFile::NAMESPACE_DRAW, 'draw:text-box');
        $frameElement->appendChild($textBoxElement);

        foreach ($this->subElements as $subElement) {
            $subElement->renderToContent($domDocument, $textBoxElement);
        }
    }
}
