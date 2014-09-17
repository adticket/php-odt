<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use DOMDocument;
use DOMXPath;

class StyleFactory
{
    /**
     * @var PageStyle
     */
    private $pageStyle;

    /**
     * @var TextStyle[]
     */
    private $textStyles = [];

    /**
     * @var TextStyle|null
     */
    private $defaultTextStyle = null;

    /**
     * @var ParagraphStyle[]
     */
    private $paragraphStyles = [];

    /**
     * @var ParagraphStyle|null
     */
    private $defaultParagraphStyle = null;

    /**
     * @var GraphicStyle[]
     */
    private $graphicStyles = [];

    /**
     * @var ImageStyle[]
     */
    private $imageStyles = [];

    /**
     * @var TextFrameStyle[]
     */
    private $textFrameStyles = [];

    public function __construct()
    {
        $this->pageStyle = new PageStyle();
        $this->defaultTextStyle = $this->createDefaultTextStyle();
        $this->defaultParagraphStyle = $this->createDefaultParagraphStyle();
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
     * @return ImageStyle
     */
    public function createImageStyle()
    {
        $name = 'im' . (count($this->imageStyles) + 1);
        $imageStyle = new ImageStyle($name);
        $this->imageStyles[] = $imageStyle;

        return $imageStyle;
    }

    /**
     * @return TextFrameStyle
     */
    public function createTextFrameStyle()
    {
        $name = 'fr' . (count($this->textFrameStyles) + 1);
        $textFrameStyle = new TextFrameStyle($name);
        $this->textFrameStyles[] = $textFrameStyle;

        return $textFrameStyle;
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

    public function renderStyles(DOMDocument $stylesDocument)
    {
        $xPath = new DOMXPath($stylesDocument);
        $parentElement = $xPath->query('//office:styles')->item(0);

        foreach ($this->getAllStyles() as $style) {
            $style->renderStyles($stylesDocument, $parentElement);
        }

        $this->pageStyle->renderMarginsTo($stylesDocument);
    }

    public function renderAutomaticStyles(DOMDocument $contentDocument)
    {
        $xPath = new DOMXPath($contentDocument);
        $parentElement = $xPath->query('//office:automatic-styles')->item(0);

        foreach ($this->getAllStyles() as $style) {
            $style->renderAutomaticStyles($contentDocument, $parentElement);
        }
    }

    /**
     * @return string
     */
    private function getNextTextStyleName()
    {
        return 'T' . (count($this->textStyles) + 1);
    }

    /**
     * @return string
     */
    private function getNextParagraphStyleName()
    {
        return 'P' . (count($this->paragraphStyles) + 1);
    }

    /**
     * @return PageStyle
     */
    public function getPageStyle()
    {
        return $this->pageStyle;
    }

    /**
     * @return AbstractStyle[]
     */
    private function getAllStyles()
    {
        return array_merge(
            $this->textStyles,
            $this->paragraphStyles,
            $this->graphicStyles,
            $this->imageStyles,
            $this->textFrameStyles
        );
    }
}
