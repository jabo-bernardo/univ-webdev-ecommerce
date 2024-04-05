<?php

include_once '../../utils/bootstrap.php';

post_request("./create_order.php");
get_request("./list_orders.php");