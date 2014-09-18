<?php
defined("__WINGMAN__") or exit("Chucks! this ain't working!");

### obj.to.json.php
# SQL sanitizing done

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-type: application/json; charset=utf-8");

if (isset($data->target)) {
	header("Content-Disposition: attachment; filename=" .$data->target. ".js");
} else {
	header("Content-Disposition: attachment; filename=file.js");
}

echo json_encode($data);

?>
