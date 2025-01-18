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
	$r=$empire->fetch1("select isurl,titleurl,classid,id from {$dbtbpre}ecms_".$class_r[$classid]['tbname']." where id='$id'".do_dblimit_one());
	if(empty($r['id']))
	{
		printerror("ErrorUrl","",1);
    }
	$titleurl=sys_ReturnBqTitleLink($r);
	db_close();
	$empire=null;
	Header("Location:$titleurl");
}
?>