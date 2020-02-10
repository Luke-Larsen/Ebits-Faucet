SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `AutoClaims` (
  `UserID` int(11) NOT NULL,
  `Time` int(11) NOT NULL,
  `Amount` decimal(9,8) NOT NULL,
  `Ip` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `FaucetClaims` (
  `UserID` int(11) NOT NULL,
  `Time` int(11) NOT NULL,
  `Amount` decimal(9,8) NOT NULL,
  `Ip` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `ipban` (
  `UserID` int(11) NOT NULL,
  `ip` int(11) NOT NULL,
  `Time` int(11) NOT NULL,
  `Reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Offers` (
  `Time` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Amount` int(11) NOT NULL,
  `Offerwall` varchar(255) NOT NULL,
  `Sig` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `ShortLinkClaims` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ShortlinkId` int(11) NOT NULL,
  `Time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `ShortLinks` (
  `ShortlinkId` int(11) NOT NULL,
  `Reward` int(11) NOT NULL,
  `APILink` varchar(255) NOT NULL,
  `API` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `TimesClicked` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `User` (
  `UserID` int(11) NOT NULL,
  `UserLevel` int(11) NOT NULL DEFAULT '1',
  `Email` varchar(255) NOT NULL,
  `emailVerified` int(11) NOT NULL,
  `emailHash` varchar(255) NOT NULL,
  `refid` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `WalletAddr` varchar(255) NOT NULL,
  `ExpressCryptoId` varchar(255) NOT NULL,
  `lastLogin` int(11) NOT NULL,
  `ClaimTimes` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `Balance` decimal(9,8) NOT NULL,
  `Tokens` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Withdrawn` (
  `UserID` int(11) NOT NULL,
  `Time` int(11) NOT NULL,
  `Amount` int(11) NOT NULL,
  `Ip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `ShortLinkClaims`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `ShortLinks`
  ADD PRIMARY KEY (`ShortlinkId`);

ALTER TABLE `User`
  ADD PRIMARY KEY (`UserID`);

ALTER TABLE `ShortLinkClaims`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ShortLinks`
  MODIFY `ShortlinkId` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `User`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
