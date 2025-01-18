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

$fzclassid=(int)$_GET['fzclassid'];
$fzid=(int)$_GET['fzid'];
$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
$tbname=$class_r[$fzclassid]['tbname'];

//验证权限
CheckLevel($logininid,$loginin,$classid,"fzdata");

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

//子信息
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$line=intval($public_r['hlistinfonum']);//每页显示
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$add='';
$search='&fzclassid='.$fzclassid.'&fzid='.$fzid.$ecms_hashur['ehref'];
//审核表
$addecmscheck='';
$ecmscheck=(int)$_GET['ecmscheck'];
$indexchecked=1;
$fzdatatb=$dbtbpre.'enewsfz_data_'.$fzinfor['fzstb'];
if($ecmscheck)
{
	$search.='&ecmscheck='.$ecmscheck;
	$addecmscheck='&ecmscheck='.$ecmscheck;
	$indexchecked=0;
	$fzdatatb=$dbtbpre.'enewsfz_data_check';
}
//信息ID
$keyboard=RepPostVar($_GET['keyboard']);
if($keyboard)
{
	if(strlen($keyboard)>12)
	{
		$add.=" and pubid='$keyboard'";
	}
	else
	{
		$add.=" and id='$keyboard'";
	}
	$search.='&keyboard='.$keyboard;
}
//子类
$ckcid=0;
$cid=0;
if(strstr($_GET['cid'],'b'))//一级
{
	$cid=str_replace($_GET['cid'],'b','');
	$cid=(int)$cid;
	if($cid)
	{
		$add.=" and bcid='$cid'";
		$search.='&cid=b'.$cid;
		$ckcid='b'.$cid;
	}
}
else//二级
{
	$cid=(int)$_GET['cid'];
	if($cid)
	{
		$add.=" and cid='$cid'";
		$search.='&cid='.$cid;
		$ckcid=$cid;
	}
}
//系统模型
$mid=(int)$_GET['mid'];
if($mid)
{
	$add.=" and mid='$mid'";
	$search.='&mid='.$mid;
}
//栏目
$classid=(int)$_GET['classid'];
if($classid)
{
	$add.=' and '.($class_r[$classid]['islast']?"classid='$classid'":"(".ReturnClass($class_r[$classid]['sonclass']).")");
	$search.='&classid='.$classid;
}
//推荐
$isgood=(int)$_GET['isgood'];
if($isgood)
{
	$add.=$isgood==-1?" and isgood>0":" and isgood='".$isgood."'";
	$search.='&isgood='.$isgood;
}
//头条
$firsttitle=(int)$_GET['firsttitle'];
if($firsttitle)
{
	$add.=$firsttitle==-1?" and firsttitle>0":" and firsttitle='".$firsttitle."'";
	$search.='&firsttitle='.$firsttitle;
}
//排序
$myorder=(int)$_GET['myorder'];
$search.='&myorder='.$myorder;
if($myorder==1)
{
	$doorder='newstime desc';
}
elseif($myorder==2)
{
	$doorder='newstime asc';
}
else
{
	$doorder='newstime desc';
}
$totalquery="select count(*) as total from ".$fzdatatb." where bpubid='$fzpubid'".$add;
$num=$empire->gettotal($totalquery);
$query="select * from ".$fzdatatb." where bpubid='$fzpubid'".$add;
$query=$query." order by ".$doorder."".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//子类
$csql=$empire->query("select cid,bcid,cname from {$dbtbpre}enewsfz_class where pubid='$fzpubid' order by bcid desc,myorder");
$selfpagecache_cr=array();
$bcr=array();
$movebcr=array();
$fzdatacs='';
$movefzdatacs='';
while($cr=$empire->fetch($csql))
{
	$thiscid=$cr['bcid']==0?'b'.$cr['cid']:$cr['cid'];
	$selected='';
	if($thiscid==$ckcid)
	{
		$selected=' selected';
	}
	$selfpagecache_cr[$cr['cid']]['cname']=$cr['cname'];
	
	if(empty($cr['bcid']))//一级
	{
		$fzdatacs.="<option value='b".$cr['cid']."'".$selected.">".$cr['cname']."</option>".$bcr[$cr['cid']];
		$movefzdatacs.="<option value='b".$cr['cid']."'>".$cr['cname']."</option>".$movebcr[$cr['cid']];
	}
	else//二级
	{
		$bcr[$cr['bcid']].="<option value='".$cr['cid']."'".$selected."> |-".$cr['cname']."</option>";
		$movebcr[$cr['bcid']].="<option value='".$cr['cid']."'> |-".$cr['cname']."</option>";
	}
}
//系统模型
$selfpagecache_modr=array();
$mod_options='';
$m_sql=$empire->query("select mid,mname,usemod from {$dbtbpre}enewsmod order by myorder,mid");
while($m_r=$empire->fetch($m_sql))
{
	//$selfpagecache_modr[$m_r['mid']]['mname']=$m_r['mname'];
	if(empty($m_r['usemod']))
	{
		if($m_r['mid']==$mid)
		{$m_d=" selected";}
		else
		{$m_d="";}
		$mod_options.="<option value=".$m_r['mid'].$m_d.">".$m_r['mname']."</option>";
	}
}

