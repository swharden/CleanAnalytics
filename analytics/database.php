<?php

require_once __DIR__ . '/lib.php';

/**
 * Log a record by appending it as text to a file named by date.
 * If logging is successful the path to the log file is returned.
 * If logging fails null is returned.
 */
function writeToLogFile(PageRecord $record, string $logFilePath = null): ?string
{
    $logLine = "" .
        $record->timestamp->format('c') . " " .
        $record->ip . " " .
        $record->url . " " .
        $record->referrer . " " .
        $record->agent . "\n";

    if ($logFilePath == null) {
        $logFileName = $record->timestamp->format('Y-m-d') . ".txt";
        $logFilePath = __DIR__ . '/logs/' . $logFileName;
    }

    $success = file_put_contents($logFilePath, $logLine, FILE_APPEND | LOCK_EX);

    return $success ? $logFilePath : null;
}

function getLatestRecords(int $maxCount, bool $anonymize = true): array
{
    $logFilePaths = glob(__DIR__ . '/logs/*.txt');
    rsort($logFilePaths);
    $logFilePath = $logFilePaths[0];

    $records = [];
    $lines = explode("\n", file_get_contents($logFilePath));
    foreach ($lines as $line) {
        $parts = explode(" ", $line, 5);
        if (count($parts) != 5) {
            continue;
        }

        $timestamp = new DateTimeImmutable($parts[0]);
        $ip = $anonymize ? anonymizeIp($parts[1]) : $parts[1];
        $url = $parts[2];
        $ref = $parts[3];
        $agent = $parts[4];
        $record = new PageRecord($ip, $url, $ref, $agent, $timestamp);
        $records[] = $record;
    }

    if (count($records) > $maxCount) {
        $records = array_slice($records, count($records) - $maxCount - 1);
    }

    return array_reverse($records);
}
