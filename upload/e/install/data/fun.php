<?php

//提示信息
function eins_InstallShowMsg($msg,$url=''){
	if(empty($url))
	{
		echo"<script>alert('".$msg."');history.go(-1);</script>";
	}
	else
	{
		echo"<script>alert('".$msg."');self.location.href='$url';</script>";
	}
	exit();
}

//提示信息(时间间隔)
function eins_InstallShowMsgTime($error,$gotourl='',$sec=0){
	global $ins_insretime;
	if($ins_insretime)
	{
		$sec=(int)$ins_insretime;
	}
	echo'<meta http-equiv="refresh" content="'.$sec.';url='.$gotourl.'">'.$error;
	exit();
}

//返回安装密码
function eins_EnInsPass($inspass){
	$pw=md5(md5($inspass).'^empire.cms!');
	return $pw;
}

//验证表单安装密码
function eins_CkFormInsPass($inspass){
	global $ins_password,$ins_pwvar;
	if(!$ins_password)
	{
		eins_InstallShowMsg('请先设置安装密码并验证密码','inslogin.php');
	}
	if(!$ins_pwvar)
	{
		eins_InstallShowMsg('请先设置验证变量名','inslogin.php');
	}
	if(!$inspass)
	{
		eins_InstallShowMsg('请输入安装密码');
	}
	$len=strlen($inspass);
	if($len<6||$len>30)
	{
		eins_InstallShowMsg('安装密码要求6~30位');
	}
	$ckpass=eins_EnInsPass($inspass);
	$pass=eins_EnInsPass($ins_password);
	if('dg'.$pass!='dg'.$ckpass)
	{
		eins_InstallShowMsg('安装密码错');
	}
	setcookie($ins_pwvar,$ckpass);
	echo"<script>self.location.href='index.php';</script>";
	exit();
}

//验证安装密码
function eins_CkInsPass(){
	global $ins_password,$ins_pwvar;
	if(!$ins_password)
	{
		eins_InstallShowMsg('请先设置安装密码并验证密码','inslogin.php');
	}
	if(!$ins_pwvar)
	{
		eins_InstallShowMsg('请先设置验证变量名','inslogin.php');
	}
	$ckpass=$_COOKIE[$ins_pwvar];
	$pass=eins_EnInsPass($ins_password);
	if('dg'.$pass!='dg'.$ckpass)
	{
		eins_InstallShowMsg('请先验证安装密码','inslogin.php');
	}
}


//返回数据库类型
function eins_ReturnDbtypeName($edbtype){
	$edbtype=(int)$edbtype;
	if($edbtype==1)
	{
		$dbtypename='MariaDB';
	}
	elseif($edbtype==2)
	{
		$dbtypename='其它MySQL‌内核的数据库';
	}
	elseif($edbtype==3)
	{
		$dbtypename='PostgreSQL‌';
	}
	elseif($edbtype==4)
	{
		$dbtypename='国产华为高斯(openGauss)';
	}
	elseif($edbtype==5)
	{
		$dbtypename='国产金仓数据库(kingbase)';
	}
	elseif($edbtype==6)
	{
		$dbtypename='其它PostgreSQL‌内核的数据库';
	}
	else
	{
		$dbtypename='MySQL';
	}
	return $dbtypename;
}

//返回编码
function eins_InstallReturnDbChar(){
	if(EmpireCMS_CHARVER=='UTF-8')//简体UTF-8
	{
		$ret_r['dbchar']='utf8';
		$ret_r['setchar']='utf8';
		$ret_r['headerchar']='utf-8';
	}
	elseif(EmpireCMS_CHARVER=='BIG5')//繁体BIG5
	{
		$ret_r['dbchar']='big5';
		$ret_r['setchar']='big5';
		$ret_r['headerchar']='big5';
	}
	elseif(EmpireCMS_CHARVER=='TC-UTF-8')//繁体UTF-8
	{
		$ret_r['dbchar']='utf8';
		$ret_r['setchar']='utf8';
		$ret_r['headerchar']='utf-8';
	}
	else//简体GBK
	{
		$ret_r['dbchar']='gbk';
		$ret_r['setchar']='gbk';
		$ret_r['headerchar']='gb2312';
	}
	return $ret_r;
}

//取得随机数
function eins_InstallMakePassword($pw_length){
	$low_ascii_bound=65;
	$upper_ascii_bound=90;
	$notuse=array(58,59,60,61,62,63,64,91,92,93,94,95,96,108,111);
	while($i<$pw_length)
	{
		if(PHP_VERSION<'4.2.0')
		{
			mt_srand((double)microtime()*1000000);
		}
		mt_srand();
		$randnum=mt_rand($low_ascii_bound,$upper_ascii_bound);
		if(!in_array($randnum,$notuse))
		{
			$password1=$password1.chr($randnum);
			$i++;
		}
	}
	return $password1;
}

//随机数字
function eins_EcmsRandInt($min=0,$max=0,$ecms=0){
	mt_srand();
	if($max)
	{
		$rnd=mt_rand($min,$max);
	}
	else
	{
		$rnd=mt_rand();
	}
	return $rnd;
}

//函数是否存在
function eins_HaveFun($fun){
	if(function_exists($fun))
	{
		$word="支持";
	}
	else
	{
		$word="不支持";
	}
	return $word;
}

//返回符号
function eins_ReturnResult($st){
	if($st==1)
	{
		$w="√";
	}
	elseif($st==2)
	{
		$w="---";
	}
	else
	{
		$w="<font color=red>×</font>";
	}
	return $w;
}

