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
CheckLevel($logininid,$loginin,$classid,"user");

//------------------------增加用户
function AddUser($username,$password,$repassword,$groupid,$adminclass,$checked,$styleid,$loginuserid,$loginusername){
	global $empire,$class_r,$dbtbpre,$public_r;
	$username=str_replace(',','',$username);
	$username=str_replace('|','',$username);
	$username=RepPostVar($username);
	$password=RepPostVar($password);
	if(!$username||!$password||!$repassword)
	{printerror("EmptyUsername","history.go(-1)");}
	if($password!=$repassword)
	{printerror("NotRepassword","history.go(-1)");}
	if(strlen($password)<8)
	{
		printerror("LessPassword","history.go(-1)");
	}
	//密码复杂度检测
	ePasswordCkChar_hck($password);
	//操作权限
	CheckLevel($loginuserid,$loginusername,$classid,"user");
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser where username='$username'".do_dblimit_cone());
	if($num)
	{printerror("ReUsername","history.go(-1)");}
	//登录用户名
	$tuser=str_replace(',','',$_POST['tuser']);
	$tuser=str_replace('|','',$tuser);
	$tuser=RepPostVar($tuser);
	if(!$tuser)
	{
		$tuser=$username;
	}
	$tusernum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser where tuser='$tuser'".do_dblimit_cone());
	if($tusernum)
	{printerror("ReUsername","history.go(-1)");}
	//管理目录
	$adminclass=eCheckEmptyArray($adminclass);
	$insert_class='';
	for($i=0;$i<count($adminclass);$i++)
	{
		$adminclass[$i]=(int)$adminclass[$i];
		//大栏目
		if(empty($class_r[$adminclass[$i]]['islast']))
		{
			if(empty($class_r[$adminclass[$i]]['sonclass'])||$class_r[$adminclass[$i]]['sonclass']=="|")
			{
				continue;
			}
			else
			{
				$andclass=substr($class_r[$adminclass[$i]]['sonclass'],1);
			}
			$insert_class.=$andclass;
		}
		else
		{
			$insert_class.=$adminclass[$i]."|";
		}
    }
	$insert_class="|".$insert_class;
	//参数
	$styleid=(int)$styleid;
	$groupid=(int)$groupid;
	$checked=(int)$checked;
	$filelevel=(int)$_POST['filelevel'];
	$classid=(int)$_POST['classid'];
	$rnd=make_password(30);
	$salt=make_password(EcmsRandInt(6,8));
	$salt2=make_password(EcmsRandInt(12,20));
	$password=DoEmpireCMSAdminPassword($password,$salt,$salt2);
	$truename=hRepPostStr($_POST['truename'],1);
	$email=hRepPostStr($_POST['email'],1);
	$openip=hRepPostStr($_POST['openip'],1);
	$wname=hRepPostStr($_POST['wname'],1);
	$tel=hRepPostStr($_POST['tel'],1);
	$wxno=hRepPostStr($_POST['wxno'],1);
	$qq=hRepPostStr($_POST['qq'],1);
	$edpasstime=time();
	$addtime=time();
	$addip=egetip();
	$addipport=egetipport();
	$userprikey=make_password(48);
	$insert_class=hRepPostStr2($insert_class);
	$sql=$empire->updatesql("insert into {$dbtbpre}enewsuser(username,password,rnd,groupid,adminclass,checked,styleid,filelevel,salt,loginnum,lasttime,lastip,truename,email,classid,addtime,addip,userprikey,salt2,lastipport,preipport,addipport,wname,tel,wxno,qq,tuser,onepassnum,edpasstime) values('$username','$password','$rnd','$groupid','$insert_class','$checked','$styleid','$filelevel','$salt',0,0,'','$truename','$email','$classid','$addtime','$addip','$userprikey','$salt2','$addipport','$addipport','$addipport','$wname','$tel','$wxno','$qq','$tuser',0,'$edpasstime');","ins");
	$userid=$empire->lastid($dbtbpre.'enewsuser','userid');
	//安全提问
	$equestion=(int)$_POST['equestion'];
	$eanswer=$_POST['eanswer'];
	if($equestion)
	{
		if(!$eanswer)
		{
			printerror('EmptyEAnswer','');
		}
		$eanswer=ReturnHLoginQuestionStr($userid,$username,$equestion,$eanswer);
	}
	else
	{
		$equestion=0;
		$eanswer='';
	}
	$empire->query("insert into {$dbtbpre}enewsuseradd(userid,equestion,eanswer,openip) values('$userid','$equestion','$eanswer','$openip');");
	if($sql)
	{
		$cache_enews='douserinfo';
		$cache_ecmstourl=urlencode('user/AddUser.php?enews=AddUser'.hReturnEcmsHashStrHref2(0));
		$cache_mess='AddUserSuccess';
		$cache_uid=$userid;
		$cache_url="../CreacjCache.php?enews=$cache_enews&uid=$cache_uid&ecmstourl=$cache_ecmstourl&mess=$cache_mess".hReturnEcmsHashStrHref2(0);
		//操作日志
		insert_dolog("userid=".$userid."<br>username=".$username);
		//printerror("AddUserSuccess","AddUser.php?enews=AddUser".hReturnEcmsHashStrHref2(0));
		echo'<meta http-equiv="refresh" content="0;url='.$cache_url.'">';
		db_close();
		$empire=null;
		exit();
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//------------------------修改用户
function EditUser($userid,$username,$password,$repassword,$groupid,$adminclass,$oldusername,$checked,$styleid,$loginuserid,$loginusername){
	global $empire,$class_r,$dbtbpre,$public_r;
	$userid=(int)$userid;
	$oldusername=RepPostVar($oldusername);
	$username=str_replace(',','',$username);
	$username=str_replace('|','',$username);
	$username=RepPostVar($username);
	$password=RepPostVar($password);
	$add='';
	if(!$userid||!$username)
	{printerror("EnterUsername","history.go(-1)");}
	//操作权限
	CheckLevel($loginuserid,$loginusername,$classid,"user");
	//修改用户名
	if($oldusername<>$username)
	{
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser where username='$username' and userid<>$userid".do_dblimit_cone());
		if($num)
		{printerror("ReUsername","history.go(-1)");}
		//修改信息
		//$nsql=$empire->query("update {$dbtbpre}enewsnews set username='$username' where username='$oldusername'");
		//修改日志
		$lsql=$empire->query("update {$dbtbpre}enewslog set username='$username' where username='$oldusername'");
		$lsql=$empire->query("update {$dbtbpre}enewsdolog set username='$username' where username='$oldusername'");
	}
	//登录用户名
	$tuser=str_replace(',','',$_POST['tuser']);
	$tuser=str_replace('|','',$tuser);
	$tuser=RepPostVar($tuser);
	if(!$tuser)
	{
		$tuser=$username;
	}
	$tusernum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser where tuser='$tuser' and userid<>$userid".do_dblimit_cone());
	if($tusernum)
	{printerror("ReUsername","history.go(-1)");}
	//修改密码
	if($password)
	{
		if($password!=$repassword)
		{printerror("NotRepassword","history.go(-1)");}
		if(strlen($password)<8)
		{
			printerror("LessPassword","history.go(-1)");
		}
		//密码复杂度检测
		ePasswordCkChar_hck($password);
		$edpasstime=time();
		$salt=make_password(EcmsRandInt(6,8));
		$salt2=make_password(EcmsRandInt(12,20));
		$password=DoEmpireCMSAdminPassword($password,$salt,$salt2);
		$add=",password='$password',salt='$salt',salt2='$salt2',edpasstime='$edpasstime'";
	}
	//管理目录
	$adminclass=eCheckEmptyArray($adminclass);
	$insert_class='';
	for($i=0;$i<count($adminclass);$i++)
	{
		$adminclass[$i]=(int)$adminclass[$i];
		//大栏目
		if(empty($class_r[$adminclass[$i]]['islast']))
		{
			if(empty($class_r[$adminclass[$i]]['sonclass'])||$class_r[$adminclass[$i]]['sonclass']=="|")
			{
				continue;
			}
			else
			{
				$andclass=substr($class_r[$adminclass[$i]]['sonclass'],1);
			}
			$insert_class.=$andclass;
		}
		else
		{
			$insert_class.=$adminclass[$i]."|";
		}
    }
	$insert_class="|".$insert_class;
	$styleid=(int)$styleid;
	$groupid=(int)$groupid;
	$checked=(int)$checked;
	$filelevel=(int)$_POST['filelevel'];
	$classid=(int)$_POST['classid'];
	$truename=hRepPostStr($_POST['truename'],1);
	$email=hRepPostStr($_POST['email'],1);
	$openip=hRepPostStr($_POST['openip'],1);
	$wname=hRepPostStr($_POST['wname'],1);
	$tel=hRepPostStr($_POST['tel'],1);
	$wxno=hRepPostStr($_POST['wxno'],1);
	$qq=hRepPostStr($_POST['qq'],1);
	$insert_class=hRepPostStr2($insert_class);
	$sql=$empire->query("update {$dbtbpre}enewsuser set username='$username',groupid='$groupid',adminclass='$insert_class',checked='$checked',styleid='$styleid',filelevel='$filelevel',truename='$truename',email='$email',classid='$classid',wname='$wname',tel='$tel',wxno='$wxno',qq='$qq',tuser='$tuser'".$add." where userid='$userid'");
	//安全提问
	$equestion=(int)$_POST['equestion'];
	$eanswer=$_POST['eanswer'];
	$uadd='';
	if($equestion)
	{
		if($equestion!=$_POST['oldequestion']&&!$eanswer)
		{
			printerror('EmptyEAnswer','');
		}
		if($eanswer)
		{
			$eanswer=ReturnHLoginQuestionStr($userid,$username,$equestion,$eanswer);
			$uadd=",eanswer='$eanswer'";
		}
	}
	else
	{
		$uadd=",eanswer=''";
	}
	$empire->query("update {$dbtbpre}enewsuseradd set equestion='$equestion',openip='$openip'".$uadd." where userid='$userid'");
	if($sql)
	{
		//操作日志
		insert_dolog("userid=".$userid."<br>username=".$username);
		if($_POST['oldadminclass']<>$insert_class)
		{
			DelFiletext('../../../c/ecachepub/eclassfc/ListEnews'.$userid.'.php');
			DelFiletext('../../../c/ecachepub/eclassfc/userclass'.$userid.'.js');
			//删除导航缓存
			$empire->query("delete from {$dbtbpre}enewsclassnavcache where navtype='userenews' and userid='$userid'");
			$cache_enews='douserinfo';
			$cache_ecmstourl=urlencode('user/ListUser.php'.hReturnEcmsHashStrHref2(1));
			$cache_mess='EditUserSuccess';
			$cache_uid=$userid;
			$cache_url="../CreacjCache.php?enews=$cache_enews&uid=$cache_uid&ecmstourl=$cache_ecmstourl&mess=$cache_mess".hReturnEcmsHashStrHref2(0);
			echo'<meta http-equiv="refresh" content="0;url='.$cache_url.'">';
			db_close();
			$empire=null;
			exit();
		}
		printerror("EditUserSuccess","ListUser.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//-----------------------删除用户
function DelUser($userid,$loginuserid,$loginusername){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	if(!$userid)
	{printerror("NotDelUserid","history.go(-1)");}
	//操作权限
	CheckLevel($loginuserid,$loginusername,$classid,"user");
	//验证是否最后一个管理员
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser");
	if($num<=1)
	{
		printerror("LastUserNotToDel","history.go(-1)");
	}
	$r=$empire->fetch1("select username from {$dbtbpre}enewsuser where userid='$userid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsuser where userid='$userid'");
	$sql1=$empire->query("delete from {$dbtbpre}enewsuseradd where userid='$userid'");
	if($sql)
	{
		DelFiletext('../../../c/ecachepub/eclassfc/ListEnews'.$userid.'.php');
		DelFiletext('../../../c/ecachepub/eclassfc/userclass'.$userid.'.js');
		//删除导航缓存
		$empire->query("delete from {$dbtbpre}enewsclassnavcache where navtype='userenews' and userid='$userid'");
		//操作日志
		insert_dolog("userid=".$userid."<br>username=".$r['username']);
		printerror("DelUserSuccess","ListUser.php".hReturnEcmsHashStrHref2(1));
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
	include('../../../e/data/dbcache/class.php');
}
//增加用户
if($enews=="AddUser")
{
	$username=$_POST['username'];
	$password=$_POST['password'];
	$repassword=$_POST['repassword'];
	$groupid=$_POST['groupid'];
	$adminclass=$_POST['adminclass'];
	$checked=$_POST['checked'];
	$styleid=$_POST['styleid'];
	AddUser($username,$password,$repassword,$groupid,$adminclass,$checked,$styleid,$logininid,$loginin);
}
//修改用户
elseif($enews=="EditUser")
{
	$userid=$_POST['userid'];
	$username=$_POST['username'];
	$password=$_POST['password'];
	$repassword=$_POST['repassword'];
	$groupid=$_POST['groupid'];
	$adminclass=$_POST['adminclass'];
	$oldusername=$_POST['oldusername'];
	$checked=$_POST['checked'];
	$styleid=$_POST['styleid'];
	EditUser($userid,$username,$password,$repassword,$groupid,$adminclass,$oldusername,$checked,$styleid,$logininid,$loginin);
}
//删除用户
elseif($enews=="DelUser")
{
	$userid=$_GET['userid'];
	DelUser($userid,$logininid,$loginin);
}

$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$url="<a href=ListUser.php".$ecms_hashur['whehref'].">管理用户</a>";
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
elseif($orderby==4)//登录次数
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
$query="select * from {$dbtbpre}enewsuser";
$totalquery="select count(*) as total from {$dbtbpre}enewsuser";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by ".$order."".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理用户</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="50%" height="32">位置： 
      <?=$url?>    </td>
    <td><div align="right" class="emenubutton">
        <input type="button" name="Submit5" value="增加用户" onClick="self.location.href='AddUser.php?enews=AddUser<?=$ecms_hashur['ehref']?>';">&nbsp;&nbsp;
		<input type="button" name="Submit52" value="管理部门" onClick="self.location.href='UserClass.php<?=$ecms_hashur['whehref']?>';">
      </div></td>
  </tr>
</table>
<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="4%" height="25"><div align="center"><a href="ListUser.php?orderby=0&mydesc=<?=$useriddesc?><?=$ecms_hashur['ehref']?>">ID</a></div></td>
    <td width="22%" height="25"><div align="center"><a href="ListUser.php?orderby=1&mydesc=<?=$usernamedesc?><?=$ecms_hashur['ehref']?>">用户名</a></div></td>
    <td width="17%"><div align="center"><a href="ListUser.php?orderby=2&mydesc=<?=$groupiddesc?><?=$ecms_hashur['ehref']?>">等级</a></div></td>
    <td width="6%"><div align="center"><a href="ListUser.php?orderby=3&mydesc=<?=$checkeddesc?><?=$ecms_hashur['ehref']?>">状态</a></div></td>
    <td width="9%"><div align="center">绑定认证</div></td>
    <td width="8%"><div align="center"><a href="ListUser.php?orderby=4&mydesc=<?=$loginnumdesc?><?=$ecms_hashur['ehref']?>">登录次数</a></div></td>
    <td width="21%"><div align="center"><a href="ListUser.php?orderby=5&mydesc=<?=$lasttimedesc?><?=$ecms_hashur['ehref']?>">最后登录</a></div></td>
    <td width="13%" height="25"><div align="center">操作</div></td>
  </tr>
  <?php
  //formhash
  $efh=heformhash_get('DelUser',1);
  $efh1=heformhash_get('MustDoLoginout',1);
  
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
	if($r['isot'])
  	{$ztisot="未退出";}
  	else
  	{$ztisot="退出";}
  	$lasttime='---';
  	if($r['lasttime'])
  	{
  		$lasttime=date("Y-m-d H:i:s",$r['lasttime']);
  	}
	//认证文件
	$usercertfile='<a href="#ecms" title="此功能需要商业授权版才支持">--</a>';
	//证书
	$usercertkey='<a href="#ecms" title="此功能需要商业授权版才支持">--</a>';
	//密码加密
	$userenpw='<a href="#ecms" title="此功能需要商业授权版才支持">--</a>';
	//一次性密码
	$onepassnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuseronepass where userid='".$r['userid']."'");
	$useronepass="<a href='ListUserOnePass.php?userid=".$r['userid'].$ecms_hashur['ehref']."' target='_blank' title='一次性密码'>[".$onepassnum."]</a>";
  ?>
  <tr bgcolor="ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
    <td height="25"><div align="center"> 
        <?=$r['userid']?>
      </div></td>
    <td height="25"><div align="center"> 
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
        <br>
        [<a href="../enews.php?enews=MustDoLoginout&userid=<?=$r['userid']?><?=$ecms_hashur['href'].$efh1?>" onClick="return confirm('确认要强制退出该用户？');"><?=$ztisot?></a>]
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
    <td height="25"><div align="center">[<a href="AddUser.php?enews=EditUser&userid=<?=$r['userid']?><?=$ecms_hashur['ehref']?>">修改</a>] 
        [<a href="ListUser.php?enews=DelUser&userid=<?=$r['userid']?><?=$ecms_hashur['href'].$efh?>" onClick="return confirm('确认要删除？');">删除</a>]</div></td>
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