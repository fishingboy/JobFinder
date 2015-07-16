-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生時間： 2015-07-16 19:00:48
-- 伺服器版本: 5.6.20
-- PHP 版本： 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `jobfinder`
--

-- --------------------------------------------------------

--
-- 資料表結構 `company`
--

CREATE TABLE IF NOT EXISTS `company` (
  `companyID` int(11) NOT NULL,
  `c_code` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `addr_no_descript` varchar(200) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `addr_indzone` varchar(200) DEFAULT NULL,
  `indcat` varchar(200) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `product` text,
  `profile` text,
  `welfare` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `job`
--

CREATE TABLE IF NOT EXISTS `job` (
  `jobID` int(11) NOT NULL,
  `companyID` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `j_code` varchar(200) DEFAULT NULL,
  `job_addr_no_descript` varchar(200) DEFAULT NULL,
  `job_address` varchar(200) DEFAULT NULL,
  `jobcat_descript` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `period` int(11) DEFAULT NULL,
  `appear_date` varchar(200) DEFAULT NULL,
  `dis_role` int(11) DEFAULT NULL,
  `dis_level` int(11) DEFAULT NULL,
  `dis_role2` int(11) DEFAULT NULL,
  `dis_level2` int(11) DEFAULT NULL,
  `dis_role3` int(11) DEFAULT NULL,
  `dis_level3` int(11) DEFAULT NULL,
  `driver` varchar(200) DEFAULT NULL,
  `handicompendium` varchar(200) DEFAULT NULL,
  `role` varchar(200) DEFAULT NULL,
  `role_status` varchar(200) DEFAULT NULL,
  `s2` varchar(200) DEFAULT NULL,
  `s3` varchar(200) DEFAULT NULL,
  `sal_month_low` varchar(200) DEFAULT NULL,
  `sal_month_high` varchar(200) DEFAULT NULL,
  `worktime` varchar(200) DEFAULT NULL,
  `startby` varchar(200) DEFAULT NULL,
  `cert_all_descript` varchar(200) DEFAULT NULL,
  `jobskill_all_desc` varchar(200) DEFAULT NULL,
  `pcskill_all_desc` varchar(200) DEFAULT NULL,
  `language1` varchar(200) DEFAULT NULL,
  `language2` varchar(200) DEFAULT NULL,
  `language3` varchar(200) DEFAULT NULL,
  `lat` varchar(200) DEFAULT NULL,
  `lon` varchar(200) DEFAULT NULL,
  `major_cat_descript` varchar(200) DEFAULT NULL,
  `minbinary_edu` varchar(200) DEFAULT NULL,
  `need_emp` varchar(200) DEFAULT NULL,
  `need_emp1` varchar(200) DEFAULT NULL,
  `ondutytime` varchar(200) DEFAULT NULL,
  `offduty_time` varchar(200) DEFAULT NULL,
  `others` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(5) NOT NULL,
  `account` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `note` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`companyID`);

--
-- 資料表索引 `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`jobID`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `company`
--
ALTER TABLE `company`
  MODIFY `companyID` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `job`
--
ALTER TABLE `job`
  MODIFY `jobID` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
