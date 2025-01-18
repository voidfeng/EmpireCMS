<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证用户
$lur=is_login();
$logininid=(int)$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=(int)$lur['groupid'];
$loginadminstyleid=(int)$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();
//验证权限
CheckLevel($logininid,$loginin,$classid,"pay");

//返回变量
function RetPostPayApiVars($add){
	$r=array();
	$r['payid']=(int)$add['payid'];
	$r['paytype']=RepPostVar($add['paytype']);
	$r['isclose']=(int)$add['isclose'];
	$r['myorder']=(int)$add['myorder'];
	$r['paymethod']=(int)$add['paymethod'];
	$r['opennturl']=(int)$add['opennturl'];
	$r['payname']=hRepPostStr($add['payname'],1);
	$r['paysay']=hRepPostStr2($add['paysay']);
	$r['payuser']=hRepPostStr2($add['payuser']);
	$r['paykey']=hRepPostStr2($add['paykey']);
	$r['payfee']=hRepPostStr($add['payfee'],1);
	$r['partner']=hRepPostStr($add['partner'],1);
	$r['paylogo']=hRepPostStr($add['paylogo'],1);
	$r['payemail']=hRepPostStr($add['payemail'],1);
	$r['payappid']=hRepPostStr2($add['payappid']);
	$r['payopenid']=hRepPostStr2($add['payopenid']);
	$r['paymchid']=hRepPostStr2($add['paymchid']);
	$r['diyset1']=hRepPostStr($add['diyset1'],1);
	$r['diyset2']=hRepPostStr($add['diyset2'],1);
	$r['diyset3']=hRepPostStr($add['diyset3'],1);
	$r['diyset4']=hRepPostStr($add['diyset4'],1);
	$r['diyset5']=hRepPostStr($add['diyset5'],1);
	eCheckStrType(4,$r['paytype'],1);
	//验证目录
	if(!file_exists('../../../e/payapi/'.$r['paytype']))
	{
		printerror("NotPayApiPath","");
	}
	return $r;
}

