<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMXPath;
use Juit\PhpOdt\OdtCreator\Document\StylesFile;
use Juit\PhpOdt\OdtCreator\Value\BorderStyle;
use Juit\PhpOdt\OdtCreator\Value\Length;

class PageStyle
{
    /**
     * @var null|Length
     */
    private $marginTop = null;

    /**
     * @var null|Length
     */
    private $marginTopOnFirstPage = null;

    /**
     * @var null|Length
     */
    private $marginLeft = null;

    /**
     * @var null|Length
     */
    private $marginRight = null;

    /**
     * @var null|Length
     */
    private $marginBottom = null;

    /**
     * @var BorderStyle|null
     */
    private $border = null;

    /**
     * @param \Juit\PhpOdt\OdtCreator\Value\Length $marginTop
     */
    public function setMarginTop(Length $marginTop)
    {
        $this->marginTop = $marginTop;
    }

    /**
     * @param \Juit\PhpOdt\OdtCreator\Value\Length $marginTopOnFirstPage
     */
    public function setMarginTopOnFirstPage(Length $marginTopOnFirstPage)
    {
        $this->marginTopOnFirstPage = $marginTopOnFirstPage;
    }

    /**
     * @param \Juit\PhpOdt\OdtCreator\Value\Length $marginLeft
     */
    public function setMarginLeft(Length $marginLeft)
    {
        $this->marginLeft = $marginLeft;
    }

    /**
     * @param \Juit\PhpOdt\OdtCreator\Value\Length $marginRight
     */
    public function setMarginRight(Length $marginRight)
    {
        $this->marginRight = $marginRight;
    }

    /**
     * @param \Juit\PhpOdt\OdtCreator\Value\Length $marginBottom
     */
    public function setMarginBottom(Length $marginBottom)
    {
        $this->marginBottom = $marginBottom;
    }

    /**
     * @param BorderStyle|null $border
     */
    public function setBorder(BorderStyle $border = null)
    {
        $this->border = $border;
    }

    /**
     * @param DOMDocument $stylesDocument
     */
    public function renderMarginsTo(DOMDocument $stylesDocument)
    {
        $firstPage = $this->findPageLayoutByName($stylesDocument, 'Mpm2');
        $otherPages = $this->findPageLayoutByName($stylesDocument, 'Mpm1');

        if (null !== $this->marginTop) {
            $firstPage->setAttribute('fo:margin-top', $this->marginTop->getValue());
            $otherPages->setAttribute('fo:margin-top', $this->marginTop->getValue());
        }
        if (null !== $this->marginTopOnFirstPage) {
            $firstPage->setAttribute('fo:margin-top', $this->marginTopOnFirstPage->getValue());
        }
        if (null !== $this->marginLeft) {
            $firstPage->setAttribute('fo:margin-left', $this->marginLeft->getValue());
            $otherPages->setAttribute('fo:margin-left', $this->marginLeft->getValue());
        }
        if (null !== $this->marginRight) {
            $firstPage->setAttribute('fo:margin-right', $this->marginRight->getValue());
            $otherPages->setAttribute('fo:margin-right', $this->marginRight->getValue());
        }
        if (null !== $this->marginBottom) {
            $firstPage->setAttribute('fo:margin-bottom', $this->marginBottom->getValue());
            $otherPages->setAttribute('fo:margin-bottom', $this->marginBottom->getValue());
        }
        if (null !== $this->border) {
            $firstPage->setAttribute('fo:border', $this->border->getValue());
            $firstPage->setAttribute('fo:padding', '0cm');
            $firstPage->setAttribute('style:shadow', 'none');

            $otherPages->setAttribute('fo:border', $this->border->getValue());
            $otherPages->setAttribute('fo:padding', '0cm');
            $otherPages->setAttribute('style:shadow', 'none');
        }
    }

    /**
     * @param DOMDocument $stylesDocument
     * @param string $name
     * @return \DOMElement
     */
    private function findPageLayoutByName(DOMDocument $stylesDocument, $name)
    {
        $xpath = new DOMXPath($stylesDocument);
        $xpath->registerNamespace('style:style', StylesFile::NAMESPACE_STYLE);

        return $xpath
            ->query('//style:page-layout[@style:name="' . $name . '"]/style:page-layout-properties')
            ->item(0);
    }
}
