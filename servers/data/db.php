<?php @session_start();
require("select.php");
class DbHelper extends Select
{
	protected $_dbHandle;
	protected $_result;
	/** 中断数据库连接 **/
	function disconnect()
	{
		if ($this->_dbHandle) {
			$this->_dbHandle->close();
			return 0;
		}
		return 1;
	}
	/** 增、删、改通用方法 **/
	function execut($query, $single = 0)
	{
		$this->connect();
		$this->_dbHandle->query("set names utf8mb4"); // Upgraded to utf8mb4 for full compatibility
		$this->_result = $this->_dbHandle->query($query);
		$affected = 0;
		if ($single == 1)
			$affected = $this->_dbHandle->insert_id;
		else
			$affected = $this->_dbHandle->affected_rows;
		return $affected;
	}
	/**  **/
	function query2($query)
	{
		$this->connect();
		$this->_dbHandle->query("set names utf8mb4");
		$this->_result = $this->_dbHandle->query($query);
		if ($this->_dbHandle->errno)
			$this->writeMsg("Sql代码：" . $query . "\n错误码：" . $this->_dbHandle->errno . "\n 错误信息：" . $this->_dbHandle->error . " ");

		if ($this->_result instanceof mysqli_result) {
			$this->_result->free();
		}
	}
	/** 自定义SQL查询语句 **/
	function query($query, $singleResult = 0)
	{
		$this->connect();
		$this->_dbHandle->query("set names utf8mb4");
		$this->_result = $this->_dbHandle->query($query);
		if ($this->_dbHandle->errno)
			$this->writeMsg("Sql代码：" . $query . "\n错误码：" . $this->_dbHandle->errno . "\n 错误信息：" . $this->_dbHandle->error . " ");

		if (!$this->_result || !($this->_result instanceof mysqli_result))
			return (array());

		if (preg_match("/select/i", $query)) {
			$result = array();
			$fieldsMetadata = $this->_result->fetch_fields();
			/*
			 * Replicating legacy behavior:
			 * Grouping results by TableName -> FieldName
			 * $tempResults['TableName']['FieldName'] = value
			 */

			while ($row = $this->_result->fetch_row()) {
				$tempResults = array();
				foreach ($row as $i => $value) {
					$meta = $fieldsMetadata[$i];
					// Clean table name using legacy logic (strip SUFFIX, ucfirst, trim PREFIX)
					$tab = str_replace(SUFFIX, "", $meta->table);
					// Handle edge case where PREFIX is empty string to avoid trimming everything if trim logic was weird, 
					// but standard PHP trim with empty charlist does nothing, so it's safe.
					// If PREFIX is not empty, it trims those chars.
					$tableName = trim(ucfirst($tab), PREFIX);

					if (empty($tableName)) {
						// Fallback if table name is missing (computed columns etc), use generic or logic?
						// Legacy code relied on field_table return.
						// If computed column, table might be empty.
						$tableName = "Unknown";
					}

					$tempResults[$tableName][$meta->name] = $value;
				}

				if ($singleResult == 1) {
					$this->_result->free();
					return $tempResults;
				}
				array_push($result, $tempResults);
			}
			$this->_result->free();
			return ($result);
		}
	}
	/** 连接数据库 **/
	function connect()
	{
		// Use mysqli object oriented style
		// Check if already connected and alive
		if ($this->_dbHandle && $this->_dbHandle->ping()) {
			return 0;
		}

		$this->_dbHandle = new mysqli(DB_HOST, DB_USER, DB_PSWD, DB_NAME);

		if ($this->_dbHandle->connect_errno) {
			// Connection failed
			return 1;
		} else {
			return 0;
		}
	}
	/** 释放结果集内存 **/
	function freeResult()
	{
		if ($this->_result instanceof mysqli_result) {
			$this->_result->free();
		}
	}