//formhash
$efhr=heformhash_getr('DoGoodFzData');
$efhr1=heformhash_getr('DoFirsttitleFzData');
$efhr2=heformhash_getr('DoDelFzData');
$efhr3=heformhash_getr('DoEditFzDataTime');
$efhr4=heformhash_getr('DoMoveFzData');
if($efhr['vname']!=$efhr1['vname'])
{
	$efhr['vform'].=$efhr1['vform'].$efhr2['vform'].$efhr3['vform'].$efhr4['vform'];
}

$efh=heformhash_get('DelFzinfo',1);
$efh1=heformhash_get('OneClearFzinfo',1);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>父信息：<?=$infor['title']?> - 管理子信息</title>
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
  
function DoDelThisFzinfo()
{
	var ok;
	var oktwo;
	var okthree;
	ok=confirm("确认要取消此父信息?");
	if(ok==false)
	{
		return false;
	}
	oktwo=confirm("再次确认要取消此父信息?");
	if(oktwo==false)
	{
		return false;
	}
	okthree=confirm("最后确认要取消此父信息?");
	if(okthree==false)
	{
		return false;
	}
	if(ok&&oktwo&&okthree)
	{
		self.location.href='ecmsfzinfo.php?enews=DelFzinfo&fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?><?=$ecms_hashur['href'].$efh?>';
	}
}

function DoClearThisFzinfo()
{
	var ok;
	ok=confirm("确认要整理当前父子信息数据?");
	if(ok==false)
	{
		return false;
	}
	if(ok)
	{
		self.location.href='ecmsfzinfo.php?enews=OneClearFzinfo&fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?><?=$ecms_hashur['href'].$efh1?>';
	}
}

function DoAddInfoFzData(){
	var addclassid;
	var addcid;
	
	addclassid=document.getElementById('esnav_addclassid').value;
	if(addclassid==0)
	{
		alert('请选择要增加信息的栏目');
		document.getElementById('esnav_addclassid').focus();
		return false;
	}
	addcid=document.SearchForm.fzcid.value;
	window.open('../AddNews.php?<?=$ecms_hashur['ehref']?>&enews=AddNews<?=$addecmscheck?>&classid='+addclassid+'&afzclassid=<?=$fzclassid?>&afzid=<?=$fzid?>&afzcid='+addcid,'','');
}

function ChangeInfoDoAction(tbname,infoids){
	if(tbname==''||infoids=='')
	{
		return false;
	}
	else
	{
		self.location.href='../fzinfo/ecmsfzinfo.php?<?=$ecms_hashur['href'].heformhash_get('LoadInFzData',1)?>&enews=LoadInFzData<?=$addecmscheck?>&fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?>&fzcid='+document.SearchForm.fzcid.value+'&tbname='+tbname+'&infoids='+infoids;
	}
}

