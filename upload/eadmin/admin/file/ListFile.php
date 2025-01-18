<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
require "../../../e/data/".LoadLang("pub/fun.php");
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
//验证权限
CheckLevel($logininid,$loginin,$classid,"file");
//参数
$modtype=(int)$_GET['modtype'];
$fstb=(int)$_GET['fstb'];
//附件表
$fstb=eReturnFileStb($fstb);
//附件类型
$isinfofile=0;
$showfstb='';
if($modtype==1)//栏目
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_other where modtype=1";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_other where modtype=1";
	$tranname='栏目';
}
elseif($modtype==2)//专题
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_other where modtype=2";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_other where modtype=2";
	$tranname='专题';
}
elseif($modtype==3)//广告
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_other where modtype=3";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_other where modtype=3";
	$tranname='广告';
}
elseif($modtype==4)//反馈
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_other where modtype=4";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_other where modtype=4";
	$tranname='反馈';
}
elseif($modtype==5)//公共
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_public where 1=1";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_public where 1=1";
	$tranname='公共';
}
elseif($modtype==6)//会员
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_member where 1=1";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_member where 1=1";
	$tranname='会员';
}
elseif($modtype==7)//碎片
{
	$query="select fileid,filename,filesize,path,filetime,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_other where modtype=7";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_other where modtype=7";
	$tranname='碎片';
}
else//信息
{
	$isinfofile=1;
	$showfstb=' - 分表'.$fstb.' ';
	$query="select fileid,filename,filesize,path,filetime,classid,no,fpath,adduser,id,type,onclick from {$dbtbpre}enewsfile_{$fstb} where 1=1";
	$totalquery="select count(*) as total from {$dbtbpre}enewsfile_{$fstb} where 1=1";
	$tranname='信息';
}
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$add='';
//附件类型
$type=(int)$_GET['type'];
if($type!=9)//其他附件
{
	$add.=" and type='$type'";
}
//选择栏目
$classid=(int)$_GET['classid'];
/*
$fcjsfile='../../../c/ecachepub/eclassfc/cmsclass.js';
$classoptions=GetFcfiletext($fcjsfile);
*/
//栏目
if($isinfofile==1)
{
	if($classid)
	{
		if($class_r[$classid]['islast'])
		{
			$add.=" and classid='$classid'";
		}
		else
		{
			$add.=" and ".ReturnClass($class_r[$classid]['sonclass']);
		}
		//$classoptions=str_replace("<option value='$classid'","<option value='$classid' selected",$classoptions);
	}
}
//附件分类1
$cid=(int)$_GET['cid'];
if($cid)
{
	$add.=" and cid='$cid'";
}
//附件分类2
$cid2=(int)$_GET['cid2'];
if($cid2)
{
	$add.=" and cid2='$cid2'";
}
//关键字
$keyboard=RepPostVar2($_GET['keyboard']);
if(!empty($keyboard))
{
	$show=RepPostStr($_GET['show'],1);
	//搜索全部
	if($show==0)
	{
		$add.=" and (filename like '%$keyboard%' or no like '%$keyboard%' or adduser like '%$keyboard%')";
	}
	//搜索文件名
	elseif($show==1)
	{
		$add.=" and filename like '%$keyboard%'";
	}
	//搜索编号
	elseif($show==2)
	{
		$add.=" and no like '%$keyboard%'";
	}
	//搜索上传者
	else
	{
		$add.=" and adduser like '%$keyboard%'";
	}
}
$search="&classid=$classid&type=$type&modtype=$modtype&fstb=$fstb&show=$show&keyboard=$keyboard&cid=$cid&cid2=$cid2".$ecms_hashur['ehref'];
$query.=$add;
$totalquery.=$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by fileid desc".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);

//formhash
$efhr=heformhash_getr('DelFile_all');
$efhr1=heformhash_getr('EditFile_all');
if($efhr['vname']!=$efhr1['vname'])
{
	$efhr['vform'].=$efhr1['vform'];
}
//$efh=heformhash_get('DelFile_all');
$efh1=heformhash_get('DelFile',1);
$efh2=heformhash_get('DelFreeFile',1);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>管理附件</title>
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="36%" height="32">位置：管理<?=$tranname?>附件<?=$showfstb?> (数据库式)&nbsp;</td>
    <td width="64%"><div align="right" class="emenubutton">
      </div></td>
  </tr>
</table>
<br>
  
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
  <form name="SearchForm" id="SearchForm" method="get" action="ListFile.php" onsubmit="document.SearchForm.classid.value=document.getElementById('esnav_classid').value;">
  <?=$ecms_hashur['eform']?>
    <input type=hidden name=classid id="classid" value="<?=$classid?>">
	<input type=hidden name=modtype value="<?=$modtype?>">
    <input type=hidden name=fstb value="<?=$fstb?>">
    <tr> 
      <td width="84%">搜索: 
        <select name="type" id="select">
          <option value="9">所有附件类型</option>
          <option value="1"<?=$type==1?' selected':''?>>图片</option>
          <option value="2"<?=$type==2?' selected':''?>>Flash文件</option>
          <option value="3"<?=$type==3?' selected':''?>>多媒体文件</option>
          <option value="0"<?=$type==0?' selected':''?>>其他附件</option>
        </select> <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
        <select name="show" id="show">
          <option value="0"<?=$show==0?' checked':''?>>不限</option>
          <option value="1"<?=$show==1?' checked':''?>>文件名</option>
          <option value="2"<?=$show==2?' checked':''?>>别名</option>
          <option value="3"<?=$show==3?' checked':''?>>上传者</option>
        </select>
		<span id="listfileclassnav"></span>
		<select name="cid" id="cid">
          <option value="0">不限分类1</option>
		  <?=PubReturnSelectClass('enewsfileclass','cid','cname','',$cid,'myorder asc','')?>
        </select>
		<select name="cid2" id="cid2">
          <option value="0">不限分类2</option>
		  <?=PubReturnSelectClass('enewsfileclasst','cid','cname','',$cid2,'myorder asc','')?>
        </select>
      <input type="submit" name="Submit2" value="搜索"> </td>
      <td width="16%"><div align="center">[<a href="../ecmsfile.php?enews=DelFreeFile<?=$ecms_hashur['href'].$efh2?>" onclick="return confirm('确认要操作?');">清理失效附件</a>]</div></td>
    </tr>
  </form>
