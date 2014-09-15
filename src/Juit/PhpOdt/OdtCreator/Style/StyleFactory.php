<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use Juit\PhpOdt\OdtCreator\Document\StylesFile;
use Juit\PhpOdt\OdtCreator\Value\Length;

class StyleFactory
{
    /**
     * @var TextStyle[]
     */
    private $textStyles = array();

    /**
     * @var ParagraphStyle[]
     */
    private $paragraphStyles = array();

    /**
     * @var GraphicStyle[]
     */
    private $graphicStyles = array();

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
     * @return TextStyle
     */
    public function createTextStyle()
    {
        $name = 'T' . (count($this->textStyles) + 1);
        $textStyle = new TextStyle($name);
        $this->textStyles[] = $textStyle;

        return $textStyle;
    }

    /**
     * @return ParagraphStyle
     */
    public function createParagraphStyle()
    {
        $name = 'P' . (count($this->paragraphStyles) + 1);
        $paragraphStyle = new ParagraphStyle($name);
        $this->paragraphStyles[] = $paragraphStyle;

        return $paragraphStyle;
    }

    /**
     * @return GraphicStyle
     */
    public function createGraphicStyle()
    {
        $name = 'G' . (count($this->graphicStyles) + 1);
        $graphicStyle = new GraphicStyle($name);
        $this->graphicStyles[] = $graphicStyle;

        return $graphicStyle;
    }

    public function renderAllStylesTo(\DOMDocument $stylesDocument)
    {
        $styles = array_merge($this->textStyles, $this->paragraphStyles, $this->graphicStyles);
        $parentElement = $stylesDocument->getElementsByTagNameNS(StylesFile::NAMESPACE_OFFICE, 'styles')->item(0);

        foreach ($styles as $style) {
            /** @var $style AbstractStyle */
            $style->renderTo($stylesDocument, $parentElement);
        }

        $this->renderMarginsTo($stylesDocument);
    }

    /**
     * @param \DOMDocument $stylesDocument
     */
    private function renderMarginsTo(\DOMDocument $stylesDocument)
    {
        if (null !== $this->marginTop) {
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm1')
                ->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-top', $this->marginTop->getValue());
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm2')
                ->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-top', $this->marginTop->getValue());
        }
        if (null !== $this->marginTopOnFirstPage) {
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm2')
                ->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-top', $this->marginTopOnFirstPage->getValue());
        }
        if (null !== $this->marginLeft) {
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm1')
                ->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-left', $this->marginLeft->getValue());
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm2')
                ->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-left', $this->marginLeft->getValue());
        }
        if (null !== $this->marginRight) {
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm1')
                ->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-right', $this->marginRight->getValue());
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm2')
                ->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-right', $this->marginRight->getValue());
        }
        if (null !== $this->marginBottom) {
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm1')
                ->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-bottom', $this->marginBottom->getValue());
            $this
                ->findPageLayoutByName($stylesDocument, 'Mpm2')
                ->setAttributeNS(StylesFile::NAMESPACE_FO, 'margin-bottom', $this->marginBottom->getValue());
        }
    }

    /**
     * @param \DOMDocument $stylesDocument
     * @param string $name
     * @return \DOMElement
     */
    private function findPageLayoutByName(\DOMDocument $stylesDocument, $name)
    {
        $xpath = new \DOMXPath($stylesDocument);
        $xpath->registerNamespace('style', StylesFile::NAMESPACE_STYLE);

        return $xpath
            ->query('//style:page-layout[@style:name="' . $name . '"]/style:page-layout-properties')
            ->item(0);
    }
}
