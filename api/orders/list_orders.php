<?php

global $database;

try {
    $sql_query = "SELECT `orders`.id, `orders`.created_at, `orders`.status, `accounts`.first_name, `accounts`.last_name, `account_id` FROM `orders` INNER JOIN `accounts` ON orders.account_id = accounts.id WHERE 1";

    $params = [];

    if (isset($_GET["account_id"])) {
        $sql_query .= " AND `account_id` = :account_id";
        $params[":account_id"] = $_GET["account_id"];
    }

    if (isset($_GET["status"])) {
        $sql_query .= " AND `status` = :status";
        $params[":status"] = $_GET["status"];
    }

    if (isset($_GET["account_id"])) {
        $sql_query .= " AND `account_id` = :account_id";
        $params[":account_id"] = $_GET["account_id"];
    }

    $sql_query .= " ORDER BY `created_at` DESC";

    $db_response = $database->execute_query($sql_query, $params);

    echo success($db_response);
    return;
} catch (Exception $err) {
    echo failed($err);
}
