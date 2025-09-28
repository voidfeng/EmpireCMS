<?php
define('InEmpireCMSDbSql',TRUE);

//------------------------- 数据库 -------------------------

//链接数据库
function do_dbconnect($dbhost,$dbport,$dbusername,$dbpassword,$dbname){
	global $ecms_config;
	$dblocalhost=$dbhost;
	//端口
	if($dbport)
	{
		$dblocalhost.=':'.$dbport;
	}
	$dblink=@mysqli_connect($dblocalhost,$dbusername,$dbpassword);
	if(!$dblink)
	{
		echo"Cann't connect to DB!";
		exit();
	}
	//编码
	if($ecms_config['db']['dbver']>='4.1')
	{
		$q='';
		if($ecms_config['db']['setchar'])
		{
			$q='character_set_connection='.$ecms_config['db']['setchar'].',character_set_results='.$ecms_config['db']['setchar'].',character_set_client=binary';
		}
		if($ecms_config['db']['dbver']>='5.0')
		{
			$q.=(empty($q)?'':',').'sql_mode=\'\'';
		}
		if($q)
		{
			@mysqli_query($dblink,'SET '.$q);
		}
	}
	@mysqli_select_db($dblink,$dbname);
	return $dblink;
}

//关闭数据库
function do_dbclose($dblink){
	if($dblink)
	{
		@mysqli_close($dblink);
	}
}

//设置编码
function do_DoSetDbChar($dbchar,$dblink){
	@mysqli_query($dblink,'set character_set_connection='.$dbchar.',character_set_results='.$dbchar.',character_set_client=binary;');
}

//取得mysql版本
function do_eGetDBVer($selectdb=0){
	global $empire,$link;
	if($selectdb&&$empire)
	{
		$getdbver=$empire->egetdbver();
	}
	else
	{
		if($link)
		{
			$getdbver=@mysqli_get_server_info($link);
		}
		else
		{
			$getdbver='';
		}
	}
	return $getdbver;
}

//普通操作
function do_dbconnect_common($dbhost,$dbport,$dbusername,$dbpassword,$dbname='',$dbsetchar=''){
	global $ecms_config;
	$dblocalhost=$dbhost;
	//端口
	if($dbport)
	{
		$dblocalhost.=':'.$dbport;
	}
	$dblink=@mysqli_connect($dblocalhost,$dbusername,$dbpassword);
	return $dblink;
}

function do_dbquery_common($query,$dblink,$ecms=0){
	global $ecms_config;
	if($ecms==0)
	{
		$sql=mysqli_query($dblink,$query);
	}
	else
	{
		$sql=mysqli_query($dblink,$query) or die($ecms_config['db']['showerror']==1?str_replace($GLOBALS['dbtbpre'],'***_',mysqli_error($dblink).'<br>'.$query):'DbError');
	}
	return $sql;
}

function do_dbfetch_common($sql){
	$r=mysqli_fetch_array($sql);
	return $r;
}

function do_dblastid_common($dblink,$tablename='',$field=''){
	$id=mysqli_insert_id($dblink);
	if($id<0)
	{
		$sql=do_dbquery_common('SELECT last_insert_id() as total',$dblink);
		$r=do_dbfetch_common($sql);
		$id=$r['total'];
	}
	return $id;
}

//选择数据库
function do_eUseDb($dbname,$dblink,$query=0){
	if($query)
	{
		$usedb=do_dbquery_common('use '.$dbname.'',$dblink);
	}
	else
	{
		$usedb=@mysqli_select_db($dblink,$dbname);
	}
	return $usedb;
}

//取得表记录数(统计表)
function do_dbTableRowNum($tbname){
	global $empire,$dbtbpre,$ecms_config;
	if($ecms_config['db']['dbtbtype']==1)
	{
		$num=$empire->gettotal('select count(*) as total from '.$tbname.do_dblimit_cone());
		return $num;
	}
	$total_r=$empire->fetch1("SHOW TABLE STATUS LIKE '".$tbname."';");
	return $total_r['Rows'];
}

//取得表信息(单个)
function do_dbTableRowOne($tbname,$ecms=0){
	global $empire,$dbtbpre;
	$tr=$empire->fetch1("SHOW TABLE STATUS LIKE '".$tbname."';");
	return $tr;
}

