<?php

function getLogfileLocation(\DateTimeInterface $date): string {
    return sprintf(__DIR__ . '/../../logs/%s.txt', $date->format('Y-m-d'));
}

function writeStatistics(string $filepath, \DateTimeInterface $date, string $ip, string $url, string $referrer, string $userAgent): bool {
    $logLine =
        $date->format('c') . " " .
        $ip . " " .
        $url . " " .
        $referrer . " " .
        $userAgent . "\n";

    if (false === file_put_contents($filepath, $logLine, FILE_APPEND | LOCK_EX)) {
        return false;
    }

    return true;
}
