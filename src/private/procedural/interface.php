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

/**
 * @author: James
 */
function disallowDirectRequest() {
	// Prevent requests to this file directly.
	if (basename($_SERVER['REQUEST_URI']) == basename(__FILE__)) {
		echo 'You may not access this file directly: ' . basename($_SERVER['PHP_SELF']);
		die;
	}

	return true;
}

disallowDirectRequest();

function adminCheck() {
	global $user;

	if (!$user->isAdmin()) { 
		messageBox('You need to be an administrator in order to view this page.', 'Admin Check Failed', true);
	}
}

function implodeQuoted($a, $quoteChar = '"') {
	$ret = "";

	for ($i = 0; $i < sizeof($a); $i++) {
		$ret .= $quoteChar . $a[$i] . $quoteChar;

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
