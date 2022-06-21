<?php

header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(400);
    echo "POST required";
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$response = array(
    "guid" => bin2hex(random_bytes(16)),
    "timestamp" => gmdate("c"),
    "ip" => $_SERVER['REMOTE_ADDR'],
    "url" => $data["url"],
    "ref" => $data["ref"],
    "agent" => $_SERVER['HTTP_USER_AGENT'],
);

$filename = "../../logs/" . gmdate("Y-m-d") . ".txt";
$logLine =
    $response["timestamp"] . " " .
    $response["ip"] . " " .
    $response["url"] . " " .
    $response["ref"] . " " .
    $response["agent"] . "\n";
file_put_contents($filename, $logLine, FILE_APPEND | LOCK_EX);

echo json_encode($response);
