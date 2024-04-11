<?php

include_once "../../../utils/bootstrap.php";

global $database;

if (!is_get_request()) {
  echo failed("Invalid method");
  return;
}

if (!isset($_GET["shipping_address_id"])) {
  echo failed("Please provide an shipping address ID");
  return;
}

$shipping_address_id = $_GET["shipping_address_id"];

try {
  $conn = $database->get_connection();

  $sql_query = "SELECT *FROM `shipping_addresses` WHERE `id` = :shipping_address_id";

  $params = [
    ":shipping_address_id" => $shipping_address_id
  ];

  $shipping_response = $database->execute_query($sql_query, $params);

  echo success($shipping_response);
  return;
} catch (Exception $err) {
  echo failed($err);
}
