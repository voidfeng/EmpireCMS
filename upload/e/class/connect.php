<?php
error_reporting(E_ALL ^ E_NOTICE);

define('InEmpireCMS',TRUE);
define('ECMS_PATH',substr(dirname(__FILE__),0,-7));
define('MAGIC_QUOTES_GPC',function_exists('ini_get')&&ini_get('magic_quotes_gpc'));
define('STR_IREPLACE',function_exists('str_ireplace'));
define('ECMS_PNO',EcmsGetProgramNo());

$ecms_config=array();
$ecms_adminloginr=array();
$ecms_adminfhr=array();
$ecms_hashur=array();
$emoreport_r=array();
$public_r=array();
$public_diyr=array();
$public_dayr=array();
$emod_pubr=array();
$etable_r=array();
$etable_t=array();
$emod_r=array();
$notcj_r=array();
$fun_r=array();
$message_r=array();
$qmessage_r=array();
$enews_r=array();
$class_r=array();
$class_zr=array();
$class_tr=array();
$eyh_r=array();
$schalltb_r=array();
$level_r=array();
$aglevel_r=array();
$iglevel_r=array();
$r=array();
$addr=array();
$paddr=array();
$lur=array();
$logininid=0;
$loginin='';
$loginadminstyleid=1;
$search='';
$start=0;
$addgethtmlpath='';
$string='';
$notcjnum=0;
$editor=0;
$ecms_gr=array();
$navinfor=array();
$pagefunr=array();
$user=array();
$emuser=array();
$ret_r=array();
$navclassid='';
$navnewsid='';
$navtheid='';
$cjnewsurl='';
$formattxt='';
$link='';
$linkrd='';
$empire='';
$dbtbpre='';
$efileftp='';
$efileftp_fr=array();
$efileftp_dr=array();
$incftp=0;
$doetran=0;
$ecmsvar_mbr=array();
$ebq_r=array();
$ecms_tofunr=array();
$ecms_topager=array();
$ecms_topagesetr=array();
$ecms_toboxr=array();
$eapi_r=array();
$eapi_setr=array();
$eapi_pubr=array();
$epay_setconfig=array();
$get_er=array();
$get_evr=array();
$edb_ir=array();
$edb_fr=array();
$edb_vr=array();
$add='';
$iconv='';
$char='';
$targetchar='';
$efh='';
$eeid='';
$eeid_r=array();
$emptyarray=array();
$ecms_config['sets']['selfmoreportid']=0;
$ecms_config['sets']['mainportpath']='';
$ecms_config['sets']['pagemustdt']=0;
$emoreport_r[1]['ppath']='';

require_once ECMS_PATH.'e/config/config.php';

if(!defined('EmpireCMSConfig'))
{
	exit();
}

if($ecms_config['sets']['webdebug']==0)
{
	error_reporting(0);
}

//超时设置
if(defined('EmpireCMSAdmin'))
{
	if($public_r['php_adminouttime'])
	{
		@set_time_limit($public_r['php_adminouttime']);
	}
}
else
{
	if($public_r['php_outtime'])
	{
		@set_time_limit($public_r['php_outtime']);
	}
}

//页面编码
if($ecms_config['sets']['setpagechar']==1)
{
	if($ecms_config['sets']['pagechar']=='gb2312'||$ecms_config['sets']['pagechar']=='big5'||$ecms_config['sets']['pagechar']=='utf-8')
	{
		@header('Content-Type: text/html; charset='.$ecms_config['sets']['pagechar']);
	}
}

//时区
if(function_exists('date_default_timezone_set'))
{
	@date_default_timezone_set($ecms_config['sets']['timezone']);
}

if($ecms_config['db']['usedb']=='pgsql')
{
	include(ECMS_PATH.'e/class/dbpg/db_pgsql.php');
	if(defined('InEmpireBak'))
	{
		echo'Pgsql not to use EmpireBak!';
		exit();
	}
}
elseif($ecms_config['db']['usedb']=='mysqli'||PHP_VERSION>='7.0.0')
{
	include(ECMS_PATH.'e/class/db/db_mysqli.php');
}
else
{
	include(ECMS_PATH.'e/class/db/db_mysql.php');
}

//禁止IP
eCheckAccessIp(0);
DoSafeCheckFromurl();

if(defined('EmpireCMSAdmin'))
{
	eCheckAccessIp(1);//禁止IP
	EcmsCheckUserAgent($ecms_config['esafe']['ckhuseragent']);
	//FireWall
	if(!empty($ecms_config['fw']['eopen']))
	{
		DoEmpireCMSFireWall();
	}
	if(!empty($ecms_config['esafe']['ckhsession']))
	{
		session_start();
		define('EmpireCMSDefSession',TRUE);
	}
}
else
{
	if($public_r['closeqdt']||$ecms_config['sets']['fcloseqdt'])
	{
		if(!defined('EMPIRECMSPCANDT'))
		{
			echo $public_r['closeqdtmsg'];
			exit();
		}
	}
}

//访问密码
EcmsViewPass(0,$ecms_config['esafe']['viewpassvar'],$ecms_config['esafe']['viewpass'],'');
EcmsViewPass(1,$ecms_config['esafe']['hviewpassvar'],$ecms_config['esafe']['hviewpass'],'');

if($ecms_config['sets']['selfmoreportid']>1)
{
	EcmsDefMoreport($ecms_config['sets']['selfmoreportid']);
}

@include(ECMS_PATH.'c/ecachedb/edayrnd.php');
DoEcmsConfigDayUpRnd();

//--------------- 数据库 ---------------

function db_connect(){
	global $ecms_config;
	$dblink=do_dbconnect($ecms_config['db']['dbserver'],$ecms_config['db']['dbport'],$ecms_config['db']['dbusername'],$ecms_config['db']['dbpassword'],$ecms_config['db']['dbname']);
	return $dblink;
}

function return_dblink($query){
	$dblink=$GLOBALS['link'];
	return $dblink;
}

function return_dblink_w(){
	$dblink=$GLOBALS['link'];
	return $dblink;
}

function return_dblink_r(){
	$dblink=$GLOBALS['link'];
	return $dblink;
}

//设置编码
function DoSetDbChar($dbchar){
	global $link;
	if($dbchar&&$dbchar!='auto')
	{
		do_DoSetDbChar($dbchar,$link);
	}
}

function db_close(){
	global $link;
	do_dbclose($link);
}


//--------------- 公共 ---------------

//设置COOKIE
function esetcookie($var,$val,$life=0,$ecms=0,$adminpath=0){
	global $ecms_config;
	//secure属性
	$cksecure=$ecms_config['cks']['cksecure'];
	if(!empty($cksecure))
	{
		$secure=0;
		if($cksecure==2)//开启
		{
			$secure=1;
		}
		elseif($cksecure==3)//后台开启
		{
			if(defined('EmpireCMSAdmin'))
			{
				$secure=1;
			}
		}
		elseif($cksecure==4)//前台开启
		{
			if(!defined('EmpireCMSAdmin'))
			{
				$secure=1;
			}
		}
		else
		{}
	}
	else
	{
		$secure=eCheckUseHttps();
	}
	//httponly属性
	$ckhttponly=$ecms_config['cks']['ckhttponly'];
	$httponly=0;
	if(!empty($ckhttponly))
	{
		if($ckhttponly==1)//开启
		{
			$httponly=1;
		}
		elseif($ckhttponly==2)//后台开启
		{
			if(defined('EmpireCMSAdmin'))
			{
				$httponly=1;
			}
		}
		elseif($ckhttponly==3)//前台开启
		{
			if(!defined('EmpireCMSAdmin'))
			{
				$httponly=1;
			}
		}
		else
		{}
	}
	//设置
	$varpre=empty($ecms)?$ecms_config['cks']['ckvarpre']:$ecms_config['cks']['ckadminvarpre'];
	if($adminpath==2)
	{
		$ckdomain=$ecms_config['cks']['ckdomain'];
		$ckpath=$ecms_config['cks']['ckpath'];
	}
	elseif(defined('EmpireCMSAdmin')||$adminpath==1)
	{
		$ckdomain=$ecms_config['cks']['ckadmindomain']?$ecms_config['cks']['ckadmindomain']:$ecms_config['cks']['ckdomain'];
		$ckpath=$ecms_config['cks']['ckadminpath']?$ecms_config['cks']['ckadminpath']:$ecms_config['cks']['ckpath'];
	}
	else
	{
		$ckdomain=$ecms_config['cks']['ckdomain'];
		$ckpath=$ecms_config['cks']['ckpath'];
	}
	//$ckdomain=empty($ecms)?$ecms_config['cks']['ckdomain']:$ecms_config['cks']['ckadmindomain'];
	//$ckpath=empty($ecms)?$ecms_config['cks']['ckpath']:$ecms_config['cks']['ckadminpath'];
	if(PHP_VERSION<'5.2.0')
	{
		if($httponly)
		{
			$ckpath.='; HttpOnly';
		}
		return setcookie($varpre.$var,$val,$life,$ckpath,$ckdomain,$secure);
	}
	else
	{
		return setcookie($varpre.$var,$val,$life,$ckpath,$ckdomain,$secure,$httponly);
	}
}

//返回cookie
function getcvar($var,$ecms=0){
	global $ecms_config;
	$tvar=empty($ecms)?$ecms_config['cks']['ckvarpre'].$var:$ecms_config['cks']['ckadminvarpre'].$var;
	return $_COOKIE[$tvar];
}

//错误提示
function printerror($error="",$gotourl="",$ecms=0,$noautourl=0,$novar=0){
	global $empire,$editor,$public_r,$ecms_config;
	if($editor==1){$a="../";}
	elseif($editor==2){$a="../../";}
	elseif($editor==3){$a="../../../";}
	else{$a="";}
	$ebp=ECMS_PATH.'e/data/';
	if(strstr($gotourl,"(")||empty($gotourl))
	{
		if(strstr($gotourl,"(-2"))
		{
			$gotourl_js="history.go(-2);";
			$gotourl="javascript:history.go(-2)";
		}
		else
		{
			$gotourl_js="history.go(-1);";
			$gotourl="javascript:history.go(-1)";
		}
	}
	else
	{$gotourl_js="self.location.href='$gotourl';";}
	if(empty($error))
	{$error="DbError";}
	if($ecms==9)//前台弹出对话框
	{
		@include $ebp.LoadLang("pub/q_message.php");
		$error=empty($novar)?$qmessage_r[$error]:$error;
		//json
		printerror_echojson('','',$error,$gotourl,0);
		//json
		echo"<script>alert('".$error."');".$gotourl_js."</script>";
		db_close();
		$empire=null;
		exit();
	}
	elseif($ecms==8)//后台弹出对话框
	{
		@include $ebp.LoadLang("pub/message.php");
		$error=empty($novar)?$message_r[$error]:$error;
		echo"<script>alert('".$error."');".$gotourl_js."</script>";
		db_close();
		$empire=null;
		exit();
	}
	elseif($ecms==7)//前台弹出对话框并关闭窗口
	{
		@include $ebp.LoadLang("pub/q_message.php");
		$error=empty($novar)?$qmessage_r[$error]:$error;
		//json
		printerror_echojson('','',$error,'window.close',0);
		//json
		echo"<script>alert('".$error."');window.close();</script>";
		db_close();
		$empire=null;
		exit();
	}
	elseif($ecms==6)//后台弹出对话框并关闭窗口
	{
		@include $ebp.LoadLang("pub/message.php");
		$error=empty($novar)?$message_r[$error]:$error;
		echo"<script>alert('".$error."');window.close();</script>";
		db_close();
		$empire=null;
		exit();
	}
	elseif($ecms==0)
	{
		@include $ebp.LoadLang("pub/message.php");
		$error=empty($novar)?$message_r[$error]:$error;
		@include($ebp."../eapub/message.php");
	}
	else
	{
		@include $ebp.LoadLang("pub/q_message.php");
		$error=empty($novar)?$qmessage_r[$error]:$error;
		//json
		printerror_echojson('','',$error,$gotourl,0);
		//json
		@include($ebp."../message/index.php");
	}
	db_close();
	$empire=null;
	exit();
}

//错误提示2：直接文字
function printerror2($error='',$gotourl='',$ecms=0,$noautourl=0){
	global $empire,$public_r;
	if(strstr($gotourl,"(")||empty($gotourl))
	{
		if(strstr($gotourl,"(-2"))
		{
			$gotourl_js="history.go(-2);";
			$gotourl="javascript:history.go(-2)";
		}
		else
		{
			$gotourl_js="history.go(-1);";
			$gotourl="javascript:history.go(-1)";
		}
	}
	else
	{$gotourl_js="self.location.href='$gotourl';";}
	if($ecms==9)//弹出对话框
	{
		//json
		printerror_echojson('','',$error,$gotourl,0);
		//json
		echo"<script>alert('".$error."');".$gotourl_js."</script>";
	}
	elseif($ecms==7)//弹出对话框并关闭窗口
	{
		//json
		printerror_echojson('','',$error,'window.close',0);
		//json
		echo"<script>alert('".$error."');window.close();</script>";
	}
	else
	{
		//json
		printerror_echojson('','',$error,$gotourl,0);
		//json
		@include(ECMS_PATH.'e/message/index.php');
	}
	db_close();
	exit();
}

//ajax错误提示
function ajax_printerror($result='',$ajaxarea='ajaxarea',$error='',$ecms=0,$novar=0){
	global $empire,$editor,$public_r,$ecms_config;
	if($editor==1){$a="../";}
	elseif($editor==2){$a="../../";}
	elseif($editor==3){$a="../../../";}
	else{$a="";}
	$ebp=ECMS_PATH.'e/data/';
	if($ecms==0)
	{
		@include $ebp.LoadLang("pub/message.php");
		$error=empty($novar)?$message_r[$error]:$error;
	}
	else
	{
		@include $ebp.LoadLang("pub/q_message.php");
		$error=empty($novar)?$qmessage_r[$error]:$error;
	}
	if(empty($ajaxarea))
	{
		$ajaxarea='ajaxarea';
	}
	$ajaxarea=ehtmlspecialchars($ajaxarea,ENT_QUOTES);
	$string=$result.'|'.$ajaxarea.'|'.$error;
	echo $string;
	db_close();
	$empire=null;
	exit();
}

//直接转向
function printerrortourl($gotourl='',$error='',$sec=0){
	global $empire,$editor,$public_r,$ecms_config;
	//json
	printerror_echojson('','',$error,$gotourl,0);
	//json
	echo'<meta http-equiv="refresh" content="'.$sec.';url='.$gotourl.'">'.$error;
	db_close();
	$empire=null;
	exit();
}

//错误提示返回json
function printerror_echojson($result,$code,$error,$gotourl,$ecms=0){
	global $empire,$editor,$public_r,$ecms_config;
	if(!$ecms_config['sets']['printerrortype'])
	{
		return '';
	}
	$str='"msg":"'.eapi_JsonEnRepstr($error).'","gotourl":"'.eapi_JsonEnRepstr($gotourl).'"';
	if($result)
	{
		$str.=',"result":"'.$result.'"';
	}
	if($code)
	{
		$str.=',"code":"'.$code.'"';
	}
	$str='{'.$str.'}';
	echo $str;
	db_close();
	$empire=null;
	exit();
}

//编码转换1
function eDoIconvOne($code,$targetcode,$str,$inc=0){
	if(!function_exists('iconv'))
	{
		return '';
	}
	//source
	if($code=='GB2312')
	{
		$code='GBK';
	}
	elseif($code=='UTF8')
	{
		$code='UTF-8';
	}
	elseif($code=='BIG5')
	{
		$code='BIG5';
	}
	elseif($code=='UNICODE')
	{
		$code='UTF-16le';
	}
	else
	{}
	//target
	if($targetcode=='GB2312')
	{
		$targetcode='GBK//IGNORE';
	}
	elseif($targetcode=='UTF8')
	{
		$targetcode='UTF-8//IGNORE';
	}
	elseif($targetcode=='BIG5')
	{
		$targetcode='BIG5//IGNORE';
	}
	elseif($targetcode=='UNICODE')
	{
		$targetcode='UTF-16le//IGNORE';
	}
	else
	{
		$targetcode=$targetcode.'//IGNORE';
	}
	$str=iconv($code,$targetcode,$str);
	return $str;
}

//编码转换2
function eDoIconvTwo($code,$targetcode,$str,$inc=0){
	global $editor,$iconv;
	if($editor==1){$a="../";}
	elseif($editor==2){$a="../../";}
	elseif($editor==3){$a="../../../";}
	else{$a="";}
	if(!defined('InEmpireCMSIconv'))
	{
		@include_once(ECMS_PATH."e/class/doiconv.php");
	}
	if(defined('ECMS_ICONV'))
	{
	}
	else
	{
		$iconv=new Chinese($a);
		define('ECMS_ICONV',TRUE);
	}
	$str=$iconv->Convert($code,$targetcode,$str);
	return $str;
}

//编码转换3
function eDoIconvThree($code,$targetcode,$str,$inc=0){
	if(!function_exists('mb_convert_encoding'))
	{
		return '';
	}
	//source
	if($code=='GB2312')
	{
		$code='GBK';
	}
	elseif($code=='UTF8')
	{
		$code='UTF-8';
	}
	elseif($code=='BIG5')
	{
		$code='BIG5';
	}
	elseif($code=='UNICODE')
	{
		$code='UTF-16le';
	}
	else
	{}
	//target
	if($targetcode=='GB2312')
	{
		$targetcode='GBK';
	}
	elseif($targetcode=='UTF8')
	{
		$targetcode='UTF-8';
	}
	elseif($targetcode=='BIG5')
	{
		$targetcode='BIG5';
	}
	elseif($targetcode=='UNICODE')
	{
		$targetcode='UTF-16le';
	}
	else
	{}
	$str=mb_convert_encoding($str,$targetcode,$code);
	return $str;
}

//编码转换
function DoIconvVal($code,$targetcode,$str,$inc=0){
	if(function_exists('iconv'))
	{
		$str=eDoIconvOne($code,$targetcode,$str,$inc);
	}
	elseif(function_exists('mb_convert_encoding'))
	{
		$str=eDoIconvThree($code,$targetcode,$str,$inc);
	}
	else
	{
		$str=eDoIconvTwo($code,$targetcode,$str,$inc);
	}
	return $str;
}

//返回当前端地址
function eReturnDmUrl(){
	global $public_r,$ecms_config,$emoreport_r;
	$dmurl=$public_r['sitedm'].'/';
	$pid=(int)$ecms_config['sets']['selfmoreportid'];
	if($pid>1&&$emoreport_r[$pid]['purl'])
	{
		$dmurl=$emoreport_r[$pid]['purl'];
	}
	return $dmurl;
}

//返回生成缓存模板组ID名
function Moreport_eReturnReTempGidF(){
	global $public_r,$ecms_config;
	$gid='';
	if($ecms_config['sets']['deftempid'])
	{
		$gid='_'.intval($ecms_config['sets']['deftempid']);
	}
	return $gid;
}

//返回当前访问端ID
function eReturnSMPid(){
	global $ecms_config;
	$pid=(int)$ecms_config['sets']['selfmoreportid'];
	return $pid;
}

//返回访问端名称
function eReturnMPname($pid){
	global $emoreport_r;
	$pid=(int)$pid;
	if(!$pid)
	{
		$pid=1;
	}
	$pname=$emoreport_r[$pid]['pname'];
	return $pname;
}

//初始化访问端
function EcmsDefMoreport($pid){
	global $public_r,$ecms_config,$emoreport_r;
	if(empty($public_r['ckhavemoreport']))
	{
		exit();
	}
	$pid=(int)$pid;
	if($pid<=1||!$emoreport_r[$pid]['pid'])
	{
		exit();
	}
	if($emoreport_r[$pid]['isclose'])
	{
		echo'This visit port is close!';
		exit();
	}
	//关闭后台
	if(defined('EmpireCMSAdmin')&&$emoreport_r[$pid]['openadmin'])
	{
		if($emoreport_r[$pid]['openadmin']==1)
		{
			if(defined('EmpireCMSAPage'))
			{
				//echo'Admin close!';
				exit();
			}
		}
		else
		{
			//echo'Admin close!';
			exit();
		}
	}
	$ecms_config['sets']['deftempid']=$emoreport_r[$pid]['tempgid'];
	$ecms_config['sets']['pagemustdt']=$emoreport_r[$pid]['mustdt'];
	$ecms_config['sets']['mainportpath']=$emoreport_r[1]['ppath'];
	if($emoreport_r[$pid]['closeadd'])
	{
		$public_r['addnews_ok']=$emoreport_r[$pid]['closeadd'];
	}
	if(empty($ecms_config['sets']['moreportusedm']))
	{
		$public_r['newsurlmp']=$public_r['newsurl'];
		$public_r['newsurl']=$emoreport_r[$pid]['purl'];
		$public_r['plurl']=$public_r['newsurl'].'e/pl/';
	}
}

//重置为主访问端模板组ID
function Moreport_ResetMainTempGid(){
	global $ecms_config,$public_r,$emoreport_r;
	$pid=(int)$ecms_config['sets']['selfmoreportid'];
	if($pid<=1||!$emoreport_r[$pid]['pid'])
	{
		return '';
	}
	$ecms_config['sets']['deftempid']=$public_r['deftempid']?$public_r['deftempid']:1;
}

//转向访问端目录
function Moreport_eSetSelfPath($pid,$ecms=0){
	global $empire,$dbtbpre,$public_r,$ecms_config;
	$pid=(int)$pid;
	$defpr=array();
	$defpr['ppath']='';
	if($pid<=1)
	{
		$pid=1;
	}
	$pr=$empire->fetch1("select * from {$dbtbpre}enewsmoreport where pid='$pid'");
	if(!$pr['ppath']||!file_exists($pr['ppath'].'e/config/config.php'))
	{
		return $defpr;
	}
	define('ECMS_SELFPATH',$pr['ppath']);
	$ecms_config['sets']['deftempid']=$pr['tempgid'];
	if($pid>1)
	{
		$public_r['newsurl']=$pr['purl'];
		$public_r['plurl']=$public_r['newsurl'].'e/pl/';
	}
	//缓存模板
	if($ecms==1)
	{
		$tr=$empire->fetch1("select downsofttemp,onlinemovietemp,listpagetemp from ".GetTemptb("enewspubtemp")."".do_dblimit_one());
		$public_r['downsofttemp']=addslashes(stripSlashes($tr['downsofttemp']));
		$public_r['onlinemovietemp']=addslashes(stripSlashes($tr['onlinemovietemp']));
		$public_r['listpagetemp']=addslashes(stripSlashes($tr['listpagetemp']));
	}
	return $pr;
}

//autodo
function eAutodo_AddDo($dotype,$classid,$id,$tid,$userid,$pid,$fname='',$ckdoall=1){
	return '';
}

//返回是否强制动态页
function Moreport_ReturnMustDt(){
	global $ecms_config;
	return $ecms_config['sets']['pagemustdt'];
}

//返回是否强制动态页(加状态)
function Moreport_ReturnMustDtAnd(){
	global $ecms_config;
	if(defined('ECMS_SELFPATH')&&$ecms_config['sets']['pagemustdt'])
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

//返回强制动态页状态
function Moreport_ReturnDtStatus($dt){
	global $ecms_config;
	if($ecms_config['sets']['pagemustdt'])
	{
		return 1;
	}
	else
	{
		return $dt;
	}
}

//返回内容页地址(访问端)
function Moreport_ReturnTitleUrl($classid,$id){
		$rewriter=eReturnRewriteInfoUrl($classid,$id,1);
		$titleurl=$rewriter['pageurl'];
		return $titleurl;
}

//返回栏目页地址(访问端)
function Moreport_ReturnClassUrl($classid){
	global $public_r,$class_r;
	if($class_r[$classid]['wburl'])
	{
		$classurl=$class_r[$classid]['wburl'];
	}
	else
	{
		$rewriter=eReturnRewriteClassUrl($classid,1);
		$classurl=$rewriter['pageurl'];
	}
	return $classurl;
}

//返回标题分类页地址(访问端)
function Moreport_ReturnInfoTypeUrl($typeid){
	$rewriter=eReturnRewriteTitleTypeUrl($typeid,1);
	$url=$rewriter['pageurl'];
	return $url;
}

//返回首页地址(访问端)
function Moreport_ReturnIndexUrl(){
	global $public_r;
	$file=$public_r['newsurl'].'index.php';
	return $file;
}

//根据编号返回模板表名
function eTnoGetTempTbname($no){
	if($no==1)//标签模板
	{
		$temptb='enewsbqtemp';
	}
	elseif($no==2)//JS模板
	{
		$temptb='enewsjstemp';
	}
	elseif($no==3)//列表模板
	{
		$temptb='enewslisttemp';
	}
	elseif($no==4)//内容模板
	{
		$temptb='enewsnewstemp';
	}
	elseif($no==5)//公共模板
	{
		$temptb='enewspubtemp';
	}
	elseif($no==6)//搜索模板
	{
		$temptb='enewssearchtemp';
	}
	elseif($no==7)//模板变量
	{
		$temptb='enewstempvar';
	}
	elseif($no==8)//投票模板
	{
		$temptb='enewsvotetemp';
	}
	elseif($no==9)//封面模板
	{
		$temptb='enewsclasstemp';
	}
	elseif($no==10)//评论模板
	{
		$temptb='enewspltemp';
	}
	elseif($no==11)//打印模板
	{
		$temptb='enewsprinttemp';
	}
	elseif($no==12)//自定义页面模板
	{
		$temptb='enewspagetemp';
	}
	else
	{
		$temptb='';
	}
	return $temptb;
}

//模板表转换
function GetTemptb($temptb){
	global $public_r,$ecms_config,$dbtbpre;
	if(!empty($ecms_config['sets']['deftempid']))
	{
		$tempid=(int)$ecms_config['sets']['deftempid'];
	}
	else
	{
		$tempid=(int)$public_r['deftempid'];
	}
	$en='';
	if(!empty($tempid)&&$tempid!=1)
	{
		$en="_".$tempid;
	}
	return $dbtbpre.$temptb.$en;
}

//返回操作模板表
function GetDoTemptb($temptb,$gid){
	global $dbtbpre;
	$en='';
	$gid=(int)$gid;
	if(!empty($gid)&&$gid!=1)
	{
		$en="_".$gid;
	}
	return $dbtbpre.$temptb.$en;
}

//返回当前使用模板组ID
function GetDoTempGid(){
	global $ecms_config,$public_r;
	if($ecms_config['sets']['deftempid'])
	{
		$gid=(int)$ecms_config['sets']['deftempid'];
	}
	elseif($public_r['deftempid'])
	{
		$gid=(int)$public_r['deftempid'];
	}
	else
	{
		$gid=1;
	}
	return $gid;
}

//导入语言包
function LoadLang($file,$ecms=1){
	global $ecms_config;
	//ck
	$l_canlist='|gb|big|en|other|';
	$l_path=str_replace('|','_',$ecms_config['sets']['elang']);
	if(!strstr($l_canlist,'|'.$l_path.'|'))
	{
		$l_path='gb';
	}
	if($ecms==1)
	{
		$langfile='../data/language/'.$l_path.'/'.$file;
	}
	else
	{
		$langfile=ECMS_PATH.'e/data/language/'.$l_path.'/'.$file;
	}
	return $langfile;
}

//取得IP
function egetip(){
	global $ecms_config;
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
	if($ecms_config['sets']['getiptype']>0)
	{
		$ip=egetipadd();
	}
	if(strlen($ip)>49)
	{
		exit();
	}
	//$ip=RepPostVar(preg_replace("/^([\d\.]+).*/","\\1",$ip));
	$ip=RepPostVar($ip);
	return $ip;
}

//取得IP附加
function egetipadd(){
	global $ecms_config;
	if($ecms_config['sets']['getiptype']==2)
	{
		$ip=getenv('HTTP_X_FORWARDED_FOR');
	}
	elseif($ecms_config['sets']['getiptype']==3)
	{
		$ip=getenv('HTTP_CLIENT_IP');
	}
	else
	{
		$ip=getenv('REMOTE_ADDR');
	}
	return $ip;
}

//取得端口
function egetipport(){
	$ipport=(int)$_SERVER['REMOTE_PORT'];
	return $ipport;
}

//取得IP所在地
function egetipfrom($ip='',$ckopen=1){
	global $public_r;
	if($ckopen==1)
	{
		if(!$public_r['openipf'])
		{
			return '';
		}
	}
	if(empty($ip))
	{
		$ip=egetip();
	}
	$ipfrom=econvertip($ip);
	$ipfrom=RepPostVar($ipfrom);
	return $ipfrom;
}

//ipdata
function econvertip($ip){
	$tinyipfile=ECMS_PATH.'e/data/ipdata/tinyipdata.dat';
	if(!file_exists($tinyipfile))
	{
		return '';
	}
	$return='Unknown';
	if(preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/",$ip))
	{
		$iparray=explode('.',$ip);

		if($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31)))
		{
			$return='Unknown';
		}
		elseif($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255)
		{
			$return='Unknown';
		}
		else
		{
			$return=econvertip_tiny($ip, $tinyipfile);
		}
	}
	return $return;
}

