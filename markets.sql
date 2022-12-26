-- MariaDB dump 10.19  Distrib 10.4.24-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: trading
-- ------------------------------------------------------
-- Server version	10.4.24-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `market-airdrops`
--

DROP TABLE IF EXISTS `market-airdrops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-airdrops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marketid` int(5) NOT NULL,
  `quantity` float NOT NULL,
  `uses` int(5) NOT NULL,
  `timestamp` int(20) NOT NULL,
  `ftimestamp` int(30) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `market-airdrops-claim`
--

DROP TABLE IF EXISTS `market-airdrops-claim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-airdrops-claim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `airdropID` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `quantity` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `market-balances`
--

DROP TABLE IF EXISTS `market-balances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-balances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerid` int(11) NOT NULL,
  `marketid` int(11) NOT NULL,
  `quantity` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `market-balances`
--


--
-- Table structure for table `market-list`
--

DROP TABLE IF EXISTS `market-list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `logo` text NOT NULL,
  `isReal` tinyint(4) NOT NULL,
  `url` text NOT NULL,
  `fluctuationValue` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `market-list`
--

LOCK TABLES `market-list` WRITE;
/*!40000 ALTER TABLE `market-list` DISABLE KEYS */;
INSERT INTO `market-list` VALUES (1,'Bitcoin','https://cdn1.iconfinder.com/data/icons/social-icons-33/512/bitcoin-256.png',1,'1',0.01),(2,'Ethereum','https://s2.coinmarketcap.com/static/img/coins/64x64/1027.png',0,'1027',0.1),(3,'Cardano','https://s2.coinmarketcap.com/static/img/coins/64x64/2010.png',0,'2010',0.1),(4,'XRP','https://s2.coinmarketcap.com/static/img/coins/64x64/52.png',0,'52',0.1),(6,'Vikings','https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fstatic.turbosquid.com%2FPreview%2F2014%2F07%2F06__19_27_56%2FMedieval_Axe_V12_00.jpg8e809461-9f14-41a9-94ac-339ae255ea89Default.jpg&f=1&nofb=1',0,'',0.5),(8,'MathMark','https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Ftse1.mm.bing.net%2Fth%3Fid%3DOIP.qbjx951QZzH6E20Tjd0drwHaHa%26pid%3DApi&f=1',0,'',0.9),(9,'Sh3lt3r3d','https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fpurepng.com%2Fpublic%2Fuploads%2Flarge%2Fpurepng.com-gold-shieldshieldarmorbufferbucklerscreeniron-sheild-1421526640732bodc7.png&f=1&nofb=1',0,'',0.005);
/*!40000 ALTER TABLE `market-list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `market-lottery-prizes`
--

DROP TABLE IF EXISTS `market-lottery-prizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-lottery-prizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `prize` int(11) NOT NULL,
  `initialvalue` int(11) NOT NULL,
  `multiplier` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `market-map`
--

DROP TABLE IF EXISTS `market-map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `countryCode` varchar(2) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `market-map-log`;
CREATE TABLE `market-map-log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` int(11) NOT NULL, 
  `userAffectedId`int(11) NOT NULL,
  `countryCode` VARCHAR(2) NOT NULL,
  `quantity` float NOT NULL,
  `marketId` int(11) NOT NULL,
  `timestamp` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `market-map-auctions`
--

DROP TABLE IF EXISTS `market-map-auctions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-map-auctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerId` int(11) NOT NULL,
  `countryCode` varchar(2) NOT NULL,
  `startingPrice` float NOT NULL,
  `timestamp` int(11) NOT NULL,
  `endAuction` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `market-map-bets`
--

DROP TABLE IF EXISTS `market-map-bets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-map-bets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerId` int(11) NOT NULL,
  `bet` float NOT NULL,
  `countryCode` varchar(2) NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `countryCode` (`countryCode`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `market-dino-jackpot`;
CREATE TABLE `market-dino-jackpot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerId` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `lastClaimed` varchar(2) NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `market-offers`
--

DROP TABLE IF EXISTS `market-offers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ownerId` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `marketId` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `USD` float NOT NULL,
  `pricePerUnit` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `market-percentages`
--

DROP TABLE IF EXISTS `market-percentages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-percentages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marketid` float NOT NULL,
  `ph` float NOT NULL,
  `pd` float NOT NULL,
  `pw` float NOT NULL,
  `pm` float NOT NULL,
  `p2m` float NOT NULL,
  `p3m` float NOT NULL,
  `marketcap` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `market-transactions`
--

DROP TABLE IF EXISTS `market-transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buyerId` int(11) NOT NULL,
  `sellerId` int(11) NOT NULL,
  `marketId` int(11) NOT NULL,
  `coins` float NOT NULL,
  `dollars` float NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `market-users`
--

DROP TABLE IF EXISTS `market-users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `status` int(1) NOT NULL,
  `color` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `market-value`
--

DROP TABLE IF EXISTS `market-value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market-value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marketid` int(11) NOT NULL,
  `value` double NOT NULL,
  `timestamp` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `marketid` (`marketid`)
) ENGINE=InnoDB AUTO_INCREMENT=1900 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `market-value`
--

LOCK TABLES `market-value` WRITE;
/*!40000 ALTER TABLE `market-value` DISABLE KEYS */;
INSERT INTO `market-value` VALUES (26,1,102,1642103070),(27,1,111,1642103071),(28,1,0,1642103072),(29,1,14,1642103073),(30,1,17,1642103073),(31,1,109,1642103074),(32,1,121,1642103076),(33,1,0,1642103077),(34,1,64,1642103078),(35,1,3,1642103079),(36,1,114,1642103079),(37,1,97,1642103080),(38,1,7,1642103081),(39,1,0,1642103082),(40,1,51,1642103083),(41,1,48,1642103085),(42,1,113,1642103085),(43,1,29,1642103086),(44,1,0,1642103087),(45,1,0,1642103088),(46,1,0,1642103112),(47,1,113,1642103120),(48,1,74,1642103123),(49,1,0,1642103125),(50,1,81,1642103127),(51,1,126,1642103130),(52,1,122,1642103132),(53,1,0,1642103234),(54,1,8,1642103246),(55,1,0,1642103302),(56,1,0,1642103334),(57,1,0,1642103553),(58,1,0,1642103554),(59,1,44,1642103556),(60,1,141,1642103560),(61,1,163,1642172999),(62,1,147,1642173010),(63,1,91,1642173116),(64,1,176,1642173973),(65,1,153,1642175646),(66,1,192,1642175648),(67,1,273,1642175652),(68,1,370,1642175657),(69,1,375,1642175682),(70,1,401,1642175685),(71,1,493,1642175687),(72,1,498,1642175690),(73,1,398,1642175692),(74,1,483,1642175699),(75,1,566,1642175709),(76,1,664,1642175712),(77,1,624,1642175717),(78,1,710,1642175721),(79,1,766,1642175726),(80,1,824,1642175733),(81,1,768,1642175749),(82,1,834,1642175793),(83,1,775,1642175808),(84,1,788,1642175824),(85,1,777,1642175832),(86,1,705,1642175843),(87,1,727,1642175845),(88,1,740,1642197123),(89,1,688,1642241286),(90,1,720,1642241293),(91,1,784,1642241295),(92,1,796,1642241295),(93,1,874,1642241296),(94,1,777,1642243784),(1637,2,6841,1643044097),(1638,3,256,1643044097),(1639,4,129,1643044097),(1641,6,9992,1643044097),(1642,8,1032,1643044097),(1643,2,6788,1643044097),(1644,3,242,1643044097),(1645,4,122,1643044097),(1647,6,14551,1643044097),(1648,8,735,1643044097),(1649,2,7147,1643044097),(1650,3,254,1643044097),(1651,4,114,1643044097),(1653,6,15482,1643044097),(1654,8,913,1643044097),(1655,2,6546,1643044097),(1656,3,250,1643044097),(1657,4,106,1643044097),(1659,6,17150,1643044097),(1660,8,552,1643044097),(1661,2,6624,1643044098),(1662,3,241,1643044098),(1663,4,111,1643044098),(1665,6,20414,1643044098),(1666,8,314,1643044098),(1667,2,6841,1643044098),(1668,3,229,1643044098),(1669,4,106,1643044098),(1671,6,10570,1643044098),(1672,8,220,1643044098),(1673,2,7037,1643044098),(1674,3,238,1643044098),(1675,4,101,1643044098),(1677,6,13440,1643044098),(1678,8,177,1643044098),(1679,2,7495,1643044098),(1680,3,230,1643044098),(1681,4,108,1643044098),(1683,6,7605,1643044098),(1684,8,46,1643044098),(1685,2,7223,1643044099),(1686,3,226,1643044099),(1687,4,106,1643044099),(1689,6,9306,1643044099),(1690,8,11,1643044099),(1691,2,7169,1643044099),(1692,3,207,1643044099),(1693,4,113,1643044099),(1695,6,4765,1643044099),(1696,8,7,1643044099),(1698,2,7747,1643044683),(1699,3,212,1643044683),(1700,4,103,1643044683),(1702,6,6973,1643044684),(1703,8,11,1643044684),(1705,2,7930,1643044684),(1706,3,218,1643044684),(1707,4,92,1643044684),(1709,6,4382,1643044684),(1710,8,14,1643044684),(1711,9,4,1643044684),(1712,2,8582,1643044685),(1713,3,199,1643044685),(1714,4,98,1643044685),(1716,6,5442,1643044685),(1717,8,22,1643044685),(1718,9,4,1643044685),(1719,2,8607,1643044685),(1720,3,195,1643044685),(1721,4,99,1643044685),(1723,6,7683,1643044685),(1724,8,23,1643044685),(1725,9,4,1643044685),(1726,2,8440,1643044685),(1727,3,212,1643044685),(1728,4,98,1643044685),(1730,6,8823,1643044685),(1731,8,32,1643044685),(1732,9,3,1643044685),(1733,2,7687,1643044685),(1734,3,203,1643044685),(1735,4,96,1643044685),(1737,6,5405,1643044685),(1738,8,17,1643044685),(1739,9,2,1643044685),(1740,2,7590,1643044685),(1741,3,196,1643044685),(1742,4,103,1643044685),(1744,6,4025,1643044685),(1745,8,21,1643044685),(1746,9,2,1643044685),(1747,2,7647,1643044685),(1748,3,177,1643044685),(1749,4,107,1643044685),(1751,6,5028,1643044685),(1752,8,30,1643044685),(1753,9,1,1643044685),(1754,2,7521,1643044686),(1755,3,170,1643044686),(1756,4,103,1643044686),(1758,6,3016,1643044686),(1759,8,51,1643044686),(1760,9,0,1643044686),(1761,2,7006,1643044686),(1762,3,162,1643044686),(1763,4,107,1643044686),(1765,6,1834,1643044686),(1766,8,81,1643044686),(1767,9,0,1643044686),(1768,2,6409,1643044686),(1769,3,152,1643044686),(1770,4,101,1643044686),(1772,6,2191,1643044686),(1773,8,128,1643044686),(1774,9,0,1643044686),(1775,2,5832,1643044686),(1776,3,145,1643044686),(1777,4,105,1643044686),(1779,6,1315,1643044686),(1780,8,143,1643044686),(1781,9,0,1643044686),(1782,2,5628,1643044686),(1783,3,152,1643044686),(1784,4,103,1643044686),(1786,6,1236,1643044686),(1787,8,235,1643044686),(1788,9,500,1643044686),(1789,2,5344,1643044727),(1790,3,136,1643044727),(1791,4,106,1643044727),(1793,6,1385,1643044727),(1794,8,198,1643044727),(1795,9,478,1643044727),(1796,2,4986,1643044727),(1797,3,129,1643044727),(1798,4,115,1643044727),(1800,6,1341,1643044727),(1801,8,79,1643044727),(1802,9,485,1643044727),(1803,2,5142,1643044727),(1804,3,118,1643044727),(1805,4,124,1643044727),(1807,6,1883,1643044727),(1808,8,93,1643044727),(1809,9,476,1643044727),(1810,2,5113,1643044728),(1811,3,117,1643044728),(1812,4,123,1643044728),(1814,6,2578,1643044728),(1815,8,110,1643044728),(1816,9,460,1643044728),(1817,2,5357,1643044728),(1818,3,113,1643044728),(1819,4,134,1643044728),(1821,6,1446,1643044728),(1822,8,209,1643044728),(1823,9,470,1643044728),(1824,2,5307,1643044728),(1825,3,119,1643044728),(1826,4,143,1643044728),(1828,6,1176,1643044728),(1829,8,182,1643044728),(1830,9,459,1643044728),(1831,2,4966,1643044762),(1832,3,128,1643044762),(1833,4,149,1643044762),(1835,6,714,1643044762),(1836,8,160,1643044762),(1837,9,458,1643044762),(1838,2,4660,1643044762),(1839,3,132,1643044762),(1840,4,138,1643044762),(1842,6,529,1643044762),(1843,8,255,1643044762),(1844,9,458,1643044762),(1845,2,4328,1643044762),(1846,3,132,1643044762),(1847,4,127,1643044762),(1849,6,756,1643044762),(1850,8,141,1643044762),(1851,9,458,1643044762),(1852,2,4410,1643044780),(1853,3,121,1643044780),(1854,4,117,1643044780),(1856,6,882,1643044780),(1857,8,165,1643044780),(1858,9,460,1643044780),(1859,2,4145,1643044791),(1860,3,127,1643044791),(1861,4,112,1643044791),(1863,6,558,1643044791),(1864,8,236,1643044791),(1865,9,462,1643044791),(1866,2,4476,1643044791),(1867,3,135,1643044791),(1868,4,106,1643044791),(1870,6,781,1643044791),(1871,8,289,1643044791),(1872,9,463,1643044791),(1873,2,4469,1643044810),(1874,3,127,1643044810),(1875,4,112,1643044810),(1877,6,1083,1643044810),(1878,8,366,1643044810),(1879,9,464,1643044810),(1880,2,4877,1643044811),(1881,3,121,1643044811),(1882,4,104,1643044811),(1884,6,1243,1643044811),(1885,8,218,1643044811),(1886,9,461,1643044811),(1887,1,61.5,1661546145),(1888,1,61.5,1661549230),(1889,1,61.5,1661549230),(1890,1,61.5,1661549416),(1891,1,61.5,1661549497),(1892,1,1000,1661552127),(1893,1,400,1661554365),(1894,1,400,1661554415),(1895,1,400,1661554424),(1896,1,400,1661554519),(1897,1,400,1661699559),(1898,1,400,1661699601),(1899,1,300,1661699728);
/*!40000 ALTER TABLE `market-value` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-08-28 17:49:10
