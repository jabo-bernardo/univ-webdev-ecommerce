<?php

include '../../../utils/bootstrap.php';

global $database;

if (!is_post_request()) {
    echo failed("Invalid method");
    return;
}

if (!isset($_POST["email_address"])) {
    echo failed("Email address is required.");
    return;
}

if (!isset($_POST["password"])) {
    echo failed("Password is required");
    return;
}

$email_address = $_POST["email_address"];
$password = $_POST["password"];

try {
    $conn = $database->get_connection();

    // Check for existing email address
    $check_existing_query = "SELECT id FROM `accounts` WHERE `email_address` = :email_address";

    $existing_response = $database->execute_query(
        $check_existing_query,
        array(
            ":email_address"=>$email_address
        )
    );
    if (isset($existing_response[0])) {
        echo failed("Email already exists.");
        return;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $create_query = "INSERT INTO `accounts` (email_address, password) VALUES (:email_address, :password)";
    $database->execute_query(
        $create_query,
        array(
            ":email_address"=>$email_address,
            ":password"=>$hashed_password
        )
    );

    echo success(array());
} catch(Exception $err) {
    echo failed($err);
}

