<?php
require("../../class/connect.php");
require("../../class/q_functions.php");
require("../../data/dbcache/class.php");
require("../class/ShopSysFun.php");
eCheckCloseMods('shop');//关闭模块
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
$shoppr=ShopSys_ReturnSet();
//导入模板
include(ECMS_PATH.'e/template/ShopSys/buycar.php');
db_close();
$empire=null;
?>