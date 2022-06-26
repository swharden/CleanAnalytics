<?php

require_once __DIR__ . '/lib.php';

/**
 * Log a record by appending it as text to a file named by date.
 * Sanitation is applied before entering the record to the database.
 * If logging is successful the path to the log file is returned.
 * If logging fails null is returned.
 */
function writeToLogFile(PageRecord $dirtyRecord, $logFilePath = null): string
{
    $record = getSanitizedRecord($dirtyRecord);
    
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
