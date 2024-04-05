<?php
    const PROJECT_ROOT_PATH = __DIR__ . "/../";

	require_once PROJECT_ROOT_PATH . "/utils/config.php";

	require_once PROJECT_ROOT_PATH . "/models/Database.php";

	require_once PROJECT_ROOT_PATH . "/utils/http_import.php";

	require_once PROJECT_ROOT_PATH . "/utils/response.php";

    global $server_name;
    global $database_name;
    global $username;
    global $password;

    header("Content-Type: application/json");
    header("Accept: application/x-www-form-urlencoded");

try {
    $database = new Database(
        $server_name,
        $database_name,
        $username,
        $password
    );
} catch (Exception $err) {
    echo failed("Couldn't connect to the database");
    exit();
}

?>