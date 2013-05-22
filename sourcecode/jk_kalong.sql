/*
SQLyog Enterprise - MySQL GUI v7.12 
MySQL - 5.5.27 : Database - jk_kalong
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

USE `jk_kalong`;

/*Table structure for table `deprecated_trn_finish_product_dt` */

DROP TABLE IF EXISTS `deprecated_trn_finish_product_dt`;

CREATE TABLE `deprecated_trn_finish_product_dt` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `trnid` int(16) unsigned NOT NULL,
  `productid` int(8) unsigned NOT NULL,
  `amount` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `deprecated_trn_finish_product_dt` */

insert  into `deprecated_trn_finish_product_dt`(`id`,`trnid`,`productid`,`amount`) values (1,1,1,50),(2,1,2,65),(3,2,1,35),(4,2,2,70),(5,2,3,95),(6,3,1,50),(7,3,2,500);

/*Table structure for table `mst_branch` */

DROP TABLE IF EXISTS `mst_branch`;

CREATE TABLE `mst_branch` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `mst_branch` */

insert  into `mst_branch`(`id`,`code`,`name`) values (1,'HO','HO Gedung Halang 1'),(2,'HO New','HO Gedung Halang 2'),(3,'CIOMAS','CIOMAS PAGELARAN');

/*Table structure for table `mst_customer` */

DROP TABLE IF EXISTS `mst_customer`;

CREATE TABLE `mst_customer` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mst_customer` */

/*Table structure for table `mst_employee` */

DROP TABLE IF EXISTS `mst_employee`;

CREATE TABLE `mst_employee` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `code` varchar(16) NOT NULL,
  `name` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(50) NOT NULL,
  `salary` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `mst_employee` */

insert  into `mst_employee`(`id`,`branchid`,`type`,`code`,`name`,`dob`,`gender`,`salary`) values (1,1,'Penjahit','K-050413-001','Abdolgofo','1980-12-26','Laki-laki',35000),(2,1,'Back Office','BO-050413-002','Ema Ratna Sari','1987-08-27','Perempuan',1500000),(3,1,'SPG','SPG-050413-001','Fitri','1989-08-20','Perempuan',1500000),(4,1,'','DRCLN 01','Dryclean A','2013-05-01','Laki-laki',15000);

/*Table structure for table `mst_employee_type` */

DROP TABLE IF EXISTS `mst_employee_type`;

CREATE TABLE `mst_employee_type` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `mst_employee_type` */

insert  into `mst_employee_type`(`id`,`name`) values (1,'Penjahit'),(2,'Back Office'),(3,'SPG'),(4,'Dry Cleaning');

/*Table structure for table `mst_product` */

DROP TABLE IF EXISTS `mst_product`;

CREATE TABLE `mst_product` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ptype` varchar(50) NOT NULL,
  `size` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `material` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `mst_product` */

insert  into `mst_product`(`id`,`code`,`name`,`ptype`,`size`,`color`,`material`) values (1,'JK-L-001','Jaket Kulit 01','Finished Product','L','White','Kulit Domba Import - Turki'),(2,'JK-L-002','Jaket Kulit 02','Finished Product','M','Blue','Kulit Domba Local - KW1'),(3,'JK-M-001','Jaket Kulit 03a','Finished Product','XL','Yellow','Kulit Domba Local - KW2'),(4,'KI-TUR-0','Kulit Domba Import - Turki - KW1','Raw Material','','',''),(5,'AKS-SLTI','Sleting Import 001','Accessories','','',''),(6,'JKD-I-001','Jaket Kulit Domba Import 3','Finished Product','S','White','Kulit Domba Import - Turki');

/*Table structure for table `mst_product_category` */

DROP TABLE IF EXISTS `mst_product_category`;

CREATE TABLE `mst_product_category` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `ptype` varchar(50) NOT NULL,
  `catname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mst_product_category` */

/*Table structure for table `mst_product_color` */

DROP TABLE IF EXISTS `mst_product_color`;

CREATE TABLE `mst_product_color` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `mst_product_color` */

insert  into `mst_product_color`(`id`,`name`) values (1,'White'),(2,'Blue'),(3,'Green'),(4,'Yellow'),(5,'Black'),(6,'Brown');

/*Table structure for table `mst_product_material` */

DROP TABLE IF EXISTS `mst_product_material`;

CREATE TABLE `mst_product_material` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `mst_product_material` */

insert  into `mst_product_material`(`id`,`name`) values (1,'Kulit Domba Import - Turki'),(2,'Kulit Domba Local - KW1'),(3,'Kulit Domba Local - KW2');

/*Table structure for table `mst_product_size` */

DROP TABLE IF EXISTS `mst_product_size`;

CREATE TABLE `mst_product_size` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `mst_product_size` */

