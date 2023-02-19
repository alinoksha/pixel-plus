<?php

namespace PixelPlus\WeatherStat\Component;

use Generator;
use IteratorAggregate;
use RuntimeException;

class CSVIterator implements IteratorAggregate
{
    private string $pathname;
    private bool $skipFirstRow;

    public function __construct(string $pathname, bool $skipFirstRow = false)
    {
        $this->pathname = $pathname;
        $this->skipFirstRow = $skipFirstRow;
    }

    public function getIterator(): Generator
    {
        if (!file_exists($this->pathname)) {
            throw new RuntimeException();
        }

        $file = fopen($this->pathname, 'r');

        if ($file === false) {
            throw new RuntimeException();
        }

        if ($this->skipFirstRow) {
            fgets($file);
        }

        while (($row = fgetcsv($file, null, ';')) !== false) {
            yield $row;
        }

        fclose($file);
    }
}
