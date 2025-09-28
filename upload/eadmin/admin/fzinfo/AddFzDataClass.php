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
//验证权限
CheckLevel($logininid,$loginin,$classid,"fzdatac");

$enews=ehtmlspecialchars($_GET['enews']);
$fzclassid=(int)$_GET['fzclassid'];
$fzid=(int)$_GET['fzid'];
$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
$tbname=$class_r[$fzclassid]['tbname'];
$cid=(int)$_GET['cid'];
eCheckStrType(4,$enews,1);

if($enews=='EditFzDataClass')
{
	$enews='EditFzDataClass';
}
else
{
	$enews='AddFzDataClass';
}
//formhash
$efh=heformhash_get($enews);


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


$postword='增加子信息分类';
$url="".$postword;
//初使化数据
$r=array();
$r['myorder']=0;
$r['islist']=1;
$r['lencord']=25;
$r['isopen']=1;
$r['reorder']="newstime DESC";

//复制
$docopy=RepPostStr($_GET['docopy'],1);
if($docopy&&$enews=="AddFzDataClass")
{
	$copyclass=1;
}
$ecmsfirstpost=1;
//修改
if($enews=="EditFzDataClass"||$copyclass)
{
	$ecmsfirstpost=0;
	if($copyclass)
	{
		$thisdo="复制";
	}
	else
	{
		$thisdo="修改";
	}
	$cid=(int)$_GET['cid'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsfz_class where cid='$cid'");
	$url="".$thisdo."子信息分类：".$r['cname'];
	$postword=$thisdo.'子信息分类';
	//复制分类
	if($copyclass)
	{
		$r['cname'].='(1)';
	}
}

//父分类
$csql=$empire->query("select cid,bcid,cname from {$dbtbpre}enewsfz_class where pubid='$fzpubid' and bcid=0 order by cid");
$fzdatacs='';
while($cr=$empire->fetch($csql))
{
	$selected='';
	if($cr['cid']==$r['bcid'])
	{
		$selected=' selected';
	}
	$fzdatacs.="<option value='".$cr['cid']."'".$selected.">".$cr['cname']."</option>";
}
//列表模板
$mod_options='';
$listtemp_options='';
$msql=$empire->query("select mid,mname,usemod from {$dbtbpre}enewsmod order by myorder,mid");
while($mr=$empire->fetch($msql))
{
	if(empty($mr['usemod']))
	{
		if($mr['mid']==$r['mid'])
		{$m_d=" selected";}
		else
		{$m_d="";}
		$mod_options.="<option value=".$mr['mid'].$m_d.">".$mr['mname']."</option>";
	}
	//列表模板
	$listtemp_options.="<option value=0 style='background:#99C4E3'>".$mr['mname']."</option>";
	$l_sql=$empire->query("select tempid,tempname from ".GetTemptb("enewslisttemp")." where modid='".$mr['mid']."'");
	while($l_r=$empire->fetch($l_sql))
	{
		if($l_r['tempid']==$r['listtempid'])
		{$l_d=" selected";}
		else
		{$l_d="";}
		$listtemp_options.="<option value=".$l_r['tempid'].$l_d."> |-".$l_r['tempname']."</option>";
	}
}
//封面模板
$classtemp='';
$classtempsql=$empire->query("select tempid,tempname from ".GetTemptb("enewsclasstemp")." order by tempid");
while($classtempr=$empire->fetch($classtempsql))
{
	$select="";
	if($r['classtempid']==$classtempr['tempid'])
	{
		$select=" selected";
	}
	$classtemp.="<option value='".$classtempr['tempid']."'".$select.">".$classtempr['tempname']."</option>";
}
//当前使用的模板组
$thegid=GetDoTempGid();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$postword?></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>

//修改绑定信息
function EditBdInfo(obj){
	var infoid=obj.bdinfoid.value;
	var r;
	r=infoid.split(',');
	if(infoid==''||r.length==1)
	{
		alert('请输入绑定信息ID');
		return false;
	}
	window.open('../AddNews.php?<?=$ecms_hashur['ehref']?>&enews=EditNews&classid='+r[0]+'&id='+r[1]);
}

</script>

</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32">位置：<a href="ListFzinfo.php<?=$ecms_hashur['whehref']?>">管理父信息</a> &gt; 父信息：<b><a href="<?=$ftitleurl?>"<?=$feagotourl_onclick?> target=_blank><?=$infor['title']?></a></b> &gt; <a href="ListFzDataClass.php?fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?><?=$ecms_hashur['ehref']?>">管理子信息分类</a> &gt; <?=$url?>  
    </td>
  </tr>
</table>
<br>
  
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="form1" id="form1" method="post" action="ecmsfzinfo.php">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25" colspan="2"> 
        <?=$postword?>
        <input type=hidden name=enews value=<?=$enews?>>
		<input name="fzclassid" type="hidden" id="fzclassid" value="<?=$fzclassid?>">
		<input name="fzid" type="hidden" id="fzid" value="<?=$fzid?>">
		<input name="cid" type="hidden" id="cid" value="<?=$cid?>">		</td>
    </tr>
    <tr> 
      <td height="25" colspan="2">基本属性</td>
    </tr>
    <tr> 
      <td width="24%" height="25" bgcolor="#FFFFFF">分类名称(*)</td>
      <td width="76%" height="25" bgcolor="#FFFFFF"> <input name="cname" type="text" id="cname" value="<?=$r['cname']?>" size="38">         </td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">上级分类</td>
      <td height="25" bgcolor="#FFFFFF"><select name="bcid" id="bcid">
	  <?php
	  if($enews=='EditFzDataClass')
	  {
		  if($r['bcid']==0)
		  {
			?>
			<option value="0"<?=$r['bcid']==0?' selected':''?>>一级分类</option>
			<?php
		  }
		  else
		  {
			  ?>
			  <?=$fzdatacs?>
			  <?php
		  }
	  }
	  else
	  {
	  ?>
	  <option value="0"<?=$r['bcid']==0?' selected':''?>>一级分类</option>
	  <?=$fzdatacs?>
	  <?php
	  }
	  ?>
      </select>
      <font color="#666666">(一级分类选择后不可修改)</font> </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">排序</td>
      <td bgcolor="#FFFFFF"><input name="myorder" type="text" id="myorder" value="<?=$r['myorder']?>" size="38"> 
        <font color="#666666"> (值越小越前面)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">是否开启</td>
      <td bgcolor="#FFFFFF"><input type="radio" name="isopen" value="1"<?=$r['isopen']==1?' checked':''?>>
        开启
        <input type="radio" name="isopen" value="0"<?=$r['isopen']==0?' checked':''?>>
      关闭</td>
    </tr>
    <tr>
      <td height="25" valign="top" bgcolor="#FFFFFF">自定义内容<br>
      <br>
      <font color="#666666">（最多255个字节）</font></td>
      <td bgcolor="#FFFFFF"><textarea name="cdiy" cols="70" rows="8" id="cdiy"><?=stripSlashes($r['cdiy'])?></textarea></td>
    </tr>
    <tr>
      <td height="25" valign="top" bgcolor="#FFFFFF">开启前台投稿：</td>
      <td bgcolor="#FFFFFF"><input name="qadd" type="checkbox" id="qadd" value="1"<?=$r['qadd']==1?' checked':''?>>
开启<font color="#666666"> (投稿地址：/e/DoInfo/AddInfo.php?mid=系统模型ID&amp;classid=栏目ID&amp;fztid=父信息表ID&amp;fzid=父信息ID&amp;fzcid=子信息分类ID) </font></td>
    </tr>
    <tr> 
      <td height="25" colspan="2">页面设置</td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">页面显示模式</td>
      <td height="25" bgcolor="#FFFFFF"> <input type="radio" name="islist" value="0"<?=$r['islist']==0?' checked':''?>>
        封面式
          <input type="radio" name="islist" value="1"<?=$r['islist']==1?' checked':''?>>
        列表式 
        <input type="radio" name="islist" value="2"<?=$r['islist']==2?' checked':''?> onClick="bdinfo.style.display='';">
绑定信息
<input name="oldislist" type="hidden" id="oldislist" value="<?=$r['islist']?>"></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF"><font color="#666666">说明：封面式要选择封面模板、列表式要选择列表模板、绑定信息要设置信息ID</font></td>
    </tr>
	<?php
	$bdinfostyle=$r['islist']==3?'':' style="display:none"';
	?>
    <tr id="bdinfo" bgcolor="#FFFFFF"<?=$bdinfostyle?>>
      <td height="25" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" bgcolor="#FFFFFF">绑定信息ID：
        <input name="bdinfoid" type="text" id="bdinfoid" value="<?=$r['bdinfoid']?>">
        <a href="#empirecms" onClick="EditBdInfo(document.form1);">[修改信息]</a> <font color="#666666">(格式：栏目ID,信息ID)</font></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF">封面模板</td>
      <td height="25" bgcolor="#FFFFFF"><select name="classtempid">
        <?=$classtemp?>
      </select>
        <input type="button" name="Submit6223" value="管理封面模板" onClick="window.open('../template/ListClasstemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>');"></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">所用列表模板</td>
      <td height="25" bgcolor="#FFFFFF"> <select name="listtempid" id="listtempid">
          <?=$listtemp_options?>
        </select> <input type="button" name="Submit622" value="管理列表模板" onClick="window.open('../template/ListListtemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>');">      </td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">列表式页面排序方式</td>
      <td height="25" bgcolor="#FFFFFF"> 
        <select name="sreorder" id="sreorder">
          <option value="0"<?=$r['reorder']=='newstime DESC'?' selected':''?>>按发布时间降序排序</option>
          <option value="1"<?=$r['reorder']=='newstime ASC'?' selected':''?>>按发布时间升序排序</option>
        </select></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">每页显示记录数</td>
      <td height="25" bgcolor="#FFFFFF"><input name="lencord" type="text" id="lencord" value="<?=$r['lencord']?>" size="38">
        条记录</td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align="center"></div></td>
      <td bgcolor="#FFFFFF"> <input type="submit" name="Submit" value="提交"> &nbsp;&nbsp; 
        <input type="reset" name="Submit2" value="重置"> </td>
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