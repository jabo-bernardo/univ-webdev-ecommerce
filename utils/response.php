<?php

function success($data) {
	return json_encode(
		array(
			"success" => true,
			"data" => $data
		)
	);
}

function failed($reason) {
	return json_encode(
		array(
			"success" => false,
			"reason" => $reason
		)
	);
}

?>