<?php

namespace ODTCreator\Document;

use ODTCreator\Style\TextStyle;

class Styles implements File
{
    /**
     * @var TextStyle[]
     */
    private $textStyles = array();

    public function addTextStyle($textStyle)
    {
        $this->textStyles[] = $textStyle;
    }

    /**
     * @return string The file content
     */
    public function render()
    {
        $domDocument = $this->createDOMDocument();

        foreach ($this->textStyles as $textStyle) {
            $textStyle->renderTo($domDocument);
        }

        return $domDocument->saveXML();
    }

    private function createDOMDocument()
    {
        $domDocument = new \DOMDocument();
        $domDocument->load(__DIR__ . '/templates/styles.xml');

        return $domDocument;
    }

    /**
     * Declare the fonts that can be used in the document
     *
     * @param \DOMElement $rootElement
     * @param \DOMDocument $domDocument
     */
    private function declareFontFaces(\DOMElement $rootElement, \DOMDocument $domDocument)
    {
        $fontFaceDecl = $domDocument->createElement('office:font-face-decls');
        $rootElement->appendChild($fontFaceDecl);

        $ff = $domDocument->createElement('style:font-face');
        $ff->setAttribute('style:name', 'Courier');
        $ff->setAttribute('svg:font-family', 'Courier');
        $ff->setAttribute('style:font-family-generic', 'modern');
        $ff->setAttribute('style:font-pitch', 'fixed');
        $fontFaceDecl->appendChild($ff);

        $ff = $domDocument->createElement('style:font-face');
        $ff->setAttribute('style:name', 'DejaVu Serif');
        $ff->setAttribute('svg:font-family', '&apos;DejaVu Serif&apos;');
        $ff->setAttribute('style:font-family-generic', 'roman');
        $ff->setAttribute('style:font-pitch', 'variable');
        $fontFaceDecl->appendChild($ff);

        $ff = $domDocument->createElement('style:font-face');
        $ff->setAttribute('style:name', 'Times New Roman');
        $ff->setAttribute('svg:font-family', '&apos;Times New Roman&apos;');
        $ff->setAttribute('style:font-family-generic', 'roman');
        $ff->setAttribute('style:font-pitch', 'variable');
        $fontFaceDecl->appendChild($ff);

        $ff = $domDocument->createElement('style:font-face');
        $ff->setAttribute('style:name', 'DejaVu Sans');
        $ff->setAttribute('svg:font-family', '&apos;DejaVu Sans&apos;');
        $ff->setAttribute('style:font-family-generic', 'swiss');
        $ff->setAttribute('style:font-pitch', 'variable');
        $fontFaceDecl->appendChild($ff);

        $ff = $domDocument->createElement('style:font-face');
        $ff->setAttribute('style:name', 'Verdana');
        $ff->setAttribute('svg:font-family', 'Verdana');
        $ff->setAttribute('style:font-family-generic', 'swiss');
        $ff->setAttribute('style:font-pitch', 'variable');
        $fontFaceDecl->appendChild($ff);
    }

    /**
     * @return string The relative path of the file within the ODT structure
     */
    public function getRelativePath()
    {
        return 'styles.xml';
    }
}
