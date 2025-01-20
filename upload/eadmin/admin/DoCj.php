<?php
define('EmpireCMSAdmin','1');
require("../../e/class/connect.php");
require("../../e/class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
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
CheckLevel($logininid,$loginin,$classid,"cj");
$add=$_GET;
$classid=eCheckEmptyArray($add['classid']);
$count=count($classid);
if(!$count)
{
	printerror("NotChangeCjid","history.go(-1)");
}

//formhash
$efh=heformhash_get('CjUrl',1);

$add['from']=ehtmlspecialchars($add['from']);
esetcookie("recjnum",$count,0,1);
$url="ecmscj.php?enews=CjUrl".$ecms_hashur['href'].$efh;
echo"<center><b>采集节点的总个数为:<font color=red>$count</font>个。</b>&nbsp;&nbsp; (<a href='ReHtml/ChangeData.php".$ecms_hashur['whehref']."#ReIfInfoHtml' target=_blank>数据更新中心</a>)</center><br>";
for($i=0;$i<$count;$i++)
{
	$classid[$i]=(int)$classid[$i];
	$trueurl=$url."&from=".$add['from']."&classid=".$classid[$i];
	echo"<iframe frameborder=0 height=35 name='class".$classid[$i]."' scrolling=no 
            src=\"".$trueurl."\" 
            width=\"100%\"></iframe><br>";
}
db_close();
$empire=null;
?>
<iframe frameborder=0 height=35 name="checkrecj" scrolling=no 
            src="CheckReCj.php?first=1&from=<?=$add['from']?><?=$ecms_hashur['href']?>" 
            width="100%"></iframe>