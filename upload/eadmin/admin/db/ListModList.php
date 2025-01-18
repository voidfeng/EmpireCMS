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
CheckLevel($logininid,$loginin,$classid,"table");
$url="<a href='ListModList.php".$ecms_hashur['whehref']."'>管理系统模型列表</a>";
$sql=$empire->query("select lid,lname,lpath,ldoup from {$dbtbpre}enewsmodlist order by lid");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理系统模型列表</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="50%" height="32">位置： 
      <?=$url?>    </td>
    <td><div align="right" class="emenubutton">
        <input type="button" name="Submit" value="增加系统模型列表" onclick="self.location.href='AddModList.php?enews=AddModList<?=$ecms_hashur['ehref']?>';">
		&nbsp;&nbsp;
		<input type="button" name="Submit" value="管理数据表" onclick="self.location.href='ListTable.php<?=$ecms_hashur['whehref']?>';">
		</div></td>
  </tr>
</table>
<br>
  
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="7%" height="25"><div align="center">ID</div></td>
    <td width="40%" height="25"><div align="center">名称</div></td>
    <td width="23%"><div align="center">目录</div></td>
    <td width="14%"><div align="center">更新文件</div></td>
    <td width="16%" height="25"><div align="center">操作</div></td>
  </tr>
  <?php
  //formhash
  $efh=heformhash_get('DelModList',1);
  
  while($r=$empire->fetch($sql))
  {
  ?>
  <tr bgcolor="#ffffff" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
    <td height="32"><div align="center"> 
        <?=$r['lid']?>
      </div></td>
    <td height="25"> 
      <?=$r['lname']?>
    </td>
    <td><div align="center"><?=$r['lpath']?></div></td>
    <td><div align="center"><?=$r['ldoup']==1?'更新':'不更新'?></div></td>
    <td height="25"><div align="center">[<a href="AddModList.php?enews=EditModList&lid=<?=$r['lid']?><?=$ecms_hashur['ehref']?>">修改</a>] &nbsp;
        [<a href="../ecmsmod.php?enews=DelModList&lid=<?=$r['lid']?><?=$ecms_hashur['href'].$efh?>" onclick="return confirm('确认要删除?');">删除</a>] 
      </div></td>
  </tr>
  <?php
	}
	?>
</table>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>
