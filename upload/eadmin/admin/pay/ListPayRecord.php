<?php
define('EmpireCMSAdmin','1');
require("../../../e/class/connect.php");
require("../../../e/class/functions.php");
require "../../../e/data/".LoadLang("pub/fun.php");
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
CheckLevel($logininid,$loginin,$classid,"pay");

//批量删除
function DelPayRecord_all($id,$st,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"pay");
	$st=(int)$st;
	$id=eCheckEmptyArray($id);
	$count=count($id);
	if(!$count)
	{
		printerror("NotDelPayRecordid","history.go(-1)");
	}
	$add='';
	for($i=0;$i<$count;$i++)
	{
		$add.=" id='".intval($id[$i])."' or";
	}
	$add=substr($add,0,strlen($add)-3);
	if($st==1)
	{
		$tb='enewspayrecordst';
	}
	else
	{
		$tb='enewspayrecord';
	}
	$sql=$empire->query("delete from ".$dbtbpre.$tb." where".$add);
	if($sql)
	{
		//操作日志
		insert_dolog("st=$st");
		printerror("DelPayRecordSuccess","ListPayRecord.php?st=".$st.hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	hCheckEcmsRHash();
}
//批量删除
if($enews=="DelPayRecord_all")
{
	$id=$_POST['id'];
	$st=$_POST['st'];
	DelPayRecord_all($id,$st,$logininid,$loginin);
}

//参数
$search='';
$search.=$ecms_hashur['ehref'];
$st=(int)$_GET['st'];
if($st==1)
{
	$search.="&st=1";
	$tb='enewspayrecordst';
}
else
{
	$tb='enewspayrecord';
}

$line=25;//每页显示条数
$page_line=18;//每页显示链接数
$page=(int)$_GET['page'];
$page=RepPIntvar($page);
$start=0;
$offset=$page*$line;//总偏移量
$query="select id,userid,username,orderid,money,posttime,paytype,payip,paydo,payfor,payddno,endtime,mpid from ".$dbtbpre.$tb;
$totalquery="select count(*) as total from ".$dbtbpre.$tb;
//搜索
$where='';
if($_GET['sear']==1)
{
	$search.="&sear=1";
	$a='';
	$startday=RepPostVar($_GET['startday']);
	$endday=RepPostVar($_GET['endday']);
	if($startday&&$endday)
	{
		$search.="&startday=$startday&endday=$endday";
		if($st==1)
		{
			$a.="posttime<='".$endday." 23:59:59' and posttime>='".$startday." 00:00:00'";
		}
		else
		{
			$a.="endtime<='".$endday." 23:59:59' and endtime>='".$startday." 00:00:00'";
		}
	}
	$keyboard=RepPostVar($_GET['keyboard']);
	if($keyboard)
	{
		$and=$a?' and ':'';
		$show=RepPostStr($_GET['show'],1);
		if($show==1)
		{
			$a.=$and."payddno like '%$keyboard%'";
		}
		elseif($show==2)
		{
			$a.=$and."username like '%$keyboard%'";
		}
		elseif($show==3)
		{
			$a.=$and."payip like '%$keyboard%'";
		}
		elseif($show==4)
		{
			$a.=$and."paydo like '%$keyboard%'";
		}
		elseif($show==5)
		{
			$a.=$and."payfor like '%$keyboard%'";
		}
		elseif($show==6)
		{
			$a.=$and."paybz like '%$keyboard%'";
		}
		else
		{
			$a.=$and."orderid like '%$keyboard%'";
		}
		$search.="&keyboard=$keyboard&show=$show";
	}
	if($a)
	{
		$where.=" where ".$a;
	}
	$query.=$where;
	$totalquery.=$where;
}
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by id desc".do_dblimit($line,$offset);
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<html>
<head>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css"> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>支付记录</title>
<script type="text/javascript" src="../ecmseditor/js/jstime/WdatePicker.js"></script>
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
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td width="48%" height="32">位置：在线支付&gt; 
	<?php
	if($st==1)
	{
		echo'<a href="ListPayRecord.php?st=1'.$ecms_hashur['ehref'].'">管理待支付记录</a>';
	}
	else
	{
		echo'<a href="ListPayRecord.php'.$ecms_hashur['whehref'].'">管理支付成功记录</a>';
	}
	?>
	</td>
    <td width="52%"><div align="right" class="emenubutton">
	<?php
	if($st==1)
	{
	?>
	<input type="button" name="Submit5" value="管理支付成功记录" onclick="self.location.href='ListPayRecord.php<?=$ecms_hashur['whehref']?>';">
	<?php
	}
	else
	{
	?>
	<input type="button" name="Submit5" value="管理待支付记录" onclick="self.location.href='ListPayRecord.php?st=1<?=$ecms_hashur['ehref']?>';">
	<?php
	}
	?>
		&nbsp;&nbsp;
        <input type="button" name="Submit5" value="管理支付接口" onclick="self.location.href='PayApi.php<?=$ecms_hashur['whehref']?>';">
		&nbsp;&nbsp;
		<input type="button" name="Submit5" value="支付参数设置" onclick="self.location.href='SetPayFen.php<?=$ecms_hashur['whehref']?>';">
    </div></td>
  </tr>
</table>
  
<br>
<table width="100%" align=center cellpadding=0 cellspacing=0>
  <form name=searchlogform method=get action='ListPayRecord.php'>
  <?=$ecms_hashur['eform']?>
    <tr> 
      <td height="25"> <div align="center">时间从 
          <input name="startday" type="text" value="<?=$startday?>" size="15" class="Wdate" onClick="WdatePicker({skin:'default',dateFmt:'yyyy-MM-dd'})">
          到 
          <input name="endday" type="text" value="<?=$endday?>" size="15" class="Wdate" onClick="WdatePicker({skin:'default',dateFmt:'yyyy-MM-dd'})">
          ，关键字： 
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="show" id="show">
            <option value="0"<?=$show==0?' selected':''?>>支付订单号</option>
			<option value="1"<?=$show==1?' selected':''?>>网站订单号</option>
            <option value="2"<?=$show==2?' selected':''?>>支付者</option>
            <option value="3"<?=$show==3?' selected':''?>>支付IP</option>
			<option value="4"<?=$show==4?' selected':''?>>操作事件</option>
			<option value="5"<?=$show==5?' selected':''?>>操作内容</option>
			<option value="6"<?=$show==6?' selected':''?>>备注</option>
          </select>
          <input name=submit1 type=submit id="submit12" value=搜索>
          <input name="sear" type="hidden" id="sear" value="1">
          <input name="st" type="hidden" id="st" value="<?=$st?>">
      </div></td>
    </tr>
  </form>
</table>
<form name="form2" method="post" action="ListPayRecord.php" onsubmit="return confirm('确认要删除?');">
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="tableborder">
  <?=$ecms_hashur['form']?>
  <?php echo heformhash_get('DelPayRecord_all'); ?>
    <tr class="header"> 
      <td width="3%"><div align="center"> 
          <input type=checkbox name=chkall value=on onClick="CheckAll(this.form)">
        </div></td>
      <td width="11%"><div align="center">接口</div></td>
      <td width="24%"><div align="center">订单号</div></td>
      <td width="13%"><div align="center">支付者</div></td>
      <td width="8%" height="25"><div align="center">金额</div></td>
      <td width="17%"><div align="center">时间</div></td>
      <td width="18%" height="25"><div align="center">操作</div></td>
      <td width="6%" height="25"><div align="center"></div></td>
    </tr>
    <?php
  while($r=$empire->fetch($sql))
  {
  	if($r['userid'])
	{
		$username="<a href='../member/AddMember.php?enews=EditMember&userid=".$r['userid'].$ecms_hashur['ehref']."' target=_blank>".$r['username']."</a>";
	}
	else
	{
		$username="游客(".$r['username'].")";
	}
  ?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#C3EFFF'"> 
      <td><div align="center"> 
          <input name="id[]" type="checkbox" id="id[]" value="<?=$r['id']?>">
        </div></td>
      <td><div align="center">
        <?=$r['paytype']?>
		<br>[<?=eReturnMPname($r['mpid'])?>]
      </div></td>
      <td>
        网站：
        <?=$r['payddno']?>
        <br>
        支付：
		<?=$st==1?'待支付':$r['orderid']?>        </td>
      <td><div align="center"> 
          <?=$username?>
        </div></td>
      <td height="25"><div align="center"> 
          <?=$r['money']?>
        </div></td>
      <td> 
        提交：<?=$r['posttime']?>
	    <br>支付：<?=$st==1?'待支付':$r['endtime']?>      </td>
      <td height="25"> 
        事件：<?=$r['paydo']?>
        <br>
	    内容：<?=$r['payfor']?>        </td>
      <td height="25"><div align="center"><a href="#ecms" onclick="window.open('ShowPayRecord.php?id=<?=$r['id']?>&st=<?=$st?><?=$ecms_hashur['ehref']?>','','width=650,height=600,scrollbars=yes,top=70,left=100');">详情</a></div></td>
    </tr>
    <?php
  }
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="8">&nbsp;
        <?=$returnpage?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="Submit" value="批量删除"> <input name="enews" type="hidden" id="enews" value="DelPayRecord_all">
        <input name="st" type="hidden" id="st" value="<?=$st?>"></td>
    </tr>
  </table>
</form>
<br>
<?php
db_close();
$empire=null;
?>
</body>
</html>