//取得php版本
function eins_GetPhpVer(){
	$r['ver']=PHP_VERSION;
	if($r['ver'])
	{
		$r['result']=($r['ver']<"4.2.3")?eins_ReturnResult(0):eins_ReturnResult(1);
	}
	else
	{
		$r['ver']="---";
		$r['result']=eins_ReturnResult(2);
	}
	return $r;
}

//取得php运行模式
function eins_GetPhpMod(){
	$mod=strtoupper(php_sapi_name());
	if(empty($mod))
	{
		$mod="---";
	}
	return $mod;
}

//是否运行于安全模式
function eins_GetPhpSafemod(){
	$phpsafemod=get_cfg_var("safe_mode");
	if($phpsafemod==1)
	{
		$r['word']="是";
		$r['result']=eins_ReturnResult(0);
	}
	else
	{
		$r['word']="否";
		$r['result']=eins_ReturnResult(1);
	}
	return $r;
}

//是否支持mysql
function eins_CanMysql(){
	$r['can']=function_exists('mysql_connect')||function_exists('mysqli_connect')?'支持':'不支持';
	$r['result']=$r['can']=="支持"?eins_ReturnResult(1):eins_ReturnResult(0);
	return $r;
}

//是否支持pgsql
function eins_CanPgsql(){
	$r['can']=function_exists('pg_connect')?'支持':'不支持';
	$r['result']=$r['can']=="支持"?eins_ReturnResult(1):eins_ReturnResult(0);
	return $r;
}

//取得mysql版本
function eins_GetMysqlVer(){
	$r['ver']=do_eGetDBVer(0);
	if(empty($r['ver']))
	{
		$r['ver']="---";
		$r['result']=eins_ReturnResult(2);
	}
	else
	{
		$r['result']=eins_ReturnResult(1);
	}
	return $r;
}

//取得mysql版本(数据库)
function eins_GetMysqlVerForDb(){
	$sql=do_dbquery_common("select version() as version",$GLOBALS['link']);
	$r=do_dbfetch_common($sql);
	return eins_ReturnMysqlVer($r['version']);
}

//返回mysql版本
function eins_ReturnMysqlVer($dbver){
	if(empty($dbver))
	{
		return '';
	}
	if($dbver>='9.0')
	{
		$dbver='9.0';
	}
	elseif($dbver>='8.0')
	{
		$dbver='8.0';
	}
	elseif($dbver>='7.0')
	{
		$dbver='7.0';
	}
	elseif($dbver>='6.0')
	{
		$dbver='6.0';
	}
	elseif($dbver>='5.0')
	{
		$dbver='5.0';
	}
	elseif($dbver>='4.1')
	{
		$dbver='4.1';
	}
	else
	{
		$dbver='4.0';
	}
	return $dbver;
}

//取得操作系统
function eins_GetUseSys(){
	$phpos=explode(" ",php_uname());
	$sys=$phpos[0]."&nbsp;".$phpos[1];
	if(empty($phpos[0]))
	{
	$sys="---";
	}
	return $sys;
}

//是否支持zend
function eins_GetZend(){
	@ob_start();
	@include("data/zend.php");
	$string=@ob_get_contents();
	@ob_end_clean();
	if($string=="www.phome.net"||strstr($string,"bytes in"))
	{
		$r['word']="支持";
		$r['result']=eins_ReturnResult(1);
	}
	else
	{
		$r['word']="不支持";
		$r['result']=eins_ReturnResult(0);
	}
	return $r;
}

//检查上传
function eins_CheckTranMode(){
	@ob_start();
	@include("../class/connect.php");
	@include("../class/functions.php");
	$string=@ob_get_contents();
	@ob_end_clean();
	if(strstr($string,"bytes in"))
	{
		echo"您没有二进制上传文件！请重新二进制上传文件，然后再安装。";
		exit();
	}
}

//是否支持采集
function eins_GetCj(){
	$cj=get_cfg_var("allow_url_fopen");
	if($cj==1)
	{
		$r['word']="支持";
		$r['result']=eins_ReturnResult(1);
	}
	else
	{
		$r['word']="不支持";
		$r['result']=eins_ReturnResult(0);
	}
	return $r;
}

//测试采集
function eins_TestCj(){
	$r=@file("http://www.163.com");
	if($r[5])
	{
		echo"<br>测试结果：<b>支持采集</b>";
	}
	else
	{
		echo"<br>测试结果：<b>不支持采集</b>";
	}
	exit();
}

//是否支持gd库
function eins_GetGd(){
	$r['can']=eins_HaveFun("gd_info");
	$r['result']=$r['can']=="支持"?eins_ReturnResult(1):eins_ReturnResult(0);
	return $r;
}

//是否支持ICONV库
function eins_GetIconv(){
	$r['can']=eins_HaveFun("iconv");
	$r['result']=$r['can']=="支持"?eins_ReturnResult(1):eins_ReturnResult(0);
	return $r;
}

//参数处理函数
function eins_RepPostVar($val){
	if($val!=addslashes($val))
	{
		exit();
	}
	eins_CkPostStrChar($val);
	$val=str_replace("%","",$val);
	$val=str_replace(" ","",$val);
	$val=str_replace("`","",$val);
	$val=str_replace("\t","",$val);
	$val=str_replace("%20","",$val);
	$val=str_replace("%27","",$val);
	$val=str_replace("*","",$val);
	$val=str_replace("'","",$val);
	$val=str_replace("\"","",$val);
	$val=str_replace("/","",$val);
	$val=str_replace(";","",$val);
	$val=str_replace("#","",$val);
	$val=str_replace("--","",$val);
	$val=eins_RepPostStr($val,1);
	$val=addslashes($val);
	return $val;
}

