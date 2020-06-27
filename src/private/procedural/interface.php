<?php

$markers = 0;
function marker() {
	global $markers;
	
	echo 'marker ' . $markers++ . '<br />';
}

function callPhpFunc($functionName) {
	try {
		ob_start();
		
		$result = call_user_func($funcionName);
			
		$contents = ob_get_contents();
		ob_end_clean();
		
		if (sizeof($contents == 0)) {
			return $result;
		} else {
			return $contents;
		}
	} catch (Exception $e) {
		return "Exception!" . $e;
	}
}

function messageBox($message, $title = 'Message', $die = false) {
	echo '<div class = "messageBox"><h2>' . $title . '</h2>' . "\n<p>" . $message . '</p></div>';

	if ($die) {
		require_once 'widgets/footer.php';
	}
}

function adminCheck() {
	global $user;

	if (!$user->isAdmin()) { 
		messageBox('You need to be an administrator in order to view this page.', 'Admin Check Failed', true);
	}
}

function implodeQuoted($a, $quoteChar = '"', $useNulls = false) {
	$ret = "";

	for ($i = 0; $i < sizeof($a); $i++) {
		if (empty($a[$i]) && $useNulls) {
			$ret .= 'NULL';
		} else {
			$ret .= $quoteChar . $a[$i] . $quoteChar;
		}

		if ($i + 1 != sizeof($a)) {
			$ret .= ', ';
		}
	}

	return $ret;
}

function redirect($url) {
	header('Location:' . $url);
}

?>
