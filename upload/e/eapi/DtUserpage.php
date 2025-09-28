<?php
require('../class/connect.php');
require('../class/functions.php');
$link=db_connect();
$empire=new mysqlquery();
eCheckCloseMods('dtup');//关闭模块

//函数

//导入页面
function eapi_DtUserpageLoad($eapi_funr){
	global $empire,$dbtbpre,$public_r,$emod_r,$class_r,$class_zr,$fun_r,$navclassid,$navinfor,$class_tr,$level_r,$etable_r,$etable_t,$page,$eapi_r,$start,$line,$page_line,$offset;
	$eapi_funr['aid']=(int)$eapi_funr['aid'];
	$dtupfile=ECMS_PATH.'c/ecacheapi/edtuserpage/dt'.$eapi_funr['aid'].'.php';
	//读取文件内容
	if(!file_exists($dtupfile))
	{
		return '';
	}
	ob_start();
	include($dtupfile);
	$string=ob_get_contents();
	ob_end_clean();
	return $string;
}

//函数


$aid=(int)$_GET['aid'];
$apass=RepPostVar($_GET['apass']);
$eapi_r=$empire->fetch1("select * from {$dbtbpre}enewsdtuserpage where aid='$aid'".do_dblimit_one());
if(!$eapi_r['aid'])
{
	printerror('此信息不存在','',1,0,1);
}
if('dg'.$apass!='dg'.$eapi_r['apass'])
{
	printerror('此信息不存在','',1,0,1);
}

$GLOBALS['navclassid']=$eapi_r['aid'];
$GLOBALS['navinfor']=array();

//分页
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
if($eapi_r['maxpage']!=-1)
{
	if($eapi_r['maxpage'])
	{
		if($page>=$eapi_r['maxpage'])
		{
			$page=0;
		}
	}
	else
	{
		$page=0;
	}
}
$start=0;
$line=10;//每页显示记录数
$page_line=10;//每页显示链接数
$offset=$page*$line;//总偏移量
//分页


//缓存
if($eapi_r['actime'])
{
	$public_r['usetotalnum']=0;
}
$ecms_tofunr=array();
$ecms_tofunr['cacheuse']=0;
$ecms_tofunr['cachetype']='dtuserpage';
$ecms_tofunr['cacheids']=$eapi_r['aid'].','.$page;
$ecms_tofunr['cachepath']='empirecms';
$ecms_tofunr['cachedatepath']='cdtuserpage/'.$eapi_r['aid'];
$ecms_tofunr['cachetime']=$eapi_r['actime'];
$ecms_tofunr['cachelasttime']=$public_r['ctimelast'];
$ecms_tofunr['cachelastedit']=$eapi_r['aclast'];
$ecms_tofunr['cacheopen']=$eapi_r['actime']?1:0;
if($ecms_tofunr['cacheopen']==1)
{
	$ecms_tofunr['cacheuse']=Ecms_eCacheOut($ecms_tofunr,0);
}
//缓存


//引用文件
if($eapi_r['atype']==1)//不引用
{}
elseif($eapi_r['atype']==2)//引用栏目
{
	include('../data/dbcache/class.php');
}
elseif($eapi_r['atype']==3)//引用栏目+标签
{
	include('../class/t_functions.php');
	include('../data/dbcache/class.php');
}
elseif($eapi_r['atype']==4)//引用栏目+分页
{
	include LoadLang('pub/fun.php');
	include('../data/dbcache/class.php');
}
elseif($eapi_r['atype']==5)//引用栏目+会员组
{
	include('../data/dbcache/class.php');
	include('../data/dbcache/MemberLevel.php');
}
elseif($eapi_r['atype']==6)//引用栏目+标签+分页
{
	include('../class/t_functions.php');
	include LoadLang('pub/fun.php');
	include('../data/dbcache/class.php');
}
elseif($eapi_r['atype']==7)//引用栏目+标签+会员组
{
	include('../class/t_functions.php');
	include('../data/dbcache/class.php');
	include('../data/dbcache/MemberLevel.php');
}
elseif($eapi_r['atype']==8)//引用栏目+标签+会员组+分页
{
	include('../class/t_functions.php');
	include LoadLang('pub/fun.php');
	include('../data/dbcache/class.php');
	include('../data/dbcache/MemberLevel.php');
}
else
{
	include('../class/t_functions.php');
	include LoadLang('pub/fun.php');
	include('../data/dbcache/class.php');
	include('../data/dbcache/MemberLevel.php');
}

$string=eapi_DtUserpageLoad($eapi_r);

//缓存
if($ecms_tofunr['cacheopen']==1)
{
	Ecms_eCacheIn($ecms_tofunr,stripSlashes($string));
}
else
{
	echo stripSlashes($string);
}
//缓存

db_close();
$empire=null;
?>