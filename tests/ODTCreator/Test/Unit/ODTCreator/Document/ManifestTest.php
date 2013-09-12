<?php

namespace OdtCreator\Test\Unit\ODTCreator\Document;

use OdtCreator\Document\Manifest;

class ManifestTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatesCorrectXML()
    {
        $sut = new Manifest();

        $actualXMLString = $sut->render();
        $expectedFile = __DIR__ . '/ManifestTest/expected_output/manifest.xml';

        $this->assertXmlStringEqualsXmlFile($expectedFile, $actualXMLString);
    }
}
