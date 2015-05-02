# kantcollesrv 
SoftwareGuy's take on a Open Source KanColle server (PHP/MySQL). Uses some nginx black magic as well.
Some of the code is sloppy and/or puts extra cycles on the host, but hey it works!

# Notice
I've suspended development on this open source server due to reasons I've mentioned on my blog post.

# What?
This is my own implementation of DMM's server API for the game called "Kantai Collection". Basically, it's a server that allows you to play the game without the need of using DMM's own server and/or needing a DMM account.
The project started as a fork of kcsrv (https://github.com/KanColleTool/kcsrv). I then adapted it into PHP, and with the KCT documentation and WireShark/Chrome Inspector I fleshed out the missing endpoints to make it boot. This was a Xmas '14 project, I started thinking "well, why the hell not make it public?"
I'm using nginx because it's much better than Apache 2.x, although Apache 2.4 has got it's improvements.

# What works so far?
* Admiral enlistment and ship girl assigning (along with her starter gear)
* API tokens and game login.
* Enough game API endpoints set up to allow the game to run without errorcats every few seconds.

# What doesn't work?
* Everything else in the official API except for the features above.

# Is there a working live example?
I intend to setup a little server that will allow you to play on the server. This will also be handy for stress testing and handling end points, because there's a lot of stuff not implemented and you'll get game freezes or errorcats (basically a screen saying "Game Error, please reload"). 

# How to set up?
Requirements:
* Competent ship management skills and able to keep your cool when your shipgirl vents at you for being a shitty admiral (happens)
* Linux Distro of your choice, recommended Debian/*buntu based.
* A modern version of nginx
* PHP-FPM 5.3+ with MySQLi installed
* MySQL or MariaDB or whatever forks out there. Wanna port it to another DB? Be my guest.

Brew Instructions:
* Install Debian/Ubuntu
* Install MySQL/MariaDB
* Install nginx and php5-fpm, along with php5-cli and php5-curl.
* Copy the nginx configuration from the docs folder into nginx's site configuration directory and reload it.
* Make a folder under your docroot called "kantcolle". Put everything here into that folder.
* Open a terminal, nativate to the "static" directory and using the commandline PHP binary, run both shipgirl_dl.php and asset_dl.php. This will download the assets from DMM and set them up for you in their respective folders. *BEWARE: This will take a while...*
* If the above step fails then just make a "kcs" folder under "static" and try again. It shouldn't have issues unless you don't have permissions to write to that folder...
* Make a database and import the SQL dump file provided. Also make a DB user *(NEVER RUN APIS AS ROOT MYSQL USER)*.
* Open the config.inc.php file under "system" and fill in your SQL details and the base URL path of your installation.
* Go to localhost/kantcolle or your IP (if you're using a VM) and then enlist. Choose a starter ship.
* You should get a message saying everything went okay when enlisting.
* Go back to the kantcolle homepage and login. Hey presto, you have your shipgirl. She will be mute, nothing you can do with that (stupid voices are in randomly hashed directories on DMM's stuff)

# Roadmap
My free time is limited as I'm a on-demand IT guy. So my priorities is to get admirals to be able to login and get to the dashboard part of the game, then able to build ships and other essentials. Battles and whatnot can wait; it's mainly core functionality that I want plumbed in.
Some parts like the tutorial are be skipped, instead on enlisting as an Admiral will have a option saying "Starter ship" and you can pick the girl you want from there.
NOTE: There's a script called "testCode.php" that will allow you to pick any ship you want as long as you have a ship id. Edit that file, and where you have '183' (that's Ooyou, the NPC secretary) change that to the shipgirls ID. You can get multiples of the same ship. You'll have to manually add them into your fleet, because fleet management is broken like everything else.

# I'm from DMM and I'll send you an C&D!
Pfft... Seriously, you have better things to do with your time. 
I'm not using your assets (apart from your art, sounds and SWF data) to make money, I'm doing this for fun and as a hobby. And plus, your API is terrible, perhaps your R&D can use my code to fix your broken things?

# Credits
* Myself - KantColle PHP API Development
* DMM - Base Game
* KCT Team & uppfinnarn - For their work on KCT and KCSrv (their original python implementation), along with the API documentation
* Salt and friends from #kancolle on Rizon - for their help decoding the names of the API shipgirl attributes
