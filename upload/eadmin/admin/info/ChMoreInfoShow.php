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
$infomaxnum=0;
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
$delid=(int)$_GET['delid'];
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

//表名
$tbr=$empire->fetch1("select tbname from {$dbtbpre}enewstable where tbname='$tbname'");
if(!$tbr['tbname'])
{
	printerror('ErrorUrl','');
}
//参数
$urladdcs="tbname=$tbname&modid=$modid&classid=$classid&id=$id&enews=$enews&form=$form&field=$field&fdivid=$fdivid&fchids=$fchids".$ecms_hashur['ehref'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>选择信息</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
<?php
$ids='';
$retids='';
if($fchids)
{
	$dh='';
	$fchidr=explode($expstr,$fchids);
	$count=count($fchidr);
	$showno=0;
	for($i=0;$i<$count;$i++)
	{
		$infoid=(int)$fchidr[$i];
		if(!$infoid||$infoid==$delid)
		{
			continue;
		}
		if($infomaxnum)
		{
			if($showno>$infomaxnum)
			{
				break;
			}
		}
		$ids.=$dh.$infoid;
		$dh=$expstr;
		$showno++;
	}
	if($ids)
	{
	$dh='';
	$infosql=$empire->query("select isurl,titleurl,classid,id,newstime,username,userid,title,titlepic from {$dbtbpre}ecms_".$tbr['tbname']." where id in (".$ids.") order by newstime desc");
	while($infor=$empire->fetch($infosql))
	{
		$retids.=$dh.$infor['id'];
		$dh=$expstr;
		$titleurl=sys_ReturnBqTitleLink($infor);
		//标题图片
		$showtitlepic='';
		if($infor['titlepic'])
		{
			$showtitlepic="<a href='".$infor['titlepic']."' title='预览标题图片' target=_blank><img src='../../../e/data/images/showimg.gif' border=0></a>";
		}
		//标题
		$oldtitle=$infor['title'];
		$infor['title']=stripSlashes(sub($infor['title'],0,28,false));
		?>
          <tr bgcolor="#ffffff" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
            <td width="11%" height="25" title="发布时间：<?=date('Y-m-d H:i:s',$infor['newstime'])?>"> <div align="center">
                <?=$infor['id']?>
              </div></td>
            <td width="75%"><?=$showtitlepic?>
			 <a href="<?=$titleurl?>" target="_blank" title="<?=$oldtitle?>">
              <?=$infor['title']?>
              </a></td>
            <td width="14%"><div align="center"><a href="ChMoreInfoShow.php?<?=$urladdcs?>&delid=<?=$infor['id']?>" onclick="return confirm('确认要从列表中移除?');">移除</a></div></td>
          </tr>
	<?php
	}
	}
}	
?>
</table>
<br>
<script>
parent.document.chmoreinfoform.treturnfchids.value='<?=$retids?>';
parent.document.searchminfoform.returnfchids.value='<?=$retids?>';
</script>
</body>
</html>
<?php
db_close();
$empire=null;
?>
