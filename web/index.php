<?php

// echo 'test'; die;
if( isset($GLOBALS['_SERVER']['REQUEST_URI']) ){
	if($GLOBALS['_SERVER']['REQUEST_URI'] == "/movie" || $GLOBALS['_SERVER']['REQUEST_URI'] == "/movies"){
		header("Location: http://10.59.1.100?r=site/movie");
		die();
	}
}

// comment out the following two lines when deployed to production
// defined('YII_DEBUG') or define('YII_DEBUG', true);
// defined('YII_ENV') or define('YII_ENV', 'dev');


define('controllerIP', '10.59.1.100');
define('whitelistedIP1', '10.59.1.250');


define('package_total_in_GB', 500);

// if somehow some usage was not recorded
define('total_unrecorded_usage_in_GB', 0);


require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
