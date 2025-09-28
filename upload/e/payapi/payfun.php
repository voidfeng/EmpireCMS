<?php

//----------------------------- 支付公共函数 ---------------------------

//支付提示信息
function epay_showmsg($msg,$url=''){
	printerror($msg,$url,1,0,1);
}

//订单号处理
function epay_repddno($ddno){
	$ddno=RepPostVar($ddno);
	eCheckStrType(4,$ddno,1);
	return $ddno;
}

//将参数转为url
function epay_ToUrlParams($r,$novar=''){
	$novar=','.$novar.',';
	$str='';
	$strurl='';
	foreach ($r as $k => $v)
	{
		if(strlen($v)==0||is_array($v)||stristr($novar,','.$k.','))
		{
			continue;
		}
		$strurl.=$k.'='.urlencode($v).'&';
		$str.=$k.'='.$v.'&';
	}
	$re['strurl']=substr($strurl,0,-1);
	$re['str']=substr($str,0,-1);
	return $re;
}

//输出xml
function epay_ToXml($r){
	if(!is_array($r)||count($r)<=0)
	{
    	return '';
    }
    $xml='<xml>';
    foreach ($r as $key => $val)
    {
    	if(is_numeric($val))
		{
    		$xml.='<'.$key.'>'.$val.'</'.$key.'>';
    	}
		else
		{
    		$xml.='<'.$key.'><![CDATA['.$val.']]></'.$key.'>';
    	}
    }
    $xml.='</xml>';
    return $xml;
}

//将xml转为array
function epay_FromXml($xml){
	$r=array();
	if(!$xml)
	{
		return $r;
	}
    //将XML转为array
    //禁止引用外部xml实体
    @libxml_disable_entity_loader(true);
    $r=json_decode(json_encode(simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA)),true);
	return $r;
}

//接收返回网页内容
function epay_getpageinput(){
	$data=file_get_contents('php://input');
	return $data;
}

//产生随机字符串，不长于32位
function epay_getRndStr($length=32){
	$chars='abcdefghijklmnopqrstuvwxyz0123456789';  
	$str='';
	for($i=0;$i<$length;$i++)
	{  
		$str.=substr($chars,mt_rand(0,strlen($chars)-1),1);  
	} 
	return $str;
}

//以post方式提交xml到对应的接口url
function epay_CurlPost($url,$xml,$useCert=false,$second=30,$sslck=0,$ecms=0){
	global $epay_setconfig;
	if(!function_exists('curl_init'))
	{
		//post提交方式
		if(!empty($xml))
		{
			echo'Not install curl.';
			exit();
		}
		$string=ReadUrltext($url);
		return $string;
	}

	$ch=curl_init();
	$curlVersion=curl_version();
	if($ecms==1)
	{
		//$ua="WXPaySDK/".$epay_setconfig['esdkver']." (".PHP_OS.") PHP/".PHP_VERSION." CURL/".$curlVersion['version']." ".$epay_setconfig['epayuser'];
		$ua='';
	}
	elseif($ecms==2)
	{
		$ua='';
	}
	else
	{
		$ua='';
	}
	//设置超时
	curl_setopt($ch,CURLOPT_TIMEOUT,$second);
	$proxyHost=$epay_setconfig['eproxyhost'];
	$proxyPort=$epay_setconfig['eproxyport'];
	//如果有配置代理这里就设置代理
	if($proxyHost!="0.0.0.0"&&$proxyPort!=0)
	{
		curl_setopt($ch,CURLOPT_PROXY,$proxyHost);
		curl_setopt($ch,CURLOPT_PROXYPORT,$proxyPort);
	}
	curl_setopt($ch,CURLOPT_URL,$url);
	if($sslck)
	{
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
	}
	else
	{
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
	}
	if($ua)
	{
		curl_setopt($ch,CURLOPT_USERAGENT,$ua); 
	}
	//设置header
	curl_setopt($ch,CURLOPT_HEADER,FALSE);
	//要求结果为字符串且输出到屏幕上
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
	//证书
	if($useCert==true)
	{
		//设置证书
		//使用证书：cert 与 key 分别属于两个.pem文件
		//证书文件请放入服务器的非web目录下
		$sslCertPath = $epay_setconfig['esslcertpath'];
		$sslKeyPath = $epay_setconfig['esslkeypath'];
		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLCERT,$sslCertPath);
		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLKEY,$sslKeyPath);
	}
	//post提交方式
	if(!empty($xml))
	{
		curl_setopt($ch,CURLOPT_POST,TRUE);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
	}
	//运行curl
	$data=curl_exec($ch);
	//返回结果
	if($data)
	{
		curl_close($ch);
		return $data;
	}
	else
	{ 
		$error = curl_errno($ch);
		curl_close($ch);
		echo'curl ErrorNo:'.$error;
		exit();
	}
}

