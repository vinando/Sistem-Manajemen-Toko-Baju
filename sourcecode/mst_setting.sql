/*
SQLyog Enterprise - MySQL GUI v7.12 
MySQL - 5.5.27 : Database - jk_kalong
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `mst_setting` */

DROP TABLE IF EXISTS `mst_setting`;

CREATE TABLE `mst_setting` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `mst_setting` */

insert  into `mst_setting`(`id`,`name`,`value`) values (1,'Employee Type','Penjahit'),(2,'Employee Type','Back Office'),(3,'Employee Type','SPG'),(4,'Employee Type','Dry Cleaner');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
