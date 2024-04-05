<?php
global $database;

if (!isset($_GET["product_id"])) {
    echo failed("Please provide a product ID.");
    return;
}

$product_id = $_GET["product_id"];

try {
    $delete_query = "DELETE FROM `products` WHERE id = :product_id";
    $delete_response = $database->execute_query(
        $delete_query,
        array(
            ":product_id"=>$product_id
        )
    );
    echo success($delete_response);
} catch (Exception $err) {
    echo failed($err);
}