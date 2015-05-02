<?PHP 
// KantColle Open Source Server
// Functions File.

// Response Functions //
function svdata($input, $code=1, $message="成功") {
	if($code == 1){
		$out = array (
			"api_result" => $code,
			"api_result_msg" => $message,
			"api_data" => $input
		);
	} else if($code == 201) {
		$out = array (
			"api_result" => 201,
			"api_result_msg" => "\u7533\u3057\u8a33\u3042\u308a\u307e\u305b\u3093\u304c\u30d6\u30e9\u30a6\u30b6\u3092\u518d\u8d77\u52d5\u3057\u518d\u30ed\u30b0\u30a4\u30f3\u3057\u3066\u304f\u3060\u3055\u3044\u3002"
		);
	}
	
	print "svdata=".json_encode($out, JSON_NUMERIC_CHECK);
}

// Admiral Functions // 
function getTokenAdmiralOrError($db, $token) {
		if(!strlen($token) == 40) return false;
		// Check if the admiral exists.
		// If he doesn't, then 403. Otherwise, get the admiral info array.
		$stmt = $db->stmt_init();
		if($stmt = $db->prepare("SELECT * FROM `admirals` WHERE token = ?")){
			$stmt->bind_param("s", $token);
			$stmt->execute();
			
			$result = $stmt->get_result();
			if($result->num_rows == 1) {
				// $result = $query->get_result();
				$admiral_data = $result->fetch_assoc();
				
				return $admiral_data;
			} else {
				header("HTTP/1.0 403 Forbidden");
				exit;
			}
			$stmt->close();
		}
}

function genNewAdmiralToken($db,$admiral_id) {
		$newAPIToken = sha1(mt_rand(1000,100000));
		
		// Change the admirals token.
		$query = "UPDATE `admirals` SET token = ? WHERE admiral_id = ?";
		if($result = $db->prepare($query)){
		$result->bind_param("si", $newAPIToken, $admiral_id);
		$result->execute();
		
	//	if($result->affected_rows == 1) return true;
	//	else return false;
		$result->close();
	}
		
}

function getFurnitureItem($member_id, $id, $masterblob="./blobs/api_start2.json") {
	if(!$member_id || !$id) return false;

	// Buffer it up.
	$buffer = json_decode( trim(str_replace("svdata=", "", file_get_contents($masterblob))) , true, 2048 );
	$buffer = $buffer['api_data']['api_mst_furniture'];

	// Let's do this.	
	foreach($buffer as $furn) {
		if($furn['api_id'] == $id) {
			return array("api_member_id" => $member_id, "api_id" => $furn['api_id'], "api_furniture_type" => $furn['api_type'], "api_furniture_no" => $furn['api_no'], "api_furniture_id" => $furn['api_id']);
		}
	}
	
	// Clean up.
	unset($buffer);
}

// Building functions
function depleteResources($db,$admiral_id,$fuel=30,$steel=30,$ammo=30,$bauxite=30,$qbuild=1,$devmats=1) {
	if(!is_object($db) || !$admiral_id) return false;
	
	$query = "SELECT res_fuel,res_steel,res_ammo,res_bauxite,res_quickbuilds,res_devmats FROM `admirals` WHERE `admiral_id` = ?";
	
	if($result = $db->prepare($query)) {
		$result->bind_param("i", $admiral_id);
		$result->execute();
		
		$data = $result->get_result();
		$curr_res = $data->fetch_assoc();
		
		$result->close();
	}
	
	$new_res = array (
		'fuel' => $curr_res['res_fuel']-$fuel,
		'steel' => $curr_res['res_steel']-$steel,
		'ammo' => $curr_res['res_ammo']-$ammo,
		'bauxite' => $curr_res['res_bauxite']-$bauxite,
		'qbuilds' => $curr_res['res_quickbuilds']-$qbuild,
		'devmats' => $curr_res['res_devmats']-$devmats
	);
	
	$query = "UPDATE `admirals` SET res_fuel = ?, res_steel = ?, res_ammo = ?, res_bauxite = ?, res_quickbuilds = ?, res_devmats = ? WHERE admiral_id = ?";
	if($result = $db->prepare($query)){
		$result->bind_param("iiiiiii", $new_res['fuel'], $new_res['steel'], $new_res['ammo'], $new_res['bauxite'], $new_res['qbuilds'], $new_res['devmats'], $admiral_id);
		$result->execute();
		
	//	if($result->affected_rows == 1) return true;
	//	else return false;
		$result->close();
	}
	
	// And that's that.
}

function depleteBuckets($db,$admiral_id,$amount=1) {
	if(!$admiral_id || !is_object($db)) return false;
	
	if($query = $kcdb->prepare("SELECT res_buckets FROM admirals WHERE admiral_id = ?")){
		$query->bind_param("i",$admiral_id);
		$query->execute();
		$query->bind_result($currentAmount);
		$query->fetch();
		$query->close();
	} else {
		return false;
	}
	
	$currentAmount = $currentAmount-$amount;
	
	if($query = $db->prepare("UPDATE admirals SET res_buckets = ? WHERE admiral_id = ?")){
		$query->bind_param("ii",$currentAmount,$admiral_id);
		$query->execute();
		
		if($query->affected_rows == 1) return true;
		else return false;
	}
}

