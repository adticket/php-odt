<?php

namespace ShellCommandExecutor;

use ShellCommandExecutor\Result;

class ShellCommandExecutor
{
    /**
     * @param string $shellCommand
     * @return Result
     */
    public function execute($shellCommand)
    {
        $output = array();
        $returnVar = null;
        $tempFile = tempnam(sys_get_temp_dir(), 'sce-');

        exec($shellCommand . ' 2>' . $tempFile, $output, $returnVar);

        $errorOutput = file($tempFile);
        $errorOutput = array_map(function ($line) {
            return rtrim($line, "\r\n");
        }, $errorOutput);
        unlink($tempFile);

        return new Result($output, $errorOutput, $returnVar);
    }
}
