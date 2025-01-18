<?php
error_reporting(0);

@set_time_limit(1000);

if(file_exists("../../c/einstall/install.off"))
{
	echo"《帝国网站管理系统》安装程序已锁定。如果要重新安装，请删除<b>/c/einstall/install.off</b>文件！";
	exit();
}

//安装密码
include('data/fun.php');
$ins_password='';
$ins_pwvar='';
$ins_insretime=0;
include('ins_config.php');
eins_CkInsPass();

include("../class/connect.php");
include("../class/functions.php");
include LoadLang("pub/fun.php");
include("../class/t_functions.php");
include("../data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();

$ecms=$_GET['ecms'];
$defaultdata=(int)$_GET['defaultdata'];
$echdbtype=(int)$_GET['echdbtype'];

//----------------生成表情JS
function eins_InstallGetPlfaceJs(){
	global $empire,$dbtbpre,$public_r;
	$r=$empire->fetch1("select plface,plfacenum from {$dbtbpre}enewspl_set".do_dblimit_one());
	if(empty($r['plfacenum']))
	{
		return '';
	}
	$filename="../../d/js/js/plface.js";
	$facer=explode('||',$r['plface']);
	$count=count($facer);
	for($i=1;$i<$count-1;$i++)
	{
		if($i%$r['plfacenum']==0)
		{
			$br="<br>";
		}
		else
		{
			$br="&nbsp;";
		}
		$face=explode('##',$facer[$i]);
		$allface.="<a href='#eface' onclick=\\\"eaddplface('".$face[0]."');\\\"><img src='".$public_r['newsurl']."d/efilepub/eplface/".$face[1]."' border=0></a>".$br;
	}
	$allface="document.write(\"<script src='".$public_r['newsurl']."e/data/js/addplface.js'></script>\");document.write(\"".$allface."\");";
	WriteFiletext_n($filename,$allface);
}

//更新其它数据
if($ecms=='ChangeInstallOtherData')
{
	//--- 删除缓存文件 ---
	DelListEnews();
	//--- 更新动态页面 ---
	GetPlTempPage();//评论列表模板
	GetPlJsPage();//评论JS模板
	ReCptemp();//控制面板模板
	GetSearch();//三搜索表单模板
	GetPrintPage();//打印模板
	GetDownloadPage();//下载地址页面
	ReGbooktemp();//留言板模板
	ReLoginIframe();//登陆状态模板
	ReSchAlltemp();//全站搜索模板
	//生成首页
	$indextemp=GetIndextemp();
	NewsBq(0,$indextemp,1,0);
	//--- 更新反馈表单 ---
	$sql=$empire->query("select bid,btemp from {$dbtbpre}enewsfeedbackclass order by bid");
	while($r=$empire->fetch($sql))
	{
		//替换公共变量
		$btemp=ReplaceTempvar($r['btemp']);
		$btemp=str_replace("[!--cp.header--]","<?php include(\"../../../c/ecachetemp/epubtemp/cp_1.php\");?>",$btemp);
		$btemp=str_replace("[!--cp.footer--]","<?php include(\"../../../c/ecachetemp/epubtemp/cp_2.php\");?>",$btemp);
		$btemp=str_replace("[!--member.header--]","<?php include(\"../../template/incfile/header.php\");?>",$btemp);
		$btemp=str_replace("[!--member.footer--]","<?php include(\"../../template/incfile/footer.php\");?>",$btemp);
		$file="../../c/ecachetemp/efbtemp/feedback".$r['bid'].".php";
		$btemp="<?php
if(!defined('InEmpireCMS'))
{exit();}
?>".$btemp;
		WriteFiletext($file,$btemp);
	}
	//--- 评论表情文件 ---
	eins_InstallGetPlfaceJs();
	echo"更新文件完毕.<script>self.location.href='index.php?enews=success&f=7&echdbtype=$echdbtype&defaultdata=$defaultdata';</script>";
	exit();
}
else//更新数据库缓存
{
	GetConfig(1);//更新参数设置
	GetClass();//更新栏目
	GetMemberLevel();//更新会员组
	GetSearchAllTb();//更新全站搜索数据表
	EcmsConfigDayUpRnd('mustdore');//天天随机验证字符
	echo"更新数据库缓存完毕.<script>self.location.href='changedata.php?ecms=ChangeInstallOtherData&echdbtype=$echdbtype&defaultdata=$defaultdata';</script>";
	exit();
}
?>