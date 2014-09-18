<?php
defined("__WINGMAN__") or exit("Chucks! this ain't working!");

### obj class
# SQL sanitizing done

class obj {
	public static function to( $format, $data ) {
		if (file_exists(__DIR__."/obj.to.$format.php")) {
			include_once(__DIR__."/obj.to.$format.php");
		} else {
			header("Content-type: text/html; charset=utf-8");
			header("Cache-Control: no-cache, must-revalidate");
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Content-Disposition: attachment; filename=error.html");
			echo "<div>No such conversion filter ($format) found.</div>";
		}
	}
}
?>