//参数处理函数2
function eins_RepPostVar2($val){
	if($val!=addslashes($val))
	{
		exit();
	}
	eins_CkPostStrChar($val);
	$val=str_replace("%","",$val);
	$val=str_replace("`","",$val);
	$val=str_replace("\t","",$val);
	$val=str_replace("%20","",$val);
	$val=str_replace("%27","",$val);
	$val=str_replace("*","",$val);
	$val=str_replace("'","",$val);
	$val=str_replace("\"","",$val);
	$val=str_replace("/","",$val);
	$val=str_replace(";","",$val);
	$val=str_replace("#","",$val);
	$val=str_replace("--","",$val);
	$val=eins_RepPostStr($val,1);
	$val=addslashes($val);
	return $val;
}

//参数处理函数3
function eins_RepPostVar3($val){
	if($val!=addslashes($val))
	{
		exit();
	}
	eins_CkPostStrChar($val);
	$val=str_replace("%","",$val);
	$val=str_replace("`","",$val);
	$val=str_replace("\t","",$val);
	$val=str_replace("%20","",$val);
	$val=str_replace("%27","",$val);
	$val=str_replace("*","",$val);
	$val=str_replace("'","",$val);
	$val=str_replace("\"","",$val);
	//$val=str_replace("/","",$val);
	$val=str_replace(";","",$val);
	$val=str_replace("#","",$val);
	$val=str_replace("--","",$val);
	$val=eins_RepPostStr($val,1);
	$val=addslashes($val);
	return $val;
}

//处理编码字符
function eins_CkPostStrChar($val){
	if(substr($val,-1)=="\\")
	{
		exit();
	}
}

//htmlspecialchars处理
function eins_ehtmlspecialchars($val,$flags=ENT_COMPAT){
	global $ecms_config;
	$ecms_config['sets']['pagechar']='utf-8';
	if(PHP_VERSION>='5.4.0')
	{
		if($ecms_config['sets']['pagechar']=='utf-8')
		{
			$char='UTF-8';
		}
		else
		{
			$char='ISO-8859-1';
		}
		$val=htmlspecialchars($val,$flags,$char);
	}
	else
	{
		$val=htmlspecialchars($val,$flags);
	}
	return $val;
}

//处理提交字符
function eins_RepPostStr($val,$ecms=0,$phck=0){
	$val=eins_ehtmlspecialchars($val,ENT_QUOTES);
	if($ecms==0)
	{
		eins_CkPostStrChar($val);
		$val=addslashes($val);
	}
	return $val;
}


//返回目录权限结果
function eins_ReturnPathLevelResult($path){
	$testfile=$path."/test.test";
	$fp=@fopen($testfile,"wb");
	if($fp)
	{
		@fclose($fp);
		@unlink($testfile);
		return 1;
	}
	else
	{
		return 0;
	}
}

//返回文件权限结果
function eins_ReturnFileLevelResult($filename){
	return is_writable($filename);
}

//检测目录权限
function eins_CheckFileMod($filename,$smallfile=""){
	$succ="√";
	$error="<font color=red>×</font>";
	if(!file_exists($filename)||($smallfile&&!file_exists($smallfile)))
	{
		return $error;
	}
	if(is_dir($filename))//目录
	{
		if(!eins_ReturnPathLevelResult($filename))
		{
			return $error;
		}
		//子目录
		if($smallfile)
		{
			if(is_dir($smallfile))
			{
				if(!eins_ReturnPathLevelResult($smallfile))
				{
					return $error;
				}
			}
			else//文件
			{
				if(!eins_ReturnFileLevelResult($smallfile))
				{
					return $error;
				}
			}
		}
	}
	else//文件
	{
		if(!eins_ReturnFileLevelResult($filename))
		{
			return $error;
		}
		if($smallfile)
		{
			if(!eins_ReturnFileLevelResult($smallfile))
			{
				return $error;
			}
		}
	}
	return $succ;
}

//建表
function eins_DoCreateTable($sql,$mysqlver,$dbcharset){
	$type=strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU","\\2",$sql));
	$type=in_array($type,array('MYISAM','HEAP','INNODB'))?$type:'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU","\\1",$sql).
		($mysqlver>='4.1'?" ENGINE=$type DEFAULT CHARSET=$dbcharset":" TYPE=$type");
}

//运行SQL
function eins_DoRunQuery($sql,$mydbchar,$mydbtbpre,$mydbver){
	global $echdbtype,$ecms_config;
	$sql=str_replace("\r","\n",str_replace(' `phome_',' `'.$mydbtbpre,$sql));
	$sql=str_replace(' phome_',' '.$mydbtbpre,$sql);
	$ret=array();
	$num=0;
	foreach(explode(";\n",trim($sql)) as $query)
	{
		$queries=explode("\n",trim($query));
		foreach($queries as $query)
		{
			$ret[$num].=$query[0]=='#'||$query[0].$query[1]=='--'?'':$query;
		}
		$num++;
	}
	unset($sql);
	foreach($ret as $query)
	{
		$query=trim($query);
		if($query)
		{
			if(substr($query,0,12)=='CREATE TABLE')
			{
				$name=preg_replace("/CREATE TABLE `([a-z0-9_]+)` .*/is","\\1",$query);
				echo"建立数据表: <b>".$name."</b> 完毕......<br>";
				//do_dbquery_common(eins_DoCreateTable($query,$mydbver,$mydbchar),$GLOBALS['link'],1);
				if($ecms_config['db']['usedb']=='pgsql')
				{
				}
				else
				{
					do_dbquery_common(do_dbTableCreateSql($query,$mydbver,$mydbchar),$GLOBALS['link'],1);
				}
			}
			else
			{
				do_dbquery_common($query,$GLOBALS['link'],1);
			}
		}
	}
}

