<?php
include_once "../../../utils/bootstrap.php";

if (!is_post_request()) {
    echo failed("Invalid method");
    return;
}

$product_id = check_required_post_data(
    "product_id",
    "Please provide a product ID"
);

$product_name = $_POST["name"];
$product_description = $_POST["description"];
$product_price = $_POST["price"];
$product_images = $_POST["images"];
$product_category = $_POST["category_id"];

if (
    !isset($product_name) &&
    !isset($product_description) &&
    !isset($product_price) &&
    !isset($product_images) &&
    !isset($product_category)
) {
    echo success([]);
    return;
}

global $database;

try {
    // Check if product exists
    $product_exists_query = "SELECT * FROM `products` WHERE id = :product_id";
    $products = $database->execute_query(
        $product_exists_query,
        array(
            ":product_id"=>$product_id
        )
    );
    if (!isset($products[0])) {
        echo failed("Invalid product id.");
        return;
    }

    $sql_query = "UPDATE `products`" . " SET ";

    $params = array(
        ":product_id"=>$product_id
    );

    $has_other_field = false;

    if (isset($product_name)) {
        $sql_query .= " `name` = :name";
        $params[":name"] = $product_name;
        $has_other_field = true;
    }

    if (isset($product_description)) {
        if($has_other_field) $sql_query .= ", ";
        $sql_query .= " `description` = :description";
        $params[":description"] = $product_description;
        $has_other_field = true;
    }

    if (isset($product_price)) {
        if($has_other_field) $sql_query .= ", ";
        $sql_query .= " `price` = :price";
        $params[":price"] = $product_price;
        $has_other_field = true;
    }

    if (isset($product_category)) {
        // Check if category exists
        $check_category_query = "SELECT * FROM `product_categories` WHERE id = :category_id";
        $category = $database->execute_query(
            $check_category_query,
            array(
                ":category_id"=>$product_category
            )
        );
        if (!isset($category[0])) {
            echo failed("Category does not exists.");
            return;
        }

        if($has_other_field) $sql_query .= ", ";
        $sql_query .= " `category_id` = :category_id";
        $params[":category_id"] = $product_category;
        $has_other_field = true;
    }

    if (isset($product_images)) {
        $individual_product_images = explode(",", $product_images);
        $individual_product_images = array_filter(
            $individual_product_images,
            function($image) {
                return strlen(trim($image)) > 0;
            }
        );

        if($has_other_field) $sql_query .= ", ";
        $sql_query .= " `images` = :images";
        $params[":images"] = join(",", $individual_product_images);
        $has_other_field = true;
    }

    $sql_query .= " WHERE id = :product_id";

    echo success($database->execute_query($sql_query, $params));
} catch (Exception $err) {
    echo failed($err);
}