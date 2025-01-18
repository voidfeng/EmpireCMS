<?php
//--------------- 会员相关处理函数 ---------------

//点卡冲值
function CardGetFen($username,$reusername,$card_no,$password){
	global $empire,$dbtbpre;
	$card_no=RepPostVar($card_no);
	$password=RepPostVar($password);
	$username=RepPostVar($username);
	if(!trim($username)||!trim($card_no)||!trim($password))
	{
		printerror("EmptyGetCard","history.go(-1)",1);
	}
	if($username!=$reusername)
	{
		printerror("DifCardUsername","history.go(-1)",1);
	}
	$user=$empire->fetch1("select ".eReturnSelectMemberF('userid,userdate,username,groupid')." from ".eReturnMemberTable()." where ".egetmf('username')."='$username'".do_dblimit_one());
	if(!$user['userid'])
	{
		printerror("ExiestCardUsername","history.go(-1)",1);
	}
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewscard where card_no='".$card_no."' and password='".$password."'".do_dblimit_cone());
	if(!$num)
	{
		printerror("CardPassError","history.go(-1)",1);
	}
	//是否过期
	$buytime=date("Y-m-d H:i:s");
	$r=$empire->fetch1("select cardfen,money,endtime,carddate,cdgroupid,cdzgroupid from {$dbtbpre}enewscard where card_no='$card_no'".do_dblimit_one());
	if($r['endtime']<>eDefEmptyDate())
	{
		$endtime=to_date($r['endtime']);
		if($endtime<time())
		{
			printerror("CardOutDate","history.go(-1)",1);
	    }
    }
	//充值
	eAddFenToUser($r['cardfen'],$r['carddate'],$r['cdgroupid'],$r['cdzgroupid'],$user);
	$sql1=$empire->query("delete from {$dbtbpre}enewscard where card_no='$card_no'");//删除卡号
	//备份购买记录
	BakBuy($user['userid'],$username,$card_no,$r['cardfen'],$r['money'],$r['carddate'],0);
	printerror("CardGetFenSuccess","../member/card/",1);
}
?>