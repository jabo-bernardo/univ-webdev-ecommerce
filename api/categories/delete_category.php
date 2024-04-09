<?php
global $database;

if (!isset($_GET["category_id"])) {
  echo failed("Please provide a category ID.");
  return;
}

$category_id = $_GET["category_id"];

try {
  $delete_query = "DELETE FROM `product_categories` WHERE id = :category_id";
  $delete_response = $database->execute_query(
    $delete_query,
    array(
      ":category_id"=>$category_id
    )
  );
  echo success($delete_response);
} catch (Exception $err) {
  echo failed($err);
}