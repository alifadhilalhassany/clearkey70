<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$hex = $_GET["keyid"] ?? '';
$key = $_GET["key"] ?? '';

function hexToBase64Url($hex) {
    $bin = @hex2bin($hex);
    if ($bin === false) return '';
    return str_replace('=', '', base64_encode($bin));
}

$finalKeyId64 = hexToBase64Url($hex);
$finalKey64 = hexToBase64Url($key);

if (!$finalKeyId64 || !$finalKey64) {
    http_response_code(503);
    echo json_encode([
        "Status" => "503",
        "Content" => "Validation Failed!",
        "Reason" => "Invalid Key ID or Key."
    ]);
    exit;
}

echo json_encode([
    "keys" => [
        ["kty"=>"oct","k"=>$finalKey64,"kid"=>$finalKeyId64]
    ],
    "type"=>"temporary"
]);
exit;
