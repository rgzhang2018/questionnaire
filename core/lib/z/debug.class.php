<?php
	namespace z;
	class debug {
		const ERRTYPE = [2=>'运行警告',8=>'运行提醒',256=>'错误',512=>'警告',1024=>'提醒',2048=>'编码标准化警告',1100=>'文件加载',1101=>'路径错误',1110=>'SQL错误',1120=>'SQL查询',1130=>'常量',1140=>'模板文件',1150=>'模板变量',8192=>'运行通知'];
		private static $starttime,$pdotime=0;
		private static $errs = [];
		static function start(){
			self::$starttime = microtime(true);
		}
		static function pdotime($time){
			self::$pdotime += $time;
		}

		static function setTrace($k,$o){
			$args = '';
			$n = isset($o['args']) ? count($o['args']) : 0;
			if($n){
				for($i=0;$i!=$n;$i++){
					$args .= (is_object($o['args'][$i]) ? get_class($o['args'][$i]) : str_replace(['\\\\','\\/'],['\\','/'],json_encode($o['args'][$i]))).',';
				}
				$args = rtrim($args,',');
			}
			$called = ($o['class'] ?? '') . ($o['type'] ?? '') . "{$o['function']}({$args}) called";
			$str = isset($o['file']) ? "#{$k} {$called} at [{$o['file']} :{$o['line']}]" : "#{$k} {$called}";
			return $str;
		}
		static function ExceptionHandler($e){
			$is_log = defined('ERROR_LOG') && ERROR_LOG;
			$GLOBALS['ZPHP_DEBUG'] || $is_log || \z\controller::_500();
			$traceMsg = [];
			$trace = $e->getTrace();
			$msg = $e->getMessage();
			$file = $e->getFile();
			$line = $e->getLine();
			if($trace){
				foreach($trace as $k=>$v){
					$traceArr[] = self::setTrace($k,$v);
				}
			}
			$err = "{$msg} at [{$file} : {$line}]";
			$traceMsg = implode("\r\n",$traceArr);
			if($is_log){
				$dir = RUN_DIR.ERROR_LOG;
				!is_dir($dir) && mkdir(iconv("UTF-8","GBK",$dir),0755,true);
				$file = "{$dir}/" . date('Ymd') . '.log';
				file_put_contents($file,'['.date('H:i:s')."] {$err}\r\n{$traceMsg}\r\n\r\n",FILE_APPEND);
			}
			$GLOBALS['ZPHP_DEBUG'] || \z\controller::_500();
			if(IS_AJAX){
				$json['ZPHP_ERROR'] = ['message'=>$err,'trace'=>$traceArr];
				$json['ZPHP_DEBUG'] = self::getJsonDebug();
				ob_end_clean();
				header('Content-Type:application/json; charset=utf-8');
				die(json_encode($json,JSON_UNESCAPED_UNICODE));
			}else{
				echo "<div style='background:#FFAEB9;padding:20px;'><h1>ERROR</h1><h2 style='font-size:16px;'>{$err}</h2><pre style='font-size:14px;'>\r\n{$traceMsg}</pre></div>";
				die(self::showMsg());
			}
		}

		static function ErrorHandler($errno, $errstr, $errfile, $errline){
			if(!$GLOBALS['ZPHP_DEBUG']) return;
			!IS_AJAX && $errstr = str_replace('\\','\\\\',$errstr);
			self::$errs[$errno][] = "{$errstr} [" .str_replace('\\','/',$errfile)." ] : {$errline}";
		}
		static function getIncludeFiles(){
			$files = get_included_files();
			foreach($files as $v){
				$file = str_replace('\\','/',$v);
				self::$errs[1100][] = $file . '[ ' . file_size_format(filesize($file)) . ' ]';
			}
		}
		static function getTplArgs(){
			$args = view::getTplArgs();
			if(!$args) return false;
			foreach($args as $k=>$v){
				$str = is_array($v) ? json_encode($v,true,JSON_UNESCAPED_UNICODE) : $v;
				self::$errs[1150][] = "[\${$k}] : {$str}";
			}
		}
		static function getConstants(){
			$const = get_defined_constants(true)['user'];
			foreach($const as $k=>$v){
				$str = is_array($v) ? json_encode($v,true,JSON_UNESCAPED_UNICODE) : $v;
				self::$errs[1130][] = "[{$k}] : {$str}";
			}
		}
		static function getJsonDebug(){
			$json['基本信息'] = [
				'SQL查询耗时'=>round(1000*self::$pdotime,3).'ms',
				'脚本运行时间'=>round(1000*(microtime(true) - self::$starttime),3).'ms',
				'内存使用'=>file_size_format(memory_get_usage()),
				'内存峰值'=>file_size_format(memory_get_peak_usage())
			];
			self::getIncludeFiles();
			self::getConstants();
			self::getTplArgs();
			foreach(self::$errs as $k=>$v){
				$json[self::ERRTYPE[$k]] = $v;
			}
			return $json;
		}
		static function showMsg(){
			if($GLOBALS['ZPHP_DEBUG']){
				$runtime = microtime(true) - self::$starttime;
				$html = $tab = '';
				self::getIncludeFiles();
				self::getConstants();
				self::getTplArgs();
				foreach(self::$errs as $k=>$v){
					$tab .= "<button type=\"button\" id=\"{$k}\" tid=\"{$k}\">".self::ERRTYPE[$k].':['. count($v) .']</button>';
					$html .= "<div id=\"zdebug-li{$k}\"><p># " . implode('</p><p># ',$v) . '</p></div>';
				}
				require CORE . 'tpl/debug.tpl';
			}
			die;
		}
	}