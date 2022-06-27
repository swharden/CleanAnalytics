<?php

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(400);
    echo "POST required supported";
    exit;
}

require_once "../../lib.php";
require_once "../../database.php";

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$posts = json_decode(file_get_contents('php://input'), true);
if ($posts == null) {
    http_response_code(400);
    echo "invalid JSON body";
    exit;
}

if (!isset($posts['count'])) {
    http_response_code(400);
    echo "count required";
    exit;
}
$count = intval($posts['count']);
if ($count < 1) {
    http_response_code(400);
    echo "invalid count";
    exit;
}

$timeStart = microtime(true);
$records = \CleanAnalytics\getLatestRecords($count);
$timeElapsed = microtime(true) - $timeStart;

echo json_encode(
    [
        "guid" => bin2hex(random_bytes(16)),
        "count" => intval($count),
        "records" => $records,
        "elapsed" => $timeElapsed,
    ]
);
