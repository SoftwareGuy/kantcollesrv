<?PHP
// KantColle Open Source Server
// Registration Script

// This script requires POST data, if we don't have it, go away.
if(!$_POST) exit;

// Check a few things...
$inputData = array (
	"nickname" => trim($_POST['new_admiral_nickname']),
	"password" => sha1($_POST['new_admiral_password']),
	"shipgirl" => ""
);

if(empty($_POST['new_admiral_nickname'])) die("ERROR: You need to give an nickname for your new account.");
if(!filter_var($_POST['new_admiral_email'], FILTER_VALIDATE_EMAIL)) die("ERROR: Invalid email address.");
else $inputData['email'] = trim(filter_var($_POST['new_admiral_email'], FILTER_SANITIZE_EMAIL));

if(empty($_POST['new_admiral_shipgirl'])) $inputData['shipgirl'] = "fubuki";
else $inputData['shipgirl'] = trim($_POST['new_admiral_shipgirl']);

// Step 1: Check if a user exists with that admiral nickname...
$query = "SELECT COUNT(*) FROM `admirals` WHERE `nickname` = ?";

if($stmt = $dbc->prepare($query)) {
	$stmt->bind_param("s", $inputData['nickname']);
	$stmt->execute();
	
	$stmt->bind_result($result);
	$stmt->fetch();
	
	if($result != 0) die("ERROR: Admiral already exists with that nickname.");

	$stmt->close();
}
// Step 2: Check if a user exists with that email...
$query = "SELECT COUNT(*) FROM `admirals` WHERE `nickname` = ?";

if($stmt = $dbc->prepare($query)) {
	$stmt->bind_param("s", $inputData['email']);
	$stmt->execute();
	
	$stmt->bind_result($result);
	$stmt->fetch();
	
	if($result != 0) die("ERROR: Admiral already exists with that email.");

	$stmt->close();
}

// Step 3: If all things are clear, insert and setup their account, along with setting up their first ship.
$query = "INSERT INTO `admirals` (nickname, password, email) VALUES (?,?,?)";

if($stmt = $dbc->prepare($query)) {
	$stmt->bind_param("sss", $inputData['nickname'], $inputData['password'], $inputData['email']);
	$stmt->execute();

	if($stmt->affected_rows == 1) {
		$new_admiral_id = $stmt->insert_id;
		print "INFO: Admiral enlisted successfully. <br>";	// TODO: Pipe stuff to success boxes
		print "DEBUG: {$new_admiral_id} is your internal admiral ID. <br>";
	}
	else die("ERROR: Admiral enlistment denied.");

	$stmt->close();
}

// Okay, so we have our starter ships, so we need to enlist them as well...
switch($inputData['shipgirl']) {
	case 'fubuki':
		$ship_id = 9;
		$equipment = array ( 2, 13 );
	break;

	case 'inazuma':
		$ship_id = 37;
		$equipment = array ( 2 );
	break;

	case 'murakumo':
		$ship_id = 33;
		$equipment = array ( 2 );
	break;

	case 'samidare':
		$ship_id = 46;
		$equipment = array ( 2 );
	break;

	case 'sazanami':
		$ship_id = 94;
		$equipment = array ( 2 );

	break;

	default:
	// If for whatever reason shit doesn't work
	// We give them a fubuki.
		$ship_id = 9;
		$equipment = array ( 2, 13 );
	break;
}

// Now, we need to fetch the girl's stuff from the API master file.
$apiMasterData = json_decode(str_replace("svdata=", "", file_get_contents("./blobs/api_start2.json")), true, 2048);
$apiMasterShips = $apiMasterData['api_data']['api_mst_ship'];
$apiMasterEquip = $apiMasterData['api_data']['api_mst_slotitem'];

// We don't need the rest of that file. Free up some memory.
unset($apiMasterData);

// Search for the shipgirl...
// There has to be a better way of doing this!
$ship_data = array();

for($i = 0; $i < count($apiMasterShips); $i++) {
	if($apiMasterShips[$i]['api_id'] == $ship_id) {
		print "DEBUG: Found API Data Ship ID {$ship_id} ({$apiMasterShips[$i]['api_name']}) ! <br>";
		$ship_data = $apiMasterShips[$i];
	}
}

