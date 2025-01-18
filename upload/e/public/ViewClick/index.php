<?php
require("../../class/connect.php");
$link=db_connect();
$empire=new mysqlquery();
eCheckCloseMods('viewnum');//关闭模块
$id=(int)$_GET['id'];
$classid=(int)$_GET['classid'];
$down=(int)$_GET['down'];
$shownum=0;
$classf='tid,tbname';
if($down==2)
{
	$classf.=',checkpl';
}
if($down==7)//专题
{
	$cr=$empire->fetch1("select restb from {$dbtbpre}enewszt where ztid='$classid'".do_dblimit_one());
	if(!$cr['restb'])
	{
		exit();
	}
}
else
{
	$cr=$empire->fetch1("select ".$classf." from {$dbtbpre}enewsclass where classid='$classid'".do_dblimit_one());
	if(empty($cr['tbname'])||InfoIsInTable($cr['tbname']))
	{
		exit();
	}
}
//浏览数
if($down==0)
{
	//年月日统计
	$ymdtotaleo='';
	$ymdtid=(int)$cr['tid'];
	if($_GET['addclick']==1&&strstr($emod_pubr['tbidseo'],','.$ymdtid.','))
	{
		$r=$empire->fetch1("select * from {$dbtbpre}ecms_".$cr['tbname']." where id='$id'".do_dblimit_one());
		$ymdtotaleo=eReturnYmdTotalf($r,0);
	}
	else
	{
		$r=$empire->fetch1("select onclick from {$dbtbpre}ecms_".$cr['tbname']." where id='$id'".do_dblimit_one());
	}
	$shownum=$r['onclick']+1;
	if($_GET['addclick']==1)
	{
		$usql=$empire->query("update {$dbtbpre}ecms_".$cr['tbname']." set onclick=onclick+1".$ymdtotaleo." where id='$id'".do_dblimit_upone());
	}
}
//下载数
elseif($down==1)
{
	$r=$empire->fetch1("select totaldown from {$dbtbpre}ecms_".$cr['tbname']." where id='$id'".do_dblimit_one());
	$shownum=$r['totaldown'];
}
//评论数
elseif($down==2)
{
	if($cr['checkpl'])
	{
		$r=$empire->fetch1("select restb from {$dbtbpre}ecms_".$cr['tbname']." where id='$id'".do_dblimit_one());
		if(!$r['restb'])
		{
			exit();
		}
		$pubid=ReturnInfoPubid(0,$id,$cr['tid']);
		$shownum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspl_".$r['restb']." where pubid='$pubid' and checked=0");
	}
	else
	{
		$r=$empire->fetch1("select plnum from {$dbtbpre}ecms_".$cr['tbname']." where id='$id'".do_dblimit_one());
		$shownum=$r['plnum'];
	}
}
//评分数
elseif($down==3)
{
	$r=$empire->fetch1("select infopfen,infopfennum from {$dbtbpre}ecms_".$cr['tbname']." where id='$id'".do_dblimit_one());
	$shownum=$r['infopfennum']?round($r['infopfen']/$r['infopfennum']):0;
}
//评分人数
elseif($down==4)
{
	$r=$empire->fetch1("select infopfennum from {$dbtbpre}ecms_".$cr['tbname']." where id='$id'".do_dblimit_one());
	$shownum=$r['infopfennum'];
}
//digg顶数
elseif($down==5)
{
	$r=$empire->fetch1("select diggtop from {$dbtbpre}ecms_".$cr['tbname']." where id='$id'".do_dblimit_one());
	$shownum=$r['diggtop'];
}
//digg踩数
elseif($down==6)
{
	$r=$empire->fetch1("select diggdown from {$dbtbpre}ecms_".$cr['tbname']." where id='$id'".do_dblimit_one());
	$shownum=$r['diggdown'];
}
//专题评论数
elseif($down==7)
{
	$pubid='-'.$classid;
	$shownum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspl_".$cr['restb']." where pubid='$pubid' and checked=0");
}
db_close();
$empire=null;
echo"document.write('".$shownum."');";
?>