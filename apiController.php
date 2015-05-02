<?PHP
// KantColle Open Source Server
// print_r($_GET);

define("KANTCOLLE", "fubuki");
// Setup things that we need.
require "system/config.inc.php";
require "system/functions.inc.php";

$dbc = new mysqli($db['host'], $db['user'], $db['password'], $db['database']);
if($dbc->connect_error) svdata("", 201);


// If we're getting the game to boot, don't do anything else but feed it the JSON blob.
if($_GET['apiEndpoint'] == "api_start2") {
	print trim(file_get_contents("./blobs/api_start2.json"));
	exit;
}


// Turn the endpoint request into an array, so we know which module to route the request to.
$request = explode("/", trim($_GET['apiEndpoint']));
$our_endpoint = $request[1];

switch($request[0]) {
	// Member Data
	case "api_get_member":
		require("apiCore/member_data.inc.php");
	break;

	case "api_req_member":
		require("apiCore/member_reqdata.inc.php");
	break;

	// Everything that's currently in our naval base?
	case "api_port":
		require("apiCore/port_data.inc.php");
	break;

	// Ship construction
	case "api_req_kousyou":
		require("apiCore/ship_construction.inc.php");
	break;

	// Equipment change?
	case "api_req_kaisou":
		require("apiCore/equipment_change.inc.php");
	break;

	// Fleet rearrangement
	case "api_req_hensei":
		require("apiCore/fleet_change.inc.php");
	break;
	
}

?>