// First insert the shipgirl into the fleet.
/* 
$query = "INSERT INTO `ships_enlisted` (admiral_id, ship_id, game_id, curr_health, max_health, experience) VALUES(?,?,?,?,?,?)";
if($stmt = $dbc->prepare($query)) {
	$first_ship = 1;	// This ship is our first.

	// Apparently the API Master File doesn't have any info on the HP values of the ships...
	// So we'll have to do things our own way.	
//	$exp = "0,10,0";
	$stmt->bind_param("iiiiis", $new_admiral_id, $first_ship, $ship_id, $ship_data['api_taik'][0], $ship_data['api_taik'][0], $exp);
	$stmt->execute();

	if($stmt->affected_rows == 1) {
		print "INFO: Successfully added ship ID {$ship_id} to Admiral ID {$new_admiral_id} <br>";
		$internal_ship_id = $stmt->insert_id;
	}
	
    unset($exp);
	$stmt->close();
} */
$internal_ship_id = enlistNewShipgirl($dbc, $ship_id, $new_admiral_id, 1);
if(!$internal_ship_id) die("ERROR: Failed to add starter ship. Your account may be broken. Please contact a system administrator.");

// Secondly insert the equipment.
$equipment_insert_ids = array( );	// This will be used when we compile it into the format that the game will use.
$my_equip_id = 1;

foreach($equipment as $e){
	for($i = 0; $i < count($apiMasterEquip); $i++) {
		if($apiMasterEquip[$i]['api_id'] == $e) {
			print "DEBUG: Found API Data Equipment ID {$e} ({$apiMasterEquip[$i]['api_name']}) ! <br>";
			$game_id = $apiMasterEquip[$i]['api_id'];
		}
	}

	$query = "INSERT INTO ship_equipment (admiral_id, equipment_id, game_id) VALUES (?, ?, ?)";

	if($stmt = $dbc->prepare($query)){
		$stmt->bind_param("iii", $new_admiral_id, $my_equip_id, $game_id);
		$stmt->execute();

		if($stmt->affected_rows == 1) $equipment_insert_ids[] = $stmt->insert_id;

		$stmt->close();
	} else die("ERROR: Failed to add equipment. Your account may be broken. Please contact a system administrator.");
}

// Now, we compile it into an array and update the shipgirl's current equipment.
$equip_string = "";

for($i = 0; $i <= 5; $i++) {
	if(isset($equipment_insert_ids[$i])) $equip_string .= $equipment_insert_ids[$i].",";
	else $equip_string .= "-1,";
}

$query = "UPDATE `ships_enlisted` SET `curr_equipment` = ? WHERE `ship_id` = ? AND `admiral_id` = ?";

if($stmt = $dbc->prepare($query)) {
	$stmt->bind_param("sii", $equip_string, $internal_ship_id, $new_admiral_id);
	$stmt->execute();

	if($stmt->affected_rows == 1) print "INFO: Successfully updated starter ship with the new equipment. <br>";
	
	$stmt->close();
} else die("ERROR: Failed to add equipment to starter ship. Your account may be broken. Please contact a system administrator. {$dbc->error}");

// Setup our fleets, repair and construction docks.
for($i = 1; $i <= 4; $i++){
	$query = "INSERT INTO `ship_fleets` (admiral_id,fleet_id) VALUES(?,?)";
	if($stmt = $dbc->prepare($query)) {
		$stmt->bind_param("ii", $new_admiral_id, $i);
		$stmt->execute();

		if($stmt->affected_rows == 1) print "INFO: Successfully setup fleet {$i} ! <br>";
		$stmt->close();
	}
	
	$query = "INSERT INTO `factory_docks` (admiral_id,dock_id) VALUES(?,?)";
	if($stmt = $dbc->prepare($query)) {
		$stmt->bind_param("ii", $new_admiral_id, $i);
		$stmt->execute();

		if($stmt->affected_rows == 1) print "INFO: Successfully setup construction dock {$i} ! <br>";
		$stmt->close();
	}
	
	$query = "INSERT INTO `repair_docks` (admiral_id,dock_id) VALUES(?,?)";
	if($stmt = $dbc->prepare($query)) {
		$stmt->bind_param("ii", $new_admiral_id, $i);
		$stmt->execute();

		if($stmt->affected_rows == 1) print "INFO: Successfully setup repair dock {$i} ! <br>";
		$stmt->close();
	}
}

// Set the shipgirl as the first fleet member in fleet 1.
$query = "UPDATE `ship_fleets` SET `first_ship` = ? WHERE `admiral_id` = ? AND `fleet_id` = 1";
if($stmt = $dbc->prepare($query)) {
	$stmt->bind_param("ii", 1, $new_admiral_id);
	$stmt->execute();

	if($stmt->affected_rows == 1) print "INFO: Successfully updated fleets with starter ship. <br>";
	
	$stmt->close();
} else die("ERROR: Failed to update ship fleets to assign starter ship. Your account may be broken. Please contact a system administrator. {$dbc->error}");
print "OK. You should be ready to go. <br>";
print "Please note that this is very experimental software. It may break. <br>";
print "<a href='index.php'>Click here to continue and login.</a> Have fun and good luck! <br>";
?>