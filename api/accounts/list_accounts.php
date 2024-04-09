<?php

global $database;

try {
    $conn = $database->get_connection();

    $sql_query = "SELECT `id`, `created_at`, `first_name`, `last_name`, `email_address`, `contact_number`, `role` FROM `accounts` WHERE 1";

    $params = [];

    if (isset($_GET["role"])) {
        $sql_query .= " AND role = :role";
        $params[":role"] = $_GET["role"];
    }

    $account_response = $database->execute_query($sql_query, $params);

    echo success($account_response);
    return;
} catch (Exception $err) {
    echo failed($err);
}
