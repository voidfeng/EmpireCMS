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
CheckLevel($logininid,$loginin,$classid,"loadcj");
$from=(int)$_GET['from'];
if($from)
{
	$listclasslink="ListPageInfoClass.php";
}
else
{
	$listclasslink="ListInfoClass.php";
}
$url="<a href=../".$listclasslink.$ecms_hashur['whehref'].">管理采集</a>&nbsp;>&nbsp;导入采集规则";
//--------------------操作的栏目
$fcfile="../../../c/ecachepub/eclassfc/ListEnews.php";
$do_class="<script src=../../../c/ecachepub/eclassfc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$do_class=ShowClass_AddClass("","n",0,"|-",0,0);}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>导入采集规则</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32">位置：<?=$url?></td>
  </tr>
</table>
<form action="../ecmscj.php" method="post" enctype="multipart/form-data" name="form1" onsubmit="return confirm('确认要导入？');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25"><div align="center">导入采集规则 
          <input name="enews" type="hidden" id="enews" value="LoadInCj">
		  <?=$ecms_hashur['form']?>
		  <?php echo heformhash_get('LoadInCj'); ?>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><table width="650" border="0" align="center" cellpadding="3" cellspacing="1">
          <tr> 
            <td width="29%"><div align="right">选择采集入库的栏目：</div></td>
            <td width="71%" height="27"><select name="classid" id="classid">
            <option value='0'>选择栏目</option>
            <?=$do_class?>
          </select> 
              <font color="#666666">(要选择终极栏目)</font></td>
          </tr>
          <tr> 
            <td height="27"> <div align="right">导入采集规则文件：</div></td>
            <td height="27"><input type="file" name="file">
              <font color="#666666">(*.cj)</font> </td>
          </tr>
        </table></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><div align="center">
          <input type="submit" name="Submit" value="马上导入">
          &nbsp;&nbsp;
          <input type="reset" name="Submit2" value="重置">
		  <input type="hidden" name="from" value="<?=$from?>">
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