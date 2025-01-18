<?php
require("../class/connect.php");
require("../class/q_functions.php");
require("../member/class/user.php");
eCheckCloseMods('pay');//关闭模块
$link=db_connect();
$empire=new mysqlquery();

$money=(float)$_POST['money'];
$money=efmmoney($money);
if($money<=0)
{
	printerror('支付金额不能为0','',1,0,1);
}
$payid=(int)$_POST['payid'];
if(!$payid)
{
	printerror('请选择支付平台','',1,0,1);
}
$payr=$empire->fetch1("select * from {$dbtbpre}enewspayapi where payid='$payid' and isclose=0");
if(!$payr['payid'])
{
	printerror('请选择支付平台','',1,0,1);
}
$ddno='';
$productname='';
$productsay='';
$phome=$_POST['phome'];
if($phome=='PayToFen')//购买点数
{
	$productname='购买点数';
}
elseif($phome=='PayToMoney')//存预付款
{
	$productname='存预付款';
}
else
{
	printerror('您来自的链接不存在','',1,0,1);
}

include('payfun.php');

//支付参数
$prdr=array();
$prdr['userid']=0;
$prdr['username']='';
$prdr['orderid']='';
$prdr['money']=$money;
$prdr['posttime']='';
$prdr['paybz']='';
$prdr['paytype']=$payr['paytype'];
$prdr['payip']='';
$prdr['ispay']=0;
$prdr['paydo']='';
$prdr['payfor']='';
$prdr['payckcode']='';
$prdr['pname']='';
$prdr['psay']='';
$prdr['endtime']='';
//支付参数
//登录
$user=array();
if($phome=='PayToFen'||$phome=='PayToMoney')
{
	$user=islogin();//是否登录
	$prdr['userid']=$user['userid'];
	$prdr['username']=$user['username'];
}

if($phome=='PayToFen')
{
	$pr=$empire->fetch1("select paymoneytofen,payminmoney from {$dbtbpre}enewspublic".do_dblimit_one());
	if($prdr['money']<$pr['payminmoney'])
	{
		printerror('金额不能小于 '.$pr['payminmoney'].' 元','',1,0,1);
	}
	$fen=floor($prdr['money'])*$pr['paymoneytofen'];
	$prdr['paydo']='PayToFen';
	$prdr['payfor']=$fen;
	$prdr['pname']="购买点数,UID:".$prdr['userid'].",UName:".$prdr['username'];
	$prdr['psay']="购买点数,用户ID:".$prdr['userid'].",用户名:".$prdr['username'];
}
elseif($phome=='PayToMoney')
{
	$pr=$empire->fetch1("select paymoneytofen,payminmoney from {$dbtbpre}enewspublic".do_dblimit_one());
	if($prdr['money']<$pr['payminmoney'])
	{
		printerror('金额不能小于 '.$pr['payminmoney'].' 元','',1,0,1);
	}
	$prdr['paydo']='PayToMoney';
	$prdr['payfor']=$prdr['money'];
	$prdr['pname']="存预付款,UID:".$prdr['userid'].",UName:".$prdr['username'];
	$prdr['psay']="存预付款,用户ID:".$prdr['userid'].",用户名:".$prdr['username'];
}
//支付参数
$prdr['payddno']=epayapi_ReturnDdno($prdr['userid']);
$ddno=$prdr['payddno'];
//支付参数
$productname=$prdr['pname'];
$productsay=$prdr['psay'];

esetcookie("payphome",$phome,0);
//返回地址前缀
$PayReturnUrlQz=$public_r['newsurl'];
if(!stristr($public_r['newsurl'],'://'))
{
	$PayReturnUrlQz=eReturnDomain().$public_r['newsurl'];
}
//编码
$iconv='';
$char='';
$targetchar='';
/*
if($ecms_config['sets']['pagechar']!='gb2312')
{
	$char=$ecms_config['sets']['pagechar']=='big5'?'BIG5':'UTF8';
	$targetchar='GB2312';
	$productname=DoIconvVal($char,$targetchar,$productname);
	$productsay=DoIconvVal($char,$targetchar,$productsay);
	@header('Content-Type: text/html; charset=gb2312');
}
*/
//记录
$re_prdr=epayapi_AddPayRecord($prdr);
$prdr['id']=$re_prdr['id'];
$prdr['payckcode']=$re_prdr['payckcode'];
$prdr['posttime']=$re_prdr['posttime'];
$prdr['payip']=$re_prdr['payip'];

$file=$payr['paytype'].'/to_pay.php';
@include($file);
db_close();
$empire=null;
?>