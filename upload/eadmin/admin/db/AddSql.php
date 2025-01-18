<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
ehCheckCloseMods('dosql');
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
CheckLevel($logininid,$loginin,$classid,"execsql");

$enews=RepPostStr($_GET['enews'],1);
if(empty($enews))
{
	$enews='AddSql';
}
eCheckStrType(4,$enews,1);
if($enews=='EditSql')
{
	$enews='EditSql';
}
else
{
	$enews='AddSql';
}
//formhash
$efh=heformhash_get($enews);

$id=0;
$url="<a href='ListSql.php".$ecms_hashur['whehref']."'>管理SQL语句</a>&nbsp;>&nbsp;增加SQL语句";
$postword='增加SQL语句';
if($enews=='EditSql')
{
	$id=intval($_GET['id']);
	$r=$empire->fetch1("select * from {$dbtbpre}enewssql where id='$id'");
	$url="<a href='ListSql.php".$ecms_hashur['whehref']."'>管理SQL语句</a>&nbsp;>&nbsp;修改SQL语句: <b>".$r['sqlname']."</b>";
	$postword='修改SQL语句';
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$postword?></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32">位置：<?=$url?></td>
  </tr>
</table>

<form action="DoSql.php" method="POST" name="sqlform">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25"><div align="center"><?=$postword?></div></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"><div align="center">(多条语句请用&quot;回车&quot;格开,每条语句以&quot;;&quot;结束，数据表前缀可用：“[!db.pre!]&quot;表示)</div></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"><div align="center"> 
          <textarea name="sqltext" cols="90" rows="12" id="sqltext"><?=ehtmlspecialchars($r['sqltext'])?></textarea>
        </div></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"><div align="center">SQL名称： 
          <input name="sqlname" type="text" id="sqlname" value="<?=$r['sqlname']?>">
          <input type="submit" name="Submit3" value="保存">
          &nbsp;<input type="reset" name="Submit2" value="重置">
          <input name="enews" type="hidden" id="enews" value="<?=$enews?>">
          <input name="id" type="hidden" id="id" value="<?=$id?>">
        </div></td>
    </tr>
  </table>
</form>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>