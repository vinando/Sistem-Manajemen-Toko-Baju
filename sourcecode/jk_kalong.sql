/*
SQLyog Enterprise - MySQL GUI v7.12 
MySQL - 5.5.27 : Database - jk_kalong
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `mst_branch` */

insert  into `mst_branch`(`id`,`code`,`name`) values (1,'HO','HO Gedung Halang 1'),(2,'HO New','HO Gedung Halang 2'),(3,'CIOMAS','CIOMAS PAGELARAN'),(4,'aaa','Test');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `mst_employee` */

insert  into `mst_employee`(`id`,`branchid`,`type`,`code`,`name`,`dob`,`gender`,`salary`) values (1,1,'Penjahit','K-050413-001','Abdolgofo','1980-12-26','Laki-laki',35000),(2,1,'Back Office','BO-050413-002','Ema Ratna Sari','1987-08-27','Perempuan',1500000),(3,1,'SPG','SPG-050413-001','Fitri','1989-08-20','Perempuan',1500000);

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
  `code` varchar(8) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ptype` varchar(50) NOT NULL,
  `size` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `material` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `mst_product` */

insert  into `mst_product`(`id`,`code`,`name`,`ptype`,`size`,`color`,`material`) values (1,'JK-L-001','Jaket Kulit 01','Finished Product','L','White','Kulit Domba Import - Turki'),(2,'JK-L-002','Jaket Kulit 02','Finished Product','M','Blue','Kulit Domba Local - KW1'),(3,'JK-M-001','Jaket Kulit 03a','Finished Product','XL','Yellow','Kulit Domba Local - KW2'),(4,'KI-TUR-0','Kulit Domba Import - Turki - KW1','Raw Material','','',''),(5,'AKS-SLTI','Sleting Import 001','Accessories','','','');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `mst_product_size` */

insert  into `mst_product_size`(`id`,`name`) values (1,'S'),(2,'M'),(3,'L'),(4,'XL'),(5,'XXL');

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

/*Table structure for table `mst_stock` */

DROP TABLE IF EXISTS `mst_stock`;

CREATE TABLE `mst_stock` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `branchid` int(4) unsigned NOT NULL,
  `productid` int(8) unsigned NOT NULL,
  `noseri` int(16) unsigned NOT NULL,
  `stock` int(4) unsigned NOT NULL,
  `price` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `mst_stock` */

insert  into `mst_stock`(`id`,`branchid`,`productid`,`noseri`,`stock`,`price`) values (1,1,1,1,1,2000000),(2,1,3,1,20,5000000),(3,1,1,2,1,3000000);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `trn_finish_order` */

insert  into `trn_finish_order`(`id`,`branchid`,`finishdate`,`finishno`,`orderid`,`orderno`,`employeeid`,`employeename`,`price`) values (1,1,'2013-04-07 01:23:23','FO-001',1,'O-1-1',1,'Abdolgofo',0),(2,1,'2013-04-07 01:25:16','FO-002',1,'O-1-2',1,'Abdolgofo',0),(3,1,'2013-04-07 02:27:04','FO-003',1,'O-1-3',2,'Ema Ratna Sari',0),(4,1,'2013-04-07 02:27:04','FO-1-4',17,'O-1-17',2,'Ema Ratna Sari',0);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `trn_finish_service` */

insert  into `trn_finish_service`(`id`,`branchid`,`finishdate`,`finishno`,`serviceid`,`serviceno`,`employeeid`,`employeename`,`price`) values (6,1,'2013-05-14 12:16:14','FS-1-6',2,'S-1-2',3,'Fitri',0);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `trn_material_using` */

insert  into `trn_material_using`(`id`,`branchid`,`orderid`,`orderno`,`trnno`,`trndate`) values (1,1,15,'O-1-15','URM-1-1','2013-04-19 03:17:00'),(2,1,14,'O-1-14','URM-1-2','2013-04-19 03:17:26'),(3,1,17,'O-1-17','URM-1-3','2013-05-04 08:43:29');

/*Table structure for table `trn_material_using_dt` */

DROP TABLE IF EXISTS `trn_material_using_dt`;

CREATE TABLE `trn_material_using_dt` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `trnid` int(16) unsigned NOT NULL,
  `productid` int(16) unsigned NOT NULL,
  `amount` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `trn_material_using_dt` */

insert  into `trn_material_using_dt`(`id`,`trnid`,`productid`,`amount`) values (5,1,4,2),(6,1,5,2),(7,2,4,1),(8,3,4,2);

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `trn_order` */

insert  into `trn_order`(`id`,`branchid`,`orderno`,`orderdate`,`status`,`customerid`,`customername`,`phone`,`total`,`dp`) values (14,1,'O-1-14','2013-04-14 01:23:57','',0,'gofoABC','081317070380',3000000,500000),(15,1,'O-1-15','2013-04-19 03:12:12','',0,'Tran minh tam','0101784839907655',0,0),(16,1,'O-1-16','2013-05-04 08:20:08','',0,'Gofo','12345',0,0),(17,1,'O-1-17','2013-05-04 08:34:24','',0,'Pak Adi','0878701111',0,0);

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `trn_order_assignment` */

insert  into `trn_order_assignment`(`id`,`branchid`,`orderid`,`orderno`,`assignmentdate`,`employeeid`,`employeename`,`price`) values (7,1,14,'O-1-14','2013-04-19 03:12:37',2,'Ema Ratna Sari',2000000),(8,1,15,'O-1-15','2013-04-19 03:13:00',2,'Ema Ratna Sari',2500000);

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

/*Data for the table `trn_order_dt` */

insert  into `trn_order_dt`(`id`,`orderid`,`productid`,`sizeid`,`colorid`,`materialid`,`price`,`amount`,`total`) values (13,15,3,5,4,3,0,1,0),(14,15,2,2,2,2,0,1,0),(15,16,1,1,2,1,0,1,0),(16,16,2,1,4,1,0,1,0),(17,17,1,1,2,1,0,1,0),(23,14,3,4,5,1,3000000,1,3000000);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `trn_purchases_order` */

insert  into `trn_purchases_order`(`id`,`branchid`,`orderno`,`orderdate`,`supplierid`,`suppliername`,`phone`) values (4,1,'PO-1-4','2013-05-19 02:39:50',0,'suplier 1','tlp 1');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

/*Data for the table `trn_purchases_order_dt` */

insert  into `trn_purchases_order_dt`(`id`,`orderid`,`productid`,`noseri`,`sizeid`,`colorid`,`materialid`,`price`,`amount`,`total`) values (8,4,1,1,3,1,1,2000000,1,2000000);

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

/*Data for the table `trn_sales_order` */

insert  into `trn_sales_order`(`id`,`branchid`,`orderno`,`orderdate`,`customerid`,`customername`,`phone`) values (4,1,'SO-1-4','2013-04-21 04:22:26',0,'Abdolgofo','081317070380'),(5,1,'SO-1-5','2013-05-01 12:17:06',0,'ICN','021123456789'),(6,1,'SO-1-6','2013-05-18 11:51:25',0,'sdsds','111111'),(15,1,'SO-1-15','2013-05-19 01:15:55',0,'asdfgh','100000');

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `trn_sales_order_dt` */

insert  into `trn_sales_order_dt`(`id`,`orderid`,`productid`,`noseri`,`sizeid`,`colorid`,`materialid`,`price`,`amount`,`total`) values (6,4,5,0,0,0,0,15000,1,15000),(7,5,3,0,4,6,3,3000000,2,6000000),(8,5,4,0,0,0,0,200000,2,400000),(9,6,1,0,3,1,1,2000000,1,2000000),(16,15,1,1,3,1,1,2000000,1,2000000);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `trn_service` */

insert  into `trn_service`(`id`,`branchid`,`servicetype`,`trnno`,`trndate`,`status`,`customerid`,`customername`,`phone`,`total`,`dp`) values (2,1,'Vermak','S-1-2','2013-05-10 01:30:13','',0,'Test Service 123','12345678',3000000,200000);

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

insert  into `trn_service_assignment`(`id`,`branchid`,`trnid`,`trnno`,`assignmentdate`,`employeeid`,`employeename`,`price`) values (1,1,2,'S-1-2','2013-05-12 12:29:31',1,'Abdolgofo',1500000);

/*Table structure for table `trn_service_dt` */

DROP TABLE IF EXISTS `trn_service_dt`;

CREATE TABLE `trn_service_dt` (
  `id` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `trnid` int(16) unsigned NOT NULL,
  `productid` int(16) unsigned NOT NULL,
  `productname` varchar(50) NOT NULL,
  `sizeid` int(8) unsigned NOT NULL,
  `colorid` int(8) unsigned NOT NULL,
  `materialid` int(8) unsigned NOT NULL,
  `price` int(16) unsigned NOT NULL,
  `amount` int(16) unsigned NOT NULL,
  `total` int(16) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `trn_service_dt` */

insert  into `trn_service_dt`(`id`,`trnid`,`productid`,`productname`,`sizeid`,`colorid`,`materialid`,`price`,`amount`,`total`) values (4,2,3,'',5,5,3,3000000,1,3000000);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