</table>
<form name="listform" id="listform" method="post" action="../ecmsfile.php" onsubmit="return confirm('确认要操作?');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?=$efhr['vform']?>
    <tr class="header"> 
      <td width="8%" height="25"><div align="center">ID</div></td>
      <td width="32%" height="25"><div align="center">文件名</div></td>
      <td width="13%" height="25"><div align="center">增加者</div></td>
      <td width="12%"><div align="center">文件大小</div></td>
      <td width="19%" height="25"><div align="center">增加时间</div></td>
      <td width="16%" height="25"><div align="center">操作</div></td>
    </tr>
    <?php
	while($r=$empire->fetch($sql))
	{
		$filesize=ChTheFilesize($r['filesize']);
		$fspath=ReturnFileSavePath($r['classid'],$r['fpath']);
		$filepath=$r['path']?$r['path'].'/':$r['path'];
		$path1=$fspath['fileurl'].$filepath.$r['filename'];
		//引用
		$thisfileid=$r['fileid'];
		if($isinfofile==1&&$r['id'])
		{
			$thisfileid="<b><a href='../../../e/public/InfoUrl/?classid=".$r['classid']."&id=".$r['id']."' target=_blank>".$r['fileid']."</a></b>";
		}
	?>
    <tr bgcolor="#FFFFFF" id="file<?=$r['fileid']?>" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
      <td height="25"><div align="center"> 
          <?=$thisfileid?>
		  <input name="dofileid[]" type="hidden" id="dofileid[]" value="<?=$r['fileid']?>">
        </div></td>
      <td height="25"><div align="center"> 
          <input name="dofileno[]" type="text" id="dofileno[]" value="<?=$r['no']?>" size="20">
          <br>
          <a href="<?=$path1?>" target="_blank">
          <?=$r['filename']?>
          </a> </div></td>
      <td height="25"><div align="center"> 
          <?=$r['adduser']?>
        </div></td>
      <td><div align="center"> 
          <?=$filesize?>
        </div></td>
      <td height="25"><div align="center"> 
          <?=date('Y-m-d H:i:s',$r['filetime'])?>
        </div></td>
      <td height="25"><div align="center">[<a href="EditFile.php?fileid=<?=$r['fileid']?>&modtype=<?=$modtype?>&fstb=<?=$fstb?><?=$ecms_hashur['ehref']?>" target="_blank">修改</a>]&nbsp; &nbsp;[<a href="../ecmsfile.php?enews=DelFile&fileid=<?=$r['fileid']?>&modtype=<?=$modtype?>&fstb=<?=$fstb?><?=$ecms_hashur['href'].$efh1?>" onclick="return confirm('您是否要删除？');">删除</a> 
          <input name="fileid[]" type="checkbox" id="fileid[]" value="<?=$r['fileid']?>" onclick="if(this.checked){file<?=$r['fileid']?>.style.backgroundColor='#DBEAF5';}else{file<?=$r['fileid']?>.style.backgroundColor='#ffffff';}">
          ]</div></td>
    </tr>
    <?php
	}
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="6"> 
        <?=$returnpage?>
        &nbsp;&nbsp; <input type="submit" name="Submit" value="删除选中" onClick="document.listform.enews.value='DelFile_all';document.listform.<?=$efhr['vname']?>.value='<?=$efhr['vval']?>';document.listform.action='../ecmsfile.php';"> &nbsp;&nbsp;
		<input type="submit" name="Submit" value="修改别名"  onClick="document.listform.enews.value='EditFile_all';document.listform.<?=$efhr1['vname']?>.value='<?=$efhr1['vval']?>';document.listform.action='../ecmsfile.php';">
		<input name="enews" type="hidden" id="enews" value="DelFile_all"> 
        &nbsp;
        <input type=checkbox name=chkall value=on onClick=CheckAll(this.form)>
        选中全部
		<input type=hidden name=classid value="<?=$classid?>">
		<input type=hidden name=modtype value="<?=$modtype?>">
		<input type=hidden name=fstb value="<?=$fstb?>">
	  </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25" colspan="6"><font color="#666666">如果ID是粗体，表示有信息引用，点击ID即可查看信息页面</font></td>
    </tr>
  </table>
</form>
<?php
if($isinfofile==1)
{
?>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="../ShowClassNav.php?ecms=5&classid=<?=$classid?><?=$ecms_hashur['ehref']?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
<?php
}
?>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>
