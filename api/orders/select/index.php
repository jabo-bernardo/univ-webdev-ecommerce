<?php


include_once "../../../utils/bootstrap.php";

global $database;

if (!is_get_request()) {
    echo failed("Invalid method");
    return;
}

if (!isset($_GET["order_id"])) {
    echo failed("Please provide an account ID");
    return;
}

$order_id = $_GET["order_id"];

try {
    $sql_query = "SELECT * FROM `orders` WHERE `id` = :order_id";

    $sql_query .= " ORDER BY `created_at` DESC";


    $db_response = $database->execute_query($sql_query, [
        ":order_id" => $order_id
    ]);

    $order_items_query = "SELECT * FROM `order_items` INNER JOIN `products` ON `order_items`.product_id = `products`.id WHERE `order_id` = :order_id";
    $order_items_response = $database->execute_query($order_items_query, [
        ":order_id" => $order_id
    ]);


    echo success(array(
        "order" => $db_response,
        "order_items" => $order_items_response
    ));
    return;
} catch (Exception $err) {
    echo failed($err);
}
