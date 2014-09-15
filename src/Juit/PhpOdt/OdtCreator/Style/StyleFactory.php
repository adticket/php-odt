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
     * @var TextStyle|null
     */
    private $defaultTextStyle = null;

    /**
     * @var ParagraphStyle[]
     */
    private $paragraphStyles = array();

    /**
     * @var ParagraphStyle|null
     */
    private $defaultParagraphStyle = null;

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
    
    public function __construct()
    {
        $this->defaultTextStyle = $this->createDefaultTextStyle();
        $this->defaultParagraphStyle = $this->createDefaultParagraphStyle();
    }

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
        $textStyle = TextStyle::copy($this->defaultTextStyle, $this->getNextTextStyleName());
        $this->textStyles[] = $textStyle;

        return $textStyle;
    }

    private function createDefaultTextStyle()
    {
        $textStyle = new TextStyle($this->getNextTextStyleName());
        $this->textStyles[] = $textStyle;

        return $textStyle;
    }

    /**
     * @return ParagraphStyle
     */
    public function createParagraphStyle()
    {
        $paragraphStyle = ParagraphStyle::copy($this->defaultParagraphStyle, $this->getNextParagraphStyleName());
        $this->paragraphStyles[] = $paragraphStyle;

        return $paragraphStyle;
    }

    private function createDefaultParagraphStyle()
    {
        $paragraphStyle = new ParagraphStyle($this->getNextParagraphStyleName());
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

    /**
     * @return TextStyle
     */
    public function getDefaultTextStyle()
    {
        return $this->defaultTextStyle;
    }

    /**
     * @return ParagraphStyle
     */
    public function getDefaultParagraphStyle()
    {
        return $this->defaultParagraphStyle;
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

    /**
     * @return string
     */
    private function getNextTextStyleName()
    {
        $name = 'T' . (count($this->textStyles) + 1);
        return $name;
    }

    /**
     * @return string
     */
    private function getNextParagraphStyleName()
    {
        $name = 'P' . (count($this->paragraphStyles) + 1);
        return $name;
    }
}
