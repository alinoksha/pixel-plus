<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PixelPlus\WeatherStat\Component\CSVIterator;
use PixelPlus\WeatherStat\Component\FileLogger;
use PixelPlus\WeatherStat\WeatherStatService;

$csv = new CSVIterator(__DIR__ . '/../weather_statistics.csv', true);
$service = new WeatherStatService(new FileLogger());

echo '<link rel="stylesheet" href="style.css">';
$html = '<table><tr><th>Date</th><th>AVG</th><th>SMA3</th><tr>';
foreach ($service->getStatFromCSV($csv, 3) as $row) {
    $html .= sprintf('<tr><td>%s</td><td>%.3f</td><td>%s</td><tr>',
        $row['date'],
        $row['averageTemp'],
        $row['movingAverage'] === null ? 'N/D' : sprintf('%.3f', $row['movingAverage'])
    );
}
$html .= '</table>';
echo $html;