//取得表信息(列表)
function do_dbTableRowList($keyboard,$ecms=0){
	global $empire,$dbtbpre;
	$and='';
	if($keyboard)
	{
		$and=" LIKE '%$keyboard%'";
	}
	$sql=$empire->query("SHOW TABLE STATUS".$and);
	return $sql;
}

//取得库信息(列表)
function do_dbDbRowList($ecms=0){
	global $empire,$dbtbpre;
	$sql=$empire->query("SHOW DATABASES");
	return $sql;
}

//取得表字段信息(列表)
function do_dbFieldRowList($tbname,$ecms=0){
	global $empire,$dbtbpre;
	$sql=$empire->query("SHOW FIELDS FROM ".$tbname);
	return $sql;
}

//返回replace-into
function do_dbReplaceInto($ecms=0){
	//return 'insert';
	return 'replace';
}

//返回慢更新
function do_dbupsqllow($ecms=0){
	//return '';
	return 'LOW_PRIORITY ';
}

//返回关键字字段
function do_dbkeyfield($field){
	return '`'.$field.'`';
}

//返回关键字字段
function do_dbkeyfield_spe($field){
	return '`'.$field.'`';
}

//设置表自增字段状态
function do_dbTableSetAutoField($tbname,$idf,$ecms=''){
	global $empire,$dbtbpre;
	return 1;
}

//设置表自增字段状态
function do_dbPubTableSetAutoField($tbname,$idf,$ecms=''){
	global $empire,$dbtbpre;
	return 1;
}

//复制表处理字段
function do_dbCopyTbChAutoField($tb,$idf){
	global $empire,$dbtbpre;
}

//判断序列是否存在
function do_dbTbCkAutoFieldSeq($tb,$idf){
	global $empire,$dbtbpre;
	return 0;
}

//取得序列
function do_dbTbGetAutoFieldSeq($tb,$idf){
	global $empire,$dbtbpre;
	return '';
}

//返回是否支持showcreatetable
function do_dbCanUseSctb(){
	$sct=1;
	return $sct;
}

//复制表
function do_dbCopyTb($otb,$tb,$tbidf=''){
	global $empire;
	$create=do_dbTableStruSql($otb);
	$create=str_replace($otb,$tb,$create);
	$empire->updatesql($create,'ctb');
}

//返回表结构
function do_dbTableStruSql($tb){
	global $empire;
	$usql=$empire->query("SET SQL_QUOTE_SHOW_CREATE=1;");//设置引号
	$r=$empire->fetch1("SHOW CREATE TABLE $tb;");//数据表结构
	$create=str_replace("\"","\\\"",$r[1]);
	return $create;
}

//返回建表语句
function do_dbTableCreateSql($sql,$mysqlver,$dbcharset){
	$sql=do_dbctbtoInnoDB($sql);
	$type=strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU","\\2",$sql));
	$type=in_array($type,array('MYISAM','HEAP','INNODB'))?$type:'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU","\\1",$sql).
		($mysqlver>='4.1'&&$dbcharset?" ENGINE=$type DEFAULT CHARSET=$dbcharset":" TYPE=$type");
}

//mysql表转INNODB
function do_dbctbtoInnoDB($mytbstr){
	global $ecms_config;
	if(!$ecms_config['db']['dbtbtype'])
	{
		return $mytbstr;
	}
	$pattern=' TYPE=MyISAM';
	$replacement=' ENGINE=InnoDB';
	if(STR_IREPLACE)
	{
		$mytbstr=str_ireplace($pattern,$replacement,$mytbstr);
	}
	else
	{
		$mytbstr=str_replace($pattern,$replacement,$mytbstr);
	}
	return $mytbstr;
}

//修改表名
function do_dbTableEditTbname($tb,$oldtb,$ecms=0){
	global $empire,$dbtbpre;
	$usql=$empire->updatesql("ALTER TABLE ".$oldtb." RENAME ".$tb.";",'rtn');
	return $usql;
}

//删除表
function do_dbTableDelTb($tb,$ecms=0){
	global $empire,$dbtbpre;
	$usql=$empire->updatesql("DROP TABLE IF EXISTS ".$tb.";",'dtb');
	return $usql;
}

//返回删除表语句
function do_dbTableDelTbSql($tb,$ecms=0){
	global $empire,$dbtbpre;
	$usql="DROP TABLE IF EXISTS ".$tb.";";
	return $usql;
}

//清空表
function do_dbTableClearTbData($tb,$ecms=0){
	global $empire,$dbtbpre;
	$usql=$empire->updatesql("TRUNCATE ".$tb.";",'ttb');
	return $usql;
}

