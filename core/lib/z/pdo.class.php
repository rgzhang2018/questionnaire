<?php
	namespace z;
	class pdo{
		private $DB_SQL,$DB_BINDVAL,$DB_OBJ,$DB_CONFIG,$DB_CACHE,$DB_CACHEMOD,$DB_QUERYMOD,$DB_MOD,$DB_PREFIX;
		function __construct($c=null){
			$c || $c = [
				'DB_HOST'=>\z\z::$ZPHP_CONFIG['DB_HOST'] ?? '127.0.0.1',
				'DB_PORT'=>\z\z::$ZPHP_CONFIG['DB_PORT'] ?? 3306,
				'DB_NAME'=>\z\z::$ZPHP_CONFIG['DB_NAME'],
				'DB_USER'=>\z\z::$ZPHP_CONFIG['DB_USER'],
				'DB_PASS'=>\z\z::$ZPHP_CONFIG['DB_PASS'],
				'DB_CHARSET'=>\z\z::$ZPHP_CONFIG['DB_CHARSET'] ?? 'utf8',
				'DB_PREFIX'=>\z\z::$ZPHP_CONFIG['DB_PREFIX'],
				'DB_MOD'=>\z\z::$ZPHP_CONFIG['DB_MOD']??0,
			];
			$this->DB_QUERYMOD = 0;
			$this->DB_MOD = $c['DB_MOD']??0;
			$this->DB_PREFIX = $c['DB_PREFIX']??'';
			if(is_array($c['DB_HOST'])){
				$this->DB_CONFIG[0] = [
					'DB_HOST'=>$c['DB_HOST'][0],
					'DB_PORT'=>is_array($c['DB_PORT']) ? $c['DB_PORT'][0] : $c['DB_PORT'],
					'DB_NAME'=>is_array($c['DB_NAME']) ? $c['DB_NAME'][0] : $c['DB_NAME'],
					'DB_USER'=>is_array($c['DB_USER']) ? $c['DB_USER'][0] : $c['DB_USER'],
					'DB_PASS'=>is_array($c['DB_PASS']) ? $c['DB_PASS'][0] : $c['DB_PASS'],
					'DB_CHARSET'=>$c['DB_CHARSET'] ?? 'utf8'
				];
				$count = count($c['DB_HOST']);
				if($this->DB_MOD && 1 < $count){
					$rand = rand(1,$count-1);
					$this->DB_CONFIG[1] = [
						'DB_HOST'=>$c['DB_HOST'][$rand],
						'DB_PORT'=>is_array($c['DB_PORT']) ? $c['DB_PORT'][$rand] : $c['DB_PORT'],
						'DB_NAME'=>is_array($c['DB_NAME']) ? $c['DB_NAME'][$rand] : $c['DB_NAME'],
						'DB_USER'=>is_array($c['DB_USER']) ? $c['DB_USER'][$rand] : $c['DB_USER'],
						'DB_PASS'=>is_array($c['DB_PASS']) ? $c['DB_PASS'][$rand] : $c['DB_PASS'],
						'DB_CHARSET'=>$c['DB_CHARSET'] ?? 'utf8'
					];
				}
			}else{
				$this->DB_CONFIG[0] = $c;
			}
		}
		public function getPrefix(){
			return $this->DB_PREFIX;
		}
		protected function DB_obj(){
			$this->DB_OBJ[$this->DB_QUERYMOD] ?? $this->DB_OBJ[$this->DB_QUERYMOD] = PDO($this->DB_CONFIG[$this->DB_QUERYMOD]??$this->DB_CONFIG[0]);
			return $this->DB_OBJ[$this->DB_QUERYMOD];
		}
		/**
		 * 执行PDO操作
		 */
		private function DB_fetchResult($type=1,$fetch=null){
			try{
				$pre = $this->DB_obj()->prepare($this->DB_SQL);
				if(!$pre){
					throw new \PDOException('',50000);
				}
				if(!$pre->execute($this->DB_BINDVAL)){
					throw new \PDOException('',50000);
				}
				switch($type){
					case 0:
						$result = $pre;
						break;
					case 1:
						$result = $pre->fetch($fetch);
						break;
					case 2:
						$result = $pre->fetchAll($fetch);
						break;
					case 3:
						$result = $pre->rowCount();
						break;
				}
				return $result;
			}catch(\PDOException $e){
				switch($e->errorInfo[1]){
					case 2006:
					case 2013:
					case 50000:
						$this->DB_OBJ[$this->DB_QUERYMOD] = PDO($this->DB_CONFIG[$this->DB_QUERYMOD]??$this->DB_CONFIG[0],true);
						return $this->DB_fetchResult($type,$fetch);
					break;
					default:
						$this->DB_throwErr($e);
					break;
				}
			}
		}

		/**
		 * 设置缓存时间
		 * @param  integer $s [秒]
		 * @param  integer $m [null:系统配置,0:文件缓存,1:redis,2:memcached]
		 * @return [type]     [description]
		 */
		public function cache($s=0,$m=null){
			$this->DB_CACHEMOD = $m;
			$this->DB_CACHE = $s?:60;
			return $this;
		}

		/**
		 * 抛出异常
		 */
		private function DB_throwErr($e){
			$sql = preg_replace('/\s/',' ',$this->DB_SQL);
			$msg = $e->getMessage();
			$err_msg = "SQL:{$sql}; MESSAGE:{$msg}";
			debug::ErrorHandler(1120,"{$sql}; ",trim(json_encode($this->DB_BINDVAL,JSON_UNESCAPED_UNICODE),'{}'),'error');
			throw new \PDOException($err_msg, (int)$e->getCode());
		}

		/**
		 * 开始事务处理
		 * @return [type] [description]
		 */
		function begin(){
			try{
				return $this->DB_obj()->beginTransaction();
			}catch(\PDOException $e){
				switch($e->errorInfo[1]){
					case 2006:
					case 2013:
					case 50000:
						$this->DB_OBJ[$this->DB_QUERYMOD] = PDO($this->DB_CONFIG[$this->DB_QUERYMOD]??$this->DB_CONFIG[0],true);
						return $this->begin();
					break;
					default:
						$this->DB_throwErr($e);
					break;
				}
			}
		}
		/**
		 * 提交事务
		 * @return [type] [description]
		 */
		function commit(){
			$result = $this->DB_OBJ[$this->DB_QUERYMOD]->commit();
			return $result;
		}
		/**
		 * 回滚
		 * @return [type] [description]
		 */
		function rollback(){
			return $this->DB_OBJ[$this->DB_QUERYMOD]->rollback();
		}
		
		/**
		 * 查询多行
		 * @param  string $sql  [SQL语句]
		 * @param  array  $bind [绑定的参数]
		 * @return array
		 */
		function queryAll($sql,$bind=null){
			$this->DB_MOD && $this->DB_QUERYMOD = 1;
			$starttime = microtime(true);
			$this->DB_SQL = $sql;
			$bind && $this->DB_BINDVAL = $bind;
			if($this->DB_CACHE){
				$result = $this->DB_fetchCache(2,\PDO::FETCH_ASSOC,$starttime);
			}else{
				$result = $this->DB_fetchResult(2,\PDO::FETCH_ASSOC);
				$this->DB_sqlend($starttime);
			}
			return $result;
		}
		/**
		 * 查询一行
		 * @param  string $sql  [SQL语句]
		 * @param  array  $bind [绑定的参数]
		 * @return array
		 */
		function queryOne($sql,$bind=null){
			$this->DB_MOD && $this->DB_QUERYMOD = 1;
			$starttime = microtime(true);
			$this->DB_SQL = $sql;
			$bind && $this->DB_BINDVAL = $bind;
			if($this->DB_CACHE){
				$result = $this->DB_fetchCache(1,\PDO::FETCH_ASSOC,$starttime);
			}else{
				$result = $this->DB_fetchResult(1,\PDO::FETCH_ASSOC);
				$this->DB_sqlend($starttime);
			}
			return $result;
		}

		/**
		 * 查询某一列
		 * @param  string $sql  [SQL语句]
		 * @param  array  $bind [绑定的参数]
		 * @return array
		 */
		function queryFields($sql,$bind=null){
			$this->DB_MOD && $this->DB_QUERYMOD = 1;
			$starttime = microtime(true);
			$this->DB_SQL = $sql;
			$bind && $this->DB_BINDVAL = $bind;
			if($this->DB_CACHE){
				$result = $this->DB_fetchCache(2,\PDO::FETCH_COLUMN,$starttime);
			}else{
				$result = $this->DB_fetchResult(2,\PDO::FETCH_COLUMN);
				$this->DB_sqlend($starttime);
			}
			return $result;
		}

		/**
		 * 查询一行中的某列
		 * @param  string $sql  [SQL语句]
		 * @param  array  $bind [绑定的参数]
		 * @return array
		 */
		function queryField($sql,$bind=null){
			$this->DB_MOD && $this->DB_QUERYMOD = 1;
			$starttime = microtime(true);
			$this->DB_SQL = $sql;
			$bind && $this->DB_BINDVAL = $bind;
			if($this->DB_CACHE){
				$result = $this->DB_fetchCache(1,\PDO::FETCH_COLUMN,$starttime);
			}else{
				$result = $this->DB_fetchResult(1,\PDO::FETCH_COLUMN);
				$this->DB_sqlend($starttime);
			}
			return $result;
		}

		/**
		 * 执行sql语句（写入操作）
		 * @param  string $sql  [SQL语句]
		 * @param  array  $bind [绑定的参数]
		 * @return
		 */
		function submit($sql,$bind=null){
			$bind && $this->DB_BINDVAL = $bind;
			$this->DB_MOD && $this->DB_QUERYMOD = 0;
			$this->DB_SQL = $sql;
			$starttime = microtime(true);
			$num = $this->DB_fetchResult(3);
			$this->DB_sqlend($starttime);
			if('INSERT' == strtoupper(substr($sql,0,6))){
				if(preg_match('/\s+ON\s+DUPLICATE\s+KEY\s+UPDATE\s+/i',$sql)){
					$r = $num ? (1 == $num ? ($this->DB_OBJ[$this->DB_QUERYMOD]->lastInsertId() ?: true) : -1) : 0;
				}else{
					$r = $this->DB_OBJ[$this->DB_QUERYMOD]->lastInsertId() ?: true;
				}
			}else{
				$r = $num;
			}
			return $r;
		}

		/**
		 * 查询缓存
		 * @param [type] $type      [description]
		 * @param [type] $fetch     [description]
		 * @param [type] $starttime [description]
		 */
		private function DB_fetchCache($type,$fetch,$starttime){
			$mod = null === $this->DB_CACHEMOD ? (\z\z::$ZPHP_CONFIG['CACHE_MOD'] ?? 0) : $this->DB_CACHEMOD;
			$query = "{$this->DB_SQL}{$fetch}".$this->DB_getJsonVal();
			switch($mod){
				case 1:
					$key = md5($query);
					$act = 'RC';
				break;
				case 2:
					$key = md5($query);
					$act = 'MC';
				break;
				default:
					$key = CACHE_DIR . 'DB_CACHE/' . md5($query);
					$act = 'FC';
				break;
			}
			$result = $act($key);
			if(false === $result){
				$result = $this->DB_fetchResult($type,$fetch);
				$this->DB_sqlend($starttime);
				$act($key,$result,$this->DB_CACHE);
			}
			return $result;
		}
		private function DB_getJsonVal(){
			return $this->DB_BINDVAL ? trim(json_encode($this->DB_BINDVAL,JSON_UNESCAPED_UNICODE),'{}') : null;
		}
		/**
		 * 统计debug信息
		 * @param  [type] $time [description]
		 * @param  [type] $sql  [description]
		 * @return [type]	   [description]
		 */
		private function DB_sqlend($time){
			if($GLOBALS['ZPHP_DEBUG']){
				$nowtime = microtime(true);
				$qtime = $nowtime - $time;
				debug::pdotime($qtime);
				$sql = $this->DB_SQL;
				$val = $this->DB_getJsonVal();
				$val && $this->DB_BINDVAL = null;
				debug::ErrorHandler(1120,preg_replace('/\s/',' ',$sql).';',$val,round(1000*$qtime,3) . 'ms');
			}
		}
		function __call($func,$args){
			$args = is_array($args) ? implode(',',$args) : $args;
			try{
				return $this->DB_obj()->$func($args);
			}catch(\PDOException $e){
				switch($e->errorInfo[1]){
					case 2006:
					case 2013:
					case 50000:
						$this->DB_OBJ[$this->DB_QUERYMOD] = PDO($this->DB_CONFIG[$this->DB_QUERYMOD]??$this->DB_CONFIG[0],true);
						return $this->DB_OBJ->$func($args);
					break;
					default:
						$this->DB_throwErr($e);
					break;
				}
			}
		}

	}