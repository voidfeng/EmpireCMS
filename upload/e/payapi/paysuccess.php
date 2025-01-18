<?php
require("../class/connect.php");
require("../class/q_functions.php");
require("../member/class/user.php");
eCheckCloseMods('pay');//关闭模块
$link=db_connect();
$empire=new mysqlquery();

include('payfun.php');
//订单信息
$get_ddno=getcvar('echeckpaysuccess');
$get_ddno=RepPostVar($get_ddno);
if(!$get_ddno)
{
	echo'<script>top.location.href="../../";</script>';
	exit();
}

$r=$empire->fetch1("select * from {$dbtbpre}enewspayrecord where payddno='$get_ddno'".do_dblimit_one());
if(!$r['id'])
{
	echo'<script>top.location.href="../../";</script>';
	exit();
}
//设置
esetcookie("checkpaysession","",0);
esetcookie("paymoneyrdid","",0);
esetcookie("payphome","",0);

$messtext='订单 '.$r['payddno'].' 已支付完成！';

printerror($messtext,'../../',1,1,1);

db_close();
$empire=null;
?>