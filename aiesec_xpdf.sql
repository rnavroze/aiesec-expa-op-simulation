-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 08, 2016 at 10:37 PM
-- Server version: 5.7.13
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aiesec_xpdf`
--

-- --------------------------------------------------------

--
-- Table structure for table `xpdf_eps`
--

CREATE TABLE `xpdf_eps` (
  `eid` int(11) NOT NULL,
  `ename` varchar(64) NOT NULL,
  `eprog` int(11) NOT NULL,
  `efield` int(11) NOT NULL,
  `eraisedby` int(11) NOT NULL,
  `ematchedby` int(11) NOT NULL,
  `ematchedto` int(11) NOT NULL,
  `eaddtime` time NOT NULL,
  `estartdate` date NOT NULL,
  `ecountryprefs` varchar(256) NOT NULL,
  `efacinumber` int(11) NOT NULL,
  `esns` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `xpdf_ir`
--

CREATE TABLE `xpdf_ir` (
  `iid` int(11) NOT NULL,
  `itfrom` int(11) NOT NULL,
  `itto` int(11) NOT NULL,
  `itype` int(11) NOT NULL,
  `inumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `xpdf_orgs`
--

CREATE TABLE `xpdf_orgs` (
  `oid` int(11) NOT NULL,
  `oname` varchar(64) NOT NULL,
  `oprog` int(11) NOT NULL,
  `ofield` int(11) NOT NULL,
  `osalary` int(11) NOT NULL,
  `oraisedby` int(11) NOT NULL,
  `omatchedby` int(11) NOT NULL,
  `omatchedto` int(11) NOT NULL,
  `oaddtime` time NOT NULL,
  `ostartdate` date NOT NULL,
  `ocountryprefs` varchar(256) NOT NULL,
  `ofacinumber` int(11) NOT NULL,
  `osns` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `xpdf_stats`
--

CREATE TABLE `xpdf_stats` (
  `sid` int(11) NOT NULL,
  `stime` time NOT NULL,
  `saction` int(11) NOT NULL COMMENT '1 - Ra, 2 - Ma, 3 - Re',
  `stype` int(11) NOT NULL,
  `sprog` int(11) NOT NULL,
  `steam` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `xpdf_teams`
--

CREATE TABLE `xpdf_teams` (
  `tid` int(11) NOT NULL,
  `tcodename` varchar(16) NOT NULL,
  `tname` varchar(16) NOT NULL,
  `tscore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `xpdf_teams`
--

INSERT INTO `xpdf_teams` (`tid`, `tcodename`, `tname`, `tscore`) VALUES
(1, 'Alpha', '', 352),
(2, 'Beta', '', 200),
(3, 'Gamma', '', 20),
(4, 'Delta', '', 8),
(5, 'Epsilon', '', 20),
(6, 'Zeta', '', 0),
(7, 'Iota', '', 0),
(8, 'Kappa', '', 0),
(9, 'Lambda', '', 0),
(10, 'Sigma', '', 0),
(11, 'Tau', '', 0),
(12, 'Upsilon', '', 0),
(13, 'Omicron', '', 0),
(14, 'Psi', '', 0),
(15, 'Rho', '', 0),
(16, 'Omega', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `xpdf_eps`
--
ALTER TABLE `xpdf_eps`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `xpdf_ir`
--
ALTER TABLE `xpdf_ir`
  ADD PRIMARY KEY (`iid`);

--
-- Indexes for table `xpdf_orgs`
--
ALTER TABLE `xpdf_orgs`
  ADD PRIMARY KEY (`oid`);

--
-- Indexes for table `xpdf_stats`
--
ALTER TABLE `xpdf_stats`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `xpdf_teams`
--
ALTER TABLE `xpdf_teams`
  ADD PRIMARY KEY (`tid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `xpdf_eps`
--
ALTER TABLE `xpdf_eps`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `xpdf_ir`
--
ALTER TABLE `xpdf_ir`
  MODIFY `iid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `xpdf_orgs`
--
ALTER TABLE `xpdf_orgs`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `xpdf_stats`
--
ALTER TABLE `xpdf_stats`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;
--
-- AUTO_INCREMENT for table `xpdf_teams`
--
ALTER TABLE `xpdf_teams`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
