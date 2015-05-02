<?PHP
// KantColle Open Source Server
// Registration Script

// This script requires POST data, if we don't have it, go away.
if(!$_POST) exit;

$inputData = array (
	"nickname" => trim($_POST['login_admiral_nick']),
	"password" => sha1($_POST['login_admiral_password'])
);


// Check if we exist
$admiral_exists = false;
$login_match = false;

$query = "SELECT COUNT(*) FROM `admirals` WHERE `nickname` = ?";

if($stmt = $dbc->prepare($query)) {
	$stmt->bind_param("s", $inputData['nickname']);
	$stmt->execute();
	
	$stmt->bind_result($result);
	$stmt->fetch();
	
	if($result == 0) die("No admiral exists with that nickname.");	// TODO Better reject msg
	else $admiral_exists = true;
	$stmt->close();
}

if($admiral_exists == true) {
	// Check the password.
	$query = "SELECT `password` FROM `admirals` WHERE `nickname` = ?";
	if($stmt = $dbc->prepare($query)) {
		$stmt->bind_param("s", $inputData['nickname']);
		$stmt->execute();
		
		$stmt->bind_result($result);
		$stmt->fetch();
		
		// print $result." vs ".$inputData['password'];
		
		if($inputData['password'] == $result) $login_match = true;
		else $login_match = false;
		$stmt->close();
	}

	if($login_match) {
		// Get admiral's ID and make a new token.
		$_SESSION['logged_in'] = true;
			
		$query = "SELECT `id` FROM `admirals` WHERE `nickname` = ?";
		if($stmt = $dbc->prepare($query)) {
			$stmt->bind_param("s", $inputData['nickname']);
			$stmt->execute();
	
			$stmt->bind_result($result);
			$stmt->fetch();

			// print $result;
	
			$_SESSION['my_id'] = $result;
			genNewAdmiralToken($dbc,$result);

			header("Location: index.php?act=play");
			exit;
		}
	} else die("Wrong password.");
} 

?>