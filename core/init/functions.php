<?php
	/**
	 * 自动加载类文件，需配置mapping映射文件
	 * @param [type] $r [description]
	 */
	function load($r){
		if(strpos($r,'\\')){
			$path_arr = explode('\\',$r);
			$path_root = array_shift($path_arr);
			if(!isset($GLOBALS['ZPHP_MAPPING'][$path_root])){
				if(empty($GLOBALS['ZPHP_AUTOLOAD'])) throw new \Exception("命名空间 {$path_root} 未做路径映射");
				else return $GLOBALS['ZPHP_AUTOLOAD']($r);
			}
			$fileName = array_pop($path_arr) . '.class.php';
			$sub_path = $path_arr ? implode('/',$path_arr) . '/' : '';
			$file = "{$GLOBALS['ZPHP_MAPPING'][$path_root]}{$sub_path}{$fileName}";
			if(is_file($file)){
				require $file;
			}elseif($GLOBALS['ZPHP_DEBUG'] || 'c' != $path_root){
				\z\debug::ErrorHandler(1101,'',$file,'类文件不存在');
				throw new \Exception("file not fond: {$file}");
			}
		}else{
			empty($GLOBALS['ZPHP_AUTOLOAD']) || $GLOBALS['ZPHP_AUTOLOAD']($r);
		}
	}
	function zautoload($act){
		$GLOBALS['ZPHP_AUTOLOAD'] = $act;
	}

	/**
	 * 记录日志
	 * @param  [type] $str  [日志内容]
	 * @param  string $path [目录]
	 * @return [type]       [description]
	 */
	function _log($str,$path='mylog'){
		$log_path = RUN_DIR . $path;
		if(make_dir($log_path)){
			$file = $log_path . '/' . date('Ymd') . ".log";
			$time = date('Y-m-d H:i:s');
			$str = "{$time}\t{$str}\r\n";
			file_put_contents($file, $str, FILE_APPEND);
		}
	}

	/**
	 * 操作cookie
	 * @param  [type]  $key   [description]
	 * @param  string $value [description]
	 * @param  integer $time  [description]
	 * @param  string  $path  [description]
	 * @return [type]         [description]
	 */
	function cookie(){
		$args = func_get_args();
		if(isset($args[1])){
			$time = empty($args[2]) ? ($_COOKIE['InvalidTime'] ?? 0) : NOW_TIME + $args[2];
			$path = $args[3] ?? '/';
			setcookie('InvalidTime',$time,$time,$path);
			return setMyCookie($args[0],$args[1],$time,$path);
		}else{
			if(!isset($_COOKIE[$args[0]])) return null;
			return $_COOKIE[$args[0]];
		}
	}
	function setMyCookie($key,$value,$time,$path,$i=0){
		if(!is_array($value)){
			if(setcookie($key,$value,$time,$path)) $i++;
		}else{
			foreach($value as $k=>$v){
				setMyCookie("{$key}[$k]",$v,$time,$path,$i);
			}
		}
		return $i;
	}

	/**
	 * GET或POST参数取值
	 * @param [string] $name [description]
	 */
	function I($name,$split=null){
		$name = isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : null);
		if($name){
			$name = urldecode(trim($name));
			$split && $name = explode($split,$name);
		}
		return $name;
	}

	/**
	 * 设置是否输出debug
	 * @param  [boolean] $i [description]
	 * @return [type]    [description]
	 */
	function debug($i){
		$GLOBALS['ZPHP_DEBUG'] = $i;
	}

	/**
	 * 打印数据，echo为假时返回打印的字符串，不输出
	 * @param  [type]  $var    [description]
	 * @param  boolean $echo   [description]
	 * @return [type]          [description]
	 */
	function P($var,$echo=true) {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();
		extension_loaded('xdebug') || $output = '<pre>' . htmlspecialchars(preg_replace('/\]\=\>\n(\s+)/m','] =>',$output),ENT_QUOTES) . '</pre>';
		if($echo){
			echo $output;
		}else{
			return $output;
		}
	}

	/**
	 * 实例化数据模型
	 * @param [type]  $table [数据表名]
	 * @param boolean $long  [是否长连接]
	 */
	function D($table=null,$c=null){
		return (new \z\db)->DB_init($table,$c);
	}

	/**
	 * 实例化自定义模型,优先查找应用/model目录下的文件，如果没有则查找common/model目录
	 * @param [type]  $table [类文件名(同时对应同名的数据表)]
	 * @param [type]  $app   [应用名]
	 */
	function M($table,$app=APP_PATH,$c=null){
		if(is_file($file = ROOT . "{$app}/model/{$table}.class.php")){
			require_once($file);
			$model = '\\m\\' . $table;
		}
		elseif(is_file(COMMON . "model/{$table}.class.php")) $model = '\\common\\' . $table;
		else $model = '\\z\\db';
		$model = new $model;
		return method_exists($model,'DB_init') ? $model->DB_init($table,$c) : $model;
	}

	/**
	 * 创建目录
	 * @param  [type]  $dir       [description]
	 * @param  integer $mode      [description]
	 * @param  boolean $recursive [description]
	 * @param  boolean $throwErr  [是否中断并抛出异常]
	 * @return [type]             [description]
	 */
	function make_dir($dir,$mode=0755,$recursive=true,$throwErr=false){
		if(!file_exists($dir)){
			if(!mkdir(iconv("UTF-8","GBK",$dir),$mode,$recursive)){
				if($throwErr) throw new Error("创建目录{$dir}失败,请检查权限");
				else \z\Debug::ErrorHandler(1101,'',$dir,"创建目录失败,请检查权限");
				return false;
			}
		}
		return true;
	}
	/**
	 * 删除目录及文件
	 * @param  [type]  $dirName [description]
	 * @param  boolean $t       [是否删除目录]
	 * @param  integer $i       [description]
	 * @return integer          [删除的文件数]
	 */
	function del_dir($dirName,$t=false,$i=0){
		if($handle = opendir($dirName)){
			while(false !== ($item = readdir($handle))){
				if ($item != "." && $item != ".."){
					if(is_dir($dirName.'/'.$item)){
						$ii=del_dir($dirName.'/'.$item,$t);
						$i+=$ii;
					}elseif(unlink($dirName.'/'.$item)) $i++;
				}
			}
			closedir($handle);			
		}else return false;
		$t && rmdir($dirName);
		return $i;
	}

	/**
	 * 设置/获取配置信息
	 */
	function C(){
		$args = func_get_args();
		if(isset($args[1])){
			return \z\z::$ZPHP_CONFIG[$args[0]] = $args[1];
		}else{
			return \z\z::$ZPHP_CONFIG[$args[0]] ?? null;
		}
	}

	/**
	 * 设置路由
	 * @param  integer $route [description]
	 * @return [type]         [description]
	 */
	function route($route=null){
		if(!$route) return false;
		return \z\route::add($route);
	}

	/**
	 * 生成路由规则
	 * @param [type] $path [description]
	 * @param [type] $arr  [description]
	 * @param [type] $key  [description]
	 */
	function UR($path,$arr=[],$key=null){
		return \z\route::getRole($path,$arr,$key);
	}

	/**
	 * 生成url
	 * @param [string]  $path    ['控制器名/方法名']
	 * @param [array]   $arr     [传递参数]
	 * @param [type]    $url_mod [url模式：0：默认,1：pathinfo,其他：路由模式(值为key)]
	 */
	function U($path,$arr=[],$url_mod=null){
		$url_mod = null !== $url_mod ? $url_mod : C('URL_MOD');
		switch($url_mod){
			case '0':
				return \z\route::Uphp($path,$arr);
				break;
			case '1':
				return \z\route::Upathinfo($path,$arr);
			default:
				return \z\route::Uroute($path,$arr,$url_mod);
				break;
		}
	}
	
	/**
	 * 是否是关联数组
	 * @param  [type]  $arr [description]
	 * @return boolean      [description]
	 */
	function is_assoc($arr){
		return !is_numeric(implode('',array_keys($arr)));
	}

	/**
	 * 是否是索引数组
	 * @param  [type]  $arr [description]
	 * @param  [type]  $r   下标是否是从0开始的连续数字
	 * @return boolean      [description]
	 */
	function is_index($arr,$r=false){
		$str = implode('',array_keys($arr));
		return is_numeric($str) ? ($r ? $str == implode('',range(0,count($arr) - 1)) : true) : talse;
	}

	/**
	* 文件大小格式化
	* @param integer $size 初始文件大小，单位为byte
	* @return array 格式化后的文件大小和单位数组，单位为byte、KB、MB、GB、TB
	*/
	function file_size_format($size=0,$dec=2){
		$unit = ['B','KB','MB','GB','TB','PB'];
		$pos = 0;
		while($size >= 1024){
			$size /= 1024;
			$pos++;
		}
		return round($size,$dec) . $unit[$pos];
	}

	/**
	 * 压缩html文本（去除注释多余的空格制表符及换行符，如果文本存在js代码请谨慎使用）
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	function compress_html($string){
		return trim(preg_replace(['/<!--[^\!\[]*?-->/','/[\\n\\r\\t]+/','/\\s{2,}/','/>\\s</','/\\/\\*.*?\\*\\//i'],['',' ',' ','><',''],$string)); 
	}

	/**
	 * Unicode编码转换
	 * @param  [type] $str [description]
	 * @return [type]      [description]
	 */
	function decodeUnicode($str){
		return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
		create_function(
			'$matches',
			'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
		),
		$str);
	}
	function RC($key,$data=null,$t=0){
		$c = R();
		if(null !== $data){
			return $c->setex($key,$t?:600,serialize($data));
		}else{
			$result = $c->get($key);
			return $result ? unserialize($result) : $result;
		}
	}
	function MC($key,$data=null,$t=0){
		$c = MEM();
		if(null !== $data){
			return $c->set($key,serialize($data),$t?:600);
		}else{
			$result = $c->get($key);
			return $result ? unserialize($result) : $result;
		}
	}
	function S($cache,$data=null,$time=0){
		switch(\z\z::$ZPHP_CONFIG['CACHE_MOD']??0){
			case 1:
				$result = RC($cache,$data,$time);
			break;
			case 2:
				$result = MC($cache,$data,$time);
			break;
			default:
				$result = FC($cache,$data,$time);
			break;
		}
		return $result;
	}

	/**
	 * 设置/获取缓存
	 * @param string  		$cache [缓存名或路径]
	 * @param array/object  $data  [缓存数据]
	 * @param integer 		$time  [生存时间：秒]
	 */
	function FC($cache,$data=null,$time=0){
		$file = true_path($cache) ? $cache : CACHE_DIR . $cache;
		if(null !== $data){
			if($time){
				$DATA['cacheData_data'] = $data;
				$DATA['cacheData_EndTime'] = NOW_TIME + $time;
			}else $DATA = $data;
			if((!$result = make_dir(dirname($file))) || (false === $result = file_put_contents($file,serialize($DATA),LOCK_EX))){
				throw new \Exception("目录或文件 {$file} 不可写，请检查权限");
			}
			return $result;
		}else{
			if(!is_file($file)) return false;
			$DATA = unserialize(file_get_contents($file));
			if(isset($DATA['cacheData_EndTime'])){
				if(NOW_TIME > $DATA['cacheData_EndTime']){
					$DATA = $DATA['cacheData_data'];
				}else{
					file_put_contents($file,false,LOCK_EX);
					$DATA = false;
				}
			}
			return $DATA;
		}
	}

	/**
	 * 获取客户端ip地址
	 * @return string
	 */
	function getip(){
		$ip=false;
		empty($_SERVER['HTTP_CLIENT_IP']) || $ip = $_SERVER['HTTP_CLIENT_IP'];
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ips = explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
			if($ip){
				array_unshift($ips,$ip);
				$ip = false;
			}
			$count = count($ips);
			for($i=0;$i!=$count;$i++) {
				if(!preg_match('^(10│172.16│192.168).',$ips[$i])){
					$ip = $ips[$i];
					break;
				}
			}
		}
		return $ip ?: $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * 检查端口是否开放
	 * @param $ip   string  ip地址
	 * @param $port integer 端口号
	 * @return boolean
	 */
	function check_port($ip,$port){
		return @fsockopen($ip,$port,$errno,$errstr,1) ? true : false;
	}

	/**
	 * 判断是否是绝对路径
	 * @param  $path string 路径
	 * @return boolean
	 */
	function true_path($path){
		if(0 === stripos(PHP_OS,'WIN')) return ':' == substr($path,1,1);
		else return '/' == substr($path,0,1);
	}

	/**
	 * 创建PDO对象
	 * @param  [type]  $c [连接参数：同config配置文件]
	 * @param  boolean $r [强制重连]
	 * @return PDO对象
	 */
	function PDO($c=null,$r=false){
		$c || $c = [
			'DB_HOST'=>\z\z::$ZPHP_CONFIG['DB_HOST'] ?? '127.0.0.1',
			'DB_PORT'=>\z\z::$ZPHP_CONFIG['DB_PORT'] ?? 3306,
			'DB_NAME'=>\z\z::$ZPHP_CONFIG['DB_NAME'],
			'DB_USER'=>\z\z::$ZPHP_CONFIG['DB_USER'],
			'DB_PASS'=>\z\z::$ZPHP_CONFIG['DB_PASS'],
			'DB_CHARSET'=>\z\z::$ZPHP_CONFIG['DB_CHARSET'] ?? 'utf8',
			'DB_PREFIX'=>\z\z::$ZPHP_CONFIG['DB_PREFIX'],
		];
		$dsn = 'mysql:host='.$c['DB_HOST'].';dbname='.$c['DB_NAME'].';port='.$c['DB_PORT'];
		$k = md5($dsn);
		if($r || !isset(\z\z::$ZPHP_PDO[$k])){
			\z\z::$ZPHP_PDO[$k] = null;
			$dsn = 'mysql:host='.$c['DB_HOST'].';dbname='.$c['DB_NAME'].';port='.$c['DB_PORT'];
			$config = [
				\PDO::ATTR_TIMEOUT=>$c['DB_TIMEOUT'] ?? 10,
				\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES ' . ($c['DB_CHARSET'] ?? 'utf8'),
				\PDO::ATTR_EMULATE_PREPARES=>false,//是否模拟预处理
				\PDO::ATTR_STRINGIFY_FETCHES=>false,//是否将数值转换为字符串
				\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION
			];
			empty($c['DB_SSL_KEY']) || $config[\PDO::MYSQL_ATTR_SSL_KEY] = $c['DB_SSL_KEY'];
			empty($c['DB_SSL_CERT']) || $config[\PDO::MYSQL_ATTR_SSL_CERT] = $c['DB_SSL_CERT'];
			empty($c['DB_SSL_CA']) || $config[\PDO::MYSQL_ATTR_SSL_CA] = $c['DB_SSL_CA'];
			\z\z::$ZPHP_PDO[$k] = new \PDO($dsn,$c['DB_USER'],$c['DB_PASS'],$config);
		}
		return \z\z::$ZPHP_PDO[$k];
	}

	/**
	 * 获取Redis对象
	 * @param $db integer 要操作的Redis库
	 * @param $c  array   连接配置
	 * @return Redis对象
	 */
	function R($db=null,$c=null){
		$c || $c = [
			'REDIS_HOST' => \z\z::$ZPHP_CONFIG['REDIS_HOST'] ?? '127.0.0.1',
			'REDIS_PORT' => \z\z::$ZPHP_CONFIG['REDIS_PORT'] ?? 6379,
			'REDIS_TIMEOUT' => \z\z::$ZPHP_CONFIG['REDIS_TIMEOUT'] ?? 1,
			'REDIS_PASS'=> \z\z::$ZPHP_CONFIG['REDIS_PASS'] ?? null,
			'REDIS_DB' => \z\z::$ZPHP_CONFIG['REDIS_DB'] ?? 1,
		];
		$k = "{$c['REDIS_HOST']}{$c['REDIS_PORT']}";
		if(empty(\z\z::$ZPHP_REDIS[$k])){
			\z\z::$ZPHP_REDIS[$k] = new \Redis();
			\z\z::$ZPHP_REDIS[$k]->connect($c['REDIS_HOST'],$c['REDIS_PORT'],$c['REDIS_TIMEOUT']);
			$c['REDIS_PASS'] && \z\z::$ZPHP_REDIS[$k]->auth($c['REDIS_PASS']);
		}
		null === $db && $db = $c['REDIS_DB']??1;
		$db && \z\z::$ZPHP_REDIS[$k]->SELECT($db);
		return \z\z::$ZPHP_REDIS[$k];
	}

	/**
	 * 获取Memcached对象
	 * @param $c  array   连接配置
	 * @return Memcached对象
	 */
	function MEM($c=null){
		$c || $c = \z\z::$ZPHP_CONFIG['MEMCACHED']??[['127.0.0.1','11211']];
		if(!\z\z::$ZPHP_MEMCACHED){
			\z\z::$ZPHP_MEMCACHED = new Memcached();
			\z\z::$ZPHP_MEMCACHED->addServers($c);
		}
		return \z\z::$ZPHP_MEMCACHED;
	}