<?php

namespace OdtCreator\Test\EndToEnd;

class EndToEndTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $testName = '';

    protected function tearDown()
    {
        unlink($this->getOutputFileInfo());
        exec("rm -fr " . $this->getOutputUnzipDirInfo()->getPathname());
    }

    /**
     * @return string
     */
    protected function getOutputPath()
    {
        return __DIR__ . '/output';
    }

    /**
     * @return \SplFileInfo
     */
    protected function getOutputFileInfo()
    {
        $this->assertIsTestNameSet();

        return new \SplFileInfo($this->getOutputPath() . '/' . $this->testName . '.odt');
    }

    /**
     * @return \SplFileInfo
     */
    protected function getOutputUnzipDirInfo()
    {
        $this->assertIsTestNameSet();

        return new \SplFileInfo($this->getOutputPath() . '/' . $this->testName);
    }

    /**
     * @return \SplFileInfo
     */
    protected function getFixtureDirInfo()
    {
        $this->assertIsTestNameSet();

        return new \SplFileInfo(__DIR__ . '/expected_output/' . $this->testName);
    }

    /**
     * @throws \RuntimeException
     */
    private function assertIsTestNameSet()
    {
        if (!$this->testName) {
           throw new \RuntimeException('Must set test name');
        }
    }
}
