<?
class db{
	private $host;
	private $user;
	private $pwd;
	private $linkID;
	private $queryID;
	private $fetchtype = MYSQL_BOTH;	// MYSQL_ASSOC-关联数组   MYSQL_NUM-数字数组   MYSQL_BOTH
	private $record = array();

	private $errno	= 0;			
	private $error	= "";	

	function db($host,$user,$pwd,$dbName)
	{	
		if(empty($host) || empty($user) || empty($dbName))
			$this->halt("数据库设置不全!");

		$this->host    = isset($host)	?$host	: SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT ;
		$this->user    = isset($user)	?$user	: SAE_MYSQL_USER	;
		$this->pwd     = isset($pwd)	?$pwd	: SAE_MYSQL_PASS	;
		$this->dbName  = isset($dbName)	?$dbName: "app_playgame01"	;
		$this->connect();
	}

	function connect($host = "", $user = "", $pwd = "", $dbName = "")
	{
		if ("" == $host)
			$host = $this->host;
		if ("" == $user)
			$user = $this->user;
		if ("" == $pwd)
			$pwd = $this->pwd;
		if ("" == $dbName)
			$dbName = $this->dbName;

		//now connect to the database
		$this->linkID = mysql_pconnect($host, $user, $pwd);
		if (!$this->linkID)
		{
			$this->halt();
			return 0;
		}
		if (!mysql_select_db($dbName, $this->linkID))
		{
			$this->halt();
			return 0;
		}
		// 用UTF8编码查询
		mysql_query("SET NAMES utf8");
		return $this->linkID;			
	}

	function query($sql)
	{
		$this->queryID = mysql_query($sql, $this->linkID);
		if (!$this->queryID)
		{	
			$this->halt();
			return 0;
		}
		return $this->queryID;
	}

	function fetchRow()
	{
		$this->record = mysql_fetch_array($this->queryID,$this->fetchtype);
		return $this->record;
	}

	function fetchAll()
	{
		$arr = array();
		while($this->record = mysql_fetch_array($this->queryID,$this->fetchtype))
		{
			$arr[] = $this->record;
		}
		mysql_free_result($this->queryID);
		return $arr;
	}

	function getValue($field)
	{
		return $this->record[$field];
	}

	function insertID() {
		return mysql_insert_id();
	}

	function affectedRows()
	{
		return mysql_affected_rows($this->linkID);
	}

	function recordCount()
	{
		return mysql_num_rows($this->queryID);
	}

	function halt($err_msg="")
	{
		if ("" == $err_msg)
		{
			$this->errno = mysql_errno();
			$this->error = mysql_error();
			echo "<b>mysql error:<b><br>";
			echo $this->errno.":".$this->error."<br>";
			exit;
		}
		else
		{
			echo "<b>mysql error:<b><br>";
			echo $err_msg."<br>";
			exit;
		}
	}

}
?>