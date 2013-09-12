<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use Juit\PhpOdt\OdtCreator\Document\StylesFile;
use Juit\PhpOdt\OdtCreator\Value\Length;

class ParagraphStyle extends AbstractStyle
{
    /**
     * @var string|null
     */
    private $masterPageName = null;

    /**
     * @var \Juit\PhpOdt\OdtCreator\Value\Length|null
     */
    private $marginTop = null;

    /**
     * @var \Juit\PhpOdt\OdtCreator\Value\Length|null
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
     * @param \Juit\PhpOdt\OdtCreator\Value\Length $marginTop
     */
    public function setMarginTop(Length $marginTop)
    {
        $this->marginTop = $marginTop;
    }

    /**
     * @param \Juit\PhpOdt\OdtCreator\Value\Length $marginBottom
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
        $styleElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:family', 'paragraph');
        $styleElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:parent-style-name', 'Standard');

        if ($this->masterPageName) {
            $styleElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:master-page-name', $this->masterPageName);
        }

        $paragraphPropertiesElement = $stylesDocument->createElementNS(StylesFile::NAMESPACE_STYLE, 'style:paragraph-properties');
        $styleElement->appendChild($paragraphPropertiesElement);

        $paragraphPropertiesElement->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:page-number', 'auto');
        if ($this->marginTop) {
            $paragraphPropertiesElement->setAttributeNS(
                StylesFile::NAMESPACE_FO,
                'fo:margin-top',
                $this->marginTop->getValue()
            );
        }
        if ($this->marginBottom) {
            $paragraphPropertiesElement->setAttributeNS(
                StylesFile::NAMESPACE_FO,
                'fo:margin-bottom',
                $this->marginBottom->getValue()
            );
        }
    }
}
