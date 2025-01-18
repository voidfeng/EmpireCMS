<?php
//提交反馈
function AddMemberFeedback($add){
	global $empire,$dbtbpre;
	//验证码
	$keyvname='checkspacefbkey';
	ecmsCheckShowKey($keyvname,$add['key'],1);
	//用户
	$userid=intval($add['userid']);
	$ur=$empire->fetch1("select ".egetmf('userid')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid'".do_dblimit_one());
	if(empty($ur['userid']))
	{
		printerror("NotUsername","",1);
	}
	//发表者
	$uid=(int)getcvar('mluserid');
	if($uid)
	{
		$cklgr=qCheckLoginAuthstr();
		if($cklgr['islogin'])
		{
			$uname=RepPostVar(getcvar('mlusername'));
		}
		else
		{
			$uid=0;
			$uname='';
		}
	}
	else
	{
		$uid=0;
		$uname='';
	}
	//实名验证
	eCheckHaveTruenameCK('msps',0);

	$uname=dgdbe_rpstr($uname);
	$name=dgdbe_rpstr($add['name']);
	$company=dgdbe_rpstr($add['company']);
	$phone=dgdbe_rpstr($add['phone']);
	$fax=dgdbe_rpstr($add['fax']);
	$email=dgdbe_rpstr($add['email']);
	$address=dgdbe_rpstr($add['address']);
	$zip=dgdbe_rpstr($add['zip']);
	$title=dgdbe_rpstr($add['title']);
	$ftext=dgdbe_rpstr($add['ftext']);
	if(!trim($name)||!trim($title)||!trim($ftext))
	{
		printerror("EmptyMemberFeedback","history.go(-1)",1);
    }
	$addtime=date("Y-m-d H:i:s");
	$ip=egetip();
	$eipf=egetipfrom();
	$eipport=egetipport();
	$sql=$empire->query("insert into {$dbtbpre}enewsmemberfeedback(name,company,phone,fax,email,address,zip,title,ftext,userid,ip,uid,uname,addtime,eipport,eipf) values('$name','$company','$phone','$fax','$email','$address','$zip','$title','$ftext',$userid,'$ip',$uid,'$uname','$addtime','$eipport','$eipf');");
	ecmsEmptyShowKey($keyvname);//清空验证码
	if($sql)
	{
		printerror("AddMemberFeedbackSuccess",EcmsGetReturnUrl(),1);
	}
	else
	{
		printerror("DbError","history.go(-1)",1);
	}
}

//删除反馈
function DelMemberFeedback($add){
	global $empire,$dbtbpre;
	$user_r=islogin();//是否登陆
	$fid=intval($add['fid']);
	if(!$fid)
	{
		printerror("NotDelMemberFeedbackid","history.go(-1)",1);
	}
	$sql=$empire->query("delete from {$dbtbpre}enewsmemberfeedback where fid='$fid' and userid='".$user_r['userid']."'");
	if($sql)
	{
		printerror("DelMemberFeedbackSuccess",EcmsGetReturnUrl(),1);
	}
	else
	{
		printerror("DbError","history.go(-1)",1);
	}
}

//批量删除反馈
function DelMemberFeedback_All($add){
	global $empire,$dbtbpre;
	$user_r=islogin();//是否登陆
	$fid=eCheckEmptyArray($add['fid']);
	$count=count($fid);
	if(empty($count))
	{
		printerror("NotDelMemberFeedbackid","history.go(-1)",1);
	}
	$addsql='';
	for($i=0;$i<$count;$i++)
	{
		$addsql.="fid='".intval($fid[$i])."' or ";
    }
	$addsql=substr($addsql,0,strlen($addsql)-4);
	$sql=$empire->query("delete from {$dbtbpre}enewsmemberfeedback where (".$addsql.") and userid='".$user_r['userid']."'");
	if($sql)
	{
		printerror("DelMemberFeedbackSuccess",EcmsGetReturnUrl(),1);
	}
	else
	{
		printerror("DbError","history.go(-1)",1);
	}
}
?>