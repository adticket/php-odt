<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use Juit\PhpOdt\OdtCreator\Document\StylesFile;
use Juit\PhpOdt\OdtCreator\Value\Length;

class ImageStyle extends AbstractStyle
{
    /**
     * @var Length
     */
    private $marginTop;

    /**
     * @var Length
     */
    private $marginLeft;

    /**
     * @var Length
     */
    private $marginRight;

    /**
     * @var Length
     */
    private $marginBottom;

    public function __construct($name)
    {
        parent::__construct($name);

        $this->marginTop    = new Length('0cm');
        $this->marginLeft   = new Length('0cm');
        $this->marginRight  = new Length('0cm');
        $this->marginBottom = new Length('0cm');
    }

    /**
     * @param Length $marginTop
     */
    public function setMarginTop(Length $marginTop)
    {
        $this->marginTop = $marginTop;
    }

    /**
     * @param Length $marginLeft
     */
    public function setMarginLeft(Length $marginLeft)
    {
        $this->marginLeft = $marginLeft;
    }

    /**
     * @param Length $marginRight
     */
    public function setMarginRight(Length $marginRight)
    {
        $this->marginRight = $marginRight;
    }

    /**
     * @param Length $marginBottom
     */
    public function setMarginBottom(Length $marginBottom)
    {
        $this->marginBottom = $marginBottom;
    }

    /**
     * @param \DOMDocument $stylesDocument
     * @param \DOMElement $styleElement
     * @return void
     */
    protected function renderToStyleElement(\DOMDocument $stylesDocument, \DOMElement $styleElement)
    {
        $styleElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:family', 'graphic');
        $styleElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:parent-style-name', 'Graphics');

        $propertiesElement = $stylesDocument->createElementNS(StylesFile::NAMESPACE_STYLE, 'style:graphic-properties');
        $styleElement->appendChild($propertiesElement);

        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-left', $this->marginLeft->getValue());
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-right', $this->marginRight->getValue());
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-top', $this->marginTop->getValue());
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-bottom', $this->marginBottom->getValue());

        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:horizontal-pos', 'left');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:horizontal-rel', 'paragraph');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:vertical-pos', 'top');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:vertical-rel', 'paragraph');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:shadow', 'none');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:shadow-opacity', '100%');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:mirror', 'none');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_FO, 'fo:clip', 'rect(0cm, 0cm, 0cm, 0cm)');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:luminance', '0%');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:contrast', '0%');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:red', '0%');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:green', '0%');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:blue', '0%');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:gamma', '100%');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:color-inversion', 'false');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:image-opacity', '100%');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_DRAW, 'draw:color-mode', 'standard');
    }
}
