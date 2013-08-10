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
            $textStyle->appendTo($domDocument);
        }

        return $domDocument->saveXML();
    }

    private function createDOMDocument()
    {
        $domDocument = new \DOMDocument('1.0', 'UTF-8');

        $rootElement = $domDocument->createElement('office:document-styles');
        $rootElement->setAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $rootElement->setAttribute('xmlns:style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
        $rootElement->setAttribute('xmlns:text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
        $rootElement->setAttribute('xmlns:fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');
        $rootElement->setAttribute('xmlns:svg', 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0');
        $rootElement->setAttribute('xmlns:draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
        $rootElement->setAttribute('xmlns:table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $rootElement->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        $domDocument->appendChild($rootElement);

        $this->declareFontFaces($rootElement, $domDocument);

        $officeStyles = $domDocument->createElement('office:styles');
        $rootElement->appendChild($officeStyles);

        $officeAutomaticStyles = $domDocument->createElement('office:automatic-styles');
        $rootElement->appendChild($officeAutomaticStyles);

        $officeMasterStyles = $domDocument->createElement('office:master-styles');
        $rootElement->appendChild($officeMasterStyles);

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
