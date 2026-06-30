-- PostQ - Post-Quantum Secure Messenger

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Table structure for table `friendrequests`
--
DROP TABLE IF EXISTS `friendrequests`;
CREATE TABLE `friendrequests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `useridTO` int(11) NOT NULL,
  `useridFROM` int(11) NOT NULL,
  `usernameFROM` varchar(80) COLLATE latin2_hungarian_ci NOT NULL,
  `rejected` bit NOT NULL DEFAULT 0,
  `symkey` text COLLATE latin2_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_hungarian_ci;

--
-- Table structure for table `messages`
--
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `messages` text COLLATE latin2_hungarian_ci NOT NULL,
  `nonce` binary(16) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_hungarian_ci;

--
-- Table structure for table `symkeyrequests`
--
DROP TABLE IF EXISTS `symkeyrequests`;
CREATE TABLE `symkeyrequests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `useridTO` int(11) NOT NULL,
  `useridFROM` int(11) NOT NULL,
  `usernameFROM` varchar(80) COLLATE latin2_hungarian_ci NOT NULL,
  `symkey` text COLLATE latin2_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_hungarian_ci;

--
-- Table structure for table `symkeys`
--
DROP TABLE IF EXISTS `symkeys`;
CREATE TABLE `symkeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `symkey` text COLLATE latin2_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_hungarian_ci;

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text COLLATE latin2_hungarian_ci NOT NULL,
  `password` varchar(65) COLLATE latin2_hungarian_ci NOT NULL,
  `privatekey` text COLLATE latin2_hungarian_ci NOT NULL,
  `publickey` text COLLATE latin2_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_hungarian_ci;

--
-- Table structure for table `unverified_users`
--
DROP TABLE IF EXISTS `unverified_users`;
CREATE TABLE `unverified_users` (
  `username` varchar(80) COLLATE latin2_hungarian_ci NOT NULL,
  `password` varchar(65) COLLATE latin2_hungarian_ci NOT NULL,
  `privatekey` text COLLATE latin2_hungarian_ci NOT NULL,
  `publickey` text COLLATE latin2_hungarian_ci NOT NULL,
  `registrationcode` varchar(65) COLLATE latin2_hungarian_ci NOT NULL,
  `expires` timestamp NOT NULL,
  PRIMARY KEY (`registrationcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_hungarian_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