//显示二维码
function epay_ShowPayImg($url){
    $DoQRcode=new QRcode();
    $level='L';
    $size=4;
    ob_start();
    $DoQRcode->png($url,false,$level,$size,2);
    $imageString="data:image/jpg;base64,".base64_encode(ob_get_contents());
    ob_end_clean();
    echo "<img src='$imageString' />";
}

//显示二维码(JS)
function epay_ShowPayImgJs($url,$baseurl='eewm/',$width='210',$height='210',$divid='eqrcode'){
	?>
	<script type="text/javascript" src="<?=$baseurl?>js/jquery.js"></script>
	<script type="text/javascript" src="<?=$baseurl?>js/qrcode.js"></script>
	
	<div style="width:<?=$width?>px; height:<?=$height?>px; display:block; margin:30px auto 0; background: url(<?=$baseurl?>img/loading.gif) no-repeat center center" id="<?=$divid?>"></div>
	
	<script type="text/javascript">
	$(document).ready(function(){var qrcode = new QRCode(document.getElementById("<?=$divid?>"),{width:<?=$width?>,height:<?=$height?>});
	qrcode.makeCode("<?=$url?>");});
	</script>
	
	<?php
}


//----------------------------- epayapi ---------------------------

//返回订单号
function epayapi_ReturnDdno($id){
	$id=(int)$id;
	if(!$id)
	{
		$id=EcmsRandInt(10000000,99999999);
	}
	$ddno=time().$id;
	$ddno=RepPostVar($ddno);
	return $ddno;
}

//返回支付验证码
function epayapi_ReturnPayckcode(){
	$ckcode=make_password(9);
	$ckcode=RepPostVar($ckcode);
	return $ckcode;
}

//验证是否支付过
function epayapi_CkHavePay($orderid){
	global $empire,$dbtbpre;
	$orderid=RepPostVar($orderid);
	$r=$empire->fetch1("select * from {$dbtbpre}enewspayrecord where orderid='$orderid'".do_dblimit_one());
	$r['havepayst']=0;
	if($r['id'])
	{
		$r['havepayst']=1;
	}
	return $r;
}

//返回待支付订单信息
function epayapi_GetPayRecordSt($ddno,$id=0){
	global $empire,$dbtbpre;
	if(!$id)
	{
		$id=(int)getcvar('paymoneyrdid');
	}
	$ddno=RepPostVar($ddno);
	$id=(int)$id;
	if($id)
	{
		$where="id='$id'";
	}
	else
	{
		$where="payddno='$ddno'";
	}
	$r=$empire->fetch1("select * from {$dbtbpre}enewspayrecordst where ".$where."".do_dblimit_one());
	return $r;
}