</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td width="51%" height="32">位置：<a href="ListFzinfo.php<?=$ecms_hashur['whehref']?>">管理父信息</a> &gt; 父信息：<b><a href="<?=$ftitleurl?>"<?=$feagotourl_onclick?> target=_blank><?=$infor['title']?></a></b> &gt; <a href="ListFzData.php?fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>">管理子信息</a> (分表：<?=$fzinfor['fzstb']?> , 父信息ID：<?=$fzpubid?>)</td>
    <td width="49%"><div align="right" class="emenubutton">
        <input type="button" name="Submit6" value="管理子信息分类" onClick="window.open('ListFzDataClass.php?fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?><?=$ecms_hashur['ehref']?>');"> 
		&nbsp;&nbsp;
		<input type="button" name="Submit6" value="父信息设置" onClick="window.open('AddFzinfo.php?enews=EditFzinfo&classid=<?=$fzclassid?>&id=<?=$fzid?><?=$ecms_hashur['ehref']?>');"> 
		&nbsp;&nbsp;
		<input type="button" name="Submit62" value="整理当前父子信息数据" onClick="DoClearThisFzinfo();">
		&nbsp;&nbsp;
		<input type="button" name="Submit6" value="取消父信息" onClick="DoDelThisFzinfo();">
    </div></td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="SearchForm" id="SearchForm" method="GET" action="ListFzData.php" onSubmit="document.SearchForm.classid.value=document.getElementById('esnav_classid').value;">
  <?=$ecms_hashur['eform']?>
    <tr>
      <td width="90%">增加子信息：
	  <span id="showaddclassnav"></span>
        <select name="fzcid" id="fzcid">
		<option value='0'>不选子信息分类</option>
		<?=$fzdatacs?>
        </select>
        <input type="button" name="Submit" value="增加信息" onClick="DoAddInfoFzData();">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="Submit4" value="推送子信息" onClick="window.open('../info/ChangeInfo.php?enews=LoadInFzData&fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?>&fzcid='+document.SearchForm.fzcid.value+'<?=$addecmscheck?><?=$ecms_hashur['ehref']?>');">
		</td>
      <td width="10%"><div align="right">
        
      </div></td>
    </tr>
    <tr> 
      <td colspan="2"> <div align="right">
          <input name="fzclassid" type="hidden" id="fzclassid" value="<?=$fzclassid?>">
          <input name="fzid" type="hidden" id="fzid" value="<?=$fzid?>">
          <input name="ecmscheck" type="hidden" id="ecmscheck" value="<?=$ecmscheck?>">
          <a href="#ecms" title="可以是信息ID，也可以是公共信息ID">信息ID</a>：
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>" size="20">
          <select name="mid" id="mid">
            <option value="0">所有系统模型</option>
			<?=$mod_options?>
          </select>
		  <span id="listfzdataclassnav"></span>
          <select name="cid" id="cid">
            <option value="0">所有子信息分类</option>
            <?=$fzdatacs?>
          </select>
          <span id="listplclassnav"></span> 
		  <select name="isgood" id="isgood">
            <option value="0"<?=$isgood==0?' selected':''?>>不限是否推荐</option>
			<option value="-1"<?=$isgood==-1?' selected':''?>>所有推荐</option>
            <option value="1"<?=$isgood==1?' selected':''?>>1级推荐</option>
            <option value="2"<?=$isgood==2?' selected':''?>>2级推荐</option>
            <option value="3"<?=$isgood==3?' selected':''?>>3级推荐</option>
            <option value="4"<?=$isgood==4?' selected':''?>>4级推荐</option>
            <option value="5"<?=$isgood==5?' selected':''?>>5级推荐</option>
            <option value="6"<?=$isgood==6?' selected':''?>>6级推荐</option>
            <option value="7"<?=$isgood==7?' selected':''?>>7级推荐</option>
            <option value="8"<?=$isgood==8?' selected':''?>>8级推荐</option>
            <option value="9"<?=$isgood==9?' selected':''?>>9级推荐</option>
          </select>
		  <select name="firsttitle" id="firsttitle">
            <option value="0"<?=$firsttitle==0?' selected':''?>>不限是否头条</option>
			<option value="-1"<?=$firsttitle==-1?' selected':''?>>所有头条</option>
            <option value="1"<?=$firsttitle==1?' selected':''?>>1级头条</option>
            <option value="2"<?=$firsttitle==2?' selected':''?>>2级头条</option>
            <option value="3"<?=$firsttitle==3?' selected':''?>>3级头条</option>
            <option value="4"<?=$firsttitle==4?' selected':''?>>4级头条</option>
            <option value="5"<?=$firsttitle==5?' selected':''?>>5级头条</option>
            <option value="6"<?=$firsttitle==6?' selected':''?>>6级头条</option>
            <option value="7"<?=$firsttitle==7?' selected':''?>>7级头条</option>
            <option value="8"<?=$firsttitle==8?' selected':''?>>8级头条</option>
            <option value="9"<?=$firsttitle==9?' selected':''?>>9级头条</option>
          </select>
          <select name="myorder" id="myorder">
            <option value="1"<?=$myorder==1?' selected':''?>>按发布时间降序排序</option>
            <option value="2"<?=$myorder==2?' selected':''?>>按发布时间升序排序</option>
          </select>
          </select>
          <input type="submit" name="Submit2" value="显示">
		  <input type="hidden" name="classid" id="classid" value="0">
        </div></td>
    </tr>
  </form>
