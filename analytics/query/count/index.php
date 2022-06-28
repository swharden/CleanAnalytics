<?php

/**
 * This endpoint returns the total number of logged events binned by day
 */

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(400);
    echo "POST required supported";
    exit;
}

require_once "../../lib.php";
require_once "../../database.php";

CleanAnalytics\reportAllErrors();

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$posts = json_decode(file_get_contents('php://input'), true);
if ($posts == null) {
    http_response_code(400);
    echo "invalid JSON body";
    exit;
}

if (isset($posts['days'])) {
    $days = intval($posts['days']);
    if ($days < 1) {
        http_response_code(400);
        echo "invalid number of days";
        exit;
    }
} else {
    http_response_code(400);
    echo "days required";
    exit;
}

if (isset($posts['bin'])) {
    $bin = $posts['bin'];
} else {
    http_response_code(400);
    echo "bin required";
    exit;
}

if (isset($posts['filter-url'])) {
    $filterUrl = $posts['filter-url'];
} else {
    $filterUrl = null;
}

$timeStart = microtime(true);
$records = \CleanAnalytics\getAllRecords(); // TODO: pass number of days in here
$records = \CleanAnalytics\getRecordsMatchingUrl($records, $filterUrl);

if ($bin == "day") {
    $counts = \CleanAnalytics\getDailyCounts($records);
} else if ($bin == "hour") {
    $counts = \CleanAnalytics\getHourlyCounts($records);
} else {
    http_response_code(400);
    echo "Invalid bin. Use 'day' or 'hour'";
    exit;
}

echo json_encode(
    [
        "request-guid" => bin2hex(random_bytes(16)),
        "request-days" => $days,
        "request-filter-url" => $filterUrl,
        "execution-records-read" => count($records),
        "execution-time-sec" => microtime(true) - $timeStart,
        "counts" => $counts,
    ]
);
