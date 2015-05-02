<?PHP
// KantColle Open Source Server
// Landing Page
if($_SESSION['logged_in'] == true) { 
	header("Location: index.php?act=play");
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>KantColle Open Source Server: Log In</title>

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
				<div class='panel-heading'><b>Log In</b></div>
				<div class='panel-body'>
					<p>Already played on this server before? Get back into the game by simply logging in again.</p>
					<p>Please note: your DMM login will NOT work. We do not talk to DMM auth servers, so you'll need to register if you haven't already.</p>
					<form action="index.php?act=login" method="POST">
						<div class="form-group">
							<label for="l_admiral_nick">Admiral nickname:</label>
							<input type="text" class="form-control" id="l_admiral_nick" name="login_admiral_nick" placeholder="Admiral nickname" required>
						</div>
						
						<div class="form-group">
							<label for="l_admiral_password">Password:</label> 
							<input type="password" class="form-control" id="l_admiral_password" name="login_admiral_password" placeholder="Password" required>
						</div>
						
						<button type="submit" class="btn btn-primary">Let's go sink some enemy ships!</button>
					</form>
				</div>
			</div>
		</div>
		
		<div class='row'>
			<div class='panel panel-default'>
				<div class='panel-heading'><b>Enlist</b></div>
				<div class='panel-body'>
					<p>Ready to command your fleet, but you need to register?</p>
					<form action="index.php?act=register" method="POST">
						<div class="form-group">
							<label for="r_admiral_nick">Desired Nickname:</label>
							<input type="text" class="form-control" id="l_admiral_nick" name="new_admiral_nickname" placeholder="Admiral nickname" required>
						</div>
						
						<div class="form-group">
							<label for="r_admiral_starter">Starter Ship:</label>
							<select id="r_admiral_starter" name="new_admiral_ship" class="form-control">
								<option value="fubuki" selected>Fubuki</option>
								<option value="inazuma">Inazuma</option>
								<option value="murakumo">Murakumo</option>
								<option value="samidare">Samidare</option>
								<option value="sazanami">Sazanami</option>
							</select>
						</div>
						
						<div class="form-group">
							<label for="r_admiral_password">Desired Password:</label>
							<input type="password" class="form-control" id="r_admiral_password" name="new_admiral_password" placeholder="Password" required>
						</div>

						<div class="form-group">
							<label for="r_admiral_email">Email Address:</label>
							<input type="text" class="form-control" id="r_admiral_email" name="new_admiral_email" placeholder="you@some-website.com" required>
						</div>						
						<button type="submit" class="btn btn-primary">Enlist me.</button>
					</form>
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