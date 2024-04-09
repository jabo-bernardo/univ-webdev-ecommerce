<?php

global $database;

$region = check_required_post_data(
    "region",
    "Region is required"
);

$barangay = check_required_post_data(
    "barangay",
    "Barangay is required"
);

$province = check_required_post_data(
    "province",
    "Province is required"
);

$city = check_required_post_data(
    "city",
    "City is required"
);

$unit = check_required_post_data(
    "unit",
    "Unit/House # is required"
);

$access_token = check_required_post_data(
    "access_token",
    "Access token is required"
);

$notes = "";
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
    $create_shipping_query = "INSERT INTO `shipping_addresses` (region, province, city, barangay, unit, notes, account_id) VALUES (:region, :province, :city, :barangay, :unit, :notes, :account_id)";
    $database->execute_query(
        $create_shipping_query,
        array(
            ":region"=>$region,
            ":province"=>$province,
            ":city"=>$city,
            ":barangay"=>$barangay,
            ":unit"=>$unit,
            ":notes"=>$notes,
            ":account_id"=>$account_id
        )
    );

    echo success(array());
} catch (Exception $err) {
    echo failed($err);
}