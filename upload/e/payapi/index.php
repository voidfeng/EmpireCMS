<?php
require("../class/connect.php");
require("../class/q_functions.php");
require("../member/class/user.php");
eCheckCloseMods('pay');//关闭模块
$link=db_connect();
$empire=new mysqlquery();
//是否登陆
$user=islogin();
$pr=$empire->fetch1("select paymoneytofen,payminmoney from {$dbtbpre}enewspublic".do_dblimit_one());
$paysql=$empire->query("select payid,paytype,payfee,paysay,payname from {$dbtbpre}enewspayapi where isclose=0 order by myorder,payid");
$pays='';
while($payr=$empire->fetch($paysql))
{
	$pays.="<option value='".$payr['payid']."'>".$payr['payname']."</option>";
}
//导入模板
include(ECMS_PATH.'e/template/payapi/payapi.php');
db_close();
$empire=null;
?>