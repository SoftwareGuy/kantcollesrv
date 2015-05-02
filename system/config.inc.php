<?PHP
// KantColle Open Source Server
// Configuration File
if(!defined("KANTCOLLE")) die("Not allowed");

$config = array(
	"no_reg" => false,	// Disable/Enable Registrations
	"free_res" => false,	// Don't actually take away admiral resources
	"game_base" => "your.domain.com"	// IP or domain name of where the game dir (kcs) is.
);

$db = array (
	"host" => "localhost",	// SQL Connection Information.
	"user" => "",
	"password" => "",
	"database" => ""
);
