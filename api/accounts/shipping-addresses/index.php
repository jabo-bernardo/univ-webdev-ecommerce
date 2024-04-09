<?php


include_once "../../../utils/bootstrap.php";

global $database;

if (!is_get_request()) {
    echo failed("Invalid method");
    return;
}

if (!isset($_GET["access_token"])) {
    echo failed("Please provide an access token");
    return;
}

$access_token = $_GET["access_token"];

try {
    $conn = $database->get_connection();

    $get_user_query = "SELECT `account_id` FROM `access_tokens` WHERE `token` = :access_token";
    $user_response = $database->execute_query($get_user_query, [":access_token" => $access_token]);
    if (!$user_response[0]) {
        echo failed("Invalid access token");
        return;
    }

    $sql_query = "SELECT * FROM `shipping_addresses` WHERE `account_id` = :account_id";

    $params = [
        ":account_id" => $user_response[0]["account_id"]
    ];

    $account_response = $database->execute_query($sql_query, $params);

    echo success($account_response);
    return;
} catch (Exception $err) {
    echo failed($err);
}
