<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
require '../../../e/data/'.LoadLang("pub/fun.php");
require("../../../e/data/dbcache/class.php");
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

//参数
$emptykeyboard=0;	//关键字是否必填，0为不限、1为必填
$expstr=',';
$tbname=RepPostVar($_GET['tbname']);
$modid=(int)$_GET['modid'];
$form=RepPostVar($_GET['form']);
$field=RepPostVar($_GET['field']);
$fdivid=RepPostVar($_GET['fdivid']);
$fchids=RepPostVar($_GET['fchids']);
$classid=(int)$_GET['classid'];
$id=(int)$_GET['id'];
$enews=ehtmlspecialchars($_GET['enews']);
//验证
if(!eInfoHaveTable($tbname,0))
{
	printerror("ErrorUrl","history.go(-1)");
}
/*
if(!eInfoHaveModid($modid,0))
{
	printerror("ErrorUrl","history.go(-1)");
}
*/
if(!eCkIdsListStr($fchids,$expstr,0))
{
	printerror("ErrorUrl","history.go(-1)");
}

eCheckStrType(4,$tbname,1);
eCheckStrType(4,$enews,1);
eCheckStrType(5,$form,1);
eCheckStrType(5,$field,1);
eCheckStrType(5,$fdivid,1);
//参数
$urladdcs="tbname=$tbname&modid=$modid&classid=$classid&id=$id&enews=$enews&form=$form&field=$field&fdivid=$fdivid&fchids=$fchids".$ecms_hashur['ehref'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>选择信息</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function eUpdateInfoFChids(){
	<?php
	if($fdivid)
	{
	?>
	opener.document.getElementById("<?=$fdivid?>").value=document.chmoreinfoform.treturnfchids.value;
	<?php
	}
	else
	{
	?>
	opener.document.<?=$form?>.<?=$field?>.value=document.chmoreinfoform.treturnfchids.value;
	<?php
	}
	?>
	window.close();
}
function eCheckSearchForm(obj){
	<?php
	if($emptykeyboard)
	{
	?>
	if(obj.keyboard.value=='')
	{
		alert('搜索关键字不能为空');
		obj.keyboard.focus();
		return false;
	}
	<?php
	}
	?>
	document.searchminfoform.classid.value=document.getElementById('esnav_classid').value;
	return true;
}
</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr> 
    <td height="25" colspan="2" class="header">
      选择字段 <b><?=$field?></b> 的信息列表 </td>
  </tr>
  <tr> 
    <td height="25" valign="top" bgcolor="#FFFFFF">
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
        <form name="chmoreinfoform" id="chmoreinfoform" method="post" action="">
          <tr> 
            <td width="80%" height="25"><strong>已选信息</strong></td>
            <td width="20%">&nbsp;</td>
          </tr>
          <tr> 
            <td height="380" colspan="2" valign="top" bgcolor="#FFFFFF"><IFRAME frameBorder="0" id="showminfopage" name="showminfopage" scrolling="yes" src="ChMoreInfoShow.php?<?=$urladdcs?>" style="HEIGHT:100%;VISIBILITY:inherit;WIDTH:100%;Z-INDEX:1"></IFRAME></td>
          </tr>
          <tr> 
            <td height="25" colspan="2" bgcolor="#FFFFFF"><div align="center"> 
                <input type="button" name="Submit2" value=" 确 定 " onclick="eUpdateInfoFChids();">
                &nbsp;&nbsp;&nbsp;
                <input type="button" name="Submit3" value="取消" onclick="window.close();">
                <input name="treturnfchids" type="hidden" id="treturnfchids">
              </div></td>
          </tr>
        </form>
      </table> </td>
    <td width="60%" valign="top" bgcolor="#FFFFFF">
        <table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
		<form action="ChMoreInfoSearch.php" method="GET" name="searchminfoform" target="searchminfopage" id="searchminfoform" onsubmit="return eCheckSearchForm(document.searchminfoform);">
		<?=$ecms_hashur['eform']?>
          <tr> 
            <td height="25">查询： 
              <input name="keyboard" type="text" id="keyboard" value="">
              <select name="show" id="show">
                <option value="1" selected>标题</option>
                <option value="2">关键字</option>
                <option value="3">ID</option>
              </select><span id="listfileclassnav"></span>
              <input type="submit" name="Submit" value="搜索">
              <input name="sear" type="hidden" id="sear" value="1">
			  
              <input name="returnfchids" type="hidden" id="returnfchids">
			  <input name="tbname" type="hidden" id="tbname" value="<?=$tbname?>">
			  <input name="modid" type="hidden" id="modid" value="<?=$modid?>">
              <input name="pclassid" type="hidden" id="pclassid" value="<?=$classid?>">
              <input name="pid" type="hidden" id="pid" value="<?=$id?>">
			  <input name="enews" type="hidden" id="enews" value="<?=$enews?>">
			  <input name="form" type="hidden" id="form" value="<?=$form?>">
			  <input name="field" type="hidden" id="field" value="<?=$field?>">
			  <input type="hidden" name="classid" id="classid" value="0">
			  </td>
          </tr>
          <tr> 
            <td height="405" valign="top" bgcolor="#FFFFFF"> 
              <IFRAME frameBorder="0" id="searchminfopage" name="searchminfopage" scrolling="yes" src="ChMoreInfoSearch.php<?=$ecms_hashur['whehref']?>" style="HEIGHT:100%;VISIBILITY:inherit;WIDTH:100%;Z-INDEX:1"></IFRAME></td>
          </tr>
		  </form>
        </table>
      </td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="25"><div align="center"><font color="#666666">说明：搜索多个关键字可以用空格隔开。</font></div></td>
  </tr>
</table>
<br>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="../ShowClassNav.php?ecms=5&classid=<?=$classid?><?=$ecms_hashur['ehref']?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
</body>
</html>
<?php
db_close();
$empire=null;
?>
