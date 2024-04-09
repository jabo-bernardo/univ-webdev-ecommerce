<?php

include_once "../../../utils/bootstrap.php";

global $database;

if (!is_get_request()) {
    echo failed("Invalid method");
    return;
}

if (!isset($_GET["account_id"])) {
    echo failed("Please provide an account ID");
    return;
}

$account_id = $_GET["account_id"];

try {
    $conn = $database->get_connection();

    $sql_query = "SELECT `id`, `created_at`, `first_name`, `last_name`, `email_address`, `contact_number`, `role` FROM `accounts` WHERE `id` = :account_id";

    $params = [
        ":account_id" => $account_id
    ];

    $account_response = $database->execute_query($sql_query, $params);

    echo success($account_response);
    return;
} catch (Exception $err) {
    echo failed($err);
}
