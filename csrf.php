<?php

    header("Access-Control-Allow-Origin: http://localhost:3000 ");
    header("Accept: application/json");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    echo json_encode($_COOKIE);

?>