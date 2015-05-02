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
	case "port":
		$output = array();

		// Part 1: Get admiral resources. //
		$ohres = array();

		$query = "SELECT res_fuel, res_ammo, res_steel, res_bauxite, res_quickbuilds, res_buckets, res_devmats, res_screws FROM admirals WHERE id = ?";
		if($stmt = $dbc->prepare($query)){
			$stmt->bind_param("i", $admiral['id']);
			$stmt->execute();
			
			$result = $stmt->get_result();
			while($data = $result->fetch_assoc()){
				// Fuel
				$ohres[0] = array ( "api_member_id" => $admiral['id'], "api_id" => 1, "api_value" => intval($data['res_fuel']) );
				// Ammo
				$ohres[1] = array ( "api_member_id" => $admiral['id'], "api_id" => 2, "api_value" => intval($data['res_ammo']) );
				// Steel
				$ohres[2] = array ( "api_member_id" => $admiral['id'], "api_id" => 3, "api_value" => intval($data['res_steel']) );
				// Bauxite
				$ohres[3] = array ( "api_member_id" => $admiral['id'], "api_id" => 4, "api_value" => intval($data['res_bauxite']) );
				// Quick Build (?)
				$ohres[4] = array ( "api_member_id" => $admiral['id'], "api_id" => 5, "api_value" => intval($data['res_quickbuilds']) );
				// Buckets
				$ohres[5] = array ( "api_member_id" => $admiral['id'], "api_id" => 6, "api_value" => intval($data['res_buckets']) );
				// Blueprints
				$ohres[6] = array ( "api_member_id" => $admiral['id'], "api_id" => 7, "api_value" => intval($data['res_devmats']) );
				// Screws (?)
				$ohres[7] = array ( "api_member_id" => $admiral['id'], "api_id" => 8, "api_value" => intval($data['res_screws']) );
			}
			
			$stmt->close();
		}

		// Clean up //
		$output['api_material'] = $ohres;
		
		
		unset($ohres,$result,$data);

		// Part 2: Get fleet data. //
		$ohfleets = array();

		$query = "SELECT * FROM ship_fleets WHERE admiral_id = ? ORDER BY fleet_id ASC ";
		
		if($stmt = $dbc->prepare($query)){
			$stmt->bind_param("i", $admiral['id']);
			$stmt->execute();
			
			$result = $stmt->get_result();
			
			while($data = $result->fetch_assoc()) {
				$ohfleets[] = array( "api_member_id" => $admiral['id'], "api_id" => intval($data['fleet_id']), "api_name" => $data['name'], "api_name_id" => 0, "api_mission" => explode(",",$data['mission']), "api_flagship" => 0, "api_ship" => getShipsInFleet($dbc, $admiral['id'], $data['fleet_id']) );
			}
			
			$stmt->close();
		}	
		$output['api_deck_port'] = $ohfleets;
		unset($ohfleets,$result,$data);

		// Part 3: Sick bay. //
		$sickbay = array();
		$query = "SELECT * FROM `repair_docks` WHERE `admiral_id` = ? ORDER BY `dock_id` ASC";
		
		if($stmt = $dbc->prepare($query)){
			$stmt->bind_param("i", $admiral['id']);
			$stmt->execute();
			
			$result = $stmt->get_result();
			
			while($data = $result->fetch_assoc()){
				$sickbay[] = array(
					"api_member_id" => $admiral['id'],
					"api_id" => $data['dock_id'],
					"api_state" => $data['dock_state'],
					"api_ship_id" => $data['dock_ship_id'],
					"api_complete_time" => $data['dock_recoverytime'],
					"api_complete_time_str" => $data['dock_recoverytime'], // date("Y-m-d H:i:s", $result['dock_recoverytime']),
					"api_item1" => $data['fuel'],
					"api_item2" => $data['ammo'],
					"api_item3" => $data['steel'],
					"api_item4" => $data['bauxite']
				);
			}
			
			$stmt->close();
		}

		$output['api_ndock'] = $sickbay;
		unset($sickbay,$result,$data);

		// Part 4: Fleet girl list //
		// TODO: Database stuff...
		$ships = array();
		
		$query = "SELECT * FROM `ships_enlisted` WHERE `admiral_id` = ? ORDER BY `ship_id` ASC";
		if($stmt = $dbc->prepare($query)){
			$stmt->bind_param("i", $admiral['id']);
			$stmt->execute();
			
			$result = $stmt->get_result();
			while($ship = $result->fetch_assoc()) {	
				// Please let this work.
				$apiShipData = getShipData($ship['game_id']);	// Get the game data for this ship.`
				$ships[] = array (
					"api_id" => intval($ship['ship_id']),
					"api_sortno" => intval($apiShipData['api_sortno']),
					"api_ship_id" => intval($ship['game_id']),
					"api_lv" => intval($ship['level']),
					"api_exp" => explode(",",$ship['experience']),
					"api_nowhp" => intval($ship['curr_health']),
					"api_maxhp" => intval($ship['max_health']),
					"api_leng" => intval($apiShipData['api_leng']),
					"api_slot" => explode(",",$ship['curr_equipment']),
					"api_onslot" => array ( 0,0,0,0,0 ), // explode(",", $ship['on_slot']),
					"api_kyouka" => array( 0,0 ), // explode(",",$ship['kyouka']),	// ??
					"api_backs" => intval($apiShipData['api_backs']),
					"api_fuel" => intval($ship['curr_fuel']),
					"api_bull" => intval($ship['curr_ammo']),
					"api_slotnum" => intval($ship['slots']),
					"api_ndock_time" => 0, // intval($ship['repair_seconds']),
					"api_ndock_item" => array(1,1) , // explode(",",$ship['repair_cost']),
					"api_srate" => intval($ship['stars']-1),
					"api_cond" => 49, // intval($ship['s_condition'])
					"api_karyoku" => array( intval($ship['base_firepower']), intval($ship['max_firepower'])),
					"api_raisou" => array( intval($ship['base_torpedo']), intval($ship['max_torpedo'])),
					"api_taiku" => array( intval($ship['base_aa']), intval($ship['max_aa'])),
					"api_souk" =>  array( intval($ship['base_armor']), intval($ship['max_armor'])) ,
					"api_soukou" =>  array( intval($ship['base_armor']), intval($ship['max_armor'])) , // array( 0,0 ), // explode(",",$apiShipData['armor_with_equip']), //intval($ship['speed']),
					"api_kaihi" => array ( 40, 89 ), // explode(",",$ship['evasion']),
					"api_taisen" => array( intval($ship['base_asw']), intval($ship['max_asw'])), // explode(",", $ship['antisub']),
					"api_sakuteki" => array( 0,0 ), // explode(",",$ship['modern_los']),
					"api_lucky" => array( intval($ship['base_luck']) , intval($ship['max_luck']) ),
					"api_locked" => 0,
					"api_locked_equip" => 0
				);
			}
		}
		$output['api_ship'] = $ships;
		unset($result, $ship, $ships);

		// Part 5: More basic user stuff? //
		$output['api_basic'] = array (
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
		'api_max_slotitem' => intval($admiral['max_slotitems']),
		'api_max_kagu' => intval($admiral['max_furniture']),
		'api_playtime' => intval($admiral['playtime']),
		'api_tutorial' => intval($admiral['tutorial_complete']), // WTF?
		'api_furniture' => explode(",",$admiral['curr_furniture']), // check this out
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

		// Part 6: Journal (?)
		$output['api_log'] = array();
		$output['api_log'][] = array (
			"api_no" => 0,
			"api_type" => 0,
			"api_state" => 0,
			"api_message" => "Welcome to the unoffical KanColle Server!"
		);

		// Part 7: API Background Music ID
		$output['api_p_bgm_id'] = 101;

		// SEND IT TO THE BLENDER //
		print svdata($output);
	break;
}
?>