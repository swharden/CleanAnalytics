<?php

/**
 * This endpoint returns the total number of logged events binned by day
 */

use function CleanAnalytics\getAllRecords;
use function CleanAnalytics\getDailyCounts;

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

if (!isset($posts['days'])) {
    http_response_code(400);
    echo "days required";
    exit;
}
$days = intval($posts['days']);
if ($days < 1) {
    http_response_code(400);
    echo "invalid number of days";
    exit;
}

$timeStart = microtime(true);
$allRecords = getAllRecords($days);
$dailyCounts = getDailyCounts($allRecords);
$timeElapsed = microtime(true) - $timeStart;

echo json_encode(
    [
        "query-guid" => bin2hex(random_bytes(16)),
        "provided-days" => $days,
        "record-total-found" => count($allRecords),
        "record-count-by-day" => $dailyCounts,
    ]
);
