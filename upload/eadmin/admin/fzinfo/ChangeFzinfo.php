<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
require '../../../e/data/'.LoadLang("pub/fun.php");
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

include("../../../e/class/fzpubfun.php");

$infoclassid=(int)$_GET['infoclassid'];
$infotid=(int)$_GET['infotid'];
$sinfo=(int)$_GET['sinfo'];
$infoid=RepPostVar($_GET['infoid']);
if(empty($sinfo)&&!$infoid)
{
	echo"<script>alert('请选择信息');window.close();</script>";
	exit();
}
$epagetodo='';
if(empty($sinfo))
{
	$epagetodo='InfoPushToFz';
}
else
{
	$infoid=(int)$infoid;
}

$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$field=RepPostVar($_GET['field']);
$form=RepPostVar($_GET['form']);
$line=50;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
//搜索
$search="&infoclassid=$infoclassid&sinfo=$sinfo&infoid=$infoid&infotid=$infotid&field=$field&form=$form".$ecms_hashur['ehref'];
$add='';
eCheckStrType(5,$field,1);
eCheckStrType(5,$form,1);

//信息ID
$mid=(int)$_GET['mid'];
$search_tbname='';
$search_infoid=0;
if($mid)
{
	$search_tbname=$emod_r[$mid]['tbname'];
}
$keyboard=RepPostVar($_GET['keyboard']);
if($keyboard)
{
	if(!eCheckStrType(1,$keyboard,0))
	{
		if($search_tbname)
		{
			$search_infor=$empire->fetch1("select id from {$dbtbpre}ecms_".$search_tbname." where title='".$keyboard."'".do_dblimit_one());
			$search_infoid=(int)$search_infor['id'];
		}
		$add.=" and id='$search_infoid'";
	}
	else
	{
		if(strlen($keyboard)>12)
		{
			$add.=" and pubid='$keyboard'";
		}
		else
		{
			$add.=" and id='$keyboard'";
		}
	}
	$search.='&keyboard='.$keyboard;
}
//分类
$cid=(int)$_GET['cid'];
if($cid)
{
	$add.=" and cid='$cid'";
	$search.="&cid=$cid";
}
//系统模型
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
if($infoclassid)
{
	$classwhere=ReturnClass($class_r[$infoclassid]['featherclass']);
	$add.=" and (sclassid=0 or sclassid='$infoclassid' or (s".$classwhere."))";
}
$add.=' and usefz=0';

//排序
$orderby=RepPostStr($_GET['orderby'],1);
if($orderby==1)//按公共ID升序排序
{$doorder='pubid asc';}
elseif($orderby==2)//按信息ID降序排序
{$doorder='id desc';}
elseif($orderby==3)//按信息ID升序排序
{$doorder='id asc';}
else//按公共ID降序排序
{$doorder='pubid desc';}
$search.="&orderby=$orderby";
$add=' where '.substr($add,5);

$totalquery="select count(*) as total from {$dbtbpre}enewsfz_info".$add;
$query="select pubid,id,classid from {$dbtbpre}enewsfz_info".$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by ".$doorder."".do_dblimit($line,$offset);
$sql=$empire->query($query);

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
//父信息分类
$cs='';
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsfz_infoclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cid==$cr['classid'])
	{
		$select=" selected";
	}
	$cs.="<option value='".$cr['classid']."'".$select.">".$cr['classname']."</option>";
}

$returnpage=page2($num,$line,$page_line,$start,$page,$search);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>选择父信息</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function iChangeFzinfo(infopubid,infoclassid,infoid,infotitle,cval){
	opener.iUpdateFzinfo(infopubid,infoclassid,infoid,infotitle,cval);
	window.close();
}

function iChangeFzinfoNc(infopubid,infoclassid,infoid,infotitle,cval){
	opener.iUpdateFzinfo(infopubid,infoclassid,infoid,infotitle,cval);
	//window.close();
}
</script>
</head>

