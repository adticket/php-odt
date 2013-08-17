<?php

namespace ODTCreator\Style;

use ODTCreator\Document\Styles;
use ODTCreator\Value\Length;

class ParagraphStyle extends AbstractStyle
{
    /**
     * @var string|null
     */
    private $masterPageName = null;

    /**
     * @var Length|null
     */
    private $marginTop = null;

    /**
     * @var Length|null
     */
    private $marginBottom = null;

    /**
     * @param string $masterPageName
     */
    public function setMasterPageName($masterPageName)
    {
        $this->masterPageName = $masterPageName;
    }

    /**
     * @param Length $marginTop
     */
    public function setMarginTop(Length $marginTop)
    {
        $this->marginTop = $marginTop;
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
        $styleElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:family', 'paragraph');
        $styleElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:parent-style-name', 'Standard');

        if ($this->masterPageName) {
            $styleElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:master-page-name', $this->masterPageName);
        }

        $paragraphPropertiesElement = $stylesDocument->createElementNS(Styles::NAMESPACE_STYLE, 'style:paragraph-properties');
        $styleElement->appendChild($paragraphPropertiesElement);

        $paragraphPropertiesElement->setAttributeNS(Styles::NAMESPACE_STYLE, 'style:page-number', 'auto');
        if ($this->marginTop) {
            $paragraphPropertiesElement->setAttributeNS(
                Styles::NAMESPACE_FO,
                'fo:margin-top',
                $this->marginTop->getValue()
            );
        }
        if ($this->marginBottom) {
            $paragraphPropertiesElement->setAttributeNS(
                Styles::NAMESPACE_FO,
                'fo:margin-bottom',
                $this->marginBottom->getValue()
            );
        }
    }
}
