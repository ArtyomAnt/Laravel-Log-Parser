<?php

namespace App\Services;

use App\Jobs\LogRestoring;
use App\Services\Converters\Converter;
use App\Services\Converters\JSObjectConverter;

class LogReader
{
    private string $logPath;

    public function __construct(string $path, public Converter $converter = new JSObjectConverter())
    {
        $this->setLogPath(storage_path() . $path);
    }

    public function setLogPath(string $logPath): void
    {
        $this->logPath = $logPath;
    }

    public function getsLogByLines(): \Generator
    {
        $handle = fopen($this->logPath, "rb");

        while (!feof($handle)) {
            yield trim(fgets($handle));

        }

        fclose($handle);
    }

    public function readLogAndDispatchMessages(): void
    {
        $buffer = "";
        $flag = 0;
        $request_id = "";

        foreach ($this->getsLogByLines() as $iteration) {
                if (!$flag && preg_match($this->converter::START_REQUEST_PATTERN, $iteration, $match)) {
                    $buffer = "";
                    $flag = 1;
                    $request_id = $match[1];
                }

                if ($flag && preg_match($this->converter::END_REQUEST_PATTERN, $iteration)) {
                    $buffer .= "}";
                    $data = $this->converter::convertToArray($buffer);

                    if ($data) {
                        LogRestoring::dispatch(array_merge($data, ['request_id' => $request_id]));
                    }

                    $flag = 0;
                    $buffer = "";
                }

                $buffer .= $iteration;
        }
    }
}