<body>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
  <form name="searchform" id="searchform" method="GET" action="ChangeFzinfo.php" onsubmit="document.searchform.classid.value=document.getElementById('esnav_classid').value;">
  <?=$ecms_hashur['eform']?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"><a href="#ecms" title="可以是信息ID，也可以是公共信息ID">信息ID</a>：
        <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
        <select name="mid" id="mid">
          <option value="0">所有系统模型</option>
          <?=$mod_options?>
        </select>
        <select name="cid" id="cid">
          <option value="0">不限分类</option>
          <?=$cs?>
        </select>
		<span id="listplclassnav"></span>
        <select name="orderby" id="orderby">
          <option value="0"<?=$orderby==0?' selected':''?>>按公共ID降序排序</option>
          <option value="1"<?=$orderby==1?' selected':''?>>按公共ID升序排序</option>
          <option value="2"<?=$orderby==2?' selected':''?>>按信息ID降序排序</option>
          <option value="3"<?=$orderby==3?' selected':''?>>按信息ID升序排序</option>
        </select> <input type="submit" name="Submit2" value="显示">
		<input type="hidden" name="classid" id="classid" value="0">
        <input name="form" type="hidden" id="form" value="<?=$form?>">
      <input name="field" type="hidden" id="field" value="<?=$field?>">
      <input name="infoclassid" type="hidden" id="infoclassid" value="<?=$infoclassid?>">
      <input name="infoid" type="hidden" id="infoid" value="<?=$infoid?>">
	  <input name="sinfo" type="hidden" id="sinfo" value="<?=$sinfo?>">
      <input name="infotid" type="hidden" id="infotid" value="<?=$infotid?>"></td>
    </tr>
  </form>
