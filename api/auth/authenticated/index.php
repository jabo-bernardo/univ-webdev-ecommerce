<?php

include_once "../../../utils/bootstrap.php";

global $database;

if (!is_post_request()) {
    echo failed("Invalid method");
    return;
}

$access_token = check_required_post_data(
    "access_token",
    "Please provide an access_token"
);

try {
    $conn = $database->get_connection();

    $sql_query = "SELECT * FROM `access_tokens` INNER JOIN `accounts` ON `accounts`.`id` = `access_tokens`.`account_id` WHERE `token` = :access_token";

    $params = [
        ":access_token" => $access_token
    ];

    $account_response = $database->execute_query($sql_query, $params);

    echo success($account_response);
    return;
} catch (Exception $err) {
    echo failed($err);
}
