<?php

namespace ODTCreator\Tests\EndToEnd;

class EndToEndTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $testName = '';

    protected function tearDown()
    {
        unlink($this->getOutputFilePath());
        exec("rm -fr " . $this->getOutputUnzipPath());
    }

    /**
     * @return string
     */
    protected function getOutputPath()
    {
        return __DIR__ . '/output';
    }

    /**
     * @return string
     */
    protected function getOutputFilePath()
    {
        $this->assertIsTestNameSet();

        return $this->getOutputPath() . '/' . $this->testName . '.odt';
    }

    /**
     * @return string
     */
    protected function getOutputUnzipPath()
    {
        $this->assertIsTestNameSet();

        return $this->getOutputPath() . '/' . $this->testName;
    }

    /**
     * @return string
     */
    protected function getFixturePath()
    {
        $this->assertIsTestNameSet();

        return __DIR__ . '/expected_output/' . $this->testName;
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
