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


//处理一次性密码字段变量
function DoPostUserOnePassVar($add){
	$edb_ir['id']=(int)$add['id'];
	$edb_ir['userid']=(int)$add['userid'];
	$edb_ir['pno']=RepPostVar($add['pno']);
	$edb_ir['password']=RepPostVar($add['password']);
	$edb_ir['isopen']=(int)$add['isopen'];
	$edb_ir['isopen']=$edb_ir['isopen']==1?1:0;
	$edb_ir['repassword']=$add['repassword'];
	return $edb_ir;
}

//------------------------增加一次性密码
function AddUserOnePass($add,$loginuserid,$loginusername){
	global $empire,$class_r,$dbtbpre,$public_r;
	$edb_ir=DoPostUserOnePassVar($add);
	if(!$edb_ir['userid']||!$edb_ir['pno']||!$edb_ir['password']||!$edb_ir['repassword'])
	{
		printerror("EmptyUserOnePass","history.go(-1)");
    }
	//操作权限
	if($loginuserid==$edb_ir['userid'])
	{
	}
	else
	{
		CheckLevel($loginuserid,$loginusername,$classid,"user");
	}
	$addur=$empire->fetch1("select userid,username from {$dbtbpre}enewsuser where userid='".$edb_ir['userid']."'");
	if(!$addur['userid'])
	{
		printerror('ErrorUrl','');
	}

	if($edb_ir['password']!=$edb_ir['repassword'])
	{
		printerror("NotRepassword","history.go(-1)");
	}
	if(strlen($edb_ir['password'])<8)
	{
		printerror("LessPassword","history.go(-1)");
	}
	//密码复杂度检测
	ePasswordCkChar_hck($edb_ir['password']);
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuseronepass where userid='".$edb_ir['userid']."' and pno='".$edb_ir['pno']."'".do_dblimit_cone());
	if($num)
	{
		printerror("ReUserOnePassPno","history.go(-1)");
	}
	//参数
	$password=$edb_ir['password'];
	$salt=make_password(EcmsRandInt(6,20));
	$salt2=make_password(EcmsRandInt(12,20));
	$password=DoEmpireCMSAdminPassword($password,$salt,$salt2);
	$addtime=time();
	$adduserid=(int)$loginuserid;
	
	$sql=$empire->updatesql("insert into {$dbtbpre}enewsuseronepass(userid,pno,password,salt,salt2,addtime,adduserid,edittime,edituserid,isopen) values('".$edb_ir['userid']."','".$edb_ir['pno']."','".$password."','".$salt."','".$salt2."','".$addtime."','".$adduserid."',0,0,'".$edb_ir['isopen']."');","ins");
	$id=$empire->lastid($dbtbpre.'enewsuseronepass','id');
	if($sql)
	{
		//操作日志
	    insert_dolog("userid=".$edb_ir['userid']."<br>id=".$id."<br>pno=".$edb_ir['pno']);
		printerror("AddUserOnePassSuccess","ListUserOnePass.php?userid=".$edb_ir['userid'].hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//------------------------修改一次性密码
function EditUserOnePass($add,$loginuserid,$loginusername){
	global $empire,$class_r,$dbtbpre,$public_r;
	$edb_ir=DoPostUserOnePassVar($add);
	if(!$edb_ir['id']||!$edb_ir['userid']||!$edb_ir['pno'])
	{
		printerror("EmptyUserOnePass","history.go(-1)");
    }
	//操作权限
	if($loginuserid==$edb_ir['userid'])
	{
	}
	else
	{
		CheckLevel($loginuserid,$loginusername,$classid,"user");
	}
	$addur=$empire->fetch1("select userid,username from {$dbtbpre}enewsuser where userid='".$edb_ir['userid']."'");
	if(!$addur['userid'])
	{
		printerror('ErrorUrl','');
	}
	//原
	$r=$empire->fetch1("select * from {$dbtbpre}enewsuseronepass where id='".$edb_ir['id']."' and userid='".$edb_ir['userid']."'");
	if(!$r['userid'])
	{
		printerror('ErrorUrl','');
	}
	//修改密码
	$addupdate='';
	if($edb_ir['password'])
	{
		if($edb_ir['password']!=$edb_ir['repassword'])
		{
			printerror("NotRepassword","history.go(-1)");
		}
		if(strlen($edb_ir['password'])<8)
		{
			printerror("LessPassword","history.go(-1)");
		}
		//密码复杂度检测
		ePasswordCkChar_hck($edb_ir['password']);
		$password=$edb_ir['password'];
		$salt=make_password(EcmsRandInt(6,20));
		$salt2=make_password(EcmsRandInt(12,20));
		$password=DoEmpireCMSAdminPassword($password,$salt,$salt2);
		$addupdate.=",password='".$password."',salt='".$salt."',salt2='".$salt2."'";
	}
	//修改识别码
	if($r['pno']<>$edb_ir['pno'])
	{
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuseronepass where userid='".$edb_ir['userid']."' and pno='".$edb_ir['pno']."' and id<>".$edb_ir['id']."".do_dblimit_cone());
		if($num)
		{printerror("ReUserOnePassPno","history.go(-1)");}
	}
	//参数
	$edittime=time();
	$edituserid=(int)$loginuserid;
	
	$sql=$empire->query("update {$dbtbpre}enewsuseronepass set pno='".$edb_ir['pno']."',edittime='".$edittime."',edituserid='".$edituserid."',isopen='".$edb_ir['isopen']."'".$addupdate." where id='".$edb_ir['id']."' and userid='".$edb_ir['userid']."'");
	if($sql)
	{
		//操作日志
	    insert_dolog("userid=".$edb_ir['userid']."<br>id=".$edb_ir['id']."<br>pno=".$edb_ir['pno']);
		printerror("EditUserOnePassSuccess","ListUserOnePass.php?userid=".$edb_ir['userid'].hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//-----------------------删除一次性密码
function DelUserOnePass($add,$loginuserid,$loginusername){
	global $empire,$dbtbpre;
	$edb_ir['id']=(int)$add['id'];
	$edb_ir['userid']=(int)$add['userid'];
	if(!$edb_ir['id']||!$edb_ir['userid'])
	{
		printerror("NotDelUserOnePassid","history.go(-1)");
    }
	//操作权限
	if($loginuserid==$edb_ir['userid'])
	{
	}
	else
	{
		CheckLevel($loginuserid,$loginusername,$classid,"user");
	}
	$addur=$empire->fetch1("select userid,username from {$dbtbpre}enewsuser where userid='".$edb_ir['userid']."'");
	if(!$addur['userid'])
	{
		printerror('ErrorUrl','');
	}
	//原
	$r=$empire->fetch1("select * from {$dbtbpre}enewsuseronepass where id='".$edb_ir['id']."' and userid='".$edb_ir['userid']."'");
	if(!$r['userid'])
	{
		printerror('ErrorUrl','');
	}
	$sql=$empire->query("delete from {$dbtbpre}enewsuseronepass where id='".$edb_ir['id']."' and userid='".$edb_ir['userid']."'");
	
	if($sql)
	{
		//操作日志
	    insert_dolog("userid=".$edb_ir['userid']."<br>id=".$edb_ir['id']."<br>pno=".$r['pno']);
		printerror("DelUserOnePassSuccess","ListUserOnePass.php?userid=".$edb_ir['userid'].hReturnEcmsHashStrHref2(0));
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
//增加一次性密码
if($enews=="AddUserOnePass")
{
	AddUserOnePass($_POST,$logininid,$loginin);
}
elseif($enews=="EditUserOnePass")//修改一次性密码
{
	EditUserOnePass($_POST,$logininid,$loginin);
}
elseif($enews=="DelUserOnePass")//删除一次性密码
{
	DelUserOnePass($_GET,$logininid,$loginin);
}


//验证权限
$userid=(int)$_GET['userid'];
if(!$userid)
{
	printerror('ErrorUrl','');
}
if($logininid==$userid)
{
}
else
{
	CheckLevel($logininid,$loginin,$classid,"user");
}

$addur=$empire->fetch1("select userid,username from {$dbtbpre}enewsuser where userid='".$userid."'");
if(!$addur['userid'])
{
	printerror('ErrorUrl','');
}

$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=30;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$url="";
$search="&userid=$userid".$ecms_hashur['ehref'];
$query="select * from {$dbtbpre}enewsuseronepass where userid='".$userid."'";
$totalquery="select count(*) as total from {$dbtbpre}enewsuseronepass where userid='".$userid."'";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by id desc".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理一次性密码</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="79%" height="32">位置： 
      <a href="ListUser.php<?=$ecms_hashur['whehref']?>">管理用户</a> &gt; 用户：<?=$addur['username']?> &gt; <a href="ListUserOnePass.php?userid=<?=$userid?><?=$ecms_hashur['ehref']?>">管理一次性密码</a>    (一次性密码登录功能状态：<b><?=$ecms_config['esafe']['loginonepass']?'开启':'关闭'?></b>)</td>
    <td width="21%"><div align="right" class="emenubutton">
    <input type="button" name="Submit5" value="增加一次性密码" onClick="self.location.href='AddUserOnePass.php?enews=AddUserOnePass&userid=<?=$userid?><?=$ecms_hashur['ehref']?>';"></div></td>
  </tr>
</table>
<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="8%" height="25"><div align="center">ID</div></td>
    <td width="28%" height="25"><div align="center">识别码</div></td>
    <td width="7%"><div align="center">启用</div></td>
    <td width="22%"><div align="center">增加时间</div></td>
    <td width="22%"><div align="center">最后修改时间</div></td>
    <td width="13%" height="25"><div align="center">操作</div></td>
  </tr>
  <?php
  //formhash
  $efh=heformhash_get('DelUserOnePass',1);
  
  while($r=$empire->fetch($sql))
  {
  	//增加
	$saddur=$empire->fetch1("select userid,username from {$dbtbpre}enewsuser where userid='".$r['adduserid']."'");
	$sadduser=$saddur['username'].' (用户ID：'.$r['adduserid'].')';
  	//修改
  	$sedituser='---';
	if($r['edituserid'])
	{
  		$seditur=$empire->fetch1("select userid,username from {$dbtbpre}enewsuser where userid='".$r['edituserid']."'");
		$sedituser=$seditur['username'].' (用户ID：'.$r['edituserid'].')';
	}
  ?>
  <tr bgcolor="ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"><div align="center"> 
        <?=$r['id']?>
      </div></td>
    <td height="25"><div align="center"> 
        <?=$r['pno']?>
      </div></td>
    <td><div align="center"> 
        <?=$r['isopen']?'启用':'<font color="#666666">禁用</font>'?>
      </div></td>
    <td>
      时间：<?=date("Y-m-d H:i:s",$r['addtime'])?>
      <br>
      用户：<?=$sadduser?></td>
    <td> 时间：<?=$r['edittime']?date("Y-m-d H:i:s",$r['edittime']):'---'?>
      <br>
      用户：<?=$sedituser?>    </td>
    <td height="25"><div align="center">[<a href="AddUserOnePass.php?enews=EditUserOnePass&userid=<?=$r['userid']?>&id=<?=$r['id']?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="ListUserOnePass.php?enews=DelUserOnePass&userid=<?=$r['userid']?>&id=<?=$r['id']?><?=$ecms_hashur['href'].$efh?>" onClick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?php
  }
  ?>
  <tr bgcolor="ffffff"> 
    <td height="25" colspan="6"> 
      <?=$returnpage?>    </td>
  </tr>
</table>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>