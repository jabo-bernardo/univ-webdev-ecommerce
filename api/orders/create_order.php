<?php

global $database;

$access_token = check_required_post_data(
    "access_token",
    "Access token is required"
);

$shipping_address_id = check_required_post_data(
    "shipping_address_id",
    "Shipping address is required!"
);

$order_items = check_required_post_data(
    "order_items",
    "You must place at least one item in your cart."
);

$order_attachments = $_POST["order_attachments"];
$individual_order_attachments = "";

if (isset($order_attachments)) {
    $individual_order_attachments = explode(",", $order_attachments);
    $individual_order_attachments = array_filter(
        $individual_order_attachments,
        function($image) {
            return strlen(trim($image)) > 0;
        }
    );
}

$individual_orders = explode(",", $order_items);
$individual_orders = array_filter(
    $individual_orders,
    function($image) {
        return strlen(trim($image)) > 0;
    }
);

if (count($individual_orders) == 0) {
    echo failed("You must place at least one item in your cart.");
    return;
}

try {
    // Check access token
    $get_token_query = "SELECT `account_id` FROM `access_tokens` WHERE `token` = :access_token";
    $token_response = $database->execute_query(
        $get_token_query,
        array(
            ":access_token"=>$access_token
        )
    );
    if (!isset($token_response[0])) {
        echo failed("Please provide a valid access token");
        return;
    }
    $account_id = ($token_response[0])["account_id"];

    // Check shipping address
    $get_shipping_query = "SELECT id FROM `shipping_addresses` WHERE account_id = :account_id AND id = :shipping_address_id";
    $shipping_response = $database->execute_query(
        $get_shipping_query,
        array(
            ":account_id"=>$account_id,
            ":shipping_address_id"=>$shipping_address_id
        )
    );
    if (!isset($shipping_response[0])) {
        echo failed("Please provide a valid shipping address");
        return;
    }

    // Check if all products exists.
    $in = str_repeat("?, ", count($individual_orders) - 1) . '?';
    $get_product_query = "SELECT id FROM `products` WHERE id IN ($in)";
    $product_response = $database->execute_query(
        $get_product_query,
        $individual_orders
    );
    if (count($product_response) != count($individual_orders)) {
        echo failed("Please provide a valid product!");
        exit();
    }

    // Create order
    $create_order_query = "INSERT INTO `orders` (`status`, `account_id`, `shipping_address_id`, `attached_files`) VALUES (:status, :account_id, :shipping_address_id, :attached_files)";
    $create_order_response = $database->execute_query(
        $create_order_query,
        array(
            ":status"=>"Awaiting Payment",
            ":account_id"=>$account_id,
            ":shipping_address_id"=>$shipping_address_id,
            ":attached_files"=>join(",", $individual_order_attachments)
        )
    );

    $order_id = $database->get_connection()->lastInsertId();

    // TODO: Optimize
    foreach ($individual_orders as $order) {
        $create_order_item_query = "INSERT INTO `order_items` (`product_id`, `order_id`) VALUES (:product_id, :order_id)";
        $create_order_response = $database->execute_query(
            $create_order_item_query,
            array(
                ":product_id"=>$order,
                ":order_id"=>$order_id
            )
        );
    }

    echo success(
        [
            "order_id"=>$order_id
        ]
    );
} catch(Exception $err) {
    echo failed($err);
}