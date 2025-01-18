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
CheckLevel($logininid,$loginin,$classid,"dtuserpage");

$enews=ehtmlspecialchars($_GET['enews']);
$fcid=(int)$_GET['fcid'];
$aid=0;
$word='增加自定义动态页面';
$url="".$word;
$r['maxpage']=0;

if($enews=='EditDtUserpage')
{
	$enews='EditDtUserpage';
}
else
{
	$enews='AddDtUserpage';
}
//formhash
$efh=heformhash_get($enews);
eCheckStrType(4,$enews,1);
//复制
if($enews=="AddDtUserpage"&&$_GET['docopy'])
{
	$aid=(int)$_GET['aid'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsdtuserpage where aid='$aid'");
	$url="复制自定义动态页面：<b>".$r['aname']."</b>";
}
//修改
if($enews=="EditDtUserpage")
{
	$word='修改自定义动态页面';
	$aid=(int)$_GET['aid'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsdtuserpage where aid='$aid'");
	$url="修改自定义动态页面：<b>".$r['aname']."</b>";
}

//分类
$cstr="";
$csql=$empire->query("select cid,cname from {$dbtbpre}enewsdtuserpageclass order by myorder,cid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr['cid']==$r['cid'])
	{
		$select=" selected";
	}
	$cstr.="<option value='".$cr['cid']."'".$select.">".$cr['cname']."</option>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$word?></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<SCRIPT lanuage="JScript">
<!--
function tempturnit(ss)
{
 if (ss.style.display=="") 
  ss.style.display="none";
 else
  ss.style.display=""; 
}
-->
</SCRIPT>
<script>
function ReTempBak(){
	self.location.reload();
}
</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td height="25">位置：<a href="ListDtUserpage.php?cid=<?=$fcid?><?=$ecms_hashur['ehref']?>">管理自定义动态页面</a> &gt; <?=$url?></td>
  </tr>
</table>
<br>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="form1" method="post" action="ListDtUserpage.php">
  <?=$ecms_hashur['form']?>
  <?php echo $efh; ?>
    <tr class="header"> 
      <td height="25" colspan="2">增加自定义动态页面 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> 
        <input name="aid" type="hidden" id="aid" value="<?=$aid?>">      <input name="fcid" type="hidden" id="fcid" value="<?=$fcid?>"></td>
    </tr>
    <tr>
      <td height="25" colspan="2">基本属性</td>
    </tr>
	<?php
	if($enews=='EditDtUserpage')
	{
	?>
    <tr bgcolor="#FFFFFF">
      <td height="25">ID：</td>
      <td height="25"><?=$r['aid']?></td>
    </tr>
	<?php
	}
	?>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" height="25">页面名称：</td>
      <td width="81%" height="25"> <input name="aname" type="text" id="aname" value="<?=$r['aname']?>">      </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">所属分类：</td>
      <td height="25"><select name="cid" id="cid">
        <option value="0">不分类</option>
		<?=$cstr?>
      </select>      </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">页面标识：</td>
      <td height="25"><input name="avar" type="text" id="avar" value="<?=$r['avar']?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">页面标识ID：</td>
      <td height="25"><input name="avarid" type="text" id="avarid" value="<?=$r['avarid']?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">访问密码：</td>
      <td height="25"><input name="apass" type="text" id="apass" value="<?=$r['apass']?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">是否开启：</td>
      <td height="25"><input name="isopen" type="checkbox" id="isopen" value="1"<?=$r['isopen']==1?" checked":""?>>
      开启</td>
    </tr>
    <tr>
      <td height="25" colspan="2">页面设置</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">页面引用文件：</td>
      <td height="25"><select name="atype" id="atype">
        <option value="0"<?=$r['atype']==0?' selected':''?>>全部引用</option>
        <option value="1"<?=$r['atype']==1?' selected':''?>>不引用文件</option>
        <option value="2"<?=$r['atype']==2?' selected':''?>>引用栏目</option>
        <option value="3"<?=$r['atype']==3?' selected':''?>>引用栏目+标签</option>
        <option value="4"<?=$r['atype']==4?' selected':''?>>引用栏目+分页</option>
        <option value="5"<?=$r['atype']==5?' selected':''?>>引用栏目+会员组</option>
        <option value="6"<?=$r['atype']==6?' selected':''?>>引用栏目+标签+分页</option>
        <option value="7"<?=$r['atype']==7?' selected':''?>>引用栏目+标签+会员组</option>
        <option value="8"<?=$r['atype']==8?' selected':''?>>引用栏目+标签+会员组+分页</option>
      </select>      </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">页码限制：</td>
      <td height="25"><input name="maxpage" type="text" id="maxpage" value="<?=$r['maxpage']?>">
        <font color="#666666">(最大page变量值限制，0为不分页、不限填-1)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">缓存时间：</td>
      <td height="25"><input name="actime" type="text" id="actime" value="<?=$r['actime']?>">
        分钟</td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">附加参数：</td>
      <td height="25"><input name="addcs" type="text" id="addcs" value="<?=ehtmlspecialchars($r['addcs'])?>" size="60"></td>
    </tr>
    
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><strong>页面内容</strong>(*)</td>
      <td height="25">请将模板内容<a href="#ecms" onClick="window.clipboardData.setData('Text',document.form1.atemptext.value);document.form1.atemptext.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onClick="window.open('../template/editor.php?getvar=opener.document.form1.atemptext.value&returnvar=opener.document.form1.atemptext.value&fun=ReturnHtml&notfullpage=1<?=$ecms_hashur['ehref']?>','edittemp','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2"><div align="center"> 
          <textarea name="atemptext" cols="90" rows="23" id="atemptext" wrap="OFF" style="WIDTH: 100%"><?=ehtmlspecialchars(stripSlashes($r['atemptext']))?></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> &nbsp;&nbsp; <input type="reset" name="Submit2" value="重置"></td>
    </tr>
	</form>
	<tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2">&nbsp;&nbsp;[<a href="#ecms" onClick="tempturnit(showtempvar);">显示模板变量说明</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onClick="window.open('../template/EnewsBq.php<?=$ecms_hashur['whehref']?>','','width=600,height=500,scrollbars=yes,resizable=yes');">查看模板标签语法</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onClick="window.open('../ListClass.php<?=$ecms_hashur['whehref']?>','','width=800,height=600,scrollbars=yes,resizable=yes');">查看JS调用地址</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onClick="window.open('../template/ListTempvar.php<?=$ecms_hashur['whehref']?>','','width=800,height=600,scrollbars=yes,resizable=yes');">查看公共模板变量</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onClick="window.open('../template/ListBqtemp.php<?=$ecms_hashur['whehref']?>','','width=800,height=600,scrollbars=yes,resizable=yes');">查看标签模板</a>]</td>
    </tr>
    <tr bgcolor="#FFFFFF" id="showtempvar" style="display:none"> 
      <td height="25" colspan="2"><strong>页面内容支持的变量说明</strong> <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
          <tr bgcolor="#FFFFFF"> 
            <td width="34%" height="25"> <input name="textfield" type="text" value="[!--self.classid--]">
              :页面ID</td>
            <td width="34%"> <input name="textfield2" type="text" value="[!--news.url--]">
              :网站地址</td>
            <td width="32%">&nbsp;</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25"><strong>支持公共模板变量</strong></td>
            <td><strong>支持所有模板标签</strong></td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
</table>
<br>
</body>
</html>
<?php
db_close();
$empire=null;
?>