function econvertip_tiny($ip,$ipdatafile){

	static $fp = NULL, $offset = array(), $index = NULL;

	$ipdot = explode('.', $ip);
	$ip    = pack('N', ip2long($ip));

	$ipdot[0] = (int)$ipdot[0];
	$ipdot[1] = (int)$ipdot[1];

	if($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
		$offset = @unpack('Nlen', @fread($fp, 4));
		$index  = @fread($fp, $offset['len'] - 4);
	} elseif($fp == FALSE) {
		return  'Unknown';
	}

	$length = $offset['len'] - 1028;
	$start  = @unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);

	for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {

		if ($index[$start] . $index[$start + 1] . $index[$start + 2] . $index[$start + 3] >= $ip) {
			$index_offset = @unpack('Vlen', $index[$start + 4] . $index[$start + 5] . $index[$start + 6] . "\x0");
			$index_length = @unpack('Clen', $index[$start + 7]);
			break;
		}
	}

	@fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
	if($index_length['len']) {
		return @fread($fp, $index_length['len']);
	} else {
		return 'Unknown';
	}

}

//检查地址
function ecms_eCheckNotUrl($str){
	if(stristr($str,'/')||stristr($str,':')||stristr($str,"\\")||stristr($str,'&')||stristr($str,'?')||stristr($str,'#')||stristr($str,'@')||stristr($str,'"')||stristr($str,"'")||stristr($str,'%'))
	{
		exit();
	}
	return $str;
}

//地址返域名
function eUrlToGetDm($url){
	$rer=array();
	$ur=explode('://',$url);
	if(!$ur[1])
	{
		return $rer;
	}
	$rer['urlpre']=$ur[0];
	$ur2=explode('/',$ur[1]);
	if(stristr($ur2[0],':'))
	{
		$ur3=explode(':',$ur2[0]);
		$rer['urldm']=$ur3[0];
		$rer['urlport']=(int)$ur3[1];
	}
	else
	{
		$rer['urldm']=$ur2[0];
		$rer['urlport']='';
	}
	return $rer;
}

//验证本域名
function CheckCanUseDm($url){
	global $public_r;
	if(empty($public_r['canusedm']))
	{
		return $url;
	}
	if(!$url)
	{
		return '';
	}
	if(!stristr($url,'://'))
	{
		return $url;
	}
	$ur=eUrlToGetDm($url);
	$urldm=$ur['urldm'];
	//验证
	$r=explode('|',$public_r['canusedm']);
	$count=count($r);
	$b=0;
	for($i=0;$i<$count;$i++)
	{
		if(strstr($urldm,$r[$i]))
		{
			$b=1;
			break;
		}
	}
	if($b==0)
	{
		return $public_r['newsurl'];
	}
	return $url;
}

//返回来源地址
function EcmsGetReturnUrl($ecms=0){
	global $public_r;
	$from=$_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:$public_r['newsurl'];
	if($ecms==1)
	{
		return RepPostStrUrl($from);
	}
	return RepPostStrUrl(CheckCanUseDm($from));
}

//checkdomain
function eToCheckThisDomain($url){
	$domain=eReturnDomain();
	if(!stristr($url,$domain))
	{
		exit();
	}
	if(eCheckHaveReStr($url,'://'))
	{
		exit();
	}
}

//checkotherurl
function eCheckOtherViewUrl($url,$havevar=0,$ecms=0){
	//fromurl
	if($ecms==1)
	{
		$fromurl=$_SERVER['HTTP_REFERER'];
		if(!$fromurl)
		{
			exit();
		}
		eToCheckThisDomain($fromurl);
	}
	if(!$url)
	{
		exit();
	}
	$url=RepPostStrUrl($url);
	if(!$havevar)
	{
		if(stristr($url,'?')||stristr($url,'&')||stristr($url,'#'))
		{
			exit();
		}
	}
	//url
	if(stristr($url,'://'))
	{
		eToCheckThisDomain($url);
	}
	else
	{
		if(stristr($url,':')||stristr($url,"\\")||stristr($url,'"')||stristr($url,"'"))
		{
			exit();
		}
	}
}

//checkrestr
function eCheckHaveReStr($str,$exp){
	$r=explode($exp,$str);
	if(count($r)>2)
	{
		return 1;
	}
	return 0;
}

//checkurl
function eToCheckIsUrl($url){
	$r=explode('://',$url);
	return eCheckStrType(2,$r[0],0);
}

//checkurl2
function eToCheckIsUrl2($url){
	if(substr($url,0,4)=='http')
	{
		return 1;
	}
	return 0;
}

//checkstrft
function eCheckStrTypeFt($type,$str,$doing=0){
	$ft=substr($str,0,1);
	$httpt=substr($str,0,7);
	$httpst=substr($str,0,8);
	$ret=0;
	if($type=='fileurl')//文件地址
	{
		if($httpt=='http://'||$httpst=='https://'||$ft=='/'||$ft=='.')
		{
			$ret=1;
		}
	}
	elseif($type=='httpurl')//网页地址
	{
		if($httpt=='http://'||$httpst=='https://')
		{
			$ret=1;
		}
	}
	elseif($type=='path')//目录
	{
		if($ft=='/')
		{
			$ret=1;
			if(strstr($str,'.')||strstr($str,"\\")||strstr($str,'%')||strstr($str,':'))
			{
				$ret=0;
			}
		}
	}
	elseif($type=='pathfile')//目录文件
	{
		if($ft=='/')
		{
			$ret=1;
			if(strstr($str,'..')||strstr($str,"\\")||strstr($str,'%')||strstr($str,':'))
			{
				$ret=0;
			}
		}
	}
	elseif($type=='weburl')//web
	{
		$webt=substr($str,0,6);
		if($httpt=='http://'||$httpst=='https://'||$webt=='ftp://')
		{
			$ret=1;
		}
	}
	else
	{
		$ret=0;
	}
	if($doing)
	{
		if($ret<1)
		{
			exit();
		}
	}
	return $ret;
}