//修复表
function do_dbTableRepTb($tb,$ecms=0){
	global $empire,$dbtbpre;
	$usql=$empire->updatesql("REPAIR TABLE ".$tb.";",'rtb');
	return $usql;
}

//优化表
function do_dbTableOpiTb($tb,$ecms=0){
	global $empire,$dbtbpre;
	$usql=$empire->updatesql("OPTIMIZE TABLE ".$tb.";",'otb');
	return $usql;
}

//新建数据库
function do_dbAddDatabase($dbname,$dbchar,$ecms=0){
	global $empire,$dbtbpre,$ecms_config;
	$a="";
	if($dbchar&&$ecms_config['db']['dbver']>='4.1')
	{
		$a=" DEFAULT CHARACTER SET ".$dbchar;
	}
	$sql=$empire->updatesql("CREATE DATABASE IF NOT EXISTS $dbname".$a,'cdb');
	return $sql;
}

//删除数据库
function do_dbDelDatabase($dbname,$ecms=0){
	global $empire,$dbtbpre;
	$sql=$empire->updatesql("DROP DATABASE $dbname",'ddb');
	return $sql;
}


//增加表字段
function do_dbTableAddF($tbname,$f,$ftype,$flen,$defval='',$ecms=0){
	global $empire,$dbtbpre;
	$field=do_dbTableRetFtype($f,$ftype,$flen,$defval,$ecms);
	$asql=$empire->updatesql("alter table ".$tbname." add ".$field,'atf');
	return $asql;
}

//修改表字段
function do_dbTableEditF($tbname,$f,$oldf,$ftype,$flen,$defval='',$ecms=0){
	global $empire,$dbtbpre;
	$field=do_dbTableRetFtype($f,$ftype,$flen,$defval,$ecms,0,1);
	$usql=$empire->updatesql("alter table ".$tbname." change ".$oldf." ".$field,'etf');
	return $usql;
}

//删除表字段
function do_dbTableDelF($tbname,$f,$ecms=0){
	global $empire,$dbtbpre;
	$usql=$empire->updatesql("alter table ".$tbname." drop COLUMN ".$f,'dtf');
	return $usql;
}

//增加表字段索引
function do_dbTableAddFIndex($tbname,$iname,$ifield='',$ecms=0){
	global $empire,$dbtbpre;
	$keysql=$empire->updatesql("ALTER TABLE ".$tbname." ADD INDEX(".$iname.")",'afi');
	return $keysql;
}

//删除表字段索引
function do_dbTableDelFIndex($tbname,$iname,$ifield='',$ecms=0){
	global $empire,$dbtbpre;
	$keysql=$empire->updatesql("ALTER TABLE ".$tbname." DROP INDEX ".$iname,'dfi');
	return $keysql;
}

//返回索引名
function do_dbRetIndexname($tbname,$iname,$ecms=0){
	$addn='';
	if($ecms==1)
	{
		$addn=$tbname.'_';
	}
	return $addn.$iname;
}

//返回字段类型
function do_dbTableRetFtype($f,$ftype,$flen,$defval='',$ecms=0,$nof=0,$iseditf=0){
	//retype
	if($ftype=='TEXT')
	{
		$retype='TEXT';
		$relen='';
		$redef='';
	}
	elseif($ftype=='MEDIUMTEXT')
	{
		$retype='MEDIUMTEXT';
		$relen='';
		$redef='';
	}
	elseif($ftype=='LONGTEXT')
	{
		$retype='LONGTEXT';
		$relen='';
		$redef='';
	}
	elseif($ftype=='TINYINT')
	{
		$retype='TINYINT';
		$relen=$flen?'('.$flen.')':'';
		$redef=" default '0'";
	}
	elseif($ftype=='SMALLINT')
	{
		$retype='SMALLINT';
		$relen=$flen?'('.$flen.')':'';
		$redef=" default '0'";
	}
	elseif($ftype=='INT')
	{
		$retype='INT';
		$relen=$flen?'('.$flen.')':'';
		$redef=" default '0'";
	}
	elseif($ftype=='BIGINT')
	{
		$retype='BIGINT';
		$relen=$flen?'('.$flen.')':'';
		$redef=" default '0'";
	}
	elseif($ftype=='FLOAT')
	{
		$retype='FLOAT';
		$relen=$flen?'('.$flen.')':'';
		$redef=" default '0'";
	}
	elseif($ftype=='DOUBLE')
	{
		$retype='DOUBLE';
		$relen=$flen?'('.$flen.')':'';
		$redef=" default '0'";
	}
	elseif($ftype=='DATE')
	{
		$retype='DATE';
		$relen='';
		$redef=" default '0000-00-00'";
	}
	elseif($ftype=='DATETIME')
	{
		$retype='DATETIME';
		$relen='';
		$redef=" default '0000-00-00 00:00:00'";
	}
	elseif($ftype=='CHAR')
	{
		$retype='CHAR';
		$relen=$flen?'('.$flen.')':'(255)';
		$redef='';
	}
	elseif($ftype=='VARCHAR')
	{
		$retype='VARCHAR';
		$relen=$flen?'('.$flen.')':'(255)';
		$redef='';
	}
	else
	{
		$retype='INT';
		$relen='';
		$redef='';
	}
	$field=($nof==1?'':$f.' ').$retype.$relen." NOT NULL".$redef;
	return $field;
}