//写入支付记录表
function epayapi_AddPayRecord($r,$ecms=0){
	global $empire,$dbtbpre;
	if($ecms==1)
	{
		$tb=$dbtbpre.'enewspayrecord';
		$orderid=RepPostVar($r['orderid']);
		$posttime=RepPostVar2($r['posttime']);
		$payip=RepPostVar($r['payip']);
		$ispay=1;
		$payckcode=RepPostVar($r['payckcode']);
		$endtime=date("Y-m-d H:i:s");
		$mpid=(int)$r['mpid'];
	}
	else
	{
		$tb=$dbtbpre.'enewspayrecordst';
		$orderid='';
		$posttime=date("Y-m-d H:i:s");
		$payip=egetip();
		$ispay=0;
		$payckcode=epayapi_ReturnPayckcode();
		$endtime='';
		$mpid=eReturnSMPid();
	}
	$userid=(int)$r['userid'];
	$username=RepPostVar($r['username']);
	$money=(float)$r['money'];
	$paybz=dgdbe_rpstr($r['paybz']);
	$paytype=RepPostVar($r['paytype']);
	$paydo=RepPostVar($r['paydo']);
	$payfor=RepPostVar($r['payfor']);
	$payddno=RepPostVar($r['payddno']);
	$pname=dgdbe_rpstr($r['pname']);
	$psay=dgdbe_rpstr($r['psay']);
	$sql=$empire->updatesql("insert into ".$tb."(userid,username,orderid,money,posttime,paybz,paytype,payip,ispay,paydo,payfor,payckcode,payddno,pname,psay,endtime,mpid) values('$userid','$username','$orderid','$money','$posttime','$paybz','$paytype','$payip','$ispay','$paydo','$payfor','$payckcode','$payddno','$pname','$psay','$endtime','$mpid');","ins");
	$lastid=$empire->lastid($tb,'id');
	if(!$sql)
	{
		exit();
	}
	if($ecms!=1&&$lastid)
	{
		$set=esetcookie("paymoneyrdid",$lastid,0);
	}
	$rtr['id']=$lastid;
	$rtr['rsql']=$sql;
	$rtr['payckcode']=$payckcode;
	$rtr['posttime']=$posttime;
	$rtr['endtime']=$endtime;
	$rtr['money']=$money;
	$rtr['payddno']=$payddno;
	$rtr['payip']=$payip;
	$rtr['pname']=$pname;
	$rtr['psay']=$psay;
	return $rtr;
}

//转移订单
function epayapi_MovePayRecord($prstr,$orderid,$paybz=''){
	global $empire,$dbtbpre;
	$prstr['orderid']=$orderid;
	if($paybz)
	{
		$prstr['paybz']=$paybz;
	}
	epayapi_AddPayRecord($prstr,1);
	$prid=(int)$prstr['id'];
	$del=$empire->query("delete from ".$dbtbpre."enewspayrecordst where id='".$prid."'");
}


//----------------------------- epayapi ---------------------------



//----------------------------- 支付后统一处理 -----------------------------

function epayapi_PaySuccessDo($psr,$isnt=0){
	global $empire,$dbtbpre;
	$paytype=RepPostVar($psr['paytype']);
	$orderid=RepPostVar($psr['orderid']);
	$ddno=RepPostVar($psr['ddno']);
	$money=(float)$psr['money'];
	$prstr=epayapi_GetPayRecordSt($ddno,0);
	//订单号
	if(!$prstr['id'])
	{
		printerror('非法操作','../../../',1,0,1);
	}
	if($psr['eaddattach'])
	{
		if($prstr['id']!=$psr['eaddattach'])
		{
			printerror('非法操作','../../../',1,0,1);
		}
	}
	//事件
	if($isnt==1)
	{
		$phome=$prstr['paydo'];
	}
	else
	{
		$phome=getcvar('payphome');
		if(!getcvar('checkpaysession'))
		{
			printerror('非法操作','../../../',1,0,1);
		}
		else
		{
			//esetcookie("checkpaysession","",0);
		}
	}
	$phome=RepPostVar($phome);
	if($money<=0||$prstr['money']<=0)
	{
		printerror('您来自的链接不存在','',1,0,1);
	}
	if($prstr['money']!=$money)
	{
		printerror('您来自的链接不存在','',1,0,1);
	}
	//事件
	if($phome=='PayToFen')//购买点数
	{}
	elseif($phome=='PayToMoney')//存预付款
	{}
	elseif($phome=='ShopPay')//商城支付
	{}
	elseif($phome=='BuyGroupPay')//购买充值类型
	{}
	else
	{
		printerror('您来自的链接不存在','',1,0,1);
	}
	$emuser=array();
	if($isnt==1)
	{
		if($prstr['userid'])
		{
			$emuser=ReturnUserInfoAll($prstr['userid']);
		}
	}
	else
	{
		if($phome=='PayToFen'||$phome=='PayToMoney'||$phome=='BuyGroupPay')
		{
			$emuser=islogin();//是否登录
		}
	}
	$pr=$empire->fetch1("select paymoneytofen,payminmoney from {$dbtbpre}enewspublic".do_dblimit_one());
	$fen=floor($money)*$pr['paymoneytofen'];
	//------ 开始处理 ------
	
	if($phome=='PayToFen')//购买点数
	{
		$paybz='购买点数: '.$fen;
		PayApiBuyFen($fen,$money,$paybz,$orderid,$paytype,$prstr,$emuser);
	}
	elseif($phome=='PayToMoney')//存预付款
	{
		$paybz='存预付款';
		PayApiPayMoney($money,$paybz,$orderid,$paytype,$prstr,$emuser);
	}
	elseif($phome=='ShopPay')//商城支付
	{
		if($isnt==1)
		{
			$ddid=(int)$prstr['payfor'];
		}
		else
		{
			$ddid=(int)getcvar('paymoneyddid');
		}
		$paybz='商城购买 [!--ddno--] 的订单(ddid='.$ddid.')';
		PayApiShopPay($ddid,$money,$paybz,$orderid,$paytype,$prstr,$emuser);
	}
	elseif($phome=='BuyGroupPay')//购买充值类型
	{
		if($isnt==1)
		{
			$bgid=(int)$prstr['payfor'];
		}
		else
		{
			$bgid=(int)getcvar('paymoneybgid');
		}
		$paybz='';
		PayApiBuyGroupPay($bgid,$money,$paybz,$orderid,$paytype,$prstr,$emuser);
	}
	else
	{}

	//------ 结束处理 ------
	
}