//checkstrtype
function eCheckStrType($type,$str,$doing=0,$canb=0){
	$ret=0;
	if($canb==1)
	{
		$str=str_replace(' ','',$str);
	}
	if($str=='')
	{
		return 1;
	}
	if($type==1)//数字
	{
		if(preg_match('/^[0-9]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==2)//字母
	{
		if(preg_match('/^[A-Za-z]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==3)//字母+数字
	{
		if(preg_match('/^[A-Za-z0-9]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==4)//字母+数字+下划线
	{
		if(preg_match('/^[A-Za-z0-9_]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==5)//字母+数字+下划线+点
	{
		if(preg_match('/^[A-Za-z0-9\-\._]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==6)//数字点
	{
		if(preg_match('/^[0-9\.]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==7)//文件地址
	{
		if(preg_match('/^[A-Za-z0-9\-\.\/_:]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==8)//网页地址
	{
		if(preg_match('/^[A-Za-z0-9\-\?\.\/_:&=]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==9)//mail
	{
		if(preg_match('/^[A-Za-z0-9\-\._@]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==10)//path
	{
		if(preg_match('/^[A-Za-z0-9\-\/_]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==11)//file
	{
		if(preg_match('/^[A-Za-z0-9\-\.\/_]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==12)//url
	{
		if(preg_match('/^[A-Za-z0-9\-\?\.\/_:&%,#!=]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==13)//spechar1
	{
		if(preg_match('/^["\'\$\*\/<>\\#%&]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==14)//spechar2
	{
		if(preg_match('/^["\'\$\/<>\\%]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==15)//spechar3
	{
		if(preg_match('/^["\$\'\\]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==16)//spechar4
	{
		if(preg_match('/^["\'\\]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==17)//ip
	{
		if(preg_match('/^[A-Za-z0-9\.:]+$/',$str))
		{
			$ret=1;
		}
	}
	elseif($type==18)//逗号+数字
	{
		if(preg_match('/^[0-9,]+$/',$str))
		{
			$ret=1;
		}
	}
	else
	{
		$ret=0;
	}
	if($doing)
	{
		if($ret<1)
		{
			exit();
		}
	}
	return $ret;
}

//checkstrfilename
function eCheckStrType_fname($str,$isindex=0){
	$ret=0;
	if($str=='')
	{
		return 1;
	}
	if(strstr($str,'..')||strstr($str,':')||strstr($str,"\\"))
	{
		exit();
	}
	if($isindex==1)
	{
		if(strstr($str,'.'))
		{
			exit();
		}
		$strqr=$str;
		if(strstr($str,'/'))
		{
			$strhz=substr($str,-6);
			if($strhz!='/index')
			{
				exit();
			}
			$strqr=substr($str,0,-6);
		}
		eCheckStrType(5,$strqr,1);
	}
	elseif($isindex==2)
	{
		if(strstr($str,'.'))
		{
			exit();
		}
		eCheckStrType(10,$strqr,1);
	}
	else
	{
		if(strstr($str,'/'))
		{
			exit();
		}
		eCheckStrType(5,$str,1);
	}
}

//ckpathget
function eCheckStrPathGet($pathfile){
	$pathfile=str_replace('/','',$pathfile);
	eCheckStrType(3,$pathfile,1);
	//$r=explode("/",$pathfile);
	//eCheckStrType(1,$r[0],1);
	//eCheckStrType(1,$r[1],1);
	//eCheckStrType(3,$r[2],1);
}

//返回地址
function DoingReturnUrl($url,$from=''){
	if(empty($from))
	{
		return RepPostStrUrl(CheckCanUseDm($url));
	}
	elseif($from==9)
	{
		$from=$_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:$url;
	}
	return RepPostStrUrl(CheckCanUseDm($from));
}

//htmlspecialchars处理
function ehtmlspecialchars($val,$flags=ENT_COMPAT){
	global $ecms_config;
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

//addslashes处理
function eaddslashes($val,$ckmq=1){
	if($ckmq==1&&MAGIC_QUOTES_GPC)
	{
		return $val;
	}
	$val=addslashes($val);
	return $val;
}

//addslashes处理
function eaddslashes2($val,$ckmq=1){
	if($ckmq==1&&MAGIC_QUOTES_GPC)
	{
		return addslashes($val);
	}
	$val=addslashes(addslashes($val));
	return $val;
}

//stripSlashes处理
function estripSlashes($val,$ckmq=1){
	if($ckmq==1&&!MAGIC_QUOTES_GPC)
	{
		return $val;
	}
	$val=stripSlashes($val);
	return $val;
}

//stripSlashes处理
function estripSlashes2($val,$ckmq=1){
	if($ckmq==1&&!MAGIC_QUOTES_GPC)
	{
		return stripSlashes($val);
	}
	$val=stripSlashes(stripSlashes($val));
	return $val;
}

//变量正数型处理
function RepPIntvar($val){
	global $public_r;
	$val=intval($val);
	if($val<0)
	{
		$val=0;
	}
	if($public_r['qmaxpage']&&!defined('EmpireCMSAdmin'))
	{
		if($val>=$public_r['qmaxpage'])
		{
			$val=0;
		}
	}
	return $val;
}

//参数处理函数
function RepPostVar($val){
	$val=CkPostStrIsArray($val);
	if($val!=addslashes($val))
	{
		exit();
	}
	CkPostStrChar($val);
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
	$val=RepPostStr($val,1);
	$val=addslashes($val);
	//FireWall
	FWClearGetText($val);
	return $val;
}

//参数处理函数2
function RepPostVar2($val){
	$val=CkPostStrIsArray($val);
	if($val!=addslashes($val))
	{
		exit();
	}
	CkPostStrChar($val);
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
	$val=RepPostStr($val,1);
	$val=addslashes($val);
	//FireWall
	FWClearGetText($val);
	return $val;
}

//参数处理函数3
function RepPostVar3($val){
	$val=CkPostStrIsArray($val);
	if($val!=addslashes($val))
	{
		exit();
	}
	CkPostStrChar($val);
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
	$val=RepPostStr($val,1);
	$val=addslashes($val);
	//FireWall
	FWClearGetText($val);
	return $val;
}

//验证编码字符
function CkPostStrCharYh($val){
	if($val!=addslashes($val))
	{
		exit();
	}
	return $val;
}

//返回验证编码字符
function RtCkPostStrCharYh($val){
	$ret=1;
	if($val!=addslashes($val))
	{
		$ret=0;
	}
	return $ret;
}

//验证数组
function CkPostStrIsArray($val,$ecms=0){
	if(is_array($val))
	{
		if($ecms==0)
		{
			return 'array';
		}
		exit();
	}
	return $val;
}

//返回验证空数组
function eCheckEmptyArray($ecr){
	if(isset($ecr))
	{
		if(is_array($ecr))
		{
			return $ecr;
		}
	}
	$emptyarray=array();
	return $emptyarray;
}

//返回验证定义变量
function eCheckEmptyVar($ecv){
	if(isset($ecv))
	{
		return $ecv;
	}
	return '';
}

//返回默认日期
function eDefEmptyDate($ecms=0){
	global $ecms_config;
	if($ecms_config['db']['usedb']=='pgsql')
	{
		$date='0001-01-01';
	}
	else
	{
		$date='0000-00-00';
	}
	if($ecms==1)
	{
		$date.=' 00:00:00';
	}
	return $date;
}

//返回空数组
function eReturnEmptyArray(){
	$emptyarray=array();
	return $emptyarray;
}

//处理提交字符
function RepPostStr($val,$ecms=0,$phck=0){
	$val=CkPostStrIsArray($val);
	if($phck==1)
	{
		CkPostStrCharYh($val);
	}
	$val=ehtmlspecialchars($val,ENT_QUOTES);
	if($ecms==0)
	{
		CkPostStrChar($val);
		$val=AddAddsData($val);
		//FireWall
		FWClearGetText($val);
	}
	return $val;
}

//处理提交字符2
function RepPostStr2($val,$phck=0){
	$val=CkPostStrIsArray($val);
	if($phck==1)
	{
		CkPostStrCharYh($val);
	}
	CkPostStrChar($val);
	$val=AddAddsData($val);
	//FireWall
	FWClearGetText($val);
	return $val;
}

//处理地址
function RepPostStrUrl($val,$phck=0){
	$val=str_replace('&amp;','&',RepPostStr($val,1,$phck));
	return $val;
}

//保存数据处理
function dgdb_tosave($val,$phck=0){
	$val=CkPostStrIsArray($val);
	$val=RepPostStr($val,0,$phck);
	$val=addslashes($val);
	return $val;
}

//保存数据处理(url)
function dgdb_tosaveurl($val,$phck=0){
	$val=CkPostStrIsArray($val);
	$val=RepPostStr($val,0,$phck);
	$val=str_replace('&amp;','&',$val);
	$val=addslashes($val);
	return $val;
}

//处理转义字符
function dgdb_repzy($val){
	$val=str_replace("\$","&#36;",$val);
	$val=str_replace('/','&#47;',$val);
	$val=str_replace("\\","&#92;",$val);
	return $val;
}

//处理转义字符2
function dgdb_repzy2($val){
	$val=str_replace("\$","&#36;",$val);
	//$val=str_replace('/','&#47;',$val);
	$val=str_replace("\\","&#92;",$val);
	return $val;
}

//处理提交字符串
function dgdbe_rpstr($val,$ecms=1){
	$val=CkPostStrIsArray($val);
	if(MAGIC_QUOTES_GPC)
	{
		$val=stripSlashes($val);
	}
	if($ecms==1)
	{
		$val=dgdb_repzy2($val);
	}
	else
	{
		$val=dgdb_repzy($val);
	}
	$val=ehtmlspecialchars($val,ENT_QUOTES);
	$val=str_replace('&amp;','&',$val);
	if($val!=addslashes($val))
	{
		exit();
	}
	CkPostStrChar($val);
	$val=addslashes($val);
	if($val!=addslashes($val))
	{
		exit();
	}
	CkPostStrChar($val);
	$val=addslashes($val);
	//FireWall
	FWClearGetText($val);
	return $val;
}

//数据显示
function dgdb_toshow($val){
	$val=stripSlashes($val);
	return $val;
}

//还原处理转义字符
function dgdb_repzy_re($val){
	$val=str_replace('&amp;','&',$val);
	$val=str_replace("&#36;","\$",$val);
	$val=str_replace('&#47;','/',$val);
	$val=str_replace("&#92;","\\",$val);
	return $val;	
}

//还原处理提交字符串
function dgdbe_rpstr_re($val,$ecms=1,$rezy=1){
	if($ecms==1)
	{
		$val=stripSlashes($val);
	}
	elseif($ecms==2)
	{
		$val=stripSlashes(stripSlashes($val));
	}
	else
	{}
	$val=htmlspecialchars_decode($val);
	if($rezy==1)
	{
		$val=dgdb_repzy_re($val);
	}
	return $val;
}

//处理显示字符
function eDoRepShowStr($val,$isurl=0){
	$val=ehtmlspecialchars($val,ENT_QUOTES);
	if($isurl==1)
	{
		$val=str_replace('&amp;','&',$val);
	}
	return $val;
}

//处理普通字符
function eDoRepPostComStr($val,$isurl=0){
	$val=CkPostStrIsArray($val);
	$val=ehtmlspecialchars($val,ENT_QUOTES);
	if($isurl==1)
	{
		$val=str_replace('&amp;','&',$val);
	}
	return $val;
}

//处理提交字符
function hRepPostStr($val,$ecms=0,$phck=0){
	$val=CkPostStrIsArray($val);
	if($phck==1)
	{
		CkPostStrCharYh($val);
	}
	if($ecms==1)
	{
		$val=ehtmlspecialchars($val,ENT_QUOTES);
	}
	CkPostStrChar($val);
	$val=AddAddsData($val);
	return $val;
}

//处理提交字符2
function hRepPostStr2($val,$phck=0){
	$val=CkPostStrIsArray($val);
	if($phck==1)
	{
		CkPostStrCharYh($val);
	}
	CkPostStrChar($val);
	$val=AddAddsData($val);
	return $val;
}

//处理编码字符
function CkPostStrChar($val){
	if(substr($val,-1)=="\\")
	{
		exit();
	}
}

//返回转义
function egetzy($n='2'){
	if($n=='rn')
	{
		$str="\r\n";
	}
	elseif($n=='n')
	{
		$str="\n";
	}
	elseif($n=='r')
	{
		$str="\r";
	}
	elseif($n=='t')
	{
		$str="\t";
	}
	elseif($n=='syh')
	{
		$str="\\\"";
	}
	elseif($n=='dyh')
	{
		$str="\'";
	}
	else
	{
		for($i=0;$i<$n;$i++)
		{
			$str.="\\";
		}
	}
	return $str;
}

//验证字符是否空
function CheckValEmpty($val){
	return strlen($val)==0?1:0;
}

//取小数点位数
function efmnump($val,$n=2){
	$val=round($val,$n);
	$val=(float)$val;
	return $val;
}

//返回2位小数金额
function efmmoney($val){
	$ws=100;
	$val=floor($val*$ws)/$ws;
	$val=(float)$val;
	if($val<=0)
	{
		exit();
	}
	return $val;
}

//ckstrlen
function eckDbStrlen_char($str,$ecms=0){
	$len=strlen($str);
	if($ecms==1)//mediumtext
	{
		if($len>16770000)
		{
			$str='';
		}
	}
	elseif($ecms==2)//text
	{
		if($len>65000)
		{
			$str='';
		}
	}
	elseif($ecms==3)//char
	{
		if($len>255)
		{
			$str='';
		}
	}
	else
	{}
	return $str;
}

//验证ID列表是否正确
function eCkIdsListStr($ids,$expstr=',',$ecms=0){
	$ids=str_replace($expstr,'',$ids);
	$rt=eCheckStrType(1,$ids,$ecms);
	return $rt;
}

//SQL返回ID列表
function eSqlToGetids($query,$idtype=0,$maxnum=0,$maxlen=0,$mustlen=1){
	global $empire,$dbtbpre,$class_r;
	//类型
	if($idtype==0)
	{
		$idlen=10;
	}
	elseif($idtype==1)
	{
		$idlen=16;
	}
	elseif($idtype==2)
	{
		$idlen=16;
	}
	else
	{
		$idlen=10;
	}
	$sql=$empire->query($query);
	$num=0;
	$ids='';
	$dh='';
	$jg='|';
	while($r=$empire->fetch($sql))
	{
		$num++;
		if($maxnum)
		{
			if($num>$maxnum)
			{
				break;
			}
		}
		if($maxlen)
		{
			$len=strlen($ids);
			if($len>=$maxlen)
			{
				break;
			}
		}
		//类型
		if($idtype==0)
		{
			$thisid=$r['id'];
		}
		elseif($idtype==1)
		{
			$thisid=$r['classid'].$jg.$r['id'];
		}
		elseif($idtype==2)
		{
			$tid=(int)$class_r[$r['classid']]['tid'];
			$thisid=$tid.$jg.$r['id'];
		}
		else
		{
			$thisid=$r['id'];
		}
		if($mustlen==1)
		{
			$thisid=eStrAddBlank($thisid,$idlen);
		}
		$ids.=$dh.$thisid;
		$dh=',';
	}
	$ecmsr=array();
	$ecmsr['num']=$num;
	$ecmsr['ids']=$ids;
	return $ecmsr;
}

//返回ID列表(固定字符)
function eGetidsLimitMlen($ids,$len,$line,$page){
	if(empty($ids))
	{
		return '';
	}
	$start=$line*$page*($len+1);
	$sublen=$line*($len+1)-1;
	$str=substr($ids,$start,$sublen);
	return $str;
}

//补空格
function eStrAddBlank($id,$maxlen){
	$len=strlen($id);
	$addlen=$maxlen-$len;
	$addb='';
	for($i=0;$i<$addlen;$i++)
	{
		$addb.=' ';
	}
	$id.=$addb;
	return $id;
}

//返回ID列表
function eReturnInids($ids){
	if(empty($ids))
	{
		return 0;
	}
	$dh='';
	$retids='';
	$r=explode(',',$ids);
	$count=count($r);
	for($i=0;$i<$count;$i++)
	{
		$id=(int)$r[$i];
		if(!$id)
		{
			continue;
		}
		$retids.=$dh.$id;
		$dh=',';
	}
	if(empty($retids))
	{
		return 0;
	}
	return $retids;
}

//数组返回ID列表
function eArrayReturnInids($r){
	$r=eCheckEmptyArray($r);
	$count=count($r);
	if(!$count)
	{
		return 0;
	}
	$dh='';
	$retids='';
	for($i=0;$i<$count;$i++)
	{
		$id=(int)$r[$i];
		if(!$id)
		{
			continue;
		}
		$retids.=$dh.$id;
		$dh=',';
	}
	if(empty($retids))
	{
		return 0;
	}
	return $retids;
}

//返回父栏目ID列表
function eReturnInFcids($featherclass){
	if(!$featherclass||$featherclass=='|')
	{
		return 0;
	}
	$cids='';
	$cdh='';
	$fcr=explode('|',$featherclass);
	$fcount=count($fcr);
	for($fi=1;$fi<$fcount-1;$fi++)
	{
		$fcr[$fi]=(int)$fcr[$fi];
		if(!$fcr[$fi])
		{
			continue;
		}
		$cids.=$cdh.$fcr[$fi];
		$cdh=',';
	}
	if(empty($cids))
	{
		return 0;
	}
	return $cids;
}

//返回组列表
function eReturnSetGroups($groupid,$isnum=1){
	$groupid=eCheckEmptyArray($groupid);
	$count=count($groupid);
	if($count==0)
	{
		return '';
	}
	$ids=',';
	for($i=0;$i<$count;$i++)
	{
		if($isnum==1)
		{
			$groupid[$i]=(int)$groupid[$i];
		}
		$ids.=$groupid[$i].',';
	}
	return $ids;
}

//取得表里的模型ID
function eGetTableModids($tid,$tbname){
	global $empire,$dbtbpre;
	$tid=(int)$tid;
	$tbname=RepPostVar($tbname);
	$dh='';
	$mids='';
	$where=$tid?"tid='$tid'":"tbname='$tbname'";
	$sql=$empire->query("select mid from {$dbtbpre}enewsmod where ".$where);
	while($r=$empire->fetch($sql))
	{
		$mids.=$dh.$r['mid'];
		$dh=',';
	}
	if(empty($mids))
	{
		$mids=0;
	}
	return $mids;
}

//替换模板变量字符
function RepTempvarPostStr($val){
	$val=str_replace('[!--','[!---',$val);
	return $val;
}

//替换模板变量字符
function RepTempvarPostStrT($val,$ispagef=0){
	if($ispagef==1)
	{
		$val=str_replace('[!--empirenews.page--]','[!!!-empirecms.page-!!]',$val);
	}
	$val=str_replace('[!--','&#091;!--',$val);
	if($ispagef==1)
	{
		$val=str_replace('[!!!-empirecms.page-!!]','[!--empirenews.page--]',$val);
	}
	return $val;
}

//取得文件扩展名
function GetFiletype($filename){
	$filer=explode(".",$filename);
	$count=count($filer)-1;
	return strtolower(".".RepGetFiletype($filer[$count]));
}

function RepGetFiletype($filetype){
	$filetype=str_replace('|','_',$filetype);
	$filetype=str_replace(',','_',$filetype);
	$filetype=str_replace('.','_',$filetype);
	return $filetype;
}

//取得文件名
function GetFilename($filename){
	if(strstr($filename,"\\"))
	{
		$exp="\\";
	}
	else
	{
		$exp='/';
	}
	$filer=explode($exp,$filename);
	$count=count($filer)-1;
	return $filer[$count];
}

//返回目录函数
function eReturnCPath($path,$ypath=''){
	if(strstr($path,'..')||strstr($path,"\\")||strstr($path,'%')||strstr($path,':'))
	{
		return $ypath;
	}
	return $path;
}

//验证文件名格式函数
function eReturnCkCFile($path,$ecms=1){
	if(strstr($path,'..')||strstr($path,"\\")||strstr($path,'/')||strstr($path,'%')||strstr($path,':'))
	{
		return 0;
	}
	if($ecms==1)
	{
		if(eCheckStrType(5,$path,0)<1)
		{
			return 0;
		}
	}
	return 1;
}

//字符截取函数
function sub($string,$start=0,$length=1,$mode=false,$dot='',$rephtml=0){
	global $ecms_config;
	$strlen=strlen($string);
	if($strlen<=$length)
	{
		return $string;
	}

	if($rephtml==0)
	{
		$string = str_replace(array('&nbsp;','&amp;','&quot;','&lt;','&gt;','&#039;'), array(' ','&','"','<','>',"'"), $string);
	}

	$strcut = '';
	if(strtolower($ecms_config['sets']['pagechar']) == 'utf-8') {

		$n = $tn = $noc = 0;
		while($n < $strlen) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);

	} else {
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	if($rephtml==0)
	{
		$strcut = str_replace(array('&','"','<','>',"'"), array('&amp;','&quot;','&lt;','&gt;','&#039;'), $strcut);
	}

	return $strcut.$dot;
}

//截取字数
function esub($string,$length,$dot='',$rephtml=0){
	return sub($string,0,$length,false,$dot,$rephtml);
}

//取得随机数
function make_password($pw_length){
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

//取得随机数(数字)
function no_make_password($pw_length){
	$low_ascii_bound=48;
	$upper_ascii_bound=57;
	$notuse=array(58,59,60,61,62,63,64,73,79,91,92,93,94,95,96,108,111);
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

//取得随机数(字母)
function abc_make_password($pw_length){
	$low_ascii_bound=65;
	$upper_ascii_bound=122;
	$notuse=array(91,92,93,94,95,96);
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

//取得随机数(多种)
function emorekey_make_password($pw_length,$ecms=0){
	if($ecms==1)//字母
	{
		$low_ascii_bound=65;
		$upper_ascii_bound=90;
		$notuse=array(91);
	}
	elseif($ecms==2)//数字+字母
	{
		$low_ascii_bound=50;
		$upper_ascii_bound=90;
		$notuse=array(58,59,60,61,62,63,64,73,79);
	}
	else//数字
	{
		$low_ascii_bound=48;
		$upper_ascii_bound=57;
		$notuse=array(58);
	}
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

//programno
function EcmsGetProgramNo(){
	$r=explode(' ',microtime());
	$pno=$r[1].$r[0];
	return $pno;
}

//随机数字
function EcmsRandInt($min=0,$max=0,$ecms=0){
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

//随机字符+随机数
function EcmsRandIntStr($min=40,$max=0){
	if(empty($max))
	{
		$max=$min+10;
	}
	$n=EcmsRandInt($min,$max);
	$str=make_password($n);
	return $str;
}

//颜色转RGB
function ToReturnRGB($rgb){
	$rgb=str_replace('#','',ehtmlspecialchars($rgb));
    return array(
        base_convert(substr($rgb,0,2),16,10),
        base_convert(substr($rgb,2,2),16,10),
        base_convert(substr($rgb,4,2),16,10)
    );
}

//验证页码是否有效
function eCheckListPageNo($page,$line,$totalnum){
	$page=(int)$page;
	$line=(int)$line;
	$totalnum=(int)$totalnum;
	if(!$page)
	{
		return '';
	}
	if(!$line)
	{
		return '';
	}
	$totalpage=ceil($totalnum/$line);
	if($page>=$totalpage)
	{
		printerror('ErrorUrl','history.go(-1)',1);
	}
}

//前台分页
function page1($num,$line,$page_line,$start,$page,$search){
	global $fun_r;
	$num=(int)$num;
	$line=(int)$line;
	$page_line=(int)$page_line;
	$start=(int)$start;
	$page=(int)$page;
	if($num<=$line)
	{
		return '';
	}
	$search=RepPostStr($search,1);
	$url=eReturnSelfPage(0).'?page';
	$snum=2;//最小页数
	$totalpage=ceil($num/$line);//取得总页数
	$firststr='<a title="'.$fun_r['trecord'].'">&nbsp;<b>'.$num.'</b> </a>&nbsp;&nbsp;';
	//上一页
	if($page<>0)
	{
		$toppage='<a href="'.$url.'=0'.$search.'">'.$fun_r['startpage'].'</a>&nbsp;';
		$pagepr=$page-1;
		$prepage='<a href="'.$url.'='.$pagepr.$search.'">'.$fun_r['pripage'].'</a>';
	}
	//下一页
	if($page!=$totalpage-1)
	{
		$pagenex=$page+1;
		$nextpage='&nbsp;<a href="'.$url.'='.$pagenex.$search.'">'.$fun_r['nextpage'].'</a>';
		$lastpage='&nbsp;<a href="'.$url.'='.($totalpage-1).$search.'">'.$fun_r['lastpage'].'</a>';
	}
	$starti=$page-$snum<0?0:$page-$snum;
	$no=0;
	for($i=$starti;$i<$totalpage&&$no<$page_line;$i++)
	{
		$no++;
		if($page==$i)
		{
			$is_1="<b>";
			$is_2="</b>";
		}
		else
		{
			$is_1='<a href="'.$url.'='.$i.$search.'">';
			$is_2="</a>";
		}
		$pagenum=$i+1;
		$returnstr.="&nbsp;".$is_1.$pagenum.$is_2;
	}
	$returnstr=$firststr.$toppage.$prepage.$returnstr.$nextpage.$lastpage;
	return $returnstr;
}

//---------- 伪静态 ----------

//返回实际动态内容页地址
function eReturnTrueDtInfoUrl($classid,$id,$ecms=0,$page=0,$tempid=0){
	global $public_r;
	if($ecms==0)
	{
		$infourl=$public_r['newsurl'].'e/action/ShowInfo.php?classid='.$classid.'&id='.$id.($tempid?'&tempid='.$tempid:'').($page?'&page='.$page:'');
	}
	else
	{
		$infourl=$public_r['newsurl'].'e/action/ShowInfo.php?eeid='.($page?$page:0).','.$classid.','.$id.($tempid?','.$tempid:'');
	}
	return $infourl;
}

//返回内容伪静态
function eReturnRewriteInfoUrl($classid,$id,$ecms=0,$tempid=0){
	global $public_r;
	$tempid=(int)$tempid;
	if(empty($public_r['rewriteinfo'])||$tempid)
	{
		$r['pageurl']=$public_r['newsurl']."e/action/ShowInfo.php?classid=$classid&id=$id".($tempid?'&tempid='.$tempid:'');
		$r['rewrite']=0;
	}
	else
	{
		if($ecms==1)
		{
			$r['pageurl']=$public_r['newsurl'].str_replace(array('[!--classid--]','[!--id--]','[!--page--]'),array($classid,$id,0),$public_r['rewriteinfo']);
		}
		else
		{
			$r['pageurl']=$public_r['newsurl'].str_replace(array('[!--classid--]','[!--id--]'),array($classid,$id),$public_r['rewriteinfo']);
		}
		$r['rewrite']=1;
	}
	return $r;
}

//返回栏目列表伪静态
function eReturnRewriteClassUrl($classid,$ecms=0,$tempid=0){
	global $public_r;
	$tempid=(int)$tempid;
	if(empty($public_r['rewriteclass'])||$tempid)
	{
		$r['pageurl']=$public_r['newsurl']."e/action/ListInfo/?classid=$classid".($tempid?'&tempid='.$tempid:'');
		$r['rewrite']=0;
	}
	else
	{
		if($ecms==1)
		{
			$r['pageurl']=$public_r['newsurl'].str_replace(array('[!--classid--]','[!--page--]'),array($classid,0),$public_r['rewriteclass']);
		}
		else
		{
			$r['pageurl']=$public_r['newsurl'].str_replace('[!--classid--]',$classid,$public_r['rewriteclass']);
		}
		$r['rewrite']=1;
	}
	return $r;
}

//返回标题分类列表伪静态
function eReturnRewriteTitleTypeUrl($ttid,$ecms=0,$tempid=0){
	global $public_r;
	$tempid=(int)$tempid;
	if(empty($public_r['rewriteinfotype'])||$tempid)
	{
		$r['pageurl']=$public_r['newsurl']."e/action/InfoType/?ttid=$ttid".($tempid?'&tempid='.$tempid:'');
		$r['rewrite']=0;
	}
	else
	{
		if($ecms==1)
		{
			$r['pageurl']=$public_r['newsurl'].str_replace(array('[!--ttid--]','[!--page--]'),array($ttid,0),$public_r['rewriteinfotype']);
		}
		else
		{
			$r['pageurl']=$public_r['newsurl'].str_replace('[!--ttid--]',$ttid,$public_r['rewriteinfotype']);
		}
		$r['rewrite']=1;
	}
	return $r;
}

//返回TAGS列表伪静态
function eReturnRewriteTagsUrl($tagid,$tagname,$ecms=0){
	global $public_r;
	$tagname=urlencode($tagname);
	if(empty($public_r['rewritetags']))
	{
		$r['pageurl']=$public_r['newsurl']."e/tags/?tagname=".$tagname;
		$r['rewrite']=0;
	}
	else
	{
		if($ecms==1)
		{
			$r['pageurl']=$public_r['newsurl'].str_replace(array('[!--tagname--]','[!--page--]'),array($tagname,0),$public_r['rewritetags']);
		}
		else
		{
			$r['pageurl']=$public_r['newsurl'].str_replace('[!--tagname--]',$tagname,$public_r['rewritetags']);
		}
		$r['rewrite']=1;
	}
	return $r;
}

//返回评论列表伪静态
function eReturnRewritePlUrl($classid,$id,$doaction='doinfo',$myorder=0,$tempid=0,$ecms=0){
	global $public_r;
	if(empty($public_r['rewritepl']))
	{
		if($doaction=='dozt')
		{
			$r['pageurl']=$public_r['plurl']."?doaction=dozt&classid=$classid".($myorder?'&myorder='.$myorder:'').($tempid?'&tempid='.$tempid:'');
		}
		else
		{
			$r['pageurl']=$public_r['plurl']."?classid=$classid&id=$id".($myorder?'&myorder='.$myorder:'').($tempid?'&tempid='.$tempid:'');
		}
		$r['rewrite']=0;
	}
	else
	{
		if($ecms==1)
		{
			$r['pageurl']=$public_r['newsurl'].str_replace(array('[!--doaction--]','[!--classid--]','[!--id--]','[!--page--]','[!--myorder--]','[!--tempid--]'),array($doaction,$classid,$id,0,$myorder,$tempid),$public_r['rewritepl']);
		}
		else
		{
			$r['pageurl']=$public_r['newsurl'].str_replace(array('[!--doaction--]','[!--classid--]','[!--id--]','[!--myorder--]','[!--tempid--]'),array($doaction,$classid,$id,$myorder,$tempid),$public_r['rewritepl']);
		}
		$r['rewrite']=1;
	}
	return $r;
}

//返回父子信息列表伪静态
function eReturnRewriteFzUrl($fztid,$fzid,$cid,$ecms=0,$tempid=0){
	global $public_r;
	$tempid=(int)$tempid;
	if(empty($public_r['rewritefz'])||$tempid)
	{
		$r['pageurl']=$public_r['newsurl']."e/fzinfo/?fztid=$fztid&fzid=$fzid&cid=$cid".($tempid?'&tempid='.$tempid:'');
		$r['rewrite']=0;
	}
	else
	{
		if($ecms==1)
		{
			$r['pageurl']=$public_r['newsurl'].str_replace(array('[!--fztid--]','[!--fzid--]','[!--cid--]','[!--page--]'),array($fztid,$fzid,$cid,0),$public_r['rewritefz']);
		}
		else
		{			
			$r['pageurl']=$public_r['newsurl'].str_replace(array('[!--fztid--]','[!--fzid--]','[!--cid--]'),array($fztid,$fzid,$cid),$public_r['rewritefz']);
		}
		$r['rewrite']=1;
	}
	return $r;
}

//伪静态链接地址中转
function eReturnRewriteLink($type,$classid,$id,$cid=0){
	if($type=='infopage')//信息页
	{
		$url=eReturnRewriteInfoUrl($classid,$id);
	}
	elseif($type=='ttpage')//标题分类页
	{
		$url=eReturnRewriteTitleTypeUrl($classid);
	}
	elseif($type=='tagspage')//Tags列表页
	{
		$url=eReturnRewriteTagsUrl($classid,$id);
	}
	elseif($type=='fzpage')//子信息列表页
	{
		$url=eReturnRewriteFzUrl($classid,$id,$cid);
	}
	else//栏目页
	{
		$url=eReturnRewriteClassUrl($classid);
	}
	return $url;
}

//伪静态替换分页号
function eReturnRewritePageLink($r,$page){
	//动静
	$truepage=$page+1;
	if($r['repagenum']&&$truepage<=$r['repagenum'])
	{
		//文件名
		if(empty($r['dofile']))
		{
			$r['dofile']='index';
		}
		$url=$r['dolink'].$r['dofile'].($truepage==1?'':'_'.$truepage).$r['dotype'];
		return $url;
	}
	if($r['rewrite']==1)
	{
		$url=str_replace('[!--page--]',$page,$r['pageurl']);
	}
	else
	{
		$url=$r['pageurl'].'&page='.$page;
	}
	return $url;
}

//伪静态替换分页号(静态)
function eReturnRewritePageLink2($r,$page){
	if($r['rewrite']==1)
	{
		$url=str_replace('[!--page--]',$page-1,$r['pageurl']);
	}
	else
	{
		$url=$r['pageurl'].'&page='.($page-1);
	}
	return $url;
}

//前台分页(伪静态)
function InfoUsePage($num,$line,$page_line,$start,$page,$search,$add){
	global $fun_r;
	$num=(int)$num;
	$line=(int)$line;
	$page_line=(int)$page_line;
	$start=(int)$start;
	$page=(int)$page;
	if($num<=$line)
	{
		return '';
	}
	$search=RepPostStr($search,1);
	$snum=2;//最小页数
	$totalpage=ceil($num/$line);//取得总页数
	$firststr='<a title="'.$fun_r['trecord'].'">&nbsp;<b>'.$num.'</b> </a>&nbsp;&nbsp;';
	//上一页
	if($page<>0)
	{
		$toppage='<a href="'.eReturnRewritePageLink($add,0).'">'.$fun_r['startpage'].'</a>&nbsp;';
		$pagepr=$page-1;
		$prepage='<a href="'.eReturnRewritePageLink($add,$pagepr).'">'.$fun_r['pripage'].'</a>';
	}
	//下一页
	if($page!=$totalpage-1)
	{
		$pagenex=$page+1;
		$nextpage='&nbsp;<a href="'.eReturnRewritePageLink($add,$pagenex).'">'.$fun_r['nextpage'].'</a>';
		$lastpage='&nbsp;<a href="'.eReturnRewritePageLink($add,$totalpage-1).'">'.$fun_r['lastpage'].'</a>';
	}
	$starti=$page-$snum<0?0:$page-$snum;
	$no=0;
	for($i=$starti;$i<$totalpage&&$no<$page_line;$i++)
	{
		$no++;
		if($page==$i)
		{
			$is_1="<b>";
			$is_2="</b>";
		}
		else
		{
			$is_1='<a href="'.eReturnRewritePageLink($add,$i).'">';
			$is_2="</a>";
		}
		$pagenum=$i+1;
		$returnstr.="&nbsp;".$is_1.$pagenum.$is_2;
	}
	$returnstr=$firststr.$toppage.$prepage.$returnstr.$nextpage.$lastpage;
	return $returnstr;
}

//时间转换函数
function to_time($datetime){
	if(strlen($datetime)==10)
	{
		$datetime.=" 00:00:00";
	}
	$r=explode(" ",$datetime);
	$t=explode("-",$r[0]);
	$k=explode(":",$r[1]);
	$dbtime=@mktime(intval($k[0]),intval($k[1]),intval($k[2]),intval($t[1]),intval($t[2]),intval($t[0]));
	return intval($dbtime);
}

//时期转日期
function date_time($time,$format="Y-m-d H:i:s"){
	$threadtime=date($format,$time);
	return $threadtime;
}

//格式化日期
function format_datetime($newstime,$format){
	if($newstime=="0000-00-00 00:00:00")
	{return $newstime;}
	$time=is_numeric($newstime)?$newstime:to_time($newstime);
	$newdate=date_time($time,$format);
	return $newdate;
}

//时间转换函数
function to_date($date){
	$date.=" 00:00:00";
	$r=explode(" ",$date);
	$t=explode("-",$r[0]);
	$k=explode(":",$r[1]);
	$dbtime=@mktime(intval($k[0]),intval($k[1]),intval($k[2]),intval($t[1]),intval($t[2]),intval($t[0]));
	return intval($dbtime);
}

//选择时间
function ToChangeTime($time,$day){
	$truetime=$time-$day*24*3600;
	$date=date_time($truetime,"Y-m-d");
	return $date;
}

//验证目录
function ecmspathbmd($path,$ecms=1){
	global $ecms_config;
	$ckpath=str_replace("\\","/",$path);
	if(stristr($ckpath,'//'))
	{
		return 1;
	}
	$ckpath='/'.$ckpath.'/';
	if($ckpath=="/../../"||$ckpath=="/../../d/file/"||$ckpath=="/../..//"||$ckpath=="/../../d/file//")
	{
		return 1;
	}
	if(stristr($ckpath,'/e/')||stristr($ckpath,'/'.$ecms_config['esafe']['hfadminpath'].'/'))
	{
		return 1;
	}
	return 0;
}

//删除文件
function DelFiletext($filename){
	if(ecmspathbmd($filename,1))
	{
		return '';
	}
	@unlink($filename);
}

//删除文件2
function DelFiletext2($filename){
	@unlink($filename);
}

//取得网页内容
function ReadUrltext($url,$ecms=0){
	$url=trim($url);
	if(!strstr($url,'://'))
	{
		return '';
	}
	if(!eToCheckIsUrl2($url))
	{
		return '';
	}
	$string='';
	$htmlfp=@fopen($url,"rb");
	while($data=@fread($htmlfp,500000))
	{
		$string.=$data;
	}
	@fclose($htmlfp);
	return $string;
}

//取得网页内容(curl)
function eCurlReadtext($url,$data=null,$ecms=0){
	if(!function_exists('curl_init'))
	{
		return '';
	}
	$url=trim($url);
	if(!strstr($url,'://'))
	{
		return '';
	}
	if(!eToCheckIsUrl2($url))
	{
		return '';
	}
	$string='';
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
	if(!empty($data))
	{
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
	}
	$string=curl_exec($ch);
	curl_close($ch);
	return $string;
}

//取得文件内容
function ReadFiletext($filepath){
	$filepath=trim($filepath);
	if(strstr($filepath,'://'))
	{
		return '';
	}
	$htmlfp=@fopen($filepath,"rb");
	$string=@fread($htmlfp,@filesize($filepath));
	@fclose($htmlfp);
	return $string;
}

//取得文件内容(兼容)
function ReadFiletextAll($filepath){
	$filepath=trim($filepath);
	$ishttp=0;
	if(strstr($filepath,'://'))
	{
		if(!eToCheckIsUrl2($filepath))
		{
			return '';
		}
		$ishttp=1;
	}
	$htmlfp=@fopen($filepath,"rb");
	//远程
	if($ishttp==1)
	{
		while($data=@fread($htmlfp,500000))
	    {
			$string.=$data;
		}
	}
	//本地
	else
	{
		$string=@fread($htmlfp,@filesize($filepath));
	}
	@fclose($htmlfp);
	return $string;
}

//写文件
function WriteFiletext($filepath,$string){
	global $public_r;
	$string=stripSlashes($string);
	$fp=@fopen($filepath,"wb");
	@fputs($fp,$string);
	@fclose($fp);
	if(empty($public_r['filechmod']))
	{
		@chmod($filepath,0777);
	}
}

//写文件
function WriteFiletext_n($filepath,$string){
	global $public_r;
	$fp=@fopen($filepath,"wb");
	@fputs($fp,$string);
	@fclose($fp);
	if(empty($public_r['filechmod']))
	{
		@chmod($filepath,0777);
	}
}

//标题属性后
function DoTitleFont($titlefont,$title){
	if(empty($titlefont))
	{
		return $title;
	}
	$r=explode(',',$titlefont);
	if(!empty($r[0]))
	{
		$title="<font color='".$r[0]."'>".$title."</font>";
	}
	if(empty($r[1]))
	{return $title;}
	//粗体
	if(strstr($r[1],"b"))
	{$title="<strong>".$title."</strong>";}
	//斜体
	if(strstr($r[1],"i"))
	{$title="<i>".$title."</i>";}
	//删除线
	if(strstr($r[1],"s"))
	{$title="<s>".$title."</s>";}
	return $title;
}

//返回头条级别名称权限
function ReturnFirsttitleNameCkLevel($r,$groupid,$classid){
	if(defined('EmpireCMSAdmin'))
	{
		if($r['groupid'])
		{
			if(!strstr($r['groupid'],','.$groupid.','))
			{
				return 0;
			}
		}
	}
	if($classid)
	{
		if($r['showcid'])
		{
			if(!strstr($r['showcid'],','.$classid.','))
			{
				return 0;
			}
		}
		if($r['hiddencid'])
		{
			if(strstr($r['hiddencid'],','.$classid.','))
			{
				return 0;
			}
		}
	}
	else
	{
		if($r['showall']==1)
		{
			return 0;
		}
	}
	return 1;
}

//返回头条级别名称
function ReturnFirsttitleNameList($firsttitle,$isgood){
	global $empire,$dbtbpre,$lur,$classid,$class_r;
	$classid=(int)$classid;
	if($classid&&!$class_r[$classid]['islast'])
	{
		$ckclassid=0;
	}
	else
	{
		$ckclassid=$classid;
	}
	$groupid=(int)$lur['groupid'];
	$first_r=array();//头条
	$ftn='';
	$good_r=array();//推荐
	$gn='';
	$sql=$empire->query("select tname,ttype,levelid,groupid,showall,showcid,hiddencid from {$dbtbpre}enewsgoodtype order by myorder desc,levelid");
	while($r=$empire->fetch($sql))
	{
		if($r['ttype']==1)//头条
		{
			$first_r[$r['levelid']]=$r['tname'];
			$selected='';
			if($r['levelid']==$firsttitle)
			{
				$selected=' selected';
			}
			if(ReturnFirsttitleNameCkLevel($r,$groupid,$ckclassid))
			{
				$ftn.='<option value="'.$r['levelid'].'"'.$selected.'>'.$r['tname'].'</option>';
			}
		}
		else//推荐
		{
			$good_r[$r['levelid']]=$r['tname'];
			$selected='';
			if($r['levelid']==$isgood)
			{
				$selected=' selected';
			}
			if(ReturnFirsttitleNameCkLevel($r,$groupid,$ckclassid))
			{
				$gn.='<option value="'.$r['levelid'].'"'.$selected.'>'.$r['tname'].'</option>';
			}
		}
	}
	$ret_r['ftname']=$ftn;
	$ret_r['ftr']=$first_r;
	$ret_r['igname']=$gn;
	$ret_r['igr']=$good_r;
	return $ret_r;
}

//返回下拉列表
function PubReturnSelectClass($tb,$idf,$namef,$deff='',$defid=0,$myorder='',$where=''){
	global $empire,$dbtbpre;
	$selectf=$idf.','.$namef;
	if($deff)
	{
		$selectf.=','.$deff;
	}
	$orderf='';
	if($myorder)
	{
		$orderf=' order by '.$myorder;
	}
	$wheref='';
	if($where)
	{
		$wheref=' where '.$where;
	}
	//子类
	$csql=$empire->query("select ".$selectf." from ".$dbtbpre.$tb.$wheref.$orderf);
	$chcs='';
	while($cr=$empire->fetch($csql))
	{
		$selected='';
		if($cr[$idf]==$defid)
		{
			$selected=' selected';
		}
		else
		{
			if($cr[$deff])
			{
				$selected=' selected';
			}
		}
		$chcs.="<option value='".$cr[$idf]."'".$selected.">".$cr[$namef]."</option>";
	}
	return $chcs;
}

//替换全角逗号
function DoReplaceQjDh($text){
	$text=str_replace('，',',',$text);
	$text=str_replace('、',',',$text);
	$text=str_replace('；',',',$text);
	$text=str_replace(';',',',$text);
	return $text;
}

//半角转全角
function eDoBjToQj($text){
	$text=str_replace(array('&','"','\'','<','>'),array('＆','”','’','＜','＞'),$text);
	return $text;
}

//给信息字段转全角
function eDoInfoTbfToQj($tbname,$f,$fval,$qjf){
	global $public_r;
	if(empty($qjf))
	{
		return $fval;
	}
	if(!stristr('|'.$qjf.'|','|'.$tbname.'.'.$f.'|'))
	{
		return $fval;
	}
	$fval=eDoBjToQj($fval);
	return $fval;
}

//建立目录函数
function DoMkdir($path){
	global $public_r;
	//不存在则建立
	if(!file_exists($path))
	{
		//安全模式
		if($public_r['phpmode'])
		{
			$pr[0]=$path;
			FtpMkdir($ftpid,$pr,0777);
			$mk=1;
		}
		else
		{
			$mk=@mkdir($path,0777);
			@chmod($path,0777);
		}
		if(empty($mk))
		{
			echo Ecms_eReturnShowMkdir($path);
			printerror("CreatePathFail","history.go(-1)");
		}
	}
	return true;
}

//建立上级目录
function DoFileMkDir($file){
	$path=dirname($file.'empirecms.txt');
	DoMkdir($path);
}

//设置上传文件权限
function DoChmodFile($file){
	global $public_r;
	if($public_r['tfilechmod']==1)
	{
		@chmod($file,0777);
	}
	elseif($public_r['tfilechmod']==2)
	{
		@chmod($file,0766);
	}
	elseif($public_r['tfilechmod']==3)
	{
		@chmod($file,0755);
	}
	else
	{}
}

//设置上传文件权限2
function DoChmodFileTwo($file){
	global $public_r;
	if($public_r['filechmod']!=1)
	{
		@chmod($file,0777);
	}
}

//替换斜扛
function DoRepFileXg($file){
	$file=str_replace("\\","/",$file);
	return $file;
}

//返回栏目链接字符串
function ReturnClassLink($classid){
	global $class_r,$public_r,$fun_r;
	if(empty($class_r[$classid]['featherclass']))
	{$class_r[$classid]['featherclass']="|";}
	$r=explode("|",$class_r[$classid]['featherclass'].$classid."|");
	$string="<a href=\"".ReturnSiteIndexUrl()."\">".$fun_r['index']."</a>";
	for($i=1;$i<count($r)-1;$i++)
	{
		//moreport
		if(Moreport_ReturnMustDt())
		{
			$class_r[$r[$i]]['listdt']=1;
		}
		//静态列表
		if(empty($class_r[$r[$i]]['listdt']))
		{
			//无绑定域名
			if(empty($class_r[$r[$i]]['classurl']))
			{$url=$public_r['newsurl'].$class_r[$r[$i]]['classpath']."/";}
			else
			{$url=$class_r[$r[$i]]['classurl'];}
		}
		else
		{
			$rewriter=eReturnRewriteClassUrl($r[$i],1);
			$url=$rewriter['pageurl'];
		}
		$string.="&nbsp;".$public_r['navfh']."&nbsp;<a href=\"".$url."\">".$class_r[$r[$i]]['classname']."</a>";
	}
	return $string;
}

//返回专题链接字符串
function ReturnZtLink($ztid){
	global $class_zr,$public_r,$fun_r;
	$string="<a href=\"".ReturnSiteIndexUrl()."\">".$fun_r['index']."</a>";
	//无绑定域名
	if(empty($class_zr[$ztid]['zturl']))
	{$url=$public_r['newsurl'].$class_zr[$ztid]['ztpath']."/";}
	else
	{$url=$class_zr[$ztid]['zturl'];}
    $string.="&nbsp;".$public_r['navfh']."&nbsp;<a href=\"".$url."\">".$class_zr[$ztid]['ztname']."</a>";
	return $string;
}

//返回标题分类链接字符串
function ReturnInfoTypeLink($typeid){
	global $class_tr,$public_r,$fun_r;
	$string="<a href=\"".ReturnSiteIndexUrl()."\">".$fun_r['index']."</a>";
	//moreport
	if(Moreport_ReturnMustDt())
	{
		$class_tr[$typeid]['listdt']=1;
	}
	if($class_tr[$typeid]['listdt'])
	{
		$rewriter=eReturnRewriteTitleTypeUrl($typeid,1);
		$url=$rewriter['pageurl'];
	}
	else
	{
		$url=$public_r['newsurl'].$class_tr[$typeid]['tpath']."/";
	}
    $string.="&nbsp;".$public_r['navfh']."&nbsp;<a href=\"".$url."\">".$class_tr[$typeid]['tname']."</a>";
	return $string;
}

//返回单页链接字符串
function ReturnUserPLink($title,$titleurl){
	global $public_r,$fun_r;
	$string='<a href="'.ReturnSiteIndexUrl().'">'.$fun_r['index'].'</a>&nbsp;'.$public_r['navfh'].'&nbsp;'.$title;
	return $string;
}

//返回标题链接(静态)
function sys_ReturnBqTitleLink($r){
	global $public_r,$ecms_config;
	if(empty($r['isurl']))
	{
		//moreport
		if(Moreport_ReturnMustDt())
		{
			return Moreport_ReturnTitleUrl($r['classid'],$r['id']);
		}
		if(!empty($public_r['newsurlmp']))
		{
			return str_replace($public_r['newsurlmp'],$public_r['newsurl'],$r['titleurl']);
		}
		return $r['titleurl'];
	}
	else
	{
		if($public_r['opentitleurl'])
		{
			$titleurl=$r['titleurl'];
		}
		else
		{
			$titleurl=$public_r['newsurl'].'e/public/jump/?classid='.$r['classid'].'&id='.$r['id'];
		}
		return $titleurl;
	}
}

//返回标题链接(动态)
function sys_ReturnBqTitleLinkDt($r){
	global $public_r,$class_r;
	if(empty($r['isurl']))
	{
		if($class_r[$r['classid']]['showdt']==1)//动态生成
		{
			$titleurl=$public_r['newsurl']."e/action/ShowInfo/?classid=".$r['classid']."&id=".$r['id'];
			return $titleurl;
		}
		elseif($class_r[$r['classid']]['showdt']==2)
		{
			$rewriter=eReturnRewriteInfoUrl($r['classid'],$r['id'],1);
			$titleurl=$rewriter['pageurl'];
			return $titleurl;
		}
		if($class_r[$r['classid']]['filename']==3)
		{
			$filename=ReturnInfoSPath($r['filename']);
		}
		else
		{
			$filetype=$r['groupid']?'.php':$class_r[$r['classid']]['filetype'];
			$filename=$r['filename'].$filetype;
		}
		$iclasspath=ReturnSaveInfoPath($r['classid'],$r['id']);
		$newspath=empty($r['newspath'])?'':$r['newspath']."/";
		if($class_r[$r['classid']]['classurl']&&$class_r[$r['classid']]['ipath']=='')//域名
		{
			$titleurl=$class_r[$r['classid']]['classurl']."/".$newspath.$filename;
		}
		else
		{
			$titleurl=$public_r['newsurl'].$iclasspath.$newspath.$filename;
		}
	}
	else
	{
		$titleurl=$r['titleurl'];
	}
	return addslashes($titleurl);
}

//中转取得信息地址
function GotoGetTitleUrl($classid,$id,$newspath,$filename,$groupid,$isurl,$titleurl){
	$r['classid']=$classid;
	$r['id']=$id;
	$r['newspath']=$newspath;
	$r['filename']=$filename;
	$r['groupid']=$groupid;
	$r['isurl']=$isurl;
	$r['titleurl']=$titleurl;
	$infourl=sys_ReturnBqTitleLinkDt($r);
	return $infourl;
}

//返回标题链接(触发)
function sys_ReturnBqAutoTitleLink($r){
	global $public_r,$class_r;
	if(empty($r['isurl']))
	{
		if($class_r[$r['classid']]['showdt']==2)
		{
			$rewriter=eReturnRewriteInfoUrl($r['classid'],$r['id'],1);
			$titleurl=$rewriter['pageurl'];
			return $titleurl;
		}
		if($class_r[$r['classid']]['filename']==3)
		{
			$filename=ReturnInfoSPath($r['filename']);
		}
		else
		{
			$filetype=$r['groupid']?'.php':$class_r[$r['classid']]['filetype'];
			$filename=$r['filename'].$filetype;
		}
		$iclasspath=ReturnSaveInfoPath($r['classid'],$r['id']);
		$newspath=empty($r['newspath'])?'':$r['newspath']."/";
		if($class_r[$r['classid']]['classurl']&&$class_r[$r['classid']]['ipath']=='')//域名
		{
			$titleurl=$class_r[$r['classid']]['classurl']."/".$newspath.$filename;
		}
		else
		{
			$titleurl=$public_r['newsurl'].$iclasspath.$newspath.$filename;
		}
	}
	else
	{
		if($public_r['opentitleurl'])
		{
			$titleurl=$r['titleurl'];
		}
		else
		{
			$titleurl=$public_r['newsurl']."e/public/jump/?classid=".$r['classid']."&id=".$r['id'];
		}
	}
	return $titleurl;
}

//返回内容页地址前缀
function ReturnInfoPageQz($r){
	global $public_r,$class_r;
	$ret_r['titleurl']='';
	$ret_r['filetype']='';
	$ret_r['nametype']=0;
	//动态页面
	if($class_r[$r['classid']]['showdt']==2)
	{
		$rewriter=eReturnRewriteInfoUrl($r['classid'],$r['id'],0);
		$ret_r['pageurl']=$rewriter['pageurl'];
		$ret_r['rewrite']=$rewriter['rewrite'];
		$ret_r['titleurl']=$rewriter['pageurl'];
		$ret_r['filetype']='';
		$ret_r['nametype']=1;
		return $ret_r;
	}
	//静态页面
	$ret_r['filetype']=$r['groupid']?'.php':$class_r[$r['classid']]['filetype'];
	$filename=$r['filename'];
	$iclasspath=ReturnSaveInfoPath($r['classid'],$r['id']);
	$newspath=empty($r['newspath'])?'':$r['newspath']."/";
	if($class_r[$r['classid']]['classurl']&&$class_r[$r['classid']]['ipath']=='')//域名
	{
		$ret_r['titleurl']=$class_r[$r['classid']]['classurl']."/".$newspath.$filename;
	}
	else
	{
		$ret_r['titleurl']=$public_r['newsurl'].$iclasspath.$newspath.$filename;
	}
	return $ret_r;
}

//返回栏目链接
function sys_ReturnBqClassname($r,$have_class=0){
	global $public_r,$class_r;
	if($have_class)
	{
		//moreport
		if(Moreport_ReturnMustDt())
		{
			$class_r[$r['classid']]['listdt']=1;
		}
		//外部栏目
		if($class_r[$r['classid']]['wburl'])
		{
			$classurl=$class_r[$r['classid']]['wburl'];
		}
		//动态列表
		elseif($class_r[$r['classid']]['listdt'])
		{
			$rewriter=eReturnRewriteClassUrl($r['classid'],1);
			$classurl=$rewriter['pageurl'];
		}
		elseif($class_r[$r['classid']]['classurl'])
		{
			$classurl=$class_r[$r['classid']]['classurl'];
		}
		else
		{
			$classurl=$public_r['newsurl'].$class_r[$r['classid']]['classpath']."/";
		}
		if(empty($class_r[$r['classid']]['bname']))
		{$classname=$class_r[$r['classid']]['classname'];}
		else
		{$classname=$class_r[$r['classid']]['bname'];}
		$myadd="[<a href=".$classurl.">".$classname."</a>]";
		//只返回链接
		if($have_class==9)
		{$myadd=$classurl;}
	}
	else
	{$myadd="";}
	return $myadd;
}

//返回栏目链接(id)
function sys_eReturnBqClassUrl($classid){
	$r['classid']=$classid;
	return sys_ReturnBqClassname($r,9);
}

//返回专题链接
function sys_ReturnBqZtname($r){
	global $public_r,$class_zr;
	if($class_zr[$r['ztid']]['zturl'])
	{
		$zturl=$class_zr[$r['ztid']]['zturl'];
    }
	else
	{
		$zturl=$public_r['newsurl'].$class_zr[$r['ztid']]['ztpath']."/";
    }
	return $zturl;
}

//返回专题链接(id)
function sys_eReturnBqZtUrl($ztid){
	$r['ztid']=$ztid;
	return sys_ReturnBqZtname($r);
}

//返回专题子类链接
function sys_ReturnBqZtTypeUrl($r){
	global $public_r,$class_zr;
	if($class_zr[$r['ztid']]['zturl'])
	{
		$zturl=$class_zr[$r['ztid']]['zturl'].'/'.$r['tfile'].$r['ttype'];
    }
	else
	{
		$zturl=$public_r['newsurl'].$class_zr[$r['ztid']]['ztpath'].'/'.$r['tfile'].$r['ttype'];
    }
	return $zturl;
}

//返回标题分类链接
function sys_ReturnBqInfoTypeUrl($typeid){
	global $public_r,$class_tr;
	//moreport
	if(Moreport_ReturnMustDt())
	{
		$class_tr[$typeid]['listdt']=1;
	}
	if($class_tr[$typeid]['listdt'])
	{
		$rewriter=eReturnRewriteTitleTypeUrl($typeid,1);
		$url=$rewriter['pageurl'];
	}
	else
	{
		$url=$public_r['newsurl'].$class_tr[$typeid]['tpath']."/";
	}
	return $url;
}

//文件大小格式转换
function ChTheFilesize($size){
	if($size>=1024*1024)//MB
	{
		$filesize=number_format($size/(1024*1024),2,'.','')." MB";
	}
	elseif($size>=1024)//KB
	{
		$filesize=number_format($size/1024,2,'.','')." KB";
	}
	else
	{
		$filesize=$size." Bytes";
	}
	return $filesize;
}

//取得表记录
function eGetTableRowNum($tbname){
	global $empire,$dbtbpre;
	$num=do_dbTableRowNum($tbname);
	return $num;
}

//更新栏目信息数
function AddClassInfos($classid,$addallstr,$addstr,$checked=1){
	global $empire,$dbtbpre;
	$updatestr='';
	$dh='';
	if($addallstr)
	{
		$updatestr.='allinfos=allinfos'.$addallstr;
		$dh=',';
	}
	if($addstr)
	{
		if($checked)
		{
			$updatestr.=$dh.'infos=infos'.$addstr;
		}
	}
	if(empty($updatestr))
	{
		return '';
	}
	$classid=(int)$classid;
	$empire->query("update {$dbtbpre}enewsclass set ".$updatestr." where classid='$classid'".do_dblimit_upone());
}

//返回栏目信息数
function ReturnClassInfoNum($cr,$ecms=0){
	global $empire,$dbtbpre,$class_r;
	if($cr['islast'])
	{
		$num=$ecms==0?$cr['infos']:$cr['allinfos'];
	}
	else
	{
		$f=$ecms==0?'infos':'allinfos';
		$num=$empire->gettotal("select sum(".$f.") as total from {$dbtbpre}enewsclass where ".ReturnClass($class_r[$cr['classid']]['sonclass']));
		$num=(int)$num;
	}
	return $num;
}

//重置栏目信息数
function ResetClassInfos($classid){
	global $empire,$dbtbpre,$class_r;
	$classid=(int)$classid;
	$infos=$empire->gettotal("select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid]['tbname']." where classid='$classid'");
	$checkinfos=$empire->gettotal("select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid]['tbname']."_check where classid='$classid'");
	$allinfos=$infos+$checkinfos;
	$empire->query("update {$dbtbpre}enewsclass set allinfos='$allinfos',infos='$infos' where classid='$classid'".do_dblimit_upone());
}

//单信息评论数
function UpdateSingleInfoPlnum($classid,$id,$checked=1){
	global $empire,$dbtbpre,$class_r;
	$classid=(int)$classid;
	$id=(int)$id;
	$tbname=$class_r[$classid]['tbname'];
	if(empty($tbname))
	{
		return '';
	}
	$infotb=ReturnInfoMainTbname($tbname,$checked);
	$r=$empire->fetch1("select id,restb,plnum from ".$infotb." where id='$id'".do_dblimit_one());
	if(empty($r['restb']))
	{
		return '';
	}
	$pubid=ReturnInfoPubid($classid,$id);
	$plnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspl_".$r['restb']." where pubid='$pubid'".do_dblimit_cone());
	if($plnum==$r['plnum'])
	{
		return '';
	}
	$empire->query("update ".$infotb." set plnum='$plnum' where id='$id'".do_dblimit_upone());
}

//信息数统计加1
function DoUpdateAddDataNum($type='info',$stb='',$addnum=1){
	global $empire,$dbtbpre;
	if($type=='info')//信息
	{
		$lasttimef='lasttimeinfo';
		$lastnumf='lastnuminfo';
		$lastnumtbf='lastnuminfotb';
		$todaytimef='todaytimeinfo';
		$todaynumf='todaynuminfo';
		$yesterdaynumf='yesterdaynuminfo';
		$sqladdf=',todaytimeinfo,todaytimepl,todaynuminfo,todaynumpl';
	}
	elseif($type=='pl')//评论
	{
		$lasttimef='lasttimepl';
		$lastnumf='lastnumpl';
		$lastnumtbf='lastnumpltb';
		$todaytimef='todaytimepl';
		$todaynumf='todaynumpl';
		$yesterdaynumf='yesterdaynumpl';
		$sqladdf=',todaytimepl,todaytimeinfo,todaynuminfo,todaynumpl';
	}
	else
	{
		return '';
	}
	$stb=(int)$stb;
	$addnum=(int)$addnum;
	$sqladdupdate='';
	$time=time();
	$pur=$empire->fetch1("select ".$lasttimef.",".$lastnumtbf.$sqladdf." from {$dbtbpre}enewspublic_up".do_dblimit_one());
	if($stb)
	{
		if(empty($pur[$lastnumtbf]))
		{
			$pur[$lastnumtbf]='|';
		}
		if(strstr($pur[$lastnumtbf],'|'.$stb.','))
		{
			$numr=explode('|'.$stb.',',$pur[$lastnumtbf]);
			$numrt=explode('|',$numr[1]);
			$newnum=$numrt[0]+$addnum;
			$tbnums=str_replace('|'.$stb.','.$numrt[0].'|','|'.$stb.','.$newnum.'|',$pur[$lastnumtbf]);
		}
		else
		{
			$tbnums=$pur[$lastnumtbf].$stb.','.$addnum.'|';
		}
		$tbnums=RepPostVar($tbnums);
		$sqladdupdate.=",".$lastnumtbf."='".$tbnums."'";
	}
	//今日统计
	if($sqladdf)
	{
		$todaydate=date('Y-m-d');
		if($todaydate<>date('Y-m-d',$pur['todaytimeinfo'])||$todaydate<>date('Y-m-d',$pur['todaytimepl']))
		{
			if($type=='info')
			{
				$todaynuminfo=$addnum;
				$todaynumpl=0;
			}
			else
			{
				$todaynuminfo=0;
				$todaynumpl=$addnum;
			}
			$yesterdaynuminfo=$pur['todaynuminfo'];
			$yesterdaynumpl=$pur['todaynumpl'];
			if($todaydate<>date('Y-m-d',$pur['todaytimeinfo']+24*3600))
			{
				$yesterdaynuminfo=0;
			}
			if($todaydate<>date('Y-m-d',$pur['todaytimepl']+24*3600))
			{
				$yesterdaynumpl=0;
			}
			$todaynuminfo=(int)$todaynuminfo;
			$todaynumpl=(int)$todaynumpl;
			$yesterdaynuminfo=(int)$yesterdaynuminfo;
			$yesterdaynumpl=(int)$yesterdaynumpl;
			$sqladdupdate.=",todaytimeinfo='$time',todaytimepl='$time',todaynuminfo='$todaynuminfo',todaynumpl='$todaynumpl',yesterdaynuminfo='$yesterdaynuminfo',yesterdaynumpl='$yesterdaynumpl'";
		}
		else
		{
			$sqladdupdate.=",".$todaynumf."=".$todaynumf."+".$addnum;
		}
	}
	$empire->query("update {$dbtbpre}enewspublic_up set ".$lastnumf."=".$lastnumf."+".$addnum.$sqladdupdate."".do_dblimit_upone());
}

//重置信息数统计
function DoResetAddDataNum($type='info'){
	global $empire,$dbtbpre;
	if($type=='info')//信息
	{
		$lasttimef='lasttimeinfo';
		$lastnumf='lastnuminfo';
		$lastnumtbf='lastnuminfotb';
	}
	elseif($type=='pl')//评论
	{
		$lasttimef='lasttimepl';
		$lastnumf='lastnumpl';
		$lastnumtbf='lastnumpltb';
	}
	else
	{
		return '';
	}
	$time=time();
	$empire->query("update {$dbtbpre}enewspublic_up set ".$lasttimef."='$time',".$lastnumf."=0,".$lastnumtbf."=''".do_dblimit_upone());
}

//更新昨日信息数统计
function DoUpdateYesterdayAddDataNum(){
	global $empire,$dbtbpre;
	$pur=$empire->fetch1("select * from {$dbtbpre}enewspublic_up".do_dblimit_one());
	$todaydate=date('Y-m-d');
	if($todaydate==date('Y-m-d',$pur['todaytimeinfo'])&&$todaydate==date('Y-m-d',$pur['todaytimepl']))
	{
		return '';
	}
	$yesterdaynuminfo=$pur['todaynuminfo'];
	$yesterdaynumpl=$pur['todaynumpl'];
	if($todaydate<>date('Y-m-d',$pur['todaytimeinfo']+24*3600))
	{
		$yesterdaynuminfo=0;
	}
	if($todaydate<>date('Y-m-d',$pur['todaytimepl']+24*3600))
	{
		$yesterdaynumpl=0;
	}
	$time=time();
	$yesterdaynuminfo=(int)$yesterdaynuminfo;
	$yesterdaynumpl=(int)$yesterdaynumpl;
	$empire->query("update {$dbtbpre}enewspublic_up set todaytimeinfo='$time',todaytimepl='$time',todaynuminfo=0,yesterdaynuminfo='$yesterdaynuminfo',todaynumpl=0,yesterdaynumpl='$yesterdaynumpl'".do_dblimit_upone());
}

//返回栏目自定义字段内容
function ReturnClassAddField($classid,$f){
	global $empire,$dbtbpre,$navclassid;
	if(empty($classid))
	{
		$classid=$navclassid;
	}
	$fr=$empire->fetch1("select ".$f." from {$dbtbpre}enewsclassadd where classid='$classid'".do_dblimit_one());
	if(strstr($f,','))
	{
		return $fr;
	}
	else
	{
		return $fr[$f];
	}
}

//返回专题自定义字段内容
function ReturnZtAddField($classid,$f){
	global $empire,$dbtbpre,$navclassid;
	if(empty($classid))
	{
		$classid=$navclassid;
	}
	$fr=$empire->fetch1("select ".$f." from {$dbtbpre}enewsztadd where ztid='$classid'".do_dblimit_one());
	if(strstr($f,','))
	{
		return $fr;
	}
	else
	{
		return $fr[$f];
	}
}

//返回扩展变量值
function ReturnPublicAddVar($myvar){
	global $empire,$dbtbpre;
	if(strstr($myvar,','))
	{
		$myvr=explode(',',$myvar);
		$count=count($myvr);
		for($i=0;$i<$count;$i++)
		{
			$v=$myvr[$i];
			$vr=$empire->fetch1("select varvalue from {$dbtbpre}enewspubvar where myvar='$v'".do_dblimit_one());
			$ret_vr[$v]=$vr['varvalue'];
		}
		return $ret_vr;
	}
	else
	{
		$vr=$empire->fetch1("select varvalue from {$dbtbpre}enewspubvar where myvar='$myvar'".do_dblimit_one());
		return $vr['varvalue'];
	}
}

//返回排序字段
function ReturnDoOrderF($mid,$orderby,$myorder){
	global $emod_r;
	$mid=(int)$mid;
	$orderby=str_replace(',','',$orderby);
	$orderf=',newstime,id,onclick,totaldown,plnum';
	if(!empty($emod_r[$mid]['orderf']))
	{
		$orderf.=$emod_r[$mid]['orderf'];
	}
	else
	{
		$orderf.=',';
	}
	if(strstr($orderf,','.$orderby.','))
	{
		$rr['returnorder']=$orderby;
		$rr['returnf']=$orderby;
	}
	else
	{
		$rr['returnorder']='newstime';
		$rr['returnf']='newstime';
	}
	if(empty($myorder))
	{
		$rr['returnorder'].=' desc';
	}
	return $rr;
}

//返回置顶
function ReturnSetTopSql($ecms){
	global $public_r;
	if(empty($public_r['settop']))
	{
		return '';
	}
	$top='istop desc,';
	if($ecms=='list')
	{
		if($public_r['settop']==1||$public_r['settop']==4||$public_r['settop']==5||$public_r['settop']==6)
		{
			return $top;
		}
	}
	elseif($ecms=='bq')
	{
		if($public_r['settop']==2||$public_r['settop']==4||$public_r['settop']==5||$public_r['settop']==7)
		{
			return $top;
		}
	}
	elseif($ecms=='js')
	{
		if($public_r['settop']==3||$public_r['settop']==4||$public_r['settop']==6||$public_r['settop']==7)
		{
			return $top;
		}
	}
	return '';
}

//返回优化方案SQL
function ReturnYhSql($yhid,$yhvar,$ecms=0){
	global $eyh_r;
	if(empty($yhid))
	{
		return '';
	}
	$yhid=(int)$yhid;
	$query='';
	if($eyh_r[$yhid][$yhvar])
	{
		$t=time()-($eyh_r[$yhid][$yhvar]*86400);
		$query='newstime>'.$t.(empty($ecms)?'':' and ');
	}
	return $query;
}

//返回优化+条件SQL
function ReturnYhAndSql($yhadd,$where,$ecms=0){
	if($yhadd.$where=='')
	{
		return '';
	}
	elseif($yhadd&&$where)
	{
		return $ecms==1?' where '.$yhadd.$where:' where '.$yhadd.' and '.$where;
	}
	elseif($yhadd&&!$where)
	{
		return ' where '.$yhadd;
	}
	else
	{
		return $ecms==1?' where '.substr($where,5):' where '.$where;
	}
}

//返回列表查询字段
function ReturnSqlListF($mid){
	global $emod_r;
	if(empty($mid))
	{
		return '*';
	}
	$mid=(int)$mid;
	$f='id,classid,ttid,onclick,plnum,totaldown,newspath,filename,userid,username,firsttitle,isgood,ispic,istop,isqf,ismember,isurl,truetime,lastdotime,havehtml,groupid,userfen,titlefont,titleurl,stb,fstb,restb,keyboard,eckuid,efz'.substr($emod_r[$mid]['listtempf'],0,-1);
	return $f;
}

//返回内容查询字段
function ReturnSqlTextF($mid,$ecms=0){
	global $emod_r;
	if(empty($mid))
	{
		return '*';
	}
	$mid=(int)$mid;
	$f=($ecms==0?'id,classid,':'').'ttid,onclick,plnum,totaldown,newspath,filename,userid,username,firsttitle,isgood,ispic,istop,isqf,ismember,isurl,truetime,lastdotime,havehtml,groupid,userfen,titlefont,titleurl,stb,fstb,restb,keyboard,eckuid,efz'.substr($emod_r[$mid]['tbmainf'],0,-1);
	return $f;
}

//返回内容副表查询字段
function ReturnSqlFtextF($mid){
	global $emod_r;
	if(empty($mid))
	{
		return '*';
	}
	$mid=(int)$mid;
	$f='keyid,dokey,newstempid,closepl,infotags,efzstb'.substr($emod_r[$mid]['tbdataf'],0,-1);
	return $f;
}

//返回信息表
function ReturnInfoTbname($tbname,$checked=1,$stb=1){
	global $dbtbpre;
	$stb=(int)$stb;
	if(empty($checked))//待审核
	{
		$r['tbname']=$dbtbpre.'ecms_'.$tbname.'_check';
		$r['datatbname']=$dbtbpre.'ecms_'.$tbname.'_check_data';
	}
	else//已审核
	{
		$r['tbname']=$dbtbpre.'ecms_'.$tbname;
		$r['datatbname']=$dbtbpre.'ecms_'.$tbname.'_data_'.$stb;
	}
	return $r;
}

//返回信息主表
function ReturnInfoMainTbname($tbname,$checked=1){
	global $dbtbpre;
	return empty($checked)?$dbtbpre.'ecms_'.$tbname.'_check':$dbtbpre.'ecms_'.$tbname;
}

//返回信息副表
function ReturnInfoDataTbname($tbname,$checked=1,$stb=1){
	global $dbtbpre;
	$stb=(int)$stb;
	return empty($checked)?$dbtbpre.'ecms_'.$tbname.'_check_data':$dbtbpre.'ecms_'.$tbname.'_data_'.$stb;
}

//主表信息
function ReturnIndexTableInfo($tbname,$f,$classid,$id){
	global $dbtbpre;
	$r=$empire->fetch1("select ".$f." from {$dbtbpre}ecms_".$tbname."_index where id='$id'".do_dblimit_one());
	return $r;
}

//返回评论表名
function eReturnRestb($restb){
	global $public_r,$dbtbpre;
	$restb=(int)$restb;
	if(!strstr($public_r['pldatatbs'],','.$restb.','))
	{
		$restb=$public_r['pldeftb'];
	}
	return $dbtbpre.'enewspl_'.$restb;
}

//返回附件表名
function eReturnFstb($fstb){
	global $public_r,$dbtbpre;
	$fstb=(int)$fstb;
	if(!strstr($public_r['filedatatbs'],','.$fstb.','))
	{
		$fstb=$public_r['filedeftb'];
	}
	return $dbtbpre.'enewsfile_'.$fstb;
}

//取得父信息使用表
function eRePubFzInfoStb($classid,$id,$pubid=0,$ecms=0){
	global $empire,$dbtbpre,$class_r;
	$classid=(int)$classid;
	$id=(int)$id;
	if(!$pubid)
	{
		$pubid=ReturnInfoPubid($classid,$id);
	}
	$pubid=RepPostVar($pubid);
	$sf=$ecms==1?'*':'fzstb';
	$infor=$empire->fetch1("select ".$sf." from {$dbtbpre}enewsfz_info where pubid='".$pubid."'".do_dblimit_one());
	if(!$infor['fzstb'])
	{
		$infor['fzstb']=1;
	}
	if($ecms==1)
	{
		return $infor;
	}
	else
	{
		return $infor['fzstb'];
	}
}

//返回公共表索引ID
function ReturnInfoPubid($classid,$id,$tid=0){
	global $class_r;
	$classid=(int)$classid;
	$id=(int)$id;
	if(empty($tid))
	{
		$tid=$class_r[$classid]['tid'];
	}
	$tid=(int)$tid;
	$pubid='1'.ReturnAllInt($tid,5).ReturnAllInt($id,10);
	return $pubid;
}

//验证表名是否存在
function eInfoHaveTable($tbname,$ecms=0){
	global $emod_pubr;
	$rt=1;
	if(strstr($tbname,','))
	{
		$rt=0;
	}
	$tbname=str_replace(',','',$tbname);
	if(!$tbname)
	{
		$rt=0;
	}
	if(!strstr($emod_pubr['alltbs'],','.$tbname.','))
	{
		$rt=0;
	}
	if($ecms&&$rt==0)
	{
		exit();
	}
	return $rt;
}

//验证表ID是否存在
function eInfoHaveTableid($tid,$ecms=0){
	global $emod_pubr;
	$tid=(int)$tid;
	$rt=1;
	if(!$tid)
	{
		$rt=0;
	}
	if(!strstr($emod_pubr['alltbids'],','.$tid.','))
	{
		$rt=0;
	}
	if($ecms&&$rt==0)
	{
		exit();
	}
	return $rt;
}

//验证模型ID是否存在
function eInfoHaveModid($mid,$ecms=0){
	global $emod_pubr;
	$mid=(int)$mid;
	$rt=1;
	if(!$mid)
	{
		$rt=0;
	}
	if(!strstr($emod_pubr['allmids'],','.$mid.','))
	{
		$rt=0;
	}
	if($ecms&&$rt==0)
	{
		exit();
	}
	return $rt;
}

//验证模型字段是否存在
function eInfoHaveModField($mid,$fname,$ckf=0,$ecms=0){
	global $emod_r;
	$mid=(int)$mid;
	$rt=1;
	if(strstr($fname,','))
	{
		$rt=0;
	}
	$fname=str_replace(',','',$fname);
	if(!$fname)
	{
		$rt=0;
	}
	if($ckf==1)//主表
	{
		$modfield=$emod_r[$mid]['tbmainf'];
	}
	elseif($ckf==2)//副表
	{
		$modfield=$emod_r[$mid]['tbdataf'];
	}
	else
	{
		$modfield=substr($emod_r[$mid]['tbmainf'],0,-1).$emod_r[$mid]['tbdataf'];
	}
	if(!strstr($modfield,','.$fname.','))
	{
		$rt=0;
	}
	if($ecms&&$rt==0)
	{
		exit();
	}
	return $rt;
}

//是否内部表
function InfoIsInTable($tbname){
	global $etable_r;
	return $etable_r[$tbname]['intb']==1?true:false;
}

//检验字段是否存在
function eCheckTbHaveField($tid,$tbname,$f){
	global $empire,$dbtbpre;
	$where=$tid?"tid='$tid' and ":"tbname='$tbname' and ";
	if(strstr($f,','))
	{
		$fr=explode(',',$f);
		$where.="f='".$fr[0]."' or f='".$fr[1]."'";
	}
	else
	{
		$where.="f='$f'";
	}
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsf where ".$where."".do_dblimit_cone());
	return $num;
}

//验证模板是否开启动态使用
function DtTempIsClose($tempid,$type='listtemp'){
	global $public_r;
	if($type=='listtemp')//列表模板
	{
		if($public_r['closelisttemp']&&strstr(','.$public_r['closelisttemp'].',',','.$tempid.','))
		{
			echo'ListTempID='.$tempid.' is close.';
			exit();
		}
	}
}

//返回年月日统计
function eReturnYmdTotalf($r,$ecms=0){
	if($ecms==1)
	{
		$fpre='ed';
	}
	else
	{
		$fpre='eo';
	}
	$uptimef=$fpre.'time';
	if(!isset($r[$uptimef]))
	{
		return '';
	}
	$uptime=$r[$uptimef];
	//当前时间
	$time=time();
	$date=date('Y-m-d');
	$year=date('Y');
	$month=date('n');
	$week=date('W');
	$day=date('j');
	$halfyear=$month>6?1:0;
	if($month>=4&&$month<=6)
	{
		$quarter=2;
	}
	elseif($month>=7&&$month<=9)
	{
		$quarter=3;
	}
	elseif($month>=10&&$month<=12)
	{
		$quarter=4;
	}
	else
	{
		$quarter=1;
	}
	//统计时间
	$tdate=date('Y-m-d',$uptime);
	$tyear=date('Y',$uptime);
	$tmonth=date('n',$uptime);
	$tweek=date('W',$uptime);
	$tday=date('j',$uptime);
	$thalfyear=$tmonth>6?1:0;
	if($tmonth>=4&&$tmonth<=6)
	{
		$tquarter=2;
	}
	elseif($tmonth>=7&&$tmonth<=9)
	{
		$tquarter=3;
	}
	elseif($tmonth>=10&&$tmonth<=12)
	{
		$tquarter=4;
	}
	else
	{
		$tquarter=1;
	}
	//更新
	$upstr='';
	//年
	$yearf=$fpre.'year';
	if(isset($r[$yearf]))
	{
		if($tyear<>$year)
		{
			$upstr.=','.$yearf.'=0';
		}
		else
		{
			$upstr.=','.$yearf.'='.$yearf.'+1';
		}
	}
	//半年
	$halfyearf=$fpre.'halfyear';
	if(isset($r[$halfyearf]))
	{
		if($tyear<>$year||$thalfyear<>$halfyear)
		{
			$upstr.=','.$halfyearf.'=0';
		}
		else
		{
			$upstr.=','.$halfyearf.'='.$halfyearf.'+1';
		}
	}
	//季度
	$quarterf=$fpre.'quarter';
	if(isset($r[$quarterf]))
	{
		if($tyear<>$year||$tquarter<>$quarter)
		{
			$upstr.=','.$quarterf.'=0';
		}
		else
		{
			$upstr.=','.$quarterf.'='.$quarterf.'+1';
		}
	}
	//月
	$monthf=$fpre.'month';
	if(isset($r[$monthf]))
	{
		if($tyear.'-'.$tmonth<>$year.'-'.$month)
		{
			$upstr.=','.$monthf.'=0';
		}
		else
		{
			$upstr.=','.$monthf.'='.$monthf.'+1';
		}
	}
	//周
	$weekf=$fpre.'week';
	if(isset($r[$weekf]))
	{
		if($tyear<>$year||$tweek<>$week)
		{
			$upstr.=','.$weekf.'=0';
		}
		else
		{
			$upstr.=','.$weekf.'='.$weekf.'+1';
		}
	}
	//日
	$dayf=$fpre.'day';
	if(isset($r[$dayf]))
	{
		if($tyear.'-'.$tmonth.'-'.$tday<>$year.'-'.$month.'-'.$day)
		{
			$upstr.=','.$dayf.'=0';
		}
		else
		{
			$upstr.=','.$dayf.'='.$dayf.'+1';
		}
	}
	//昨
	$yesterdayf=$fpre.'yesterday';
	if(isset($r[$yesterdayf]))
	{
		if($date!=$tdate)
		{
			$timeyesterday=date('Y-m-d',$time-24*3600);
			if($timeyesterday==$tdate)
			{
				$r[$dayf]=(int)$r[$dayf];
				$upstr.=",".$yesterdayf."='".$r[$dayf]."'";
			}
			else
			{
				$upstr.=','.$yesterdayf.'=0';
			}
		}
	}
	//返回
	if($upstr)
	{
		$upstr=",".$uptimef."='".$time."'".$upstr;
	}
	return $upstr;
}

//补零
function ReturnAllInt($val,$num){
	$len=strlen($val);
	$zeronum=$num-$len;
	if($zeronum==1)
	{
		$val='0'.$val;
	}
	elseif($zeronum==2)
	{
		$val='00'.$val;
	}
	elseif($zeronum==3)
	{
		$val='000'.$val;
	}
	elseif($zeronum==4)
	{
		$val='0000'.$val;
	}
	elseif($zeronum==5)
	{
		$val='00000'.$val;
	}
	elseif($zeronum==6)
	{
		$val='000000'.$val;
	}
	elseif($zeronum==7)
	{
		$val='0000000'.$val;
	}
	elseif($zeronum==8)
	{
		$val='00000000'.$val;
	}
	elseif($zeronum==9)
	{
		$val='000000000'.$val;
	}
	elseif($zeronum==10)
	{
		$val='0000000000'.$val;
	}
	return $val;
}

//返回替换列表
function ReturnReplaceListF($mid){
	global $emod_r;
	$mid=(int)$mid;
	$r['mid']=$mid;
	$r['fr']=explode(',',$emod_r[$mid]['listtempf']);
	$r['fcount']=count($r['fr'])-1;
	return $r;
}

//返回替换内容
function ReturnReplaceTextF($mid){
	global $emod_r;
	$mid=(int)$mid;
	$r['mid']=$mid;
	$r['fr']=explode(',',$emod_r[$mid]['tempf']);
	$r['fcount']=count($r['fr'])-1;
	return $r;
}

//替换列表模板/标签模板/搜索模板
function ReplaceListVars($no,$listtemp,$subnews,$subtitle,$formatdate,$url,$haveclass,$r,$field,$docode=0){
	global $empire,$public_r,$class_r,$class_zr,$fun_r,$dbtbpre,$emod_r,$class_tr,$level_r,$navclassid,$etable_r,$etable_t;
	if($haveclass)
	{
		$add=sys_ReturnBqClassname($r,$haveclass);
	}
	if(empty($r['oldtitle']))
	{
		$r['oldtitle']=$r['title'];
	}
	if($docode==1)
	{
		$listtemp=stripSlashes($listtemp);
		eval($listtemp);
	}
	$ylisttemp=$listtemp;
	$mid=$field['mid'];
	$fr=$field['fr'];
	$fcount=$field['fcount'];
	for($i=1;$i<$fcount;$i++)
	{
		$f=$fr[$i];
		$value=$r[$f];
		$spf=0;
		if($f=='title')//标题
		{
	        if(!empty($subtitle))//截取字符
	        {
				$value=sub($value,0,$subtitle,false);
	        }
			$value=DoTitleFont($r['titlefont'],$value);
			$spf=1;
		}
		elseif($f=='newstime')//时间
		{
			//$value=date($formatdate,$value);
			$value=format_datetime($value,$formatdate);
			$spf=1;
		}
		elseif($f=='titlepic')//标题图片
		{
			if(empty($value))
		    {
				$value=$public_r['newsurl'].'e/data/images/notimg.gif';
			}
			$spf=1;
		}
		elseif(strstr($emod_r[$mid]['smalltextf'],','.$f.','))//简介
		{
			if(!empty($subnews))//截取字符
			{
				$value=sub($value,0,$subnews,false);
			}
		}
		elseif($f=='befrom')//信息来源
		{
			$spf=1;
		}
		elseif($f=='writer')//作者
		{
			$spf=1;
		}
		if($spf==0&&!strstr($emod_r[$mid]['editorf'],','.$f.','))
		{
			if(strstr($emod_r[$mid]['tobrf'],','.$f.','))//加br
			{
				$value=nl2br($value);
			}
			if(!strstr($emod_r[$mid]['dohtmlf'],','.$f.','))//去除html
			{
				$value=RepFieldtextNbsp(ehtmlspecialchars($value));
			}
		}
		$listtemp=str_replace('[!--'.$f.'--]',$value,$listtemp);
	}
	$titleurl=sys_ReturnBqTitleLink($r);//链接
	$listtemp=str_replace('[!--id--]',$r['id'],$listtemp);
	$listtemp=str_replace('[!--classid--]',$r['classid'],$listtemp);
	$listtemp=str_replace('[!--class.name--]',$add,$listtemp);
	$listtemp=str_replace('[!--ttid--]',$r['ttid'],$listtemp);
	$listtemp=str_replace('[!--tt.name--]',$class_tr[$r['ttid']]['tname'],$listtemp);
	$listtemp=str_replace('[!--tt.url--]',sys_ReturnBqInfoTypeUrl($r['ttid']),$listtemp);
	$listtemp=str_replace('[!--userfen--]',$r['userfen'],$listtemp);
	$listtemp=str_replace('[!--titleurl--]',$titleurl,$listtemp);
	$listtemp=str_replace('[!--no.num--]',$no,$listtemp);
	$listtemp=str_replace('[!--plnum--]',$r['plnum'],$listtemp);
	$listtemp=str_replace('[!--userid--]',$r['userid'],$listtemp);
	$listtemp=str_replace('[!--username--]',$r['username'],$listtemp);
	$listtemp=str_replace('[!--onclick--]',$r['onclick'],$listtemp);
	$listtemp=str_replace('[!--oldtitle--]',$r['oldtitle'],$listtemp);
	$listtemp=str_replace('[!--totaldown--]',$r['totaldown'],$listtemp);
	//栏目链接
	if(strstr($ylisttemp,'[!--this.classlink--]'))
	{
		$thisclasslink=sys_ReturnBqClassname($r,9);
		$listtemp=str_replace('[!--this.classlink--]',$thisclasslink,$listtemp);
	}
	$thisclassname=$class_r[$r['classid']]['bname']?$class_r[$r['classid']]['bname']:$class_r[$r['classid']]['classname'];
	$listtemp=str_replace('[!--this.classname--]',$thisclassname,$listtemp);
	return $listtemp;
}

//加上防复制字符
function AddNotCopyRndStr($text){
	global $public_r;
	if($public_r['opencopytext'])
	{
		$rnd=make_password(3).$public_r['sitename'];
		$text=str_replace("<br />","<span style=\"display:none\">".$rnd."</span><br />",$text);
		$text=str_replace("</p>","<span style=\"display:none\">".$rnd."</span></p>",$text);
	}
	return $text;
}

//替换信息来源
function ReplaceBefrom($befrom){
	global $empire,$dbtbpre;
	if(empty($befrom))
	{return $befrom;}
	$obefrom=$befrom;
	$befrom=addslashes($befrom);
	if('dg'.$befrom<>'dg'.$obefrom)
	{return $obefrom;}
	$r=$empire->fetch1("select befromid,sitename,siteurl from {$dbtbpre}enewsbefrom where sitename='$befrom'".do_dblimit_one());
	if(empty($r['befromid']))
	{return $befrom;}
	$return_befrom="<a href='".$r['siteurl']."' target=_blank>".$r['sitename']."</a>";
	return $return_befrom;
}

//替换作者
function ReplaceWriter($writer){
	global $empire,$dbtbpre;
	if(empty($writer))
	{return $writer;}
	$owriter=$writer;
	$writer=addslashes($writer);
	if('dg'.$writer<>'dg'.$owriter)
	{return $owriter;}
	$r=$empire->fetch1("select wid,writer,email from {$dbtbpre}enewswriter where writer='$writer'".do_dblimit_one());
	if(empty($r['wid'])||empty($r['email']))
	{
		return $writer;
	}
	$return_writer="<a href='".$r['email']."'>".$r['writer']."</a>";
	return $return_writer;
}

//备份下载记录
function BakDown($classid,$id,$pathid,$userid,$username,$title,$cardfen,$online=0){
	global $empire,$dbtbpre;
	$truetime=time();
	$id=(int)$id;
	$pathid=(int)$pathid;
	$userid=(int)$userid;
	$cardfen=(int)$cardfen;
	$classid=(int)$classid;
	$username=RepPostVar($username);
	$title=dgdbe_rpstr($title);
	$online=addslashes(dgdbe_rpstr($online));
	$sql=$empire->query("insert into {$dbtbpre}enewsdownrecord(id,pathid,userid,username,title,cardfen,truetime,classid,online) values('$id','$pathid','$userid','$username','".addslashes($title)."','$cardfen','$truetime','$classid','$online');");
}

//备份充值记录
function BakBuy($userid,$username,$buyname,$userfen,$money,$userdate,$type=0){
	global $empire,$dbtbpre;
	$buytime=date("y-m-d H:i:s");
	$buyname=addslashes(dgdbe_rpstr($buyname));
	$userid=(int)$userid;
	$username=RepPostVar($username);
	$userfen=addslashes(dgdbe_rpstr($userfen));
	$money=addslashes(dgdbe_rpstr($money));
	$userdate=addslashes(dgdbe_rpstr($userdate));
	$type=addslashes(dgdbe_rpstr($type));
	$empire->query("insert into {$dbtbpre}enewsbuybak(userid,username,card_no,cardfen,money,buytime,userdate,type) values('$userid','$username','$buyname','$userfen','$money','$buytime','$userdate','$type');");
}

//发送短消息
function eSendMsg($title,$msgtext,$to_username,$from_userid,$from_username,$isadmin,$issys,$ecms=0){
	global $empire,$dbtbpre;
	$tbname=$ecms==1?$dbtbpre.'enewshmsg':$dbtbpre.'enewsqmsg';
	$to_username=RepPostVar($to_username);
	$from_userid=(int)$from_userid;
	$from_username=RepPostVar($from_username);
	$isadmin=(int)$isadmin;
	$issys=(int)$issys;
	$msgtime=date("Y-m-d H:i:s");
	$empire->query("insert into ".$tbname."(title,msgtext,haveread,msgtime,to_username,from_userid,from_username,isadmin,issys) values('$title','$msgtext',0,'$msgtime','$to_username','$from_userid','$from_username','$isadmin','$issys');");
	//消息状态
	$userr=$empire->fetch1("select ".eReturnSelectMemberF('userid,havemsg')." from ".eReturnMemberTable()." where ".egetmf('username')."='$to_username'".do_dblimit_one());
	if(!$userr['havemsg'])
	{
		$newhavemsg=eReturnSetHavemsg($userr['havemsg'],0);
		$empire->query("update ".eReturnMemberTable()." set ".egetmf('havemsg')."='$newhavemsg' where ".egetmf('userid')."='".$userr['userid']."'".do_dblimit_upone());
	}
}

//发送通知
function eSendNotice($title,$msgtext,$to_username,$from_userid,$from_username,$ecms=0){
	global $empire,$dbtbpre;
	$tbname=$ecms==1?$dbtbpre.'enewshnotice':$dbtbpre.'enewsnotice';
	$to_username=RepPostVar($to_username);
	$from_userid=(int)$from_userid;
	$from_username=RepPostVar($from_username);
	$msgtime=date("Y-m-d H:i:s");
	$empire->query("insert into ".$tbname."(title,msgtext,haveread,msgtime,to_username,from_userid,from_username) values('".$title."','".$msgtext."',0,'$msgtime','$to_username','$from_userid','$from_username');");
}

//截取简介
function SubSmalltextVal($value,$len){
	if(empty($len))
	{
		return '';
	}
	$value=str_replace(array("\r\n","<br />","<br>","&nbsp;","[!--empirenews.page--]","[/!--empirenews.page--]"),array("","\r\n","\r\n"," ","",""),$value);
	$value=strip_tags($value);
	if($len)
	{
		$value=sub($value,0,$len,false);
	}
	$value=trim($value,"\r\n");
	$value=str_replace('&amp;ldquo;','&ldquo;',$value);
	$value=str_replace('&amp;rdquo;','&rdquo;',$value);
	$value=str_replace('&amp;mdash;','&mdash;',$value);
	return $value;
}

//全站搜索简介
function SubSchallSmalltext($value,$len){
	$value=str_replace(array("\r\n","&nbsp;","[!--empirenews.page--]","[/!--empirenews.page--]"),array("","","",""),$value);
	$value=strip_tags($value);
	if($len)
	{
		$value=sub($value,0,$len,false);
	}
	$value=trim($value,"\r\n");
	return $value;
}

//加红替换
function DoReplaceFontRed($text,$key){
	return str_replace($key,'<font color="red">'.$key.'</font>',$text);
}

//返回不生成html的栏目
function ReturnNreInfoWhere(){
	global $public_r;
	if(empty($public_r['nreinfo'])||$public_r['nreinfo']==',')
	{
		return '';
	}
	$cids=substr($public_r['nreinfo'],1,strlen($public_r['nreinfo'])-2);
	$where=' and classid not in ('.$cids.')';
	return $where;
}

//返回标签不调用栏目
function ReturnNottoBqWhere(){
	global $public_r;
	if(empty($public_r['nottobq'])||$public_r['nottobq']==',')
	{
		return '';
	}
	$cids=substr($public_r['nottobq'],1,strlen($public_r['nottobq'])-2);
	$where='classid not in ('.$cids.')';
	return $where;
}

//返回文件名及扩展名
function ReturnCFiletype($file){
	$r=explode('.',$file);
	$count=count($r)-1;
	$re['filetype']=strtolower($r[$count]);
	$re['filename']=substr($file,0,strlen($file)-strlen($re['filetype'])-1);
	return $re;
}

//返回栏目目录
function ReturnSaveClassPath($classid,$f=0){
	global $class_r;
	$classpath=$class_r[$classid]['classpath'];
	if($f==1){
		$classpath.="/index".$class_r[$classid]['classtype'];
	}
	return $classpath;
}

//返回专题目录
function ReturnSaveZtPath($classid,$f=0){
	global $class_zr;
	$classpath=$class_zr[$classid]['ztpath'];
	if($f==1){
		$classpath.="/index".$class_zr[$classid]['zttype'];
	}
	return $classpath;
}

//返回标题分类目录
function ReturnSaveInfoTypePath($classid,$f=0){
	global $class_tr;
	$classpath=$class_tr[$classid]['tpath'];
	if($f==1){
		$classpath.='/index'.$class_tr[$classid]['ttype'];
	}
	return $classpath;
}

//返回首页文件
function ReturnSaveIndexFile(){
	global $public_r;
	$file='index'.$public_r['indextype'];
	return $file;
}

//返回首页地址
function ReturnSiteIndexUrl(){
	global $public_r;
	if(empty($public_r['indexaddpage']))
	{
		return $public_r['newsurl'];
	}
	if($public_r['indexpagedt']||Moreport_ReturnMustDt())//moreport
	{
		$public_r['indextype']='.php';
	}
	$file=$public_r['newsurl'].'index'.$public_r['indextype'];
	return $file;
}

//返回内容页存放目录
function ReturnSaveInfoPath($classid,$id){
	global $class_r;
	if($class_r[$classid]['ipath']==''){
		$path=$class_r[$classid]['classpath'].'/';
	}
	else{
		$path=$class_r[$classid]['ipath']=='/'?'':$class_r[$classid]['ipath'].'/';
	}
	return $path;
}

//返回内容页文件名
function GetInfoFilename($classid,$id){
	global $empire,$dbtbpre,$public_r,$class_r;
	$classid=(int)$classid;
	$id=(int)$id;
	$infor=$empire->fetch1("select isurl,groupid,classid,newspath,filename,id from {$dbtbpre}ecms_".$class_r[$classid]['tbname']." where id='$id'".do_dblimit_one());
	if(!$infor['id']||$infor['isurl'])
	{
		return '';
	}
	$filetype=$infor['groupid']?'.php':$class_r[$classid]['filetype'];
	$iclasspath=ReturnSaveInfoPath($classid,$id);
	$doclasspath=eReturnTrueEcmsPath().$iclasspath;//moreport
	$newspath='';
	if($infor['newspath'])
	{
		$newspath=$infor['newspath'].'/';
	}
	$file=$doclasspath.$newspath.$infor['filename'].$filetype;
	return $file;
}

//格式化信息目录
function FormatPath($classid,$mynewspath,$enews=0){
	global $class_r;
	if($enews)
	{
		$newspath=$mynewspath;
	}
	else
	{
		$newspath=date($class_r[$classid]['newspath']);
	}
	if(empty($newspath))
	{
		return "";
	}
	$path=eReturnTrueEcmsPath().ReturnSaveInfoPath($classid,$id);
	if(file_exists($path.$newspath))
	{
		return $newspath;
	}
	$returnpath="";
	$r=explode("/",$newspath);
	$count=count($r);
	for($i=0;$i<$count;$i++){
		if($i>0)
		{
			$returnpath.="/".$r[$i];
		}
		else
		{
			$returnpath.=$r[$i];
		}
		$createpath=$path.$returnpath;
		$mk=DoMkdir($createpath);
		if(empty($mk))
		{
			printerror("CreatePathFail","");
		}
	}
	return $returnpath;
}

//返回内容页目录
function ReturnInfoSPath($filename){
	return str_replace('/index','',$filename);
}

//返回根目录
function ReturnAbsEcmsPath(){
	$ecmspath=str_replace("\\","/",ECMS_PATH);
	return $ecmspath;
}

//返回当前根目录
function eReturnTrueEcmsPath(){
	if(defined('ECMS_SELFPATH'))
	{
		return ECMS_SELFPATH;
	}
	else
	{
		return ECMS_PATH;
	}
}

//返回主端根目录
function eReturnEcmsMainPortPath(){
	global $ecms_config;
	if($ecms_config['sets']['mainportpath'])
	{
		return $ecms_config['sets']['mainportpath'];
	}
	else
	{
		return ECMS_PATH;
	}
}

//返回不斜扛结尾的根目录
function eEcmsPath($ecms=0,$epath=''){
	if($ecms==0)
	{
		$repath=substr(ECMS_PATH,0,-1);
	}
	elseif($ecms==1)
	{
		$repath=substr(eReturnTrueEcmsPath(),0,-1);
	}
	elseif($ecms==2)
	{
		$repath=substr(eReturnEcmsMainPortPath(),0,-1);
	}
	elseif($ecms==3)
	{
		$repath=substr($epath,0,-1);
	}
	else
	{
		$repath=substr(ECMS_PATH,0,-1);
	}
	return $repath;
}

//返回数字目录
function eDoIntPath($epath){
	$epath=(int)$epath;
	return $epath;
}

//返回文件是否存在
function eDoCheckHvFile($ckfile){
	if(file_exists($ckfile))
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

//返回目录是否存在
function eDoCheckHvPath($ckpath){
	if(file_exists($ckpath))
	{
		return 1;
	}
	else
	{
		return 0;
	}
}


//------------- 附件 -------------

//返回附件分表
function eReturnFileStb($fstb){
	global $public_r;
	$fstb=(int)$fstb;
	if(!strstr($public_r['filedatatbs'],','.$fstb.','))
	{
		$fstb=$public_r['filedeftb'];
	}
	return $fstb;
}

//返回附件表
function eReturnFileTable($modtype,$fstb){
	global $dbtbpre;
	if($modtype==0)//信息
	{
		$fstb=eReturnFileStb($fstb);
		$table=$dbtbpre.'enewsfile_'.$fstb;
	}
	elseif($modtype==5)//公共
	{
		$table=$dbtbpre.'enewsfile_public';
	}
	elseif($modtype==6)//会员
	{
		$table=$dbtbpre.'enewsfile_member';
	}
	else//其他
	{
		$table=$dbtbpre.'enewsfile_other';
	}
	return $table;
}

//查询附件表
function eSelectFileTable($modtype,$fstb,$selectf,$where){
	global $dbtbpre;
	$query="select {$selectf} from ".eReturnFileTable($modtype,$fstb)." where ".$where;
	return $query;
}

//写入附件记录
function eInsertFileTable($filename,$filesize,$path,$adduser,$classid,$no,$type,$id,$cjid,$fpath,$pubid,$modtype=0,$fstb=1,$cid=0,$cid2=0){
	global $empire,$dbtbpre,$public_r;
	$filetime=time();
	$filesize=(int)$filesize;
	$classid=(int)$classid;
	$id=(int)$id;
	$cjid=(int)$cjid;
	$fpath=(int)$fpath;
	$type=(int)$type;
	$modtype=(int)$modtype;
	$filename=addslashes(dgdbe_rpstr($filename));
	$no=addslashes(dgdbe_rpstr($no));
	$adduser=RepPostVar($adduser);
	$path=addslashes(dgdbe_rpstr($path,1));
	$pubid=RepPostVar($pubid);
	$fstb=(int)$fstb;
	$cid=(int)$cid;
	$cid2=(int)$cid2;
	if($modtype==0)//信息
	{
		$fstb=eReturnFileStb($fstb);
		$sql=$empire->query("insert into {$dbtbpre}enewsfile_".$fstb."(pubid,filename,filesize,adduser,path,filetime,classid,no,type,id,cjid,onclick,fpath,cid,cid2) values('$pubid','$filename','$filesize','$adduser','$path','$filetime','$classid','$no','$type','$id','$cjid',0,'$fpath','$cid','$cid2');");
	}
	elseif($modtype==5)//公共
	{
		$sql=$empire->query("insert into {$dbtbpre}enewsfile_public(filename,filesize,adduser,path,filetime,modtype,no,type,id,cjid,onclick,fpath,cid,cid2) values('$filename','$filesize','$adduser','$path','$filetime',0,'$no','$type','$id','$cjid',0,'$fpath','$cid','$cid2');");
	}
	elseif($modtype==6)//会员
	{
		$sql=$empire->query("insert into {$dbtbpre}enewsfile_member(filename,filesize,adduser,path,filetime,no,type,id,cjid,onclick,fpath,cid,cid2) values('$filename','$filesize','$adduser','$path','$filetime','$no','$type','$id','$cjid',0,'$fpath','$cid','$cid2');");
	}
	else//其他
	{
		$sql=$empire->query("insert into {$dbtbpre}enewsfile_other(filename,filesize,adduser,path,filetime,modtype,no,type,id,cjid,onclick,fpath,cid,cid2) values('$filename','$filesize','$adduser','$path','$filetime','$modtype','$no','$type','$id','$cjid',0,'$fpath','$cid','$cid2');");
	}
	return $sql;
}

//更新相应的附件(非信息)
function UpdateTheFileOther($modtype,$id,$checkpass,$tb='other'){
	global $empire,$dbtbpre;
	if(empty($id)||empty($checkpass))
	{
		return "";
	}
	$id=(int)$id;
	$checkpass=(int)$checkpass;
	$where='';
	if($tb=='other')
	{
		$where=" and modtype='$modtype'";
	}
	$sql=$empire->query("update {$dbtbpre}enewsfile_{$tb} set id='$id',cjid=0 where cjid='$checkpass'".$where);
}

//修改时更新附件(非信息)
function UpdateTheFileEditOther($modtype,$id,$tb='other'){
	global $empire,$dbtbpre;
	$id=(int)$id;
	$where='';
	if($tb=='other')
	{
		$where=" and modtype='$modtype'";
	}
	$sql=$empire->query("update {$dbtbpre}enewsfile_{$tb} set cjid=0 where id='$id'".$where);
}

//返回filepass
function ReturnTranFilepass(){
	$filepass=time();
	return $filepass;
}

//返回附件域名地址
function eReturnFileUrl($ecms=0){
	global $public_r;
	if($ecms==1)
	{
		return $public_r['fileurl'];
	}
	$fileurl=$public_r['openfileserver']?$public_r['fs_purl']:$public_r['fileurl'];
	return $fileurl;
}

//返回附件目录
function ReturnFileSavePath($classid,$fpath=''){
	global $public_r,$class_r;
	$fpath=$fpath||strstr(','.$fpath.',',',0,')?$fpath:$public_r['fpath'];
	$efileurl=eReturnFileUrl();
	$fpnpub=$public_r['fpnpub'];
	if($fpath==1)//p目录
	{
		$r['filepath']='d/file/'.$fpnpub.'/';
		$r['fileurl']=$efileurl.$fpnpub.'/';
	}
	elseif($fpath==2)//file目录
	{
		$r['filepath']='d/file/';
		$r['fileurl']=$efileurl;
	}
	elseif($fpath==3)//头像
	{
		$r['filepath']='d/file/efupic/';
		$r['fileurl']=$efileurl.'efupic/';
	}
	elseif($fpath==4)//elu
	{
		$r['filepath']='d/file/efelu/';
		$r['fileurl']=$efileurl.'efelu/';
	}
	else
	{
		if(empty($classid))
		{
			$r['filepath']='d/file/'.$fpnpub.'/';
			$r['fileurl']=$efileurl.$fpnpub.'/';
		}
		else
		{
			$classid=(int)$classid;
			$r['filepath']='d/file/'.$class_r[$classid]['classpath'].'/';
			$r['fileurl']=$efileurl.$class_r[$classid]['classpath'].'/';
		}
	}
	return $r;
}

//格式化附件目录
function FormatFilePath($classid,$mynewspath,$enews=0){
	global $public_r;
	if($enews)
	{
		$newspath=$mynewspath;
	}
	else
	{
		$newspath=date($public_r['filepath']);
	}
	if(empty($newspath))
	{
		return "";
	}
	$fspath=ReturnFileSavePath($classid);
	$path=eReturnEcmsMainPortPath().$fspath['filepath'];//moreport
	if(file_exists($path.$newspath))
	{
		return $newspath;
	}
	$returnpath="";
	$r=explode("/",$newspath);
	$count=count($r);
	for($i=0;$i<$count;$i++)
	{
		if($i>0)
		{
			$returnpath.="/".$r[$i];
		}
		else
		{
			$returnpath.=$r[$i];
		}
		$createpath=$path.$returnpath;
		$mk=DoMkdir($createpath);
		if(empty($mk))
		{
			//printerror("CreatePathFail","");
			echo'Create Path Fail.';
			exit();
		}
	}
	return $returnpath;
}

//公共格式化附件目录
function ecp_FormatFilePath($mynewspath,$fpath=3,$ecms=0,$fm=0,$basepath=''){
	global $public_r;
	if($fm)
	{
		$newspath=$mynewspath;
	}
	else
	{
		$newspath=date($mynewspath);
	}
	if(empty($newspath))
	{
		return '';
	}
	if(empty($basepath))
	{
		$fspath=ReturnFileSavePath(0,$fpath);
		$basepath=$fspath['filepath'];
	}
	$path=eReturnEcmsMainPortPath().$basepath;//moreport
	if(file_exists($path.$newspath))
	{
		return $newspath;
	}
	$returnpath='';
	$r=explode('/',$newspath);
	$count=count($r);
	for($i=0;$i<$count;$i++)
	{
		if($i>0)
		{
			$returnpath.='/'.$r[$i];
		}
		else
		{
			$returnpath.=$r[$i];
		}
		$createpath=$path.$returnpath;
		$mk=DoMkdir($createpath);
		if(empty($mk))
		{
			//printerror('CreatePathFail','',$ecms);
			echo'Create Path Fail.';
			exit();
		}
	}
	return $returnpath;
}

//返回上传文件名
function ReturnDoTranFilename($file_name,$classid){
	global $public_r;
	if($public_r['fntype']==0)
	{
		$filename=md5(uniqid(microtime()).EcmsRandInt());
	}
	else
	{
		$filename=time().EcmsRandInt(10000000,99999999);
	}
	return $filename;
}

//是否上传文件
function eCkIsTranFile($file){
	if(is_uploaded_file($file))
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

//返回上传文件信息
function ReDoTranFile($file,$file_name,$file_type,$file_size,$classid,$ecms=0){
	global $public_r,$class_r,$doetran,$efileftp_fr;
	$classid=(int)$classid;
	//文件类型
	$r['filetype']=GetFiletype($file_name);
	//文件名
	$r['insertfile']=ReturnDoTranFilename($file_name,$classid);
	$r['filename']=$r['insertfile'].$r['filetype'];
	//日期目录
	$r['filepath']=FormatFilePath($classid,$mynewspath,0);
	$filepath=$r['filepath']?$r['filepath'].'/':$r['filepath'];
	//存放目录
	$fspath=ReturnFileSavePath($classid);
	$r['savepath']=eReturnEcmsMainPortPath().$fspath['filepath'].$filepath;//moreport
	//附件地址
	$r['url']=$fspath['fileurl'].$filepath.$r['filename'];
	//缩图文件
	$r['name']=$r['savepath']."small".$r['insertfile'];
	//附件文件
	$r['yname']=$r['savepath'].$r['filename'];
	$r['filesize']=(int)$file_size;
	$r['tran']=1;
	if(!eCkIsTranFile($file))
	{
		$r['tran']=0;
	}
	//验证类型
	if(CheckSaveTranFiletype($r['filetype']))
	{
		$r['tran']=0;
	}
	/*
	//FileServer
	if($public_r['openfileserver'])
	{
		$efileftp_fr[]=$r['yname'];
	}
	*/
	return $r;
}

//上传文件
function DoTranFile($file,$file_name,$file_type,$file_size,$classid,$ecms=0){
	global $public_r,$class_r,$doetran,$efileftp_fr;
	$classid=(int)$classid;
	//文件类型
	$r['filetype']=GetFiletype($file_name);
	//文件名
	$r['insertfile']=ReturnDoTranFilename($file_name,$classid);
	$r['filename']=$r['insertfile'].$r['filetype'];
	//日期目录
	$r['filepath']=FormatFilePath($classid,$mynewspath,0);
	$filepath=$r['filepath']?$r['filepath'].'/':$r['filepath'];
	//存放目录
	$fspath=ReturnFileSavePath($classid);
	$r['savepath']=eReturnEcmsMainPortPath().$fspath['filepath'].$filepath;//moreport
	//附件地址
	$r['url']=$fspath['fileurl'].$filepath.$r['filename'];
	//缩图文件
	$r['name']=$r['savepath']."small".$r['insertfile'];
	//附件文件
	$r['yname']=$r['savepath'].$r['filename'];
	$r['tran']=1;
	//验证类型
	if(CheckSaveTranFiletype($r['filetype']))
	{
		if($doetran)
		{
			$r['tran']=0;
			return $r;
		}
		else
		{
			printerror('TranFail','',$ecms);
		}
	}
	//istran
	if(!eCkIsTranFile($file))
	{
		if($doetran)
		{
			$r['tran']=0;
			return $r;
		}
		else
		{
			printerror('TranFail','',$ecms);
		}
	}
	//上传文件
	$cp=@move_uploaded_file($file,$r['yname']);
	if(empty($cp))
	{
		if($doetran)
		{
			$r['tran']=0;
			return $r;
		}
		else
		{
			printerror('TranFail','',$ecms);
		}
	}
	DoChmodFile($r['yname']);
	$r['filesize']=(int)$file_size;
	//FileServer
	if($public_r['openfileserver'])
	{
		$efileftp_fr[]=$r['yname'];
	}
	return $r;
}

//公共上传文件
function ecp_DoTranFile($file,$file_name,$file_type,$file_size,$mynewspath,$fpath=3,$ecms=0,$fm=0,$basepath='',$baseurl='',$fn='',$fnt=0){
	global $public_r,$doetran,$efileftp_fr;
	//文件类型
	$r['filetype']=GetFiletype($file_name);
	//文件名
	if($fn)
	{
		if(!$fnt)
		{
			$fn=(int)$fn;
		}
		$r['insertfile']=$fn;
	}
	else
	{
		$r['insertfile']=ReturnDoTranFilename($file_name,0);
	}
	$r['filename']=$r['insertfile'].$r['filetype'];
	//日期目录
	$r['filepath']=ecp_FormatFilePath($mynewspath,$fpath,$ecms,$fm,$basepath);
	$filepath=$r['filepath']?$r['filepath'].'/':$r['filepath'];
	//存放目录
	if(empty($basepath))
	{
		$fspath=ReturnFileSavePath(0,$fpath);
		$basepath=$fspath['filepath'];
		$baseurl=$fspath['fileurl'];
	}
	$r['savepath']=eReturnEcmsMainPortPath().$basepath.$filepath;//moreport
	//附件地址
	$r['url']=$baseurl.$filepath.$r['filename'];
	//缩图文件
	$r['name']=$r['savepath']."small".$r['insertfile'];
	//附件文件
	$r['yname']=$r['savepath'].$r['filename'];
	$r['tran']=1;
	//验证类型
	if(CheckSaveTranFiletype($r['filetype']))
	{
		if($doetran)
		{
			$r['tran']=0;
			return $r;
		}
		else
		{
			printerror('TranFail','',$ecms);
		}
	}
	//istran
	if(!eCkIsTranFile($file))
	{
		if($doetran)
		{
			$r['tran']=0;
			return $r;
		}
		else
		{
			printerror('TranFail','',$ecms);
		}
	}
	//上传文件
	$cp=@move_uploaded_file($file,$r['yname']);
	if(empty($cp))
	{
		if($doetran)
		{
			$r['tran']=0;
			return $r;
		}
		else
		{
			printerror('TranFail','',$ecms);
		}
	}
	DoChmodFile($r['yname']);
	$r['filesize']=(int)$file_size;
	//FileServer
	if($public_r['openfileserver'])
	{
		$efileftp_fr[]=$r['yname'];
	}
	return $r;
}

//上传文件(普通)
function ecom_DoTranFile($file,$file_name,$file_type,$file_size,$tranpath,$tranfile,$chmodf=1){
	global $public_r,$doetran;
	if(!$file_name)
	{
		return 0;
	}
	//文件类型
	$r['filetype']=GetFiletype($file_name);
	//验证类型
	if(CheckSaveTranFiletype($r['filetype']))
	{
		return 0;
	}
	//istran
	if(!eCkIsTranFile($file))
	{
		return 0;
	}
	$r['yname']=$tranpath.$tranfile;
	//上传文件
	$cp=@move_uploaded_file($file,$r['yname']);
	if(empty($cp))
	{
		return 0;
	}
	if($chmodf)
	{
		DoChmodFile($r['yname']);
	}
	return 1;
}

//远程保存忽略地址
function CheckNotSaveUrl($url){
	global $public_r;
	if(empty($public_r['notsaveurl']))
	{
		return 0;
    }
	$r=explode("\r\n",$public_r['notsaveurl']);
	$count=count($r);
	$re=0;
	for($i=0;$i<$count;$i++)
	{
		if(empty($r[$i]))
		{continue;}
		if(stristr($url,$r[$i]))
		{
			$re=1;
			break;
	    }
    }
	return $re;
}

//远程保存
function DoTranUrl($url,$classid){
	global $public_r,$class_r,$ecms_config,$efileftp_fr;
	$classid=(int)$classid;
	//处理地址
	$url=trim($url);
	$url=str_replace(" ","%20",$url);
    $r['tran']=1;
	//附件地址
	$r['url']=$url;
	//文件类型
	$r['filetype']=GetFiletype($url);
	if(CheckSaveTranFiletype($r['filetype']))
	{
		$r['tran']=0;
		return $r;
	}
	//是否已上传的文件
	$havetr=CheckNotSaveUrl($url);
	if($havetr)
	{
		$r['tran']=0;
		return $r;
	}
	//是否地址
	if(!strstr($url,'://'))
	{
		$r['tran']=0;
		return $r;
	}
	if(!eToCheckIsUrl2($url))
	{
		$r['tran']=0;
		return $r;
	}
	$string=ReadUrltext($url);
	if(empty($string))//读取不了
	{
		$r['tran']=0;
		return $r;
	}
	//文件名
	$r['insertfile']=ReturnDoTranFilename($file_name,$classid);
	$r['filename']=$r['insertfile'].$r['filetype'];
	//日期目录
	$r['filepath']=FormatFilePath($classid,$mynewspath,0);
	$filepath=$r['filepath']?$r['filepath'].'/':$r['filepath'];
	//存放目录
	$fspath=ReturnFileSavePath($classid);
	$r['savepath']=eReturnEcmsMainPortPath().$fspath['filepath'].$filepath;//moreport
	//附件地址
	$r['url']=$fspath['fileurl'].$filepath.$r['filename'];
	//缩图文件
	$r['name']=$r['savepath']."small".$r['insertfile'];
	//附件文件
	$r['yname']=$r['savepath'].$r['filename'];
	WriteFiletext_n($r['yname'],$string);
	$r['filesize']=@filesize($r['yname']);
	//返回类型
	if(strstr($ecms_config['sets']['tranflashtype'],','.$r['filetype'].','))
	{
		$r['type']=2;
	}
	elseif(strstr($ecms_config['sets']['tranpicturetype'],','.$r['filetype'].','))
	{
		$r['type']=1;
	}
	elseif(strstr($ecms_config['sets']['mediaplayertype'],','.$r['filetype'].',')||strstr($ecms_config['sets']['realplayertype'],','.$r['filetype'].','))//多媒体
	{
		$r['type']=3;
	}
	else
	{
		$r['type']=0;
	}
	//FileServer
	if($public_r['openfileserver'])
	{
		$efileftp_fr[]=$r['yname'];
	}
	return $r;
}

//删除附件
function DoDelFile($r){
	global $class_r,$public_r,$efileftp_dr;
	$path=$r['path']?$r['path'].'/':$r['path'];
	$fspath=ReturnFileSavePath($r['classid'],$r['fpath']);
	$delfile=eReturnEcmsMainPortPath().$fspath['filepath'].$path.$r['filename'];//moreport
	DelFiletext($delfile);
	//FileServer
	if($public_r['openfileserver'])
	{
		$efileftp_dr[]=$delfile;
	}
}

//替换表前缀
function RepSqlTbpre($sql){
	global $dbtbpre;
	$sql=str_replace('[!db.pre!]',$dbtbpre,$sql);
	return $sql;
}

//反替换表前缀
function ReRepSqlTbpre($sql){
	global $dbtbpre;
	$sql=str_replace($dbtbpre,'***_',$sql);
	return $sql;
}

//验证表是否存在
function eCheckTbname($tbname){
	global $empire,$dbtbpre;
	$tbname=RepPostVar($tbname);
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewstable where tbname='$tbname'".do_dblimit_cone());
	return $num;
}

//时间转换
function ToChangeUseTime($time){
	global $fun_r;
	$usetime=time()-$time;
	if($usetime<60)
	{
		$tstr=$usetime.$fun_r['TimeSecond'];
	}
	else
	{
		$usetime=round($usetime/60);
		$tstr=$usetime.$fun_r['TimeMinute'];
	}
	return $tstr;
}

//返回栏目集合
function ReturnClass($sonclass){
	if($sonclass==''||$sonclass=='|'){
		return 'classid=0';
	}
	$where='classid in ('.RepSonclassSql($sonclass).')';
	return $where;
}

//替换子栏目子
function RepSonclassSql($sonclass){
	if($sonclass==''||$sonclass=='|'){
		return 0;
	}
	$sonclass=substr($sonclass,1,strlen($sonclass)-2);
	$sonclass=str_replace('|',',',$sonclass);
	return $sonclass;
}

//返回多栏目
function sys_ReturnMoreClass($sonclass,$son=0){
	global $class_r;
	$r=explode(',',$sonclass);
	$count=count($r);
	$return_r[0]=intval($r[0]);
	$where='';
	$or='';
	for($i=0;$i<$count;$i++)
	{
		$r[$i]=intval($r[$i]);
		if($son==1)
		{
			if($class_r[$r[$i]]['tbname']&&!$class_r[$r[$i]]['islast'])
			{
				$where.=$or."classid in (".RepSonclassSql($class_r[$r[$i]]['sonclass']).")";
			}
			else
			{
				$where.=$or."classid='".$r[$i]."'";
			}
		}
		else
		{
			$where.=$or."classid='".$r[$i]."'";
		}
		$or=' or ';
	}
	$return_r[1]=$where;
	return $return_r;
}

//返回多专题
function sys_ReturnMoreZt($zt,$ecms=0){
	$f=$ecms==1?'ztid':'cid';
	$r=explode(',',$zt);
	$count=count($r);
	$return_r[0]=intval($r[0]);
	$ids='';
	$dh='';
	for($i=0;$i<$count;$i++)
	{
		$r[$i]=intval($r[$i]);
		$ids.=$dh.$r[$i];
		$dh=',';
	}
	$return_r[1]=$f.' in ('.$ids.')';
	return $return_r;
}

//返回多标题分类
function sys_ReturnMoreTT($tt){
	$r=explode(',',$tt);
	$count=count($r);
	$return_r[0]=intval($r[0]);
	$ids='';
	$dh='';
	for($i=0;$i<$count;$i++)
	{
		$r[$i]=intval($r[$i]);
		$ids.=$dh.$r[$i];
		$dh=',';
	}
	$return_r[1]='ttid in ('.$ids.')';
	return $return_r;
}

//验证是否包含栏目
function CheckHaveInClassid($cr,$checkclass){
	global $class_r;
	if($cr['islast'])
	{
		$chclass='|'.$cr['classid'].'|';
	}
	else
	{
		$chclass=$cr['sonclass'];
	}
	$return=0;
	$r=explode('|',$chclass);
	$count=count($r);
	for($i=1;$i<$count-1;$i++)
	{
		if(strstr($checkclass,'|'.$r[$i].'|'))
		{
			$return=1;
			break;
		}
	}
	return $return;
}

//返回加前缀的下载地址
function ReturnDownQzPath($path,$urlid){
	global $empire,$dbtbpre;
	$urlid=(int)$urlid;
	if(empty($urlid))
	{
		$re['repath']=$path;
		$re['downtype']=0;
    }
	else
	{
		$r=$empire->fetch1("select urlid,url,downtype from {$dbtbpre}enewsdownurlqz where urlid='$urlid'");
		if($r['urlid'])
		{
			$re['repath']=$r['url'].$path;
		}
		else
		{
			$re['repath']=$path;
		}
		$re['downtype']=$r['downtype'];
	}
	return $re;
}

//返回带防盗链的绝对地址
function ReturnDSofturl($downurl,$qz,$path='../../',$isdown=0){
	$urlr=ReturnDownQzPath(stripSlashes($downurl),$qz);
	$url=$urlr['repath'];
	@include_once(ECMS_PATH."e/DownSys/class/enpath.php");//防盗链
	if($isdown)
	{
		$url=DoEnDownpath($url);
	}
	else
	{
		$url=DoEnOnlinepath($url);
	}
	return $url;
}

//验证提交来源
function CheckCanPostUrl(){
	global $public_r;
	if($public_r['canposturl'])
	{
		$r=explode("\r\n",$public_r['canposturl']);
		$count=count($r);
		$b=0;
		for($i=0;$i<$count;$i++)
		{
			if(strstr($_SERVER['HTTP_REFERER'],$r[$i]))
			{
				$b=1;
				break;
			}
		}
		if($b==0)
		{
			printerror('NotCanPostUrl','',1);
		}
	}
}

//adminpath
function eGetSelfAdminPath(){
	global $ecms_config;
	$selfpath=eReturnSelfPage(0);
	$selfpath=str_replace("\\","/",$selfpath);
	if(strstr($selfpath,'//'))
	{
		exit();
	}
	$pr=explode('/'.$ecms_config['esafe']['hfadminpath'].'/',$selfpath);
	$pr2=explode('/',$pr[1]);
	$adminpath=$pr2[0];
	if(empty($adminpath))
	{
		exit();
	}
	return $adminpath;
}

//特殊来源验证
function hCheckSpFromUrl(){
	if(defined('EmpireCMSSpFromUrl'))
	{
		return '';
	}
	$spurl=',AddNews.php,ShowInfo.php,ShowWfInfo.php,EditCjNews.php,infoeditor,';
	$r=explode(',',$spurl);
	$count=count($r);
	$fromurl=$_SERVER['HTTP_REFERER'];
	for($i=1;$i<$count;$i++)
	{
		if(empty($r[$i]))
		{
			continue;
		}
		if(stristr($fromurl,$r[$i]))
		{
			printerror("FailHash","history.go(-1)");
		}
	}
}

//设定特殊来源
function hSetSpFromUrl(){
	define('EmpireCMSSpFromUrl',TRUE);
}

//验证来源
function DoSafeCheckFromurl(){
	global $ecms_config;
	if($ecms_config['esafe']['ckfromurl']==0||defined('EmpireCMSNFPage'))//不启用
	{
		return '';
	}
	$fromurl=$_SERVER['HTTP_REFERER'];
	if(!$fromurl)
	{
		return '';
	}
	$domain=eReturnDomain();
	if($ecms_config['esafe']['ckfromurl']==1)//全部启用
	{
		if(!stristr($fromurl,$domain))
		{
			echo"";
			exit();
		}
	}
	elseif($ecms_config['esafe']['ckfromurl']==2)//后台启用
	{
		if(defined('EmpireCMSAdmin')&&!stristr($fromurl,$domain.'/'))
		{
			echo"";
			exit();
		}
	}
	elseif($ecms_config['esafe']['ckfromurl']==3)//前台启用
	{
		if(!defined('EmpireCMSAdmin')&&!stristr($fromurl,$domain))
		{
			echo"";
			exit();
		}
	}
	elseif($ecms_config['esafe']['ckfromurl']==4)//全部启用(严格)
	{
		if(!stristr($fromurl,$domain))
		{
			echo"";
			exit();
		}
		if(defined('EmpireCMSAdmin'))
		{
			$adminpath=eGetSelfAdminPath();
			if(!stristr($fromurl,'/'.$ecms_config['esafe']['hfadminpath'].'/'.$adminpath.'/'))
			{
				echo"";
				exit();
			}
		}
	}
	elseif($ecms_config['esafe']['ckfromurl']==5)//后台启用(严格)
	{
		if(defined('EmpireCMSAdmin'))
		{
			if(!stristr($fromurl,$domain.'/'))
			{
				echo"";
				exit();
			}
			$adminpath=eGetSelfAdminPath();
			if(!stristr($fromurl,'/'.$ecms_config['esafe']['hfadminpath'].'/'.$adminpath.'/'))
			{
				echo"";
				exit();
			}
		}
	}
	elseif($ecms_config['esafe']['ckfromurl']==6)//前台启用(严格)
	{
		if(!defined('EmpireCMSAdmin')&&!stristr($fromurl,$domain))
		{
			echo"";
			exit();
		}
	}
}

//验证agent信息
function EcmsCheckUserAgent($ckstr){
	if(empty($ckstr))
	{
		return '';
	}
	$userinfo=$_SERVER['HTTP_USER_AGENT'];
	$cr=explode('||',$ckstr);
	$count=count($cr);
	for($i=0;$i<$count;$i++)
	{
		if(empty($cr[$i]))
		{
			continue;
		}
		if(!strstr($userinfo,$cr[$i]))
		{
			//echo'Userinfo Error';
			exit();
		}
	}
}

//验证IP
function eCheckAccessIp($ecms=0){
	global $public_r;
	$userip=egetip();
	if($ecms)//后台
	{
		//允许IP
		if($public_r['hopenip'])
		{
			$close=1;
			foreach(explode("\n",$public_r['hopenip']) as $ctrlip)
			{
				if(preg_match("/^(".preg_quote(($ctrlip=trim($ctrlip)),'/').")/",$userip))
				{
					$close=0;
					break;
				}
			}
			if($close==1)
			{
				echo"Ip<font color='#cccccc'>(".$userip.")</font> be prohibited.";
				exit();
			}
		}
	}
	else
	{
		//允许IP
		if($public_r['openip'])
		{
			$close=1;
			foreach(explode("\n",$public_r['openip']) as $ctrlip)
			{
				if(preg_match("/^(".preg_quote(($ctrlip=trim($ctrlip)),'/').")/",$userip))
				{
					$close=0;
					break;
				}
			}
			if($close==1)
			{
				echo"Ip<font color='#cccccc'>(".$userip.")</font> be prohibited.";
				exit();
			}
		}
		//禁止IP
		if($public_r['closeip'])
		{
			foreach(explode("\n",$public_r['closeip']) as $ctrlip)
			{
				if(preg_match("/^(".preg_quote(($ctrlip=trim($ctrlip)),'/').")/",$userip))
				{
					echo"Ip<font color='#cccccc'>(".$userip.")</font> be prohibited.";
					exit();
				}
			}
		}
	}
}

//验证提交IP
function eCheckAccessDoIp($doing){
	global $public_r,$empire,$dbtbpre;
	$pr=$empire->fetch1("select opendoip,closedoip,doiptype from {$dbtbpre}enewspublic".do_dblimit_one());
	if(!strstr($pr['doiptype'],','.$doing.','))
	{
		return '';
	}
	$userip=egetip();
	//允许IP
	if($pr['opendoip'])
	{
		$close=1;
		foreach(explode("\n",$pr['opendoip']) as $ctrlip)
		{
			if(preg_match("/^(".preg_quote(($ctrlip=trim($ctrlip)),'/').")/",$userip))
			{
				$close=0;
				break;
			}
		}
		if($close==1)
		{
			printerror('NotCanPostIp','history.go(-1)',1);
		}
	}
	//禁止IP
	if($pr['closedoip'])
	{
		foreach(explode("\n",$pr['closedoip']) as $ctrlip)
		{
			if(preg_match("/^(".preg_quote(($ctrlip=trim($ctrlip)),'/').")/",$userip))
			{
				printerror('NotCanPostIp','history.go(-1)',1);
			}
		}
	}
}

//验证后台是否关闭相关模块
function ehCheckCloseMods($mod){
	global $ecms_config;
	if(strstr($ecms_config['esafe']['hclosemods'],','.$mod.','))
	{
		echo $mod.' is close';
		exit();
	}
}

//验证是否关闭相关模块
function eCheckCloseMods($mod){
	global $public_r;
	if(strstr($public_r['closemods'],','.$mod.','))
	{
		echo $mod.' is close';
		exit();
	}
}

//验证操作时间
function eCheckTimeCloseDo($ecms){
	global $public_r;
	if(stristr($public_r['timeclosedo'],','.$ecms.','))
	{
		$h=date('G');
		if(strstr($public_r['timeclose'],','.$h.','))
		{
			printerror('ThisTimeCloseDo','history.go(-1)',1);
		}
	}
}

//访问密码
function EcmsViewPass($ecms,$ckvar,$ckpass,$title=''){
	if(!$ckpass)
	{
		return '';
	}
	$ckecms=$ecms==1?1:0;
	$evpostvname=$ecms==1?'hecmsckviewdof':'qecmsckviewdof';
	$reurl=eReturnSelfPage(1);
	if($_POST[$evpostvname])
	{
		if('dg'.$ckpass!='dg'.$_POST['eckvpassword'])
		{
			echo'<SCRIPT language=javascript>history.go(-1);</SCRIPT>';
			exit();
		}
		$setenpass=EcmsViewPassEn($ecms,$_POST['eckvpassword']);
		esetcookie($ckvar,$setenpass,0,0,$ckecms);
		echo'<meta http-equiv="refresh" content="0;url='.$reurl.'">';
		exit();
	}
	$getpass=getcvar($ckvar);
	if(!$getpass)
	{
		EcmsViewPassForm($title,$ecms);
	}
	$ckenpass=EcmsViewPassEn($ecms,$ckpass);
	if('dg'.$ckenpass!='dg'.$getpass)
	{
		EcmsViewPassForm($title,$ecms);
	}
}

//加密访问密码
function EcmsViewPassEn($ecms,$pass){
	if($ecms==1)//后台
	{
		$enpass=md5(md5(md5($pass).'Em~pi#').'re20^02-C.m-s');
	}
	else
	{
		$enpass=md5(md5(md5($pass).'pH~om#').'en^et.C.m-s');
	}
	return $enpass;
}

//访问密码(录入)
function EcmsViewPassForm($title='',$ecms=0){
	@include(ECMS_PATH.'e/message/viewpasspage.php');
	exit();
}

//验证外部登录是否开启
function eCheckCloseMemberConnect(){
	global $public_r;
	if(!$public_r['memberconnectnum'])
	{
		printerror('NotOpenMemberConnect','history.go(-1)',1);
	}
}

//过滤
function ClearNewsBadCode($text){
	$text=preg_replace(array('!<script!i','!</script>!i','!<link!i','!<iframe!i','!</iframe>!i','!<meta!i','!<body!i','!<style!i','!</style>!i','! onerror!i','!<marquee!i','!</marquee>!i','/<!--/','! onload!i','! onmouse!i','!<frame!i','!<frameset!i'),array('&lt;script','&lt;/script&gt;','&lt;link','&lt;iframe','&lt;/iframe&gt;','&lt;meta','&lt;body','&lt;style','&lt;/style&gt;',' one rror','&lt;marquee','&lt;/marquee&gt;','<!---ecms ',' onl oad',' onm ouse','&lt;frame','&lt;frameset'),$text);
	return $text;
}

//验证包含字符
function toCheckCloseWord($word,$closestr,$mess){
	if($closestr&&$closestr!='|')
	{
		$checkr=explode('|',$closestr);
		$ckcount=count($checkr);
		for($i=0;$i<$ckcount;$i++)
		{
			if($checkr[$i])
			{
				if(stristr($checkr[$i],'##'))//多字
				{
					$morer=explode('##',$checkr[$i]);
					if(stristr($word,$morer[0])&&stristr($word,$morer[1]))
					{
						printerror($mess,"history.go(-1)",1);
					}
				}
				else
				{
					if(stristr($word,$checkr[$i]))
					{
						printerror($mess,"history.go(-1)",1);
					}
				}
			}
		}
	}
}

//替换评论表情
function RepPltextFace($text){
	global $public_r;
	if(empty($public_r['plface'])||$public_r['plface']=='||')
	{
		return $text;
	}
	$facer=explode('||',$public_r['plface']);
	$count=count($facer);
	for($i=1;$i<$count-1;$i++)
	{
		$r=explode('##',$facer[$i]);
		$text=str_replace($r[0],"<img src='".$public_r['newsurl']."d/efilepub/eplface/".$r[1]."' border=0>",$text);
	}
	return $text;
}

//替换空格
function RepFieldtextNbsp($text){
	return str_replace(array("\t",'   ','  '),array('&nbsp; &nbsp; &nbsp; &nbsp; ','&nbsp; &nbsp;','&nbsp;&nbsp;'),$text);
}

//保留扩展名验证
function CheckSaveTranFiletype($filetype){
	$savetranfiletype=',.php,.php3,.php4,.php5,.php6,.php7,.php8,.php9,.phar,.asp,.aspx,.jsp,.cgi,.phtml,.asa,.asax,.fcgi,.pl,.ascx,.ashx,.cer,.cdx,.pht,.shtml,.shtm,.stm,';
	if(stristr($savetranfiletype,','.$filetype.','))
	{
		return true;
	}
	return false;
}

//设置验证码
function ecmsSetShowKey($varname,$val,$ecms=0,$isadmin=0){
	global $public_r;
	$pubkeyrnd=$isadmin==1?$public_r['hkeyrnd']:$public_r['keyrnd'];
	$time=time();
	$checkpass=md5('d!i#g?o-d-'.md5(md5($varname.'E.C#M!S^e-'.$val).'-E?m!P.i#R-e'.$time).$pubkeyrnd.'P#H!o,m^e-e');
	$key=$time.','.$checkpass.',EmpireCMS';
	esetcookie($varname,$key,0,$ecms);
}

//检查验证码
function ecmsCheckShowKey($varname,$postval,$dopr,$ecms=0,$isadmin=0){
	global $public_r;
	$postval=trim($postval);
	if($isadmin==1)
	{
		$pubkeytime=$public_r['hkeytime'];
		$pubkeyrnd=$public_r['hkeyrnd'];
	}
	else
	{
		$pubkeytime=$public_r['keytime'];
		$pubkeyrnd=$public_r['keyrnd'];
	}
	$r=explode(',',getcvar($varname,$ecms));
	$cktime=(int)$r[0];
	$pass=$r[1];
	$val=$r[2];
	$time=time();
	if($cktime>$time||$time-$cktime>$pubkeytime)
	{
		printerror('OutKeytime','',$dopr);
	}
	if(empty($postval))
	{
		printerror('FailKey','',$dopr);
	}
	$checkpass=md5('d!i#g?o-d-'.md5(md5($varname.'E.C#M!S^e-'.$postval).'-E?m!P.i#R-e'.$cktime).$pubkeyrnd.'P#H!o,m^e-e');
	if('dg'.$checkpass<>'dg'.$pass)
	{
		printerror('FailKey','',$dopr);
	}
}

//清空验证码
function ecmsEmptyShowKey($varname,$ecms=0,$isadmin=0){
	esetcookie($varname,'',0,$ecms);
}

//设置提交码
function DoSetActionPass($userid,$username,$rnd,$other,$ecms=0){
	global $ecms_config;
	$varname='actionepass';
	$date=date("Y-m-d-H");
	$pass=md5(md5($rnd.'-'.$userid.'-'.$date.'-'.$other).$ecms_config['cks']['ckrnd'].$username);
	esetcookie($varname,$pass,0,$ecms);
}

//清除提交码
function DoEmptyActionPass($ecms=0){
	$varname='actionepass';
	esetcookie($varname,'',0,$ecms);
}

//检测提交码
function DoCheckActionPass($userid,$username,$rnd,$other,$ecms=0){
	global $ecms_config;
	$varname='actionepass';
	$date=date("Y-m-d-H");
	$checkpass=md5(md5($rnd.'-'.$userid.'-'.$date.'-'.$other).$ecms_config['cks']['ckrnd'].$username);
	$pass=getcvar($varname,$ecms);
	if('dg'.$checkpass<>'dg'.$pass)
	{
		exit();
	}
}

//返回字段标识
function toReturnFname($tbname,$f){
	global $empire,$dbtbpre;
	$tbname=RepPostVar($tbname);
	$f=RepPostVar($f);
	$r=$empire->fetch1("select fname from {$dbtbpre}enewsf where f='$f' and tbname='$tbname'".do_dblimit_one());
	return $r['fname'];
}

//返回拼音
function ReturnPinyinFun($hz){
	global $ecms_config;
	include_once(ECMS_PATH.'e/class/epinyin.php');
	//编码
	if($ecms_config['sets']['pagechar']!='gb2312')
	{
		$char=$ecms_config['sets']['pagechar']=='big5'?'BIG5':'UTF8';
		$targetchar='GB2312';
		$hz=DoIconvVal($char,$targetchar,$hz);
	}
	return c($hz);
}

//取得字母
function GetInfoZm($hz){
	if(!trim($hz))
	{
		return '';
	}
	$py=ReturnPinyinFun($hz);
	$zm=substr($py,0,1);
	return strtoupper($zm);
}

//返回加密后的IP
function ToReturnXhIp($ip,$n=2){
	if(strstr($ip,':'))
	{
		$jg=':';
	}
	else
	{
		$jg='.';
	}
	$newip='';
	$d='';
	$ipr=explode($jg,$ip);
	//$ipnum=$jg==':'?4:count($ipr);
	$ipnum=4;
	for($i=0;$i<$ipnum;$i++)
	{
		if($i==$ipnum-1)
		{
			$ipr[$i]='*';
		}
		if($n==2)
		{
			if($i==$ipnum-2)
			{
				$ipr[$i]='*';
			}
		}
		$newip.=$d.$ipr[$i];
		$d=$jg;
	}
	return $newip;
}

//验证是否使用https
function eCheckUseHttps(){
	if(!empty($_SERVER['HTTPS'])&&strtolower($_SERVER['HTTPS'])!=='off')
	{
		return 1;
	}
	elseif(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])&&strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'])==='https')
	{
        return 1;
    }
	elseif(!empty($_SERVER['HTTP_FRONT_END_HTTPS'])&&strtolower($_SERVER['HTTP_FRONT_END_HTTPS'])!=='off')
	{
        return 1;
    }
	else
	{
		return 0;
	}
}

//验证是否使用https
function eReturnHttpsQz(){
	$http=eCheckUseHttps()==1?'https://':'http://';
	return $http;
}

//返回当前页面地址
function eReturnSelfLocationUrl(){
	$phpself=$_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME'];
	$url=RepPostStrUrl(eReturnHttpsQz().$_SERVER['HTTP_HOST'].$phpself.'?'.$_SERVER['QUERY_STRING']);
	return $url;
}

//返回当前页面地址(无参数)
function eReturnSelfLocationPage(){
	$phpself=$_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME'];
	$url=RepPostStrUrl(eReturnHttpsQz().$_SERVER['HTTP_HOST'].$phpself);
	return $url;
}

//多次转向
function eapage_NumGotoSelfUrl($n,$add){
	$egn=(int)$add['egn'];
	$n=(int)$n;
	if($egn>=$n)
	{
		return '';
	}
	$sec=0;
	$gotourl=eReturnSelfPage(1).'&egn='.($egn+1);
	echo'<meta http-equiv="refresh" content="'.$sec.';url='.$gotourl.'">';
	exit();
}

//返回http类型
function eReturnHttpType(){
	global $public_r;
	if($public_r['httptype'])
	{
		if($public_r['httptype']==1)
		{
			return 'http://';
		}
		elseif($public_r['httptype']==2)
		{
			return 'https://';
		}
		elseif($public_r['httptype']==3)
		{
			if(defined('EmpireCMSAdmin'))
			{
				return 'https://';
			}
			else
			{
				return 'http://';
			}
		}
		elseif($public_r['httptype']==4)
		{
			if(defined('EmpireCMSAdmin'))
			{
				return 'http://';
			}
			else
			{
				return 'https://';
			}
		}
	}
	return eCheckUseHttps()==1?'https://':'http://';
}

//返回当前域名2
function eReturnTrueDomain(){
	$domain=RepPostStr($_SERVER['HTTP_HOST'],1);
	if(empty($domain))
	{
		return '';
	}
	return $domain;
}

//返回当前域名
function eReturnDomain(){
	$domain=RepPostStr($_SERVER['HTTP_HOST'],1);
	if(empty($domain))
	{
		return '';
	}
	return eReturnHttpType().$domain;
}

//返回域名网站地址
function eReturnDomainSiteUrl(){
	global $public_r;
	$PayReturnUrlQz=$public_r['newsurl'];
	if(!stristr($public_r['newsurl'],'://'))
	{
		$PayReturnUrlQz=eReturnDomain().$public_r['newsurl'];
	}
	return $PayReturnUrlQz;
}

//返回当前地址
function eReturnSelfPage($ecms=0){
	$phpself=$_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME'];
	if(empty($ecms))
	{
		$page=$phpself;
	}
	else
	{
		$page=$phpself.'?'.$_SERVER['QUERY_STRING'];
	}
	$page=str_replace('&amp;','&',RepPostStr($page,1));
	return $page;
}

//验证当前会员权限
function sys_CheckMemberGroup($groupid){
	if(!defined('InEmpireCMSUser'))
	{
		include_once ECMS_PATH.'e/member/class/user.php';
	}
	$r=qCheckLoginAuthstr();
	if(!$r['islogin'])
	{
		return 0;
	}
	if(!strstr(','.$groupid.',',','.$r['groupid'].','))
	{
		return -1;
	}
	return 1;
}

//返回会员头像扩展名
function eMember_UpicReturnGtype($upic){
	$ut_r=array();
	$ut_r[1]='.jpg';
	$ut_r[2]='.gif';
	$ut_r[3]='.png';
	$ut_r[4]='.bmp';
	$ut_r[5]='.jpeg';
	$ut_r[6]='.webp';
	return $ut_r[$upic];
}

//返回会员头像扩展名
function eMember_UpicReturnGupic($upictype){
	$upic=0;
	if($upictype=='.jpg')
	{
		$upic=1;
	}
	elseif($upictype=='.gif')
	{
		$upic=2;
	}
	elseif($upictype=='.png')
	{
		$upic=3;
	}
	elseif($upictype=='.bmp')
	{
		$upic=4;
	}
	elseif($upictype=='.jpeg')
	{
		$upic=5;
	}
	elseif($upictype=='.webp')
	{
		$upic=6;
	}
	else
	{
		exit();
	}
	return $upic;
}

//获取会员头像目录和文件名
function eMember_UpicReturnPathfile($userid,$upic=1){
	global $public_r,$ecms_config;
	$userid=(int)$userid;
	$upic=(int)$upic;
	if($userid<1)
	{
		exit();
	}
	$len=strlen($userid);
	$r=array();
	$r['rfiletype']=eMember_UpicReturnGtype($upic);
	if($userid<1000)
	{
		$r['rpath']='000/00/00';
		$r['rinsertfile']=$userid;
		$r['rfile']=$r['rinsertfile'].$r['rfiletype'];
	}
	elseif($userid<100000)
	{
		$p1=$len==5?substr($userid,-5,2):'0'.substr($userid,-4,1);
		$r['rpath']='000/00/'.$p1;
		$r['rinsertfile']=substr($userid,-3);
		$r['rfile']=$r['rinsertfile'].$r['rfiletype'];
	}
	elseif($userid<10000000)
	{
		$p1=substr($userid,-5,2);
		$p2=$len==7?substr($userid,-7,2):'0'.substr($userid,-6,1);
		$r['rpath']='000/'.$p2.'/'.$p1;
		$r['rinsertfile']=substr($userid,-3);
		$r['rfile']=$r['rinsertfile'].$r['rfiletype'];
	}
	else
	{
		$p1=substr($userid,-5,2);
		$p2=substr($userid,-7,2);
		if($len==10)
		{
			$p3=substr($userid,0,3);
		}
		elseif($len==9)
		{
			$p3='0'.substr($userid,0,2);
		}
		else
		{
			$p3='00'.substr($userid,0,1);
		}
		$r['rpath']=$p3.'/'.$p2.'/'.$p1;
		$r['rinsertfile']=substr($userid,-3);
		$r['rfile']=$r['rinsertfile'].$r['rfiletype'];
	}
	$fspath=ReturnFileSavePath(0,3);
	$r['rbasepath']=$fspath['filepath'];
	$r['rbasefurl']=$fspath['fileurl'];
	$r['rupicpathfile']=$r['rbasepath'].$r['rpath'].'/'.$r['rfile'];
	$r['rupicurl']=$r['rbasefurl'].$r['rpath'].'/'.$r['rfile'];
	return $r;
}

//返回会员头像地址
function eMember_UpicReturnUrl($userid,$upic=1,$ckf=0){
	global $public_r;
	$userid=(int)$userid;
	$r=eMember_UpicReturnPathfile($userid,$upic);
	if($ckf&&$upic)
	{
		if(!file_exists(eReturnEcmsMainPortPath().$r['rupicpathfile']))//moreport
		{
			return $public_r['upicdef'];
		}
	}
	if($upic)
	{
		$upicurl=$r['rupicurl'];
	}
	else
	{
		$upicurl=$public_r['upicdef'];
	}
	return $upicurl;
}

//删除会员头像
function eMember_UpicDelFile($userid,$upic=1){
	global $efileftp_dr;
	$userid=(int)$userid;
	if(!$userid||!$upic)
	{
		return '';
	}
	$r=eMember_UpicReturnPathfile($userid,$upic);
	$delfile=eReturnEcmsMainPortPath().$r['rupicpathfile'];//moreport
	DelFiletext($delfile);
	//FileServer
	if($public_r['openfileserver'])
	{
		$efileftp_dr[]=$delfile;
	}
}

//手机号码检查
function echphno($phno){
	if($phno)
	{
		if(!eCheckStrType(1,$phno,0))
		{
			return false;
		}
		$len=strlen($phno);
		if($len<5||$len>20)
		{
			return false;
		}
	}
	return true;
}

//EMAIL地址检查
function chemail($email){
	$chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($email, '@') !== false && strpos($email, '.') !== false)
    {
        if (preg_match($chars, $email))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

//密码复杂度检测
function ePasswordCkChar($password){
    if(strlen($password)<8)
	{
		return 'PasswordCharLess';
    }
    if(!preg_match("#[0-9]+#",$password))
	{
		return 'PasswordCharSz';
    }
    if(!preg_match("#[a-zA-Z]+#",$password))
	{
		return 'PasswordCharZm';
    }
    if(!preg_match("#[A-Z]+#",$password))
	{
		return 'PasswordCharDzm';
    }
    if(!preg_match("#\W+#",$password))
	{
		return 'PasswordCharTs';
    }
	return 'EmpireCMS';
}

//去除adds
function ClearAddsData($data){
	if(MAGIC_QUOTES_GPC)
	{
		$data=stripSlashes($data);
	}
	return $data;
}

//增加adds
function AddAddsData($data){
	if(!MAGIC_QUOTES_GPC)
	{
		$data=addslashes($data);
	}
	return $data;
}

//原字符adds
function StripAddsData($data){
	$data=addslashes(stripSlashes($data));
	return $data;
}

//反增加adds
function fAddAddsData($data){
	if(MAGIC_QUOTES_GPC)
	{
		$data=addslashes($data);
	}
	return $data;
}


//------- doencode -------

//endata
function eed_endata($str,$key='',$outtime=0){
	$str=eed_authcode($str,'ENCODE',$key,$outtime);
	return $str;
}

//dedata
function eed_dedata($str,$key=''){
	$str=eed_authcode($str,'DECODE',$key);
	return $str;
}

//authcode
function eed_authcode($string,$operation='DECODE',$key='',$expiry=0) {
	global $ecms_config;
	$ckey_length = 4;
	$key=md5($key!=''?$key:$ecms_config['esafe']['endatakey']);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}


//------- 存文本 -------

//读取文本字段内容
function GetTxtFieldText($pagetexturl){
	global $ecms_config;
	if(empty($pagetexturl))
	{
		return '';
	}
	$file=$ecms_config['sets']['txtpath'].$pagetexturl.".php";
	$text=ReadFiletext($file);
	$text=substr($text,12);//去除exit
	return $text;
}

//取得文本地址
function GetTxtFieldTextUrl($pagetexturl){
	global $ecms_config;
	$file=$ecms_config['sets']['txtpath'].$pagetexturl.".php";
	return $file;
}

//修改文本字段内容
function EditTxtFieldText($pagetexturl,$pagetext){
	global $ecms_config;
	eCheckStrPathGet($pagetexturl);
	$pagetext="<? exit();?>".$pagetext;
	$file=$ecms_config['sets']['txtpath'].$pagetexturl.".php";
	WriteFiletext_n($file,$pagetext);
}

//删除文本字段内容
function DelTxtFieldText($pagetexturl){
	global $ecms_config;
	if(empty($pagetexturl))
	{
		return '';
	}
	eCheckStrPathGet($pagetexturl);
	$file=$ecms_config['sets']['txtpath'].$pagetexturl.".php";
	DelFiletext($file);
}

//取得随机数
function GetFileMd5(){
	$p=md5(uniqid(microtime()).EcmsRandInt());
	return $p;
}

//建立存放目录
function MkDirTxtFile($date,$file){
	global $ecms_config;
	$r=explode("/",$date);
	eCheckStrType(1,$r[0],1);
	eCheckStrType(1,$r[1],1);
	eCheckStrType(3,$file,1);
	$path=$ecms_config['sets']['txtpath'].$r[0];
	DoMkdir($path);
	$path=$ecms_config['sets']['txtpath'].$r[0].'/'.$r[1];
	DoMkdir($path);
	$returnpath=$r[0].'/'.$r[1].'/'.$file;
	return $returnpath;
}

//替换公共标记
function ReplaceSvars($temp,$url,$classid,$title,$key,$des,$add,$repvar=1){
	global $public_r,$class_r,$class_zr;
	if($repvar==1)//全局模板变量
	{
		$temp=ReplaceTempvar($temp);
	}
	$temp=str_replace('[!--class.menu--]',$public_r['classnavs'],$temp);//栏目导航
	$temp=str_replace('[!--newsnav--]',$url,$temp);//位置导航
	$temp=str_replace('[!--pagetitle--]',$title,$temp);
	$temp=str_replace('[!--pagekey--]',$key,$temp);
	$temp=str_replace('[!--pagedes--]',$des,$temp);
	$temp=str_replace('[!--self.classid--]',0,$temp);
	$temp=str_replace('[!--news.url--]',$public_r['newsurl'],$temp);
	return $temp;
}

//返回数组组合字符
function eReturnRDataStr($r){
	$r=eCheckEmptyArray($r);
	$count=count($r);
	if(!$count)
	{
		return '';
	}
	$str=',';
	for($i=0;$i<$count;$i++)
	{
		$str.=$r[$i].',';
	}
	return $str;
}

//------- firewall -------

//提示
function FWShowMsg($msg){
	//echo $msg;
	exit();
}

//防火墙
function DoEmpireCMSFireWall(){
	global $ecms_config;
	if(!empty($ecms_config['fw']['adminloginurl']))
	{
		$usehost=FWeReturnDomain();
		$alur=explode('||',$ecms_config['fw']['adminloginurl']);
		$alucount=count($alur);
		$alutrue=0;
		for($alui=0;$alui<$alucount;$alui++)
		{
			if('dg'.$usehost=='dg'.$alur[$alui])
			{
				$alutrue=1;
				break;
			}
		}
		if($alutrue!=1)
		{
			FWShowMsg('Login Url');
		}
	}
	if($ecms_config['fw']['adminhour']!=='')
	{
		$h=date('G');
		if(!strstr(','.$ecms_config['fw']['adminhour'].',',','.$h.','))
		{
			FWShowMsg('Admin Hour');
		}
	}
	if($ecms_config['fw']['adminweek']!=='')
	{
		$w=date('w');
		if(!strstr(','.$ecms_config['fw']['adminweek'].',',','.$w.','))
		{
			FWShowMsg('Admin Week');
		}
	}
	if(!defined('EmpireCMSAPage')&&$ecms_config['fw']['adminckpassvar']&&$ecms_config['fw']['adminckpassval'])
	{
		FWCheckPassword();
	}
}

//返回当前域名
function FWeReturnDomain(){
	$domain=RepPostStr($_SERVER['HTTP_HOST'],1);
	if(empty($domain))
	{
		return '';
	}
	return eReturnHttpType().$domain;
}

//检查敏感字符
function FWClearGetText($str){
	global $ecms_config;
	if(empty($ecms_config['fw']['eopen']))
	{
		return '';
	}
	if(empty($ecms_config['fw']['cleargettext']))
	{
		return '';
	}
	$r=explode(',',$ecms_config['fw']['cleargettext']);
	$count=count($r);
	for($i=0;$i<$count;$i++)
	{
		if(stristr($r[$i],'##'))//多字
		{
			$morer=explode('##',$r[$i]);
			if(stristr($str,$morer[0])&&stristr($str,$morer[1]))
			{
				FWShowMsg('Post String');
			}
		}
		else
		{
			if(stristr($str,$r[$i]))
			{
				FWShowMsg('Post String');
			}
		}
	}
}

//后台防火墙密码
function FWSetPassword(){
	global $ecms_config;
	if(!$ecms_config['fw']['eopen']||!$ecms_config['fw']['adminckpassvar']||!$ecms_config['fw']['adminckpassval'])
	{
		return '';
	}
	$ip=$ecms_config['esafe']['ckhloginip']==0?'127.0.0.1':egetip();
	$ecmsckpass=md5(md5($ecms_config['fw']['adminckpassval'].'-empirecms-'.$ecms_config['fw']['epass']).'-'.$ip.'-'.$ecms_config['fw']['adminckpassval'].'-phome.net-');
	esetcookie($ecms_config['fw']['adminckpassvar'],$ecmsckpass,0,1);
}

function FWCheckPassword(){
	global $ecms_config;
	if(!$ecms_config['fw']['eopen']||!$ecms_config['fw']['adminckpassvar']||!$ecms_config['fw']['adminckpassval'])
	{
		return '';
	}
	$ip=$ecms_config['esafe']['ckhloginip']==0?'127.0.0.1':egetip();
	$ecmsckpass=md5(md5($ecms_config['fw']['adminckpassval'].'-empirecms-'.$ecms_config['fw']['epass']).'-'.$ip.'-'.$ecms_config['fw']['adminckpassval'].'-phome.net-');
	if('dg'.$ecmsckpass<>'dg'.getcvar($ecms_config['fw']['adminckpassvar'],1))
	{
		FWShowMsg('Password');
	}
}

function FWEmptyPassword(){
	global $ecms_config;
	esetcookie($ecms_config['fw']['adminckpassvar'],'',0,1);
}


//--------------- 缓存 ---------------

//取最后一两级目录
function Ecms_eReturnShowMkdir($path){
	global $ecms_config;
	if(!$ecms_config['sets']['webdebug'])
	{
		return '';
	}
	if(!stristr($path,'/'))
	{
		return '';
	}
	$path=str_replace(eReturnTrueEcmsPath(),'/',$path);
	$r=explode('/',$path);
	$count=count($r);
	if($count<2)
	{
		return '';
	}
	else
	{
		return '/'.$r[$count-1];
	}
}

//建立目录(普通)
function Ecms_eMkdir($path){
	if(!file_exists($path))
	{
		$mk=@mkdir($path,0777);
		@chmod($path,0777);
		if(empty($mk))
		{
			echo 'Create path fail: '.Ecms_eReturnShowMkdir($path);
			exit();
		}
	}
	return true;
}

//递级建立目录
function Ecms_eMoreMkdir($basepath,$path){
	if(empty($path))
	{
		return '';
	}
	if(file_exists($basepath.$path))
	{
		return $path;
	}
	$returnpath='';
	$r=explode('/',$path);
	$count=count($r);
	for($i=0;$i<$count;$i++)
	{
		if(empty($r[$i]))
		{
			continue;
		}
		if($returnpath)
		{
			$returnpath.='/'.$r[$i];
		}
		else
		{
			$returnpath.=$r[$i];
		}
		$createpath=$basepath.$returnpath;
		$mk=Ecms_eMkdir($createpath);
	}
	return $returnpath;
}

//取得文件内容(可锁定)
function Ecms_ReadFiletext($filepath,$dolock=0){
	$filepath=trim($filepath);
	$htmlfp=@fopen($filepath,"rb");
	if($dolock==1)
	{
		flock($htmlfp,LOCK_SH);
	}
	$string=@fread($htmlfp,@filesize($filepath));
	if($dolock==1)
	{
		flock($htmlfp,LOCK_UN); 
	}
	@fclose($htmlfp);
	return $string;
}

//写文件(可锁定)
function Ecms_WriteFiletext($filepath,$string,$dolock=0,$strip=0){
	global $public_r;
	if($strip==1)
	{
		$string=stripSlashes($string);
	}
	$fp=@fopen($filepath,"wb");
	if($dolock==1)
	{
		flock($fp,LOCK_EX);
	}
	@fputs($fp,$string);
	if($dolock==1)
	{
		flock($fp,LOCK_UN);
	}
	@fclose($fp);
	if(empty($public_r['filechmod']))
	{
		@chmod($filepath,0777);
	}
}

//返回文件修改时间
function Ecms_GetFileEditTime($filepath){
	return file_exists($filepath)?intval(filemtime($filepath)):0;
}

//ID返回动态缓存目录
function ePagenoGetPageCache($cpage){
	$r=array();
	if($cpage==1)//首页
	{
		$r['esyspath']='empirecms';
		$r['cpath']='cindex';
	}
	elseif($cpage==2)//封面
	{
		$r['esyspath']='empirecms';
		$r['cpath']='cpage';
	}
	elseif($cpage==3)//列表
	{
		$r['esyspath']='empirecms';
		$r['cpath']='clist';
	}
	elseif($cpage==4)//内容
	{
		$r['esyspath']='empirecms';
		$r['cpath']='ctext';
	}
	elseif($cpage==5)//标题分类
	{
		$r['esyspath']='empirecms';
		$r['cpath']='cinfotype';
	}
	elseif($cpage==6)//TAGS
	{
		$r['esyspath']='empirecms';
		$r['cpath']='ctags';
	}
	elseif($cpage==7)//父子信息
	{
		$r['esyspath']='empirecms';
		$r['cpath']='cfzinfo';
	}
	elseif($cpage==8)//自定义动态页面
	{
		$r['esyspath']='empirecms';
		$r['cpath']='cdtuserpage';
	}
	elseif($cpage==10000)//ALLECMS
	{
		$r['esyspath']='empirecms';
		$r['cpath']='';
	}
	else
	{
		$r['esyspath']='';
		$r['cpath']='no';
	}
	return $r;
}

//返回缓存文件名
function Ecms_eCacheReturnFile($cachetype,$ids,$datepath,$path='empirecms'){
	global $ecms_config,$public_r;
	$filer['basepath']=$ecms_config['sets']['ecmscachepath'].$path.'/';
	$filer['cpath']=$datepath;
	$filer['cfile']=md5($cachetype.'!-'.$ids.'-,'.$public_r['ctimernd'].'-!'.$path).$ecms_config['sets']['ecmscachefiletype'];
	$filer['ctruefile']=$filer['basepath'].$filer['cpath'].'/'.$filer['cfile'];
	return $filer;
}

//输出缓存
function Ecms_eCacheOut($cr,$usedo=0){
	$cachetime=abs($cr['cachetime'])*60;
	$filer=Ecms_eCacheReturnFile($cr['cachetype'],$cr['cacheids'],$cr['cachedatepath'],$cr['cachepath']);
	$cachefile=$filer['ctruefile'];
	$filetime=Ecms_GetFileEditTime($cachefile);
	if(!$filetime)
	{
		return 0;
	}
	$time=time();
	if($time-$filetime>$cachetime)
	{
		return 0;
	}
	$cr['cachelastedit']=(int)$cr['cachelastedit'];
	if($cr['cachelastedit']&&$cr['cachelastedit']>$filetime)
	{
		return 0;
	}
	if($cr['cachelasttime']>=$filetime)
	{
		return 0;
	}
	echo Ecms_ReadFiletext($cachefile);
	if($usedo==0)
	{
		db_close();
		$empire=null;
		exit();
	}
	elseif($usedo==1)
	{
		exit();
	}
	else
	{}
	return 1;
}

//写入缓存
function Ecms_eCacheIn($cr,$cachetext){
	$filer=Ecms_eCacheReturnFile($cr['cachetype'],$cr['cacheids'],$cr['cachedatepath'],$cr['cachepath']);
	$cachefile=$filer['ctruefile'];
	Ecms_eMoreMkdir($filer['basepath'],$filer['cpath']);
	Ecms_WriteFiletext($cachefile,$cachetext,1);
	echo $cachetext;
}

//验证是否启用缓存
function Ecms_eCacheCheckOpen($cachetime){
	global $ecms_config,$public_r,$ecms_tofunr;
	if(empty($public_r['ctimeopen']))
	{
		return 0;
	}
	if($cachetime>0)
	{
		$open=1;
	}
	elseif($cachetime<0)
	{
		$open=1;
		$userid=(int)getcvar('mluserid');
		if($userid)
		{
			$open=0;
		}
	}
	else
	{
		$open=0;
	}
	if($public_r['ctimegids'])
	{
		$groupid=(int)getcvar('mlgroupid');
		if($groupid&&strstr(','.$public_r['ctimegids'].',',','.$groupid.','))
		{
			$open=0;
		}
	}
	if($public_r['ctimecids']&&$ecms_tofunr['cacheselfcid'])
	{
		$selfcid=(int)$ecms_tofunr['cacheselfcid'];
		if($selfcid&&strstr(','.$public_r['ctimecids'].',',','.$selfcid.','))
		{
			$open=0;
		}
	}
	return $open;
}

//设置更新缓存
function eDoUpCache($id,$tname,$ecms=0,$ck=0){
	global $empire,$dbtbpre,$public_r;
	if(empty($public_r['ctimeopen']))
	{
		return '';
	}
	$time=time();
	$uptime=$time-2;
	$addwhere='';
	$addwhere_index=' where fclastindex<'.$uptime;
	if($ck==1)
	{
		$addwhere=' and fclast<'.$uptime;
		$addwhere_index=' where fclastindex<'.$uptime;
	}
	if($ecms==1)//栏目
	{
		if(!$id)
		{
			return '';
		}
		$empire->query("update {$dbtbpre}enewsclass set fclast='$time' where classid in (".$id.")".$addwhere);
	}
	elseif($ecms==2)//标题分类
	{
		if(!$id)
		{
			return '';
		}
		$empire->query("update {$dbtbpre}enewsinfotype set fclast='$time' where typeid in (".$id.")".$addwhere);
	}
	elseif($ecms==3)//内容页
	{
		if(!$id||!$tname)
		{
			return '';
		}
		$empire->query("update {$dbtbpre}ecms_".$tname." set lastdotime='$time' where id in (".$id.")");
	}
	elseif($ecms==4)//TAGS
	{
		if(!$id&&!$tname)
		{
			return '';
		}
		if($tname)
		{
			$tr=explode(',',$tname);
			$tcount=count($tr);
			$where='';
			$or='';
			for($ti=0;$ti<$tcount;$ti++)
			{
				$tr[$ti]=RepPostVar($tr[$ti]);
				if(!$tr[$ti])
				{
					continue;
				}
				$where.=$or."tagname='".$tr[$ti]."'";
				$or=' or ';
			}
			if(!$where)
			{
				return '';
			}
		}
		else
		{
			$where="tagid in (".$id.")";
		}
		$empire->query("update {$dbtbpre}enewstags set fclast='$time' where ".$where.$addwhere);
	}
	else//首页
	{
		$empire->query("update {$dbtbpre}enewspublic_fc set fclastindex='$time'".$addwhere_index."".do_dblimit_upone());
	}
}

//设置更新缓存
function eUpCacheInfo($ecms,$classid,$id,$pid,$ttid,$tagid,$tagname,$oldclassid=0,$oldttid=0,$ck=0){
	global $empire,$dbtbpre,$public_r,$class_r;
	if(empty($public_r['ctimeopen']))
	{
		return '';
	}
	$ctimeaddre=$ecms==1?$public_r['ctimeaddre']:$public_r['ctimeqaddre'];
	if(!$ctimeaddre)
	{
		return '';
	}
	$classid=(int)$classid;
	$id=(int)$id;
	$pid=(int)$pid;
	$ttid=(int)$ttid;
	$oldclassid=(int)$oldclassid;
	$oldttid=(int)$oldttid;
	//首页
	if($ctimeaddre==2||$ctimeaddre==4||$ctimeaddre==6||$ctimeaddre==7||$ctimeaddre==8)
	{
		eDoUpCache(0,'',0,$ck);
	}
	//栏目
	if($ctimeaddre!=2)
	{
		if(!empty($classid))
		{
			$cids='';
			if($ctimeaddre==1)//当前
			{
				$cids=$classid;
				if($oldclassid&&$oldclassid!=$classid)
				{
					$cids.=','.$oldclassid;
				}
			}
			else
			{
				$featherclass=$class_r[$classid]['featherclass'];
				if($ctimeaddre>=5)
				{
					if(empty($featherclass))
					{
						$featherclass='|';
					}
					$featherclass.=$classid.'|';
					if($oldclassid&&$oldclassid!=$classid)
					{
						$featherclass.=$oldclassid.'|';
					}
				}
				$cids=eReturnInFcids($featherclass);
			}
			if(empty($cids))
			{
				return '';
			}
			eDoUpCache($cids,'',1,$ck);
		}
	}
	//标题分类
	if($ctimeaddre>=7)
	{
		if(!empty($ttid))
		{
			if($oldttid&&$oldttid!=$ttid)
			{
				$ttid.=','.$oldttid;
			}
			eDoUpCache($ttid,'',2,$ck);
		}
	}
	//TAGS
	if($ctimeaddre>=8)
	{
		eDoUpCache('',$tagname,4,$ck);
	}
	//信息
	if($id||$pid)
	{
		$tbname=$class_r[$classid]['tbname'];
		if(!empty($tbname))
		{
			$ids='';
			$iddh='';
			if($id)
			{
				$ids.=$id;
				$iddh=',';
			}
			if($pid)
			{
				$ids.=$iddh.$pid;
				$iddh=',';
			}
			eDoUpCache($ids,$tbname,3,$ck);
		}
	}
}


//--------------- 每天生成随机数 ---------------

//执行每天生成随机数
function DoEcmsConfigDayUpRnd(){
	global $public_r;
	if($public_r['drretype']==1)//每天自动
	{
		EcmsConfigDayUpRnd();
	}
	elseif($public_r['drretype']==2)//按条件
	{
		$selfurl=eReturnSelfPage(1);
		if(strstr($selfurl,$public_r['drrepage']))
		{
			EcmsConfigDayUpRnd();
		}
	}
	else//手动
	{}
}

//每天生成随机数
function EcmsConfigDayUpRnd($ecms=''){
	global $ecms_config,$public_dayr;
	$thisdrdate=date('Ymd');
	//马上执行
	if($ecms=='mustdore')
	{}
	else
	{
		//当天
		if($thisdrdate==$public_dayr['drdate'])
		{
			return '';
		}
	}
	$file=ECMS_PATH.'c/ecachedb/edayrnd.php';
	$filestr='';
	$allrnds='';
	$thisqdrckcom=EcmsRandIntStr(40,70);
	$thisdrckcom=EcmsRandIntStr(40,70);
	$num=30;
	for($i=1;$i<=$num;$i++)
	{
		$thisrnd=EcmsRandIntStr(36,60);
		$allrnds.="'dr".$i."'=>'".addslashes($thisrnd)."',";
	}
	$thisdrstart=EcmsRandInt(1,20);
	$filestr.="<?php
\$public_dayr=array();
\$public_dayr=Array('drdate'=>'".$thisdrdate."',
".$allrnds."
'qdrckcom'=>'".$thisqdrckcom."',
'drckcom'=>'".$thisdrckcom."',
'drstart'=>'".$thisdrstart."');
?>";
	Ecms_WriteFiletext($file,$filestr,1);
}

//获取每天随机数
function eGetDayRnd($n){
	global $public_dayr;
	$thisn=$public_dayr['drstart']+$n;
	$thisv='dr'.$thisn;
	return $public_dayr[$thisv];
}


//返回前台附加验证字符串
function eapage_qReturnCkComPass($userid,$username,$groupid,$ecms=''){
	global $ecms_config,$public_dayr,$public_r;
	$date=date("Y-m-d");
	$ckpass=md5(md5('di-'.$userid.'-God,'.$ecms).md5('el-'.$groupid.'-ink,'.$username.'-us'.$userid.'#er-'.$public_dayr['qdrckcom']).$date.$ecms.'-com,'.$public_dayr['qdrckcom']);
	return $ckpass;
}

//验证前台附加验证字符串
function eapage_qCkComPass($userid,$username,$groupid,$dopass,$ecms=''){
	global $ecms_config,$public_dayr,$public_r;
	$userid=(int)$userid;
	$username=RepPostVar($username);
	$groupid=(int)$groupid;
	$dopass=RepPostVar($dopass);
	$ecms=RepPostVar($ecms);
	$ckpass=eapage_qReturnCkComPass($userid,$username,$groupid,$ecms);
	if('dg'.$ckpass!='dg'.$dopass)
	{
		printerror('EapageErrorPass',$public_r['newsurl'],1);
	}
}

//返回后台附加验证字符串
function eapage_ReturnCkComPass($userid,$username,$groupid,$ecms=''){
	global $ecms_config,$public_dayr,$public_r;
	$date=date("Y-m-d");
	$ckpass=md5(md5('eM-'.$ecms.'-Pi,'.$userid).md5('Rec-'.$groupid.'-Ms,'.$userid.'-Eli'.$username.'#nkUs-'.$public_dayr['drckcom']).$ecms.','.$date.'-er.com,'.$public_dayr['drckcom']);
	return $ckpass;
}

//后台附加验证字符串
function eapage_CkComPass($userid,$username,$groupid,$dopass,$ecms=''){
	global $ecms_config,$public_dayr,$public_r;
	$userid=(int)$userid;
	$username=RepPostVar($username);
	$groupid=(int)$groupid;
	$dopass=RepPostVar($dopass);
	$ecms=RepPostVar($ecms);
	$ckpass=eapage_ReturnCkComPass($userid,$username,$groupid,$ecms);
	if('dg'.$ckpass!='dg'.$dopass)
	{
		printerror('EapageErrorPass',$public_r['newsurl']);
	}
}

//返回验证字符串
function eapage_ReturnCkPass($classid,$id,$rnd,$time,$ecms){
	global $ecms_config,$public_dayr,$public_r;
	$ckrnd=$public_r['eackrnd'];
	$ckdayrnd=eGetDayRnd(1);
	$date=date("Y-m-d");
	$ckpass=md5(md5('Em-'.$id.'Pir-'.$classid.'#Ecms').md5('ecms-'.$classid.'Dig-'.$rnd.'!od-'.$id.'-.com#'.$ckrnd).$ecms.'.net'.$date.'Pho-'.$time.'-me-'.$ckdayrnd);
	return $ckpass;
}

//验证字符串
function eapage_CkPass($showclassid,$showid,$showrnd,$showtime,$showcode,$ecms){
	global $ecms_config,$public_dayr,$public_r;
	$time=time();
	$showclassid=RepPostVar($showclassid);
	$showid=RepPostVar($showid);
	$showrnd=RepPostVar($showrnd);
	$showtime=(int)$showtime;
	$showcode=RepPostVar($showcode);
	if($showtime>$time||$time-$showtime>$public_r['eacktime'])
	{
		printerror('EapageOutTime',$public_r['newsurl']);
	}
	$ckpass=eapage_ReturnCkPass($showclassid,$showid,$showrnd,$showtime,$ecms);
	if('dg'.$ckpass!='dg'.$showcode)
	{
		printerror('EapageErrorPass',$public_r['newsurl']);
	}
}

//信息预览验证设置
function eapage_SetPassShowInfo($classid,$id,$ecmsck='eaShowInfo'){
	$r['classid']=(int)$classid;
	$r['id']=(int)$id;
	$r['dotime']=time();
	$r['ecmsck']=RepPostVar($ecmsck);
	$r['dornd']=EcmsRandIntStr(28,55);
	$r['dopass']=eapage_ReturnCkPass($r['classid'],$r['id'],$r['dornd'],$r['dotime'],$r['ecmsck']);
	$r['urlcs']='&classid='.$r['classid'].'&id='.$r['id'].'&dornd='.$r['dornd'].'&dotime='.$r['dotime'].'&ecmsck='.$r['ecmsck'].'&dopass='.$r['dopass'];
	return $r;
}

//信息预览验证
function eapage_CkPassShowInfo($classid,$id,$dornd,$dotime,$dopass,$ecmsck='eaShowInfo'){
	$classid=(int)$classid;
	$id=(int)$id;
	$dornd=RepPostVar($dornd);
	$dotime=(int)$dotime;
	$dopass=RepPostVar($dopass);
	$ecmsck=RepPostVar($ecmsck);
	eapage_CkPass($classid,$id,$dornd,$dotime,$dopass,$ecmsck);
	$urlcs='&classid='.$classid.'&id='.$id.'&dornd='.$dornd.'&dotime='.$dotime.'&ecmsck='.$ecmsck.'&dopass='.$dopass;
	return $urlcs;
}

//返回信息预览地址
function eapage_hGetGotoUrl($turl,$addpath,$classid,$id,$ecmsck='eaShowInfo',$mtrue=0){
	global $public_r,$ecms_hashur;
	if(empty($public_r['eaopenpage'])||$mtrue==1)
	{
		return $turl;
	}
	$url=$addpath.'toeapage/SetGotoPage.php?classid='.$classid.'&id='.$id.'&ecmsck='.$ecmsck.$ecms_hashur['ehref'];
	return $url;
}

//访问搜索转发
function ePostGoSearchUrl($dokb,$sec=0){
	global $empire,$dbtbpre,$public_r;
	if(!$public_r['opensearchurl'])
	{
		return '';
	}
	if(is_array($dokb))
	{
		$kb=$dokb[0];
	}
	else
	{
		$kb=$dokb;
	}
	if(!$kb)
	{
		return '';
	}
	$kb=RepPostVar2($kb);
	$r=$empire->fetch1("select id,url from {$dbtbpre}enewssearchurl where title='$kb'".do_dblimit_one());
	if(!$r['url'])
	{
		return '';
	}
	$time=time();
	$sql=$empire->query("update {$dbtbpre}enewssearchurl set onclick=onclick+1,lasttime='$time' where id='".$r['id']."'");
	echo'<meta http-equiv="refresh" content="'.$sec.';url='.$r['url'].'">';
	db_close();
	$empire=null;
	exit();
}



//--------------- eapi ---------------

//jsonen
function eapi_JsonEn($jsonr,$ecms=0){
	if(!is_array($jsonr))
	{
		return $jsonr;
	}
	if(!function_exists('json_encode'))
	{
		$jsonstr=eapi_JsonEnFun($jsonr,$ecms);
	}
	else
	{
		$jsonstr=json_encode($jsonr);
	}
	return $jsonstr;
}

//jsonenfun
function eapi_JsonEnFun($jsonr,$ecms=0){
	if(!is_array($jsonr))
	{
		return $jsonr;
	}
	$jsonstr='';
	$dh='';
	foreach($jsonr as $rkey=>$rval)
	{
		$jsonstr.=$dh.'"'.$rkey.'":'.(is_array($rval)?eapi_JsonEn($rval,0):'"'.eapi_JsonEnRepstr($rval).'"');
		$dh=',';
	}
	$jsonstr='{'.$jsonstr.'}';
	return $jsonstr;
}

//jsonenrepstr
function eapi_JsonEnRepstr($jsonstr){
	$jsonstr=str_replace(array("\\'","\r","\n","\t","\b","\f"),array("'","\\r","\\n","\\t","\\b","\\f"),addslashes($jsonstr));
	return $jsonstr;
}

//jsonde
function eapi_JsonDe($erejsonstr,$ecms=0){
	if(!function_exists('json_decode'))
	{
		return '';
	}
	if($ecms==1)
	{
		$erejsonr=json_decode($erejsonstr);
	}
	else
	{
		$erejsonr=json_decode($erejsonstr,true);
	}
	return $erejsonr;
}

//jsonprinterror
function eapi_JsonPrintError($result,$code,$msg,$data,$gotourl='',$ecms=0){
	$msg_r=array();
	$msg_r['result']=$result;
	$msg_r['code']=$code;
	$msg_r['msg']=$msg;
	$msg_r['data']=$data;
	$jsonstr=eapi_JsonEn($msg_r,0);
	return $jsonstr;
}


//jsondbquery
function eapi_JsonDbQuery($jsonquery,$ecms=0){
	global $empire,$dbtbpre;
	$jsondb_r=array();
	if($ecms==1)
	{
		$json_r=$empire->fetch1($jsonquery);
		$jsondb_r=eapi_JsonForeach($json_r);
		return $jsondb_r;
	}
	else
	{
		$jsonsql=$empire->query($jsonquery);
		if(!$jsonsql)
		{
			return $jsondb_r;
		}
		while($json_r=$empire->fetch($jsonsql))
		{
			$jsondb_r[]=eapi_JsonForeach($json_r);
		}
	}
	return $jsondb_r;
}

//jsondbquery
function eapi_JsonForeach($json_r,$ecms=0){
	$jsondb_oner=array();
	foreach($json_r as $rkey=>$rval)
	{
		$jsondb_oner[$rkey]=$rval;
	}
	return $jsondb_oner;
}

//--------------- eapi ---------------


?>