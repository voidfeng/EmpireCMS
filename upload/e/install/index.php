<?php
error_reporting(0);

@set_time_limit(1000);

define('InEmpireCMS',TRUE);
define('InEmpireCMSIns',TRUE);
define('ECMS_PATH',substr(dirname(__FILE__),0,-9));
define('MAGIC_QUOTES_GPC',function_exists('ini_get')&&ini_get('magic_quotes_gpc'));
define('STR_IREPLACE',function_exists('str_ireplace'));

@header('Content-Type: text/html; charset=utf-8');

if(file_exists("../../c/einstall/install.off"))
{
	echo"《帝国网站管理系统》安装程序已锁定。如果要重新安装，请删除<b>/c/einstall/install.off</b>文件！";
	exit();
}

$ecms_config=array();
$ecms_config['db']['showerror']=1;
$link='';
$empire='';
$dbtbpre='';

//导入文件
include('data/fun.php');
include('../class/EmpireCMS_version.php');

//安装密码
$ins_password='';
$ins_pwvar='';
$ins_insretime=0;
include('ins_config.php');
eins_CkInsPass();


//------ 参数开始 ------

$char_r=array();
$char_r=eins_InstallReturnDbChar();
$version="8.0,1735574400";
$dbchar=$char_r['dbchar'];
$setchar=$char_r['setchar'];
$headerchar=$char_r['headerchar'];
$eins_candefdata=0;
$eins_usepgsql=0;

//------ 参数结束 ------

$enews=$_GET['enews'];
if(empty($enews))
{
	$enews=$_POST['enews'];
}
//测试采集
if($enews=="TestCj")
{
	echo"<title>TEST</title>";
	eins_TestCj();
}
$ok=$_GET['ok'];
if(empty($ok))
{
	$ok=$_POST['ok'];
}
$ok=(int)$ok;
//步骤
$f=$_GET['f'];
if(empty($f))
{
	$f=$_POST['f'];
}
if(empty($f))
{
	$f=1;
}
$f=(int)$f;
//数据库类型
$echdbtype=$_GET['echdbtype'];
if(empty($echdbtype))
{
	$echdbtype=$_POST['echdbtype'];
}
if(empty($echdbtype))
{
	$echdbtype=0;
}
$echdbtype=(int)$echdbtype;
$eusedbtypename=$echdbtype>2?'PostgreSQL':'MySQL';
//数据库操作
if($ok)
{
	if($enews=='setdb'||$enews=='systb'||$enews=='systbdata'||$enews=='modtb'||$enews=='modtbdata'||$enews=='templatetb'||$enews=='templatetbdata'||$enews=='defaultdata'||$enews=='firstadmin')
	{
		if($enews!='setdb')
		{
			@include("../config/config.php");
			$ecms_config['db']['showerror']=1;
		}
		if($echdbtype>2)//pgsql
		{
			if(!function_exists('pg_connect'))
			{
				echo'PostgreSQL is close!';
				exit();
			}
			if($eins_usepgsql!=1)
			{
				echo'此项需商业授权版才支持';
				exit();
			}
			include('../class/dbpg/db_pgsql.php');
			include('../class/dbpg/mytopgsqltb.php');
		}
		else//mysql
		{
			if(function_exists('mysql_connect')||function_exists('mysqli_connect'))
			{}
			else
			{
				echo'MySQL is close!';
				exit();
			}
			if(function_exists('mysql_connect'))
			{
				include('../class/db/db_mysql.php');
			}
			else
			{
				include('../class/db/db_mysqli.php');
			}
		}
	}
}
//处理
if($enews=="setdb"&&$ok)
{
	eins_SetDb($_POST);
}
elseif($enews=="systb"&&$ok)
{
	eins_InstallTb($_GET);
}
elseif($enews=="systbdata"&&$ok)
{
	eins_InstallTbData($_GET);
}
elseif($enews=="modtb"&&$ok)
{
	eins_InstallModTb($_GET);
}
elseif($enews=="modtbdata"&&$ok)
{
	eins_InstallModTbData($_GET);
}
elseif($enews=="templatetb"&&$ok)
{
	eins_InstallTemplateTb($_GET);
}
elseif($enews=="templatetbdata"&&$ok)
{
	eins_InstallTemplateTbData($_GET);
}
elseif($enews=="defaultdata"&&$ok)
{
	eins_InstallDefaultData($_GET);
}
elseif($enews=="firstadmin"&&$ok)
{
	eins_FirstAdmin($_POST);
}
else
{}

$shorttag=ini_get('short_open_tag');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>帝国网站管理系统安装程序 - Powered by EmpireCMS</title>

