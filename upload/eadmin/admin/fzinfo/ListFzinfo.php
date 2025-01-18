<?php
define('EmpireCMSAdmin','1');
require('../../../e/class/connect.php');
require('../../../e/class/functions.php');
require '../../../e/data/'.LoadLang('pub/fun.php');
require('../../../e/data/dbcache/class.php');
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
CheckLevel($logininid,$loginin,$classid,"fzinfo");

$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=50;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$add="";
$and='';
$search="";
$search.=$ecms_hashur['ehref'];
$url="<a href=ListFzinfo.php".$ecms_hashur['whehref'].">管理父信息</a>";

//信息ID
$keyboard=RepPostVar($_GET['keyboard']);
if($keyboard)
{
	$and=$add?' and ':' where ';
	if(strlen($keyboard)>12)
	{
		$add.=$and."pubid='$keyboard'";
	}
	else
	{
		$add.=$and."id='$keyboard'";
	}
	$search.='&keyboard='.$keyboard;
}
//分类
$cid=(int)$_GET['cid'];
if($cid)
{
	$and=$add?' and ':' where ';
	$add.=$and."cid='$cid'";
	$search.="&cid=$cid";
}
//系统模型
$mid=(int)$_GET['mid'];
if($mid)
{
	$and=$add?' and ':' where ';
	$add.=$and."mid='$mid'";
	$search.='&mid='.$mid;
}
//栏目
$classid=(int)$_GET['classid'];
if($classid)
{
	$and=$add?' and ':' where ';
	$add.=$and.($class_r[$classid]['islast']?"classid='$classid'":"(".ReturnClass($class_r[$classid]['sonclass']).")");
	$search.='&classid='.$classid;
}
//条件
$scond=(int)$_GET['scond'];
if($scond)
{
	$and=$add?' and ':' where ';
	if($scond==1)
	{
		$add.=$and."usefz=0";
	}
	elseif($scond==2)
	{
		$add.=$and."usefz=1";
	}
	$search.="&scond=$scond";
}

//分类
$selfpagecache_cr=array();
$cstr='';
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsfz_infoclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select='';
	if($cr['classid']==$cid)
	{
		$select=' selected';
	}
	//$selfpagecache_cr[$cr['classid']]['classname']=$cr['classname'];
	
	$cstr.="<option value='".$cr['classid']."'".$select.">".$cr['classname']."</option>";
}
//系统模型
$selfpagecache_modr=array();
$mod_options='';
$m_sql=$empire->query("select mid,mname,usemod from {$dbtbpre}enewsmod order by myorder,mid");
while($m_r=$empire->fetch($m_sql))
{
	$selfpagecache_modr[$m_r['mid']]['mname']=$m_r['mname'];
	if(empty($m_r['usemod']))
	{
		if($m_r['mid']==$mid)
		{$m_d=" selected";}
		else
		{$m_d="";}
		$mod_options.="<option value=".$m_r['mid'].$m_d.">".$m_r['mname']."</option>";
	}
}

$totalquery="select count(*) as total from {$dbtbpre}enewsfz_info".$add;
$query="select * from {$dbtbpre}enewsfz_info".$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by pubid desc".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);

//formhash
$efhr=heformhash_getr('EditFzinfoOrder');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理父信息</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="50%" height="32">位置： 
      <?=$url?>    </td>
    <td><div align="right" class="emenubutton">
        <input type="button" name="Submit52" value="管理父信息分类" onClick="window.open('FzinfoClass.php<?=$ecms_hashur['whehref']?>');"> 
		&nbsp;&nbsp;
        <input type="button" name="Submit6" value="整理父子信息数据" onClick="window.open('ClearFzinfo.php<?=$ecms_hashur['whehref']?>');">
      </div></td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <form name="FSearchForm" id=="FSearchForm" method="GET" action="ListFzinfo.php" onSubmit="document.FSearchForm.classid.value=document.getElementById('esnav_classid').value;">
  <?=$ecms_hashur['eform']?>
    <tr> 
      <td height="30">
	  <a href="#ecms" title="可以是信息ID，也可以是公共信息ID">信息ID</a>： 
        <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
        <select name="mid" id="mid">
          <option value="0">所有系统模型</option>
          <?=$mod_options?>
        </select>
        <select name="cid" id="cid">
          <option value="0">所有父信息分类</option>
          <?=$cstr?>
        </select>
		<span id="listplclassnav"></span>
        <select name="scond" id="scond">
          <option value="0"<?=$scond==0?' selected':''?>>不限条件</option>
          <option value="1"<?=$scond==1?' selected':''?>>只显示信息可选择的父信息</option>
          <option value="2"<?=$scond==2?' selected':''?>>只显示信息不可选择的父信息</option>
        </select>
      <input type="submit" name="Submit8" value="显示">
      <input name="sear" type="hidden" id="sear" value="1">
	  <input type="hidden" name="classid" id="classid" value="0"></td>
    </tr>
  </form>
