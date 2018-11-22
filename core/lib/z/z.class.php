<?php
	namespace z;
	class z{
		public static $ZPHP_CONFIG,$ZPHP_PDO,$ZPHP_REDIS,$ZPHP_MEMCACHED;
		public static final function start(){
			define('VERSION','3.1.8');
			define('IS_AJAX',isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && 'xmlhttprequest' == strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]));
			define('IS_GET',$_SERVER['REQUEST_METHOD'] == 'GET');
			define('IS_POST',$_SERVER['REQUEST_METHOD'] == 'POST');
			self::$ZPHP_CONFIG = require INIT . 'config.php'; //加载核心配置文件
			$CommonConfig_file = COMMON . 'config.php';
			$CustomerConfig_file = APP . 'common/config.php'; //加载应用配置文件
			$CommonFunction_file = COMMON . 'functions.php';//加载公共函数库
			$CustomerFunction_file = APP . 'common/functions.php';//加载应用函数库
			is_file($CommonConfig_file) && ($CommonConfig = require $CommonConfig_file) && self::$ZPHP_CONFIG = $CommonConfig + self::$ZPHP_CONFIG;
			is_file($CustomerConfig_file) && ($CustomerConfig = require $CustomerConfig_file) && self::$ZPHP_CONFIG = $CustomerConfig + self::$ZPHP_CONFIG;
			is_file($CommonFunction_file) && require $CommonFunction_file;
			is_file($CustomerFunction_file) && require $CustomerFunction_file;
			!empty(self::$ZPHP_CONFIG['AUTO_MOBILE']) && define('ISMOBILE',self::ZPHP_checkWap()) && ISMOBILE && self::$ZPHP_CONFIG['THEME'] = self::$ZPHP_CONFIG['AUTO_MOBILE'];

			if(!empty(self::$ZPHP_CONFIG['SESSION_MOD'])){
				$session_savepath = 'tcp://'.self::$ZPHP_CONFIG['REDIS_HOST'].':'.self::$ZPHP_CONFIG['REDIS_PORT'];
				empty(self::$ZPHP_CONFIG['REDIS_PASS']) || $session_savepath .= '?auth=' . self::$ZPHP_CONFIG['REDIS_PASS'];
				ini_set('session.save_handler','redis');
				ini_set('session.save_path',$session_savepath);
			}
			session_start();

			//加载路由
			define('ISROUTE',self::$ZPHP_CONFIG['ROUTE_ON'] ?? false);
			route::route();
			define('MODEL',APP . 'model/');
			define('VIEW',APP . 'public_html/');
			define('THEME',VIEW . self::$ZPHP_CONFIG['THEME'] . '/');
			define('RUN_APP',RUN_DIR . APP_PATH . '/');
			define('CACHE_DIR',RUN_DIR . 'cache/');
			if(defined('IN')){
				define('RES',IN . 'res/' . APP_PATH . '/' . self::$ZPHP_CONFIG['THEME']);
				define('__RES__',__ROOT__ . 'res/' . APP_PATH . '/' . self::$ZPHP_CONFIG['THEME']);
				define('__PUBLIC__',__ROOT__ . 'public');
			}else{
				define('IN',ROOT . RES_PATH . '/');
				define('RES',IN . 'res/' . APP_PATH . '/' . self::$ZPHP_CONFIG['THEME']);
				define('__RES__',__ROOT__ . RES_PATH .'/res/' . APP_PATH . '/' . self::$ZPHP_CONFIG['THEME']);
				define('__PUBLIC__',__ROOT__ . RES_PATH . '/public');
			}
			define('PUB',IN . 'public/');
			is_dir(APP) || self::ZPHP_mkAppPath();
			make_dir(RUN_DIR);
			headers_sent() || header("Content-type: text/html; charset=utf-8");
			$cmd = '\\c\\' . CONTROLLER_NAME;
			$act = ACTION_NAME;
			$GLOBALS['ZPHP_DEBUG'] || method_exists($cmd,$act) || class_exists($cmd,false) && $cmd::_404() || controller::_404(); 
			method_exists($cmd,'init') && $cmd::init();
			$result = $cmd::$act();
			method_exists($cmd,'after') && $cmd::after();
			isset($result) ? die($cmd::json($result)) : debug::showMsg();
		}

		private static function ZPHP_checkWap(){
			return isset($_SERVER['HTTP_X_WAP_PROFILE']) || (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'],'wap')) || (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(nokia|sony|ericsson|mot|samsung|htc|sgh|lg|sharp|sie-|philips|panasonic|alcatel|lenovo|iphone|ipod|blackberry|meizu|android|netfront|symbian|ucweb|windowsce|palm|operamini|operamobi|openwave|nexusone|cldc|midp|wap|mobile)/i',strtolower($_SERVER['HTTP_USER_AGENT']))) || (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'],'vnd.wap.wml') !== false && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))));
		}

		private static function ZPHP_mkAppPath(){
			make_dir(COMMON . 'model',0755,true,true);
			make_dir(COMMON . 'lib',0755,true,true);
			make_dir(THEME,0755,true,true);
			make_dir(MODEL,0755,true,true);
			make_dir(PUB,0755,true,true);
			make_dir(RES,0755,true,true);
			make_dir(APP . 'common',0755,true,true);
			make_dir(APP . 'controller',0755,true,true);
			$file = COMMON . 'mapping.php';
			if(!is_file($file)){
			$str = "<?php
	return [
		'm'	=>	APP . 'model/',
		'common'	=>	COMMON . 'model/',
		'lib'	=>	COMMON . 'lib/',
	];";
				file_put_contents($file,$str);
			}

			$file = COMMON . 'config.php';
			if(!is_file($file)){
			$str = "<?php
	return [
		'DB_HOST' => '127.0.0.1',
		'DB_NAME' => 'root',
		'DB_USER' => 'root',
		'DB_PASS' => 'root',
		'DB_PORT' => '3306',
		'DB_PREFIX' => 'col_',
	];";
				file_put_contents($file,$str);
			}
			$file = COMMON . 'functions.php';
			if(!is_file($file)){
			$str = "<?php
	//自定义的函数写在这里";
				file_put_contents($file,$str);
			}
			$str = "<?php
	return [
		'AUTO_MOBILE' => '',
		'THEME' => 'default',
	];";
			file_put_contents(APP . 'common/config.php',$str);

			$str = "<?php
	namespace c;
	use \z\controller;
	class index extends controller{
		public static function index(){
			echo '<h1>欢迎使用Z-PHP框架</h1>';
		}
	}";
			file_put_contents(APP . 'controller/index.class.php',$str);
		}
	}