-- phpMyAdmin SQL Dump
-- version 4.2.12deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 02, 2015 at 11:03 AM
-- Server version: 5.5.43-0+deb8u1
-- PHP Version: 5.6.7-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kancolle`
--

-- --------------------------------------------------------

--
-- Table structure for table `admirals`
--

DROP TABLE IF EXISTS `admirals`;
CREATE TABLE IF NOT EXISTS `admirals` (
`id` int(11) NOT NULL,
  `nickname` varchar(16) NOT NULL,
  `password` char(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` char(40) DEFAULT NULL,
  `tutorial_progress` int(11) DEFAULT '100',
  `tutorial_complete` tinyint(1) DEFAULT '1',
  `res_fuel` int(11) DEFAULT '10000',
  `res_steel` int(11) DEFAULT '10000',
  `res_ammo` int(11) DEFAULT '10000',
  `res_bauxite` int(11) DEFAULT '10000',
  `res_quickbuilds` int(11) DEFAULT '100',
  `res_buckets` int(11) DEFAULT '100',
  `res_devmats` int(11) DEFAULT '100',
  `res_screws` int(11) DEFAULT '0',
  `start_time` int(11) DEFAULT '0',
  `level` int(11) DEFAULT '1',
  `rank` int(11) DEFAULT '0',
  `experience` int(11) DEFAULT '0',
  `fleetname` varchar(16) DEFAULT 'No Name',
  `max_ships` int(11) DEFAULT '250',
  `max_slotitems` int(11) DEFAULT '500',
  `max_furniture` int(11) DEFAULT '255',
  `playtime` int(11) DEFAULT '0',
  `curr_furniture` varchar(255) DEFAULT '1,38,77,110,151,168',
  `curr_fleets` tinyint(4) DEFAULT '4',
  `curr_docks` int(11) DEFAULT '4',
  `curr_cbays` int(11) DEFAULT '4',
  `furn_coins` int(11) DEFAULT '250',
  `sortie_wins` int(11) DEFAULT '0',
  `sortie_losses` int(11) DEFAULT '0',
  `exped_wins` int(11) DEFAULT '0',
  `exped_fails` int(11) DEFAULT '0',
  `pvp_wins` int(11) DEFAULT '0',
  `pvp_losses` int(11) DEFAULT '0',
  `pvp_challenges` int(11) DEFAULT '0',
  `pvp_challenges_won` int(11) DEFAULT '0',
  `first_flag` int(11) DEFAULT '1',
  `pvp` varchar(255) DEFAULT '0,0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `factory_docks`
--