//----------------------------- 购买处理 -----------------------------

//购买点数处理
function PayApiBuyFen($fen,$money,$paybz,$orderid,$ecms_paytype,$prstr,$emuser){
	global $empire,$dbtbpre;
	$fen=(int)$fen;
	$money=(float)$money;
	$paybz=dgdbe_rpstr($paybz);
	$userid=(int)$emuser['userid'];
	$username=RepPostVar($emuser['username']);
	$ecms_paytype=RepPostVar($ecms_paytype);
	//验证是否重复提交
	$orderid=RepPostVar($orderid);
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspayrecord where orderid='$orderid'".do_dblimit_cone());
	if($num)
	{
		printerror('您已成功购买 '.$fen.' 点','../../../',1,0,1);
	}
	if($fen)
	{
		$sql=$empire->query("update ".eReturnMemberTable()." set ".egetmf('userfen')."=".egetmf('userfen')."+".$fen." where ".egetmf('userid')."='$userid'");
		//转移订单
		epayapi_MovePayRecord($prstr,$orderid,$paybz);
		//备份充值记录
		BakBuy($userid,$username,$orderid,$fen,$money,0,2);
	}
	printerror('您已成功购买 '.$fen.' 点','../../../',1,0,1);
}

//预付款处理
function PayApiPayMoney($money,$paybz,$orderid,$ecms_paytype,$prstr,$emuser){
	global $empire,$dbtbpre;
	$money=(float)$money;
	$paybz=dgdbe_rpstr($paybz);
	$userid=(int)$emuser['userid'];
	$username=RepPostVar($emuser['username']);
	$ecms_paytype=RepPostVar($ecms_paytype);
	//验证是否重复提交
	$orderid=RepPostVar($orderid);
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspayrecord where orderid='$orderid'".do_dblimit_cone());
	if($num)
	{
		printerror('您已成功存预付款 '.$money.' 元','../../../',1,0,1);
	}
	if($money)
	{
		$sql=$empire->query("update ".eReturnMemberTable()." set ".egetmf('money')."=".egetmf('money')."+".$money." where ".egetmf('userid')."='$userid'");
		//转移订单
		epayapi_MovePayRecord($prstr,$orderid,$paybz);
		//备份充值记录
		BakBuy($userid,$username,$orderid,0,$money,0,3);
	}
	printerror('您已成功存预付款 '.$money.' 元','../../../',1,0,1);
}

