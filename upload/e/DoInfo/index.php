<?php
require("../class/connect.php");
$link=db_connect();
$empire=new mysqlquery();
//关闭投稿
if($public_r['addnews_ok'])
{
	printerror("CloseQAdd","",1);
}
//导入模板
include(ECMS_PATH.'e/template/DoInfo/DoInfo.php');
db_close();
$empire=null;
?>