//password
function eins_DoEmpireCMSAdminPassword($password,$salt,$salt2){
	$pw=md5($salt2.'E!m^p-i(r#e.C:M?S'.md5(md5($password).$salt).'d)i.g^o-d'.$salt);
	return $pw;
}

//取得随机数
function eins_make_password($pw_length){
	$low_ascii_bound=48;
	$upper_ascii_bound=122;
	$notuse=array(58,59,60,61,62,63,64,91,92,93,94,95,96);
	while($i<$pw_length)
	{
		if(PHP_VERSION<'4.2.0')
		{
			mt_srand((double)microtime()*1000000);
		}
		mt_srand();
		$randnum=mt_rand($low_ascii_bound,$upper_ascii_bound);
		if(!in_array($randnum,$notuse))
		{
			$password1=$password1.chr($randnum);
			$i++;
		}
	}
	return $password1;
}

//取得IP
function eins_egetip(){
	if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) 
	{
		$ip=getenv('HTTP_CLIENT_IP');
	} 
	elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown'))
	{
		$ip=getenv('HTTP_X_FORWARDED_FOR');
	}
	elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown'))
	{
		$ip=getenv('REMOTE_ADDR');
	}
	elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown'))
	{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	//$ip=addslashes(preg_replace("/^([\d\.]+).*/","\\1",$ip));
	$ip=eins_RepPostVar($ip);
	return $ip;
}

//取得端口
function eins_egetipport(){
	$ipport=(int)$_SERVER['REMOTE_PORT'];
	return $ipport;
}

