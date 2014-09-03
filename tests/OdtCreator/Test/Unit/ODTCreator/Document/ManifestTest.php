<?php

namespace OdtCreator\Test\Unit\ODTCreator\Document;

use Juit\PhpOdt\OdtCreator\Document\ManifestFile;

class ManifestTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatesCorrectXML()
    {
        $sut = new ManifestFile();

        $actualXMLString = $sut->render();
        $expectedFile = __DIR__ . '/ManifestTest/expected_output/manifest.xml';

        $this->assertXmlStringEqualsXmlFile($expectedFile, $actualXMLString);
    }
}
