<?PHP
// KantColle Open Source Server
// Game Page

if(!is_numeric($_SESSION['my_id'])) {
	$_SESSION['logged_in'] = false;

	header("Location: index.php");
	exit;
}


$myApiToken = "";

// Get our API Token.
$query = "SELECT `token` FROM `admirals` WHERE `id` = ?";
if($stmt = $dbc->prepare($query)){
	$stmt->bind_param("i", $_SESSION['my_id']);
	$stmt->execute();
	
	$stmt->bind_result($result);
	$stmt->fetch();

	$myApiToken = $result;

	$stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>KantColle Open Source Server</title>

    <!-- Bootstrap -->
    <link href="static/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type='text/css'>
	body {
		background: #efefef url("static/images/attract_img.jpg") top left no-repeat;
		background-size: cover;
	}

	.container {
		margin-top: 64px;
	}
    </style>
  </head>
  <body>
	<div class="container">
		<div class='row'>
			<div class='panel panel-default'>
				<div class='panel-heading'><b>Game Client</b></div>
				<div class='panel-body'>
					<div id="flashWrap" style="width: 800px; margin-left: auto; margin-right: auto; height: 480px;">
						<embed id="externalswf" width="800" height="480" wmode="opaque" quality="high" bgcolor="#000000" allowscriptaccess="always" base="http://<?PHP print $config['game_base']; ?>/kcs/" type="application/x-shockwave-flash" src="http://<?PHP print $config['game_base']; ?>/kcs/mainD2.swf?api_token=<?PHP print $myApiToken; ?>&amp;api_starttime=<?PHP print time(); ?>" title="Adobe Flash Player">
					</div>
				</div>
			</div>
		</div>
				
		<div class='row'>
				<div class='panel panel-default'>
					<div class='panel-heading'>Copyright Info</div>
					<div class='panel-body'>
						<p>All game contents is &copy; DMM / PowerChord Studio / C2 / KADOKAWA GAMES in Japan and/or other countries.</p>
						<p>This project mimics the API responses allowing people to play on unofficial non-DMM servers. We do not hack or tamper the game client SWF files.<br>We're doing this for fun and not to profit in any way. Don't sue us either, you've got better things to do than throw money at C&amp;D letters.</p>
						<p>Special thanks to the KCT Team for their DMM API Documentation and the Python server project. Adapted to PHP by SoftwareGuy.</p>
					</div>
				</div>
		</div>		
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="static/js/bootstrap.min.js"></script>
  </body>
</html>
