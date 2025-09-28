<?php
define('EmpireCMSAdmin','1');
require("../../e/class/connect.php");
require("../../e/class/functions.php");
require("../../e/member/class/user.php");
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
//我的状态
$user_r=$empire->fetch1("select pretime,preip,loginnum,preipport,onepassnum,edpasstime from {$dbtbpre}enewsuser where userid='$logininid'");
$gr=$empire->fetch1("select groupname from {$dbtbpre}enewsgroup where groupid='$loginlevel'");
//密码有效期检测
ePasswordCkTime_hck($logininid,$loginin,$user_r['edpasstime'],'');
//管理员统计
$adminnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser");
$date=date("Y-m-d");
$noplnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspl_".$public_r['pldeftb']." where checked=1");
//未审核会员
$nomembernum=$empire->gettotal("select count(*) as total from ".eReturnMemberTable()." where ".egetmf('checked')."=0");
//过期广告
$outtimeadnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsad where endtime<'$date' and endtime<>'".eDefEmptyDate()."'");
//签发信息
$qfinfonum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewswfinfo where checktno=0 and (groupid like '%,".$lur['groupid'].",%' or userclass like '%,".$lur['classid'].",%' or username like '%,".$lur['username'].",%')");
//系统信息
	if(function_exists('ini_get')){
        $onoff = ini_get('register_globals');
    } else {
        $onoff = get_cfg_var('register_globals');
    }
    if($onoff){
        $onoff="打开";
    }else{
        $onoff="关闭";
    }
    if(function_exists('ini_get')){
        $upload = ini_get('file_uploads');
    } else {
        $upload = get_cfg_var('file_uploads');
    }
    if ($upload){
        $upload="可以";
    }else{
        $upload="不可以";
    }
	if(function_exists('ini_get')){
        $uploadsize = ini_get('upload_max_filesize');
    } else {
        $uploadsize = get_cfg_var('upload_max_filesize');
    }
	if(function_exists('ini_get')){
        $uploadpostsize = ini_get('post_max_size');
    } else {
        $uploadpostsize = get_cfg_var('post_max_size');
    }
