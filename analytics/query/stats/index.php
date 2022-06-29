<?php

/**
 * This endpoint returns page view and user stats
 * for a specified time range.
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

$timeStart = microtime(true);
$records = \CleanAnalytics\getAllRecords(); // TODO: pass number of days in here
$pageCount = count($records);
$userCount = \CleanAnalytics\getUserCount($records);

echo json_encode(
    [
        "request-guid" => bin2hex(random_bytes(16)),
        "request-days" => $days,
        "execution-records-read" => count($records),
        "execution-time-sec" => microtime(true) - $timeStart,
        "result-page-count" => $pageCount,
        "result-user-count" => $userCount,
    ]
);
