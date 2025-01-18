<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
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
CheckLevel($logininid,$loginin,$classid,"public");

//设置更多系统参数
function eSetEcmsMore($add,$userid,$username){
	global $empire,$dbtbpre;
	CheckLevel($userid,$username,$classid,"public");//验证权限
	//参数
	$eaopenpage=(int)$add['eaopenpage'];
	$eackrnd=hRepPostStr($add['eackrnd'],1,1);
	$eacktime=(int)$add['eacktime'];
	$drretype=(int)$add['drretype'];
	$drrepage=hRepPostStr($add['drrepage'],1,1);
	$chlisttemp=(int)$add['chlisttemp'];
	$nchlisttemp=RepPostVar($add['nchlisttemp']);
	$chnewstemp=(int)$add['chnewstemp'];
	$nchnewstemp=RepPostVar($add['nchnewstemp']);
	$chclasstemp=(int)$add['chclasstemp'];
	$nchclasstemp=RepPostVar($add['nchclasstemp']);
	if($nchlisttemp)
	{
		$nchlisttemp=','.$nchlisttemp.',';
	}
	if($nchnewstemp)
	{
		$nchnewstemp=','.$nchnewstemp.',';
	}
	if($nchclasstemp)
	{
		$nchclasstemp=','.$nchclasstemp.',';
	}
	$openbqr=(int)$add['openbqr'];
	$openfzpage=(int)$add['openfzpage'];
	//同步ID模板组
	$sametgids='';
	$sametgidck=$add['sametgidck'];
	$sametgidck=eCheckEmptyArray($sametgidck);
	$sametgidcount=count($sametgidck);
	if($sametgidcount)
	{
		$sametgids=',';
		for($stgidi=0;$stgidi<$sametgidcount;$stgidi++)
		{
			$sametgidck[$stgidi]=(int)$sametgidck[$stgidi];
			$sametgids.=$sametgidck[$stgidi].',';
		}
	}
	$sametgids=hRepPostStr($sametgids,1,1);
	//同步ID操作
	$sametgdo='';
	$sametgdock=$add['sametgdock'];
	$sametgdock=eCheckEmptyArray($sametgdock);
	$sametgdocount=count($sametgdock);
	if($sametgdocount)
	{
		$sametgdo=',';
		for($stgi=0;$stgi<$sametgdocount;$stgi++)
		{
			$sametgdo.=$sametgdock[$stgi].',';
		}
	}
	$sametgdo=hRepPostStr($sametgdo,1,1);
	//处理
	$sql=$empire->query("update {$dbtbpre}enewspublicadd set eaopenpage='$eaopenpage',eackrnd='".addslashes($eackrnd)."',eacktime='$eacktime',drretype='$drretype',drrepage='".addslashes($drrepage)."',chlisttemp='$chlisttemp',nchlisttemp='$nchlisttemp',chnewstemp='$chnewstemp',nchnewstemp='$nchnewstemp',chclasstemp='$chclasstemp',nchclasstemp='$nchclasstemp',openbqr='$openbqr',sametgids='$sametgids',sametgdo='$sametgdo'".do_dblimit_upone());
	//父子信息设置
	$sql2=$empire->query("update {$dbtbpre}enewsfz_set set openfzpage='$openfzpage'".do_dblimit_upone());
	if($sql)
	{
		GetConfig();
		//操作日志
		insert_dolog("");
		printerror("SetEcmsMoreSuccess","SetEcmsMore.php".hReturnEcmsHashStrHref2(1));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//更新天天随机验证字符
function eDoDayUpRndMust($add,$userid,$username){
	global $empire,$dbtbpre,$ecms_config,$public_r;
	EcmsConfigDayUpRnd('mustdore');
	//操作日志
	insert_dolog("");
	printerror("DoDayUpRndMustSuccess",EcmsGetReturnUrl());
}


$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
if($enews=="SetEcmsMore")//设置参数
{
	eSetEcmsMore($_POST,$logininid,$loginin);
}
elseif($enews=="DoDayUpRndMust")//更新天天随机验证字符
{
	eDoDayUpRndMust($_GET,$logininid,$loginin);
}
else
{}

$r=$empire->fetch1("select * from {$dbtbpre}enewspublicadd".do_dblimit_one());

$fzpr=$empire->fetch1("select * from {$dbtbpre}enewsfz_set".do_dblimit_one());

if($r['nchclasstemp'])
{
	$r['nchclasstemp']=substr($r['nchclasstemp'],1,-1);
}
if($r['nchlisttemp'])
{
	$r['nchlisttemp']=substr($r['nchlisttemp'],1,-1);
}
if($r['nchnewstemp'])
{
	$r['nchnewstemp']=substr($r['nchnewstemp'],1,-1);
}

//模板组
$tempgroups='';
$tgbr='';
$tgi=0;
$tgsql=$empire->query("select gid,gname,isdefault from {$dbtbpre}enewstempgroup order by gid");
while($tgr=$empire->fetch($tgsql))
{
	$tgi++;
	if($tgi%6==0)
	{
		$tgbr='<br>';
	}
	else
	{
		$tgbr='';
	}
	$tgchecked='';
	if(strstr($r['sametgids'],','.$tgr['gid'].','))
	{
		$tgchecked=' checked';
	}
	if($tgr['isdefault'])
	{
		$tgr['gname']='<b>'.$tgr['gname'].'</b>';
	}
	$tempgroups.="<input type=checkbox name=sametgidck[] value='".$tgr['gid']."'".$tgchecked.">".$tgr['gname']."&nbsp;".$tgbr;
}

//当前使用的模板组
$thegid=GetDoTempGid();

//formhash
$efh=heformhash_get('SetEcmsMore');
$efh1=heformhash_get('DoDayUpRndMust',1);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>更多系统参数设置</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function ShowDrretypeSet(obj,val){
	if(val==2)
	{
		drrepagediv.style.display="";
	}
	else
	{
		drrepagediv.style.display="none";
	}
}
</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="32"><p>位置：<a href="SetEcmsMore.php<?=$ecms_hashur['whehref']?>">更多系统参数设置</a></p>
    </td>
  </tr>
</table>
<form name="setpublic" method="post" action="SetEcmsMore.php" onSubmit="return confirm('确认设置?');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25" colspan="2">更多系统参数设置 
        <input name="enews" type="hidden" value="SetEcmsMore"></td>
    </tr>
	<tr>
	  <td height="25" colspan="2">天天随机验证字符更新设置</td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td width="18%" height="25">触发条件：</td>
	  <td width="82%"><select name="drretype" id="drretype" onChange="ShowDrretypeSet(document.setpublic,this.options[this.selectedIndex].value)">
	    <option value="0"<?=$r['drretype']==0?' selected':''?>>手动</option>
	    <option value="1"<?=$r['drretype']==1?' selected':''?>>每天自动</option>
	    <option value="2"<?=$r['drretype']==2?' selected':''?>>按访问地址包含</option>
	    </select>
	    （当选手动更新时，可<strong><a href="SetEcmsMore.php?enews=DoDayUpRndMust<?=$ecms_hashur['href'].$efh1?>" onClick="return confirm('确认要更新？');"><u>点击这里</u></a></strong>进行立即更新）	  </td>
    </tr>
	<tr bgcolor="#FFFFFF" id="drrepagediv" style="display:none">
	  <td height="25">访问地址包含触发：</td>
	  <td><input name="drrepage" type="text" id="drrepage" value="<?=$r['drrepage']?>" size="38">
	    <font color="#666666">(访问地址包含这个内容时触发更新，文件名或参数包含均可)</font></td>
    </tr>
	<tr>
	  <td height="25" colspan="2">附加验证参数设置</td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">开启附加页面：</td>
	  <td><input type="radio" name="eaopenpage" value="1"<?=$r['eaopenpage']==1?' checked':''?>>
	    开启
	      <input type="radio" name="eaopenpage" value="0"<?=$r['eaopenpage']==0?' checked':''?>> 
	      关闭</td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">附加验证随机码：</td>
	  <td><input name="eackrnd" type="text" id="eackrnd" value="<?=$r['eackrnd']?>" size="38">
	    <font color="#666666">
	    <input type="button" name="Submit3222" value="随机" onClick="document.setpublic.eackrnd.value='<?=make_password(62)?>';">
      (填写10~100个任意字符，最好多种字符组合)</font></td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">附加验证过期时间：</td>
	  <td><input name="eacktime" type="text" id="eacktime" value="<?=$r['eacktime']?>" size="38">
	    秒</td>
    </tr>
    <tr>
	  <td height="25" colspan="2">父子信息参数设置</td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">开启前台子信息列表页：</td>
	  <td><input type="radio" name="openfzpage" value="1"<?=$fzpr['openfzpage']==1?' checked':''?>>
	    开启
	      <input type="radio" name="openfzpage" value="0"<?=$fzpr['openfzpage']==0?' checked':''?>> 
	      关闭</td>
    </tr>
	<tr>
	  <td height="25" colspan="2">页面多模板设置</td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">开启栏目封面多模板：</td>
	  <td><select name="chclasstemp" id="chclasstemp">
        <option value="0"<?=$r['chclasstemp']==0?' selected':''?>>关闭</option>
        <option value="1"<?=$r['chclasstemp']==1?' selected':''?>>开启(不限模板)</option>
      </select>
        <font color="#666666"> (开启后，指定模板：/e/action/ListInfo/?ctempid=封面模板ID......)  </font></td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">禁用栏目封面多模板ID：</td>
	  <td><input name="nchclasstemp" type="text" id="nchclasstemp" value="<?=$r['nchclasstemp']?>" size="38">
        <input type="button" name="Submit62223" value="管理封面模板" onClick="window.open('../template/ListClasstemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>');">
        <font color="#666666">(多个ID用半角逗号“,”隔开)</font></td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">开启栏目列表多模板：</td>
	  <td><select name="chlisttemp" id="chlisttemp">
	    <option value="0"<?=$r['chlisttemp']==0?' selected':''?>>关闭</option>
		<option value="1"<?=$r['chlisttemp']==1?' selected':''?>>开启(不限模板)</option>
	    <option value="2"<?=$r['chlisttemp']==2?' selected':''?>>开启(允许相同系统模型的模板)</option>
	    <option value="3"<?=$r['chlisttemp']==3?' selected':''?>>开启(允许相同数据表的模板)</option>
	    </select>
      <font color="#666666">	    (开启后，指定模板：/e/action/ListInfo/?tempid=列表模板ID......)      </font></td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">禁用栏目列表多模板ID：</td>
	  <td><input name="nchlisttemp" type="text" id="nchlisttemp" value="<?=$r['nchlisttemp']?>" size="38">
        <input type="button" name="Submit6222" value="管理列表模板" onClick="window.open('../template/ListListtemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>');">
        <font color="#666666">(多个ID用半角逗号“,”隔开)</font></td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">开启栏目内容多模板：</td>
	  <td><select name="chnewstemp" id="chnewstemp">
        <option value="0"<?=$r['chnewstemp']==0?' selected':''?>>关闭</option>
        <option value="1"<?=$r['chnewstemp']==1?' selected':''?>>开启(不限模板)</option>
        <option value="2"<?=$r['chnewstemp']==2?' selected':''?>>开启(允许相同系统模型的模板)</option>
        <option value="3"<?=$r['chnewstemp']==3?' selected':''?>>开启(允许相同数据表的模板)</option>
      </select>
        <font color="#666666"> (开启后，指定模板：/e/action/ShowInfo.php?tempid=内容模板ID......) </font></td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">禁用栏目内容多模板ID：</td>
	  <td><input name="nchnewstemp" type="text" id="nchnewstemp" value="<?=$r['nchnewstemp']?>" size="38">
        <input type="button" name="Submit62222" value="管理内容模板" onClick="window.open('../template/ListNewstemp.php?gid=<?=$thegid?><?=$ecms_hashur['ehref']?>');">
        <font color="#666666">(多个ID用半角逗号“,”隔开)</font></td>
    </tr>
	<tr>
	  <td height="25" colspan="2">模板相关参数设置</td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">开启数组参数标签：</td>
	  <td><select name="openbqr" id="openbqr">
	    <option value="0"<?=$r['openbqr']==0?' selected':''?>>不开启</option>
	    <option value="1"<?=$r['openbqr']==1?' selected':''?>>开启数组参数灵动标签(e:loopr)+索引灵动标签(e:indexloopr)</option>
	    <option value="2"<?=$r['openbqr']==2?' selected':''?>>仅开启数组参数灵动标签(e:loopr)</option>
	    <option value="3"<?=$r['openbqr']==3?' selected':''?>>仅开启数组参数索引灵动标签(e:indexloopr)</option>
	    </select>	  </td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">同步模板ID的模板组：</td>
	  <td><?=$tempgroups?></td>
    </tr>
	<tr bgcolor="#FFFFFF">
	  <td height="25">同步模板ID的操作：</td>
	  <td>
	  <input name="sametgdock[]" type="checkbox" id="sametgdock[]" value="add"<?=strstr($r['sametgdo'],',add,')?' checked':''?>>增加模板
      <input name="sametgdock[]" type="checkbox" id="sametgdock[]" value="del"<?=strstr($r['sametgdo'],',del,')?' checked':''?>>删除模板
      <input name="sametgdock[]" type="checkbox" id="sametgdock[]" value="editid"<?=strstr($r['sametgdo'],',editid,')?' checked':''?>>修改模板ID</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="30">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value=" 提 交 "> &nbsp;&nbsp;&nbsp;&nbsp; <input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>