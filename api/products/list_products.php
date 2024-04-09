<?php


global $database;

try {
    $sql_query = "SELECT * FROM `products` WHERE 1";

    $params = [];

    if (isset($_GET["category_id"])) {
        $sql_query .= " AND `category_id` = :category_id";
        $params[":category_id"] = $_GET["category_id"];
    }

    $sql_query .= " ORDER BY `created_at` DESC";

    $db_response = $database->execute_query($sql_query, $params);

    echo success($db_response);
    return;
} catch (Exception $err) {
    echo failed($err);
}
