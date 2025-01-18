<?php
//发表留言
function AddMemberGbook($add){
	global $empire,$dbtbpre;
	//验证码
	$keyvname='checkspacegbkey';
	ecmsCheckShowKey($keyvname,$add['key'],1);
	//用户
	$userid=intval($add['userid']);
	$ur=$empire->fetch1("select ".eReturnSelectMemberF('userid')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid'".do_dblimit_one());
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
			$uname=trim($add['uname']);
		}
	}
	else
	{
		$uid=0;
		$uname=trim($add['uname']);
	}
	//实名验证
	eCheckHaveTruenameCK('msps',0);

	$uname=dgdbe_rpstr($uname);
	$gbtext=dgdbe_rpstr($add['gbtext']);
	if(empty($uname)||!trim($gbtext))
	{
		printerror("EmptyMemberGbook","history.go(-1)",1);
    }
	$isprivate=intval($add['isprivate']);
	$addtime=date("Y-m-d H:i:s");
	$ip=egetip();
	$eipf=egetipfrom();
	$eipport=egetipport();
	$sql=$empire->query("insert into {$dbtbpre}enewsmembergbook(userid,isprivate,uid,uname,ip,addtime,gbtext,retext,eipport,eipf) values($userid,$isprivate,$uid,'$uname','$ip','$addtime','$gbtext','','$eipport','$eipf');");
	ecmsEmptyShowKey($keyvname);//清空验证码
	if($sql)
	{
		printerror("AddMemberGbookSuccess",EcmsGetReturnUrl(),1);
	}
	else
	{
		printerror("DbError","history.go(-1)",1);
	}
}

//回复留言
function ReMemberGbook($add){
	global $empire,$dbtbpre;
	$user_r=islogin();//是否登陆
	$gid=intval($add['gid']);
	if(!$gid)
	{
		printerror("EmptyReMemberGbook","history.go(-1)",1);
	}
	$retext=dgdbe_rpstr($add['retext']);
	$sql=$empire->query("update {$dbtbpre}enewsmembergbook set retext='$retext' where gid='$gid' and userid='".$user_r['userid']."'");
	if($sql)
	{
		printerror("ReMemberGbookSuccess",EcmsGetReturnUrl(),1);
	}
	else
	{
		printerror("DbError","history.go(-1)",1);
	}
}

//删除留言
function DelMemberGbook($add){
	global $empire,$dbtbpre;
	$user_r=islogin();//是否登陆
	$gid=intval($add['gid']);
	if(!$gid)
	{
		printerror("NotDelMemberGbookid","history.go(-1)",1);
	}
	$sql=$empire->query("delete from {$dbtbpre}enewsmembergbook where gid='$gid' and userid='".$user_r['userid']."'");
	if($sql)
	{
		printerror("DelMemberGbookSuccess",EcmsGetReturnUrl(),1);
	}
	else
	{
		printerror("DbError","history.go(-1)",1);
	}
}

//批量删除留言
function DelMemberGbook_All($add){
	global $empire,$dbtbpre;
	$user_r=islogin();//是否登陆
	$gid=eCheckEmptyArray($add['gid']);
	$count=count($gid);
	if(empty($count))
	{
		printerror("NotDelMemberGbookid","history.go(-1)",1);
	}
	$addsql='';
	for($i=0;$i<$count;$i++)
	{
		$addsql.="gid='".intval($gid[$i])."' or ";
    }
	$addsql=substr($addsql,0,strlen($addsql)-4);
	$sql=$empire->query("delete from {$dbtbpre}enewsmembergbook where (".$addsql.") and userid='".$user_r['userid']."'");
	if($sql)
	{
		printerror("DelMemberGbookSuccess",EcmsGetReturnUrl(),1);
	}
	else
	{
		printerror("DbError","history.go(-1)",1);
	}
}
?>