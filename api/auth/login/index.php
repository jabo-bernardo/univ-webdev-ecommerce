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

    // Check if the email exists
    $check_existing__query = "SELECT * FROM `accounts` WHERE `email_address` = :email_address";

    $existing_response = $database->execute_query(
        $check_existing__query,
        array(
            ":email_address"=>$email_address
        )
    );
    if (!isset($existing_response[0])) {
        echo failed("Email does not exists");
        return;
    }

    $account = $existing_response[0];

    // Password check
    $password_matched = @password_verify($password, $account["password"]);
    if (!$password_matched) {
        echo failed("Password did not match. Please try again");
        return;
    }

    // Generate access token
    $token = md5(uniqid() . $account["id"]);

    $create_token__query = "INSERT INTO `access_tokens` (account_id, token) VALUES (:account_id, :token)";
    $database->execute_query(
        $create_token__query,
        array(
            ":account_id"=>$account["id"],
            ":token"=>$token
        )
    );

    echo success(array(
        "access_token"=>$token
    ));
} catch (PDOException $e) {
  if ($e->getCode() == 'HY000') {
    // Check if data was inserted correctly
    // If yes, ignore the exception or handle it accordingly
  } else {
    // If the error code is not 'HY000' or the data was not inserted correctly, rethrow the exception
    echo failed($e->getMessage());
    throw $e;
  }
}