//增加接口
function AddPayApi($add,$userid,$username){
	global $empire,$dbtbpre;
	$r=RetPostPayApiVars($add);
	if(!$r['payname']||!$r['paytype'])
	{
		printerror("EmptyPayApi","history.go(-1)");
    }
	$sql=$empire->updatesql("insert into {$dbtbpre}enewspayapi(paytype,myorder,payfee,payuser,partner,paykey,paylogo,paysay,payname,isclose,payemail,paymethod,payappid,payopenid,paymchid,opennturl,diyset1,diyset2,diyset3,diyset4,diyset5) values('".$r['paytype']."','".$r['myorder']."','".$r['payfee']."','".$r['payuser']."','".$r['partner']."','".$r['paykey']."','".$r['paylogo']."','".$r['paysay']."','".$r['payname']."','".$r['isclose']."','".$r['payemail']."','".$r['paymethod']."','".$r['payappid']."','".$r['payopenid']."','".$r['paymchid']."','".$r['opennturl']."','".$r['diyset1']."','".$r['diyset2']."','".$r['diyset3']."','".$r['diyset4']."','".$r['diyset5']."');","ins");
	$lastid=$empire->lastid($dbtbpre.'enewspayapi','payid');
	if($sql)
	{		
		//操作日志
		insert_dolog("payid=".$lastid."<br>payname=".$r['payname']);
		printerror("AddPayApiSuccess","PayApi.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//设置接口
function EditPayApi($add,$userid,$username){
	global $empire,$dbtbpre;
	$payid=(int)$add['payid'];
	$r=RetPostPayApiVars($add);
	if(!$r['payname']||!$payid||!$r['paytype'])
	{
		printerror("EmptyPayApi","history.go(-1)");
    }
	$sql=$empire->query("update {$dbtbpre}enewspayapi set paytype='".$r['paytype']."',isclose='".$r['isclose']."',payname='".$r['payname']."',paysay='".$r['paysay']."',payuser='".$r['payuser']."',paykey='".$r['paykey']."',payfee='".$r['payfee']."',partner='".$r['partner']."',paylogo='".$r['paylogo']."',payemail='".$r['payemail']."',myorder='".$r['myorder']."',paymethod='".$r['paymethod']."',payappid='".$r['payappid']."',payopenid='".$r['payopenid']."',paymchid='".$r['paymchid']."',opennturl='".$r['opennturl']."',diyset1='".$r['diyset1']."',diyset2='".$r['diyset2']."',diyset3='".$r['diyset3']."',diyset4='".$r['diyset4']."',diyset5='".$r['diyset5']."' where payid='".$payid."'");
	if($sql)
	{
		//操作日志
		insert_dolog("payid=".$payid."<br>payname=".$r['payname']);
		printerror("EditPayApiSuccess","PayApi.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除接口
function DelPayApi($add,$userid,$username){
	global $empire,$dbtbpre;
	$payid=(int)$add['payid'];
	if(!$payid)
	{
		printerror("NotDelPayApi","history.go(-1)");
	}
	$r=$empire->fetch1("select payid,paytype,payname from {$dbtbpre}enewspayapi where payid='$payid'");
	if(!$r['payid'])
	{
		printerror("NotDelPayApi","history.go(-1)");
	}
	$sql=$empire->query("delete from {$dbtbpre}enewspayapi where payid='$payid'");
	if($sql)
	{
		//操作日志
		insert_dolog("payid=".$payid."<br>payname=".$r['payname']);
		printerror("DelPayApiSuccess","PayApi.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//支付参数设置
function SetPayFen($add,$userid,$username){
	global $empire,$dbtbpre;
	$add['paymoneytofen']=(int)$add['paymoneytofen'];
	$add['payminmoney']=(int)$add['payminmoney'];
	if(empty($add['paymoneytofen']))
	{
		printerror("EmptySetPayFen","history.go(-1)");
    }
	$sql=$empire->query("update {$dbtbpre}enewspublic set paymoneytofen='".$add['paymoneytofen']."',payminmoney='".$add['payminmoney']."'");
	if($sql)
	{
		//操作日志
		insert_dolog("moneytofen=".$add['paymoneytofen']."&minmoney=".$add['payminmoney']);
		printerror("SetPayFenSuccess","SetPayFen.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//修改
if($enews=="EditPayApi")
{
	EditPayApi($_POST,$logininid,$loginin);
}
elseif($enews=="AddPayApi")//增加
{
	AddPayApi($_POST,$logininid,$loginin);
}
elseif($enews=="DelPayApi")//删除
{
	DelPayApi($_GET,$logininid,$loginin);
}
elseif($enews=="SetPayFen")
{
	SetPayFen($_POST,$logininid,$loginin);
}

$sql=$empire->query("select payid,paytype,payfee,paylogo,paysay,payname,isclose from {$dbtbpre}enewspayapi order by myorder,payid");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>支付接口</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="50%" height="32">位置：在线支付&gt; <a href="PayApi.php<?=$ecms_hashur['whehref']?>">管理支付接口</a> </td>
    <td><div align="right" class="emenubutton">
        <input type="button" name="Submit53" value="增加支付接口" onclick="self.location.href='SetPayApi.php?enews=AddPayApi<?=$ecms_hashur['ehref']?>';">
		&nbsp;&nbsp; 
        <input type="button" name="Submit5" value="管理支付记录" onclick="self.location.href='ListPayRecord.php<?=$ecms_hashur['whehref']?>';">
        &nbsp;&nbsp; 
        <input type="button" name="Submit52" value="支付参数设置" onclick="self.location.href='SetPayFen.php<?=$ecms_hashur['whehref']?>';">
      </div></td>
  </tr>
</table>
<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="17%"><div align="center">接口名称</div></td>
    <td width="43%"><div align="center">接口描述</div></td>
    <td width="10%"><div align="center">状态</div></td>
    <td width="14%" height="25"><div align="center">接口目录</div></td>
    <td width="16%" height="25"><div align="center">操作</div></td>
  </tr>
  <?php
  //formhash
  $efh=heformhash_get('DelPayApi',1);
  
  while($r=$empire->fetch($sql))
  {
	  if($r['paytype']=='')
	  {
		  //$r['payname']="<font color='red'><b>".$r['payname']."</b></font>";
	  }
  ?>
  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
    <td height="38" align="center"> 
      <?=$r['payname']?>
    </td>
    <td>
      <?=ehtmlspecialchars($r['paysay'])?>
    </td>
    <td><div align="center">
        <?=$r['isclose']==0?'开启':'关闭'?>
      </div></td>
    <td height="25"> <div align="center">
        <?=$r['paytype']?>
      </div></td>
    <td height="25"> <div align="center">[<a href="SetPayApi.php?enews=EditPayApi&payid=<?=$r['payid']?><?=$ecms_hashur['ehref']?>">配置接口</a>] &nbsp; [<a href="PayApi.php?enews=DelPayApi&payid=<?=$r['payid']?><?=$ecms_hashur['href'].$efh?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?php
  }
  db_close();
  $empire=null;
  ?>
</table>
<br>
</body>
</html>
