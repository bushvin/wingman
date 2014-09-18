<?php
defined("__WINGMAN__") or exit("Chucks! this ain't working!");

### obj.to.csv.php
# SQL sanitizing done

header("Content-type: application/csv; charset=utf-8");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

if (isset($data->target)) {
	header("Content-Disposition: attachment; filename=" .$data->target. ".csv");
} else {
	header("Content-Disposition: attachment; filename=file.csv");
}

if (isset($data->header)) {
	$header = array();
	foreach ($data->header as $t) {
		$header[] = addslashes($t);
	}
	echo "\"" .implode("\",\"",$header). "\"\n";
}

if (isset($data->data)) {
	foreach ($data->data as $line) {
		$values = array();
		foreach ($line as $value) {
			$values[] = addslashes($value);
		}
		echo "\"" .implode("\",\"",$values). "\"\n";
	}
}
?>
