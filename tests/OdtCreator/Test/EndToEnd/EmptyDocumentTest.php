<?php

namespace OdtCreator\Test\EndToEnd;

use Juit\PhpOdt\OdtCreator\OdtFile;

class EmptyDocumentTest extends EndToEndTestCase
{
    protected function setUp()
    {
        $this->testName = 'empty_document';
    }

    public function testEmptyDocument()
    {
        $odt = new OdtFile();

        $odt->save($this->getOutputFileInfo());
        exec("unzip {$this->getOutputFileInfo()->getPathname()} -d {$this->getOutputUnzipDirInfo()->getPathname()}");

        $odtFileInfo = $this->getOutputFileInfo();

        $this->assertIsValidOdtFile($odtFileInfo);
    }

    /**
     * @param \SplFileInfo $odtFileInfo
     */
    protected function assertIsValidOdtFile(\SplFileInfo $odtFileInfo)
    {
        $this->markTestSkipped("ODF validator is broken");

        $output = array();
        $resultVar = null;
        $command = "cd " . __DIR__ . "/odf-validator && ./validator --file=" . $odtFileInfo->getPathname();

        exec($command, $output, $resultVar);

        $expected = array(
            'WARNING: does not contain a mimetype. This is a SHOULD in OpenDocument 1.0',
            '1 warning(s), 0 error(s)',
            'Document does validate'
        );
        $this->assertEquals($expected, $output, 'Document does not validate.');
    }
}