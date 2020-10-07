<?php
header("Access-Control-Allow-Origin: http://localhost:3000 ");
#header("Accept: application/json");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
$rest_json = file_get_contents("php://input");

$data = json_decode($rest_json, true);

echo $this->fetch('content');
?>