<?php

global $database;

try {

    $sql_query = "SELECT * FROM `product_categories`";
    $db_response = $database->execute_query($sql_query);

    echo success($db_response);
    return;
} catch (Exception $err) {
    echo failed($err);
}
