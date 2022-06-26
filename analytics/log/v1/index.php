<?php

require_once __DIR__ . "/../../lib.php";
require_once __DIR__ . "/../../database.php";

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(400);
    echo "POST required";
    exit;
}

$posts = json_decode(file_get_contents('php://input'), true);
if ($posts == null) {
    http_response_code(400);
    echo "invalid JSON body";
    exit;
}

$requiredPostVariables = ['url', 'ref'];
foreach ($requiredPostVariables as $varName) {
    if (!isset($posts[$varName])) {
        http_response_code(400);
        echo "$varName required";
        exit;
    }
}

$ip = $_SERVER['REMOTE_ADDR'];
$url = $posts['url'];
$referrer = $posts['ref'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$timestamp = new DateTimeImmutable();

$record = new PageRecord($ip, $url, $referrer, $agent, $timestamp);
$record = getSanitizedRecord($record);
$logFilePath = writeToLogFile($record);

if ($logFilePath == null) {
    http_response_code(500);
    echo "error logging record to database";
    exit;
}

echo json_encode(
    [
        "guid" => bin2hex(random_bytes(16)),
        "timestamp" => $timestamp->format("c"),
        "ip" => $ip,
        "url" => $url,
        "ref" => $referrer,
        "agent" => $_SERVER['HTTP_USER_AGENT'],
        "logFilePath" => $logFilePath,
    ]
);
