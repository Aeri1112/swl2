<?php
    require("../database.php");
    
   header('Access-Control-Allow-Origin: *');
   header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
   header("Access-Control-Allow-Headers: Content-Disposition, Content-Type, Content-Length, Accept-Encoding");
   header("Content-type:application/json");
    $rest_json = file_get_contents("php://input");
    $_POST = json_decode($rest_json, true);
    
    if (empty($_POST)) die();
    
    if ($_POST)
        {
        // set response code - 200 OK
        http_response_code(200);

        $stm = $pdo->prepare("SELECT * FROM jedi_alliances WHERE id = :id");
        $stm->execute(["id" => $_POST["id"]]);

        $result = $stm->fetch();
        echo json_encode($result);
        }
    else
        {

        // tell the user about error

        echo json_encode(["sent" => false, "message" => "Something went wrong"]);
        }

?>