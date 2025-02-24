<?php
define('EmpireCMSAdmin','1');
require('../../../e/class/connect.php');
require('../../../e/class/functions.php');
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

$fzclassid=(int)$_GET['fzclassid'];
$fzid=(int)$_GET['fzid'];
$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
$tbname=$class_r[$fzclassid]['tbname'];
$fztid=(int)$class_r[$fzclassid]['tid'];

//验证权限
CheckLevel($logininid,$loginin,$classid,"fzdatac");

//父信息
if(!$fzclassid||!$fzid||!$tbname||!$fzpubid)
{
	printerror('ErrorUrl','');
}
$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='$fzpubid'".do_dblimit_one());
if(!$fzinfor['id']||!$fzinfor['fzstb'])
{
	printerror('ErrorUrl','');
}
$index_infor=$empire->fetch1("select id,checked from {$dbtbpre}ecms_".$tbname."_index where id='".$fzinfor['id']."'");
if(!$index_infor['id'])
{
	printerror('ErrorUrl','');
}
$finfotb=ReturnInfoMainTbname($tbname,$index_infor['checked']);
$infor=$empire->fetch1("select * from ".$finfotb." where id='".$fzinfor['id']."'".do_dblimit_one());
if(!$infor['id']||$infor['classid']!=$fzclassid)
{
	printerror('ErrorUrl','');
}
//审核
$faddecmscheck='';
if($index_infor['checked'])
{
	$faddecmscheck='&ecmscheck=1';
}
if(empty($index_infor['checked']))
{
	$ftitleurl="../ShowInfo.php?classid=".$infor['classid']."&id=".$infor['id'].$faddecmscheck.$ecms_hashur['ehref'];
	$feagotourl=$infor['ismember']?$ftitleurl:eapage_hGetGotoUrl($ftitleurl,'',$infor['classid'],$infor['id'],'eaShowInfo',0);
}
else
{
	$ftitleurl=sys_ReturnBqTitleLink($infor);
	$feagotourl=eapage_hGetGotoUrl($ftitleurl,'',$infor['classid'],$infor['id'],'eaShowInfoUrl',0);
}
$feagotourl_onclick='';
if($feagotourl!=$ftitleurl)
{
	$feagotourl_onclick=' onclick="window.open(\''.$feagotourl.'\');return false;"';
}


$query="select cid,pubid,bcid,cname,islast,myorder,islist,isopen from {$dbtbpre}enewsfz_class where pubid='$fzpubid' and bcid=0 order by cid";
$sql=$empire->query($query);