DROP TABLE IF EXISTS `factory_docks`;
CREATE TABLE IF NOT EXISTS `factory_docks` (
`rowid` int(11) NOT NULL,
  `admiral_id` int(11) NOT NULL,
  `dock_id` int(11) NOT NULL DEFAULT '1',
  `dock_state` int(11) DEFAULT '0',
  `dock_builtship_id` int(11) DEFAULT '0',
  `dock_buildtime` int(11) DEFAULT '0',
  `fuel` int(11) DEFAULT '0',
  `ammo` int(11) DEFAULT '0',
  `steel` int(11) DEFAULT '0',
  `bauxite` int(11) DEFAULT '0',
  `cmats` int(11) DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `repair_docks`
--

DROP TABLE IF EXISTS `repair_docks`;
CREATE TABLE IF NOT EXISTS `repair_docks` (
`rowid` int(11) NOT NULL,
  `admiral_id` int(11) NOT NULL,
  `dock_id` int(11) NOT NULL DEFAULT '1',
  `dock_state` int(11) DEFAULT '0',
  `dock_ship_id` int(11) DEFAULT '0',
  `dock_recoverytime` int(11) DEFAULT '0',
  `fuel` int(11) DEFAULT '0',
  `ammo` int(11) DEFAULT '0',
  `steel` int(11) DEFAULT '0',
  `bauxite` int(11) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ships_enlisted`
--

DROP TABLE IF EXISTS `ships_enlisted`;
CREATE TABLE IF NOT EXISTS `ships_enlisted` (
`row_id` int(11) NOT NULL,
  `admiral_id` int(11) NOT NULL DEFAULT '0',
  `ship_id` int(11) NOT NULL DEFAULT '1',
  `game_id` int(11) NOT NULL DEFAULT '1',
  `experience` tinytext NOT NULL,
  `level` int(11) DEFAULT '1',
  `curr_health` tinyint(4) DEFAULT '10',
  `max_health` tinyint(4) DEFAULT '10',
  `curr_fuel` int(11) DEFAULT '15',
  `curr_ammo` int(11) DEFAULT '20',
  `base_armor` tinyint(4) DEFAULT '0',
  `max_armor` tinyint(4) DEFAULT '0',
  `base_evade` int(11) DEFAULT '0',
  `max_evade` tinyint(4) DEFAULT '0',
  `aircraft` tinyint(4) DEFAULT '0',
  `speed` tinyint(4) DEFAULT '1',
  `ship_range` tinyint(4) DEFAULT '1',
  `base_firepower` int(11) DEFAULT '0',
  `max_firepower` int(11) DEFAULT '0',
  `base_torpedo` tinyint(4) DEFAULT '0',
  `max_torpedo` tinyint(4) DEFAULT '0',
  `base_aa` tinyint(4) DEFAULT '0',
  `max_aa` tinyint(4) DEFAULT '0',
  `base_asw` tinyint(4) DEFAULT '0',
  `max_asw` tinyint(4) DEFAULT '0',
  `base_los` tinyint(4) DEFAULT '1',
  `max_los` tinyint(4) DEFAULT '1',
  `base_luck` tinyint(4) DEFAULT '10',
  `max_luck` tinyint(4) DEFAULT '10',
  `stars` tinyint(4) DEFAULT '1',
  `slots` tinyint(4) DEFAULT '2',
  `curr_equipment` varchar(255) DEFAULT '-1,-1,-1,-1,-1'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ship_equipment`
--

DROP TABLE IF EXISTS `ship_equipment`;
CREATE TABLE IF NOT EXISTS `ship_equipment` (
`rowid` int(11) NOT NULL,
  `admiral_id` int(11) DEFAULT '0',
  `ship_id` int(11) DEFAULT '0',
  `equipment_id` int(11) DEFAULT '1',
  `game_id` int(11) DEFAULT '0',
  `game_type_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ship_fleets`
--

DROP TABLE IF EXISTS `ship_fleets`;
CREATE TABLE IF NOT EXISTS `ship_fleets` (
`rowid` int(11) NOT NULL,
  `admiral_id` int(11) NOT NULL DEFAULT '0',
  `fleet_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(32) CHARACTER SET latin1 NOT NULL DEFAULT 'Fleet',
  `name_id` int(11) NOT NULL DEFAULT '0',
  `mission` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '0,0,0,0',
  `flagship` int(11) NOT NULL DEFAULT '0',
  `first_ship` int(11) DEFAULT '-1',
  `second_ship` int(11) DEFAULT '-1',
  `third_ship` int(11) DEFAULT '-1',
  `fourth_ship` int(11) DEFAULT '-1',
  `fifth_ship` int(11) DEFAULT '-1',
  `sixth_ship` int(11) DEFAULT '-1'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admirals`
--
ALTER TABLE `admirals`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factory_docks`
--
ALTER TABLE `factory_docks`
 ADD PRIMARY KEY (`rowid`);

--
-- Indexes for table `repair_docks`
--
ALTER TABLE `repair_docks`
 ADD PRIMARY KEY (`rowid`);

--
-- Indexes for table `ships_enlisted`
--
ALTER TABLE `ships_enlisted`
 ADD PRIMARY KEY (`row_id`);

--
-- Indexes for table `ship_equipment`
--
ALTER TABLE `ship_equipment`
 ADD PRIMARY KEY (`rowid`);

--
-- Indexes for table `ship_fleets`
--
ALTER TABLE `ship_fleets`
 ADD PRIMARY KEY (`rowid`), ADD KEY `admiral_id` (`admiral_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admirals`
--
ALTER TABLE `admirals`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `factory_docks`
--
ALTER TABLE `factory_docks`
MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `repair_docks`
--
ALTER TABLE `repair_docks`
MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ships_enlisted`
--
ALTER TABLE `ships_enlisted`
MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ship_equipment`
--
ALTER TABLE `ship_equipment`
MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ship_fleets`
--
ALTER TABLE `ship_fleets`
MODIFY `rowid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