//返回字段类型(char处理)
function do_dbTableRetFtypeChar(){
	//return 'CHAR';
	return 'VARCHAR';
}

//限制查询数量
function do_dblimit_str($limit,$offset=0,$ecms=0){
	$offset=(int)$offset;
	$str='';
	if($limit)
	{
		if(!$offset)
		{
			if(strstr($limit,','))
			{
				$lir=explode(',',$limit);
				$offset=(int)$lir[0];
				$limit=(int)$lir[1];
				$str=' limit '.$offset.','.$limit;
			}
			else
			{
				$limit=(int)$limit;
				$str=' limit '.$limit;
			}
		}
		else
		{
			$limit=(int)$limit;
			$str=' limit '.$offset.','.$limit;
		}
	}
	return $str;
}

//限制查询数量
function do_dblimit($limit,$offset=0,$ecms=0){
	$limit=(int)$limit;
	$offset=(int)$offset;
	$str='';
	if($limit)
	{
		if(!$offset)
		{
			$str=' limit '.$limit;
		}
		else
		{
			$str=' limit '.$offset.','.$limit;
		}
	}
	return $str;
}

//限制查询数量(单个)
function do_dblimit_one(){
	return ' limit 1';
}

//限制查询数量(单个count)
function do_dblimit_cone(){
	return ' limit 1';
}

//限制数量(up)
function do_dblimit_up($limit,$offset=0){
	$limit=(int)$limit;
	$offset=(int)$offset;
	$str='';
	if($limit)
	{
		if(!$offset)
		{
			$str=' limit '.$limit;
		}
		else
		{
			$str=' limit '.$offset.','.$limit;
		}
	}
	return $str;
}

//限制数量(单个up)
function do_dblimit_upone(){
	return ' limit 1';
}

//限制数量(del)
function do_dblimit_del($limit,$offset=0){
	$limit=(int)$limit;
	$offset=(int)$offset;
	$str='';
	if($limit)
	{
		if(!$offset)
		{
			$str=' limit '.$limit;
		}
		else
		{
			$str=' limit '.$offset.','.$limit;
		}
	}
	return $str;
}

//限制数量(del)
function do_dblimit_delone(){
	return ' limit 1';
}



//------------------------- 数据库操作 -------------------------