	//获得总数
	function getCount($tab, $w = "")
	{
		if (!empty($w))
			$w = " WHERE $w";
		$count = $this->query("SELECT COUNT(1) as c FROM " . SUFFIX . $tab . PREFIX . " $w", 1);

		//	$this->writeMsg("SELECT COUNT(1) as c FROM ".SUFFIX.$tab.PREFIX." $w");

		// Legacy behavior: The query method returns array('Table' => array('Field'=>'Value'))
		// If the table is aliased or computed, we need to ensure we access it correctly.
		// For "SELECT COUNT(1) as c ...", the table might be reported as empty or the table name.
		// Let's inspect how query() handles it. It uses fetch_fields()->table.
		// For computed columns like COUNT(1), MySQL usually returns empty table name or the underlying table if traceable.
		// In the legacy code: $count[""]["c"]. This implies legacy mysql_field_table returned empty string for computed?
		// or code expected it.
		// Let's try to find the key. Recursively or reset()?
		if (!empty($count)) {
			// Get the first element of the array (the table part)
			$firstTable = reset($count);
			return $firstTable["c"];
		}
		return 0;
	}
	//获得总和
	function getSum($tab, $field, $w = "")
	{
		if (!empty($w))
			$w = " WHERE $w";
		$sum = $this->query("SELECT SUM(" . $field . ") as c FROM " . SUFFIX . $tab . PREFIX . " $w", 1);

		if (!empty($sum)) {
			$firstTable = reset($sum);
			return isset($firstTable["c"]) ? $firstTable["c"] : 0;
		}
		return 0;
	}

	//获取带分业的列表
	function getList($table, $where = "", $order = "id desc", $size = 20)
	{
		//当前页数
		$page = $this->getPar("p");
		//$this->writeMsg("page:".$page);
		if (empty($page) || !is_numeric($page) || $page < 1)
			$page = 1;
		if (empty($size) || !is_numeric($size))
			$size = 20;
		$w = $where;
		if (!empty($w))
			$w = "WHERE $w";

		//总页数
		$count = $this->getCount($table, $where); // Optimized to use getCount directly

		$pg = ceil($count / $size);

		//当前页数超过总页数
		//if($page>$pg)
//			$page = $pg;
		if ($page < 1)
			$page = 1;

		$c = ($page - 1) * $size;

		$sql = "SELECT * FROM " . SUFFIX . $table . PREFIX . " $w ORDER BY $order LIMIT $c,$size";
		$this->writeMsg($sql);
		$tableList = $this->query($sql);

		//上一页 下一页的页数
		$P = $page - 1;
		$N = $page + 1;
		if ($P < 1)
			$P = 1;
		if ($N > $pg)
			$N = $pg;
		//当前页的第一个序号
		$cur = ($page - 1) * $size;
		//cur:当前序号+  count:总记录数  pg:总页数  P:上一页  N:下一页  page:当前页  tableList：列表集合  s:搜索条件
		$hyzxs = array($cur, $count, $pg, $P, $N, $page, $tableList);
		return $hyzxs;
	}

	//添加记录
	function addRecode($table, $col, $val)
	{
		$insertSql = "INSERT INTO " . SUFFIX . $table . PREFIX . "($col) VALUES $val";
		//echo $insertSql;
		$this->writeMsg($insertSql);
		$insertRes = $this->execut($insertSql, 1);
		return $insertRes;
	}
	//修改记录
	function editRecode($table, $col, $where)
	{
		$w = $where;
		if (!empty($w))
			$w = "WHERE $w";
		$updateSql = "UPDATE " . SUFFIX . $table . PREFIX . " SET $col $w";

		$this->writeMsg($updateSql);
		$updateRes = $this->execut($updateSql);
		return $updateRes;
	}
	//删除记录
	function deleteRecode($table, $where)
	{
		$w = $where;
		if (!empty($w))
			$w = "WHERE $w";
		$deleteSql = "DELETE FROM " . SUFFIX . $table . PREFIX . " $w";
		$deleteRes = $this->execut($deleteSql);
		return $deleteRes;
	}
	//获得一条记录
	function getOne($table, $where = "", $order = "")
	{
		$w = $where;
		if (!empty($w))
			$w = "WHERE $w";
		if (!empty($order))
			$w .= " ORDER BY $order";
		$selectSql = "SELECT * FROM " . SUFFIX . $table . PREFIX . " $w";
		// echo $selectSql;
		//$this->writeMsg($selectSql);

		$selectRes = $this->query($selectSql, 1);
		return $selectRes;
	}
	//获得符合条件的记录
	function getAll($table, $where = "", $order = " id desc")
	{
		$w = $where;
		if (!empty($w))
			$w = "WHERE $w";
		if (!empty($order))
			$w .= " ORDER BY $order";
		$selectSql = "SELECT * FROM " . SUFFIX . $table . PREFIX . " $w";
		//$this->writeMsg($selectSql);
		$selectRes = $this->query($selectSql);
		return $selectRes;
	}


}
$db = new DbHelper();
























