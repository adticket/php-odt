<?php

namespace ODTCreator\Test\Unit\ODTCreator\File;

use ODTCreator\File\Manifest;

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