class mysqlquery
{
	var $dblink;
	var $sql;//sql语句执行结果
	var $query;//sql语句
	var $num;//返回记录数
	var $r;//返回数组
	var $id;//返回数据库id号
	//执行mysql_query()语句
	function query($query,$ecms=''){
		global $ecms_config;
		$this->sql=mysqli_query(return_dblink($query),$query) or die($ecms_config['db']['showerror']==1?str_replace($GLOBALS['dbtbpre'],'***_',mysqli_error(return_dblink($query)).'<br>'.$query):'DbError');
		return $this->sql;
	}
	//执行mysql_query()语句2
	function query1($query,$ecms=''){
		$this->sql=mysqli_query(return_dblink($query),$query);
		return $this->sql;
	}
	//执行mysql_query()语句(选择数据库USE)
	function usequery($query){
		global $ecms_config;
		$this->sql=mysqli_query($GLOBALS['link'],$query) or die($ecms_config['db']['showerror']==1?str_replace($GLOBALS['dbtbpre'],'***_',mysqli_error($GLOBALS['link']).'<br>'.$query):'DbError');
		if($GLOBALS['linkrd'])
		{
			mysqli_query($GLOBALS['linkrd'],$query);
		}
		return $this->sql;
	}
	//执行mysql_query()语句(操作数据库)
	function updatesql($query,$ecms=''){
		global $ecms_config;
		$this->sql=mysqli_query(return_dblink_w(),$query) or die($ecms_config['db']['showerror']==1?str_replace($GLOBALS['dbtbpre'],'***_',mysqli_error(return_dblink_w()).'<br>'.$query):'DbError');
		return $this->sql;
	}
	//执行mysql_query()语句2(操作数据库)
	function updatesql2($query,$ecms=''){
		global $ecms_config;
		$this->sql=mysqli_query(return_dblink_w(),$query);
		return $this->sql;
	}
	//执行pg_prepare()语句
	function query_bd($qname,$query,$vr,$ecms=''){
		return 0;
	}
	//执行pg_prepare()语句(操作数据库)
	function updatesql_bd($qname,$query,$vr,$ecms=''){
		return 0;
	}
	//执行mysql_fetch_array()
	function fetch($sql)//此方法的参数是$sql就是sql语句执行结果
	{
		$this->r=mysqli_fetch_array($sql);
		return $this->r;
	}
	//执行fetchone(mysql_fetch_array())
	//此方法与fetch()的区别是:1、此方法的参数是$query就是sql语句 
	//2、此方法用于while(),for()数据库指针不会自动下移，而fetch()可以自动下移。
	function fetch1($query)
	{
		$this->sql=$this->query($query);
		$this->r=mysqli_fetch_array($this->sql);
		return $this->r;
	}
	//执行mysql_fetch_assoc()
	function fetch_zm($sql)//此方法的参数是$sql就是sql语句执行结果
	{
		$this->r=mysqli_fetch_assoc($sql);
		return $this->r;
	}
	//执行fetchone(mysql_fetch_assoc())
	function fetch1_zm($query)
	{
		$this->sql=$this->query($query);
		$this->r=mysqli_fetch_assoc($this->sql);
		return $this->r;
	}
	//执行mysql_fetch_row()
	function fetch_sz($sql)//此方法的参数是$sql就是sql语句执行结果
	{
		$this->r=mysqli_fetch_row($sql);
		return $this->r;
	}
	//执行fetchone(mysql_fetch_row())
	function fetch1_sz($query)
	{
		$this->sql=$this->query($query);
		$this->r=mysqli_fetch_row($this->sql);
		return $this->r;
	}
	//执行mysql_num_rows()
	function num($query)//此类的参数是$query就是sql语句
	{
		$this->sql=$this->query($query);
		$this->num=mysqli_num_rows($this->sql);
		return $this->num;
	}
	//执行numone(mysql_num_rows())
	//此方法与num()的区别是：1、此方法的参数是$sql就是sql语句的执行结果。
	function num1($sql)
	{
		$this->num=mysqli_num_rows($sql);
		return $this->num;
	}
	//执行numone(mysql_num_rows())
	//统计记录数
	function gettotal($query)
	{
		$this->r=$this->fetch1($query);
		return $this->r['total'];
	}
	//执行free(mysql_result_free())
	//此方法的参数是$sql就是sql语句的执行结果。只有在用到mysql_fetch_array的情况下用
	function free($sql)
	{
		mysqli_free_result($sql);
	}
	//执行seek(mysql_data_seek())
	//此方法的参数是$sql就是sql语句的执行结果,$pit为执行指针的偏移数
	function seek($sql,$pit)
	{
		mysqli_data_seek($sql,$pit);
	}
	//执行id(mysql_insert_id())
	function lastid($tablename='',$field='')//取得最后一次执行mysql数据库id号
	{
		$this->id=mysqli_insert_id($GLOBALS['link']);
		if($this->id<0)
		{
			$this->id=$this->gettotal('SELECT last_insert_id() as total');
		}
		return $this->id;
	}
	//返回影响数量(mysql_affected_rows())
	function affectnum()//取得操作数据表后受影响的记录数
	{
		return mysqli_affected_rows($GLOBALS['link']);
	}
	//执行escape_string()函数
	function EDbEscapeStr($str){
		$str=mysqli_real_escape_string($GLOBALS['link'],$str);
		return $str;
	}
	//取得数据库版本
	function egetdbver()
	{
		$this->r=$this->fetch1('select version() as version');
		return $this->r['version'];
	}
}
?>