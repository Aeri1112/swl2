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
    
        // data
        $stm = $pdo->prepare("SELECT * FROM jedi_user_chars WHERE userid = :id");
        $stm->execute(["id" => $_POST["userId"]]);
        $char = $stm->fetch();

        $stm = $pdo->prepare("SELECT * FROM jedi_user_skills WHERE userid = :id");
        $stm->execute(["id" => $_POST["userId"]]);
        $skills = $stm->fetch();

        $skills["next_level_xp"] = round(((15 * ($skills["level"] * $skills["level"])) + 100 + pow(4,($skills["level"]/12))));
        $skills["max_health"] = ($skills["level"] * 2) + (($skills["cns"]) * 3) + 20 ;
        $skills["max_mana"] = round(($skills["level"] * 1.5) + ($skills["spi"] * 4) + ($skills["itl"] / 2.5) + 10);
        $skills["max_energy"] = round(((($skills["level"] / 12.5) + ($skills["cns"] / 33) + ($skills["agi"] / 66))  * 3.3)+50,0);

        $skills["level_width"] = round($skills["xp"] * 100 / $skills["next_level_xp"], 2);
        $skills["health_width"] = round($char["health"] * 100 / $skills["max_health"]);
        $skills["mana_width"] = round($char["mana"] * 100 / $skills["max_mana"]);
        $skills["energy_width"] = round($char["energy"] * 100 / $skills["max_energy"]);

        $side["perc"] = round((abs($skills["side"]) / 32768 * 100),2);

        if($skills["side"] < 0)
        {
            $side["side"] = "dark";
            $side["white_begin"] = 50+($side["perc"]/2);
        }
        elseif($skills["side"] == 0)
        {
            $side["side"] = "neutral";
            $side["white_begin"] = 50;
        }
        else
        {
            $side["side"] = "light";
            $side["white_begin"] = 50-($side["perc"]/2);
        }
  
        echo json_encode(
            [$side, $char, $skills]
        );
        }
      else
        {
    
        // tell the user about error
    
        echo json_encode(["sent" => false, "message" => "Something went wrong"]);
        }
?>