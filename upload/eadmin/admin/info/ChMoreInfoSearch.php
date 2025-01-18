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
$showselfinfo=0;//是否排除当前信息,1为排除
$infomaxnum=0;
$expstr=',';
$tbname=RepPostVar($_GET['tbname']);
$modid=(int)$_GET['modid'];
$form=RepPostVar($_GET['form']);
$field=RepPostVar($_GET['field']);
$fdivid=RepPostVar($_GET['fdivid']);
//$fchids=RepPostVar($_GET['fchids']);
$pclassid=(int)$_GET['pclassid'];
$pid=(int)$_GET['pid'];
$enews=ehtmlspecialchars($_GET['enews']);
//搜索参数
$keyboard=RepPostVar2($_GET['keyboard']);
$show=(int)$_GET['show'];
$sear=(int)$_GET['sear'];
$classid=(int)$_GET['classid'];
$returnfchids=RepPostVar($_GET['returnfchids']);
//验证
if(!eInfoHaveTable($tbname,0))
{
	exit();
	//printerror("ErrorUrl","history.go(-1)");
}
/*
if(!eInfoHaveModid($modid,0))
{
	exit();
	//printerror("ErrorUrl","history.go(-1)");
}
*/
if(!eCkIdsListStr($returnfchids,$expstr,0))
{
	exit();
	//printerror("ErrorUrl","history.go(-1)");
}

eCheckStrType(4,$tbname,1);
eCheckStrType(4,$enews,1);
eCheckStrType(5,$form,1);
eCheckStrType(5,$field,1);
eCheckStrType(5,$fdivid,1);

//表名
$tbr=$empire->fetch1("select tbname from {$dbtbpre}enewstable where tbname='$tbname'");
if(!$tbr['tbname'])
{
	exit();
	//printerror('ErrorUrl','');
}
if($emptykeyboard)
{
	if(!trim($keyboard))
	{
		exit();
	}
}
//参数
$urladdcs="tbname=$tbname&modid=$modid&classid=$pclassid&id=$pid&enews=$enews&form=$form&field=$field&fdivid=$fdivid".$ecms_hashur['ehref'];
//搜索
$search="&tbname=$tbname&modid=$modid&pclassid=$pclassid&pid=$pid&enews=$enews&form=$form&field=$field&fdivid=$fdivid&classid=$classid&keyboard=$keyboard&show=$show&sear=$sear&returnfchids=$returnfchids".$ecms_hashur['ehref'];
$add='';
//分页
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=30;//每页显示条数
$page_line=6;//每页显示链接数
$offset=$page*$line;//总偏移量
//已选信息
$ids='';
$dh='';
$fchidr=explode($expstr,$returnfchids);
$count=count($fchidr);
for($i=0;$i<$count;$i++)
{
	$infoid=(int)$fchidr[$i];
	if(!$infoid)
	{
		continue;
	}
	$ids.=$dh.$infoid;
	$dh=$expstr;
}
//当前信息排除
if($ids)
{
	if($pid)
	{
		if($showselfinfo==1)
		{
			$ids.=$expstr.$pid;
		}
	}
}
else
{
	$ids=$showselfinfo==1?$pid:0;
}
//栏目
if($classid)
{
	if($class_r[$classid]['islast'])
	{
		$add.=" and classid='$classid'";
	}
	else
	{
		$add.=" and (".ReturnClass($class_r[$classid]['sonclass']).")";
	}
}
//搜索
if($keyboard)
{
	$kbr=explode(' ',$keyboard);
	$kbcount=count($kbr);
	$kbor='';
	$kbwhere='';
	for($kbi=0;$kbi<$kbcount;$kbi++)
	{
		if(!$kbr[$kbi])
		{
			continue;
		}
		if($show==1)
		{
			$kbwhere.=$kbor."title like '%".$kbr[$kbi]."%'";
		}
		elseif($show==2)
		{
			$kbwhere.=$kbor."keyboard like '%".$kbr[$kbi]."%'";
		}
		else
		{
			$kbwhere.=$kbor."id='".$kbr[$kbi]."'";
		}
		$kbor=' or ';
	}
	if($kbwhere)
	{
		$add.=' and ('.$kbwhere.')';
	}
}
$query="select isurl,titleurl,classid,id,newstime,username,userid,title,titlepic from {$dbtbpre}ecms_".$tbr['tbname']." where id not in (".$ids.")".$add;
$totalquery="select count(*) as total from {$dbtbpre}ecms_".$tbr['tbname']." where id not in (".$ids.")".$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by newstime desc".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>选择信息</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function AddFChids(infoid){
	var str;
	var r;
	var kr;
	var fchids;
	var ckinfoid;
	var showmaxnum=<?=$infomaxnum?>;
	fchids=parent.document.chmoreinfoform.treturnfchids.value;
	str=','+fchids+',';
	ckinfoid=','+infoid+',';
	r=str.split(ckinfoid);
	if(r.length!=1)
	{
		alert('此信息已添加');
		return false;
	}
	<?php
	if($infomaxnum)
	{
	?>
	kr=fchids.split(',');
	if(kr.length>=showmaxnum)
	{
		alert('添加数量已超过设定('+showmaxnum+'个)');
		return false;
	}
	<?php
	}
	?>
	if(fchids=='')
	{
		fchids=infoid;
	}
	else
	{
		fchids+=','+infoid;
	}
	parent.showminfopage.location.href='ChMoreInfoShow.php?<?=$urladdcs?>&fchids='+fchids;
	document.getElementById('doaddfchid'+infoid).innerHTML='--';
}
</script>
</head>

<body>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
<?php
while($infor=$empire->fetch($sql))
{
	$titleurl=sys_ReturnBqTitleLink($infor);
	//标题图片
	$showtitlepic='';
	if($infor['titlepic'])
	{
		$showtitlepic="<a href='".$infor['titlepic']."' title='预览标题图片' target=_blank><img src='../../../e/data/images/showimg.gif' border=0></a>";
	}
	//标题
	$oldtitle=$infor['title'];
	$infor['title']=stripSlashes(sub($infor['title'],0,38,false));
	?>
  <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
    <td width="11%" height="25" title="发布时间：<?=date('Y-m-d H:i:s',$infor['newstime'])?>"> 
      <div align="center"> 
        <?=$infor['id']?>
      </div></td>
    <td width="75%"><?=$showtitlepic?>
	 <a href="<?=$titleurl?>" target="_blank" title="<?=$oldtitle?>"> 
      <?=$infor['title']?>
      </a></td>
    <td width="14%"><div align="center" id="doaddfchid<?=$infor['id']?>"><a href="#empirecms" onclick="AddFChids('<?=$infor['id']?>');">添加</a></div></td>
  </tr>
<?php
}
?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25" colspan="3"> 
      <?=$returnpage?>
    </td>
  </tr>
</table>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>