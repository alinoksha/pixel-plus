<?php

namespace PixelPlus\WeatherStat\Component;

class FileLogger
{
    private const PATH = __DIR__ . '/../../logs';

    public function log(string $level, string $message, array $context = []): void
    {
        $text = sprintf('%s ? %s : %s ', date('Y-m-d H:i:s'), $level, $message);
        if (count($context) > 0) {
            $text .= json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        $text .= PHP_EOL;
        file_put_contents(sprintf(self::PATH . '/%s.log', date('Y-m-d')), $text, FILE_APPEND);
    }
}
