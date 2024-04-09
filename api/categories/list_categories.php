<?php

global $database;

try {

    $sql_query = "SELECT `product_categories`.*, COUNT(`products`.id) as `product_count` FROM `product_categories` LEFT JOIN `products` ON `product_categories`.id = `products`.category_id GROUP BY `product_categories`.id";
    $db_response = $database->execute_query($sql_query);

    echo success($db_response);
    return;
} catch (Exception $err) {
    echo failed($err);
}
