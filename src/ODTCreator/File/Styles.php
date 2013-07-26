<?php

namespace ODTCreator\File;

class Styles implements FileInterface
{
    /**
     * @var \DOMDocument
     */
    private $domDocument;

    public function __construct()
    {
        $this->initDOMDocument();
    }

    private function initDOMDocument()
    {
        $this->domDocument = new \DOMDocument('1.0', 'UTF-8');

        $rootElement = $this->domDocument->createElement('office:document-styles');
        $rootElement->setAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $rootElement->setAttribute('xmlns:style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
        $rootElement->setAttribute('xmlns:text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
        $rootElement->setAttribute('xmlns:fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');
        $rootElement->setAttribute('xmlns:svg', 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0');
        $rootElement->setAttribute('xmlns:draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
        $rootElement->setAttribute('xmlns:table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $rootElement->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        $this->domDocument->appendChild($rootElement);

        $this->declareFontFaces($rootElement);

        $officeStyles = $this->domDocument->createElement('office:styles');
        $rootElement->appendChild($officeStyles);

        $officeAutomaticStyles = $this->domDocument->createElement('office:automatic-styles');
        $rootElement->appendChild($officeAutomaticStyles);

        $officeMasterStyles = $this->domDocument->createElement('office:master-styles');
        $rootElement->appendChild($officeMasterStyles);
    }

    /**
     * @return string The file content
     */
    public function render()
    {
        return $this->domDocument->saveXML();
    }

    /**
     * Declare the fonts that can be used in the document
     *
     * @param \DOMElement $rootStyles The root element of the styles document
     */
    private function declareFontFaces($rootStyles)
    {
        $fontFaceDecl = $this->domDocument->createElement('office:font-face-decls');
        $rootStyles->appendChild($fontFaceDecl);

        $ff = $this->domDocument->createElement('style:font-face');
        $ff->setAttribute('style:name', 'Courier');
        $ff->setAttribute('svg:font-family', 'Courier');
        $ff->setAttribute('style:font-family-generic', 'modern');
        $ff->setAttribute('style:font-pitch', 'fixed');
        $fontFaceDecl->appendChild($ff);

        $ff = $this->domDocument->createElement('style:font-face');
        $ff->setAttribute('style:name', 'DejaVu Serif');
        $ff->setAttribute('svg:font-family', '&apos;DejaVu Serif&apos;');
        $ff->setAttribute('style:font-family-generic', 'roman');
        $ff->setAttribute('style:font-pitch', 'variable');
        $fontFaceDecl->appendChild($ff);

        $ff = $this->domDocument->createElement('style:font-face');
        $ff->setAttribute('style:name', 'Times New Roman');
        $ff->setAttribute('svg:font-family', '&apos;Times New Roman&apos;');
        $ff->setAttribute('style:font-family-generic', 'roman');
        $ff->setAttribute('style:font-pitch', 'variable');
        $fontFaceDecl->appendChild($ff);

        $ff = $this->domDocument->createElement('style:font-face');
        $ff->setAttribute('style:name', 'DejaVu Sans');
        $ff->setAttribute('svg:font-family', '&apos;DejaVu Sans&apos;');
        $ff->setAttribute('style:font-family-generic', 'swiss');
        $ff->setAttribute('style:font-pitch', 'variable');
        $fontFaceDecl->appendChild($ff);

        $ff = $this->domDocument->createElement('style:font-face');
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
        return '/styles.xml';
    }

    /**
     * @return \DOMDocument
     */
    public function getDOMDocument()
    {
        // TODO: Remove this helper method as soon as all its users are refactored

        return $this->domDocument;
    }
}
