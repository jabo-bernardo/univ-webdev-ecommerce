<?php


include "../../../utils/bootstrap.php";

global $database;

if (!is_post_request()) {
    echo failed("Invalid request method");
    return;
}

$access_token = check_required_post_data(
    "access_token",
    "Access token is required"
);

$target_dir = "../../../uploads/";
$original_file_name = basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));
$file_name = md5($original_file_name . uniqid());
$target_file = $target_dir . $file_name . "." . $imageFileType;
$uploadOk = 1;

try {
    $conn = $database->get_connection();

    $sql_query = "SELECT * FROM `access_tokens` INNER JOIN `accounts` ON `accounts`.`id` = `access_tokens`.`account_id` WHERE `token` = :access_token";

    $params = [
        ":access_token" => $access_token
    ];

    $account_response = $database->execute_query($sql_query, $params);

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check === false) {
            echo failed("File is not an image.");
            $uploadOk = 0;
            exit();
        }
    }

    if (file_exists($target_file)) {
        echo failed("Something went wrong in our end. Please try again.");
        $uploadOk = 0;
        exit();
    }

    $ONE_MB = 1000000;
    $FILE_LIMIT = $ONE_MB * 15;
    if ($_FILES["fileToUpload"]["size"] > $FILE_LIMIT) {
        echo failed("Sorry, your file is too large.");
        $uploadOk = 0;
        exit();
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "pdf" && $imageFileType != "docx" && $imageFileType != "doc") {
        echo failed("Sorry, only JPG, JPEG, PNG, GIF, PDF, DOCX, & DOC files are allowed.");
        $uploadOk = 0;
        exit();
    }

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $insert_query = "INSERT INTO `files` (original_file_name, file_name, account_id) VALUES (:original_file_name, :file_name, :account_id)";
        $database->execute_query(
            $insert_query,
            array(
                ":original_file_name" => $original_file_name,
                ":file_name" => $file_name . "." . $imageFileType,
                ":account_id" => $account_response[0]["account_id"]
            )
        );
        echo success(array(
            "file_name" => $file_name . "." . $imageFileType
        ));
    } else {
        echo failed("Sorry, there was an error uploading your file.");
        exit();
    }

} catch (Exception $err) {
    echo failed($err);
}


