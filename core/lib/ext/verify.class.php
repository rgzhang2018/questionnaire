<?php
/**
 * 自动验证类
 * namespace m;
 * use \ext\verify;
 * class user extends verify{
 * 		protected $roles = array(
 * 			'username'=>array(
 * 				'unique'=>array('该用户已被注册！'),
 * 				'length'=>array(3,8,'长度必须在3-8之间'),
 * 				'notnull'=>array('用户名必填！'),
 * 				'must'=>true,//不管是否存在此字段都必须验证
 * 			),
 * 			'age' => array(
 *				'myfun'=>array('checkage','年龄不符合要求'),
 *			),
 *			'password' => array('eq'=>array('repassword','密码不一致！'),
 * 		);
 * 		protected function checkage($value,$key){
 * 			if($value < 1 || $value > 100) return false;
 * 			else return true;
 * 		}
 * }
 */
	namespace ext;
	use \z\db;
	class verify extends db{
		private $verType;
		protected $returnMod=1;//0:验证完所有字段后返回，1：某字段验证失败后返回，2：某规则验证失败后返回
		/**
		 * [create 主动验证]
		 * @param  array  $data [待验证的数据]
		 * @param  string  $type ['add':添加数据时验证,'update':更新数据时验证,'both':添加和更新数据时都验证]
		 * @return boolean
		 */
		public function create($data,$type='both',$roles=null){
			if(!$roles) $roles = $this->roles;
			if(!$roles) return true;
			$this->verType = $type ?: 'type';
			$i = 0;
			foreach($roles as $key=>$role){
				empty($role['eq']) || $role['eq'][0] = $data[$role['eq'][0]];
				if(isset($role['must'])){
					$must = $role['must'];
					unset($role['must']);
				}else{
					$must = false;
				}
				if(!isset($data[$key]) && !$must){
					$i++;//如果提交的数据中不存在需要验证的字段&&该字段必须验证规则(must)为假
				}elseif($this->check_role($key,$role,$data[$key])){
					$i++;
				}elseif($this->returnMod){
					return false;
				}
			}
			return $i == count($roles);
		}
		
		private function check_role($key,$role,$value){
			$i = 0;
			foreach($role as $k=>$v){
				$checkName =  'check_' . $k;
				if($this->$checkName($key,$value,$v)){
					$i ++;
				}elseif(2 == $this->returnMod){
					return false;
				}
			}
			return $i == count($role);
		}
		/**
		 * [check_myfun 自定义验证方法]
		 * @param  string $key   [待验证字段]
		 * @param  string $value [待验证的值]
		 * @param  array  $msg   [$msg[0]:方法名,$msg[1]:提示信息,$msg[2]:验证时机]
		 * @return boolean
		 */
		private function check_myfun($key,$value,$msg){
			$type = empty($msg[2]) ? 'both' : $msg[2];
			if($type != $this->verType && $type != 'both') return true;
			$result = call_user_func(array($this,$msg[0]),$value,$key);
			if(!$result) $this->DB_ERROR[] = $msg[1];
			return $result;
		}
		/**
		 * [check_unique 验证唯一]
		 * @param  string $key   [待验证字段]
		 * @param  string $value [待验证的值]
		 * @param  array  $msg   [$msg[0]:提示信息,$msg[1]:验证时机]
		 * @return boolean
		 */
		private function check_unique($key,$value,$msg){
			$type = empty($msg[1]) ? 'both' : $msg[1];
			if($type != $this->verType && $type != 'both') return true;
			$bind_key = ':' . $key;
			$bind[$bind_key] = $value;
			$sql = 'SELECT * FROM ' . $this->DB_TABLE . " WHERE `{$key}`={$bind_key}";
			$pre = $this->DB_obj()->prepare($sql);
			$pre->execute($bind);
			$data = $pre->fetch(\PDO::FETCH_ASSOC);
			if(!$data || ('update' == $this->verType && $data[$key] == $value)) return true;
			$this->DB_ERROR[] = $msg[0];
			return false;
		}
		/**
		 * [check_length 验证字符串长度]
		 * @param  string $key   [待验证字段]
		 * @param  string $value [待验证的值]
		 * @param  array  $msg   [$msg[0]:下限,$msg[1]:上限,$msg[2]:提示信息,$msg[3]:验证时机]
		 * @param  array  $msg   [$msg[0]:长度值(验证长度相等),$msg[1]:提示信息,$msg[2]:验证时机]
		 * @return boolean
		 */
		private function check_length($key,$value,$msg){
        	$length = mb_strlen($value);
			if(is_numeric($msg[1])){
				$type = empty($msg[3]) ? 'both' : $msg[3];
				if($type != $this->verType && $type != 'both') return true;
				$min = $msg[0];
				$max = $msg[1];
				$msg = $msg[2];
				if($length < $min || $length > $max){
					$this->DB_ERROR[] = $msg;
					$result = false;
				}else{
					$result = true;
				}
			}else{
				$type = $msg[2] ? $msg[2] : 'both';
				if($type != $this->verType && $type != 'both') return true;
				$str_length = $msg[0];
				$msg = $msg[1];
				if($length != $str_length){
					$this->DB_ERROR[] = $msg;
					$result = false;
				}else{
					$result = true;
				}
			}
			return $result;
		}
		/**
		 * [check_notnull 验证非空]
		 * @param  string $key   [待验证字段]
		 * @param  string $value [待验证的值]
		 * @param  array  $msg   [$msg[0]:提示信息,$msg[1]:验证时机]
		 * @return boolean
		 */
		private function check_notnull($key,$value,$msg){
			$type = $msg[1] ?? 'both';
			if($type != $this->verType && $type != 'both') return true;
			if(strlen($value)) return true;
			$this->DB_ERROR[] = $msg[0];
			return false;
		}
		/**
		 * [check_email 验证电子邮件]
		 * @param  string $key   [待验证字段]
		 * @param  string $value [待验证的值]
		 * @param  array  $msg   [$msg[0]:提示信息,$msg[1]:验证时机]
		 * @return boolean
		 */
		private function check_email($key,$value,$msg){
			$type = $msg[1] ? $msg[1] : 'both';
			if($type != $this->verType && $type != 'both') return true;
			$preg = '/^[\w\.\-]+@[\w\-]+(\.\w+)+$/';
			if(preg_match($preg,$value)){
				return true;
			}else{
				$this->DB_ERROR[] = $msg[0];
				return false;
			}
		}
		/**
		 * [check_number 验证数字]
		 * @param  string $key   [待验证字段]
		 * @param  string $value [待验证的值]
		 * @param  array  $msg   [$msg[0]:提示信息,$msg[1]:验证时机]
		 * @return boolean
		 */
		private function check_number($key,$value,$msg){
			$type = $msg[1] ? $msg[1] : 'both';
			if($type != $this->verType && $type != 'both') return true;
			if(is_numeric($value)){
				return true;
			}else{
				$this->DB_ERROR[] = $msg[0];
				return false;
			}
		}
		/**
		 * [check_eq 验证相等]
		 * @param  string $key   [待验证字段]
		 * @param  string $value [待验证的值]
		 * @param  array  $msg   [$msg[0]:要比较的值,$msg[1]:提示信息,$msg[2]:验证时机]
		 * @return boolean
		 */
		private function check_eq($key,$value,$msg){
			$type = $msg[2] ? $msg[2] : 'both';
			if($type != $this->verType && $type != 'both') return true;
			if($value == $msg[0]){
				return true;
			}else{
              	$msg = $msg[1];
				$this->DB_ERROR[] = $msg;
				return false;
			}
		}
		/**
		 * [check_between 验证区间]
		 * @param  string $key   [待验证字段]
		 * @param  string $value [待验证的值]
		 * @param  array  $msg   [$msg[0]:下限,$msg[1]:上限,$msg[2]:提示信息,$msg[3]:验证时机]
		 * @return boolean
		 */
		private function check_between($key,$value,$msg){
			$type = $msg[3] ? $msg[3] : 'both';
			if($type != $this->verType && $type != 'both') return true;
			$min = $msg[0];
			$max = $msg[1];
			$msg = $msg[2];
			if($value < $min || $value > $max){
				$this->DB_ERROR[] = $msg;
				return false;
			}else{
				return true;
			}
		}
		/**
		 * [check_preg 验证正则]
		 * @param  string $key   [待验证字段]
		 * @param  string $value [待验证的值]
		 * @param  array  $msg   [$msg[0]:正则表达式,$msg[1]:提示信息,$msg[2]:验证时机]
		 * @return boolean
		 */
		private function check_preg($key,$value,$msg){
			$type = $msg[2] ? $msg[2] : 'both';
			if($type != $this->verType && $type != 'both') return true;
			$preg = $msg[0];
			$msg = $msg[1];
			if(preg_match($preg,$value)){
				return true;
			}else{
				$this->DB_ERROR[] = $msg;
				return false;
			}
		}
	}