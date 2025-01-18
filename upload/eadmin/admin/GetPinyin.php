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
hCheckEcmsRHash();

//取得汉字
$hz=ehtmlspecialchars($_GET['hz']);
$returnform=RepPostVar($_GET['returnform']);
if(empty($hz)||empty($returnform))
{
	echo"<script>alert('没输入汉字!');window.close();</script>";
	exit();
}
eCheckStrType(5,$returnform,1);

$py=ReturnPinyinFun($hz);

db_close();
$empire=null;
?>
<script>
<?=$returnform?>="<?=$py?>";
window.close();
</script>
