<?php

global $database;

try {
    $sql_query = "SELECT * FROM `orders` WHERE 1";

    $params = [];

    if (isset($_GET["account_id"])) {
        $sql_query .= " AND `account_id` = :account_id";
        $params[":account_id"] = $_GET["account_id"];
    }

    if (isset($_GET["status"])) {
        $sql_query .= " AND `status` = :status";
        $params[":status"] = $_GET["status"];
    }

    $db_response = $database->execute_query($sql_query, $params);

    echo success($db_response);
    return;
} catch (Exception $err) {
    echo failed($err);
}
