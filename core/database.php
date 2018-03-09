<?php
    header('Content-Type: application/json');

    $database["products"][12]["type"] = "bb";
    $database["products"][12]["name"] = "Broadband1";
    $database["products"][12]["price"] = 40;
    
    $database["products"][16]["type"] = "bb";
    $database["products"][16]["name"] = "Broadband2";
    $database["products"][16]["price"] = 60;
    
    $database["products"][18]["type"] = "tv";
    $database["products"][18]["name"] = "TV1";
    $database["products"][18]["price"] = 50;
    
    $database["products"][10]["type"] = "tv";
    $database["products"][10]["name"] = "TV2";
    $database["products"][10]["price"] = 120;
    
    $database["products"][8]["type"] = "ll";
    $database["products"][8]["name"] = "Landline";
    $database["products"][8]["price"] = 35;

    $database["products"][14]["type"] = "addon";
    $database["products"][14]["name"] = "AddonTV";
    
    $database["products"][21]["type"] = "addon";
    $database["products"][21]["name"] = "AddonBB";
    $database["products"][21]["price"] = 10;

    $database["addons"]["tv"] = [14];
    
    $database["addons"]["bb"] = [21];

    $database["bundles"][16] = array(
        18 => -10,
        8 => -40,
        10 => -20,
        21 => -10,
    );

    $database["bundles"][18] = array(
        14 => 35,
        8 => -10,
        16 => -10,
    );

    $database["bundles"][12] = array(
        8 => -40,
        21 => 0,
    );

    $database["bundles"][8] = array(
        12 => -40,
        10 => -30,
        16 => -40,
        18 => -10,
    );

    $database["bundles"][10] = array(
        8 => -30,
        16 => -20,
        14 => 15,
    );

    echo json_encode($database);
?>