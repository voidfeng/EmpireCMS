<?php
define('EmpireCMSAdmin','1');
require("../../e/class/connect.php");
require("../../e/class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=(int)$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=(int)$lur['groupid'];
$loginadminstyleid=(int)$lur['adminstyleid'];
//ehash
$ecms_hashur=hReturnEcmsHashStrAll();

//返回目录权限结果
function ReturnPathLevelResult($path){
	$testfile=$path."/test.test";
	$fp=@fopen($testfile,"wb");
	if($fp)
	{
		@fclose($fp);
		@unlink($testfile);
		return 1;
	}
	else
	{
		return 0;
	}
}
//返回文件权限结果
function ReturnFileLevelResult($filename){
	return is_writable($filename);
}
//检测目录权限
function CheckFileMod($filename,$smallfile=""){
	$succ="√";
	$error="<font color=red>×</font>";
	if(!file_exists($filename)||($smallfile&&!file_exists($smallfile)))
	{
		return $error;
	}
	if(is_dir($filename))//目录
	{
		if(!ReturnPathLevelResult($filename))
		{
			return $error;
		}
		//子目录
		if($smallfile)
		{
			if(is_dir($smallfile))
			{
				if(!ReturnPathLevelResult($smallfile))
				{
					return $error;
				}
			}
			else//文件
			{
				if(!ReturnFileLevelResult($smallfile))
				{
					return $error;
				}
			}
		}
	}
	else//文件
	{
		if(!ReturnFileLevelResult($filename))
		{
			return $error;
		}
		if($smallfile)
		{
			if(!ReturnFileLevelResult($smallfile))
			{
				return $error;
			}
		}
	}
	return $succ;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>帝国网站管理系统</title>

<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="form1" method="post" action="">
    <tr class="header"> 
      <td height="25"> <div align="center">目录权限检测</div></td>
    </tr>
    <tr> 
      <td height="100" bgcolor="#FFFFFF"> <div align="center"> 
          <table width="99%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="D6E0EF">
            <tr> 
              <td height="23"><strong>提示信息</strong></td>
            </tr>
            <tr> 
              <td height="25" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="3" cellspacing="1">
                  <tr> 
                    <td height="25"> <li>将下面目录权限设为0777, 除了红色目录外，是目录全部要把权限应用于子目录与文件。<br>
                      </li></td>
                  </tr>
                </table></td>
            </tr>
          </table>
          <br>
          <table width="99%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="D6E0EF">
            <tr> 
              <td width="34%" height="23"> <div align="center"><strong>目录文件名称</strong></div></td>
              <td width="42%"> <div align="center"><strong>说明</strong></div></td>
              <td width="24%"> <div align="center"><strong>权限检查</strong></div></td>
            </tr>
            <tr bgcolor="#FFFFFF"> 
              <td height="25"> <div align="left"><font color="#FF0000"><strong>/</strong></font></div></td>
              <td> <div align="center"><font color="#FF0000">系统根目录(不要应用于子目录)</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("../../");?>
                </div></td>
            </tr>
			<tr bgcolor="#FFFFFF"> 
               <td height="25"> <div align="left">/c</div></td>
               <td> <div align="center"><font color="#666666">系统缓存目录</font></div></td>
               <td> <div align="center"> <?php echo CheckFileMod("../../c","../../c/ecachetmp");?> 
                </div></td>
            </tr>
            <tr bgcolor="#FFFFFF"> 
              <td height="25"> <div align="left">/d</div></td>
              <td> <div align="center"><font color="#FF0000">附件目录 (无需脚本执行权限)</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("../../d","../../d/etmp/etitlepic");?>
                </div></td>
            </tr>
			<tr bgcolor="#FFFFFF"> 
              <td height="25"> <div align="left">/e/config/config.php</div></td>
              <td> <div align="center"><font color="#666666">数据库等参数配置文件</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("../../e/config/config.php");?>
                </div></td>
            </tr>
			<tr bgcolor="#FFFFFF"> 
              <td height="25"> <div align="left">/e/data/dbcache</div></td>
              <td> <div align="center"><font color="#666666">部分数据库缓存文件存放目录</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("../../e/data/dbcache");?>
                </div></td>
            </tr>
			<tr bgcolor="#FFFFFF"> 
              <td height="25">/e/template</td>
              <td> <div align="center"><font color="#666666">动态页面的模板目录</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("../../e/template");?>
                </div></td>
            </tr>
			<tr bgcolor="#FFFFFF"> 
              <td height="25">/eadmin/admin/ebak/bdata</td>
              <td> <div align="center"><font color="#666666">备份数据存放目录</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("ebak/bdata");?>
                </div></td>
            </tr>
            <tr bgcolor="#FFFFFF"> 
              <td height="25">/eadmin/admin/ebak/zip</td>
              <td> <div align="center"><font color="#666666">备份数据压缩存放目录</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("ebak/zip");?>
                </div></td>
            </tr>
			<tr bgcolor="#FFFFFF">
			  <td height="25">/ecachefiles</td>
			  <td><div align="center"><font color="#666666">动态页面缓存目录</font></div></td>
			  <td><div align="center"><?=CheckFileMod("../../ecachefiles","../../ecachefiles/empirecms");?></div></td>
		    </tr>
			<tr bgcolor="#FFFFFF"> 
              <td height="25"> <div align="left">/esavedatas</div></td>
              <td> <div align="center"><font color="#666666">内容存文本文件存放目录</font></div></td>
              <td> <div align="center"> <?php echo CheckFileMod("../../esavedatas","../../esavedatas/edtxt");?> 
              </div></td>
            </tr>
			<tr bgcolor="#FFFFFF"> 
              <td height="25"> <div align="left">/html</div></td>
              <td> <div align="center"><font color="#666666">默认可选的HTML存放目录</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("../../html");?>
                </div></td>
            </tr>
            <tr bgcolor="#FFFFFF"> 
              <td height="25"> <div align="left">/s</div></td>
              <td> <div align="center"><font color="#666666">专题存放目录</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("../../s");?>
                </div></td>
            </tr>
			<tr bgcolor="#FFFFFF"> 
              <td height="25"> <div align="left">/search</div></td>
              <td> <div align="center"><font color="#666666">搜索表单</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("../../search","../../search/test.txt");?>
                </div></td>
            </tr>
			<tr bgcolor="#FFFFFF"> 
              <td height="25"> <div align="left">/t</div></td>
              <td> <div align="center"><font color="#666666">标题分类存放目录</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("../../t");?>
                </div></td>
            </tr>
			<tr bgcolor="#FFFFFF"> 
               <td height="25"> <div align="left">/w</div></td>
               <td> <div align="center"><font color="#666666">可选的网站存放根目录(预留)</font></div></td>
               <td> <div align="center"> <?php echo CheckFileMod("../../w");?> 
                 </div></td>
             </tr>
            <tr bgcolor="#FFFFFF"> 
              <td height="25"> <div align="left">/index.html</div></td>
              <td> <div align="center"><font color="#666666">网站首页</font></div></td>
              <td> <div align="center"> 
                  <?=CheckFileMod("../../index.html");?>
                </div></td>
            </tr>
          </table>
        </div></td>
    </tr>
    <tr class="header"> 
      <td><div align="center"> 
          &nbsp;&nbsp; &nbsp;&nbsp; </div></td>
    </tr>
  </form>
</table>
</body>
</html>
<?php
db_close();
$empire=null;
?>