insert  into `mst_product_size`(`id`,`name`) values (1,'S'),(2,'M'),(3,'L'),(4,'XL'),(5,'XXL'),(6,'');

/*Table structure for table `mst_role` */

DROP TABLE IF EXISTS `mst_role`;

CREATE TABLE `mst_role` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `mst_role` */

insert  into `mst_role`(`id`,`name`,`description`) values (1,'System Administrator','Full Akses to system'),(2,'Reporting','Generating Reports');

/*Table structure for table `mst_role_dt` */

DROP TABLE IF EXISTS `mst_role_dt`;

CREATE TABLE `mst_role_dt` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `roleid` int(4) unsigned NOT NULL,
  `menu` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=239 DEFAULT CHARSET=latin1;

/*Data for the table `mst_role_dt` */

insert  into `mst_role_dt`(`id`,`roleid`,`menu`) values (8,2,'rptpembelanjaan'),(9,2,'rptgaji'),(10,2,'rptbiayaoperasional'),(11,2,'rptpemasukan'),(12,2,'rptrugilaba'),(13,2,'rptstockopname'),(14,2,'rptharian'),(215,1,'branch'),(216,1,'role'),(217,1,'employee'),(218,1,'product'),(219,1,'stock'),(220,1,'user'),(221,1,'trnorder'),(222,1,'trnorderassignment'),(223,1,'trnfinishorder'),(224,1,'trnservice'),(225,1,'trnserviceassignment'),(226,1,'trnfinishservice'),(227,1,'trnpenggunaanbahan'),(228,1,'trnsalesorder'),(229,1,'trnpurchasesorder'),(230,1,'trnkasharian'),(231,1,'rptpembelanjaan'),(232,1,'rptgaji'),(233,1,'rptbiayaoperasional'),(234,1,'rptpemasukan'),(235,1,'rptrugilaba'),(236,1,'rptstockopname'),(237,1,'exportdata'),(238,1,'setting');

/*Table structure for table `mst_setting` */

DROP TABLE IF EXISTS `mst_setting`;

CREATE TABLE `mst_setting` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `mst_setting` */

insert  into `mst_setting`(`id`,`name`,`value`) values (1,'Employee Type','Penjahit'),(2,'Employee Type','Back Office'),(3,'Employee Type','SPG');

/*Table structure for table `mst_stock` */

DROP TABLE IF EXISTS `mst_stock`;

CREATE TABLE `mst_stock` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `productid` int(8) unsigned NOT NULL,
  `noseri` int(16) unsigned NOT NULL,
  `stock` decimal(11,2) unsigned NOT NULL,
  `unit` varchar(50) NOT NULL,
  `price` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `mst_stock` */

insert  into `mst_stock`(`id`,`branchid`,`productid`,`noseri`,`stock`,`unit`,`price`) values (1,1,1,1,'1.00','',2000000),(2,1,4,1,'13.00','',5000000),(3,1,1,2,'1.00','',3000000),(4,1,6,1,'0.00','',500000),(5,1,6,2,'1.00','',500000);

/*Table structure for table `mst_supplier` */

DROP TABLE IF EXISTS `mst_supplier`;

CREATE TABLE `mst_supplier` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `mst_supplier` */

/*Table structure for table `mst_user` */

DROP TABLE IF EXISTS `mst_user`;

CREATE TABLE `mst_user` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `roleid` int(4) unsigned NOT NULL,
  `branchid` int(4) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `mst_user` */

insert  into `mst_user`(`id`,`username`,`password`,`fullname`,`email`,`roleid`,`branchid`) values (1,'admin','21232f297a57a5a743894a0e4a801fc3','Administrator','admin@localhost.com',1,1),(2,'report','e98d2f001da5678b39482efbdf5770dc','Reporter','report@kalong.com',2,1);

/*Table structure for table `trn_daily_cash` */

DROP TABLE IF EXISTS `trn_daily_cash`;