<link href="images/css.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#C9F1FF" leftmargin="0" topmargin="0">
<?php
if(!$shorttag)
{
?>
<br>
<br><br>
<table width="500" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr> 
    <td height="25" class="header"><div align="center"><strong><font color="#FFFFFF">错误提示</font></strong></div></td>
  </tr>
  <tr> 
    <td height="25" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr>
          <td height="25">您的PHP配置文件php.ini配置有问题，请按下面操作即可解决：</td>
        </tr>
        <tr>
          <td height="25">1、修改php.ini，将：short_open_tag 设为 On</td>
        </tr>
        <tr>
          <td height="25">2、修改后重启apache/iis方能生效。</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php
	echo"</body></html>";
	exit();
}
?>
<table width="776" height="100%" border="0" align="center" cellpadding="6" cellspacing="0" bgcolor="#FFFFFF">
  <tr> 
    <td height="56" colspan="2" background="images/topbg.gif"> 
      <div align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="80%"><div align="center"><img src="images/installsay.gif" width="500" height="50"></div></td>
            <td width="20%" valign="bottom"><font color="#FFFFFF">最后更新: <?php echo EmpireCMS_LASTTIME;?></font></td>
          </tr>
        </table>
        
      </div></td>
  </tr>
  <tr> 
    <td width="21%" rowspan="3" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td><div align="center"><a href="http://www.phome.net" target="_blank"><img src="images/logo.gif" width="170" height="72" border="0"></a></div></td>
        </tr>
      </table>
      <br> 
	  <table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2"> <div align="left"><strong><font color="#FFFFFF">版权信息</font></strong></div></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td width="34%" height="25">软件名称</td>
          <td width="66%" height="25">帝国网站管理系统</td>
        </tr>
        <tr bgcolor="#FFFFFF">
          <td height="25">英文名称</td>
          <td height="25">EmpireCMS</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td height="25">程序版本</td>
          <td height="25">Version 8.0 </td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td height="25">开发团队</td>
          <td height="25">帝国软件开发团队</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td height="25">公司名称</td>
          <td height="25">漳州市芗城帝兴软件开发有限公司</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td height="25">官方网站</td>
          <td height="25"><a href="http://www.PHome.Net" target="_blank">www.PHome.Net</a></td>
        </tr>
      </table>
      <br> 
	  <table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25"><strong><font color="#FFFFFF">安装进程</font></strong></td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td><img src="images/noadd.gif" width="15" height="15">&nbsp;<font color="<?php echo $f==1?'#FF0000':'#000000';?>">阅读用户使用条款</font></td>
              </tr>
              <tr> 
                <td><img src="images/noadd.gif" width="15" height="15">&nbsp;<font color="<?php echo $f==2?'#FF0000':'#000000';?>">检测运行环境</font></td>
              </tr>
              <tr> 
                <td><img src="images/noadd.gif" width="15" height="15">&nbsp;<font color="<?php echo $f==3?'#FF0000':'#000000';?>">设置目录权限</font></td>
              </tr>
			  <tr> 
                <td><img src="images/noadd.gif" width="15" height="15">&nbsp;<font color="<?php echo $f==4?'#FF0000':'#000000';?>">选择数据库类型</font></td>
              </tr>
              <tr> 
                <td><img src="images/noadd.gif" width="15" height="15">&nbsp;<font color="<?php echo $f==5?'#FF0000':'#000000';?>">配置数据库</font></td>
              </tr>
              <tr> 
                <td><img src="images/noadd.gif" width="15" height="15">&nbsp;<font color="<?php echo $f==6?'#FF0000':'#000000';?>">初始化管理员账号</font></td>
              </tr>
              <tr> 
                <td><img src="images/noadd.gif" width="15" height="15">&nbsp;<font color="<?php echo $f==7?'#FF0000':'#000000';?>">安装完毕</font></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td><div align="center"><strong><font color="#0000FF" size="3">想到即可做到 - 帝国网站管理系统</font></strong></div></td>
  </tr>
  <tr> 
    <td valign="top"> 
    <?php
	if($enews=="ckere")//运行环境
	{
		include('page/eins_ere.php');
	}
	elseif($enews=="ckpath")//设置目录权限
	{
		include('page/eins_ckpath.php');
	}
	elseif($enews=="chdbtype")//选择数据库类型
	{
		include('page/eins_chdbtype.php');
	}
	elseif($enews=="setdb")//配置数据库
	{
		$mycookievarpre=strtolower(eins_InstallMakePassword(5));
		$myadmincookievarpre=strtolower(eins_InstallMakePassword(5));
		include('page/eins_setdb.php');
	}
	elseif($enews=="firstadmin")//初使化管理员
	{
		include('page/eins_firstadmin.php');
	}
	elseif($enews=="success")//安装完毕
	{
		//锁定安装程序
		$fp=@fopen("../../c/einstall/install.off","wb");
		@fclose($fp);
		$word='恭喜您！您已成功安装帝国网站管理系统．';
		if($_GET['defaultdata'])
		{
			$word='恭喜您！您已成功安装帝国网站管理系统．<br>请继续操作初始化内置数据(看安装说明第三大步)。';
		}
		include('page/eins_success.php');
	}
	else//用户条款
	{
		include('page/eins_readme.php');
	}
	?>
    </td>
  </tr>
  <tr> 
    <td valign="top"> <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td><hr align="center"></td>
        </tr>
        <tr> 
          <td height="25"><div align="center"><a href="http://www.PHome.Net" target="_blank">官方网站</a>&nbsp; 
              | &nbsp;<a href="http://www.phome.net/support/" target="_blank">技术支持</a>&nbsp; 
              | &nbsp;<a href="http://www.phome.net/case/" target="_blank">部分案例</a>&nbsp; 
              | &nbsp;<a href="http://www.phome.net/ecmssay/" target="_blank">系统特性</a>&nbsp; 
              | &nbsp;<a href="http://www.phome.net/template/" target="_blank">模板下载</a>&nbsp; 
              | &nbsp;<a href="http://www.phome.net/docs/" target="_blank">教程下载</a>&nbsp; 
              | &nbsp;<a href="http://www.phome.net/service/about.html" target="_blank">关于帝国</a></div></td>
        </tr>
        <tr> 
          <td height="36"> <div align="center">帝兴软件开发有限公司 版权所有<BR>
              <font face="Arial, Helvetica, sans-serif">Copyright &copy; 2002 
              - 2025<b> <a href="http://www.PHome.net"><font color="#000000">PHome</font><font color="#FF6600">.Net</font></a></b></font></div></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>