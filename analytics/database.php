<?php

namespace CleanAnalytics;

/**
 * Log a record by appending it as text to a file named by date.
 * If logging is successful the path to the log file is returned.
 * If logging fails null is returned.
 */
function writeToLogFile(PageRecord $record, string $logFilePath = null): ?string
{
    include_once __DIR__ . '/lib.php';

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

/**
 * Returns the latest N records in reverse chronological order.
 * If the records file only has M entries, a maximum of M results will be returned.
 */
function getLatestRecords(int $maxCount): array
{
    include_once __DIR__ . '/lib.php';

    $logFilePaths = glob(__DIR__ . '/logs/*.txt');
    rsort($logFilePaths);
    $logFilePath = $logFilePaths[0];

    $records = getRecordsFromFile($logFilePath);

    if (count($records) > $maxCount) {
        $records = array_slice($records, count($records) - $maxCount - 1);
    }

    return array_reverse($records);
}

/**
 * Return array of log records from a database log file
 */
function getRecordsFromFile(string $logFilePath): array
{
    $records = [];

    $lines = explode("\n", file_get_contents($logFilePath));
    foreach ($lines as $line) {
        $parts = explode(" ", $line, 5);
        if (count($parts) != 5) {
            continue;
        }

        $timestamp = new \DateTimeImmutable($parts[0]);
        $ip = $parts[1];
        $url = $parts[2];
        $ref = $parts[3];
        $agent = $parts[4];
        $record = new PageRecord($ip, $url, $ref, $agent, $timestamp);
        $records[] = $record;
    }

    return $records;
}

/**
 * Return every record in the database in chronological order.
 * If a match string is supplied, only return records that satisfy the match.
 */
function getAllRecords(): array
{
    include_once __DIR__ . '/lib.php';
    $allRecords = [];
    $logFilePaths = glob(__DIR__ . '/logs/*.txt');
    sort($logFilePaths);
    foreach ($logFilePaths as $logFilePath) {
        // TODO: skip log files older than the given number of days
        $fileRecords = getRecordsFromFile($logFilePath);
        $allRecords = array_merge($allRecords, $fileRecords);
    }
    return $allRecords;
}

function getRecordsMatchingUrl(array $records, ?string $filterUrl): array
{
    if ($filterUrl == null) {
        return $records;
    }

    $matchingRecords = [];

    foreach ($records as $record) {
        if (mb_strpos($record->url, $filterUrl) !== false) {
            $matchingRecords[] = $record;
        }
    }

    return $matchingRecords;
}

/**
 * Return an array of total number of hits by day.
 * Day code is a string formatted like: '2022-01-03'
 */
function getDailyCounts(array $records): array
{
    $counts = [];

    // initialize counter to 0 for all hours
    $oldestRecord = $records[0];
    $newestRecord = $records[count($records) - 1];
    $dayInterval = new \DateInterval('P1D');
    $t = $oldestRecord->timestamp;
    while ($t <= $newestRecord->timestamp) {
        $dayCode = $t->format("Y-m-d");
        $counts[$dayCode] = 0;
        $t = $t->add($dayInterval);
    }

    foreach ($records as $record) {
        $dayCode = $record->timestamp->format("Y-m-d");
        if (!array_key_exists($dayCode, $counts)) {
            $counts[$dayCode] = 0;
        }
        $counts[$dayCode] += 1;
    }

    return $counts;
}

/**
 * Return an array of total number of hits by hour.
 * Hour code is a string formatted like: '2022-01-03T17'
 */
function getHourlyCounts(array $records): array
{
    $counts = [];

    // initialize counter to 0 for all hours
    $oldestRecord = $records[0];
    $newestRecord = $records[count($records) - 1];
    $hrInterval = new \DateInterval('PT1H');
    $t = $oldestRecord->timestamp;
    while ($t <= $newestRecord->timestamp) {
        $hourCode = $t->format("Y-m-d\TH");
        $counts[$hourCode] = 0;
        $t = $t->add($hrInterval);
    }

    foreach ($records as $record) {
        $hourCode = $record->timestamp->format("Y-m-d\TH");
        if (!array_key_exists($hourCode, $counts)) {
            $counts[$hourCode] = 0;
        }
        $counts[$hourCode] += 1;
    }

    return $counts;
}