CREATE TABLE `trn_daily_cash` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `trnno` varchar(50) NOT NULL,
  `trndate` datetime NOT NULL,
  `description` varchar(255) NOT NULL,
  `debit` int(16) unsigned NOT NULL,
  `credit` int(16) unsigned NOT NULL,
  `balance` int(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `trn_daily_cash` */

insert  into `trn_daily_cash`(`id`,`branchid`,`trnno`,`trndate`,`description`,`debit`,`credit`,`balance`) values (9,1,'DC-1-9','2013-04-26 02:24:13','Saldo Awal',450000,0,450000),(10,1,'DC-1-10','2013-04-26 02:24:54','Pembelian Aqua Galon @ 2',0,35000,415000),(11,1,'DC-1-11','2013-04-26 02:25:23','Gaji Pegawai Bulan April 2013',0,2000000,-1585000);

/*Table structure for table `trn_finish_order` */

DROP TABLE IF EXISTS `trn_finish_order`;

CREATE TABLE `trn_finish_order` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `finishdate` datetime NOT NULL,
  `finishno` varchar(50) NOT NULL,
  `orderid` int(16) unsigned NOT NULL,
  `orderno` varchar(50) NOT NULL,
  `employeeid` int(8) unsigned NOT NULL,
  `employeename` varchar(50) NOT NULL,
  `price` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `trn_finish_order` */

insert  into `trn_finish_order`(`id`,`branchid`,`finishdate`,`finishno`,`orderid`,`orderno`,`employeeid`,`employeename`,`price`) values (1,1,'2013-05-22 02:20:40','FO-1-1',21,'O-1-21',1,'Abdolgofo',75000);

/*Table structure for table `trn_finish_service` */

DROP TABLE IF EXISTS `trn_finish_service`;

CREATE TABLE `trn_finish_service` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `finishdate` datetime NOT NULL,
  `finishno` varchar(50) NOT NULL,
  `serviceid` int(16) unsigned NOT NULL,
  `serviceno` varchar(50) NOT NULL,
  `employeeid` int(8) unsigned NOT NULL,
  `employeename` varchar(50) NOT NULL,
  `price` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trn_finish_service` */

/*Table structure for table `trn_material_using` */

DROP TABLE IF EXISTS `trn_material_using`;

CREATE TABLE `trn_material_using` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `orderid` int(16) unsigned NOT NULL,
  `orderno` varchar(50) NOT NULL,
  `trnno` varchar(50) NOT NULL,
  `trndate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `trn_material_using` */

insert  into `trn_material_using`(`id`,`branchid`,`orderid`,`orderno`,`trnno`,`trndate`) values (1,1,21,'O-1-21','URM-1-1','2013-05-22 02:20:09');

/*Table structure for table `trn_material_using_dt` */

DROP TABLE IF EXISTS `trn_material_using_dt`;

CREATE TABLE `trn_material_using_dt` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `trnid` int(16) unsigned NOT NULL,
  `productid` int(16) unsigned NOT NULL,
  `amount` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `trn_material_using_dt` */

insert  into `trn_material_using_dt`(`id`,`trnid`,`productid`,`amount`) values (1,1,4,1);

/*Table structure for table `trn_order` */

DROP TABLE IF EXISTS `trn_order`;

CREATE TABLE `trn_order` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) NOT NULL,
  `orderno` varchar(50) NOT NULL,
  `orderdate` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `customerid` int(8) NOT NULL,
  `customername` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `total` int(16) unsigned NOT NULL,
  `dp` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

/*Data for the table `trn_order` */

insert  into `trn_order`(`id`,`branchid`,`orderno`,`orderdate`,`status`,`customerid`,`customername`,`phone`,`total`,`dp`) values (21,1,'O-1-21','2013-05-20 03:59:34','Finished',0,'asdsds','131231231',950000,200000);

/*Table structure for table `trn_order_assignment` */

DROP TABLE IF EXISTS `trn_order_assignment`;

CREATE TABLE `trn_order_assignment` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `orderid` int(16) unsigned NOT NULL,
  `orderno` varchar(50) NOT NULL,
  `assignmentdate` datetime NOT NULL,
  `employeeid` int(8) unsigned NOT NULL,
  `employeename` varchar(50) NOT NULL,
  `price` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `trn_order_assignment` */

insert  into `trn_order_assignment`(`id`,`branchid`,`orderid`,`orderno`,`assignmentdate`,`employeeid`,`employeename`,`price`) values (1,1,21,'O-1-21','2013-05-22 02:18:31',1,'Abdolgofo',75000);

/*Table structure for table `trn_order_dt` */

DROP TABLE IF EXISTS `trn_order_dt`;

CREATE TABLE `trn_order_dt` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(16) unsigned NOT NULL,
  `productid` int(16) unsigned NOT NULL,
  `sizeid` int(8) unsigned NOT NULL,
  `colorid` int(8) unsigned NOT NULL,
  `materialid` int(8) unsigned NOT NULL,
  `price` int(16) NOT NULL,
  `amount` int(16) unsigned NOT NULL,
  `total` int(16) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

/*Data for the table `trn_order_dt` */

insert  into `trn_order_dt`(`id`,`orderid`,`productid`,`sizeid`,`colorid`,`materialid`,`price`,`amount`,`total`) values (34,21,6,2,1,2,500000,1,500000),(35,21,3,0,0,0,450000,1,450000);

/*Table structure for table `trn_purchases_order` */

DROP TABLE IF EXISTS `trn_purchases_order`;