</table>
<br>
<form name="iChangeFormFz" id="iChangeFormFz" method="post" action="ecmsfzinfo.php">
<table width="100%" border="0" cellspacing="1" cellpadding="3">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('PushInfoToEfz'); ?>
    <tr>
      <td>
	  <?php
	  if($epagetodo=='InfoPushToFz')
	  {
	  ?>
	  子信息ID：<?=$infoid?>
	  <?php
	  }
	  else
	  {
	  ?>
	  子信息：
	  <script>document.write(opener.document.add.title.value);</script>
	  <?php
	  }
	  ?>
	  </td>
    </tr>
  </table>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header">
      <td width="7%"><div align="center">ID</div></td>
      <td width="18%"><div align="center">栏目</div></td>
      <td width="44%">标题</td>
      <td width="24%"><div align="center">子信息分类</div></td>
      <td width="7%"><div align="center">选择</div></td>
    </tr>
	<?php
	$i=0;
	while($r=$empire->fetch($sql))
	{
		$tbname=$class_r[$r['classid']]['tbname'];
		if(!$tbname)
		{
			continue;
		}
		$index_r=$empire->fetch1("select checked from {$dbtbpre}ecms_".$tbname."_index where id='".$r['id']."'");
		$infotb=ReturnInfoMainTbname($tbname,$index_r['checked']);
		$infor=$empire->fetch1("select id,classid,isurl,isqf,havehtml,newstime,truetime,lastdotime,titlepic,title,titleurl from ".$infotb." where id='".$r['id']."'");
		$addecmscheck='';
		if($index_r['checked'])
		{
			$addecmscheck='&ecmscheck=1';
		}
		$oldtitle=$infor['title'];
		$title=stripSlashes($infor['title']);
		$infor['title']=sub($title,0,36,false);
		//审核
		if(empty($index_r['checked']))
		{
			$checked=" title='未审核' style='background:#99C4E3'";
			$titleurl="../ShowInfo.php?classid=".$infor['classid']."&id=".$infor['id'].$addecmscheck.$ecms_hashur['ehref'];
		}
		else
		{
			$checked="";
			$titleurl=sys_ReturnBqTitleLink($infor);
		}
		$classname=$class_r[$infor['classid']]['classname'];
		$classurl=sys_ReturnBqClassname($infor,9);
		$bclassid=$class_r[$infor['classid']]['bclassid'];
		$bclassname=$class_r[$bclassid]['classname'];
		//分类
		$fzclassr=PubReturnFzClass($r['pubid'],0,3,'');
		
		$jstitle=str_replace('|','',$title);
		$jstitle=str_replace(',','，',$jstitle);
		$jstitle=str_replace('"','“',$jstitle);
		$jstitle=str_replace("'","‘",$jstitle);
		$i++;
		if($epagetodo=='InfoPushToFz')
		{
			$thisvn='chfzc'.$i;
			$thisvid='chfzc'.$i;
			$efzidval=$infor['classid'].'|'.$infor['id'].'|0|0|';
			$jsonch=" onchange=\"document.getElementById('efzid'".$i.").value='".$infor['classid']."|".$infor['id']."|'+this.options[this.selectedIndex].value;\"";
			$chfzinfofun='';
		}
		else
		{
			$thisvn='chfzc'.$i;
			$thisvid='chfzc'.$i;
			$jsonch='';
			$chfzinfofun=" onclick=\"iChangeFzinfoNc('".$r['pubid']."','".$infor['classid']."','".$infor['id']."','".$jstitle."',document.iChangeFormFz.".$thisvn.".value);\"";
		}
	?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'">
      <td height="27"><div align="center"><a href="#empirecms" title="公共信息ID：<?=$r['pubid']?>"<?=$chfzinfofun?>><?=$r['id']?></a></div></td>
      <td><div align="center"><a href="<?=$classurl?>" target=_blank title="父栏目：<?=$bclassname?>"><?=$classname?></a></div></td>
      <td><a href='<?=$titleurl?>' target=_blank title="<?=$oldtitle?>"><?=$infor['title']?></a></td>
      <td><div align="center">
          <select name="<?=$thisvn?>" id="<?=$thisvid?>"<?=$jsonch?>>
            <option value="0|0|">不选择分类</option>
			<?=$fzclassr['chfzdatacs']?>
          </select>
      </div></td>
      <td><div align="center">
		<?php
		if($epagetodo=='InfoPushToFz')
		{
		?>
		  <input name="efzid[]" type="hidden" id="efzid<?=$i?>" value="<?=$efzidval?>">
		<?php
		}
		else
		{
		?>
          <input type="button" name="Submit" value="选择" onclick="iChangeFzinfo('<?=$r['pubid']?>','<?=$infor['classid']?>','<?=$infor['id']?>','<?=$jstitle?>',document.iChangeFormFz.<?=$thisvn?>.value);">
		<?php
		}
		?>
      </div></td>
    </tr>
	<?php
	}
	?>
    <tr>
      <td colspan="5"><?=$returnpage?></td>
    </tr>
	<?php
	if($epagetodo=='InfoPushToFz')
	{
	?>
    <tr>
      <td colspan="5"><div align="center">
        <input type="submit" name="Submit3" value="推送到父信息">
		&nbsp;&nbsp; 
        <input type="button" name="Submit3" value="取消" onclick="window.close();">
		<input name="enews" type="hidden" id="enews" value="PushInfoToEfz">
          <input name="infoclassid" type="hidden" id="infoclassid" value="<?=$infoclassid?>">
          <input name="infotid" type="hidden" id="infotid" value="<?=$infotid?>">
          <input name="infoid" type="hidden" id="infoid" value="<?=$infoid?>">
		  <input name="sinfo" type="hidden" id="sinfo" value="<?=$sinfo?>">
      </div></td>
    </tr>
	<?php
	}
	else
	{
	?>
	<tr bgcolor="#FFFFFF">
      <td height="25" colspan="6"><font color="#666666">说明：如果要选择父信息不关窗口可以点击ID进行选择。</font></td>
    </tr>
	<?php
	}
	?>
  </table>
</form>
<br>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="../ShowClassNav.php?ecms=6&classid=<?=$classid?><?=$ecms_hashur['ehref']?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
</body>
</html>
<?php
db_close();
$empire=null;
?>