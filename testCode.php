<?PHP
define("KANTCOLLE", "fubuki");
require "system/config.inc.php";
require "system/functions.inc.php";

// Attempt database connection
$dbc = new mysqli($db['host'], $db['user'], $db['password'], $db['database']);
if($dbc->connect_error) die("ERROR: SQL Connection Failure. Aborting - inform sysadmin!");

/*
$out = array();
// There's 36 types of items?!
for($i = 1; $i <= 36; $i++) {
	$out["api_slottype{$i}"] = array();
}

$query = "SELECT * FROM `ship_equipment` WHERE admiral_id = ? ORDER BY equipment_id ASC";
$a = 1;
if($stmt = $dbc->prepare($query)) {
	$stmt->bind_param("i", $a);
	$stmt->execute();
	
	$result = $stmt->get_result();
	while($data = $result->fetch_assoc()){
		$out["api_slottype{$data['game_type_id']}"][] = $data['equipment_id'];
	}
}

for($i = 1; $i <= 36; $i++) {
	if(count($out["api_slottype{$i}"]) == 0) $out["api_slottype{$i}"] = -1;
}


print_r($out);
*/

enlistNewShipgirl($dbc, 183, 1);

?>