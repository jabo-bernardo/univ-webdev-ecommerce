<?php

include_once "../../../utils/bootstrap.php";

global $database;

if (!is_post_request()) {
    echo failed("Invalid method");
    return;
}

$order_id = check_required_post_data(
    "order_id",
    "Please provide an order ID"
);

$status = @$_POST["status"];

if (!isset($status)) {
    echo success([]);
    return;
}

try {
    $update_order_query = "UPDATE `orders` SET `status` = :status WHERE id = :order_id";
    $update_response = $database->execute_query(
        $update_order_query,
        array(
            ":status"=>$status,
            ":order_id"=>$order_id
        )
    );
    echo success([]);
} catch(Exception $err) {
    echo failed($err);
}