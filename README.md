# kantcollesrv 
SoftwareGuy's take on a Open Source KanColle server (PHP/MySQL). Uses some nginx black magic as well.
Some of the code is sloppy and/or puts extra cycles on the host, but hey it works!

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
TODO.

# Roadmap
My free time is limited as I'm a on-demand IT guy. So my priorities is to get admirals to be able to login and get to the dashboard part of the game, then able to build ships and other essentials. Battles and whatnot can wait; it's mainly core functionality that I want plumbed in.

Some parts like the tutorial will possibly be skipped, instead on enlisting as an Admiral will have a option saying "Starter ship" and you can pick the girl you want from there. Or maybe I let you pick whatever girl you want.

I also want to see what happens when you have a boss girl in your fleets. Does the game break or what?

# I'm from DMM and I'll send you an C&D!
Pfft... Seriously, you have better things to do with your time. 
Let me just say this: I'm not using your assets (apart from your art, sounds and SWF data) to make money, I'm doing this for fun and as a hobby. And plus, your API is terrible, perhaps your R&D can use my code to fix your broken things?

# Credits
* DMM - Base Game
* KCT Team & uppfinnarn - For their work on KCT and KCSrv, along with the API documentation
* Salt and friends from #kancolle on Rizon - for their help decoding the names of the API shipgirl attributes