CREATE TABLE `trn_purchases_order` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) NOT NULL,
  `orderno` varchar(50) NOT NULL,
  `orderdate` datetime NOT NULL,
  `supplierid` int(8) NOT NULL,
  `suppliername` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trn_purchases_order` */

/*Table structure for table `trn_purchases_order_dt` */

DROP TABLE IF EXISTS `trn_purchases_order_dt`;

CREATE TABLE `trn_purchases_order_dt` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(16) unsigned NOT NULL,
  `productid` int(16) unsigned NOT NULL,
  `noseri` int(16) unsigned NOT NULL,
  `sizeid` int(8) unsigned NOT NULL,
  `colorid` int(8) unsigned NOT NULL,
  `materialid` int(8) unsigned NOT NULL,
  `price` int(16) unsigned NOT NULL,
  `amount` int(16) unsigned NOT NULL,
  `total` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `trn_purchases_order_dt` */

/*Table structure for table `trn_sales_order` */

DROP TABLE IF EXISTS `trn_sales_order`;

CREATE TABLE `trn_sales_order` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) NOT NULL,
  `orderno` varchar(50) NOT NULL,
  `orderdate` datetime NOT NULL,
  `customerid` int(8) NOT NULL,
  `customername` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `trn_sales_order` */

insert  into `trn_sales_order`(`id`,`branchid`,`orderno`,`orderdate`,`customerid`,`customername`,`phone`) values (16,1,'SO-1-16','2013-05-20 03:54:53',0,'asdasd','123123213');

/*Table structure for table `trn_sales_order_dt` */

DROP TABLE IF EXISTS `trn_sales_order_dt`;

CREATE TABLE `trn_sales_order_dt` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(16) unsigned NOT NULL,
  `productid` int(16) unsigned NOT NULL,
  `noseri` int(16) unsigned NOT NULL,
  `sizeid` int(8) unsigned NOT NULL,
  `colorid` int(8) unsigned NOT NULL,
  `materialid` int(8) unsigned NOT NULL,
  `price` int(16) unsigned NOT NULL,
  `amount` int(16) unsigned NOT NULL,
  `total` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `trn_sales_order_dt` */

insert  into `trn_sales_order_dt`(`id`,`orderid`,`productid`,`noseri`,`sizeid`,`colorid`,`materialid`,`price`,`amount`,`total`) values (17,16,6,1,0,1,1,500000,1,500000);

/*Table structure for table `trn_service` */

DROP TABLE IF EXISTS `trn_service`;

CREATE TABLE `trn_service` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `servicetype` varchar(50) NOT NULL,
  `trnno` varchar(50) NOT NULL,
  `trndate` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `customerid` int(8) unsigned NOT NULL,
  `customername` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `total` int(16) unsigned NOT NULL,
  `dp` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `trn_service` */

insert  into `trn_service`(`id`,`branchid`,`servicetype`,`trnno`,`trndate`,`status`,`customerid`,`customername`,`phone`,`total`,`dp`) values (1,1,'Vermak','S-1-1','2013-05-21 11:43:17','Assigned',0,'Abdolgofo','081317070380',7500000,500000);

/*Table structure for table `trn_service_assignment` */

DROP TABLE IF EXISTS `trn_service_assignment`;

CREATE TABLE `trn_service_assignment` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `trnid` int(16) unsigned NOT NULL,
  `trnno` varchar(50) NOT NULL,
  `assignmentdate` datetime NOT NULL,
  `employeeid` int(8) unsigned NOT NULL,
  `employeename` varchar(50) NOT NULL,
  `price` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `trn_service_assignment` */

insert  into `trn_service_assignment`(`id`,`branchid`,`trnid`,`trnno`,`assignmentdate`,`employeeid`,`employeename`,`price`) values (1,1,1,'S-1-1','2013-05-22 02:10:51',1,'Abdolgofo',500000);

/*Table structure for table `trn_service_dt` */

DROP TABLE IF EXISTS `trn_service_dt`;

CREATE TABLE `trn_service_dt` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `trnid` int(16) unsigned NOT NULL,
  `productname` varchar(50) NOT NULL,
  `sizename` varchar(50) NOT NULL,
  `colorname` varchar(50) NOT NULL,
  `materialname` varchar(50) NOT NULL,
  `price` int(16) unsigned NOT NULL,
  `amount` int(16) unsigned NOT NULL,
  `total` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `trn_service_dt` */

insert  into `trn_service_dt`(`id`,`trnid`,`productname`,`sizename`,`colorname`,`materialname`,`price`,`amount`,`total`) values (6,1,'Product 1','L','Putih','Kulit Domba',5000000,1,5000000),(7,1,'Product 2','XL','Biru','Kulit Macan',2500000,1,2500000);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
