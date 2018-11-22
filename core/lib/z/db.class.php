<?php
	namespace z;
	class db extends sql{
		protected $DB_OBJ,$DB_CONFIG,$DB_CACHE,$DB_CACHEMOD,$DB_QUERYMOD,$DB_MOD;
		function DB_init($table,$c){
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
			$table && $this->table($table);
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
			return $this;
		}
		protected function DB_obj(){
			$this->DB_OBJ[$this->DB_QUERYMOD] ?? $this->DB_OBJ[$this->DB_QUERYMOD] = PDO($this->DB_CONFIG[$this->DB_QUERYMOD]??$this->DB_CONFIG[0]);
			return $this->DB_OBJ[$this->DB_QUERYMOD];
		}
		/**
		 * 执行PDO操作
		 */
		protected function DB_fetchResult($type=1,$fetch=null,$bind=true,$en=0){
			try{
				$pre = $this->DB_obj()->prepare($this->DB_SQL);
				if(!$pre){
					throw new \PDOException('',50000);
				}
				if(!$pre->execute($bind ? $this->DB_BINDVAL : null)){
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
					case 1054:
						if(!$en && preg_match('/COUNT\s*\([\s\S]+\)/i',$this->DB_SQL)){
							$this->DB_SQL = 'SELECT COUNT(*) FROM (' . $this->DB_SQL() . ') DB_n';
							return $this->DB_fetchResult($type,$fetch,$bind,1);
						}
					default:
						$this->DB_throwErr($e);
					break;
				}
			}
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
		 * 字段数据求和
		 * @param  [type] $field [description]
		 * @return [type]		[description]
		 */
		public function sum($field){
			return $this->find("SUM({$field})");
		}
		
		/**
		 * 返回最大值
		 * @param  [type] $field [description]
		 * @return [type]		[description]
		 */
		public function max($field){
			return $this->find("MAX({$field})");
		}
		
		/**
		 * 返回最小值
		 * @param  [type] $field [description]
		 * @return [type]		[description]
		 */
		public function min($field){
			return $this->find("MIN({$field})");
		}
		
		/**
		 * 返回平均值
		 * @param  [type] $field [description]
		 * @return [type]		[description]
		 */
		public function avg($field){
			return $this->find("AVG({$field})");
		}
		
		/**
		 * 单独更新某字段的值
		 * @param [type] $field [description]
		 * @param [type] $value [description]
		 */
		public function setField($field,$value){
			return $this->update([$field=>$value]);
		}
		
		public function subQuery($field=null){
			$field && $this->DB_FIELD = $this->DB_setField($field);
			$sql = $this->DB_SQL();
			$this->DB_ORDER = null;
			$this->DB_WHERE = null;
			$this->DB_LOGIC = null;
			$this->DB_JOIN = null;
			$this->DB_DELTABLE = null;
			$this->DB_TABLES = null;
			$this->DB_FIELD = '*';
			$this->DB_HAVING = null;
			$this->DB_GROUP = null;
			$this->DB_LIMIT = null;
			return $sql;
		}

		/**
		 * 多条数据查找
		 * @param  [type] $field [字段名，返回field字段的数据（一维数组）]
		 * @return [type]		[description]
		 */
		public function select($field=null,$lock=false){
			$this->DB_MOD && $this->DB_QUERYMOD = 1;
			$starttime = microtime(true);
			if($field){
				$this->DB_FIELD = $this->DB_setField($field);
				$this->DB_SQL = $this->DB_SQL();
				$type = \PDO::FETCH_COLUMN;
			}else{
				$this->DB_SQL = $this->DB_SQL();
				$type = \PDO::FETCH_ASSOC;
			}
			if($this->DB_CACHE){
				$result = $this->DB_fetchCache(2,$type,$starttime);
				$this->DB_CACHE = 0;
			}else{
				$lock && $this->DB_SQL .= ' FOR UPDATE';
				$result = $this->DB_fetchResult(2,$type);
				$this->DB_sqlend($starttime);
			}
			$this->DB_cl();
			return $result;
		}

		public function DB_fetchCache($type,$fetch,$starttime){
			$mod = null === $this->DB_CACHEMOD ? (\z\z::$ZPHP_CONFIG['DB_CACHEMOD'] ?? 0) : $this->DB_CACHEMOD;
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
					$key = CACHE_DIR . "DB_CACHE/{$this->DB_SUBNAME}/" . md5($query);
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

		public function cache($s=0,$m=null){
			$this->DB_CACHEMOD = $m;
			$this->DB_CACHE = $s?:60;
			return $this;
		}

		/**
		 * 返回符合条件的记录条数
		 * @return [type] [description]
		 */
		public function count(){
			$this->DB_MOD && $this->DB_QUERYMOD = 1;
			$starttime = microtime(true);
			$this->DB_SQL = $this->DB_SQL('COUNT(*)');
			$num = $this->DB_fetchResult(1,\PDO::FETCH_COLUMN);
			$this->DB_sqlend($starttime);
			$this->DB_cl();
			return $num;
		}

		/**
		 * 获取js分页数据
		 * @param  integer $num   [每页数据量]
		 * @param  integer $total [是否返回总数据量]
		 * @param  integer $page  [当前页码]
		 * @param  integer $max   [限制最大总分页数]
		 * @return [type]		 [description]
		 */
		public function jsPage($num=10,$total=0,$page=0,$max=0){
			$this->DB_PAGE = null;
			$page || ($page = empty($_GET['p']) ? 1 : $_GET['p']);
			if($total){
				$max && $this->limit($max * $num);
				$this->DB_PAGE['nowpage'] = $page;
				$this->DB_SQL = $this->DB_SQL('COUNT(*)');
				$this->DB_PAGE['total'] = $this->DB_fetchResult(1,\PDO::FETCH_COLUMN);
			}
			$start = ($page - 1) * $num;
			return $this->limit($start,$num);
		}

		/**
		 * 数据分页
		 * @param  [type]  $num	  [每页数据量]
		 * @param  integer $pageRoll [返回的最大的分页数量]
		 * @param  boolean $page	 [当前页码]
		 * @return [type]			[description]
		 */
		public function page($num,$pageRoll=10,$page=false){			
			$page || ($page = empty($_GET['p']) ? 1 : $_GET['p']);
			$start = ($page - 1) * $num;
			$stop = $num;
			$this->DB_PAGE = null;
			$starttime = microtime(true);
			$this->DB_SQL = $this->DB_SQL('COUNT(*)');
			$this->DB_PAGE['total'] = $this->DB_fetchResult(1,\PDO::FETCH_COLUMN);
			debug::pdotime(microtime(true) - $starttime);
			$this->DB_PAGE['li'] = [];
			$this->DB_PAGE['pages_num'] = !empty($this->DB_PAGE['total']) ? (INT)ceil($this->DB_PAGE['total'] / $num) : 1;
			$args = $_GET;
			if($this->DB_PAGE['pages_num'] > 1){
				$Pnow = intval($pageRoll / 2);
				if($page > $Pnow && $this->DB_PAGE['pages_num'] > $pageRoll){
					$i = $page - $Pnow;
					$Pend = $i + $pageRoll - 1;
					$Pend > $this->DB_PAGE['pages_num'] && $Pend = $this->DB_PAGE['pages_num'] && $i = $Pend - $pageRoll + 1;
				}else{
					$i = 1;
					$Pend = $pageRoll > $this->DB_PAGE['pages_num'] ? $this->DB_PAGE['pages_num'] : $pageRoll;
				}
				for($i;$i<=$Pend;$i++){
					$args['p'] = $i;
					$this->DB_PAGE['li'][$i] = $page == $i ? 'javascript:;' : U([CONTROLLER_NAME,ACTION_NAME],$args);
				}
			}
			$this->DB_PAGE['nowpage'] = $page;
			if($page > 1){
				$args['p'] = $page - 1;
				$this->DB_PAGE['prev'] = U([CONTROLLER_NAME,ACTION_NAME],$args);
			}else{
				$this->DB_PAGE['prev'] = 'javascript:;';
			}
			if($page < $this->DB_PAGE['pages_num']){
				$args['p'] = $page + 1;
				$this->DB_PAGE['next'] = U([CONTROLLER_NAME,ACTION_NAME],$args);
			}else{
				$this->DB_PAGE['next'] = 'javascript:;';
			}
			$args['p'] = 1;
			$this->DB_PAGE['start'] = $page > 1 ? U([CONTROLLER_NAME,ACTION_NAME],$args) : 'javascript:;';;
			$args['p'] = $this->DB_PAGE['pages_num'];
			$this->DB_PAGE['end'] = $page == $this->DB_PAGE['pages_num'] ? 'javascript:;' : U([CONTROLLER_NAME,ACTION_NAME],$args);
			return $this->limit($start,$stop);
		}

		/**
		 * 受影响的行数
		 * @return [type] [description]
		 */
		private function DB_returnNum(){
			$starttime = microtime(true);
			$this->DB_MOD && $this->DB_QUERYMOD = 0;
			$num = $this->DB_fetchResult(3);
			$this->DB_sqlend($starttime);
			$this->DB_cl();
			return $num;
		}

		/**
		 * 更新数据（不执行自动验证）
		 * @param  [type]  $add  [description]
		 * @param  boolean $safe [description]
		 * @return [type]		[description]
		 */
		public function update($add,$safe=true){
			$update_sql = $this->DB_addUpdate($add,$safe);
			$sql = implode(',',$update_sql);
			$where = $this->DB_sqlWhere();
			$join = $this->DB_JOIN ? ' ' . implode(' ',$this->DB_JOIN) : '';
			$this->DB_SQL = "UPDATE {$this->DB_TABLE}{$join} SET {$sql}{$where}";
			return $this->DB_returnNum();
		}

		/**
		 * 更新数据（执行自动验证）
		 * @param  [type]  $add  [description]
		 * @param  boolean $safe [description]
		 * @return [type]		[description]
		 */
		public function save($add,$safe=true){
			return method_exists($this,'create') && !$this->create($add,'update') ? false : $this->update($add,$safe);
		}

		/**
		 * 字段值自增
		 * @param [type]  $field [description]
		 * @param integer $num   [description]
		 */
		public function setInc($field,$num=1){
			$_field = $this->DB_setField($field);
			$where = $this->DB_sqlWhere();
			$join = $this->DB_JOIN ? ' ' . implode(' ',$this->DB_JOIN) : '';
			$this->DB_SQL = "UPDATE {$this->DB_TABLE}{$join} SET {$_field}={$_field} + {$num}{$where}";
			return $this->DB_returnNum();
		}

		/**
		 * 字段值自减
		 * @param [type]  $field [description]
		 * @param integer $num   [description]
		 */
		public function setDec($field,$num=1){
			$_field = $this->DB_setField($field);
			$where = $this->DB_sqlWhere();
			$join = $this->DB_JOIN ? ' ' . implode(' ',$this->DB_JOIN) : '';
			$this->DB_SQL = "UPDATE {$this->DB_TABLE}{$join} SET {$_field}={$_field} - {$num}{$where}";
			return $this->DB_returnNum();
		}

		/**
		 * 字段值乘以
		 * @param [type]  $field [description]
		 * @param integer $num   [description]
		 */
		public function setMul($field,$num=2){
			$_field = $this->DB_setField($field);
			$where = $this->DB_sqlWhere();
			$join = $this->DB_JOIN ? ' ' . implode(' ',$this->DB_JOIN) : '';
			$this->DB_SQL = "UPDATE {$this->DB_TABLE}{$join} SET {$_field}={$_field} * {$num}{$where}";
			return $this->DB_returnNum();
		}

		/**
		 * 字段值除以
		 * @param [type]  $field [description]
		 * @param integer $num   [description]
		 */
		public function setDiv($field,$num=2){
			$_field = $this->DB_setField($field);
			$where = $this->DB_sqlWhere();
			$join = $this->DB_JOIN ? ' ' . implode(' ',$this->DB_JOIN) : '';
			$this->DB_SQL = "UPDATE {$this->DB_TABLE}{$join} SET {$_field}={$_field} / {$num}{$where}";
			return $this->DB_returnNum();
		}
		
		/**
		 * 数据删除
		 * @param  string $table [要执行删除操作的表别名，没有where条件时，需指定此参数为ALL来删除全部数据]
		 * @return [type]		[受影响行数]
		 */
		public function delete($table=''){
			$deleteAll = false;
			if(is_array($table)){
				empty($table['all']) && empty($table['ALL']) && $deleteAll = true;
				$table[0] && $this->DB_DELTABLE = $table[0];
			}else{
				if('all' == $table || 'ALL' == $table){
					$deleteAll = true;
				}else{
					$this->DB_DELTABLE = $table;
				}
			}
			$where = $this->DB_sqlWhere();
			if(!$where && !$deleteAll){
				throw new \PDOException("delete all of table[{$this->DB_TABLE}] data? use delete('all')");
			}
			$join = $this->DB_JOIN ? ' ' . implode(' ',$this->DB_JOIN) : '';
			$this->DB_SQL = "DELETE {$this->DB_DELTABLE} FROM {$this->DB_TABLE}{$join}{$where}";
			return $this->DB_returnNum();
		}

		/**
		 * 插入数据（不执行自动验证）
		 * @param  [array]  $add  [字段名=>值]
		 * @param  boolean  $safe [安全模式：是否转译特殊字符]
		 * @return [type]		 [新插入数据的主键值，没有主键返回true]
		 */
		public function insert($add,$safe=true){
			$add_sql = $this->DB_addUpdate($add,$safe);
			$sql = implode(',',$add_sql);
			$this->DB_MOD && $this->DB_QUERYMOD = 0;
			$this->DB_SQL = "INSERT INTO {$this->DB_TABLE} SET {$sql}";
			$starttime = microtime(true);
			$this->DB_fetchResult(0);
			$id = $this->DB_OBJ[$this->DB_QUERYMOD]->lastInsertId();
			$this->DB_sqlend($starttime);
			$this->DB_cl();
			return $id ?: true;
		}

		/**
		 * 有则更新，无则插入（该操作不执行自动验证，请自行检查数据合法性）
		 * @param  [type]  $add	[执行插入操作的数据]
		 * @param  [type]  $update [执行更新操作的数据]
		 * @param  boolean $safe   [安全模式：是否转译特殊字符]
		 * @return [type]		  [插入数据时返回主键值，更新数据时返回-1，无影响时返回0]
		 */
		public function ifInsert($add,$update=null,$safe=true){
			$add_sql = $this->DB_addUpdate($add,$safe);
			$add_sql = implode(',',$add_sql);
			$update_sql = $this->DB_addUpdate($update ?: $add,$safe);
			$update_sql = implode(',',$update_sql);
			$this->DB_MOD && $this->DB_QUERYMOD = 0;
			$this->DB_SQL = "INSERT INTO {$this->DB_TABLE} SET {$add_sql} ON DUPLICATE KEY UPDATE {$update_sql}";
			$starttime = microtime(true);
			$num = $this->DB_fetchResult(3);
			$this->DB_sqlend($starttime);
			$this->DB_cl();
			if(!$num){
				return 0;
			}else{
				return 1 == $num ? ($this->DB_OBJ[$this->DB_QUERYMOD]->lastInsertId() ?: true) : -1;
			}
		}

		/**
		 * 插入数据（执行自动验证）
		 * @param [type]  $add  [字段名=>值]
		 * @param boolean $safe [安全模式：是否转译特殊字符]
		 * @return [type]	   [新插入数据的主键值，没有主键返回true]
		 */
		public function add($add,$safe=true){
			return method_exists($this,'create') && !$this->create($add,'add') ? false : $this->insert($add,$safe);
		}

		/**
		 * 单条数据查找
		 * @param  [type]  $field [字段名：返回此字段的值，字符串]
		 * @return [array]		[一维数组]
		 */
		public function find($field=null,$lock=false){
			$this->DB_MOD && $this->DB_QUERYMOD = 1;
			$starttime = microtime(true);
			if($field){
				$this->DB_FIELD = $this->DB_setField($field);
				$this->DB_SQL = $this->DB_SQL();
				$type = \PDO::FETCH_COLUMN;
			}else{
				$this->DB_SQL = $this->DB_SQL();
				$type = \PDO::FETCH_ASSOC;
			}
			if($this->DB_CACHE){
				$result = $this->DB_fetchCache(1,$type,$starttime);
				$this->DB_CACHE = 0;
			}else{
				$lock && $this->DB_SQL .= ' FOR UPDATE';
				$result = $this->DB_fetchResult(1,$type);
				$this->DB_sqlend($starttime);
			}
			$this->DB_cl();
			return $result;
		}

		/**
		 * 返回数据库的所有表名
		 * @return [type] [description]
		 */
		public function getTables(){
			$this->DB_SQL = "show tables";
			$starttime = microtime(true);
			$result = $this->DB_fetchResult(2,\PDO::FETCH_COLUMN,false);
			$this->DB_sqlend($starttime);
			return $result;
		}

		/**
		 * 返回表的所有字段名
		 * @return [type] [description]
		 */
		public function getFields(){
			if(!isset($this->DB_FIELDS[$this->DB_SUBNAME])){
				$this->DB_SQL = "DESC {$this->DB_PREFIX}{$this->DB_SUBNAME}";
				$starttime = microtime(true);
				$list = $this->DB_fetchResult(2,\PDO::FETCH_ASSOC,false);
				$this->DB_sqlend($starttime);
				if($list){
					foreach($list as $v){
						$this->DB_FIELDS[$this->DB_SUBNAME][] = $v['Field'];
						if('PRI' == $v['Key']) $this->DB_PRIKEY[$this->DB_SUBNAME] = $v['Field'];
					}
				}
			}
			return $this->DB_FIELDS[$this->DB_SUBNAME];
		}

		public function getPrimaryKey(){
			$this->DB_PRIKEY[$this->DB_SUBNAME] ?? $this->getFields();
			return $this->DB_PRIKEY[$this->DB_SUBNAME];
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

		function queryAll($sql,$bind=null){
			$this->DB_MOD && $this->DB_QUERYMOD = 1;
			$starttime = microtime(true);
			$this->DB_SQL = $sql;
			$bind && $this->DB_BINDVAL = $bind;
			if($this->DB_CACHE){
				$result = $this->DB_fetchCache(2,\PDO::FETCH_ASSOC,$starttime);
				$this->DB_CACHE = 0;
			}else{
				$result = $this->DB_fetchResult(2,\PDO::FETCH_ASSOC);
				$this->DB_sqlend($starttime);
			}
			return $result;
		}

		function queryOne($sql,$bind=null){
			$this->DB_MOD && $this->DB_QUERYMOD = 1;
			$starttime = microtime(true);
			$this->DB_SQL = $sql;
			$bind && $this->DB_BINDVAL = $bind;
			if($this->DB_CACHE){
				$result = $this->DB_fetchCache(1,\PDO::FETCH_ASSOC,$starttime);
				$this->DB_CACHE = 0;
			}else{
				$result = $this->DB_fetchResult(1,\PDO::FETCH_ASSOC);
				$this->DB_sqlend($starttime);
			}
			return $result;
		}

		function queryFields($sql,$bind=null){
			$this->DB_MOD && $this->DB_QUERYMOD = 1;
			$starttime = microtime(true);
			$this->DB_SQL = $sql;
			$bind && $this->DB_BINDVAL = $bind;
			if($this->DB_CACHE){
				$result = $this->DB_fetchCache(2,\PDO::FETCH_COLUMN,$starttime);
				$this->DB_CACHE = 0;
			}else{
				$result = $this->DB_fetchResult(2,\PDO::FETCH_COLUMN);
				$this->DB_sqlend($starttime);
			}
			return $result;
		}

		function queryField($sql,$bind=null){
			$this->DB_MOD && $this->DB_QUERYMOD = 1;
			$starttime = microtime(true);
			$this->DB_SQL = $sql;
			$bind && $this->DB_BINDVAL = $bind;
			if($this->DB_CACHE){
				$result = $this->DB_fetchCache(1,\PDO::FETCH_COLUMN,$starttime);
				$this->DB_CACHE = 0;
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
			$pre = $this->DB_fetchResult(0);
			switch(strtoupper(substr($sql,0,6))){
				case 'INSERT':
					$num = $pre->rowCount();
					if(preg_match('/\s+ON\s+DUPLICATE\s+KEY\s+UPDATE\s+/i',$sql)){
						$r = $num ? (1 == $num ? ($this->DB_OBJ[$this->DB_QUERYMOD]->lastInsertId() ?: true) : -1) : 0;
					}else{
						$r = $this->DB_OBJ[$this->DB_QUERYMOD]->lastInsertId() ?: true;
					}
				break;
				case 'UPDATE':
				case 'DELETE':
					$r = $pre->rowCount();
				break;
				default:
					$r = $pre;
				break;
			}
			$this->DB_sqlend($starttime);
			return $r;
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