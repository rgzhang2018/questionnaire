<?php
	namespace z;
	class controller extends view{
		/**
		 * 页面跳转
		 * @param  [type] $url [description]
		 * @return [type]      [description]
		 */
		protected static function redirect($url=null){
			!$url && $url = U('/');
			header("Content-type: text/html; charset=utf-8");
			header("Location:" . $url);
			die;
		}

		/**
		 * 操作成功：提示信息并跳转
		 * @param  string  $msg      [description]
		 * @param  [type]  $jump_url  [description]
		 * @param  integer $wait_time [description]
		 * @return [type]             [description]
		 */
		protected static function success($msg='操作成功！',$jump_url=null,$wait_time=0){
			self::ZPHP_jump($msg,1,$jump_url,$wait_time);
		}

		/**
		 * 操作失败：提示信息并跳转
		 * @param  string  $msg      [description]
		 * @param  [type]  $jump_url  [description]
		 * @param  integer $wait_time [description]
		 * @return [type]             [description]
		 */
		protected static function error($msg='操作失败！',$jump_url=null,$wait_time=0){
			self::ZPHP_jump($msg,0,$jump_url,$wait_time);
		}

		private static function ZPHP_jump($msg,$status,$jump_url,$wait_time){
			!$wait_time && $wait_time = $status ? 1 : 3;
			if(IS_AJAX){
				$data['wait_time'] = $wait_time;
				$data['info'] = $msg;
				$data['status'] = $status;
				$data['url'] = $jump_url;
				self::json($data);
			}else{
				$jump_url || $jump_url = $_SERVER["HTTP_REFERER"];
				$tpl = THEME . 'jump.tpl';
				!is_file($tpl) && $tpl = CORE . 'tpl/jump.tpl';
				require $tpl;
				die;
			}
		}

		/**
		 * 输出json格式数据（脚本中断）
		 * @param  [type] $data [description]
		 * @return [type]       [description]
		 */
		protected static function json($data=null){
			$GLOBALS['ZPHP_DEBUG'] && $data['ZPHP_DEBUG'] = \z\debug::getJsonDebug();
			ob_end_clean();
			header('Content-Type:application/json; charset=utf-8');
			die(json_encode($data,JSON_UNESCAPED_UNICODE));
		}

		/*
		 * 显示404页面
		 */
		public static function _404($errMsg='404，您请求的文件不存在！',$file=null){
			if($file){
				$tpl = strstr($file,'/') ? $file : THEME . $file;
				is_file($tpl) || $tpl = ROOT . $file;
			}else{
				$tpl = THEME . '404.html';
				is_file($tpl) || is_file($tpl = ROOT . '404.html') || $tpl = CORE . 'tpl/404.tpl';
			}
			ob_end_clean();
			require $tpl;
			die;
		}

		/*
		 * 显示500页面
		 */
		public static function _500($errMsg='500，出错啦！',$file=null){
			if($file){
				$tpl = strstr($file,'/') ? $file : THEME . $file;
				is_file($tpl) || $tpl = ROOT . $file;
			}else{
				$tpl = THEME . '500.html';
				is_file($tpl) || is_file($tpl = ROOT . '500.html') || $tpl = CORE . 'tpl/500.tpl';
			}
			ob_end_clean();
			require $tpl;
			die;
		}
	}