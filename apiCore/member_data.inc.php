<?PHP
// API Get Member Data Node
if(!$_POST) exit;

if(strlen($_POST['api_token']) != 40) {
	header("HTTP/1.0 403 Forbidden");
	exit;
}

$apiToken = $_POST['api_token'];
$admiral = getTokenAdmiralOrError($dbc, $apiToken);

switch($our_endpoint) {
	case "basic":
		// Basic Member Data.
		$output = array (
		'api_member_id' => intval($admiral['id']),
		'api_nickname' => $admiral['nickname'],
		'api_nickname_id' => 0, // Don't need this?
		'api_active_flag' => 1, // Banned if 0 ?
		'api_starttime' => 0, // Unix Time?
		'api_level' => intval($admiral['level']),
		'api_rank' => intval($admiral['rank']),
		'api_experience' => intval($admiral['experience']),
		'api_fleetname' => $admiral['fleetname'],
		'api_comment' => "", // $admiral['comment'],
		'api_comment_id' => 0, // intval($admiral['api_comment_id']),
		'api_max_chara' => intval($admiral['max_ships']),
		'api_max_slotitem' => intval($admiral['max_slotitem']),
		'api_max_kagu' => intval($admiral['max_furniture']),
		'api_playtime' => intval($admiral['playtime']),
		'api_tutorial' => intval($admiral['tutorial_complete']), // WTF?
		'api_furniture' => explode(",",$admiral['furniture']), // check this out
		'api_count_deck' => intval($admiral['curr_fleets']),
		'api_count_kdock' => intval($admiral['curr_docks']),
		'api_count_ndock' => intval($admiral['curr_cbays']),
		'api_fcoin' => intval($admiral['furn_coins']),
		'api_st_win' => intval($admiral['sortie_wins']),
		'api_st_lose' => intval($admiral['sortie_losses']),
		'api_ms_count' => intval($admiral['exped_wins'] + $admiral['exped_fails']),
		'api_ms_success' => intval($admiral['exped_fails']),
		'api_pt_win' => intval($admiral['pvp_wins']),
		'api_pt_lose' => intval($admiral['pvp_losses']), 
		'api_pt_challenged' => intval($admiral['pvp_challenges']),
		'api_pt_challenged_win' => intval($admiral['pvp_challenges_won']),
		'api_firstflag' => 1, // intval($admiral['api_firstflag'])
		'api_tutorial_progress' => intval($admiral['tutorial_progress']),
		'api_pvp' => array(0, 0),
		'api_large_dock' => 1	// LSC ftw!
		);

		print svdata($output);
		unset($output);

	break;

	case "furniture":
		$output = array();

		$query = "SELECT `curr_furniture` FROM `admirals` WHERE id = ?";
		$stmt = $dbc->stmt_init();
		if($stmt = $dbc->prepare($query)) {
			$stmt->bind_param("i", $admiral['id']);
			$stmt->execute();

			$result = $stmt->get_result();
			if($result->num_rows == 1) {
				$data = $result->fetch_assoc();
				foreach(explode(",",$data['curr_furniture']) as $f) $output[] = getFurnitureItem($admiral['id'],$f);
			}
			$stmt->close();
		}
		print svdata($output);
		unset($output);

	break;

	case "slot_item":
		$output = array();
		$query = "SELECT * FROM `ship_equipment` WHERE admiral_id = ? ORDER BY equipment_id ASC";

		if($stmt = $dbc->prepare($query)){
			$stmt->bind_param("i", $admiral['id']);
			$stmt->execute();

			$result = $stmt->get_result();
			while($data = $result->fetch_assoc()){
				$output[] = array (
					"api_id" => $data['equipment_id'],
					"api_slotitem_id" => $data['game_type_id'],
					"api_locked" => 0,
					"api_level" => 0					
				);
			}

			$stmt->close();
		}
		print svdata($output);
		unset($output);
	break;

	case "useitem":
		// Consumable items
		$output = array();

		print svdata($output);
	break;

	case "kdock":
		$output = array();
		$query = "SELECT * FROM `factory_docks` WHERE `admiral_id` = ? ORDER BY dock_id ASC";
		$stmt = $dbc->stmt_init();
		if($stmt = $dbc->prepare($query)){
			$stmt->bind_param("i", $admiral['id']);
			$stmt->execute();

			$result = $stmt->get_result();
			while($data = $result->fetch_assoc()){
				$output[] = array(
					"api_member_id" => $admiral['id'],
					"api_id" => $data['dock_id'],
						"api_state" => $data['dock_state'],
						"api_created_ship_id" => $data['dock_builtship_id'],
						"api_complete_time" => $data['dock_buildtime'],
						"api_complete_time_str" => date("Y-M-d H:M:S", $data['dock_buildtime']),
						"api_item1" => $data['fuel'],
						"api_item2" => $data['ammo'],
						"api_item3" => $data['steel'],
						"api_item4" => $data['bauxite'],
						"api_item5" => $data['cmats']
				);
			}
		}

		print svdata($output);
		unset($output);
	break;

	case "ndock":
		$output = array();
		$query = "SELECT * FROM `repair_docks` WHERE `admiral_id` = ? ORDER BY dock_id ASC";
		$stmt = $dbc->stmt_init();
		if($stmt = $dbc->prepare($query)){
			$stmt->bind_param("i", $admiral['id']);
			$stmt->execute();

			$result = $stmt->get_result();
			while($data = $result->fetch_assoc()){
				$output[] = array(
					"api_member_id" => $admiral['id'],
					"api_id" => $data['dock_id'],
						"api_state" => $data['dock_state'],
						"api_created_ship_id" => $data['dock_ship_id'],
						"api_complete_time" => $data['dock_recoverytime'],
						"api_complete_time_str" => date("Y-M-d H:M:S", $data['dock_buildtime']),
						"api_item1" => $data['fuel'],
						"api_item2" => $data['ammo'],
						"api_item3" => $data['steel'],
						"api_item4" => $data['bauxite']
				);
			}
		}

		print svdata($output);
		unset($output);
	break;

	case "unsetslot":
		$output = array();
		// There's 36 types of items?!
		for($i = 1; $i <= 36; $i++) {
			$output["api_slottype{$i}"] = array();
		}

		$query = "SELECT * FROM `ship_equipment` WHERE admiral_id = ? ORDER BY equipment_id ASC";

		if($stmt = $dbc->prepare($query)) {
			$stmt->bind_param("i", $admiral['id']);
			$stmt->execute();
	
			$result = $stmt->get_result();
			while($data = $result->fetch_assoc()){
				$output["api_slottype{$data['game_type_id']}"][] = $data['equipment_id'];
			}
		}

		for($i = 1; $i <= 36; $i++) {
			if(count($output["api_slottype{$i}"]) == 0) $output["api_slottype{$i}"] = -1;
		}

		print svdata($output);
		unset($output);
	break;
}


?>