<?php

global $database;

$municipality = check_required_post_data(
    "municipality",
    "Municipality is required"
);

$barangay = check_required_post_data(
    "barangay",
    "Barangay is required"
);

$street = check_required_post_data(
    "street",
    "Street is required"
);

$access_token = check_required_post_data(
    "access_token",
    "Access token is required"
);

$unit = "";
$notes = "";

if (isset($_POST["unit"])) {
	$unit = $_POST["unit"];
}

if (isset($_POST["notes"])) {
	$notes = $_POST["notes"];
}

try {
    $get_token_query = "SELECT account_id FROM `access_tokens` WHERE token = :access_token";
    $token_response = $database->execute_query(
        $get_token_query,
        array(
            ":access_token"=>$access_token
        )
    );
    if (!isset($token_response[0])) {
        echo failed("Please provide a valid access token");
        return;
    }
    $account_id = ($token_response[0])["account_id"];
    $create_shipping_query = "INSERT INTO `shipping_addresses` (municipality, barangay, street, unit, notes, account_id) VALUES (:municipality, :barangay, :street, :unit, :notes, :account_id)";
    $database->execute_query(
        $create_shipping_query,
        array(
            ":municipality"=>$municipality,
            ":barangay"=>$barangay,
            ":street"=>$street,
            ":unit"=>$unit,
            ":notes"=>$notes,
            ":account_id"=>$account_id
        )
    );

    echo success(array());
} catch (Exception $err) {
    echo failed($err);
}