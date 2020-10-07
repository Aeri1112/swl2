<?php    
    #if (empty($_GET)) die();

    if($_GET){

        http_response_code(200);
        
        $side["test"] = true;

        echo json_encode([$_SERVER["REDIRECT_HTTP_AUTHORIZATION"]]);
    }
    else {
        // tell the user about error
        echo json_encode(["sent" => false, "message" => "Something went wrong"]);
    }
?>