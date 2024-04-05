<?php

include_once "../../utils/bootstrap.php";

post_request("./create_product.php");
get_request("./list_products.php");
delete_request("./delete_product.php");