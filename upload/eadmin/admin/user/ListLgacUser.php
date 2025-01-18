<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
require "../../../e/data/".LoadLang("pub/fun.php");
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
CheckLevel($logininid,$loginin,$classid,"cklgac");


//------------------------后台登录激活
function CheckLgacUser($add,$loginuserid,$loginusername){
	global $empire,$class_r,$dbtbpre;
	$userid=(int)$add['userid'];
	if(!$userid)
	{
		printerror("NotLgacUserid","history.go(-1)");
	}
	//操作权限
	CheckLevel($loginuserid,$loginusername,$classid,"cklgac");
	//用户
	$yuser_r=$empire->fetch1("select userid,username from {$dbtbpre}enewsuser where userid='".$userid."'");
	if(!$yuser_r['userid'])
	{
		printerror("NotLgacUserid","history.go(-1)");
	}
	$lgactime=time();
	$sql=$empire->query("update {$dbtbpre}enewsuser set lgac=1,goac=0,lgactime='$lgactime' where userid='$userid'");
	if($sql)
	{
		//操作日志
		insert_dolog("userid=".$userid."<br>username=".$yuser_r['username']);
		printerror("CheckLgacUserSuccess","ListLgacUser.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}


$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//登录激活
if($enews=="CheckLgacUser")
{
	CheckLgacUser($_GET,$logininid,$loginin);
}


$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$url="<a href=ListLgacUser.php".$ecms_hashur['whehref'].">管理后台登录激活</a>";
//排序
$mydesc=(int)$_GET['mydesc'];
$desc=$mydesc?'asc':'desc';
$orderby=(int)$_GET['orderby'];
if($orderby==1)//用户名
{
	$order="username ".$desc.",userid desc";
	$usernamedesc=$mydesc?0:1;
}
elseif($orderby==2)//用户组
{
	$order="groupid ".$desc.",userid desc";
	$groupiddesc=$mydesc?0:1;
}
elseif($orderby==3)//状态
{
	$order="checked ".$desc.",userid desc";
	$checkeddesc=$mydesc?0:1;
}
elseif($orderby==4)//登陆次数
{
	$order="loginnum ".$desc.",userid desc";
	$loginnumdesc=$mydesc?0:1;
}
elseif($orderby==5)//最后登录
{
	$order="lasttime ".$desc.",userid desc";
	$lasttimedesc=$mydesc?0:1;
}
else//用户ID
{
	$order="userid ".$desc;
	$useriddesc=$mydesc?0:1;
}
$search="&orderby=$orderby&mydesc=$mydesc".$ecms_hashur['ehref'];
$query="select * from {$dbtbpre}enewsuser where goac=1";
$totalquery="select count(*) as total from {$dbtbpre}enewsuser where goac=1";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by ".$order."".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理后台登录激活</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="50%" height="32">位置： 
      <?=$url?>    </td>
    <td><div align="right" class="emenubutton">
      </div></td>
  </tr>
</table>
<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="4%" height="25"><div align="center"><a href="ListLgacUser.php?orderby=0&mydesc=<?=$useriddesc?><?=$ecms_hashur['ehref']?>">ID</a></div></td>
    <td width="22%" height="25"><div align="center"><a href="ListLgacUser.php?orderby=1&mydesc=<?=$usernamedesc?><?=$ecms_hashur['ehref']?>">用户名</a></div></td>
    <td width="17%"><div align="center"><a href="ListLgacUser.php?orderby=2&mydesc=<?=$groupiddesc?><?=$ecms_hashur['ehref']?>">等级</a></div></td>
    <td width="6%"><div align="center"><a href="ListLgacUser.php?orderby=3&mydesc=<?=$checkeddesc?><?=$ecms_hashur['ehref']?>">状态</a></div></td>
    <td width="9%"><div align="center">绑定认证</div></td>
    <td width="8%"><div align="center"><a href="ListLgacUser.php?orderby=4&mydesc=<?=$loginnumdesc?><?=$ecms_hashur['ehref']?>">登录次数</a></div></td>
    <td width="21%"><div align="center"><a href="ListLgacUser.php?orderby=5&mydesc=<?=$lasttimedesc?><?=$ecms_hashur['ehref']?>">登录时间</a></div></td>
    <td width="13%" height="25"><div align="center">操作</div></td>
  </tr>
  <?php
  //formhash
  $efh=heformhash_get('CheckLgacUser',1);
  
  while($r=$empire->fetch($sql))
  {
  	$classname='--';
	if($r['classid'])
	{
  		$cr=$empire->fetch1("select classname from {$dbtbpre}enewsuserclass where classid='".$r['classid']."'");
		$classname=$cr['classname'];
	}
	$gr=$empire->fetch1("select groupname from {$dbtbpre}enewsgroup where groupid='".$r['groupid']."'");
  	if($r['checked'])
  	{$zt="禁用";}
  	else
  	{$zt="开启";}
  	$lasttime='---';
  	if($r['lasttime'])
  	{
  		$lasttime=date("Y-m-d H:i:s",$r['lasttime']);
  	}
	//认证文件
	if($addur['ckffname'])
	{
		$usercertfile="<a href='UserCertFile.php?userid=".$r['userid'].$ecms_hashur['ehref']."' target='_blank' title='给帐号绑定登录认证文件，更安全'>已绑定</a>";
	}
	else
	{
		$usercertfile="<a href='UserCertFile.php?userid=".$r['userid'].$ecms_hashur['ehref']."' target='_blank' title='给帐号绑定登录认证文件，更安全'>未绑定</a>";
	}
	//证书
	$addur=$empire->fetch1("select certkey,ckffname,ckftime from {$dbtbpre}enewsuseradd where userid='".$r['userid']."'".do_dblimit_one());
	if($addur['certkey'])
	{
		$usercertkey="<a href='UserCertkey.php?userid=".$r['userid'].$ecms_hashur['ehref']."' target='_blank' title='给帐号绑定证书，更安全'>已绑定</a>";
	}
	else
	{
		$usercertkey="<a href='UserCertkey.php?userid=".$r['userid'].$ecms_hashur['ehref']."' target='_blank' title='给帐号绑定证书，更安全'>未绑定</a>";
	}
	//密码加密
	if($r['ckinfos'])
	{
		$userenpw="<a href='UserEnpassFile.php?userid=".$r['userid'].$ecms_hashur['ehref']."' target='_blank' title='给登录密码加密，更安全'>已启用</a>";
	}
	else
	{
		$userenpw="<a href='UserEnpassFile.php?userid=".$r['userid'].$ecms_hashur['ehref']."' target='_blank' title='给登录密码加密，更安全'>未启用</a>";
	}
	//一次性密码
	$onepassnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuseronepass where userid='".$r['userid']."'");
	$useronepass="<a href='ListUserOnePass.php?userid=".$r['userid'].$ecms_hashur['ehref']."' target='_blank' title='一次性密码'>[".$onepassnum."]</a>";
	//上次激活时间
	$lgactime='---';
  	if($r['lgactime'])
  	{
  		$lgactime=date("Y-m-d H:i:s",$r['lgactime']);
  	}
  ?>
  <tr bgcolor="ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"><div align="center"> 
        <?=$r['userid']?>
      </div></td>
    <td height="25" title="上次激活时间：<?=$lgactime?>"><div align="center"> 
        <?=$r['username']?>
      </div></td>
    <td> <div align="left">用户组：
        <?=$gr['groupname']?>
        <br>
        部门&nbsp;&nbsp;&nbsp;：
        <?=$classname?>
      </div></td>
    <td><div align="center"> 
        <?=$zt?>
      </div></td>
    <td><div align="left">证书：
        <?=$usercertkey?>
		<br>
		加密：
		<?=$userenpw?>
		<br>
		文件：
        <?=$usercertfile?>
		<br>
		一密：
		<?=$useronepass?>
    </div></td>
    <td><div align="center">
        <?=$r['loginnum']?>
		<br>
		<font color="#666666" title="一次性密码登录次数">一：
		<?=$r['onepassnum']?></font>
      </div></td>
    <td> 时间：
      <?=$lasttime?>
      <br>
      IP&nbsp;&nbsp;&nbsp;：
      <?=$r['lastip']?$r['lastip'].':'.$r['lastipport']:'---'?>
    </td>
    <td height="25"><div align="center">[<a href="ListLgacUser.php?enews=CheckLgacUser&userid=<?=$r['userid']?><?=$ecms_hashur['href'].$efh?>" onClick="return confirm('确认要激活登录？');">同意登录</a>]</div></td>
  </tr>
  <?php
  }
  ?>
  <tr bgcolor="ffffff"> 
    <td height="25" colspan="8"> 
      <?=$returnpage?>
    </td>
  </tr>
</table>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>