<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    dirname(__FILE__).'/../library',
    get_include_path(),
)));
//echo get_include_path();
ini_set("date.timezone", "Asia/Jakarta");

/** Zend_Application */
require_once 'Zend/Application.php';
require_once 'Zend/Registry.php';
require_once 'Zend/Db/Adapter/Pdo/Mysql.php';
require_once 'Zend/Layout.php';
require_once 'Zend/Session.php';
require_once 'IGLO/IGLO_String.php';

// Define object database
$params = array('host'		=>'localhost',
	                'username'	=>'root',
					'password'  =>'',
					'dbname'	=>'jk_kalong'
	               );
$DB = new Zend_Db_Adapter_Pdo_Mysql($params);

$DB->setFetchMode(Zend_Db::FETCH_OBJ);
Zend_Registry::set('DB', $DB);
Zend_Session::start();

//$Session = new Zend_Session_Namespace();

// Save all setting to session
/*$arrSettings = $DB->fetchAssoc("SELECT * FROM mst_setting");
foreach ($arrSettings as $arrSetting) {
    $_SESSION["Setting"][$arrSetting["Key"]] = $arrSetting["Value"];
	//$Session->Setting[$arrSetting["Key"]] = $arrSetting["Value"];
}*/

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();