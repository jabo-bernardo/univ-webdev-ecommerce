<?php

global $database;

if (!isset($_POST["name"])) {
    echo failed("Please provide the required parameters");
    return;
}

$category_name = $_POST["name"];

try {
    $conn = $database->get_connection();

    $sql_query = "INSERT INTO `product_categories` (name) VALUES (:name)";
    $query = $conn->prepare($sql_query);
    $query->bindParam(":name", $category_name);
    $query->execute();

    echo success(array());
    return;
} catch (Exception $err) {
    echo failed($err);
}
