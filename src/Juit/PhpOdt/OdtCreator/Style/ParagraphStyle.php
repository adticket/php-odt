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
     * @param ParagraphStyle $source
     * @param string $destinationName
     * @return ParagraphStyle
     */
    public static function copy(ParagraphStyle $source, $destinationName)
    {
        $destination = new self($destinationName);

        $destination->masterPageName = $source->masterPageName;
        $destination->marginTop      = $source->marginTop ? clone $source->marginTop : null;
        $destination->marginBottom   = $source->marginBottom ? clone $source->marginBottom : null;

        return $destination;
    }

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

    public function renderStyles(\DOMDocument $document, \DOMElement $parent)
    {
        $style = $this->createDefaultStyleElement($document, $parent);
        $style->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:family', 'paragraph');
        $style->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:parent-style-name', 'Standard');
        if ($this->masterPageName) {
            $style->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:master-page-name', $this->masterPageName);
        }

        $paragraphProperties = $document->createElementNS(StylesFile::NAMESPACE_STYLE, 'style:paragraph-properties');
        $style->appendChild($paragraphProperties);
        $paragraphProperties->setAttributeNS(StylesFile::NAMESPACE_STYLE, 'style:page-number', 'auto');
        if ($this->marginTop) {
            $paragraphProperties->setAttributeNS(
                StylesFile::NAMESPACE_FO,
                'fo:margin-top',
                $this->marginTop->getValue()
            );
        }
        if ($this->marginBottom) {
            $paragraphProperties->setAttributeNS(
                StylesFile::NAMESPACE_FO,
                'fo:margin-bottom',
                $this->marginBottom->getValue()
            );
        }
    }
}
