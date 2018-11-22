<?php
/**
 * 方法：route(['user'=>['index','user','userid']]);
 */
	namespace z;
	class route{
		static private $controller,$action,$route=[],$delimit,$pregkey;
		static private $routeFile = APP . 'common/route.php';

		/**
		 * 添加路由规则
		 * @param [type] $route [description]
		 */
		static public function add($route){
			return self::$route = $route + self::$route;
		}

		/**
		 * 解析路由
		 * @param  [type] $info [description]
		 * @return [type]       [description]
		 */
		static private function preg_route($info){
			self::$delimit = z::$ZPHP_CONFIG['ROUTE_DELIMIT'];
			if(!self::$delimit){
				return false;
			}
			if(strpos($info,'/')){
				$arr = explode('/',$info);
				if(!strpos($arr[1],'=')){
					return self::path_info($info);
				}
				$info = $arr[0];
				parse_str($arr[1],$_GET);
			}

			if(empty(self::$route[$info])){
				$keys = explode(self::$delimit,$info);
				$key = array_shift($keys);
				$route = empty(self::$route[$key]) ? false : self::$route[$key];
			}else{
				$route = self::$route[$info];
				$keys = null;
			}
			if(!$route){
				return self::path_info($info);
			}
			self::$controller = $route[0];
			unset($route[0]);
			self::$action = $route[1];
			unset($route[1]);
			if($route){
				$route = array_values(array_filter($route));
				sort($route);
				foreach($route as $k=>$v){
					isset($keys[$k]) && $_GET[$v] = $keys[$k];
				}
			}
		}

		/**
		 * 解析pathinfo
		 * @param  [type] $str [description]
		 * @return [type]      [description]
		 */
		static private function path_info($str){
			$str = strstr($str,'.',true) ?: $str;
			$var_arr = explode('/',$str);
			self::$controller = array_shift($var_arr);
			$var_arr && self::$action = array_shift($var_arr);
			if($var_arr){
				$var_arr = array_chunk($var_arr,2);
				foreach($var_arr as $value){
					$_GET[$value[0]] = isset($value[1]) ? $value[1] : null;
				}
			}
		}

		/**
		 * 解析默认url
		 * @return [type] [description]
		 */
		static private function path_php(){
			if(!empty($_GET['c'])){
				self::$controller = $_GET['c'];
				unset($_GET['c']);
			}
			if(!empty($_GET['a'])){
				self::$action = $_GET['a'];
				unset($_GET['a']);
			}
		}

		/**
		 * 解析URL
		 * @return [type] [description]
		 */
		static public function route(){
			$php = explode('/',trim($_SERVER['SCRIPT_NAME'],'/'));
			define('PHP_FILE',array_pop($php));
			define('__ROOT__',$php ? '/' . implode('/',$php) . '/' : '/');
			$route = is_file(self::$routeFile) ? require(self::$routeFile) : [];
			self::$route += $route;
			$preg = '#(\/.+)*(\/index\.php)$#';
			$path_info = empty($_SERVER['PATH_INFO']) ? null : trim(preg_replace($preg,'$1',$_SERVER['PATH_INFO']),'/');
			if($path_info){
				if(ISROUTE){
					empty(self::$route) && \z\debug::ErrorHandler(8192,'没有配置路由','','');
					self::preg_route($path_info);
				}else{
					self::path_info($path_info);
				}
			}else{
				self::path_php();
			}
			define('CONTROLLER_NAME',self::$controller ? strtolower(self::$controller) : 'index');
			define('ACTION_NAME',self::$action ? strtolower(self::$action) : 'index');
		}


		static private function format($path){
			if(!is_array($path)){
				$path = explode('/',$path);
			}
			$path_count = count($path);
			$info = [PHP_FILE];
			switch($path_count){
				case 1: 
					$info[1] = $path[0] ?: 'index';;
					$info[2] = 'index';
					break;
				case 2:
				$info[1] = $path[0] ?: 'index';
				$info[2] = $path[1] ?: 'index';
					break;
				case 3:
				$info[0] = $path[0] ? "{$path[0]}" : PHP_FILE;
				$info[1] = $path[1] ?: 'index';
				$info[2] = $path[2];
					break;
				default:
					$info[1] = 'index';
					$info[2] = 'index';
					break;
			}
			return $info;
		}

		/**
		 * 生成pathinfo模式的rul
		 * @param [type] $path [description]
		 * @param [type] $arr  [description]
		 */
		static function Upathinfo($path,$arr,$mod=1){
			$ca = self::format($path);
			if(3 == $mod){
				if('index.php' == $ca[0]){
					unset($ca[0]);
				}
				else{
					$ca[0] = str_replace('.php','',$ca[0]);
				}
			}
			if($arr){
				foreach($arr as $k=>$v){
					if($v || '' !== $v){
						$args[] = "{$k}/$v";
					}
				}
			}
			if(empty($args)){
				if('index' == $ca[1] && 'index' == $ca[2]){
					$url =  empty($ca[0]) ? __ROOT__ : __ROOT__ . ('index.php'==$ca[0]||'index'==$ca[0] ? '' : $ca[0]);
				}
				elseif('index' == $ca[2]){
					$url = __ROOT__ . (empty($ca[0]) ? $ca[1] : "{$ca[0]}/{$ca[1]}") . z::$ZPHP_CONFIG['PATHINFO_SUFFIX'];
				}
				else{
					$url = __ROOT__ . implode('/',$ca) . z::$ZPHP_CONFIG['PATHINFO_SUFFIX'];
				}
			}else{
				$ca = implode('/',$ca);
				$query_str = implode('/',$args);
				$url = __ROOT__ . "{$ca}/{$query_str}" . z::$ZPHP_CONFIG['PATHINFO_SUFFIX'];
			}
			return $url;
		}

		/**
		 * 生成默认模式的url
		 * @param [type] $path [description]
		 * @param [type] $args [description]
		 */
		static function Uphp($path,$args){
			if($args && !is_array($args)){
				return false;
			}
			$ca = self::format($path);
			$ca[0] = 'index.php' == $ca[0] ? '' : $ca[0];
			$arr = [];
			'index' == $ca[1] || $arr['c'] = $ca[1];
			'index' == $ca[2] || $arr['a'] = $ca[2];
			$arr += $args;
			$query_str = empty($arr) ? '' : '?' . http_build_query($arr);
			return __ROOT__ . "{$ca[0]}{$query_str}";
		}

		private static function getPregKey(){
			$cache = CACHE_DIR . 'route/pregkey.cache';
			$cacheTime = filemtime($cache);
			if($cacheTime > filemtime(self::$routeFile) && $data = S($cache)){
				self::$pregkey = $data;
				return;
			}

			foreach(self::$route as $k=>$v){
				$preg = "{$v[0]}/{$v[1]}";
				unset($v[0]);
				unset($v[1]);
				sort($v);
				$arg = implode('',$v);
				self::$pregkey[$preg][$arg] = $k;
			}
			S($cache,self::$pregkey);
			return;
		}

		/**
		 * 生成路由模式的url
		 * @param [type] $path [description]
		 * @param [type] $arr  [description]
		 * @param [type] $mod  [description]
		 */
		static function Uroute($path,$arr=null,$mod=0){
			$mod || $mod = z::$ZPHP_CONFIG['URL_MOD'] ?? 2;
			if(!ISROUTE||!$arr){
				return self::Upathinfo($path,$arr,$mod);
			}
			if(!$delimit = self::$delimit ?: z::$ZPHP_CONFIG['ROUTE_DELIMIT']){
				return false;
			}
			$ca = self::format($path);
			self::$pregkey || self::getPregKey();
			self::$pregkey || \z\debug::ErrorHandler(8192,'没有配置路由','','');

			$kv = $arr;
			ksort($kv);
			$keys = array_keys($kv);
			$i = count($keys);
			$args = [];
			$preg = "{$ca[1]}/{$ca[2]}";
			for($i;$i;$i--){
				$argStr = implode('',array_slice($keys,0,$i));
				if(isset(self::$pregkey[$preg][$argStr])){
					break;
				}
				$args[$keys[$i-1]] = array_pop($kv);
			}

			if(!isset(self::$pregkey[$preg][$argStr])){
				$url = self::Upathinfo($path,$arr,$mod);
			}else{
				$key = self::$pregkey[$preg][$argStr];
				$argStr = $args ? '/'. http_build_query($args) : '';
				$dir = 3 == $mod ? ('index.php' == $ca[0] ? '' : str_replace('.php','',$ca[0]) . '/') : PHP_FILE . '/';
				$url = __ROOT__ . "{$dir}{$key}{$delimit}" . implode($delimit,array_values($kv)) . $argStr;
			}
			return $url;
		}

		/**
		 * 生成路由规则
		 * @param  [type] $path [description]
		 * @param  [type] $arr  [description]
		 * @param  [type] $key  [description]
		 * @return [type]       [description]
		 */
		static function getRole($path,$arr,$key){
			if(!$path || !$key){
				return false;
			}
			$ca = self::format($path);
			$keys = $arr ? array_keys($arr) : [];
			return array($key=>$keys);
		}

		/**
		 * 返回当前的路由规则
		 * @return [type] [description]
		 */
		static public function getRoute(){
			return self::$route;
		}
	}