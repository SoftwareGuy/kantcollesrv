<?PHP
// KantColle Open Source Server
// WebUI Login / Play Page

// Grab the things we need
define("KANTCOLLE", "fubuki");
require "system/config.inc.php";
require "system/functions.inc.php";

// Attempt database connection
$dbc = new mysqli($db['host'], $db['user'], $db['password'], $db['database']);
if($dbc->connect_error) die("ERROR: SQL Connection Failure. Aborting - inform sysadmin!");

// Start (or resume) our session
session_start();

if($_GET['act']) {
	switch($_GET['act']) {

	case 'play':
		require "system/webUI/play.inc.php";
	break;

	case 'login':
		require "system/webUI/login_processor.inc.php";
	break;

	case 'register':
		require "system/webUI/register_processor.inc.php";
	break;

	default: 
		require "system/webUI/login.inc.php";
	break;
	}
} else {
	require "system/webUI/login.inc.php";
}
?>