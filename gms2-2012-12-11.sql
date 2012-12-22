-- main GMS create with some basic data to get going.

-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 27, 2012 at 02:14 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gms2`
--

-- --------------------------------------------------------

--
-- Table structure for table `clean_input`
--

CREATE TABLE IF NOT EXISTS `clean_input` (
  `idclean_input` int(11) NOT NULL AUTO_INCREMENT,
  `area` tinyint(4) NOT NULL,
  `content` varchar(2000) COLLATE latin1_general_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_dt` datetime NOT NULL,
  `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idclean_input`),
  UNIQUE KEY `idclean_input` (`idclean_input`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `area` (`area`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `idcomment` bigint(20) NOT NULL AUTO_INCREMENT,
  `comment_txt` varchar(1000) COLLATE latin1_general_ci DEFAULT NULL,
  `related_to` int(11) NOT NULL,
  `liked` int(10) unsigned NOT NULL DEFAULT '0',
  `disliked` int(10) unsigned NOT NULL DEFAULT '0',
  `approved` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'W' COMMENT 'W if waiting; A if approved; R if rejected',
  `approved_by` int(11) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL DEFAULT '1' COMMENT '1 for Anonymous',
  `created_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idcomment`),
  KEY `related_to` (`related_to`),
  KEY `created_by` (`created_by`),
  KEY `approved_by` (`approved_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`idcomment`, `comment_txt`, `related_to`, `liked`, `disliked`, `approved`, `approved_by`, `created_by`, `created_dt`) VALUES
(3, 'This screen will have the same Subject Area Drop Down as GMS Problem Submit screen and collapsable List of Submitted Problems. This List will be popualted with approved Problems for Subject Area chosen in its Drop Down. Click on a Problem highlights it. DoubleClick opens second level (Comments) of collapsable for respective Problem. ', 2, 0, 0, 'W', 2, 1, '2012-08-25 03:24:55');

-- --------------------------------------------------------

--
-- Table structure for table `fellow`
--

CREATE TABLE IF NOT EXISTS `fellow` (
  `idfellow` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(12) COLLATE latin1_general_ci DEFAULT NULL,
  `fname` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `lname` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `email` varchar(75) COLLATE latin1_general_ci NOT NULL,
  `address` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `city` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `country` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `state` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `zip` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `disputer` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'N' COMMENT 'Y for dusputers',
  `moderator` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'N' COMMENT 'Y for moderators',
  `member` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'N' COMMENT 'Y for members',
  `expert` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'N' COMMENT 'Y for experts',
  `volunteer` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'N' COMMENT 'Could be N, A (active), or P (potential)',
  `comments` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `created_dt` datetime NOT NULL,
  `updated_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idfellow`),
  UNIQUE KEY `idfellow` (`idfellow`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `fellow`
--

INSERT INTO `fellow` (`idfellow`, `nick`, `password`, `fname`, `lname`, `email`, `address`, `city`, `country`, `state`, `zip`, `disputer`, `moderator`, `member`, `expert`, `volunteer`, `comments`, `created_dt`, `updated_dt`) VALUES
(1, 'anonymous ', NULL, NULL, NULL, 'N/A', NULL, NULL, NULL, NULL, NULL, 'N', 'N', 'N', 'N', 'N', NULL, '2012-08-23 00:00:00', '0000-00-00 00:00:00'),
(2, 'gmik', '12345', 'George', 'Mikhailovsky', 'gmikhai@yahoo.com', '6603 Tucker Ave.', 'McLean', 'USA', 'VA', '22101', 'Y', 'Y', 'Y', 'Y', 'Y', 'This is a comment', '2012-07-12 00:58:10', '0000-00-00 00:00:00'),
(3, 'petr', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, 'N', 'N', 'N', 'N', 'N', NULL, '2012-07-12 01:04:54', '0000-00-00 00:00:00'),
(4, 'SQuick', 'pass1234', 'Simon', 'Quick', 'sq@hotmail.com', NULL, 'Pert', 'Andorra', 'Mississippi', NULL, 'N', 'N', 'N', 'N', 'N', NULL, '2012-10-09 22:52:23', '2012-10-09 22:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `input`
--

CREATE TABLE IF NOT EXISTS `input` (
  `idinput` int(11) NOT NULL AUTO_INCREMENT,
  `idsubject` tinyint(4) NOT NULL,
  `suggestion` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `details` varchar(1000) COLLATE latin1_general_ci DEFAULT NULL,
  `email` varchar(75) COLLATE latin1_general_ci DEFAULT NULL,
  `liked` int(10) unsigned NOT NULL DEFAULT '0',
  `disliked` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '1' COMMENT '1 for Anonymous',
  `approved` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'W' COMMENT 'W if waiting; A if approved; R if rejected',
  `approved_by` int(11) NOT NULL DEFAULT '1',
  `created_dt` datetime NOT NULL,
  PRIMARY KEY (`idinput`),
  UNIQUE KEY `idinput` (`idinput`),
  KEY `created_by` (`created_by`),
  KEY `idsubject` (`idsubject`),
  KEY `approved_by` (`approved_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=19 ;

--
-- Dumping data for table `input`
--

INSERT INTO `input` (`idinput`, `idsubject`, `suggestion`, `details`, `email`, `liked`, `disliked`, `created_by`, `approved`, `approved_by`, `created_dt`) VALUES
(1, 14, 'This is my suggestion', 'This is my comments', NULL, 0, 0, 3, 'W', 2, '2012-07-12 02:27:43'),
(2, 12, 'Just another suggestion!', 'And just another comment', 'gmikhai@yahoo.com', 0, 0, 1, 'A', 2, '2012-08-25 00:00:00'),
(3, 14, 'Testing 01', 'testing the full site phase1 with db on gms host', 'testmail@nomail.com', 0, 0, 1, 'W', 1, '2012-10-09 01:09:20'),
(4, 14, 'test02', 'some details entered here', 'nomail@jjj.com', 0, 0, 1, 'W', 1, '2012-10-09 01:22:21'),
(5, 14, 'test02', 'some details entered here', 'nomail@jjj.com', 0, 0, 1, 'W', 1, '2012-10-09 01:42:33'),
(6, 14, 'test03', 'here are my details', 'mail@j1.com', 0, 0, 1, 'W', 1, '2012-10-09 01:43:28'),
(7, 17, 'testing 04 - ', 'should be for art and culture subj area', 'jeff@nomail.com', 0, 0, 1, 'W', 1, '2012-10-09 01:55:25'),
(8, 13, 'what is the names of test05', 'details found here. you can type anything that you want, but it may not exceed 1000 characters.', 'nomail@oo.it', 0, 0, 1, 'W', 1, '2012-10-09 02:42:57'),
(9, 18, 'To be or not to be?', 'This is just a test description and nothing more...', 'gmik@yahoo.com', 0, 0, 1, 'W', 1, '2012-10-09 22:58:10'),
(10, 13, 'What was the first - hen or egg?', 'I could provide a lot of detail to this problem but I decided not to do this.', 'BBlanks@yahoo.com', 0, 0, 1, 'W', 1, '2012-10-09 23:26:39'),
(11, 15, 'another test', 'no email required', '', 0, 0, 1, 'W', 1, '2012-10-10 01:48:11'),
(12, 15, 'another test', 'no email required', '', 0, 0, 1, 'W', 1, '2012-10-10 01:52:53'),
(13, 13, 'another test of date and complaints', 'should suppress all warnings by PHP about no timezone set', 'jeff@nomail.com', 0, 0, 1, 'W', 1, '2012-10-10 03:43:36'),
(14, 15, 'George''s Test', 'Details of my test. George', NULL, 0, 0, 1, 'W', 1, '2012-10-21 00:00:00'),
(15, 12, 'George''s Test 2', 'Details of my test 2. George', NULL, 0, 0, 1, 'W', 1, '0000-00-00 00:00:00'),
(16, 13, 'testing all the submits', 'heres the stuff', 'mymail@jsw.com', 0, 0, 1, 'W', 1, '2012-11-02 01:02:50'),
(17, 10, 'Submit your problem to the Global Mind Share Submit your problem to the Global Mind Share', 'ostess Brands Wednesday gave striking bakers until 4 p.m. Dallas time Thursday to get back to work. If they donâ€™t, Hostess said, it will file a motion with the U.S. Bankruptcy Court on Friday to liquidate the company.\r\nâ€œWe simply do not have the financial resources to survive an ongoing national strike,â€ said Gregory Rayburn, chairman and chief executive of the Irving-based snack maker.\r\nâ€œTherefore, if sufficient employees do not return to work by 5 p.m. EST on Thursday to restore normal operations, we will be forced to immediately move to liquidate the entire company, which will result in the loss of nearly 18,000 jobs.â€\r\nRayburn said it is now up to members of the Bakery, Confectionery, Tobacco Workers and Grain Millers Union, and Frank Hurt, its international president, â€œto decide if they want to call off the strike and save this company, or cause massive financial harm.â€\r\nHurt could not be reached for comment late Wednesday.\r\nHostessâ€™ second-largest union went on st', '', 0, 0, 1, 'W', 1, '2012-11-15 03:27:12'),
(18, 12, 'How can we distribute water so it gets to those who need it?', 'We need to address the shortage of water worldwide and it needs to be addressed sooner than later because it will be one of the largest problems facing mankind in the future.  There are already shortages of water in several places in the world and this problem will only get worse.', 'fadinmd@verison.net', 0, 0, 1, 'W', 1, '2012-11-21 04:56:30');

-- --------------------------------------------------------

--
-- Table structure for table `input_clean_xwalk`
--

CREATE TABLE IF NOT EXISTS `input_clean_xwalk` (
  `input_id` int(11) NOT NULL,
  `clean_id` int(11) NOT NULL,
  PRIMARY KEY (`input_id`,`clean_id`),
  KEY `input_id` (`input_id`),
  KEY `clean_id` (`clean_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moderation`
--

CREATE TABLE IF NOT EXISTS `moderation` (
  `idmoderation` int(11) NOT NULL AUTO_INCREMENT,
  `input` int(11) NOT NULL,
  `moderator` int(11) NOT NULL,
  `action` char(1) COLLATE latin1_general_ci NOT NULL DEFAULT 'N' COMMENT 'A if approved or R if rejected',
  `moderation_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idmoderation`),
  UNIQUE KEY `idmoderation` (`idmoderation`),
  KEY `input` (`input`),
  KEY `moderator` (`moderator`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rostrum`
--

CREATE TABLE IF NOT EXISTS `rostrum` (
  `idrostrum` int(11) NOT NULL AUTO_INCREMENT,
  `our_text` varchar(8000) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idrostrum`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Our announcements on Start Page' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subjarea`
--

CREATE TABLE IF NOT EXISTS `subjarea` (
  `idsubjarea` tinyint(4) NOT NULL AUTO_INCREMENT,
  `area` varchar(45) COLLATE latin1_general_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idsubjarea`),
  UNIQUE KEY `idsubjarea` (`idsubjarea`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='List of Subject Areas' AUTO_INCREMENT=19 ;

--
-- Dumping data for table `subjarea`
--

INSERT INTO `subjarea` (`idsubjarea`, `area`, `created_by`, `created_dt`) VALUES
(10, 'Society and Politics', 2, '2012-07-12 02:17:28'),
(11, 'Economy and Finance', 2, '2012-07-12 02:18:05'),
(12, 'Environment and Climate Changes', 2, '2012-07-12 02:18:34'),
(13, 'Health and Medicine', 2, '2012-07-12 02:22:11'),
(14, 'Education', 2, '2012-07-12 02:22:38'),
(15, 'Human Rights', 2, '2012-07-12 02:23:16'),
(16, 'Science and Technology', 2, '2012-07-12 02:23:58'),
(17, 'Art and Culture', 2, '2012-07-12 02:24:23'),
(18, 'Religion and Philosophy', 2, '2012-07-12 02:24:46');



--
-- Dumping data for table `rostrum`
--

INSERT INTO `rostrum` (`idrostrum`, `our_text`, `created_by`, `created_on`) VALUES
(1, 'Liftoff : we have now launched the amazing GMS website for all members of the Earth to join in a unilateral effort to solve the problems that face us all', 2, '2012-11-26 09:15:00'),
(2, 'Version 1.8 of GMS was launched.', 2, '2012-11-18 03:00:00');
(3, 'Phase 1 of Global Mind Share will run Version 2 - Utilizing an improved MVC model', 2, '2012-12-15 19:30:00');


--
-- Dumping data for table `comment`
--
INSERT INTO `comment` ( `idcomment` ,  `comment_txt` ,  `related_to` ,  `liked` ,  `disliked` ,  `created_by` )  VALUES 
(1, 'I do NOT like Eggs', 2, 0, 1, 1);
(2, 'I DO like Eggs', 2, 1, 0, 1);
--
--
-- Constraints for dumped tables
--

--
-- Constraints for table `clean_input`
--
ALTER TABLE `clean_input`
  ADD CONSTRAINT `clean_input_ibfk_1` FOREIGN KEY (`area`) REFERENCES `subjarea` (`idsubjarea`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `clean_input_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `fellow` (`idfellow`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `clean_input_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `fellow` (`idfellow`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`related_to`) REFERENCES `input` (`idinput`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `fellow` (`idfellow`),
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `fellow` (`idfellow`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `input`
--
ALTER TABLE `input`
  ADD CONSTRAINT `input_ibfk_3` FOREIGN KEY (`idsubject`) REFERENCES `subjarea` (`idsubjarea`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `input_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `fellow` (`idfellow`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `input_ibfk_6` FOREIGN KEY (`approved_by`) REFERENCES `fellow` (`idfellow`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `input_clean_xwalk`
--
ALTER TABLE `input_clean_xwalk`
  ADD CONSTRAINT `input_clean_xwalk_ibfk_3` FOREIGN KEY (`input_id`) REFERENCES `input` (`idinput`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `input_clean_xwalk_ibfk_4` FOREIGN KEY (`clean_id`) REFERENCES `clean_input` (`idclean_input`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `moderation`
--
ALTER TABLE `moderation`
  ADD CONSTRAINT `moderation_ibfk_3` FOREIGN KEY (`input`) REFERENCES `input` (`idinput`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `moderation_ibfk_4` FOREIGN KEY (`moderator`) REFERENCES `fellow` (`idfellow`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `rostrum`
--
ALTER TABLE `rostrum`
  ADD CONSTRAINT `rostrum_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `fellow` (`idfellow`) ON UPDATE NO ACTION;

--
-- Constraints for table `subjarea`
--
ALTER TABLE `subjarea`
  ADD CONSTRAINT `subjarea_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `fellow` (`idfellow`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
