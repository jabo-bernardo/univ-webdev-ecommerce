<?php

include_once "../../utils/bootstrap.php";

post_request("./create_shipping.php");
get_request("./list_shipping.php");