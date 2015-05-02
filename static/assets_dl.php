<?PHP
// $outdir = "./kcs/resources/swf/ships";

print "SoftwareGuy's KC Asset SWF Downloader" . PHP_EOL;
print "START: Core SWF Assets" . PHP_EOL;

$outdir = "./kcs";
if (!mkdir($outdir, 0755, true)) print "WARNING: Can't make directory. Maybe it already exists? If so, ignore this error." . PHP_EOL;
$core = array("Core.swf", "mainD2.swf", "PortMain.swf", "maintenance.swf", "ban.swf");

foreach($core as $f) {
	set_time_limit(30);
	
	print "GET: {$f} ... ";
	
	$ch = curl_init();
	// Could change this; this is the server my account is on. Suit yourself...
	curl_setopt($ch, CURLOPT_URL, "http://203.104.209.39/kcs/{$f}");
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36");
	
	$data = curl_exec($ch);
	if(!$data) print "FAIL! ".curl_error($ch). PHP_EOL;
	else { 
		print "OK!".PHP_EOL;
		file_put_contents($outdir."/{$f}", trim($data));
	}
	
	curl_close($ch);
}
print "...DONE" . PHP_EOL;

print "START: Scene SWF Assets" . PHP_EOL;
$outdir = "./kcs/scenes";
if (!mkdir($outdir, 0755, true)) print "WARNING: Can't make directory. Maybe it already exists? If so, ignore this error." . PHP_EOL;
$scenes = array("AlbumMain.swf", "ArsenalMain.swf", "DutyMain.swf", "InteriorMain.swf", "ItemlistMain.swf", "OrganizeMain.swf", "RecordMain.swf", "RemodelMain.swf", "RepairMain.swf", "SallyMain.swf", "SupplyMain.swf", "TitleMain.swf", "tutorial.swf");
foreach($scenes as $f) {
	set_time_limit(30);
	
	print "GET: {$f} ... ";
	
	$ch = curl_init();
	// Could change this; this is the server my account is on. Suit yourself...
	curl_setopt($ch, CURLOPT_URL, "http://203.104.209.39/kcs/scenes/{$f}");
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36");
	
	$data = curl_exec($ch);
	if(!$data) print "FAIL! ".curl_error($ch). PHP_EOL;
	else { 
		print "OK!".PHP_EOL;
		file_put_contents($outdir."/{$f}", trim($data));
	}
	
	curl_close($ch);
}
print "...DONE" . PHP_EOL;

print "START: Common SWF Assets" . PHP_EOL;
$outdir = "./kcs/resources/swf";
if (!mkdir($outdir, 0755, true)) print "WARNING: Can't make directory. Maybe it already exists? If so, ignore this error." . PHP_EOL;
$assets = array("commonAssets.swf", "font.swf", "icons.swf", "sound_bgm.swf", "sound_se.swf");

foreach($assets as $f) {
	set_time_limit(30);
	
	print "GET: {$f} ... ";
	
	$ch = curl_init();
	// Could change this; this is the server my account is on. Suit yourself...
	curl_setopt($ch, CURLOPT_URL, "http://203.104.209.39/kcs/resources/swf/{$f}");
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36");
	
	$data = curl_exec($ch);
	if(!$data) print "FAIL! ".curl_error($ch). PHP_EOL;
	else { 
		print "OK!".PHP_EOL;
		file_put_contents($outdir."/{$f}", trim($data));
	}
	
	curl_close($ch);
}
print "...DONE" . PHP_EOL;

print "There's liekly more to be done here but SG is lazy. Fin." . PHP_EOL;
?>
