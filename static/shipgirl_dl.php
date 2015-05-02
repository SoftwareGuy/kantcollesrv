<?PHP
if(!file_exists("api_start2.json")) die("Please put api_start2.json in the same directory as this script so we can download the goods. Exiting...". PHP_EOL);

$buffer = str_replace("svdata=", "", file_get_contents("api_start2.json"));
$buffer = json_decode($buffer, true, 2048);

$outdir = "./kcs/resources/swf/ships";
if (!mkdir($outdir, 0755, true)) die("Unable to create directory to write data... If the directory already exists just delete it or rename it.");
print "SoftwareGuy's KC Ship girl Assets Downloader" . PHP_EOL;

foreach($buffer['api_data']['api_mst_shipgraph'] as $sgirl) {
	set_time_limit(30);
	
	print "ID: {$sgirl['api_filename']} ... ";
	
	$ch = curl_init();
	// Could change this; this is the server my account is on. Suit yourself...
	curl_setopt($ch, CURLOPT_URL, "http://203.104.209.39/kcs/resources/swf/ships/{$sgirl['api_filename']}.swf");
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36");
	
	$data = curl_exec($ch);
	if(!$data) print "FAIL! ".curl_error($ch). PHP_EOL;
	else { 
		print "OK!".PHP_EOL;
		file_put_contents($outdir."/{$sgirl['api_filename']}.swf", trim($data));
	}
	
	curl_close($ch);
}

/* Code below here is not working. Apparently ship girl sound is actually another directory.
 * I tried to do the same as above but looking in <ip>/kcs/sound/<apifilename>/ . Seems DMM keeps their stuff in really odd places.
 * If anyone knows how to find the voice server path, please let me (SoftwareGuy) know. Thanks.
 * -----
print "Now downloading Audio assets for each ship girl. Standby..." . PHP_EOL;

foreach($buffer['api_data']['api_mst_shipgraph'] as $sgirl) {
	set_time_limit(45);
	
	$outdir = "./kcs/sound/ships/{$sgirl['api_filename']}";
	if (!mkdir($outdir, 0755, true)) die("Unable to create directory to write data...");
	
	print "ID: {$sgirl['api_filename']} ... " . PHP_EOL;
	
	// First check if we have sound clips for that girl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://203.104.209.39/kcs/sound/{$sgirl['api_filename']}/1.mp3");
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36");
		$data = curl_exec($ch);
		if(!$data) {
			print "Huh? {$sgirl['api_filename']} has no voices? That's strange... " . curl_error($ch) . PHP_EOL;
			curl_close($ch);
		} else {
			// 29 sound clips as far as I know for the base voices. Not sure about hourlies, would have to check that.
			for($i = 1; $i <= 29; $i++) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://203.104.209.39/kcs/sound/{$sgirl['api_filename']}/{$i}.mp3");
				curl_setopt($ch, CURLOPT_FAILONERROR, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.89 Safari/537.36");
				
				$data = curl_exec($ch);
				if(!$data) print "-> Voice {$i} FAIL! ". curl_error($ch). PHP_EOL;
				else { 
					print "-> Voice {$i} OK!".PHP_EOL;
					file_put_contents($outdir."/{$sgirl['api_filename']}/{$i}.mp3", trim($data));
				}

				curl_close($ch);
			}
		}
}
*/

print "Fin.".PHP_EOL;
exit;
?>