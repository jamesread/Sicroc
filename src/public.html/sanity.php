<html>
	<head>
		<title>Sanity Checks</title>

		<style type = "text/css">
		body {
			font-family: Verdana;
			font-size: 9pt;
		}
		</style>
	</head>

	<body>
<?php
/**
This file is designed to check the environment for sanity, it can be used
for debugging, installers, etc. 

It should NOT include/require any other files or non-standard modules.
*/

error_reporting(E_ALL | E_STRICT);
	
abstract class SanityChecks {
	private static $notices = 0;
	private static $warnings = 0;
	private static $errors = 0;
	private static $passes = 0;

	public static function noticeable($result, $expected, $message) {
		if ($result === $expected) {
			SanityChecks::pass($message);
		} else {
			SanityChecks::output('ffc', $message);
		}
	}

	public static function warningable($result, $expected, $message) {
		if ($result === $expected) {
			SanityChecks::pass($message);
		} else {
			SanityChecks::output('ffc', $message);
		}
	}

	public static function errorable($result, $expected, $message) {
		if ($result === $expected) {
			SanityChecks::pass($message);		
		} else {
			SanityChecks::output('fcc', $message);
			SanityChecks::$errors++;
		}
	}

	private static function output($color, $message) {
		echo '<p style = "background-color: #' . $color . '">';
		echo $message;
		echo '</p>';
	}

	private static function pass($message) {
		SanityChecks::output('cfc', $message);
		SanityChecks::$passes++;
	}

	public static function printTotals() {
		$total = SanityChecks::$passes + SanityChecks::$notices + SanityChecks::$warnings + SanityChecks::$errors; 
		echo '<p>', $total, ' tests were run, ', floor(SanityChecks::$passes / $total * 100), '% of them completed successfully.</p>';
	}
}

echo '<html><head><title>SanityChecks</title>';
echo '<style type = "text/css">p { padding: 1em;}</style>';
echo '</head><body>';

// Check sum myself!
SanityChecks::errorable(version_compare(PHP_VERSION, '5.0.0', '>'), 1, 'Hello');
SanityChecks::errorable(1, 1, "...");
SanityChecks::warningable(1, 1, "...");

SanityChecks::printTotals();

// Check PHP version

echo '</body></html>';
?>
	</body>
</html>