//商城支付
function PayApiShopPay($ddid,$money,$paybz,$orderid,$ecms_paytype,$prstr,$emuser){
	global $empire,$dbtbpre;
	$ddid=(int)$ddid;
	$userid=(int)$emuser['userid'];
	$username=RepPostVar($emuser['username']);
	$ecms_paytype=RepPostVar($ecms_paytype);
	//验证是否重复提交
	$orderid=RepPostVar($orderid);
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspayrecord where orderid='$orderid'".do_dblimit_cone());
	if($num)
	{
		printerror('您已成功购买此订单','../../ShopSys/buycar/',1,0,1);
	}
	$ddr=PayApiShopDdMoney($ddid);
	if($money==$ddr['tmoney'])
	{
		include('../../ShopSys/class/ShopSysFun.php');
		$money=(float)$money;
		$sql=$empire->query("update {$dbtbpre}enewsshopdd set haveprice=1 where ddid='$ddid'");
		//减少库存
		$shoppr=ShopSys_ReturnSet();
		if($shoppr['cutnumtype']==1)
		{
			$buycarr=$empire->fetch1("select buycar from {$dbtbpre}enewsshopdd_add where ddid='$ddid'");
			Shopsys_CutMaxnum($ddid,$buycarr['buycar'],$ddr['havecutnum'],$shoppr,0);
		}
		$userid=(int)$ddr['userid'];
		$username=$ddr['username']?$ddr['username']:$ddr['truename'];
		$username=dgdbe_rpstr($username);
		$paybz=str_replace('[!--ddno--]',$ddr['ddno'],$paybz);
		$paybz=dgdbe_rpstr($paybz);
		$emuserid=(int)$emuser['userid'];
		if(!$emuserid)
		{
			$prstr['username']=$username;
		}
		//转移订单
		epayapi_MovePayRecord($prstr,$orderid,$paybz);
	}
	printerror('您已成功购买此订单','../../ShopSys/buycar/',1,0,1);
}

//商城订单金额
function PayApiShopDdMoney($ddid){
	global $empire,$dbtbpre;
	$ddid=(int)$ddid;
	if(empty($ddid))
	{
		printerror('订单不存在','../../../',1,0,1);
	}
	$r=$empire->fetch1("select ddid,ddno,userid,username,truename,pstotal,alltotal,fptotal,pretotal,fp,payby,havecutnum from {$dbtbpre}enewsshopdd where ddid='$ddid'");
	if(empty($r['ddid']))
	{
		printerror('订单不存在','../../../',1,0,1);
	}
	//是否现金购买
	if($r['payby']!=0)
	{
		printerror('此订单为非现金支付','../../../',1,0,1);
	}
	$r['tmoney']=$r['alltotal']+$r['pstotal']+$r['fptotal']-$r['pretotal'];
	$r['tmoney']=efmmoney($r['tmoney']);
	if($r['tmoney']<=0)
	{
		printerror('订单金额有误','../../../',1,0,1);
	}
	return $r;
}

//充值类型支付
function PayApiBuyGroupPay($bgid,$money,$paybz,$orderid,$ecms_paytype,$prstr,$emuser){
	global $empire,$dbtbpre,$level_r;
	$bgid=(int)$bgid;
	$userid=(int)$emuser['userid'];
	$username=RepPostVar($emuser['username']);
	$groupid=(int)$emuser['groupid'];
	$ecms_paytype=RepPostVar($ecms_paytype);
	//验证是否重复提交
	$orderid=RepPostVar($orderid);
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspayrecord where orderid='$orderid'".do_dblimit_cone());
	if($num)
	{
		printerror('您已成功充值','../../../',1,0,1);
	}
	$buyr=$empire->fetch1("select * from {$dbtbpre}enewsbuygroup where id='$bgid'");
	if($buyr['id']&&$money==$buyr['gmoney']&&$level_r[$buyr['buygroupid']]['level']<=$level_r[$groupid]['level'])
	{
		$money=(float)$money;
		//充值
		$user=$empire->fetch1("select ".eReturnSelectMemberF('userdate,userid,username,groupid')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid'");
		eAddFenToUser($buyr['gfen'],$buyr['gdate'],$buyr['ggroupid'],$buyr['gzgroupid'],$user);
		$paybz="充值类型:".addslashes($buyr['gname']);
		$paybz=dgdbe_rpstr($paybz);
		//转移订单
		epayapi_MovePayRecord($prstr,$orderid,$paybz);
		//备份充值记录
		BakBuy($userid,$username,$buyr['gname'],$buyr['gfen'],$money,$buyr['gdate'],1);
	}
	printerror('您已成功充值','../../../',1,0,1);
}
?>