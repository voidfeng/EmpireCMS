<?php
require("../../class/connect.php");
$id=(int)$_GET['id'];
$classid=(int)$_GET['classid'];
if($id&&$classid)
{
	include("../../data/dbcache/class.php");
	$link=db_connect();
	$empire=new mysqlquery();
	$editor=1;
	if(empty($class_r[$classid]['tbname'])||InfoIsInTable($class_r[$classid]['tbname']))
	{
		printerror("ErrorUrl","",1);
    }
	//年月日统计
	$ymdtotaleo='';
	$ymdtid=(int)$class_r[$classid]['tid'];
	if(strstr($emod_pubr['tbidseo'],','.$ymdtid.','))
	{
		$r=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$classid]['tbname']." where id='$id'".do_dblimit_one());
		$ymdtotaleo=eReturnYmdTotalf($r,0);
	}
	else
	{
		$r=$empire->fetch1("select isurl,titleurl,classid,id from {$dbtbpre}ecms_".$class_r[$classid]['tbname']." where id='$id'".do_dblimit_one());
	}
	if(empty($r['isurl']))
	{
		printerror("ErrorUrl","",1);
    }
	$url=$r['titleurl'];
	$sql=$empire->query("update {$dbtbpre}ecms_".$class_r[$classid]['tbname']." set onclick=onclick+1".$ymdtotaleo." where id='$id'");
	db_close();
	$empire=null;
	Header("Location:$url");
}
?>