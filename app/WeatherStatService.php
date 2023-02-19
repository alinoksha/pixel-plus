<?php

namespace PixelPlus\WeatherStat;

use PixelPlus\WeatherStat\Component\CSVIterator;
use PixelPlus\WeatherStat\Component\FileLogger;

class WeatherStatService
{
    private FileLogger $logger;

    public function __construct(FileLogger $logger)
    {
        $this->logger = $logger;
    }

    private function getDailyAveragesFromCSV(CSVIterator $csv): array
    {
        $tmp = [];
        foreach ($csv as $row) {
            $dt = date_create($row[0]);
            if ($dt === false) {
                $this->logger->log('warning', 'Invalid date', [
                    'row' => $row,
                ]);
                continue;
            }
            $date = $dt->format('Y-m-d');

            if (!is_numeric($row[1])) {
                $this->logger->log('warning', 'Invalid temperature', [
                    'row' => $row,
                ]);
                continue;
            }
            $temp = (float)$row[1];

            $tmp[$date] ?? $tmp[$date] = [
                'count' => 0,
                'sum' => 0,
            ];
            $tmp[$date]['count']++;
            $tmp[$date]['sum'] += $temp;
        }
        ksort($tmp); // Чтобы было по возрастанию даты
        $result = [];
        foreach ($tmp as $date => $row) {
            $result[] = [
                'date' => $date,
                'averageTemp' => $row['sum'] / $row['count'],
            ];
        }
        return $result;
    }

    public function getStatFromCSV(CSVIterator $csv, int $movingAverageLength = 5): array
    {
        $rows = $this->getDailyAveragesFromCSV($csv);
        foreach ($rows as $i => &$row) {
            $ma = null;
            if ($i >= $movingAverageLength - 1) {
                $ma = 0;
                for ($j = $i - $movingAverageLength + 1; $j <= $i; $j++) {
                    $ma += $rows[$j]['averageTemp'];
                }
                $ma /= $movingAverageLength;
            }
            $row['movingAverage'] = $ma;
        }
        return $rows;
    }
}