// Fleet functions //
function getShipsInFleet($db, $admiral_id, $fleet_id) {
	if(!is_object($db) || !$admiral_id || !$fleet_id) return;
	
	$query = "SELECT * FROM ship_fleets WHERE admiral_id = ? AND fleet_id = ?";
	
	if($result = $db->prepare($query)) {
		$result->bind_param("ii", $admiral_id, $fleet_id);
		$result->execute();
		
		$data = $result->get_result();
		while($myships = $data->fetch_assoc()) {
			$out = array($myships['first_ship'], $myships['second_ship'], $myships['third_ship'], $myships['fourth_ship'], $myships['fifth_ship'], $myships['sixth_ship']);
		}
		
		$result->close();
		
		return $out;
	}
}

function getShipCurrentEquipment($db,$admiral_id,$ship_id) {
	if(!is_object($db) || !$admiral_id || !$ship_id) return;
	
	$query = "SELECT `curr_equipment` FROM `ships_enlisted` WHERE admiral_id = ? AND internal_id = ?";	
	if($result = $db->prepare($query)) {
		$result->bind_param("ii", $admiral_id, $ship_id);
		$result->execute();
		
		$result->bind_result($equipment);
		$result->fetch();
		
		return $equipment;
		$result->close();
	}
	
//	if($result['equipment']) return $result['equipment'];
	else return "-1,-1,-1,-1,-1";
}

function getShipData($ship_id, $file="./blobs/api_start2.json") {
	file_put_contents("test.log", "Debug: Ship data ID: {$ship_id}");
	$apiMasterData = json_decode(str_replace("svdata=", "", file_get_contents($file)), true, 2048);
	$apiMasterShips = $apiMasterData['api_data']['api_mst_ship'];
	unset($apiMasterData);

	foreach($apiMasterShips as $s) {
			file_put_contents("test.log", "Debug: Looking for {$ship_id}. Currently at {$s['api_id']}", FILE_APPEND);
			if($s['api_id'] == $ship_id) {
				file_put_contents("test.log", "Debug: Found {$ship_id}!", FILE_APPEND);
				return $s;	
			}
	}

	// return;
}

// Shipgirl related functions
function enlistNewShipgirl($db, $ship_id, $admiral_id, $return_insert_id=0) {
	// Master ship information.
	$ship_data = getShipData($ship_id);
	$myShipID = 1;

	$query = "SELECT `ship_id` FROM `ships_enlisted` WHERE `admiral_id` = ? ORDER BY `ship_id` DESC LIMIT 1";
	if($stmt = $db->prepare($query)) {
		$stmt->bind_param("i", $admiral_id);
		$stmt->execute();

		$result = $stmt->get_result();
		while($row = $result->fetch_row()) {
			$myShipID = $row[0]+1;
		}
	} else {
		$myShipID = 1;
	}

	$experience = "0,10,0";

	$iquery = "INSERT INTO `ships_enlisted` (admiral_id, ship_id, game_id, experience, curr_health, max_health, base_armor, max_armor, base_firepower, max_firepower, base_torpedo, max_torpedo, base_aa, max_aa, base_asw, max_asw, base_los, max_los, aircraft, speed, ship_range, base_luck, max_luck ) VALUES (?,?,?,?,?, ? ,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
	$dummy = 0;
	if($stmt = $db->prepare($iquery)) {
		// Holy fuck.
		$stmt->bind_param("iiisiiiiiiiiiiiiiiiiiii", 
$admiral_id, $myShipID, $ship_id, $experience, $ship_data['api_taik'][0], $ship_data['api_taik'][0], $ship_data['api_souk'][0], 
$ship_data['api_souk'][1], $ship_data['api_houg'][0], $ship_data['api_houg'][1], $ship_data['api_raig'][0], $ship_data['api_raig'][1], $ship_data['api_tyku'][0],
 $ship_data['api_tyku'][1], $ship_data['api_raig'][0], $ship_data['api_raig'][1], $dummy , $dummy , $dummy, $ship_data['api_soku'], $ship_data['api_leng'], $ship_data['api_luck'][0], $ship_data['api_luck'][1]);
		$stmt->execute();
		
		if($return_insert_id == 1) return $stmt->insert_id;
		else $stmt->close();

	} else {
		return false;
	}

}

function switchFleetGirlsAround($db, $admiral_id, $fleetPosID, $shipID) {
	// TODO.
/* work out this stuff in the api post call:
 api_ship_idx:0
api_id:1
api_token: <token>
api_verno:1
api_ship_id:3
*/
}