//开启
$register_ok="开启";
if($public_r['register_ok'])
{$register_ok="关闭";}
$addnews_ok="开启";
if($public_r['addnews_ok'])
{$addnews_ok="关闭";}
//版本
@include("../../e/class/EmpireCMS_version.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>帝国网站管理系统</title>
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td><div align="center"><strong> 
        <h3>欢迎使用帝国网站管理系统 (EmpireCMS)</h3>
        </strong></div></td>
  </tr>
  <tr> 
    <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25">我的状态</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"> <table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td height="22">登录者:&nbsp;<b>
                  <?=$loginin?>
                  </b>&nbsp;&nbsp;,所属用户组:&nbsp;<b>
                  <?=$gr['groupname']?>
                  </b></td>
              </tr>
              <tr>
                <td height="22">这是您第 <b>
                  <?=$user_r['loginnum']?>
                  </b> 次登录，上次登录时间：
                  <?=$user_r['pretime']?date('Y-m-d H:i:s',$user_r['pretime']):'---'?>
                  ，登录IP：
                  <?=$user_r['preip']?$user_r['preip'].':'.$user_r['preipport']:'---'?>
				  ，一次性密码使用次数：
                  <b><?=$user_r['onepassnum']?></b>
				 </td>
              </tr>
            </table>          </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td width="100%" height="25"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><strong><a href="#ecms">快捷菜单</a></strong></td>
                <td title="MYSQL备份数据库工具"><div align="right"><a href="http://www.phome.net/EmpireBak/" target="_blank"><strong>帝国备份王下载</strong></a></div></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"><strong>信息操作</strong>：&nbsp;&nbsp;<a href="AddInfoChClass.php<?=$ecms_hashur['whehref']?>">增加信息</a>&nbsp;&nbsp; 
            <a href="ListAllInfo.php<?=$ecms_hashur['whehref']?>">管理信息</a>&nbsp;&nbsp; <a href="ListAllInfo.php?ecmscheck=1<?=$ecms_hashur['ehref']?>">审核信息</a> 
            &nbsp;&nbsp; <a href="workflow/ListWfInfo.php<?=$ecms_hashur['whehref']?>">签发信息</a>(<strong><font color="#FF0000"><?=$qfinfonum?></font></strong>)&nbsp;&nbsp; <a href="openpage/AdminPage.php?efileid=3<?=$ecms_hashur['ehref']?>">管理评论</a>&nbsp;&nbsp; <a href="sp/UpdgxSp.php<?=$ecms_hashur['whehref']?>">更新碎片</a>&nbsp;&nbsp; <a href="special/UpdgxZt.php<?=$ecms_hashur['whehref']?>">更新专题</a>&nbsp;&nbsp; <a href="info/InfoMain.php<?=$ecms_hashur['whehref']?>">数据统计</a></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"><strong>栏目操作</strong>：&nbsp;&nbsp;<a href="ListClass.php<?=$ecms_hashur['whehref']?>">管理栏目</a>&nbsp;&nbsp; 
            <a href="special/ListZt.php<?=$ecms_hashur['whehref']?>">管理专题</a>&nbsp;&nbsp; <a href="ListInfoClass.php<?=$ecms_hashur['whehref']?>">管理采集</a> 
            &nbsp;&nbsp; <a href="openpage/AdminPage.php?efileid=4<?=$ecms_hashur['ehref']?>">附件管理</a>&nbsp;&nbsp; 
            <a href="SetEnews.php<?=$ecms_hashur['whehref']?>">系统参数设置</a>&nbsp;&nbsp; <a href="ReHtml/ChangeData.php<?=$ecms_hashur['whehref']?>">数据更新中心</a></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"><strong>用户操作</strong>：&nbsp;&nbsp;<a href="member/ListMember.php?sear=1&schecked=1<?=$ecms_hashur['ehref']?>">审核会员</a>&nbsp;&nbsp; 
            <a href="member/ListMember.php<?=$ecms_hashur['whehref']?>">管理会员</a>&nbsp;&nbsp;<a href="user/ListLog.php<?=$ecms_hashur['whehref']?>">管理登录日志</a> 
            &nbsp;&nbsp; <a href="user/ListDolog.php<?=$ecms_hashur['whehref']?>">管理操作日志</a>&nbsp;&nbsp; <a href="user/EditPassword.php<?=$ecms_hashur['whehref']?>">修改个人资料</a>&nbsp;&nbsp; 
            <a href="user/UserTotal.php<?=$ecms_hashur['whehref']?>">用户发布统计</a>&nbsp;&nbsp;<a href="user/ListLgacUser.php<?=$ecms_hashur['whehref']?>">管理后台登录激活</a></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"><strong>反馈管理</strong>：&nbsp;&nbsp;<a href="tool/gbook.php<?=$ecms_hashur['whehref']?>">管理留言</a>&nbsp;&nbsp; 
            <a href="tool/feedback.php<?=$ecms_hashur['whehref']?>">管理反馈信息</a>&nbsp;&nbsp;<a href="DownSys/ListError.php<?=$ecms_hashur['whehref']?>">管理错误报告</a>&nbsp;&nbsp; 
            <a href="#empirecms" onClick="window.open('openpage/AdminPage.php?efileid=6<?=$ecms_hashur['ehref']?>','AdminShopSys','');">管理订单</a>&nbsp;&nbsp;<a href="pay/ListPayRecord.php<?=$ecms_hashur['whehref']?>">管理支付记录</a>&nbsp;&nbsp; 
            <a href="PathLevel.php<?=$ecms_hashur['whehref']?>">查看目录权限状态</a></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="102">
      <table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr> 
          <td bgcolor="#FFFFFF"><div align="center"><img src="loginimg/ecmsbanner.gif" border="0"></div></td>
        </tr>
    </table>	</td>
  </tr>
  <tr> 
    <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="50%"><a href="#"><strong>系统信息</strong></a></td>
                <td title="MYSQL备份+管理数据库工具"><div align="right"><a href="http://www.phome.net/EmpireBak/ebma/" target="_blank"><strong>帝国EBMA系统下载</strong></a></div></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td width="43%"><strong>网站信息</strong></td>
          <td width="57%"><strong>服务器信息</strong></td>
        </tr>
        <tr> 
          <td valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td width="28%" height="23">会员注册:</td>
                <td width="72%"> 
                  <?=$register_ok?>                </td>
              </tr>
              <tr> 
                <td height="23">会员投稿:</td>
                <td> 
                  <?=$addnews_ok?>                </td>
              </tr>
              <tr> 
                <td height="23">管理员个数:</td>
                <td><a href="user/ListUser.php<?=$ecms_hashur['whehref']?>"><?=$adminnum?></a> 人</td>
              </tr>
              <tr> 
                <td height="23">未审核评论:</td>
                <td><a href="openpage/AdminPage.php?efileid=7<?=$ecms_hashur['ehref']?>"><?=$noplnum?></a> 条</td>
              </tr>
              <tr> 
                <td height="23">未审核会员:</td>
                <td><a href="member/ListMember.php?sear=1&schecked=1<?=$ecms_hashur['ehref']?>"><?=$nomembernum?></a> 人</td>
              </tr>
              <tr> 
                <td height="23">过期广告:</td>
                <td><a href="tool/ListAd.php?time=1<?=$ecms_hashur['ehref']?>"><?=$outtimeadnum?></a> 个</td>
              </tr>
              <tr> 
                <td height="23">登录者IP:</td>
                <td><?php echo egetip();?></td>
              </tr>
              <tr> 
                <td height="23">程序版本:</td>
                <td> <a href="http://www.phome.net" target="_blank"><strong>EmpireCMS 
                  v<?=EmpireCMS_VERSION?> Free</strong></a> <font color="#666666">(<?=EmpireCMS_LASTTIME?>)</font></td>
              </tr>
              <tr>
                <td height="23">程序编码:</td>
                <td><?=EmpireCMS_CHARVER?></td>
              </tr>
            </table></td>
          <td valign="top" bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td width="25%" height="23">服务器软件:</td>
                <td width="75%"> 
                  <?=$_SERVER['SERVER_SOFTWARE']?>                </td>
              </tr>
              <tr> 
                <td height="23">操作系统:</td>
                <td><?php echo defined('PHP_OS')?PHP_OS:'未知';?></td>
              </tr>
              <tr> 
                <td height="23">PHP版本:</td>
                <td><?php echo @phpversion();?></td>
              </tr>
              <tr> 
                <td height="23"><?php echo $ecms_config['db']['usedb']=='pgsql'?'PGSQL':'MYSQL';?>版本:</td>
                <td><?php echo do_eGetDBVer(0);?></td>
              </tr>
              <tr> 
                <td height="23">全局变量:</td>
                <td> 
                  <?=$onoff?>
                  <font color="#666666">(建议关闭)</font></td>
              </tr>
              <tr>
                <td height="23">魔术引用:</td>
                <td> 
                  <?=MAGIC_QUOTES_GPC?'开启':'关闭'?>
                  <font color="#666666"></font></td>
              </tr>
              <tr> 
                <td height="23">上传文件:</td>
                <td> 
                  <?=$upload?>
                  <font color="#666666">(最大文件：<?=$uploadsize?>，表单：<?=$uploadpostsize?>)</font> </td>
              </tr>
              <tr> 
                <td height="23">当前时间:</td>
                <td><?php echo date("Y-m-d H:i:s");?></td>
              </tr>
              <tr> 
                <td height="23">使用域名:</td>
                <td> 
                  <?=$_SERVER['HTTP_HOST']?>                </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
      <tr class="header">
        <td height="25" colspan="2">官方信息</td>
      </tr>
      <tr>
        <td width="43%" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="3" cellspacing="1">
            <tr bgcolor="#FFFFFF">
              <td height="25">版权所有</td>
              <td height="25"><a href="http://www.phome.net" target="_blank">漳州市芗城帝兴软件开发有限公司</a></td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td width="28%" height="25">帝国CMS官方网站</td>
              <td width="72%" height="25"><a href="http://www.phome.net" target="_blank">http://www.phome.net</a></td>
            </tr>
            
            <tr bgcolor="#FFFFFF">
              <td height="25">购买帝国CMS授权版</td>
              <td height="25"><a href="http://www.phome.net/buy/" target="_blank">http://www.phome.net/buy/</a></td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td height="25">特别感谢</td>
              <td height="25">禾火木风、yingnt、hicode、sooden、老鬼、小林、天浪歌、TryLife、5starsgeneral</td>
            </tr>
            
        </table></td>
        <td width="57%" height="125" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="3" cellspacing="1">
          <tr bgcolor="#FFFFFF">
            <td height="25">帝国备份王官网</td>
            <td height="25"><a href="http://www.phome.net/EmpireBak/" target="_blank">http://www.phome.net/EmpireBak/</a></td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="25">&nbsp;</td>
            <td height="25">（MYSQL备份数据库工具）</td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td width="25%" height="25">EBMA系统官网</td>
            <td width="75%" height="25"><a href="http://www.phome.net/EmpireBak/ebma/" target="_blank">http://www.phome.net/EmpireBak/ebma/</a></td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td height="25">&nbsp;</td>
            <td height="25">（MYSQL备份+管理数据库工具）</td>
          </tr>
          
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
db_close();
$empire=null;
?>