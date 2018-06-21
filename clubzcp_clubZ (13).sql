-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 21, 2018 at 07:11 AM
-- Server version: 5.6.39
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clubzcp_clubZ`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activityId` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `image` varchar(255) NOT NULL,
  `leader_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `location` text NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `fee_type` varchar(250) NOT NULL,
  `fee` float NOT NULL,
  `min_users` int(11) NOT NULL,
  `max_users` int(11) NOT NULL,
  `user_role` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `terms_conditions` text NOT NULL,
  `is_hide` tinyint(4) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activityId`, `name`, `image`, `leader_id`, `club_id`, `creator_id`, `location`, `latitude`, `longitude`, `fee_type`, `fee`, `min_users`, `max_users`, `user_role`, `description`, `terms_conditions`, `is_hide`, `status`, `crd`, `upd`) VALUES
(20, 'Diwali Festival', '523bd8f1619144b713495338aa882fd7.jpg', 0, 87, 98, '502, 503 & 504 Krishna Tower Above ICICI Bank, Main Rd, Brajeshwari Extension, Pipliyahana, Indore, Madhya Pradesh 452016, India', '22.705138200000004', '75.9090618', 'Voluntary', 25, 2, 10, 'admin', 'Diwali festival and we will get you on Friday', 'Terms and conditions and a good weekend and then delete it and you it', 0, '1', '2018-06-13 12:13:57', '2018-06-13 12:13:57'),
(25, 'Hello', 'be6ee47e32a3036f4fba2b91abbee2a4.jpg', 99, 87, 98, '502, 503 & 504 Krishna Tower Above ICICI Bank, Main Rd, Brajeshwari Extension, Pipliyahana, Indore, Madhya Pradesh 452016, India', '22.705138200000004', '75.9090618', 'Free', 0, 1, 5, 'Club Manager', 'Eyjj', 'Rujk', 0, '1', '2018-06-19 12:22:55', '2018-06-19 12:22:55'),
(26, 'Make Music - June 21', '222e3ab9fe913e39e87f52861cf3ebf6.jpg', 111, 97, 112, 'Vijay Nagar, Indore, Madhya Pradesh 452010, India', '22.753284800000003', '75.8936962', 'Free', 0, 2, 5, 'Club Manager', 'The Fete de la Musique, also known as Music Day, Make Music Day or World Music Day, is an annual music celebration that takes place on 21 June.', 'Hrhrhhr jdjrjrn jn. Enjeken. Skskns. Dbdjjdhbr. S jsjje bfjs ehrhrkjd ksjehbjdU.Ns jsbennd sjsvevdbdks. Ejsnsjdbdbooknsshejgd khsbsjkd nskhddnd\' noksvekksvs nkshhwjedbnd jkejhehjsjsjejjr. Jsvsksbsb', 0, '1', '2018-06-21 09:47:24', '2018-06-21 09:47:24'),
(27, 'Beauty googly ', 'e4f9dd0360a0d9cf9910b84fb8caf833.jpg', 112, 99, 111, 'Dewas, Madhya Pradesh, India', '22.962267199999996', '76.0507949', 'Dynamic', 12, 0, 1, 'Club Manager', 'Whbehe he rjr he rjr rhr rhr he rjr he rjr rj rbnrnrj rjr rnr jr rhr. Rhr rhr dbrn rjr rbrbr r r ', 'Demllllmd and rjr rjr f rjr fjf f ', 0, '1', '2018-06-21 09:53:46', '2018-06-21 10:02:23'),
(28, 'Nupu Activity', 'cbd724e917462f2afcd4547ffe3434bb.jpg', 111, 97, 112, 'Old Palasia, Indore, Madhya Pradesh 452018, India', '22.728976100000004', '75.8885912', 'Dynamic', 26, 2, 5, 'Club Manager', 'Nupur activity', 'No terms and conditions', 0, '1', '2018-06-21 10:58:04', '2018-06-21 10:58:04'),
(32, 'Second Activity', '17cd2acab0ecaae8d8c674e672430e60.jpg', 111, 97, 112, 'Indore, Madhya Pradesh, India', '22.719568699999996', '75.8577258', 'Dynamic', 25.95, 1, 10, 'Club Manager', 'Euibcdhjkl', 'R78tghii', 0, '1', '2018-06-21 11:06:41', '2018-06-21 11:06:41');

-- --------------------------------------------------------

--
-- Table structure for table `activity_confirm`
--

