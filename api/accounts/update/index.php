<?php
include_once "../../../utils/bootstrap.php";

if (!is_post_request()) {
    echo failed("Invalid method");
    return;
}

$account_id = check_required_post_data(
    "account_id",
    "Please provide a account ID"
);

$first_name = @$_POST["first_name"];
$last_name = @$_POST["last_name"];
$email_address = @$_POST["email_address"];
$contact_number = @$_POST["contact_number"];

if (
    !isset($first_name) &&
    !isset($last_name) &&
    !isset($email_address) &&
    !isset($contact_number)
) {
    echo success([]);
    return;
}

global $database;

try {
    // Check if product exists
    $account_exists_query = "SELECT * FROM `accounts` WHERE id = :account_id";
    $accounts = $database->execute_query(
        $account_exists_query,
        array(
            ":account_id"=>$account_id
        )
    );
    if (!isset($accounts[0])) {
        echo failed("Invalid account id.");
        return;
    }

    $sql_query = "UPDATE `accounts`" . " SET ";

    $params = array(
        ":account_id"=>$account_id
    );

    $has_other_field = false;

    if (isset($first_name)) {
        $sql_query .= " `first_name` = :first_name";
        $params[":first_name"] = $first_name;
        $has_other_field = true;
    }

    if (isset($last_name)) {
        if($has_other_field) $sql_query .= ", ";
        $sql_query .= " `last_name` = :last_name";
        $params[":last_name"] = $last_name;
        $has_other_field = true;
    }

    if (isset($email_address)) {
        if($has_other_field) $sql_query .= ", ";
        $sql_query .= " `email_address` = :email_address";
        $params[":email_address"] = $email_address;
        $has_other_field = true;
    }

    if (isset($contact_number)) {
        if($has_other_field) $sql_query .= ", ";
        $sql_query .= " `contact_number` = :contact_number";
        $params[":contact_number"] = $contact_number;
        $has_other_field = true;
    }

    $sql_query .= " WHERE id = :account_id";

    echo success($database->execute_query($sql_query, $params));
} catch (Exception $err) {
    echo failed($err);
}