//formhash
$efhr=heformhash_getr('EditFzDataClassOrder');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>父信息：<?=$infor['title']?> - 管理子信息分类</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="50%" height="32">
	位置：<a href="ListFzinfo.php<?=$ecms_hashur['whehref']?>">管理父信息</a> &gt; 父信息：<b><a href="<?=$ftitleurl?>"<?=$feagotourl_onclick?> target=_blank><?=$infor['title']?></a></b> &gt; <a href="ListFzDataClass.php?fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?><?=$ecms_hashur['ehref']?>">管理子信息分类</a>
    </td>
    <td><div align="right" class="emenubutton">
        <input type="button" name="Submit52" value="增加子信息分类" onClick="self.location.href='AddFzDataClass.php?enews=AddFzDataClass&fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?><?=$ecms_hashur['ehref']?>';"> 
      </div></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="fzdataclassform" id="fzdataclassform" method="post" action="ecmsfzinfo.php">
  <?=$ecms_hashur['form']?>
  <?=$efhr['vform']?>
  <input name="fzclassid" type="hidden" id="fzclassid" value="<?=$fzclassid?>">
  <input name="fzid" type="hidden" id="fzid" value="<?=$fzid?>">
    <tr class="header">
      <td width="9%"><div align="center">顺序</div></td> 
      <td width="9%"><div align="center"></div></td>
      <td width="9%" height="25"><div align="center">ID</div></td>
      <td width="33%" height="25">分类名</td>
      <td width="11%"><div align="center">开启</div></td>
      <td width="16%"><div align="center">页面模式</div></td>
      <td width="13%" height="25"><div align="center">操作</div></td>
    </tr>
    <?php
  //formhash
  $efh=heformhash_get('DelFzDataClass',1);

  while($r=$empire->fetch($sql))
  {
		if($r['islist']==1)
		{
			$islistmod='列表式';
		}
		elseif($r['islist']==2)
		{
			$islistmod='绑定信息';
		}
		else
		{
			$islistmod='封面式';
		}
		$fzcurl=eReturnRewriteFzUrl($fztid,$fzid,$r['cid'],1);
  ?>
	<tbody id="classdiv<?=$r['cid']?>">
    <tr>
      <td><div align="center"><input type=text name=myorder[] value="<?=$r['myorder']?>" size=2></div></td> 
      <td><img src="../../../e/data/images/dir.gif"></td>
      <td height="25"><div align="center"> 
          <?=$r['cid']?>
		  <input name="cid[]" type="hidden" id="cid[]" value="<?=$r['cid']?>">
        </div></td>
      <td height="25">
          <a href='<?=$fzcurl['pageurl']?>' target=_blank><?=$r['cname']?></a>
      </td>
      <td><div align="center"> 
          <?=$r['isopen']==1?'是':'否'?>
		  </div></td>
      <td><div align="center"><?=$islistmod?></div></td>
      <td height="25"><div align="center"><a href="AddFzDataClass.php?enews=EditFzDataClass&fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?>&cid=<?=$r['cid']?><?=$ecms_hashur['ehref']?>">修改</a> <a href="AddFzDataClass.php?enews=AddFzDataClass&fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?>&cid=<?=$r['cid']?>&docopy=1<?=$ecms_hashur['ehref']?>">复制</a> <a href="ecmsfzinfo.php?enews=DelFzDataClass&fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?>&cid=<?=$r['cid']?><?=$ecms_hashur['href'].$efh?>" onClick="return confirm('确认要删除？');">删除</a></div></td>
    </tr>
	<?php
	$smallsql=$empire->query("select cid,pubid,bcid,cname,islast,myorder,islist,isopen from {$dbtbpre}enewsfz_class where bcid='".$r['cid']."' order by cid");
	while($sr=$empire->fetch($smallsql))
	{
		if($sr['islist']==1)
		{
			$sislistmod='列表式';
		}
		elseif($sr['islist']==2)
		{
			$sislistmod='绑定信息';
		}
		else
		{
			$sislistmod='封面式';
		}
		$fzcurls=eReturnRewriteFzUrl($fztid,$fzid,$sr['cid'],1);
	?>
    <tr bgcolor="ffffff">
      <td><div align="center"><input type=text name=myorder[] value="<?=$sr['myorder']?>" size=2></div></td> 
      <td>&nbsp;&nbsp;&nbsp;<img src="../../../e/data/images/txt.gif"></td>
      <td height="25"><div align="center"> 
          <?=$sr['cid']?>
		  <input name="cid[]" type="hidden" id="cid[]" value="<?=$sr['cid']?>">
        </div></td>
      <td height="25">
          <a href='<?=$fzcurls['pageurl']?>' target=_blank><?=$sr['cname']?></a>
      </td>
      <td><div align="center"> 
          <?=$sr['isopen']==1?'是':'否'?>
		  </div></td>
      <td><div align="center"><?=$sislistmod?></div></td>
      <td height="25"><div align="center"><a href="AddFzDataClass.php?enews=EditFzDataClass&fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?>&cid=<?=$sr['cid']?><?=$ecms_hashur['ehref']?>">修改</a> <a href="AddFzDataClass.php?enews=AddFzDataClass&fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?>&cid=<?=$sr['cid']?>&docopy=1<?=$ecms_hashur['ehref']?>">复制</a> <a href="ecmsfzinfo.php?enews=DelFzDataClass&fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?>&cid=<?=$sr['cid']?><?=$ecms_hashur['href'].$efh?>" onClick="return confirm('确认要删除？');">删除</a></div></td>
    </tr>
	<?php
	}
	?>
	</tbody>
    <?php
  }
  ?>
    
    <tr bgcolor="ffffff">
      <td height="25" colspan="7"><input type="submit" name="Submit522" value="修改排序" onClick="document.fzdataclassform.enews.value='EditFzDataClassOrder';document.fzdataclassform.<?=$efhr['vname']?>.value='<?=$efhr['vval']?>';document.fzdataclassform.action='ecmsfzinfo.php';">
        <font color="#666666">(排序值越小越前面)</font></td>
    </tr>
  </form>
</table>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>
