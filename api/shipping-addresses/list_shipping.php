<?php

global $database;

try {
  $conn = $database->get_connection();

  $sql_query = "SELECT * FROM `shipping_addresses` WHERE 1";

  $params = [];

  if (isset($_GET["account_id"])) {
    $sql_query .= " AND account_id = :account_id";
    $params[":account_id"] = $_GET["account_id"];
  }

  $shipping_response = $database->execute_query($sql_query, $params);

  echo success($shipping_response);
  return;
} catch (Exception $err) {
  echo failed($err);
}