</table>
<br>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="FzinfoForm" id="FzinfoForm" method="post" action="ecmsfzinfo.php">
  <?=$ecms_hashur['form']?>
  <?=$efhr['vform']?>
    <tr class="header"> 
      <td width="12%" height="25"><div align="center">公共信息ID</div></td>
      <td width="42%" height="25">标题</td>
      <td width="20%"><div align="center">系统模型</div></td>
      <td width="15%">管理</td>
      <td width="11%" height="25"><div align="center">操作</div></td>
    </tr>
    <?php
  //formhash
  $efh=heformhash_get('DelFzinfo',1);
	
  while($zr=$empire->fetch($sql))
  {		
		$tbname=$class_r[$zr['classid']]['tbname'];
		if(!$tbname)
		{
			continue;
		}
		$index_r=$empire->fetch1("select checked from {$dbtbpre}ecms_".$tbname."_index where id='".$zr['id']."'");
		$infotb=ReturnInfoMainTbname($tbname,$index_r['checked']);
		$r=$empire->fetch1("select id,classid,isurl,isqf,havehtml,newstime,truetime,lastdotime,titlepic,title,titleurl,ismember from ".$infotb." where id='".$zr['id']."'");
		$addecmscheck='';
		if($index_r['checked'])
		{
			$addecmscheck='&ecmscheck=1';
		}
		//状态
		$st='';
		$oldtitle=$r['title'];
		$r['title']=stripSlashes(sub($r['title'],0,36,false));
		//时间
		$truetime=date("Y-m-d H:i:s",$r['truetime']);
		$lastdotime=date("Y-m-d H:i:s",$r['lastdotime']);
		//审核
		if(empty($index_r['checked']))
		{
			$checked=" title='未审核' style='background:#99C4E3'";
			$titleurl="../ShowInfo.php?classid=".$r['classid']."&id=".$r['id'].$addecmscheck.$ecms_hashur['ehref'];
			$eagotourl=$r['ismember']?$titleurl:eapage_hGetGotoUrl($titleurl,'',$r['classid'],$r['id'],'eaShowInfo',0);
		}
		else
		{
			$checked="";
			$titleurl=sys_ReturnBqTitleLink($r);
			$eagotourl=eapage_hGetGotoUrl($titleurl,'',$r['classid'],$r['id'],'eaShowInfoUrl',0);
		}
		$eagotourl_onclick='';
		if($eagotourl!=$titleurl)
		{
			$eagotourl_onclick=' onclick="window.open(\''.$eagotourl.'\');return false;"';
		}
		//取得栏目名
		$do=$r['classid'];
		$dob=$class_r[$r['classid']]['bclassid'];
		//标题图片
		$showtitlepic="";
		if($r['titlepic'])
		{
			$showtitlepic="<a href='".$r['titlepic']."' title='预览标题图片' target=_blank><img src='../../../e/data/images/showimg.gif' border=0></a>";
		}
		$myid=$r['id'];
  ?>
    <tr bgcolor="ffffff" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td height="25"><div align="center"> 
          <?=$zr['pubid']?>
        </div></td>
      <td height="25">
		<?=$st?>
          <?=$showtitlepic?>
          <a href='<?=$titleurl?>'<?=$eagotourl_onclick?> target=_blank title="<?=$oldtitle?>"> 
          <?=$r['title']?>
          </a> 
          <?=$qf?>
          <br>
          <font color="#574D5C">栏目:<a href='../ListNews.php?bclassid=<?=$class_r[$r['classid']]['bclassid']?>&classid=<?=$r['classid']?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>' target="_blank"> 
          <font color="#574D5C"> 
          <?=$class_r[$dob]['classname']?>
          </font> </a> &gt; <a href='../ListNews.php?bclassid=<?=$class_r[$r['classid']]['bclassid']?>&classid=<?=$r['classid']?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>' target="_blank"> 
          <font color="#574D5C"> 
          <?=$class_r[$r['classid']]['classname']?>
          </font> </a></font>
		        </td>
      <td><div align="center"> 
          <a href="ListFzinfo.php?mid=<?=$zr['mid']?><?=$ecms_hashur['ehref']?>"><?=$selfpagecache_modr[$zr['mid']]['mname']?></a></div></td>
      <td><a href="AddFzinfo.php?enews=EditFzinfo&classid=<?=$r['classid']?>&id=<?=$r['id']?>&fcid=<?=$cid?><?=$ecms_hashur['ehref']?>">设置参数</a>&nbsp;|&nbsp;<a href="ecmsfzinfo.php?enews=DelFzinfo&classid=<?=$r['classid']?>&id=<?=$r['id']?>&fcid=<?=$cid?><?=$ecms_hashur['href'].$efh?>" onClick="return confirm('确认要取消此父信息？');">取消父信息</a></td>
      <td height="25"><div align="center"><a href="ListFzData.php?fzclassid=<?=$r['classid']?>&fzid=<?=$r['id']?><?=$ecms_hashur['ehref']?>" target=_blank>管理子信息</a></div></td>
    </tr>
    <?php
  }
  ?>
    
    <tr bgcolor="ffffff">
      <td height="25" colspan="5">&nbsp;&nbsp; 
        <?=$returnpage?></td>
    </tr>
	<input name="fcid" type="hidden" id="fcid" value="<?=$cid?>">
  </form>
</table>
<br>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="../ShowClassNav.php?ecms=6&classid=<?=$classid?><?=$ecms_hashur['ehref']?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
</body>
</html>
<?php
db_close();
$empire=null;
?>
