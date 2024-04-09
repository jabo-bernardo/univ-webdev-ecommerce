<?php

include_once "../../../utils/bootstrap.php";

global $database;

if (!is_get_request()) {
    echo failed("Invalid method");
    return;
}

if (!isset($_GET["product_id"])) {
    echo failed("Please provide an account ID");
    return;
}

$product_id = $_GET["product_id"];

try {
    $sql_query = "SELECT `products`.`id`, `products`.`name`, `products`.`description`, `products`.`price`, `products`.`category_id`, `products`.`created_at`, `products`.`images`, `product_categories`.`name` as `category_name` FROM `products` INNER JOIN `product_categories` ON `products`.`category_id` = `product_categories`.`id` WHERE 1";

    $params = [];

    if (isset($product_id)) {
        $sql_query .= " AND `products`.`id` = :product_id";
        $params[":product_id"] = $product_id;
    }

    $sql_query .= " ORDER BY `created_at` DESC";

    $db_response = $database->execute_query($sql_query, $params);

    echo success($db_response);
    return;
} catch (Exception $err) {
    echo failed($err);
}
