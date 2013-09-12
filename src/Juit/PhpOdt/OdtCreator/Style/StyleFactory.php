<?php

namespace Juit\PhpOdt\OdtCreator\Style;

use Juit\PhpOdt\OdtCreator\Document\StylesFile;

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
    }
}
