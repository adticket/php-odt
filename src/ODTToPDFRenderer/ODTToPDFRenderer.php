<?php

namespace ODTToPDFRenderer;

use ShellCommandExecutor\Result;
use ShellCommandExecutor\ShellCommandExecutor;

class ODTToPDFRenderer
{
    /**
     * @var \SplFileInfo
     */
    private $libreOfficeBinary;

    public function __construct(\SplFileInfo $libreOfficeBinary = null)
    {
        if (null === $libreOfficeBinary) {
            $libreOfficeBinary = new \SplFileInfo('/usr/bin/libreoffice');
        }
        $this->libreOfficeBinary = $libreOfficeBinary;
    }

    /**
     * @param \SplFileInfo $odtFile
     * @throws \RuntimeException
     * @return \SplFileInfo Info about the generated PDF file
     */
    public function render(\SplFileInfo $odtFile)
    {
        $shellCommand = $this->createShellCommand($odtFile);

        $shellCommandExecutor = new ShellCommandExecutor();
        $result = $shellCommandExecutor->execute($shellCommand);

        $this->assertIsValid($result);

        return $this->extractOutputFileInfo($result);
    }

    /**
     * @param \SplFileInfo $odtFile
     * @return string
     */
    private function createShellCommand(\SplFileInfo $odtFile)
    {
        return
            "{$this->libreOfficeBinary->getPathname()} "
            . "--headless --convert-to pdf "
            . "{$odtFile->getPathname()} "
            . "--outdir {$odtFile->getPath()}";
    }

    /**
     * @param Result $result
     * @throws \RuntimeException
     */
    private function assertIsValid(Result $result)
    {
        $isReturnValueError = ($result->getReturnVar() !== 0);
        $hasErrorOutput = (count($result->getErrors()) > 0);
        $hasNoRegularOutput = count($result->getOutput()) === 0;

        if ($isReturnValueError || $hasErrorOutput || $hasNoRegularOutput) {
            throw new \RuntimeException("Unexpected result from LibreOffice");
        }
    }

    /**
     * @param Result $result
     * @throws \RuntimeException
     * @return \SplFileInfo
     */
    private function extractOutputFileInfo(Result $result)
    {
        $regex = '/^convert \S* -> (?P<output_path>\S*) using writer_pdf_Export$/';
        $matches = array();

        $output = $result->getOutput();
        $firstLine = $output[0];

        if (!preg_match($regex, $firstLine, $matches)) {
            throw new \RuntimeException("Unexpected output from LibreOffice");
        }

        return new \SplFileInfo($matches['output_path']);
    }
}
