<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use Juit\PhpOdt\OdtCreator\Document\StylesFile;

class ImageStyle extends AbstractStyle
{
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

        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:horizontal-pos', 'left');
        $propertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:horizontal-rel', 'paragraph');
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
