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

//变量
//pagelist
$efileid=(int)$_GET['efileid'];
$getclassid=(int)$_GET['getclassid'];
$getid=(int)$_GET['getid'];
$getclassname=hRepPostStr($_GET['getclassname'],1);

$openadminpage_r=array();
$openadminpage_r[1][0]='../file/FileNav.php'.$ecms_hashur['whehref'];
$openadminpage_r[1][1]='../file/ListFile.php?type=9&classid='.$getclassid.$ecms_hashur['ehref'];
$openadminpage_r[1][2]='管理附件';

$openadminpage_r[2][0]='../ShopSys/pageleft.php'.$ecms_hashur['whehref'];
$openadminpage_r[2][1]='../other/OtherMain.php'.$ecms_hashur['whehref'];
$openadminpage_r[2][2]='商城系统管理';

$openadminpage_r[3][0]='../pl/PlNav.php'.$ecms_hashur['whehref'];
$openadminpage_r[3][1]='../pl/PlMain.php'.$ecms_hashur['whehref'];
$openadminpage_r[3][2]='管理评论';

$openadminpage_r[4][0]='../file/FileNav.php'.$ecms_hashur['whehref'];
$openadminpage_r[4][1]='';
$openadminpage_r[4][2]='管理附件';

$openadminpage_r[5][0]='../template/dttemppageleft.php'.$ecms_hashur['whehref'];
$openadminpage_r[5][1]='';
$openadminpage_r[5][2]='管理动态页面模板';

$openadminpage_r[6][0]='../ShopSys/pageleft.php'.$ecms_hashur['whehref'];
$openadminpage_r[6][1]='../ShopSys/ListDd.php'.$ecms_hashur['whehref'];
$openadminpage_r[6][2]='商城系统管理';

$openadminpage_r[7][0]='../pl/PlNav.php'.$ecms_hashur['whehref'];
$openadminpage_r[7][1]='../pl/ListAllPl.php?checked=2'.$ecms_hashur['ehref'];
$openadminpage_r[7][2]='管理评论';

$openadminpage_r[8][0]='../special/pageleft.php?ztid='.$getclassid.$ecms_hashur['ehref'];
$openadminpage_r[8][1]='';
$openadminpage_r[8][2]=$getclassname;

//pagelist

if(!$efileid)
{
	$leftfile='left.php';
	$mainfile='main.php';
	$title='管理';
}
if($openadminpage_r[$efileid][0])
{
	$leftfile=$openadminpage_r[$efileid][0];
	$mainfile=$openadminpage_r[$efileid][1];
	if(!$mainfile)
	{
		$mainfile='main.php';
	}
	$title=$openadminpage_r[$efileid][2];
	if(!$title)
	{
		$title='管理';
	}
}
else
{
	$leftfile='left.php';
	$mainfile='main.php';
	$title='管理';
}

//check
if(stristr($leftfile,'://'))
{
	exit();
}
if(stristr($mainfile,'://'))
{
	exit();
}

?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$title?></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</HEAD>
<script language=javascript>
var ie = (document.all) ? true : false;
function changeColor(j){
	if(j < 0) return;
	(ie)?chIE(j,idb):chNS(j,idb.document);
}
function chIE(j,obj){
with(obj){
	document.bgColor = j;
}}
function chNS(j,obj){
with(obj){
	bgColor = j;
}}
</script>
<SCRIPT>
function switchSysBar(){
if (switchPoint.innerText==3){
switchPoint.innerText=4
document.all("frmTitle").style.display="none"
}else{
switchPoint.innerText=3
document.all("frmTitle").style.display=""
}}
</SCRIPT>
<body leftmargin="0" topmargin="0">
<TABLE border=0 cellPadding=0 cellSpacing=0 height="100%" width="100%">
  <TBODY>
    <TR> 
      <TD rowspan="2" align=middle vAlign=center noWrap id="frmTitle"> <IFRAME frameBorder=0 id="apleft" name="apleft" scrolling=yes src="<?=$leftfile?>" style="HEIGHT:100%;VISIBILITY:inherit;WIDTH:190px;Z-INDEX:2"></IFRAME> 
      </TD>
      <TD rowspan="2" bgColor="#D0D0D0"> <TABLE border=0 cellPadding=0 cellSpacing=0 height="100%">
          <TBODY>
            <tr> 
              <TD onclick="switchSysBar()" style="HEIGHT:100%;"> <font style="COLOR:666666;CURSOR:hand;FONT-FAMILY:Webdings;FONT-SIZE:9pt;"> 
                <SPAN id="switchPoint" title="打开/关闭左边导航栏">3</SPAN></font> 
          </TBODY>
        </TABLE></TD>
      <TD style="WIDTH:100%"> 
	  		<table border=0 cellPadding=0 cellSpacing=0 height=100% width=100%><tr height=30 bgcolor=C7D4F7>
            <td height="27" bgcolor="#D0D0D0"><strong><?=$title?></strong></td>
          	</tr><tr><td><IFRAME frameBorder=0 id="apmain" name="apmain" scrolling=yes src="<?=$mainfile?>" style="HEIGHT:100%;VISIBILITY:inherit;WIDTH:100%;Z-INDEX:1"></IFRAME></td></tr>
		 	</table>
	</TD>
    </TR>
  </TBODY>
</TABLE>
</body>
</html>
<?php
db_close();
$empire=null;
?>