CREATE TABLE `activity_confirm` (
  `activityConfirmId` int(11) NOT NULL,
  `activity_event_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activity_confirm`
--

INSERT INTO `activity_confirm` (`activityConfirmId`, `activity_event_id`, `activity_id`, `user_id`, `affiliate_id`, `status`, `crd`, `upd`) VALUES
(31, 16, 26, 111, 0, '1', '2018-06-21 10:01:33', '2018-06-21 10:01:33'),
(32, 16, 26, 111, 144, '1', '2018-06-21 10:01:33', '2018-06-21 10:01:33'),
(33, 16, 26, 111, 142, '1', '2018-06-21 10:01:33', '2018-06-21 10:01:33'),
(41, 16, 26, 98, 113, '1', '2018-06-21 12:22:51', '2018-06-21 12:22:51'),
(47, 18, 27, 112, 148, '1', '2018-06-21 12:34:09', '2018-06-21 12:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `activity_events`
--

CREATE TABLE `activity_events` (
  `activityEventId` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `event_title` varchar(200) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `location` text NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activity_events`
--

INSERT INTO `activity_events` (`activityEventId`, `activity_id`, `event_title`, `event_date`, `event_time`, `location`, `latitude`, `longitude`, `description`, `status`, `crd`, `upd`) VALUES
(11, 20, 'My events', '2018-07-01', '18:15:00', '502, 503 & 504 Krishna Tower Above ICICI Bank, Main Rd, Brajeshwari Extension, Pipliyahana, Indore, Madhya Pradesh 452016, India', '22.705138200000004', '75.9090618', '', '1', '2018-06-15 10:46:17', '2018-06-15 10:46:17'),
(15, 20, 'edfuyyy', '2018-06-27', '00:51:00', '502, 503 & 504 Krishna Tower Above ICICI Bank, Main Rd, Brajeshwari Extension, Pipliyahana, Indore, Madhya Pradesh 452016, India', '22.705138200000004', '75.9090618', '', '1', '2018-06-19 06:22:02', '2018-06-19 06:22:02'),
(16, 26, '1st', '2018-06-21', '18:21:00', '502, 503 & 504 Krishna Tower Above ICICI Bank, Main Rd, Brajeshwari Extension, Pipliyahana, Indore, Madhya Pradesh 452016, India', '22.705138200000004', '75.9090618', '', '1', '2018-06-21 09:52:10', '2018-06-21 09:52:10'),
(17, 26, '2nd event', '2018-06-22', '15:22:00', 'Vijaynagar, Naranpura, Ahmedabad, Gujarat 380013, India', '23.062358800000002', '72.55745329999999', '', '1', '2018-06-21 09:52:50', '2018-06-21 09:52:50'),
(18, 27, 'beautiful class', '2018-06-22', '16:30:00', 'Dewas, Madhya Pradesh, India', '22.962267199999996', '76.0507949', '', '1', '2018-06-21 09:54:26', '2018-06-21 09:54:26');

-- --------------------------------------------------------

--
-- Table structure for table `activity_join`
--

CREATE TABLE `activity_join` (
  `activityJoinId` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activity_join`
--

INSERT INTO `activity_join` (`activityJoinId`, `activity_id`, `user_id`, `affiliate_id`, `status`, `crd`, `upd`) VALUES
(55, 26, 111, 0, '1', '2018-06-21 10:01:24', '2018-06-21 10:01:24'),
(56, 26, 111, 144, '1', '2018-06-21 10:01:24', '2018-06-21 10:01:24'),
(57, 26, 111, 142, '1', '2018-06-21 10:01:24', '2018-06-21 10:01:24'),
(58, 26, 111, 143, '1', '2018-06-21 10:01:24', '2018-06-21 10:01:24'),
(59, 26, 111, 145, '1', '2018-06-21 10:01:24', '2018-06-21 10:01:24'),
(75, 26, 98, 0, '1', '2018-06-21 12:22:43', '2018-06-21 12:22:43'),
(76, 26, 98, 113, '1', '2018-06-21 12:22:43', '2018-06-21 12:22:43'),
(77, 27, 112, 148, '1', '2018-06-21 12:29:11', '2018-06-21 12:29:11'),
(78, 27, 112, 149, '1', '2018-06-21 12:29:11', '2018-06-21 12:29:11'),
(79, 27, 101, 0, '1', '2018-06-21 12:37:02', '2018-06-21 12:37:02'),
(82, 28, 101, 0, '1', '2018-06-21 12:41:19', '2018-06-21 12:41:19');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `crd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fullName`, `email`, `password`, `crd`, `upd`) VALUES
(1, 'Admin', 'admin@clubz.co', '123456', '2016-07-22 14:21:00', '2018-02-08 06:43:42');

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `adId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `fee` float NOT NULL,
  `is_renew` tinyint(4) NOT NULL,
  `description` text NOT NULL,
  `club_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_role` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`adId`, `title`, `image`, `fee`, `is_renew`, `description`, `club_id`, `user_id`, `user_role`, `status`, `crd`, `upd`) VALUES
(1, 'test', 'f9d0d490a8980d398240996a8cb66c13.jpg', 0, 1, 'df', 84, 98, 'admin', 0, '2018-06-18 13:38:14', '2018-06-18 13:38:14'),
(2, 'test', 'b93a2d145690631532f585f7af48b17b.jpg', 12, 1, 'df', 84, 98, 'admin', 0, '2018-06-18 13:38:37', '2018-06-18 13:38:37');

-- --------------------------------------------------------

--
-- Table structure for table `ad_category`
--

CREATE TABLE `ad_category` (
  `adCategoryId` int(11) NOT NULL,
  `ad_category_name` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime DEFAULT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `clubs`
--

CREATE TABLE `clubs` (
  `clubId` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `club_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `club_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `club_image` varchar(255) NOT NULL,
  `club_icon` varchar(255) NOT NULL,
  `club_foundation_date` date NOT NULL,
  `club_email` varchar(255) NOT NULL,
  `club_contact_no` varchar(255) NOT NULL,
  `club_country_code` varchar(11) NOT NULL,
  `club_website` varchar(255) NOT NULL,
  `club_location` text NOT NULL,
  `club_address` text NOT NULL,
  `club_latitude` varchar(255) NOT NULL,
  `club_longitude` varchar(255) NOT NULL,
  `club_city` varchar(50) NOT NULL,
  `club_type` enum('1','2','3') NOT NULL COMMENT '1 is for public;2 is for private',
  `club_category_id` int(11) NOT NULL,
  `terms_conditions` text CHARACTER SET utf8mb4 NOT NULL,
  `comment_count` int(11) NOT NULL,
  `user_role` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clubs`
--

INSERT INTO `clubs` (`clubId`, `user_id`, `club_name`, `club_description`, `club_image`, `club_icon`, `club_foundation_date`, `club_email`, `club_contact_no`, `club_country_code`, `club_website`, `club_location`, `club_address`, `club_latitude`, `club_longitude`, `club_city`, `club_type`, `club_category_id`, `terms_conditions`, `comment_count`, `user_role`, `status`, `crd`, `upd`) VALUES
(86, 100, 'Clubz', 'it is default club for every user', '1e0ffbb3ef0e3e96562b2f6e562a36ce.png', '9b9380cd05d095363d4f328ae065704b.png', '2018-05-25', 'contact@clubz.co', '8796767767', '+91', 'www.clubz.co', 'indore', 'Mindiii System pvt ltd.', '22.705177799999994', '75.9091074', 'Indore', '3', 16, 'default club', 0, 'admin', '1', '2018-06-13 09:30:45', '2018-06-13 09:30:45'),
(87, 98, 'Lions Club', 'He he he', '6be2c1492ef595b87c70b63b59c00e5e.jpg', '', '2018-06-13', 'lionsclub@gmail.com', '8116174365', '+91', 'www.lionsclub.com', 'MINDIII Systems Pvt. Ltd.', 'indore', '22.705138200000004', '75.9090618', 'Indore', '1', 16, 'Terms and conditions are not sure why I asked 3', 0, 'Admin', '1', '2018-06-13 12:11:54', '2018-06-13 12:11:54'),
(88, 101, 'Club 9', 'Hi jdjdd hjav isvdhbf udjhdkwb yfuregr hrhrh jshshd uejjwhja jduhdjf jjshegd jjegejdj hdhshhd  hejdhfjjg ucjhfhdjids ijdhdg udjjfjfl hdhfhdjfh hehhg jjdjdj uidjsjskk idjdjhdbt hdhfhfbf hdhfhfh hfhhfhf', '368410e1497c8176c23716442b46b9ec.jpg', 'e7a0bf895ea435c38a295cac9514fcdc.jpg', '2018-06-14', 'myclub@gmail.com', '9407105020', '+91', 'www.myclub.com', 'Cloud 9', 'Bhopal', '23.2846673', '77.35619799999999', 'Indore', '1', 18, 'Ychrhr bfhfjCAis ehjdjdvve rhry4hr fbjrhe. Ehfhdhrhrhurj vidvgdjdkgsgegv. Bdjjdhdb.  Xhdhdhhfjfjhr hdjjfbhfhidkduhshjri ifjhdbhdoidgehjfiy. Fjfhhfndb fkhfjdpaisjr hfghrdbbjjfjfr ', 0, 'Club manager', '1', '2018-06-14 05:55:04', '2018-06-14 05:55:04'),
(89, 103, 'Club Z', 'Somos una organizaci', 'dbf81e532e7bf93d1c21254af290db17.jpg', '97ddcce0150502887fb79ec464b6f788.jpg', '2015-08-29', 'clubzlavega@gmail.com', '3016965786', '+57', 'clubz.co', 'Sabaneta', 'Cra. 38 # 75B Sur 115', '6.150559', '-75.61681999999999', 'Sabaneta', '1', 18, 'El Club Z ofrece una herramienta para el uso libre de comunidades, unidades residenciales, clubes deportivos, entre otros pero no tiene vinculaci', 0, 'Organizador', '1', '2018-06-16 17:34:02', '2018-06-16 17:34:02'),
(94, 98, 'Nn test', '57hh,jfjr jfjhr idjhehd f fudjt bjdjrjrkt\nUritit', '02b0b756aad21fa892fc57de25096b9d.jpg', '25fe77649a2101d326af396530825c8f.jpg', '2018-06-19', 'hxhdhdj@hdjd.com', '6564646646465', '+91', 'hdhfhf.bjfjf.com', 'Minneapolis', 'gdggdgdydhf hrhhfhfmi', '44.977753', '-93.2650108', 'Indore', '1', 17, 'Yfudjrjrjrjrhhdhdjjr', 0, 'Club manager', '1', '2018-06-19 14:14:12', '2018-06-19 14:14:12'),
(95, 98, 'Hxyry', 'Hdhdhdurrygdhfjfjrirk', 'fad37550c5d21ca0dfdacae70324e8ff.jpg', 'c797702ca66038b33c9a9bb639b145e4.jpg', '2018-06-19', 'vxhdh@gxhd.com', '6434346465656', '+91', 'hryufuf.bjd', 'MINDIII Systems Pvt. Ltd.', 'tdgeydy', '22.705138200000004', '75.9090618', 'Indore', '2', 19, 'Grhdhrutjj', 0, 'Club manager', '1', '2018-06-19 14:15:38', '2018-06-19 14:15:38'),
(97, 112, 'DjMaza', 'Hhdeb jdjjs hjs jjaja jjsjac kshhs ksb sjes. Is tkscs kvschdjshhdhdhdu jdjjej kshsks. Jsjsjsb jdjjdhdj husuegfdj hjsudgdo. Hshshskic hudhvks jjsuhsjev jjsdidv hdjdgdhjejevjj hsjjdjdjdggv hhehdjd ujdjd', '4941a83db704d5b91ddb36f6f5bfbed3.jpg', '877379f33d1bb10feaeace217b42acfc.jpg', '2018-06-21', 'dzmaza@gmail.com', '9407105020', '+91', 'www.djmaza.com', 'MINDIII Systems Pvt. Ltd.', 'Mindiii', '22.705138200000004', '75.9090618', 'Indore', '1', 17, 'Hdjdudu jdirjjfjdj jdisjjdhd jjdjdhhshslh jdjdvr jhdgdgrh hhshsjjs hjsjshsalidgdhf kshrjud bjdhdhhfh jjdjfjf jjejwjkonfv jjdhekeksbbd kndbdbdjf jkdjfjtjnd', 0, 'Club manager', '1', '2018-06-21 09:25:29', '2018-06-21 09:25:29'),
(98, 112, 'MSport', 'Djjdjrj jjduhsjn jdiidvshe jjshhehebe. Ehshhdbe jsjsjbr jdjdjejrk.dnjdjdhd bdhe. Jshhejrjhrjrkrkjsheh hehjejdhdhhfhfhrhdhdunbejjfro bjdjejjejr\nJrjjrjfj bdhfb', '1d7cb97d135e76f130587eb09376c6ee.jpg', 'd979f3972c30ca14c62f013ad8638307.jpg', '2018-06-21', 'support@msport.com', '9977141812', '+91', 'www.msport.com', 'Vijay Nagar', 'Vijay Nagar', '22.753284800000003', '75.8936962', 'Indore', '2', 16, 'Hdyryyr hduuejjww ehuejjege dhdjuehrbe bhsdjejhrjrju4u4', 0, 'Club manager', '1', '2018-06-21 09:31:12', '2018-06-21 09:31:12'),
(99, 111, 'Full house', 'Sghs eje eje jeneuwinw7wbb3 3 rj rur did did did did djd dj su2h2 3 3j3 3j3 7d in u3 4Nj3 3i3. 3j3 3j3 \n\n', '23dfe8857f7908e81ae82a9c2c201408.jpg', '', '2018-06-22', 'full@gmail.com', '9685784512', '+91', 'www.fullhouse.com', 'Bhopal', 'bhopal', '23.2599333', '77.412615', 'Indore', '2', 16, 'Demo he d in w wiwnwniw eje wjniw eje eosnsowwnisneien2iw. Is eje he wj9 2nw w ', 0, 'Club manager', '1', '2018-06-21 09:32:36', '2018-06-21 09:32:36');

-- --------------------------------------------------------

--
-- Table structure for table `club_category`
--

CREATE TABLE `club_category` (
  `clubCategoryId` int(11) NOT NULL,
  `club_category_name` varchar(250) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `club_category`
--

INSERT INTO `club_category` (`clubCategoryId`, `club_category_name`, `status`, `crd`, `upd`) VALUES
(16, 'Sports', '1', '2018-06-13 09:02:51', '2018-06-13 09:02:51'),
(17, 'Music', '1', '2018-06-13 09:03:00', '2018-06-13 09:03:00'),
(18, 'Social', '1', '2018-06-13 09:03:10', '2018-06-13 09:03:10'),
(19, 'Fashion', '1', '2018-06-13 09:03:21', '2018-06-13 09:03:21'),
(20, 'Financial', '1', '2018-06-13 09:03:43', '2018-06-13 09:03:43'),
(21, 'Residential', '1', '2018-06-13 09:04:00', '2018-06-13 09:04:00'),
(22, 'Dance', '1', '2018-06-13 09:04:45', '2018-06-13 09:04:45'),
(23, 'Electronics', '1', '2018-06-13 09:05:11', '2018-06-13 09:05:11'),
(24, 'Fun', '1', '2018-06-13 09:05:24', '2018-06-13 09:05:24'),
(25, 'Kids', '1', '2018-06-13 09:05:32', '2018-06-13 09:05:32');

-- --------------------------------------------------------

--
-- Table structure for table `club_user_mapping`
--

CREATE TABLE `club_user_mapping` (
  `clubUserId` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `club_user_status` enum('0','1') NOT NULL COMMENT '0 is for pending, 1 is for joined',
  `is_allow_feeds` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1 means yes, 0 means no',
  `member_status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1 means not silent, o means user silent',
  `user_nickname` varchar(255) NOT NULL,
  `is_favorite` tinyint(4) NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `club_user_mapping`
--

INSERT INTO `club_user_mapping` (`clubUserId`, `club_id`, `user_id`, `club_user_status`, `is_allow_feeds`, `member_status`, `user_nickname`, `is_favorite`, `status`, `crd`, `upd`) VALUES
(128, 86, 101, '1', '1', '1', '', 0, '1', '2018-06-13 13:00:07', '2018-06-13 13:00:07'),
(135, 86, 102, '1', '1', '1', '', 0, '1', '2018-06-16 12:46:42', '2018-06-16 12:46:42'),
(136, 86, 103, '1', '1', '1', '', 0, '1', '2018-06-16 16:44:09', '2018-06-16 16:44:09'),
(137, 86, 104, '1', '1', '1', '', 0, '1', '2018-06-16 17:35:33', '2018-06-16 17:35:33'),
(138, 89, 104, '1', '1', '0', '', 0, '1', '2018-06-16 17:36:08', '2018-06-16 17:38:04'),
(153, 86, 109, '1', '1', '1', '', 0, '1', '2018-06-19 10:54:13', '2018-06-19 10:54:13'),
(170, 88, 98, '1', '1', '1', '', 0, '1', '2018-06-19 14:17:43', '2018-06-19 14:17:43'),
(179, 86, 111, '1', '1', '1', '', 0, '1', '2018-06-21 09:19:42', '2018-06-21 09:19:42'),
(180, 86, 112, '1', '1', '1', '', 0, '1', '2018-06-21 09:20:59', '2018-06-21 09:20:59'),
(181, 97, 111, '1', '1', '1', '', 0, '1', '2018-06-21 09:29:36', '2018-06-21 12:43:07'),
(184, 98, 111, '1', '1', '1', 'Pankaj Sir', 0, '1', '2018-06-21 09:39:54', '2018-06-21 09:49:36'),
(186, 87, 112, '1', '1', '1', '', 0, '1', '2018-06-21 12:09:26', '2018-06-21 12:09:26'),
(187, 97, 98, '1', '1', '1', '', 0, '1', '2018-06-21 12:10:15', '2018-06-21 12:10:15'),
(190, 97, 101, '1', '1', '1', '', 0, '1', '2018-06-21 12:40:32', '2018-06-21 12:40:32'),
(191, 95, 112, '0', '1', '1', '', 0, '1', '2018-06-21 12:42:27', '2018-06-21 12:42:27'),
(193, 99, 112, '0', '1', '1', '', 0, '1', '2018-06-21 12:43:09', '2018-06-21 12:43:09'),
(195, 88, 112, '1', '1', '1', '', 0, '1', '2018-06-21 12:43:28', '2018-06-21 12:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `emoj`
--

CREATE TABLE `emoj` (
  `emoId` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `favorite_ads`
--

CREATE TABLE `favorite_ads` (
  `favoriteAdId` int(11) NOT NULL,
  `ad_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `crd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `interests`
--

CREATE TABLE `interests` (
  `interestId` int(11) NOT NULL,
  `interest_name` varchar(250) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `interests`
--

INSERT INTO `interests` (`interestId`, `interest_name`, `status`, `crd`, `upd`) VALUES
(2, 'drawing', '1', '2018-06-15 08:00:00', '2018-06-15 00:40:00'),
(3, 'cooking', '1', '2018-06-15 10:00:00', '2018-06-15 00:00:26'),
(4, 'travelling', '1', '2018-06-15 10:00:00', '2018-06-15 00:00:26'),
(5, 'news', '1', '2018-06-19 06:37:03', '2018-06-19 06:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `news_feeds`
--

CREATE TABLE `news_feeds` (
  `newsFeedId` int(11) NOT NULL,
  `news_feed_title` varchar(250) CHARACTER SET utf8mb4 NOT NULL,
  `news_feed_description` text CHARACTER SET utf8mb4 NOT NULL,
  `news_feed_attachment` varchar(255) NOT NULL,
  `club_id` int(11) NOT NULL,
  `is_comment_allow` enum('0','1') NOT NULL DEFAULT '1' COMMENT '1 means allow, 0 means not allow',
  `comment_count` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `news_feeds`
--

INSERT INTO `news_feeds` (`newsFeedId`, `news_feed_title`, `news_feed_description`, `news_feed_attachment`, `club_id`, `is_comment_allow`, `comment_count`, `status`, `crd`, `upd`) VALUES
(17, 'Test', 'Grhddhsuhruiririevhdieei idiiejew xbhdhdhdhhshshhshrjfhvvsjshhs jjsgdhdjdirr', '4e61851a0b93010272bd2465208c8dd3.jpg', 88, '1', 0, '1', '2018-06-14 12:45:04', '2018-06-15 08:20:32'),
(22, 'Ranji Trophy', 'Hdhfhf udjjdjr jjdirjjt jjajwb jkssbsjdjjfr bdudjekjevsjr jjsuejefrjece hdhgrjeg4 huehheirhrjehr jisiejkw jsjeh', '69a7bdc54d564db06008f0a7df856c8d.jpg', 98, '1', 0, '1', '2018-06-21 09:34:25', '2018-06-21 09:34:25'),
(23, 'DzMusic champiyan', 'Bxbdhfhd jjdjdjje juudjrh hjdijejejev. ', 'ea6650b2d80ba6038a16fe60e71a3708.jpg', 97, '1', 0, '1', '2018-06-21 09:37:17', '2018-06-21 09:37:17'),
(24, 'Here is new tournament ', 'Heren is msjd djd is djd dbdbd dbdbd dbdbd djd dbdbd dbdbd dbd jd dbdbd bfbfbbdvvd dbdbdbdbdbdbdbbdbdbdbdhdbdbdbdbdbdbbdbddbdbbdbdbdbdbbdbdbbdbdbdbdbdbdbbdbdbdvdd ddrg ff f dbd dbd f f. F', '4b669e9edea2493a8362003f9338f09d.jpg', 99, '1', 0, '1', '2018-06-21 09:44:26', '2018-06-21 09:48:28');

-- --------------------------------------------------------

--
-- Table structure for table `news_feeds_bookmarks`
--

CREATE TABLE `news_feeds_bookmarks` (
  `newsFeedsBookmarkId` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `news_feed_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news_feeds_comments`
--

CREATE TABLE `news_feeds_comments` (
  `newsFeedsCommentId` int(11) NOT NULL,
  `news_feed_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news_feeds_likes`
--

CREATE TABLE `news_feeds_likes` (
  `newsFeedsLikeId` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `news_feed_id` int(11) NOT NULL,
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `news_feeds_likes`
--

INSERT INTO `news_feeds_likes` (`newsFeedsLikeId`, `user_id`, `news_feed_id`, `crd`, `upd`) VALUES
(93, 101, 17, '2018-06-14 13:12:04', '2018-06-14 13:12:04'),
(99, 98, 17, '2018-06-20 04:47:50', '2018-06-20 04:47:50'),
(101, 111, 22, '2018-06-21 09:35:38', '2018-06-21 09:35:38'),
(102, 111, 23, '2018-06-21 09:39:27', '2018-06-21 09:39:27'),
(103, 112, 24, '2018-06-21 09:48:39', '2018-06-21 09:48:39'),
(104, 111, 24, '2018-06-21 09:48:44', '2018-06-21 09:48:44'),
(106, 112, 22, '2018-06-21 10:04:30', '2018-06-21 10:04:30'),
(107, 112, 23, '2018-06-21 12:41:55', '2018-06-21 12:41:55');

-- --------------------------------------------------------

--
-- Table structure for table `news_feed_filter_tags`
--

CREATE TABLE `news_feed_filter_tags` (
  `feedFilterTagId` int(11) NOT NULL,
  `feed_filter_tag_name` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `news_feed_filter_tags`
--

INSERT INTO `news_feed_filter_tags` (`feedFilterTagId`, `feed_filter_tag_name`, `status`, `crd`, `upd`) VALUES
(17, 'Foodball', '1', '2018-06-13 09:38:31', '2018-06-13 09:38:31'),
(18, 'Mindiii', '1', '2018-06-13 09:38:31', '2018-06-13 09:38:31'),
(19, 'Match', '1', '2018-06-13 09:38:31', '2018-06-13 09:38:31'),
(20, 'Sports', '1', '2018-06-13 11:19:41', '2018-06-13 11:19:41'),
(21, 'Test', '1', '2018-06-14 12:45:04', '2018-06-14 12:45:04'),
(22, 'Xvjhfbh', '1', '2018-06-14 14:11:51', '2018-06-14 14:11:51'),
(23, 'Fyyryeyeye', '1', '2018-06-14 14:15:53', '2018-06-14 14:15:53'),
(24, 'Ydyryrry', '1', '2018-06-14 14:15:53', '2018-06-14 14:15:53'),
(25, '4yyryruru4ruuei', '1', '2018-06-14 14:15:53', '2018-06-14 14:15:53'),
(26, ' Jay', '1', '2018-06-15 06:29:07', '2018-06-15 06:29:07'),
(27, ' Shree', '1', '2018-06-15 06:29:07', '2018-06-15 06:29:07'),
(28, ' Jay Shree', '1', '2018-06-15 06:29:50', '2018-06-15 06:29:50'),
(29, ' Ram', '1', '2018-06-15 06:29:50', '2018-06-15 06:29:50'),
(30, ' Sports', '1', '2018-06-15 08:20:32', '2018-06-15 08:20:32'),
(31, ' Mindiiitechno', '1', '2018-06-15 08:20:32', '2018-06-15 08:20:32'),
(32, ' Indore', '1', '2018-06-16 06:56:06', '2018-06-16 06:56:06'),
(33, ' Jhabua', '1', '2018-06-16 06:56:06', '2018-06-16 06:56:06'),
(34, 'Beauty', '1', '2018-06-18 08:57:24', '2018-06-18 08:57:24'),
(35, ' Beautiful', '1', '2018-06-18 08:57:24', '2018-06-18 08:57:24'),
(36, 'Football', '1', '2018-06-19 05:32:29', '2018-06-19 05:32:29'),
(37, ' New Game', '1', '2018-06-19 05:32:29', '2018-06-19 05:32:29'),
(38, ' Okau', '1', '2018-06-19 05:32:29', '2018-06-19 05:32:29'),
(39, ' Hello', '1', '2018-06-19 05:39:23', '2018-06-19 05:39:23'),
(40, 'Google', '1', '2018-06-19 13:54:06', '2018-06-19 13:54:06'),
(41, ' Mindiii', '1', '2018-06-19 13:54:06', '2018-06-19 13:54:06'),
(42, ' Vijaynagar', '1', '2018-06-21 09:34:25', '2018-06-21 09:34:25'),
(43, ' Ranji', '1', '2018-06-21 09:34:25', '2018-06-21 09:34:25'),
(44, 'Music', '1', '2018-06-21 09:37:17', '2018-06-21 09:37:17'),
(45, ' Dj', '1', '2018-06-21 09:37:17', '2018-06-21 09:37:17'),
(46, 'Footballs', '1', '2018-06-21 09:44:26', '2018-06-21 09:44:26'),
(47, ' Goa', '1', '2018-06-21 09:48:29', '2018-06-21 09:48:29');

-- --------------------------------------------------------

--
-- Table structure for table `news_feed_filter_tags_mapping`
--

CREATE TABLE `news_feed_filter_tags_mapping` (
  `feedFilterTagMappingId` int(11) NOT NULL,
  `feed_filter_tag_id` int(11) NOT NULL,
  `news_feed_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `news_feed_filter_tags_mapping`
--

INSERT INTO `news_feed_filter_tags_mapping` (`feedFilterTagMappingId`, `feed_filter_tag_id`, `news_feed_id`, `status`, `crd`, `upd`) VALUES
(49, 18, 17, '1', '2018-06-15 08:20:32', '2018-06-15 08:20:32'),
(50, 30, 17, '1', '2018-06-15 08:20:32', '2018-06-15 08:20:32'),
(51, 26, 17, '1', '2018-06-15 08:20:32', '2018-06-15 08:20:32'),
(52, 31, 17, '1', '2018-06-15 08:20:32', '2018-06-15 08:20:32'),
(67, 19, 22, '1', '2018-06-21 09:34:25', '2018-06-21 09:34:25'),
(68, 42, 22, '1', '2018-06-21 09:34:25', '2018-06-21 09:34:25'),
(69, 43, 22, '1', '2018-06-21 09:34:25', '2018-06-21 09:34:25'),
(70, 44, 23, '1', '2018-06-21 09:37:17', '2018-06-21 09:37:17'),
(71, 45, 23, '1', '2018-06-21 09:37:17', '2018-06-21 09:37:17'),
(73, 46, 24, '1', '2018-06-21 09:48:28', '2018-06-21 09:48:28'),
(74, 47, 24, '1', '2018-06-21 09:48:29', '2018-06-21 09:48:29');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skillId` int(11) NOT NULL,
  `skill_name` varchar(255) NOT NULL,
  `status` enum('0','1') DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skillId`, `skill_name`, `status`, `crd`, `upd`) VALUES
(3, 'dance', '1', '2018-06-15 07:00:00', '2018-06-15 08:00:00'),
(4, 'music', '1', '2018-06-15 08:00:00', '2018-06-15 00:25:00'),
(5, 'sports', '1', '2018-06-15 08:00:00', '2018-06-15 00:25:00'),
(6, 'coding', '1', '2018-06-15 08:00:00', '2018-06-15 00:25:00'),
(7, 'testing', '1', '2018-06-19 06:37:03', '2018-06-19 06:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `full_name` varchar(500) NOT NULL,
  `email` varchar(255) NOT NULL,
  `social_id` varchar(100) NOT NULL,
  `social_type` varchar(100) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `notification_status` enum('0','1') NOT NULL DEFAULT '1',
  `device_type` tinyint(1) NOT NULL COMMENT '1:iphone ,2:android',
  `auth_token` varchar(255) NOT NULL,
  `device_token` text NOT NULL,
  `contact_no` varchar(50) NOT NULL,
  `country_code` varchar(20) NOT NULL,
  `is_profile_url` tinyint(4) NOT NULL DEFAULT '0',
  `about_me` text NOT NULL,
  `dob` date NOT NULL,
  `about_me_visibility` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'o is for hidden, 1 is for public, 2 is for club members, 3 is for contacts',
  `dob_visibility` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'o is for hidden, 1 is for public, 2 is for club members, 3 is for contacts',
  `contact_no_visibility` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'o is for hidden, 1 is for public, 2 is for club members, 3 is for contacts',
  `email_visibility` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'o is for hidden, 1 is for public, 2 is for club members, 3 is for contacts',
  `affiliates_visibility` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'o is for hidden, 1 is for public, 2 is for club members, 3 is for contacts',
  `skills_visibility` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'o is for hidden, 1 is for public, 2 is for club members, 3 is for contacts',
  `interest_visibility` tinyint(4) DEFAULT '1' COMMENT 'o is for hidden, 1 is for public, 2 is for club members, 3 is for contacts',
  `news_notifications` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 means on, 0 means off',
  `activities_notifications` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 means on, 0 means off',
  `chat_notifications` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 means on, 0 means off',
  `ads_notifications` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 means on, 0 means off',
  `show_profile` tinyint(4) DEFAULT '1' COMMENT '1 for show,0 for not show',
  `allow_anyone` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 means allow, 0 means not allow',
  `sync_wifi` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 means yes, 0 means no',
  `language` varchar(50) NOT NULL DEFAULT 'en',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `full_name`, `email`, `social_id`, `social_type`, `profile_image`, `address`, `latitude`, `longitude`, `city`, `notification_status`, `device_type`, `auth_token`, `device_token`, `contact_no`, `country_code`, `is_profile_url`, `about_me`, `dob`, `about_me_visibility`, `dob_visibility`, `contact_no_visibility`, `email_visibility`, `affiliates_visibility`, `skills_visibility`, `interest_visibility`, `news_notifications`, `activities_notifications`, `chat_notifications`, `ads_notifications`, `show_profile`, `allow_anyone`, `sync_wifi`, `language`, `status`, `crd`, `upd`) VALUES
(98, 'Chiranjib Ganguly', 'chiranjibg91@gmail.com', '', '', '', '', '22.7051492', '75.9091626', 'Indore', '1', 0, 'f750ac47c4e81df5b78a8da15d771a4afb8c11ea', '', '8116174365', '+91', 0, '', '0000-00-00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'en', '1', '2018-06-13 05:43:18', '2018-06-13 12:43:18'),
(100, 'admin', 'admin@gmail.com', '', '', '', '', '', '', '', '1', 2, 'ad753f69bdaa4c757a2bb23b73c50d5c67f1d35d', '1234', '8982923563', '+91', 0, '', '0000-00-00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'en', '1', '2018-06-13 08:57:38', '2018-06-13 15:57:38'),
(101, 'Alka Singh', 'testingalka30@gmail.com', '179719346208877', 'facebook', 'https://graph.facebook.com/179719346208877/picture?type=large', '', '22.704922', '75.9091089', 'Indore', '1', 76, '76a88b1728eb71777d853d4b5d89471e53d9fa73', '', '9407105020', '+91', 1, 'Hello i am alka here', '1994-10-17', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'en', '1', '2018-06-13 13:00:07', '2018-06-13 20:00:07'),
(102, 'Yulieth Betancur', 'yuliethbetancur@gmail.com', '', '', '', '', '', '', '', '1', 2, '78ea7668293cf3560b9f3430e908accc0ea1cc27', 'fid2C3Qckiw:APA91bHGTZmI85Qj8tPMS83zKevj0pczqwfD7R0A76SrNErAq6bkBrLw7kEBcCHO6H0fJYWIY7uSHtj0tH2zpcMQTCKLXTVl_oYyf1IgnA6JQ2gLMx6EqTCmChWX_V2cz0C2uaeq5w2C', '3006734794', '+57', 0, '', '0000-00-00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'en', '1', '2018-06-16 12:46:42', '2018-06-16 19:46:42'),
(103, 'Club Z', 'clubzlavega@gmail.com', '', '', '', '', '6.1434968', '-75.6158986', 'Sabaneta', '1', 2, 'dfdb2ff14aa302b82dede767abf668f6c2012386', 'dowgQUBP5jM:APA91bFXd16VSyEiqMzUut58_yuFYpQH4dxIppU4_c7VJ26-934lvDORZ0JKxlldYTfAVw6VfUIjzADuoFM_zWWh_EXW_4se_mecGcdhIkUlIr_N8-XVKXCVLV9KNSTC4C91CoeKu1ad', '3016965786', '+57', 0, '', '0000-00-00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'en', '1', '2018-06-16 16:44:09', '2018-06-16 23:44:09'),
(104, 'Jonathan Largo', 'jonathanlargo@hotmail.com', '', '', '', '', '6.1434985', '-75.6171496', 'Sabaneta', '1', 2, '6fd56abdfc227d65b6528abd324e01ba132ba535', 'ck8xzz0e5xM:APA91bFnuoflZ6Q7iiKlzXMkCgFUyB2RTtc5iOMVzfZJ6qmINgrtaqecN-XODW3PYw2SXgYXwVHgrBI3OEJo1GEnzW_fosjgw-jpduVLb4FQkgMhgsLIJcUDw6WMRv_Y_Hx9DpV8M2bD', '3053356623', '+57', 0, '', '0000-00-00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'en', '1', '2018-06-16 17:35:33', '2018-06-17 00:35:33'),
(109, 'Rk', 'hxhdhhf@gm.com', '', '', 'ff077b487abbdd1029daa67b53c3a32e.jpg', '', '22.7051853', '75.9090563', 'Indore', '1', 2, 'ba1da058801f649ee9eb1d2eb232536d2b5fc488', 'dS1wuenZY1M:APA91bFs8K5M7ibg8lKUIVmabkLQAru2OEldbv7rol_xzbjPJAVbNCqMl3Ar681Adj-KuzoAaOzXgeHWK8EED7nJdNsI5zGzNnSXdyWRHyG4rKGinFNpI0_xbT-AdycCk9fnMs0xcrS7', '9993337771', '+91', 0, '', '0000-00-00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'en', '1', '2018-06-19 10:54:13', '2018-06-19 17:54:13'),
(111, 'Pankaj ', 'pankaj.mindiii@gmail.com', '', '', '0ec65816942ad238a1eae6d0a92d340e.jpg', '', '22.7048826', '75.9090671', 'Indore', '1', 2, '1e21b0f3484807acebe579130ee90be666010796', 'cOX1hsP-XTY:APA91bEZCgWwVsoHZd4Np8wNWiRKlNFBYn5anGEeGNdqe37eczfQQOztmQVJ1C7X4ECquMiSw54Zw-jAAIeYyJv-dV_WNu5ORQzyAEsBNzyZkLkdagQXtDXf-fHWqo8-bF6qba5GxH1Tl7z78l_d9BaVcmXYfNMCXQ', '9630612281', '+91', 0, '', '0000-00-00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'en', '1', '2018-06-21 09:19:42', '2018-06-21 16:19:42'),
(112, 'Dharmraj', 'dharmraj.mindiii@gmail.com', '', '', '', '', '22.7051492', '75.9091626', 'Indore', '1', 0, 'fd16b8f24ec6b771b0a315b8dd6710fae5486051', '', '9977141811', '+91', 0, '', '0000-00-00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'en', '1', '2018-06-21 09:20:59', '2018-06-21 16:20:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_affiliates`
--

CREATE TABLE `user_affiliates` (
  `userAffiliateId` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `affiliate_name` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_affiliates`
--

INSERT INTO `user_affiliates` (`userAffiliateId`, `user_id`, `affiliate_name`, `status`, `crd`, `upd`) VALUES
(113, 98, 'Aish', '1', '2018-06-13 05:43:56', '2018-06-13 05:43:56'),
(114, 98, ' Dharam', '1', '2018-06-13 05:43:56', '2018-06-13 05:43:56'),
(123, 102, 'Julián Betancur', '1', '2018-06-16 12:46:56', '2018-06-16 12:46:56'),
(124, 103, 'Bianey Largo', '1', '2018-06-16 16:44:25', '2018-06-16 16:44:25'),
(125, 104, 'Yulieth Betancur', '1', '2018-06-16 17:35:42', '2018-06-16 17:35:42'),
(133, 101, 'neha', '1', '2018-06-19 06:36:42', '2018-06-19 06:36:42'),
(134, 101, 'pranjal', '1', '2018-06-19 06:36:42', '2018-06-19 06:36:42'),
(135, 101, 'test', '1', '2018-06-19 06:36:42', '2018-06-19 06:36:42'),
(136, 109, 'Marry', '1', '2018-06-19 10:54:25', '2018-06-19 10:54:25'),
(137, 109, ' Jfjjdjdkd', '1', '2018-06-19 10:54:25', '2018-06-19 10:54:25'),
(142, 111, 'Anil', '1', '2018-06-21 09:20:14', '2018-06-21 09:20:14'),
(143, 111, ' Sunil', '1', '2018-06-21 09:20:14', '2018-06-21 09:20:14'),
(144, 111, ' Nupur', '1', '2018-06-21 09:20:14', '2018-06-21 09:20:14'),
(145, 111, ' Dharm', '1', '2018-06-21 09:20:14', '2018-06-21 09:20:14'),
(146, 112, 'Lekhnatha', '1', '2018-06-21 09:21:39', '2018-06-21 09:21:39'),
(147, 112, ' Parvati', '1', '2018-06-21 09:21:39', '2018-06-21 09:21:39'),
(148, 112, ' Laxmi', '1', '2018-06-21 09:21:39', '2018-06-21 09:21:39'),
(149, 112, ' Khemchandra', '1', '2018-06-21 09:21:39', '2018-06-21 09:21:39'),
(150, 112, ' Deena', '1', '2018-06-21 09:21:39', '2018-06-21 09:21:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_interests`
--

CREATE TABLE `user_interests` (
  `userInterestId` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `interest_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_interests`
--

INSERT INTO `user_interests` (`userInterestId`, `user_id`, `interest_id`, `status`, `crd`, `upd`) VALUES
(4, 101, 5, '1', '2018-06-19 06:37:03', '2018-06-19 06:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `user_skills`
--

CREATE TABLE `user_skills` (
  `userSkillId` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_skills`
--

INSERT INTO `user_skills` (`userSkillId`, `skill_id`, `user_id`, `status`, `crd`, `upd`) VALUES
(5, 7, 101, '1', '2018-06-19 06:37:03', '2018-06-19 06:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `user_tags`
--

CREATE TABLE `user_tags` (
  `userTagId` int(11) NOT NULL,
  `club_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tag_name` varchar(250) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `crd` datetime NOT NULL,
  `upd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_tags`
--

INSERT INTO `user_tags` (`userTagId`, `club_user_id`, `user_id`, `tag_name`, `status`, `crd`, `upd`) VALUES
(17, 138, 104, 'Propietario', '1', '2018-06-16 17:39:18', '2018-06-16 17:39:18'),
(18, 138, 104, 'Mascotas', '1', '2018-06-16 18:07:07', '2018-06-16 18:07:07'),
(19, 138, 104, 'Niños', '1', '2018-06-16 18:07:12', '2018-06-16 18:07:12'),
(26, 184, 111, 'MusicMj', '1', '2018-06-21 09:41:21', '2018-06-21 09:41:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activityId`),
  ADD KEY `creator_id` (`creator_id`),
  ADD KEY `leader_id` (`leader_id`),
  ADD KEY `club_id` (`club_id`);

--
-- Indexes for table `activity_confirm`
--
ALTER TABLE `activity_confirm`
  ADD PRIMARY KEY (`activityConfirmId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `activity_event_id` (`activity_event_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `activity_events`
--
ALTER TABLE `activity_events`
  ADD PRIMARY KEY (`activityEventId`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `activity_join`
--
ALTER TABLE `activity_join`
  ADD PRIMARY KEY (`activityJoinId`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`adId`);

--
-- Indexes for table `ad_category`
--
ALTER TABLE `ad_category`
  ADD PRIMARY KEY (`adCategoryId`);

--
-- Indexes for table `clubs`
--
ALTER TABLE `clubs`
  ADD PRIMARY KEY (`clubId`),
  ADD KEY `club_category_id` (`club_category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `club_category`
--
ALTER TABLE `club_category`
  ADD PRIMARY KEY (`clubCategoryId`);

--
-- Indexes for table `club_user_mapping`
--
ALTER TABLE `club_user_mapping`
  ADD PRIMARY KEY (`clubUserId`),
  ADD KEY `club_id` (`club_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `emoj`
--
ALTER TABLE `emoj`
  ADD PRIMARY KEY (`emoId`);

--
-- Indexes for table `interests`
--
ALTER TABLE `interests`
  ADD PRIMARY KEY (`interestId`);

--
-- Indexes for table `news_feeds`
--
ALTER TABLE `news_feeds`
  ADD PRIMARY KEY (`newsFeedId`),
  ADD KEY `club_id` (`club_id`) USING BTREE;

--
-- Indexes for table `news_feeds_bookmarks`
--
ALTER TABLE `news_feeds_bookmarks`
  ADD PRIMARY KEY (`newsFeedsBookmarkId`),
  ADD KEY `news_feed_id` (`news_feed_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `news_feeds_comments`
--
ALTER TABLE `news_feeds_comments`
  ADD PRIMARY KEY (`newsFeedsCommentId`),
  ADD KEY `news_feed_id` (`news_feed_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `news_feeds_likes`
--
ALTER TABLE `news_feeds_likes`
  ADD PRIMARY KEY (`newsFeedsLikeId`),
  ADD KEY `news_feed_id` (`news_feed_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `news_feed_filter_tags`
--
ALTER TABLE `news_feed_filter_tags`
  ADD PRIMARY KEY (`feedFilterTagId`);

--
-- Indexes for table `news_feed_filter_tags_mapping`
--
ALTER TABLE `news_feed_filter_tags_mapping`
  ADD PRIMARY KEY (`feedFilterTagMappingId`),
  ADD KEY `news_feed_id` (`news_feed_id`),
  ADD KEY `feed_filter_tag_id` (`feed_filter_tag_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skillId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `user_affiliates`
--
ALTER TABLE `user_affiliates`
  ADD PRIMARY KEY (`userAffiliateId`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_interests`
--
ALTER TABLE `user_interests`
  ADD PRIMARY KEY (`userInterestId`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `interest_id` (`interest_id`);

--
-- Indexes for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD PRIMARY KEY (`userSkillId`),
  ADD KEY `skill_id` (`skill_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_tags`
--
ALTER TABLE `user_tags`
  ADD PRIMARY KEY (`userTagId`),
  ADD KEY `club_user_id` (`club_user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activityId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `activity_confirm`
--
ALTER TABLE `activity_confirm`
  MODIFY `activityConfirmId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `activity_events`
--
ALTER TABLE `activity_events`
  MODIFY `activityEventId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `activity_join`
--
ALTER TABLE `activity_join`
  MODIFY `activityJoinId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `adId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ad_category`
--
ALTER TABLE `ad_category`
  MODIFY `adCategoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `clubs`
--
ALTER TABLE `clubs`
  MODIFY `clubId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `club_category`
--
ALTER TABLE `club_category`
  MODIFY `clubCategoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `club_user_mapping`
--
ALTER TABLE `club_user_mapping`
  MODIFY `clubUserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `emoj`
--
ALTER TABLE `emoj`
  MODIFY `emoId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `interests`
--
ALTER TABLE `interests`
  MODIFY `interestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `news_feeds`
--
ALTER TABLE `news_feeds`
  MODIFY `newsFeedId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `news_feeds_bookmarks`
--
ALTER TABLE `news_feeds_bookmarks`
  MODIFY `newsFeedsBookmarkId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_feeds_comments`
--
ALTER TABLE `news_feeds_comments`
  MODIFY `newsFeedsCommentId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_feeds_likes`
--
ALTER TABLE `news_feeds_likes`
  MODIFY `newsFeedsLikeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `news_feed_filter_tags`
--
ALTER TABLE `news_feed_filter_tags`
  MODIFY `feedFilterTagId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `news_feed_filter_tags_mapping`
--
ALTER TABLE `news_feed_filter_tags_mapping`
  MODIFY `feedFilterTagMappingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skillId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `user_affiliates`
--
ALTER TABLE `user_affiliates`
  MODIFY `userAffiliateId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `user_interests`
--
ALTER TABLE `user_interests`
  MODIFY `userInterestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_skills`
--
ALTER TABLE `user_skills`
  MODIFY `userSkillId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_tags`
--
ALTER TABLE `user_tags`
  MODIFY `userTagId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`creator_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_ibfk_2` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`clubId`) ON DELETE CASCADE;

--
-- Constraints for table `activity_confirm`
--
ALTER TABLE `activity_confirm`
  ADD CONSTRAINT `activity_confirm_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_confirm_ibfk_2` FOREIGN KEY (`activity_event_id`) REFERENCES `activity_events` (`activityEventId`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_confirm_ibfk_3` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activityId`) ON DELETE CASCADE;

--
-- Constraints for table `activity_events`
--
ALTER TABLE `activity_events`
  ADD CONSTRAINT `activity_events_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activityId`) ON DELETE CASCADE;

--
-- Constraints for table `activity_join`
--
ALTER TABLE `activity_join`
  ADD CONSTRAINT `activity_join_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activityId`) ON DELETE CASCADE,
  ADD CONSTRAINT `activity_join_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `clubs`
--
ALTER TABLE `clubs`
  ADD CONSTRAINT `clubs_ibfk_1` FOREIGN KEY (`club_category_id`) REFERENCES `club_category` (`clubCategoryId`) ON DELETE CASCADE,
  ADD CONSTRAINT `clubs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `club_user_mapping`
--
ALTER TABLE `club_user_mapping`
  ADD CONSTRAINT `club_user_mapping_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`clubId`) ON DELETE CASCADE,
  ADD CONSTRAINT `club_user_mapping_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `news_feeds`
--
ALTER TABLE `news_feeds`
  ADD CONSTRAINT `news_feeds_ibfk_1` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`clubId`) ON DELETE CASCADE;

--
-- Constraints for table `news_feeds_comments`
--
ALTER TABLE `news_feeds_comments`
  ADD CONSTRAINT `news_feeds_comments_ibfk_1` FOREIGN KEY (`news_feed_id`) REFERENCES `news_feeds` (`newsFeedId`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_feeds_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `news_feeds_likes`
--
ALTER TABLE `news_feeds_likes`
  ADD CONSTRAINT `news_feeds_likes_ibfk_1` FOREIGN KEY (`news_feed_id`) REFERENCES `news_feeds` (`newsFeedId`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_feeds_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `news_feed_filter_tags_mapping`
--
ALTER TABLE `news_feed_filter_tags_mapping`
  ADD CONSTRAINT `news_feed_filter_tags_mapping_ibfk_1` FOREIGN KEY (`news_feed_id`) REFERENCES `news_feeds` (`newsFeedId`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_feed_filter_tags_mapping_ibfk_2` FOREIGN KEY (`feed_filter_tag_id`) REFERENCES `news_feed_filter_tags` (`feedFilterTagId`) ON DELETE CASCADE;

--
-- Constraints for table `user_affiliates`
--
ALTER TABLE `user_affiliates`
  ADD CONSTRAINT `user_affiliates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `user_interests`
--
ALTER TABLE `user_interests`
  ADD CONSTRAINT `user_interests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_interests_ibfk_2` FOREIGN KEY (`interest_id`) REFERENCES `interests` (`interestId`) ON DELETE CASCADE;

--
-- Constraints for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD CONSTRAINT `user_skills_ibfk_1` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skillId`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_skills_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE;

--
-- Constraints for table `user_tags`
--
ALTER TABLE `user_tags`
  ADD CONSTRAINT `user_tags_ibfk_1` FOREIGN KEY (`club_user_id`) REFERENCES `club_user_mapping` (`clubUserId`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_tags_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
