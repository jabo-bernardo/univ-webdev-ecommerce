<?php

include_once "../../utils/bootstrap.php";

post_request('./create_category.php');
get_request('./list_categories.php');