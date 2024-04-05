<?php
global $database;

if (!isset($_GET["account_id"])) {
    echo failed("Please provide an account ID.");
    return;
}

$account_id = $_GET["account_id"];

try {
    $delete_query = "DELETE FROM `accounts` WHERE id = :account_id";
    $delete_response = $database->execute_query(
        $delete_query,
        array(
            ":account_id"=>$account_id
        )
    );
    echo success($delete_response);
} catch (Exception $err) {
    echo failed($err);
}