<?php

namespace Juit\PhpOdt\OdtCreator\Style;

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
     * @var bool
     */
    private $pageBreakBefore = false;

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

    /**
     * @param boolean $pageBreakBefore
     */
    public function setPageBreakBefore($pageBreakBefore)
    {
        $this->pageBreakBefore = $pageBreakBefore;
    }

    public function renderStyles(\DOMDocument $document, \DOMElement $parent)
    {
        $style = $this->createDefaultStyleElement($document, $parent);
        $style->setAttribute('style:family', 'paragraph');
        $style->setAttribute('style:parent-style-name', 'Standard');
        if ($this->masterPageName) {
            $style->setAttribute('style:master-page-name', $this->masterPageName);
        }

        $paragraphProperties = $document->createElement('style:paragraph-properties');
        $style->appendChild($paragraphProperties);
        $paragraphProperties->setAttribute('style:page-number', 'auto');
        if ($this->marginTop) {
            $paragraphProperties->setAttribute('fo:margin-top', $this->marginTop->getValue());
        }
        if ($this->marginBottom) {
            $paragraphProperties->setAttribute('fo:margin-bottom', $this->marginBottom->getValue());
        }
        if ($this->pageBreakBefore) {
            $paragraphProperties->setAttribute('fo:break-before', 'page');
        }
    }
}
