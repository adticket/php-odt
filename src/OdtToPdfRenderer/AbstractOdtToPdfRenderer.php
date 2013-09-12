<?php

namespace OdtToPdfRenderer;

use Adticket\Elvis\CommunicationBundle\FormLetter\Cache;
use ShellCommandExecutor\Result;
use ShellCommandExecutor\ShellCommandExecutor;

abstract class AbstractOdtToPdfRenderer
{
    /**
     * @var Cache
     */
    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param \SplFileInfo $odtFileInfo
     * @throws \RuntimeException
     * @return \SplFileInfo Info about the generated PDF file
     */
    public function render(\SplFileInfo $odtFileInfo)
    {
        $pdfFileInfo = $this->cache->getPdfFileInfo($odtFileInfo);
        if ($pdfFileInfo->isFile()) {
//            return $pdfFileInfo;
        }

        $shellCommand = $this->createShellCommand($odtFileInfo);

        $shellCommandExecutor = new ShellCommandExecutor();
        $result = $shellCommandExecutor->execute($shellCommand);

        $this->assertIsValid($result);

        // TODO: This is just a dev experiment
        $out = new \SplFileInfo($pdfFileInfo->getPath() . '/with_background.pdf');
        $cmd = "/usr/bin/pdftk {$pdfFileInfo->getPathname()}"
            . " multibackground /var/www/app/doc/Briefbogen.pdf"
            . " output {$out->getPathname()}";
        exec($cmd);
        unlink($pdfFileInfo->getPathname());
        rename($out->getPathname(), $pdfFileInfo->getPathname());

        return $pdfFileInfo;
    }

    /**
     * @param \SplFileInfo $odtFile
     * @return string
     */
    abstract protected function createShellCommand(\SplFileInfo $odtFile);

    /**
     * @param Result $result
     * @throws \RuntimeException
     */
    protected function assertIsValid(Result $result)
    {
        $isReturnValueError = ($result->getReturnVar() !== 0);
        $hasUnexpectedErrorOutput = !$this->isExpectedOutput($result->getErrors(), $this->getErrorWhiteListRegexes());
        $hasUnexpectedOutput = !$this->isExpectedOutput($result->getOutput(), $this->getOutputWhiteListRegexes());

        if ($isReturnValueError || $hasUnexpectedErrorOutput || $hasUnexpectedOutput) {
            $message = "Unexpected result ({$result->getReturnVar()}) from LibreOffice: ";
            $message .= implode("| ", $result->getOutput());
            $message .= ' | ' . implode(" | ", $result->getErrors());
            throw new \RuntimeException($message);
        }
    }

    /**
     * @param array $lines
     * @param array $whiteListRegexes
     * @return bool
     */
    protected function isExpectedOutput(array $lines, array $whiteListRegexes)
    {
        $isExpectedOutput = true;

        foreach ($lines as $line) {
            if (!$this->isExpectedOutputLine($line, $whiteListRegexes)) {
                $isExpectedOutput = false;
            }
        }

        return $isExpectedOutput;
    }

    /**
     * @param string $line
     * @param array $whiteListRegexes
     * @return bool
     */
    protected function isExpectedOutputLine($line, array $whiteListRegexes)
    {
        foreach ($whiteListRegexes as $regex) {
            if (preg_match($regex, $line)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    abstract protected function getOutputWhiteListRegexes();

    /**
     * @return array
     */
    abstract protected function getErrorWhiteListRegexes();
}