//初使化管理员
function eins_FirstAdmin($add){
	global $echdbtype,$ecms_config,$dbtbpre;
	$add['username']=eins_RepPostVar($add['username']);
	$add['password']=eins_RepPostVar($add['password']);
	if(!trim($add['username'])||!trim($add['password']))
	{
		eins_InstallShowMsg('请输入管理员用户名与密码');
	}
	if($add['password']!=$add['repassword'])
	{
		eins_InstallShowMsg('两次输入的密码不一致，请重新输入');
	}
	if(strlen($add['password'])<8)
	{
		eins_InstallShowMsg('密码不能少于8位，请重新输入');
	}
	//链接数据库
	$dbver=eins_InstallConnectDb($ecms_config['db']['dbver'],$ecms_config['db']['dbserver'],$ecms_config['db']['dbport'],$ecms_config['db']['dbusername'],$ecms_config['db']['dbpassword'],$ecms_config['db']['dbname'],$ecms_config['db']['setchar'],$ecms_config['db']['dbchar']);
	$salt=eins_make_password(eins_EcmsRandInt(6,8));
	$salt2=eins_make_password(eins_EcmsRandInt(12,20));
	$username=$add['username'];
	$password=eins_DoEmpireCMSAdminPassword($add['password'],$salt,$salt2);
	$rnd=eins_make_password(30);
	$userprikey=eins_make_password(48);
	$tuser=$username;
	$addtime=time();
	$addip=eins_egetip();
	$addipport=eins_egetipport();
	$sql=do_dbquery_common("INSERT INTO ".$dbtbpre."enewsuser(userid,username,password,rnd,adminclass,groupid,checked,styleid,filelevel,salt,loginnum,lasttime,lastip,truename,email,classid,pretime,preip,addtime,addip,userprikey,salt2,lastipport,preipport,addipport,wname,tel,wxno,qq,tuser,onepassnum,edpasstime) VALUES (1,'$username','$password','$rnd','',1,0,1,0,'$salt',0,0,'','','',0,0,'','$addtime','$addip','$userprikey','$salt2','$addipport','$addipport','$addipport','admin','','','','$tuser',0,'$addtime');",$GLOBALS['link']);
	$sql2=do_dbquery_common("INSERT INTO ".$dbtbpre."enewsuseradd VALUES (1,0,'','','',0,'',0,'');",$GLOBALS['link']);
	do_dbclose($GLOBALS['link']);
	//认证码
	eins_RepEcmsConfigLoginauth($add);
	if($sql)
	{
		//echo"初始化管理员账号完毕!<script>self.location.href='changedata.php?echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata'])."';</script>";
		eins_InstallShowMsgTime("初始化管理员账号完毕!","changedata.php?echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata']),0);
		exit();
	}
	else
	{
		eins_InstallShowMsg('初使化管理员不成功，意外出错，请重新安装一次.');
	}
}


//建系统表
function eins_InstallTb($add){
	global $echdbtype,$ecms_config,$dbtbpre;
	//链接数据库
	$dbver=eins_InstallConnectDb($ecms_config['db']['dbver'],$ecms_config['db']['dbserver'],$ecms_config['db']['dbport'],$ecms_config['db']['dbusername'],$ecms_config['db']['dbpassword'],$ecms_config['db']['dbname'],$ecms_config['db']['setchar'],$ecms_config['db']['dbchar']);
	//执行SQL语句
	eins_DoRunQuery(eins_ReturnInstallSql(1),$ecms_config['db']['dbchar'],$dbtbpre,$ecms_config['db']['dbver']);
	do_dbclose($GLOBALS['link']);
	//echo"新建系统表完毕，正进入系统表数据导入......<script>self.location.href='index.php?enews=systbdata&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata'])."';</script>";
	eins_InstallShowMsgTime("新建系统表完毕，正进入系统表数据导入......","index.php?enews=systbdata&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata']),0);
	exit();
}

//导入系统表数据
function eins_InstallTbData($add){
	global $echdbtype,$ecms_config,$dbtbpre;
	//链接数据库
	$dbver=eins_InstallConnectDb($ecms_config['db']['dbver'],$ecms_config['db']['dbserver'],$ecms_config['db']['dbport'],$ecms_config['db']['dbusername'],$ecms_config['db']['dbpassword'],$ecms_config['db']['dbname'],$ecms_config['db']['setchar'],$ecms_config['db']['dbchar']);
	//执行SQL语句
	eins_DoRunQuery(eins_ReturnInstallSql(2),$ecms_config['db']['dbchar'],$dbtbpre,$ecms_config['db']['dbver']);
	//更新常用设置
	eins_SetDb_systb($add);
	do_dbclose($GLOBALS['link']);
	//echo"导入系统表数据完毕，正进入新建系统模型表......<script>self.location.href='index.php?enews=modtb&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata'])."';</script>";
	eins_InstallShowMsgTime("导入系统表数据完毕，正进入新建系统模型表......","index.php?enews=modtb&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata']),0);
	exit();
}


//新建系统模型表
function eins_InstallModTb($add){
	global $echdbtype,$ecms_config,$dbtbpre;
	//链接数据库
	$dbver=eins_InstallConnectDb($ecms_config['db']['dbver'],$ecms_config['db']['dbserver'],$ecms_config['db']['dbport'],$ecms_config['db']['dbusername'],$ecms_config['db']['dbpassword'],$ecms_config['db']['dbname'],$ecms_config['db']['setchar'],$ecms_config['db']['dbchar']);
	//执行SQL语句
	eins_DoRunQuery(eins_ReturnInstallSql(3),$ecms_config['db']['dbchar'],$dbtbpre,$ecms_config['db']['dbver']);
	do_dbclose($GLOBALS['link']);
	//echo"新建系统模型完毕，正进入系统模型数据导入......<script>self.location.href='index.php?enews=modtbdata&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata'])."';</script>";
	eins_InstallShowMsgTime("新建系统模型完毕，正进入系统模型数据导入......","index.php?enews=modtbdata&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata']),0);
	exit();
}

//导入系统模型数据
function eins_InstallModTbData($add){
	global $echdbtype,$ecms_config,$dbtbpre;
	//链接数据库
	$dbver=eins_InstallConnectDb($ecms_config['db']['dbver'],$ecms_config['db']['dbserver'],$ecms_config['db']['dbport'],$ecms_config['db']['dbusername'],$ecms_config['db']['dbpassword'],$ecms_config['db']['dbname'],$ecms_config['db']['setchar'],$ecms_config['db']['dbchar']);
	//执行SQL语句
	eins_DoRunQuery(eins_ReturnInstallSql(4),$ecms_config['db']['dbchar'],$dbtbpre,$ecms_config['db']['dbver']);
	do_dbclose($GLOBALS['link']);
	//echo"导入系统模型数据完毕，正进入新建模板表......<script>self.location.href='index.php?enews=templatetb&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata'])."';</script>";
	eins_InstallShowMsgTime("导入系统模型数据完毕，正进入新建模板表......","index.php?enews=templatetb&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata']),0);
	exit();
}


//新建模板表
function eins_InstallTemplateTb($add){
	global $echdbtype,$ecms_config,$dbtbpre;
	//链接数据库
	$dbver=eins_InstallConnectDb($ecms_config['db']['dbver'],$ecms_config['db']['dbserver'],$ecms_config['db']['dbport'],$ecms_config['db']['dbusername'],$ecms_config['db']['dbpassword'],$ecms_config['db']['dbname'],$ecms_config['db']['setchar'],$ecms_config['db']['dbchar']);
	//执行SQL语句
	eins_DoRunQuery(eins_ReturnInstallSql(5),$ecms_config['db']['dbchar'],$dbtbpre,$ecms_config['db']['dbver']);
	do_dbclose($GLOBALS['link']);
	//echo"新建模板表完毕，正进入模板数据导入......<script>self.location.href='index.php?enews=templatetbdata&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata'])."';</script>";
	eins_InstallShowMsgTime("新建模板表完毕，正进入模板数据导入......","index.php?enews=templatetbdata&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata']),0);
	exit();
}

//导入模板数据
function eins_InstallTemplateTbData($add){
	global $echdbtype,$ecms_config,$dbtbpre;
	//链接数据库
	$dbver=eins_InstallConnectDb($ecms_config['db']['dbver'],$ecms_config['db']['dbserver'],$ecms_config['db']['dbport'],$ecms_config['db']['dbusername'],$ecms_config['db']['dbpassword'],$ecms_config['db']['dbname'],$ecms_config['db']['setchar'],$ecms_config['db']['dbchar']);
	//执行SQL语句
	eins_DoRunQuery(eins_ReturnInstallSql(6),$ecms_config['db']['dbchar'],$dbtbpre,$ecms_config['db']['dbver']);
	do_dbclose($GLOBALS['link']);
	if(empty($add['defaultdata']))
	{
		eins_InstallDelArticleTxtFile();
		//echo"导入模板数据完毕!<script>self.location.href='index.php?enews=firstadmin&f=6&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata'])."';</script>";
		eins_InstallShowMsgTime("导入模板数据完毕!","index.php?enews=firstadmin&f=6&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata']),0);
	}
	else
	{
		//echo"导入模板数据完毕，正进入测试数据导入......<script>self.location.href='index.php?enews=defaultdata&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata'])."';</script>";
		eins_InstallShowMsgTime("导入模板数据完毕，正进入测试数据导入......","index.php?enews=defaultdata&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata']),0);
	}
	exit();
}


//导入测试数据
function eins_InstallDefaultData($add){
	global $echdbtype,$ecms_config,$dbtbpre;
	//链接数据库
	$dbver=eins_InstallConnectDb($ecms_config['db']['dbver'],$ecms_config['db']['dbserver'],$ecms_config['db']['dbport'],$ecms_config['db']['dbusername'],$ecms_config['db']['dbpassword'],$ecms_config['db']['dbname'],$ecms_config['db']['setchar'],$ecms_config['db']['dbchar']);
	//执行SQL语句
	eins_DoRunQuery(eins_ReturnInstallSql(7),$ecms_config['db']['dbchar'],$dbtbpre,$ecms_config['db']['dbver']);
	do_dbclose($GLOBALS['link']);
	//echo"导入测试数据完毕!<script>self.location.href='index.php?enews=firstadmin&f=6&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata'])."';</script>";
	eins_InstallShowMsgTime("导入测试数据完毕!","index.php?enews=firstadmin&f=6&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata']),0);
	exit();
}


//链接数据库
function eins_InstallConnectDb($phome_use_dbver,$phome_db_server,$phome_db_port,$phome_db_username,$phome_db_password,$phome_db_dbname,$phome_db_char,$phome_db_dbchar){
	global $link,$echdbtype,$dbtbpre;
	$eispgsql=$echdbtype>2?1:0;
	$link=do_dbconnect_common($phome_db_server,$phome_db_port,$phome_db_username,$phome_db_password,$phome_db_dbname,$phome_db_char);
	if(!$link)
	{
		if($eispgsql==1)
		{
			eins_InstallShowMsg('您的数据库用户名或密码或数据库名有误，链接不上PGSQL数据库');
		}
		else
		{
			eins_InstallShowMsg('您的数据库用户名或密码有误，链接不上MYSQL数据库');
		}
	}
	//mysql
	if($eispgsql==0)
	{
		//mysql版本
		if($phome_use_dbver=='auto')
		{
			$phome_use_dbver=eins_GetMysqlVerForDb();
			if(!$phome_use_dbver)
			{
				eins_InstallShowMsg('系统无法自动识别MYSQL版本，请手动选择MYSQL版本');
			}
		}
		//编码
		if($phome_use_dbver>='4.1')
		{
			$q='';
			if($phome_db_char)
			{
				$q='character_set_connection='.$phome_db_char.',character_set_results='.$phome_db_char.',character_set_client=binary';
			}
			if($phome_use_dbver>='5.0')
			{
				$q.=(empty($q)?'':',').'sql_mode=\'\'';
			}
			if($q)
			{
				do_dbquery_common('SET '.$q,$link);
			}
		}
		$db=do_eUseDb($phome_db_dbname,$link);
		//数据库不存在
		if(!$db)
		{
			if($phome_use_dbver>='4.1')
			{
				$createdb=do_dbquery_common("CREATE DATABASE IF NOT EXISTS ".$phome_db_dbname." DEFAULT CHARACTER SET ".$phome_db_dbchar,$link);
			}
			else
			{
				$createdb=do_dbquery_common("CREATE DATABASE IF NOT EXISTS ".$phome_db_dbname,$link);
			}
			if(!$createdb)
			{
				eins_InstallShowMsg('您输入的数据库名不存在');
			}
			do_eUseDb($phome_db_dbname,$link);
		}
	}
	else
	{
		$phome_use_dbver='9.0';
	}
	return $phome_use_dbver;
}


//配置数据库
function eins_SetDb($add){
	global $version,$dbtbpre;
	//编码
	if($add['dbcharmb']==1)
	{
		$add['mysetchar']='utf8mb4';
		$add['mydbchar']='utf8mb4';
		$add['myheaderchar']='utf-8';
	}
	else
	{
		$add['mysetchar']='utf8';
		$add['mydbchar']='utf8';
		$add['myheaderchar']='utf-8';
	}
	//pgsql
	if($add['echdbtype']>2)
	{
		$add['mysetchar']='utf8';
		$add['mydbchar']='utf8';
		$add['myheaderchar']='utf-8';
		$add['mydbtype']='pgsql';
		$add['mydbver']='9.0';
		$add['mysetchar']=strtoupper($add['mysetchar']);
		$add['mydbchar']=strtoupper($add['mydbchar']);
	}
	else
	{
		$add['mydbtype']=$add['mydbtype']=='mysqli'?'mysqli':'mysql';
	}
	//必填
	if(!$add['mydbver']||!$add['mydbhost']||!$add['mydbname']||!$add['mydbtbpre']||!$add['mycookievarpre']||!$add['myadmincookievarpre'])
	{
		eins_InstallShowMsg('带*项不能为空');
	}
	//链接数据库
	$dbver=eins_InstallConnectDb($add['mydbver'],$add['mydbhost'],$add['mydbport'],$add['mydbusername'],$add['mydbpassword'],$add['mydbname'],$add['mysetchar'],$add['mydbchar']);
	if($add['mydbver']=='auto')
	{
		$add['mydbver']=$dbver;
	}
	//初使化网站信息
	$siteurl=eins_ReturnEcmsSiteUrl();
	//配置文件
	eins_RepEcmsConfig($add,$siteurl);
	//关闭
	do_dbclose($GLOBALS['link']);
	//echo"配置数据库完毕，正进入新建系统表......<script>self.location.href='index.php?enews=systb&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata'])."';</script>";
	eins_InstallShowMsgTime("配置数据库完毕，正进入新建系统表......","index.php?enews=systb&f=5&ok=1&echdbtype=".$GLOBALS['echdbtype']."&defaultdata=".intval($add['defaultdata']),0);
	exit();
}

//更新常用项
function eins_SetDb_systb($add){
	global $version,$dbtbpre;
	//初使化网站信息
	$siteurl=eins_ReturnEcmsSiteUrl();
	$siteurlno=substr($siteurl,1);
	$add['keyrnd']=eins_make_password(32);
	$add['downpass']=eins_make_password(20);
	$add['hkeyrnd']=eins_make_password(36);
	$add['ctimernd']=eins_make_password(42);
	$add['autodopostpass']=eins_make_password(60);
	$add['upicdef']=$siteurl.'e/data/images/nouserpic.gif';
	$add['eackrnd']=eins_make_password(62);
	$add['phmckrnd']=eins_make_password(46);
	$add['markimg']=$siteurlno.'d/efilepub/emark/maskdef.gif';
	$add['markfont']=$siteurlno.'d/efilepub/emark/cour.ttf';
	//更新
	do_dbquery_common("update ".$dbtbpre."enewspublic set newsurl='$siteurl',fileurl='".$siteurl."d/file/',softversion='$version',keyrnd='".$add['keyrnd']."',downpass='".$add['downpass']."',hkeyrnd='".$add['hkeyrnd']."',upicdef='".$add['upicdef']."',markimg='".$add['markimg']."',markfont='".$add['markfont']."'".do_dblimit_upone(),$GLOBALS['link']);
	do_dbquery_common("update ".$dbtbpre."enewspublicadd set ctimernd='".$add['ctimernd']."',autodopostpass='".$add['autodopostpass']."',eackrnd='".$add['eackrnd']."',phmckrnd='".$add['phmckrnd']."'".do_dblimit_upone(),$GLOBALS['link']);
	do_dbquery_common("update ".$dbtbpre."enewspl_set set plurl='".$siteurl."e/pl/'".do_dblimit_upone(),$GLOBALS['link']);
	do_dbquery_common("update ".$dbtbpre."enewsshoppayfs set payurl='".$siteurl."e/payapi/ShopPay.php?paytype=alipay' where payid=2",$GLOBALS['link']);
	//配置文件
	eins_RepEcmsConfigSystb($add,$siteurl);
}

//处理更新常用项
function eins_RepEcmsConfigSystb($add,$siteurl){
	global $headerchar;
	//初使化配置文件
	$fp=@fopen("../config/config.php","rb");
	if(!$fp)
	{
		eins_InstallShowMsg('请检查 /e/config/config.php 文件是否存在!');
	}
	$data=@fread($fp,filesize("../config/config.php"));
	fclose($fp);
	//替换
	$data=str_replace('<!--ecms.downpass-->',addslashes($add['downpass']),$data);
	$data=str_replace('<!--ecms.hkeyrnd-->',addslashes($add['hkeyrnd']),$data);
	$data=str_replace('<!--ecms.ctimernd-->',addslashes($add['ctimernd']),$data);
	$data=str_replace('<!--ecms.autodopostpass-->',addslashes($add['autodopostpass']),$data);
	$data=str_replace('<!--ecms.keyrnd-->',addslashes($add['keyrnd']),$data);
	$data=str_replace('<!--ecms.upicdef-->',addslashes($add['upicdef']),$data);
	$data=str_replace('<!--ecms.markimg-->',addslashes($add['markimg']),$data);
	$data=str_replace('<!--ecms.markfont-->',addslashes($add['markfont']),$data);
	$data=str_replace('<!--ecms.eackrnd-->',addslashes($add['eackrnd']),$data);
	$data=str_replace('<!--ecms.phmckrnd-->',addslashes($add['phmckrnd']),$data);
	//写入配置文件
	$fp1=@fopen("../config/config.php","wb");
	if(!$fp1)
	{
		eins_InstallShowMsg(' /e/config/config.php 文件权限没有设为0777，配置不成功');
	}
	@fputs($fp1,$data);
	@fclose($fp1);
}

//处理配置文件
function eins_RepEcmsConfig($add,$siteurl){
	global $headerchar;
	$headerchar=$add['myheaderchar'];
	$add['euseinnodb']=$add['euseinnodb']?1:0;
	//初使化配置文件
	$fp=@fopen("data/config.txt","rb");
	if(!$fp)
	{
		eins_InstallShowMsg('请检查 /e/install/data/config.txt 文件是否存在!');
	}
	$data=@fread($fp,filesize("data/config.txt"));
	fclose($fp);
	$data=str_replace('<!--dbtype.phome.net-->',addslashes($add['mydbtype']),$data);
	$data=str_replace('<!--dbver.phome.net-->',addslashes($add['mydbver']),$data);
	$data=str_replace('<!--host.phome.net-->',addslashes($add['mydbhost']),$data);
	$data=str_replace('<!--port.phome.net-->',addslashes($add['mydbport']),$data);
	$data=str_replace('<!--username.phome.net-->',addslashes($add['mydbusername']),$data);
	$data=str_replace('<!--password.phome.net-->',addslashes($add['mydbpassword']),$data);
	$data=str_replace('<!--name.phome.net-->',addslashes($add['mydbname']),$data);
	$data=str_replace('<!--char.phome.net-->',addslashes($add['mysetchar']),$data);
	$data=str_replace('<!--dbchar.phome.net-->',addslashes($add['mydbchar']),$data);
	$data=str_replace('<!--tbpre.phome.net-->',addslashes($add['mydbtbpre']),$data);
	$data=str_replace('<!--dbtbtype.phome.net-->',intval($add['euseinnodb']),$data);
	$data=str_replace('<!--cookiepre.phome.net-->',addslashes($add['mycookievarpre']),$data);
	$data=str_replace('<!--admincookiepre.phome.net-->',addslashes($add['myadmincookievarpre']),$data);
	$data=str_replace('<!--headerchar.phome.net-->',addslashes($headerchar),$data);
	$data=str_replace('<!--cookiernd.phome.net-->',eins_make_password(eins_EcmsRandInt(36,50)),$data);
	$data=str_replace('<!--qcookiernd.phome.net-->',eins_make_password(eins_EcmsRandInt(35,46)),$data);
	$data=str_replace('<!--qcookierndtwo.phome.net-->',eins_make_password(eins_EcmsRandInt(34,48)),$data);
	$data=str_replace('<!--qcookierndthree.phome.net-->',eins_make_password(eins_EcmsRandInt(33,47)),$data);
	$data=str_replace('<!--qcookierndfour.phome.net-->',eins_make_password(eins_EcmsRandInt(32,45)),$data);
	$data=str_replace('<!--qcookierndfive.phome.net-->',eins_make_password(eins_EcmsRandInt(31,49)),$data);
	$data=str_replace('<!--eset.hendatakey.phome.net-->',eins_make_password(eins_EcmsRandInt(52,70)),$data);
	$data=str_replace('<!--eset.endatakey.phome.net-->',eins_make_password(eins_EcmsRandInt(52,66)),$data);
	$data=str_replace('<!--ecms.newsurl-->',addslashes($siteurl),$data);
	$data=str_replace('<!--ecms.fileurl-->',addslashes($siteurl)."d/file/",$data);
	$data=str_replace('<!--ecms.plurl-->',addslashes($siteurl)."e/pl/",$data);
	//写入配置文件
	$fp1=@fopen("../config/config.php","wb");
	if(!$fp1)
	{
		eins_InstallShowMsg(' /e/config/config.php 文件权限没有设为0777，配置数据库不成功');
	}
	@fputs($fp1,$data);
	@fclose($fp1);
}

//处理认证码
function eins_RepEcmsConfigLoginauth($add){
	global $headerchar;
	//初使化配置文件
	$fp=@fopen("../config/config.php","rb");
	if(!$fp)
	{
		eins_InstallShowMsg('请检查 /e/config/config.php 文件是否存在!');
	}
	$data=@fread($fp,filesize("../config/config.php"));
	fclose($fp);
	$data=str_replace('<!--loginauth.phome.net-->',addslashes($add['loginauth']),$data);
	//写入配置文件
	$fp1=@fopen("../config/config.php","wb");
	if(!$fp1)
	{
		eins_InstallShowMsg(' /e/config/config.php 文件权限没有设为0777，配置不成功');
	}
	@fputs($fp1,$data);
	@fclose($fp1);
}

//返回SQL语句
function eins_ReturnInstallSql($defaultdata=1){
	if($defaultdata==1)
	{
		$sqlfile="data/empirecms.com.sql";
	}
	elseif($defaultdata==2)
	{
		$sqlfile="data/empirecms.comdata.sql";
	}
	elseif($defaultdata==3)
	{
		$sqlfile="data/empirecms.mod.sql";
	}
	elseif($defaultdata==4)
	{
		$sqlfile="data/empirecms.moddata.sql";
	}
	elseif($defaultdata==5)
	{
		$sqlfile="data/empirecms.temp.sql";
	}
	elseif($defaultdata==6)
	{
		$sqlfile="data/empirecms.tempdata.sql";
	}
	else
	{
		$sqlfile="data/empirecms.data.sql";
	}
	$fp=fopen($sqlfile,'rb');
	$sql=fread($fp,filesize($sqlfile));
	fclose($fp);
	if(empty($sql))
	{
		eins_InstallShowMsg(' /e/install/'.$sqlfile.' 文件丢失,安装不成功','index.php?enews=setdb&f=5&echdbtype='.$GLOBALS['echdbtype']);
	}
	//替换测试数据网址
	if($sqlfile=='data/empirecms.data.sql')
	{
		$sql=eins_InstallReplaceTestDataUrl($sql);
	}
	return $sql;
}

//取得网站地址
function eins_ReturnEcmsSiteUrl(){
	$phpself=$_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME'];
	$siteurl=str_replace('e/install/index.php','',$phpself);
	$siteurl=str_replace('e/install/','',$siteurl);
	$siteurl=str_replace('e/install','',$siteurl);
	$siteurl=eins_RepPostStr($siteurl,0,0);
	return $siteurl;
}

//删除存文本文件
function eins_InstallDelArticleTxtFile(){
	return '';
	@include("../class/delpath.php");
	$DelPath="../../esavedatas/edtxt/2012";
	$wm_chief=new del_path();
	$wm_chief_ok=$wm_chief->wm_chief_delpath($DelPath);
	return $wm_chief_ok;
}

//替换测试数据网址
function eins_InstallReplaceTestDataUrl($text){
	$baseurl=eins_ReturnEcmsSiteUrl();
	$text=str_replace('/ecms80/',$baseurl,$text);
	$text=str_replace('http://demo.phome.net/defdata/demopic/',$baseurl.'testdata/demopic/',$text);
	return $text;
}
?>