</table>
<br>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td width="10%" height="25"<?=$indexchecked==1?' class="header"':' bgcolor="#C9F1FF"'?>><div align="center"><a href="ListFzData.php?fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?><?=$ecms_hashur['ehref']?>">已发布</a></div></td>
    <td width="10%"<?=$indexchecked==0?' class="header"':' bgcolor="#C9F1FF"'?>><div align="center"><a href="ListFzData.php?fzclassid=<?=$fzclassid?>&fzid=<?=$fzid?>&ecmscheck=1<?=$ecms_hashur['ehref']?>">待审核</a></div></td>
    <td width="10%">&nbsp;</td>
    <td width="58%">&nbsp;</td>
    <td width="6%">&nbsp;</td>
    <td width="6%">&nbsp;</td>
  </tr>
</table>
  
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="listform" method="post" action="ecmsfzinfo.php" onSubmit="return confirm('确认要执行此操作？');">
  <?=$ecms_hashur['form']?>
  <?=$efhr['vform']?>
    <tr class="header"> 
      <td width="3%"><div align="center"></div></td>
      <td width="13%"><div align="center">分类</div></td>
      <td width="6%" height="25"><div align="center">ID</div></td>
      <td width="43%" height="25"><div align="center">标题</div></td>
      <td width="28%" height="25"> <div align="center">发布时间</div></td>
      <td width="7%"><div align="center">操作</div></td>
    </tr>
    <?php
	while($zr=$empire->fetch($sql))
	{
		//子类
		$cname='';
		if($zr['bcid'])
		{
			$cname="<a href='ListFzData.php?fzclassid=$fzclassid&fzid=$fzid&cid=b".$zr['bcid'].$addecmscheck.$ecms_hashur['ehref']."'>".$selfpagecache_cr[$zr['bcid']]['cname']."</a> &gt; <br>";
		}
		if($zr['cid'])
		{
			$cname.="<a href='ListFzData.php?fzclassid=$fzclassid&fzid=$fzid&cid=".$zr['cid'].$addecmscheck.$ecms_hashur['ehref']."'>".$selfpagecache_cr[$zr['cid']]['cname']."</a>";
		}
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
		if($zr['firsttitle'])//头条
		{
			$st.="<font color=red>[头".$zr['firsttitle']."]</font>";
		}
		if($zr['isgood'])//推荐
		{
			$st.="<font color=red>[推".$zr['isgood']."]</font>";
		}
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
    <tr bgcolor="#FFFFFF" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="zid[]" type="checkbox" id="zid[]" value="<?=$zr['tid'].'.'.$zr['id']?>"<?=$checked?>>
        </div></td>
      <td><div align="center"> 
          <?=$cname?>
        </div></td>
      <td height="42"> <div align="center"> 
          <?=$myid?>
        </div></td>
      <td height="42"> <div align="left"> 
          <?=$st?>
          <?=$showtitlepic?>
          <a href='<?=$titleurl?>'<?=$eagotourl_onclick?> target=_blank title="<?=$oldtitle?>"> 
          <?=$r['title']?>
          </a>
          <br>
          <font color="#574D5C">栏目:<a href='../ListNews.php?bclassid=<?=$class_r[$r['classid']]['bclassid']?>&classid=<?=$r['classid']?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>' target="_blank"> 
          <font color="#574D5C"> 
          <?=$class_r[$dob]['classname']?>
          </font> </a> &gt; <a href='../ListNews.php?bclassid=<?=$class_r[$r['classid']]['bclassid']?>&classid=<?=$r['classid']?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>' target="_blank"> 
          <font color="#574D5C"> 
          <?=$class_r[$r['classid']]['classname']?>
          </font> </a></font></div></td>
      <td height="42"> <div align="center"> 
          <input name="dozid[]" type="hidden" id="dozid[]" value="<?=$zr['tid'].'.'.$zr['id']?>">
          <input name="newstime[]" type="text" value="<?=date("Y-m-d H:i:s",$zr['newstime'])?>" size="22">
        </div></td>
      <td><div align="center">[<a href="../AddNews.php?enews=EditNews&id=<?=$r['id']?>&classid=<?=$r['classid']?>&bclassid=<?=$class_r[$r['classid']]['bclassid']?><?=$addecmscheck?><?=$ecms_hashur['ehref']?>" title="<?php echo"增加时间：".$truetime."\r\n最后修改：".$lastdotime;?>" target="_blank">修改</a>]</div></td>
    </tr>
    <?php
	}
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"> <div align="center"> 
          <input type=checkbox name=chkall value=on onclick=CheckAll(this.form)>
        </div></td>
      <td height="25" colspan="5"> <div align="right"> 
          <input type="submit" name="Submit3" value="移除子信息" onClick="document.listform.enews.value='DoDelFzData';document.listform.<?=$efhr2['vname']?>.value='<?=$efhr2['vval']?>';document.listform.action='ecmsfzinfo.php';">&nbsp;
          <select name="firsttitle" id="firsttitle">
            <option value="0">不头条</option>
            <option value="1">1级头条</option>
            <option value="2">2级头条</option>
            <option value="3">3级头条</option>
            <option value="4">4级头条</option>
            <option value="5">5级头条</option>
            <option value="6">6级头条</option>
            <option value="7">7级头条</option>
            <option value="8">8级头条</option>
            <option value="9">9级头条</option>
          </select>
          <input type="submit" name="Submit822" value="头条" onClick="document.listform.enews.value='DoFirsttitleFzData';document.listform.doing.value='2';document.listform.<?=$efhr1['vname']?>.value='<?=$efhr1['vval']?>';document.listform.action='ecmsfzinfo.php';">&nbsp;
          <select name="isgood" id="isgood">
            <option value="0">不推荐</option>
            <option value="1">1级推荐</option>
            <option value="2">2级推荐</option>
            <option value="3">3级推荐</option>
            <option value="4">4级推荐</option>
            <option value="5">5级推荐</option>
            <option value="6">6级推荐</option>
            <option value="7">7级推荐</option>
            <option value="8">8级推荐</option>
            <option value="9">9级推荐</option>
          </select>
          <input type="submit" name="Submit82" value="推荐" onClick="document.listform.enews.value='DoGoodFzData';document.listform.doing.value='1';document.listform.<?=$efhr['vname']?>.value='<?=$efhr['vval']?>';document.listform.action='ecmsfzinfo.php';">&nbsp;
          
          <input type="submit" name="Submit8223" value="批量修改时间" onClick="document.listform.enews.value='DoEditFzDataTime';document.listform.<?=$efhr3['vname']?>.value='<?=$efhr3['vval']?>';document.listform.action='ecmsfzinfo.php';">&nbsp;
          <select name="to_cid" id="to_cid">
            <option value="">选择分类</option>
            <?=$movefzdatacs?>
          </select>
          <input type="submit" name="Submit8222" value="转移" onClick="document.listform.enews.value='DoMoveFzData';document.listform.<?=$efhr4['vname']?>.value='<?=$efhr4['vval']?>';document.listform.action='ecmsfzinfo.php';">
          <input name="enews" type="hidden" id="enews" value="DoGoodFzData">
          <input type=hidden name=doing value=0>
           </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="6"> 
        <?=$returnpage?>
        <input name="fzclassid" type="hidden" id="fzclassid" value="<?=$fzclassid?>">
		<input name="fzid" type="hidden" id="fzid" value="<?=$fzid?>">
		<input name="ecmscheck" type="hidden" id="ecmscheck" value="<?=$ecmscheck?>">
      </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25" colspan="6"><font color="#666666">说明：信息是按发布时间排序，如果要改顺序可以修改发布时间，发布时间设置空则改为当前时间。</font></td>
    </tr>
  </form>
</table>
<br>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="../ShowClassNav.php?ecms=8&classid=<?=$classid?><?=$ecms_hashur['ehref']?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
</body>
</html>
<?php
db_close();
$empire=null;
?>