<?php

global $database;

$product_name = check_required_post_data(
    "name",
    "Product name is required"
);

$product_description = check_required_post_data(
    "description",
    "Product description is required"
);

$product_price = check_required_post_data(
    "price",
    "Product price is required"
);

$product_images = check_required_post_data(
    "images",
    "Product images are required"
);

$product_category_id = check_required_post_data(
    "category_id",
    "Product must be assigned in a category"
);

$individual_product_images = explode(",", $product_images);
$individual_product_images = array_filter(
    $individual_product_images,
    function($image) {
        return strlen(trim($image)) > 0;
    }
);

if (count($individual_product_images) == 0) {
    echo failed("You must provide at least 1 product image");
    return;
}

try {
    // Check if category exists
    $check_category_query = "SELECT * FROM `product_categories` WHERE id = :category_id";
    $category = $database->execute_query(
        $check_category_query,
        array(
            ":category_id"=>$product_category_id
        )
    );
    if (!isset($category[0])) {
        echo failed("Category does not exists.");
        return;
    }

    $create_product_query = "INSERT INTO `products` (name, description, price, category_id, images) VALUES (:name, :description, :price, :category_id, :images)";
    $database->execute_query(
        $create_product_query,
        array(
            ":name"=>$product_name,
            ":description"=>$product_description,
            ":price"=>$product_price,
            ":category_id"=>$product_category_id,
            ":images"=>join(",", $individual_product_images)
        )
    );

    echo success(array());
} catch (Exception $err) {
    echo failed($err);
}