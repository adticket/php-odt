<?php

namespace OdtCreator\Test\EndToEnd;

use RuntimeException;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class FullFeatureTest extends \PHPUnit_Framework_TestCase
{
    private $baseDir;

    protected function setUp()
    {
        $this->baseDir = ROOT_DIR . "/tmp/odtcreator_tests";
        $this->runShellCommand("rm -fr {$this->baseDir} && mkdir {$this->baseDir}");
    }

    protected function tearDown()
    {
        $this->runShellCommand("rm -fr {$this->baseDir}");
    }

    public function testRunExample()
    {
        $expected = new SplFileInfo(__DIR__ . '/fixtures/example.pdf');

        $builder = new ExampleBuilder(new SplFileInfo(__DIR__ . '/output'));
        $actual  = $builder->build();

        $expectedPages = $this->burstPdfInSinglePages($expected, 'expected');
        $actualPages   = $this->burstPdfInSinglePages($actual, 'actual');

        $this->assertEquals(count($expectedPages), count($actualPages), 'Page count does not equal');

        for ($i = 0; $i < count($actualPages); $i++) {
            $this->assertPageContentsEqual($expectedPages[$i], $actualPages[$i]);
        }
    }

    /**
     * @param string $command
     * @return string
     * @throws RuntimeException
     */
    private function runShellCommand($command)
    {
        $process = new Process($command);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }

    /**
     * @param SplFileInfo $inputFile
     * @param string $outputPrefix
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    private function burstPdfInSinglePages(SplFileInfo $inputFile, $outputPrefix)
    {
        $expectedPath = "{$outputPrefix}.%02d.pdf";

        // cd baseDir because pdftk insists on writing an output file named doc_data.txt
        $this->runShellCommand("cd {$this->baseDir} && pdftk {$inputFile} burst output {$expectedPath}");

        $finder = new Finder();
        $finder->files()->in($this->baseDir)->name('/^' . preg_quote($outputPrefix) . '\.\d+\.pdf/');
        $files = [];
        foreach ($finder as $file) {
            $files[] = $file;
        }

        return $files;
    }

    /**
     * @param SplFileInfo $expected
     * @param SplFileInfo $actual
     */
    private function assertPageContentsEqual(SplFileInfo $expected, SplFileInfo $actual)
    {
        $diff      = "{$this->baseDir}/diff.pdf";
        $diffImage = "{$this->baseDir}/diff.bmp";

        $this->runShellCommand("compare {$expected} {$actual} -compose src {$diff}");
        $this->runShellCommand("gs -o {$diffImage} -r72 -g595x842 -sDEVICE=bmp256 {$diff}");

        $result = $this->runShellCommand("md5sum {$diffImage}");
        if (!preg_match('/^(?P<hash>\S+) /', $result, $matches)) {
            $this->fail('Could not get hash of diff image');
        }

        $md5OfDiff      = $matches['hash'];
        $md5OfWhitePage = '74ab373396b8c6dd4ca9322fd6edae66';
        $this->assertEquals(
            $md5OfWhitePage,
            $md5OfDiff,
            "Pages appear to be different: '{$expected}' vs. '{$actual}'"
        );
    }
}
