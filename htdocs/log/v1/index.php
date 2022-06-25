<?php
include "lib.php";

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(400);
    echo "POST required";
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$date = new DateTimeImmutable();

$response = array(
    "guid" => bin2hex(random_bytes(16)),
    "timestamp" => $date->format("c"),
    "ip" => $_SERVER['REMOTE_ADDR'],
    "url" => $data["url"],
    "ref" => $data["ref"],
    "agent" => $_SERVER['HTTP_USER_AGENT'],
);

$success = writeStatistics(
    getLogfileLocation($date),
    $date,
    $response["ip"],
    $response["url"],
    $response["ref"],
    $response["agent"],
);

if (false === $success) {
    http_response_code(500);
    echo json_encode([
        'error' => 'could not write file'
    ]);
}

echo json_encode($response);
