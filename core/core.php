<?php
	error_reporting(E_ALL);
	ini_set('date.timezone','Asia/Shanghai');
	defined('DEBUG') || define('DEBUG',1);
	define('NOW_TIME',$_SERVER['REQUEST_TIME']);
	define('CORE',str_replace('\\','/',dirname(__FILE__).'/'));
	defined('ROOT') || define('ROOT',dirname(CORE) . '/');
	define('LIB',CORE.'lib'.'/');
	define('INIT',CORE.'init'.'/');
	define('APP',ROOT . APP_PATH . '/');
	define('COMMON',ROOT . 'common/');
	define('RUN_DIR',ROOT . 'rundir/');
	require LIB.'z/debug.class.php';
	set_exception_handler('\z\debug::ExceptionHandler');
	if(DEBUG){
		ini_set('display_errors', 'On');
		$GLOBALS['ZPHP_DEBUG'] = true;
		\z\debug::start(microtime(true));
		set_error_handler('\z\debug::ErrorHandler');
	}else{
		ini_set('display_errors', 'Off');
		$GLOBALS['ZPHP_DEBUG'] = false;
	}
	require(INIT . 'functions.php'); //加载核心函数库
	$GLOBALS['ZPHP_MAPPING'] = require(INIT . '/mapping.php'); //加载核心类库目录映射
	$CommonMapping_file = COMMON . 'mapping.php'; //公共类库映射
	$CustomerMapping_file = APP . 'common/mapping.php'; //用户类库映射
	is_file($CommonMapping_file) && ($CommonMapping = require $CommonMapping_file) && $GLOBALS['ZPHP_MAPPING'] = $CommonMapping + $GLOBALS['ZPHP_MAPPING'];
	is_file($CustomerMapping_file) && ($CustomerMapping = require $CustomerMapping_file) && $GLOBALS['ZPHP_MAPPING'] = $CustomerMapping + $GLOBALS['ZPHP_MAPPING'];
	spl_autoload_register('load');