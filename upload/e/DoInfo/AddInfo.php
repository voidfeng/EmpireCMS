<?php
require("../class/connect.php");
require("../class/q_functions.php");
require("../class/qinfofun.php");
require("../member/class/user.php");
require("../data/dbcache/class.php");
require("../data/dbcache/MemberLevel.php");
$link=db_connect();
$empire=new mysqlquery();
if($public_r['addnews_ok'])//关闭投稿
{
	printerror("NotOpenCQInfo","",1);
}
//验证本时间允许操作
eCheckTimeCloseDo('info');
//验证IP
eCheckAccessDoIp('postinfo');
$classid=(int)$_GET['classid'];
$mid=$class_r[$classid]['modid'];
if(empty($classid)||empty($mid)||InfoIsInTable($class_r[$classid]['tbname']))
{
	printerror("EmptyQinfoCid","",1);
}
$enews=RepPostStr($_GET['enews'],1);
if(empty($enews))
{
	$enews="MAddInfo";
}
$r=array();
$memberinfor=array();
$muserid=(int)getcvar('mluserid');
$musername=RepPostVar(getcvar('mlusername'));
$mrnd=RepPostVar(getcvar('mlrnd'));
$id=0;
$newstime=time();
$r['newstime']=date("Y-m-d H:i:s");
$todaytime=$r['newstime'];
$showkey="";
$r['newstext']="";
$rechangeclass='';
//验证会员信息
$mloginauthr=qCheckLoginAuthstr();
//取得登陆会员资料
if($muserid&&$mloginauthr['islogin'])
{
	$memberinfor=$empire->fetch1("select ".eReturnSelectMemberF('*','u.').",ui.* from ".eReturnMemberTable()." u LEFT JOIN {$dbtbpre}enewsmemberadd ui ON u.".egetmf('userid')."=ui.userid where u.".egetmf('userid')."='$muserid'".do_dblimit_one());
}
//efz
//父信息
$fztid=(int)$_GET['fztid'];
$fzid=(int)$_GET['fzid'];
$fzcid=(int)$_GET['fzcid'];
$fzdoadd=0;
$edbfunc_fzr=array();
$addurlcsfz='';
if($fztid&&$fzid)
{
	$ftbname=$etable_t[$fztid]['tbname'];
	if(!$ftbname)
	{
		printerror('ErrorUrl','',1);
	}
	$bpubid=ReturnInfoPubid(0,$fzid,$fztid);
	$fzinfor=$empire->fetch1("select * from {$dbtbpre}enewsfz_info where pubid='".$bpubid."'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		printerror('ErrorUrl','',1);
	}
	if(!$fzinfor['qadd'])
	{
		printerror('ErrorUrl','',1);
	}
	$binfor=$empire->fetch1("select * from {$dbtbpre}ecms_".$ftbname." where id='".$fzinfor['id']."'".do_dblimit_one());
	if(!$binfor['id'])
	{
		printerror('ErrorUrl','');
	}
	//子信息分类
	$edbfunc_fzr['fzbcid']=0;
	$edbfunc_fzr['fzcid']=0;
	if($fzcid)
	{
		$fzdatacr=$empire->fetch1("select * from {$dbtbpre}enewsfz_class where cid='".$fzcid."' and pubid='".$fzinfor['pubid']."'".do_dblimit_one());
		if(!$fzdatacr['cid'])
		{
			printerror('ErrorUrl','',1);
		}
		if(!$fzdatacr['qadd'])
		{
			printerror('ErrorUrl','',1);
		}
		if($fzdatacr['bcid'])
		{
			$edbfunc_fzr['fzbcid']=$fzdatacr['bcid'];
			$edbfunc_fzr['fzcid']=$fzdatacr['cid'];
		}
		else
		{
			$edbfunc_fzr['fzbcid']=$fzdatacr['cid'];
			$edbfunc_fzr['fzcid']=0;
		}
	}
	$edbfunc_fzr['fzclassid']=$binfor['classid'];
	$edbfunc_fzr['fzid']=$binfor['id'];
	$fzdoadd=1;
	$addurlcsfz='&fztid='.$fztid.'&fzid='.$fzid.'&fzcid='.$fzcid;
}
//增加
if($enews=="MAddInfo")
{
	$cr=DoQCheckAddLevel($classid,$muserid,$musername,$mrnd,0,1);
	$mr=$empire->fetch1("select qenter,qmname from {$dbtbpre}enewsmod where mid='".$cr['modid']."'");
	if(empty($mr['qenter']))
	{
		printerror("NotOpenCQInfo","history.go(-1)",1);
	}
	//IP发布数限制
	$check_ip=egetip();
	$check_checked=$cr['wfid']?0:$cr['checkqadd'];
	eCheckIpAddInfoNum($check_ip,$cr['tbname'],$cr['modid'],$check_checked);
	//验证单信息
	//qCheckMemberOneInfo($cr['tbname'],$cr['modid'],$classid,$muserid);
	//初始变量
	$word="增加信息";
	$ecmsfirstpost=1;
	$rechangeclass="&nbsp;[<a href='ChangeClass.php?mid=".$mid."'>重新选择</a>]";
	//验证码
	if($cr['qaddshowkey'])
	{
		$showkey="<tr bgcolor=\"#FFFFFF\">
      <td width=\"11%\" height=\"25\">验证码</td>
      <td height=\"25\">
        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                <tr> 
                  <td width=\"52\"><input name=\"key\" type=\"text\" id=\"key\" size=\"6\"> 
                  </td>
                  <td id=\"infoshowkey\"><a href=\"#EmpireCMS\" onclick=\"edoshowkey('infoshowkey','info','".$public_r['newsurl']."');\" title=\"点击显示验证码\">点击显示验证码</a></td>
                </tr>
        </table>
      </td></tr>";
	}
	//图片
	$imgwidth=0;
	$imgheight=0;
	//文件验证码
	$filepass=time();
}
else
{
	$word="修改信息";
	$ecmsfirstpost=0;
	$id=(int)$_GET['id'];
	if(empty($id))
	{
		printerror("EmptyQinfoCid","",1);
	}
	$cr=DoQCheckAddLevel($classid,$muserid,$musername,$mrnd,1,0);
	$mr=$empire->fetch1("select qenter,qmname from {$dbtbpre}enewsmod where mid='".$cr['modid']."'");
	if(empty($mr['qenter']))
	{
		printerror("NotOpenCQInfo","history.go(-1)",1);
	}
	$r=CheckQdoinfo($classid,$id,$muserid,$cr['tbname'],$cr['adminqinfo'],1);
	//检测时间
	if($public_r['qeditinfotime'])
	{
		if(time()-$r['truetime']>$public_r['qeditinfotime']*60)
		{
			printerror("QEditInfoOutTime","history.go(-1)",1);
		}
	}
	$newstime=$r['newstime'];
	$r['newstime']=date("Y-m-d H:i:s",$r['newstime']);
	//图片
	$imgwidth=170;
	$imgheight=120;
	//文件验证码
	$filepass=$id;
}
$tbname=$cr['tbname'];
esetcookie("qeditinfo","dgcms");
//标题分类
$cttidswhere='';
$tts='';
$caddr=$empire->fetch1("select ttids from {$dbtbpre}enewsclassadd where classid='$classid'");
if($caddr['ttids']!='-')
{
	if($caddr['ttids']&&$caddr['ttids']!=',')
	{
		$cttidswhere=' and typeid in ('.substr($caddr['ttids'],1,-1).')';
	}
	$ttsql=$empire->query("select typeid,tname from {$dbtbpre}enewsinfotype where mid='".$cr['modid']."'".$cttidswhere." order by myorder");
	while($ttr=$empire->fetch($ttsql))
	{
		$select='';
		if($ttr['typeid']==$r['ttid'])
		{
			$select=' selected';
		}
		$tts.="<option value='".$ttr['typeid']."'".$select.">".$ttr['tname']."</option>";
	}
}
//栏目
$classurl=sys_ReturnBqClassname($cr,9);
$postclass="<a href='".$classurl."' target='_blank'>".$class_r[$classid]['classname']."</a>".$rechangeclass;
if($cr['bclassid'])
{
	$bcr['classid']=$cr['bclassid'];
	$bclassurl=sys_ReturnBqClassname($bcr,9);
	$postclass="<a href='".$bclassurl."' target=_blank>".$class_r[$cr['bclassid']]['classname']."</a>&nbsp;>&nbsp;".$postclass;
}
//html编辑器
$loadeditorjs='';
if($emod_r[$mid]['editorf']&&$emod_r[$mid]['editorf']!=',')
{
	include('../data/ecmseditor/eshoweditor.php');
	$loadeditorjs=ECMS_ShowEditorJS('../data/ecmseditor/infoeditor/');
}
if(empty($musername))
{
	$musername="游客";
}
$cr['modid']=(int)$cr['modid'];

//是否采用文本框
$thismpid=eReturnSMPid();
if($thismpid<=1)
{
	$thismpid=1;
}
$ecms_tofunr=array();
$ecms_tofunr['qedmpid']=$thismpid;
$ecms_tofunr['qedmid']=$cr['modid'];
$ecms_tofunr['qedtbname']=$cr['tbname'];

$isqedtotext=eCkQEdToText($ecms_tofunr['qedmpid'],$ecms_tofunr['qedmid'],$ecms_tofunr['qedtbname'],'');
if($isqedtotext)
{
	$loadeditorjs='';
}

$modfile="../../c/ecachemod/emodform/q".$cr['modid'].".php";
if(eDoCheckHvFile($modfile)==0)
{
	exit();
}
//导入模板
include(ECMS_PATH.'e/template/DoInfo/AddInfo.php');
db_close();
$empire=null;
?>