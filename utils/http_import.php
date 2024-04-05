<?php
	function get_request($file_include) {
		handle_request($file_include, "GET");
	}

	function post_request($file_include) {
		handle_request($file_include, "POST");
	}

    function delete_request($file_include) {
        handle_request($file_include, "DELETE");
    }

    function patch_request($file_include) {
        handle_request($file_include, "PATCH");
    }

	function handle_request($file_include, $request_method) {
		if ($_SERVER["REQUEST_METHOD"] == $request_method) {
			include $file_include;
		}
	}

    function is_post_request() {
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }

    function is_get_request() {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    function is_delete_request() {
        return $_SERVER["REQUEST_METHOD"] == "DELETE";
    }

    function is_patch_request() {
        return $_SERVER["REQUEST_METHOD"] == "PATCH";
    }

    function check_required_post_data($data_name, $error_response) {
        if (!isset($_POST[$data_name])) {
            echo failed($error_response);
            exit();
        }
        return $_POST[$data_name];
    }
?>