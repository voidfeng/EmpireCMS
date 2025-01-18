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
//参数
$returnform=RepPostVar($_GET['returnform']);
eCheckStrType(5,$returnform,1);
//基目录
$openpath="../../../d/efilepub/eplface";
$hand=@opendir($openpath);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>选择文件</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="56%" height="32">位置：<a href="ChangePlfaceFile.php<?=$ecms_hashur['whehref']?>">选择文件</a></td>
    <td width="44%"><div align="right"> </div></td>
  </tr>
</table>
<form name="chfile" method="post" action="../enews.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <?=$ecms_hashur['eform']?>
    <tr class="header"> 
      <td height="25">文件名 (当前目录：<strong>/d/efilepub/eplface/</strong>)</td>
    </tr>
    <?php
	while($file=@readdir($hand))
	{
		$truefile=$file;
		if($file=="."||$file=="..")
		{
			continue;
		}
		//目录
		if(is_dir($openpath."/".$file))
		{
			continue;
		}
		$filetype=GetFiletype($file);
		if(!strstr($ecms_config['sets']['tranpicturetype'],','.$filetype.','))
		{
			continue;
		}
	 ?>
    <tr> 
      <td width="88%" height="25"><a href="#ecms" onclick="<?=$returnform?>='<?=$truefile?>';window.close();" title="选择"> 
        <img src="../../../d/efilepub/eplface/<?=$truefile?>" border=0>&nbsp;<?=$truefile?>
        </a></td>
    </tr>
    <?php
	}
	@closedir($hand);
	?>
  </table>
</form>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>