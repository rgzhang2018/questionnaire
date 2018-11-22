<?php
	namespace z;
	class view extends z{
		private static $ZPHP_tplArgs,$ZPHP_tmpFile,$ZPHP_fileStr,$ZPHP_includeFileEditTime;

		/**
		 * 返回分配的模板变量
		 * @return array [description]
		 */
		static function getTplArgs(){
			return self::$ZPHP_tplArgs;
		}

		/**
		 * 添加debug信息
		 */
		private static function ZPHP_setDebug($tpl_file){
			$GLOBALS['ZPHP_DEBUG'] && debug::ErrorHandler(1140,'',$tpl_file,file_size_format(filesize($tpl_file)));
		}

		/**
		 * 获取模板文件路径
		 */
		private static function ZPHP_getTpl_file($r){
			$tpl_file = '';
			if(!$r){
				($file = THEME . CONTROLLER_NAME . '/' . ACTION_NAME . parent::$ZPHP_CONFIG['THEME_SUFFIX']) && is_file($file) && $tpl_file = $file;
			}else{
				$file = $r . (preg_match('/\.\w+$/',$r) ? '' : parent::$ZPHP_CONFIG['THEME_SUFFIX']);
				if(is_file($file)){
					$tpl_file = $file;
				}elseif(strstr($r,'/')){
					$filename_arr = explode('/',$r);
					switch(count($filename_arr)){
						case 4 : $file = ROOT . "{$filename_arr[0]}/public_html/{$filename_arr[1]}/{$filename_arr[2]}/{$filename_arr[3]}";
						break;
						case 3 : $file = VIEW . $file;
						break;
						case 2 : $file = THEME . $file;
						break;
						default : $file =  ROOT . $file;
						break;
					}
				}else{
					$file = THEME . CONTROLLER_NAME . '/' . $file;
				}
				if(is_file($file)) $tpl_file = $file;
			}
			if($tpl_file){
				self::ZPHP_setDebug($tpl_file);
				return $tpl_file;
			}else{
				debug::ErrorHandler(2,'没有找到模板文件',$file,'');
				return false;
			}
		}

		/**
		 * 解析模板
		 * @param  string $r [模板文件]
		 * @return string    [返回渲染结果的字符串]
		 */
		protected static function fetch($r=null){
			$tpl_file = self::ZPHP_getTpl_file($r);
			if(!$tpl_file) return false;
			$GLOBALS['ZPHP_DEBUG'] && self::$ZPHP_fileStr = file_get_contents($tpl_file) && self::ZPHP_include_file(self::$ZPHP_fileStr);		
			self::$ZPHP_tmpFile = RUN_APP . md5($tpl_file) . '.php';
			if(!is_file(self::$ZPHP_tmpFile) || filemtime(self::$ZPHP_tmpFile) < filemtime($tpl_file) || filemtime(self::$ZPHP_tmpFile) < self::$ZPHP_includeFileEditTime){
				if(!self::$ZPHP_fileStr){
					self::$ZPHP_fileStr = file_get_contents($tpl_file);
					self::ZPHP_include_file(self::$ZPHP_fileStr);
				}
				$rep_str = self::tmp_replace(self::$ZPHP_fileStr);
				empty(parent::$ZPHP_CONFIG['HTML_COMMPRESS']) || $rep_str = compress_html($rep_str);
				if(!make_dir(RUN_APP) || false === file_put_contents(self::$ZPHP_tmpFile,$rep_str)){
					throw new \Exception('目录或文件 '.self::$ZPHP_tmpFile.' 不可写，请检查权限');
				}
			}
			$html =  self::ZPHP_toHtml();
			ob_end_clean();
			return $html;
		}
		private static function ZPHP_toHtml(){
			self::$ZPHP_tplArgs && extract(self::$ZPHP_tplArgs);
			ob_start() && require self::$ZPHP_tmpFile;
			return ob_get_contents();
		}

		/**
		 * 渲染模板
		 * @param  string $r [模板文件]
		 * @return [type]    [渲染结果输出到浏览器]
		 */
		protected static function display($r=null){
			echo self::fetch($r);
		}

		/**
		 * 处理模板包含文件
		 * @param  [type] $str       [description]
		 * @param  [type] $edit_time [description]
		 * @return [type]            [description]
		 */
		private static function ZPHP_include_file(){
			$suffix = preg_quote(parent::$ZPHP_CONFIG['VIEW_SUFFIX']);
			$prefix = preg_quote(parent::$ZPHP_CONFIG['VIEW_PREFIX']);
			$preg = "#{$prefix}include\s+([\w\.\/-]+){$suffix}#";
			if(preg_match($preg,self::$ZPHP_fileStr)){
				self::$ZPHP_fileStr = preg_replace_callback($preg,function($match){
					$path = str_replace(['ROOT/','APP/','COMMON/','VIEW/','THEME/','RES/','PUB/'],[ROOT,APP,COMMON,VIEW,THEME,RES,PUB],$match[1]);
					if(!true_path($path)){
						$arr = explode('/',$match[1]);
						switch(count($arr)){
							case 1:
								$file = THEME . CONTROLLER_NAME . '/' . $match[1];
								break;
							case 2:
								$file = THEME . $match[1];
								break;
							case 3:
								$file = VIEW . $match[1];
								break;
							default :
								$file = ROOT . $match[1];
								break;
						}
					}else{
						$file = $path;
					}
					if(is_file($file)){
						$GLOBALS['ZPHP_DEBUG'] && debug::ErrorHandler(1140,'',$file,file_size_format(filesize($file)));
						self::$ZPHP_includeFileEditTime[] = filemtime($file);
						return file_get_contents($file);
					}else{
						debug::ErrorHandler(2,'没有找到模板文件',$file,'');
						return "<b style=\"color:red;\">模板文件不存在：{$file}</b>";
					}
				},self::$ZPHP_fileStr);
				self::ZPHP_include_file();
			}elseif(self::$ZPHP_includeFileEditTime){
				sort(self::$ZPHP_includeFileEditTime);
		 		self::$ZPHP_includeFileEditTime = end(self::$ZPHP_includeFileEditTime);
		 	}
		}

		/**
		 * 解析模板变量和操作
		 * @param  [type] $str [description]
		 * @return [type]      [description]
		 */
		private static function tmp_replace($str){
			$pre = parent::$ZPHP_CONFIG['VIEW_PREFIX'];
			$suf = parent::$ZPHP_CONFIG['VIEW_SUFFIX'];
			$keys = [
				'__ROOT__' => '<?php echo rtrim(__ROOT__,"/"); ?>',
				'__PUBLIC__' => '<?php echo __PUBLIC__; ?>',
				'__RES__' => '<?php echo __RES__; ?>',
				"{$pre}if %%{$suf}" => '<?php if (\1): ?>',
				"{$pre}elseif %%{$suf}" => '<?php ; elseif (\1): ?>',
                "{$pre}for %%{$suf}" => '<?php for (\1): ?>',
				"{$pre}foreach %% %%{$suf}" => '<?php if (is_array(\1) && \1): foreach (\1 \2): ?>',
				"{$pre}while %%{$suf}" => '<?php while (\1): ?>',
				"{$pre}/if{$suf}" => '<?php endif; ?>',
				"{$pre}/for{$suf}" => '<?php endfor; ?>',
				"{$pre}/foreach{$suf}" => '<?php endforeach; endif; ?>',
				"{$pre}/while{$suf}" => '<?php endwhile; ?>',
				"{$pre}else{$suf}" => '<?php ; else: ?>',
				"{$pre}continue{$suf}" => '<?php continue; ?>',
				"{$pre}break{$suf}" => '<?php break; ?>',
				"{$pre}$%%||%%{$suf}" => '<?php echo isset($\1)?$\1:\2; ?>',
				"{$pre}:%%{$suf}" => '<?php echo \1; ?>',
				"{$pre}$%%{$suf}" => '<?php echo $\1; ?>',
				"{$pre}%%{$suf}" => '<?php \1; ?>',
            ];
			foreach ($keys as $key=>$val) {
                $patterns[] = '#' . str_replace('%%','(.+)', preg_quote($key, '#')) . '#U';
                $replace[] = $val;
            }
            $filestr = preg_replace($patterns, $replace, $str);
			return $filestr;
		}

		/**
		 * 分配模板变量
		 * @param  [type] $str [分配的变量名]
		 * @param  [type] $var [分配的变量值]
		 * @return [type]      [description]
		 */
		protected static function assign($str,$var){
			self::$ZPHP_tplArgs[$str] = $var;
		}

	}
