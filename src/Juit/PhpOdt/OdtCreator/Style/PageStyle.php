<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMXPath;
use Juit\PhpOdt\OdtCreator\Document\StylesFile;
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
     * @param DOMDocument $stylesDocument
     */
    public function renderMarginsTo(DOMDocument $stylesDocument)
    {
        if (null !== $this->marginTop) {
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm1')
                ->setAttribute('fo:margin-top', $this->marginTop->getValue());
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm2')
                ->setAttribute('fo:margin-top', $this->marginTop->getValue());
        }
        if (null !== $this->marginTopOnFirstPage) {
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm2')
                ->setAttribute('fo:margin-top', $this->marginTopOnFirstPage->getValue());
        }
        if (null !== $this->marginLeft) {
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm1')
                ->setAttribute('fo:margin-left', $this->marginLeft->getValue());
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm2')
                ->setAttribute('fo:margin-left', $this->marginLeft->getValue());
        }
        if (null !== $this->marginRight) {
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm1')
                ->setAttribute('fo:margin-right', $this->marginRight->getValue());
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm2')
                ->setAttribute('fo:margin-right', $this->marginRight->getValue());
        }
        if (null !== $this->marginBottom) {
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm1')
                ->setAttribute('fo:margin-bottom', $this->marginBottom->getValue());
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm2')
                ->setAttribute('fo:margin-bottom', $this->marginBottom->getValue());
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
