<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
define('InEmpireCMSUser',TRUE);

//--------------- 扩展函数 ---------------

//登录附加cookie
function AddLoginCookie($r){
}

//--------------- 会员表信息函数 ---------------

//返回会员表
function eReturnMemberTable(){
	global $ecms_config;
	return $ecms_config['member']['tablename'];
}

//返回默认会员组ID
function eReturnMemberDefGroupid(){
	global $ecms_config,$public_r;
	$groupid=$ecms_config['member']['defgroupid']?$ecms_config['member']['defgroupid']:$public_r['defaultgroupid'];
	return intval($groupid);
}

//返回查询会员字段列表
function eReturnSelectMemberF($f,$tb=''){
	global $ecms_config;
	if(empty($ecms_config['member']['chmember']))
	{
		if(!empty($tb))
		{
			$f=$f=='*'?$tb.$f:$tb.str_replace(',',','.$tb,$f);
		}
		return $f;
	}
	if($f=='*')
	{
		$f='userid,username,password,rnd,email,registertime,groupid,userfen,userdate,money,zgroupid,havemsg,checked,salt,userkey,ingid,agid,isern,isot,phno,upic,mustdo,rndt,userjyz,usertitle';
	}
	$r=explode(',',$f);
	$count=count($r);
	$selectf='';
	$dh='';
	for($i=0;$i<$count;$i++)
	{
		$truef=$r[$i];
		if($ecms_config['memberf'][$truef]==$truef)
		{
			$selectf.=$dh.$tb.$truef;
		}
		else
		{
			$selectf.=$dh.$tb.$ecms_config['memberf'][$truef].' as '.$truef;
		}
		$dh=',';
	}
	return $selectf;
}

//返回插入会员字段列表
function eReturnInsertMemberF($f){
	global $ecms_config;
	if(empty($ecms_config['member']['chmember']))
	{
		return $f;
	}
	$r=explode(',',$f);
	$count=count($r);
	$insertf='';
	$dh='';
	for($i=0;$i<$count;$i++)
	{
		$truef=$r[$i];
		$insertf.=$dh.$ecms_config['memberf'][$truef];
		$dh=',';
	}
	return $insertf;
}

//取得实际会员字段
function egetmf($f){
	global $ecms_config;
	if(empty($ecms_config['member']['chmember']))
	{
		return $f;
	}
	return $ecms_config['memberf'][$f]?$ecms_config['memberf'][$f]:$f;
}

//密码
function eDoMemberPw($password,$salt){
	global $ecms_config;
	if($ecms_config['member']['pwtype']==0)//单重md5
	{
		$pw=md5($password);
	}
	elseif($ecms_config['member']['pwtype']==1)//明码
	{
		$pw=$password;
	}
	elseif($ecms_config['member']['pwtype']==3)//16位md5
	{
		$pw=substr(md5($password),8,16);
	}
	else//双重md5
	{
		$pw=md5(md5($password).$salt);
	}
	return $pw;
}

//验证密码
function eDoCkMemberPw($oldpw,$pw,$salt){
	global $ecms_config;
	$istrue=0;
	if($ecms_config['member']['pwtype']==0)//单重md5
	{
		$oldpw=md5($oldpw);
		if('dg'.$oldpw=='dg'.$pw)
		{
			$istrue=1;
		}
	}
	elseif($ecms_config['member']['pwtype']==1)//明码
	{
		if('dg'.$oldpw=='dg'.$pw)
		{
			$istrue=1;
		}
	}
	elseif($ecms_config['member']['pwtype']==3)//16位md5
	{
		$oldpw=substr(md5($oldpw),8,16);
		if('dg'.$oldpw=='dg'.$pw)
		{
			$istrue=1;
		}
	}
	else//双重md5
	{
		$oldpw=md5(md5($oldpw).$salt);
		if('dg'.$oldpw=='dg'.$pw)
		{
			$istrue=1;
		}
	}
	return $istrue;
}

//返回注册时间
function eReturnMemberRegtime($regtime,$format){
	global $ecms_config;
	return empty($ecms_config['member']['regtimetype'])?$regtime:date($format,$regtime);
}

//返回注册时间(int)
function eReturnMemberIntRegtime($regtime){
	global $ecms_config;
	return empty($ecms_config['member']['regtimetype'])?to_time($regtime):$regtime;
}

//返回当前注册时间
function eReturnAddMemberRegtime(){
	global $ecms_config;
	return empty($ecms_config['member']['regtimetype'])?date('Y-m-d H:i:s'):time();
}

//返回SALT
function eReturnMemberSalt(){
	global $ecms_config;
	if($ecms_config['member']['saltnum']<0)
	{
		$n=0-$ecms_config['member']['saltnum'];
		$n=EcmsRandInt($n,12);
	}
	else
	{
		$n=$ecms_config['member']['saltnum'];
	}
	return make_password($n);
}

//返回UserKey
function eReturnMemberUserKey(){
	global $ecms_config;
	return make_password(12);
}

//启动易通行系统
function DoEpassport($ecms,$userid,$username,$password,$salt,$email,$groupid,$retime){
	global $ecms_config;
	return '';
	if(!$ecms_config['epassport']['open'])
	{
		return '';
	}
	include_once ECMS_PATH.'e/epassport/epp_config.php';
	include_once ECMS_PATH.'e/epassport/epp_function.php';
	$r=DoEpassportVar($userid,$username,$password,$salt,$email,$groupid,$retime);
	epassport_doaction($r,$ecms);
}

//易通行系统变量
function DoEpassportVar($userid,$username,$password,$salt,$email,$groupid,$retime){
	$r['userid']=$userid;
	$r['username']=$username;
	$r['password']=$password;
	$r['salt']=$salt;
	$r['email']=$email;
	$r['groupid']=$groupid;
	$r['retime']=$retime;
	return $r;
}

//--------------- 会员公共函数 ---------------

//返回设置短消息
function eReturnSetHavemsg($havemsg,$ecms=0){
	$newhavemsg=1;
	if($havemsg==3)//全部信息
	{
		$newhavemsg=3;
	}
	elseif($havemsg==2)//通知
	{
		$newhavemsg=$ecms==1?2:3;
	}
	elseif($havemsg==1)//消息
	{
		$newhavemsg=$ecms==1?3:1;
	}
	else //无状态
	{
		$newhavemsg=$ecms==1?2:1;
	}
	return $newhavemsg;
}

//返回更新登录随机码
function eReturnDoUpRndf($dotype,$rnd,$rndt){
	global $ecms_config;
	$upstr='';
	if($ecms_config['sets']['mpchmrnd'])
	{
		return '';
	}
	$upstr=egetmf('rnd')."='".addslashes($rnd)."',".egetmf('rndt')."='".addslashes($rndt)."',";
	return $upstr;
}

//验证rnd
function eMemberDoCkRnd($cktype,$userid,$username,$rnd,$rndt,$isot=-1){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	$username=RepPostVar($username);
	$rnd=RepPostVar($rnd);
	$rndt=RepPostVar($rndt);
	$isot=(int)$isot;
	$r=$empire->fetch1("select ".eReturnSelectMemberF('*')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid'".do_dblimit_one());
	$nor=array();
	$isok=1;
	if(!$r['userid'])
	{
		return $nor;
	}
	if($isot!=-1)
	{
		if(!$r['isot'])
		{
			return $nor;
		}
	}
	if($cktype=='')
	{
		if('dg'.$r['username']!='dg'.$username||'dg'.$r['rnd']!='dg'.$rnd||'dg'.$r['rndt']!='dg'.$rndt)
		{
			return $nor;
		}
	}
	else
	{
		if(stristr($cktype,'us'))
		{
			if('dg'.$r['username']!='dg'.$username)
			{
				return $nor;
			}
		}
		if(stristr($cktype,'r1'))
		{
			if('dg'.$r['rnd']!='dg'.$rnd)
			{
				return $nor;
			}
		}
		if(stristr($cktype,'r2'))
		{
			if('dg'.$r['rndt']!='dg'.$rndt)
			{
				return $nor;
			}
		}
	}
	return $r;
}

//取得表单id
function GetMemberFormId($groupid){
	global $empire,$dbtbpre;
	$groupid=(int)$groupid;
	$r=$empire->fetch1("select formid from {$dbtbpre}enewsmembergroup where groupid='$groupid'");
	return intval($r['formid']);
}

//取得邮件地址
function GetUserEmail($userid,$username){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	$r=$empire->fetch1("select ".eReturnSelectMemberF('email')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid'".do_dblimit_one());
	return $r['email'];
}

//返回修改资料
function ReturnUserInfo($userid){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	$r=$empire->fetch1("select ".eReturnSelectMemberF('username,email,groupid,userfen,money,userdate,zgroupid,checked,registertime,ingid,agid,isern,isot,phno,upic,mustdo,userjyz,usertitle')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid'".do_dblimit_one());
	return $r;
}

//返回修改资料(ALL)
function ReturnUserInfoAll($userid,$username=''){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	if($userid)
	{
		$where=egetmf('userid')."='$userid'";
	}
	else
	{
		$username=RepPostVar($username);
		$where=egetmf('username')."='$username'";
	}
	$r=$empire->fetch1("select ".eReturnSelectMemberF('*')." from ".eReturnMemberTable()." where ".$where."".do_dblimit_one());
	return $r;
}

//返回是否审核
function ReturnGroupChecked($groupid){
	global $level_r;
	$groupid=(int)$groupid;
	if($level_r[$groupid]['regchecked']==1)
	{
		$checked=0;
	}
	else
	{
		$checked=1;
	}
	return $checked;
}

//返回使用空间模板
function ReturnGroupSpaceStyleid($groupid){
	global $level_r;
	$groupid=(int)$groupid;
	$spacestyleid=$level_r[$groupid]['spacestyleid']?$level_r[$groupid]['spacestyleid']:0;
	return intval($spacestyleid);
}

//清空COOKIE
function EmptyEcmsCookie(){
	$set1=esetcookie("mlusername","",0);
	$set2=esetcookie("mluserid","",0);
	$set3=esetcookie("mlgroupid","",0);
	$set4=esetcookie("mlrnd","",0);
	$set5=esetcookie("mlauth","",0);
	$set6=esetcookie("mlrndt","",0);
}

//登录加密验证2
function qReturnLoginPassNoCK($userid,$username,$rnd,$ecms=0){
	global $ecms_config;
	if(!$userid||!$rnd)
	{
		return '';
	}
	$checkpass=md5(md5($ecms_config['cks']['ckrndthree'].'e.c-m-s-'.$userid.'-(em!pi.re!-)'.$rnd.'-d-i-g.o*d').'-#em.pire.cms!-'.$ecms_config['cks']['ckrndthree']);
	return $checkpass;
}

//登录验证符
function qGetLoginAuthstr($userid,$username,$rnd,$groupid,$cookietime=0){
	global $ecms_config;
	$checkpass=md5(md5($rnd.'--d-i!'.$userid.'-(g*od-'.$username.$ecms_config['cks']['ckrndtwo'].'-'.$groupid).'-#empire.cms!--p)h-o!me-'.$ecms_config['cks']['ckrndtwo']);
	esetcookie('mlauth',$checkpass,$cookietime);
}

//验证登录验证符
function qCheckLoginAuthstr(){
	global $ecms_config;
	$re['userid']='';
	$re['username']='';
	$re['groupid']='';
	$re['rnd']='';
	$re['rndt']='';
	$re['islogin']=0;
	$checkpass=getcvar('mlauth');
	if(!$checkpass)
	{
		return $re;
	}
	$re['userid']=(int)getcvar('mluserid');
	$re['username']=RepPostVar(getcvar('mlusername'));
	$re['rnd']=RepPostVar(getcvar('mlrnd'));
	$re['rndt']=RepPostVar(getcvar('mlrndt'));
	$re['groupid']=(int)getcvar('mlgroupid');
	if(!$re['userid']||!$re['username']||!$re['rnd'])
	{
		return $re;
	}
	$pass=md5(md5($re['rnd'].'--d-i!'.$re['userid'].'-(g*od-'.$re['username'].$ecms_config['cks']['ckrndtwo'].'-'.$re['groupid']).'-#empire.cms!--p)h-o!me-'.$ecms_config['cks']['ckrndtwo']);
	if('dg'.$pass!='dg'.$checkpass)
	{
		return $re;
	}
	else
	{
		$re['islogin']=1;
		return $re;
	}
}

//是否登录
function islogin($uid=0,$uname='',$urnd=''){
	global $empire,$dbtbpre,$public_r,$ecmsreurl,$ecms_config;
	if($uid)
	{$userid=(int)$uid;}
	else
	{$userid=(int)getcvar('mluserid');}
	if($uname)
	{$username=$uname;}
	else
	{$username=getcvar('mlusername');}
	$username=RepPostVar($username);
	if($urnd)
	{$rnd=$urnd;}
	else
	{$rnd=getcvar('mlrnd');}
	if($ecms_config['member']['loginurl'])
	{$gotourl=$ecms_config['member']['loginurl'];}
	else
	{$gotourl=$public_r['newsurl']."e/member/login/";}
	$petype=1;
	$rnd=RepPostVar($rnd);
	if(!$userid||!$username||!$rnd)
	{
		if(!getcvar('returnurl'))
		{
			esetcookie("returnurl",EcmsGetReturnUrl(),0);
		}
		if($ecmsreurl==1)
		{
			$gotourl="history.go(-1)";
			$petype=9;
		}
		elseif($ecmsreurl==2)
		{
			$phpmyself=urlencode(eReturnSelfPage(1));
			$gotourl=$public_r['newsurl']."e/member/login/login.php?prt=1&from=".$phpmyself;
			$petype=9;
		}
		printerror("NotLogin",$gotourl,$petype);
	}
	//cookie
	if(getcvar('mluserid'))
	{
		$qcklgr=qCheckLoginAuthstr();
		if(!$qcklgr['islogin'])
		{
			EmptyEcmsCookie();
			if(!getcvar('returnurl'))
			{
				esetcookie("returnurl",EcmsGetReturnUrl(),0);
			}
			if($ecmsreurl==1)
			{
				$gotourl="history.go(-1)";
				$petype=9;
			}
			elseif($ecmsreurl==2)
			{
				$phpmyself=urlencode(eReturnSelfPage(1));
				$gotourl=$public_r['newsurl']."e/member/login/login.php?prt=1&from=".$phpmyself;
				$petype=9;
			}
			printerror("NotSingleLogin",$gotourl,$petype);
		}
	}
	$cr=$empire->fetch1("select ".eReturnSelectMemberF('userid,username,email,groupid,userfen,money,userdate,zgroupid,havemsg,checked,registertime,ingid,agid,isern,isot,phno,upic,mustdo,rndt,userjyz,usertitle')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid' and ".egetmf('username')."='$username' and ".egetmf('rnd')."='$rnd'".do_dblimit_one());
	if(!$cr['userid']||!$cr['isot'])
	{
		EmptyEcmsCookie();
		if(!getcvar('returnurl'))
		{
			esetcookie("returnurl",EcmsGetReturnUrl(),0);
		}
		if($ecmsreurl==1)
		{
			$gotourl="history.go(-1)";
			$petype=9;
		}
		elseif($ecmsreurl==2)
		{
			$phpmyself=urlencode(eReturnSelfPage(1));
			$gotourl=$public_r['newsurl']."e/member/login/login.php?prt=1&from=".$phpmyself;
			$petype=9;
		}
		printerror("NotSingleLogin",$gotourl,$petype);
	}
	if($cr['checked']==0)
	{
		EmptyEcmsCookie();
		if($ecmsreurl==1)
		{
			$gotourl="history.go(-1)";
			$petype=9;
		}
		elseif($ecmsreurl==2)
		{
			$phpmyself=urlencode(eReturnSelfPage(1));
			$gotourl=$public_r['newsurl']."e/member/login/login.php?prt=1&from=".$phpmyself;
			$petype=9;
		}
		printerror("NotCheckedUser",'',$petype);
	}
	//默认会员组
	if(empty($cr['groupid']))
	{
		$user_groupid=eReturnMemberDefGroupid();
		$usql=$empire->query("update ".eReturnMemberTable()." set ".egetmf('groupid')."='$user_groupid' where ".egetmf('userid')."='".$cr['userid']."'");
		$cr['groupid']=$user_groupid;
	}
	//是否过期
	if($cr['userdate'])
	{
		if($cr['userdate']-time()<=0)
		{
			OutTimeZGroup($cr['userid'],$cr['zgroupid']);
			$cr['userdate']=0;
			if($cr['zgroupid'])
			{
				$cr['groupid']=$cr['zgroupid'];
				$cr['zgroupid']=0;
			}
		}
	}
	//必须操作转向
	if($public_r['phmmust'])
	{
		if($cr['mustdo']==0)
		{
			if(!defined('InEmpireCMSNMUST'))
			{
				$mustgotourl=$public_r['newsurl'].'e/extenddef/esms/BindPh.php';
				printerrortourl($mustgotourl,'',1);
			}
		}
	}
	$re['userid']=(int)$cr['userid'];
	$re['rnd']=$rnd;
	$re['rndt']=$cr['rndt'];
	$re['username']=$cr['username'];
	$re['email']=$cr['email'];
	$re['userfen']=$cr['userfen'];
	$re['money']=$cr['money'];
	$re['groupid']=(int)$cr['groupid'];
	$re['userdate']=$cr['userdate'];
	$re['zgroupid']=$cr['zgroupid'];
	$re['havemsg']=$cr['havemsg'];
	$re['registertime']=$cr['registertime'];
	$re['ingid']=$cr['ingid'];
	$re['agid']=$cr['agid'];
	$re['isern']=$cr['isern'];
	$re['phno']=$cr['phno'];
	$re['upic']=$cr['upic'];
	$re['isot']=$cr['isot'];
	$re['mustdo']=$cr['mustdo'];
	$re['userjyz']=$cr['userjyz'];
	$re['usertitle']=$cr['usertitle'];
	$re['checked']=$cr['checked'];
	return $re;
}

//会员登录
function DoEcmsMemberLogin($r,$lifetime=0,$uplast=0){
	global $empire,$dbtbpre,$ecms_config;
	$rnd=make_password(EcmsRandInt(20,36));//取得随机密码
	$rndt=make_password(EcmsRandInt(20,32));//取得随机密码2
	//更新rnd
	$uprndstr=eReturnDoUpRndf('funlogin',$rnd,$rndt);
	if(!$uprndstr)
	{
		$rnd=$r['rnd'];
		$rndt=$r['rndt'];
	}
	//默认会员组
	if(empty($r['groupid']))
	{
		$r['groupid']=eReturnMemberDefGroupid();
	}
	$r['userid']=(int)$r['userid'];
	$r['groupid']=(int)$r['groupid'];
	$empire->query("update ".eReturnMemberTable()." set ".$uprndstr.egetmf('groupid')."='".$r['groupid']."',".egetmf('isot')."=1 where ".egetmf('userid')."='".$r['userid']."'");
	if($uplast==1)
	{
		$lasttime=time();
		//IP
		$lastip=egetip();
		$eipf=egetipfrom();
		$lastipport=egetipport();
		$empire->query("update {$dbtbpre}enewsmemberadd set lasttime='$lasttime',lastip='$lastip',loginnum=loginnum+1,lastipport='$lastipport',eipf='$eipf' where userid='".$r['userid']."'");
	}
	//设置cookie
	$lifetime=(int)$lifetime;
	$logincookie=0;
	if($lifetime)
	{
		$logincookie=time()+$lifetime;
	}
	esetcookie("mlusername",$r['username'],$logincookie);
	esetcookie("mluserid",$r['userid'],$logincookie);
	esetcookie("mlgroupid",$r['groupid'],$logincookie);
	esetcookie("mlrnd",$rnd,$logincookie);
	esetcookie("mlrndt",$rndt,$logincookie);
	//验证符
	qGetLoginAuthstr($r['userid'],$r['username'],$rnd,$r['groupid'],$logincookie);
	//登录附加cookie
	AddLoginCookie($r);
}

//会员注册
function DoEcmsMemberReg($r,$setr){
	global $empire,$dbtbpre,$ecms_config,$public_r,$level_r;
	//变量
	$yusername=$r['username'];
	$username=RepPostVar($r['username']);
	if($yusername!=$username)
	{
		exit();
	}
	$password=RepPostVar($r['password']);
	$email=RepPostVar($r['email']);
	$public_r['reggetfen']=(int)$public_r['reggetfen'];
	$checked=(int)$r['checked'];
	$isot=(int)$r['isot'];
	$phno=RepPostVar($r['phno']);
	$loginnum=$r['loginnum']?1:0;
	//返回
	if(!$username)
	{
		return 'EmptyUsername';
	}
	//参数
	$pr=$empire->fetch1("select min_userlen,max_userlen,min_passlen,max_passlen,regretime,regclosewords,regemailonly,regphonly from {$dbtbpre}enewspublic".do_dblimit_one());
	//IP
	$regip=egetip();
	$eipf=egetipfrom();
	$regipport=egetipport();
	//会员组
	$user_groupid=eReturnMemberDefGroupid();
	$groupid=(int)$r['groupid'];
	$groupid=empty($groupid)?$user_groupid:$groupid;
	$groupid=(int)$groupid;
	//验证会员组是否可注册
	if($setr['ck_gcanreg'])
	{
		$ckmgr=$empire->fetch1("select groupid from {$dbtbpre}enewsmembergroup where groupid='$groupid' and canreg=1");
		if(empty($ckmgr['groupid']))
		{
			return 'GroupNotCanReg';
		}
	}
	//认证码
	if($setr['ck_regps'])
	{
		if($level_r[$groupid]['regps'])
		{
			if('dg'.$level_r[$groupid]['regps']!='dg'.$r['mg_regps'])
			{
				return 'FailRegPs';
			}
		}
	}
	//用户名长度
	if($setr['ck_userlen'])
	{
		$userlen=strlen($username);
		if($userlen<$pr['min_userlen']||$userlen>$pr['max_userlen'])
		{
			return 'FaiUserlen';
		}
	}
	if($setr['ck_passlen'])
	{
		//密码字数
		$passlen=strlen($password);
		if($passlen<$pr['min_passlen']||$passlen>$pr['max_passlen'])
		{
			return 'FailPasslen';
		}
	}
	//注册时间
	$lasttime=time();
	$rnd=make_password(20);//产生随机密码
	$registertime=eReturnAddMemberRegtime();
	$userkey=eReturnMemberUserKey();
	//密码
	$truepassword=$password;
	$salt=eReturnMemberSalt();
	$password=eDoMemberPw($password,$salt);
	//验证
	if(strstr($username,'|')||strstr($username,'*'))
	{
		return 'NotSpeWord';
	}
	//保留用户
	if($setr['ck_regcloseuserword'])
	{
		toCheckCloseWord($username,$pr['regclosewords'],'RegHaveCloseword');
	}
	//重复用户
	$num=$empire->gettotal("select count(*) as total from ".eReturnMemberTable()." where ".egetmf('username')."='$username'".do_dblimit_cone());
	if($num)
	{
		return 'ReUsername';
	}
	//重复邮箱
	if($setr['ck_reregemail'])
	{
		if(!$email)
		{
			return 'EmptyEmail';
		}
		if($email)
		{
			$num=$empire->gettotal("select count(*) as total from ".eReturnMemberTable()." where ".egetmf('email')."='$email'".do_dblimit_cone());
			if($num>=$pr['regemailonly'])
			{
				return 'ReEmail';
			}
		}
	}
	//重复手机
	if($setr['ck_reregphno'])
	{
		if(!$phno)
		{
			return 'EmptyPhno';
		}
		if($phno)
		{
			$num=$empire->gettotal("select count(*) as total from ".eReturnMemberTable()." where ".egetmf('phno')."='$phno'".do_dblimit_cone());
			if($num>=$pr['regphonly'])
			{
				return 'RePhno';
			}
		}
	}
	//审核
	if($setr['ck_setregcheck'])
	{
		$checked=ReturnGroupChecked($groupid);
	}
	if($checked&&$public_r['regacttype']==2)
	{
		$checked=0;
	}
	$checked=(int)$checked;
	//绑定手机
	$u_isern=0;
	$u_mustdo=0;
	if($setr['have_setregbindph'])
	{
		$u_isern=1;
		$u_mustdo=1;
	}
	//主表
	$sql=$empire->updatesql("insert into ".eReturnMemberTable()."(".eReturnInsertMemberF('username,password,rnd,email,registertime,groupid,userfen,userdate,money,zgroupid,havemsg,checked,salt,userkey,isot,phno,upic,mustdo,isern').") values('$username','$password','$rnd','$email','$registertime','$groupid','".$public_r['reggetfen']."','0','0','0','0','$checked','$salt','$userkey','$isot','$phno',0,'$u_mustdo','$u_isern');","ins");
	//取得userid
	$userid=$empire->lastid(eReturnMemberTable(),egetmf('userid'));
	//附加表
	$addr=$empire->fetch1("select userid from {$dbtbpre}enewsmemberadd where userid='".$userid."'");
	if(!$addr['userid'])
	{
		$spacestyleid=ReturnGroupSpaceStyleid($groupid);
		$sql1=$empire->updatesql("insert into {$dbtbpre}enewsmemberadd(userid,spacestyleid,regip,lasttime,lastip,loginnum,regipport,lastipport,eipf) values('$userid','$spacestyleid','$regip','$lasttime','$regip','$loginnum','$regipport','$regipport','$eipf');","ins");
	}
	return $userid;
}

//随机生成用户名
function DoEcmsMemberReg_rnduser($r){
	$phno=RepPostVar($r['phno']);
	$key=RepPostVar($r['key']);
	$time=time();
	$regusername=substr(md5($phno.'-'.$key.'-'.$time),8,16).make_password(9);
	$regpass=make_password(EcmsRandInt(18,30));
	$regpass=substr($regpass,2,28);
	$ret_r=array();
	$ret_r['reguname']=$regusername;
	$ret_r['regupass']=$regpass;
	return $ret_r;
}

//默认用户名长度验证
function DoEcmsMemberReg_ckunamelen($uname){
	if(strlen($uname)>24)
	{
		return 0;
	}
	return 1;
}

//验证会员帐号和密码
function DoEcmsMemberCheckUserPass($add,$ecms=0){
	global $empire,$dbtbpre,$ecms_config;
	$dopr=1;
	if($_POST['prtype'])
	{
		$dopr=9;
	}
	$username=trim($add['username']);
	$password=trim($add['password']);
	if(!$username||!$password)
	{
		printerror("EmptyLogin","history.go(-1)",$dopr);
	}
	$username=RepPostVar($username);
	$password=RepPostVar($password);
	$num=0;
	$r=$empire->fetch1("select ".eReturnSelectMemberF('*')." from ".eReturnMemberTable()." where ".egetmf('username')."='$username'".do_dblimit_one());
	if(!$r['userid'])
	{
		printerror("FailPassword","history.go(-1)",$dopr);
	}
	if(!eDoCkMemberPw($password,$r['password'],$r['salt']))
	{
		printerror("FailPassword","history.go(-1)",$dopr);
	}
	if($ecms==1)
	{
		if($r['checked']==0)
		{
			printerror('NotCheckedUser','',$dopr);
		}
	}
	return $r;
}

//返回管理组级别
function eMember_ReturnAgidLevel($userid,$agid){
	global $empire,$dbtbpre,$public_r,$ecms_config,$aglevel_r;
	$userid=(int)$userid;
	$agid=(int)$agid;
	$glevel=0;
	$ckstr=','.$userid.',';
	if(!$agid)
	{
		return 0;
	}
	if(!$aglevel_r[$agid]['agid'])
	{
		return 0;
	}
	if(strstr($public_r['qmotheruids'],$ckstr))
	{
		$glevel=1;
	}
	elseif(strstr($public_r['qmforumuids'],$ckstr))
	{
		$glevel=5;
	}
	elseif(strstr($public_r['qmadminuids'],$ckstr))
	{
		$glevel=9;
	}
	else
	{
		$glevel=0;
	}
	if($aglevel_r[$agid]['isadmin']!=$glevel)
	{
		$glevel=0;
	}
	return $glevel;
}

//返回验证访问组权限
function eMember_ReturnCheckViewGroup($ckuser,$vgid){
	global $empire,$dbtbpre,$public_r,$ecms_config,$class_r;
	$esuccess='empire.cms';
	$vgid=(int)$vgid;
	$ckuser['userid']=(int)$ckuser['userid'];
	if(!$vgid)
	{
		return 'NotVgid';
	}
	$vgr=$empire->fetch1("select * from {$dbtbpre}enewsvg where vgid='$vgid'");
	if(!$vgr['vgid'])
	{
		return 'NotVgid';
	}
	$thistime=time();
	//会员组验证
	if($vgr['gids'])
	{
		$ckuser['groupid']=(int)$ckuser['groupid'];
		if(strstr($vgr['gids'],','.$ckuser['groupid'].','))
		{
			return $esuccess;
		}
	}
	//内部组验证
	if($vgr['ingids'])
	{
		$ckuser['ingid']=(int)$ckuser['ingid'];
		if(strstr($vgr['ingids'],','.$ckuser['ingid'].','))
		{
			return $esuccess;
		}
	}
	//会员管理组验证
	if($vgr['agids'])
	{
		$ckuser['agid']=(int)$ckuser['agid'];
		if(strstr($vgr['agids'],','.$ckuser['agid'].','))
		{
			return $esuccess;
		}
	}
	//会员白名单
	if($vgr['mlist'])
	{
		$vgmember=$empire->fetch1("select userid,outtime from {$dbtbpre}enewsvglist where vgid='$vgid' and userid='".$ckuser['userid']."'".do_dblimit_one());
		if(!$vgmember['userid'])
		{
			return 'NotUserid';
		}
		if(empty($vgmember['outtime']))
		{
			return $esuccess;
		}
		if($thistime<$vgmember['outtime'])
		{
			return $esuccess;
		}
	}
	return 'NotLevel';
}

//返回验证会员组权限值
function eMember_ReturnCheckMemberGroup($ckuser,$gid){
	global $level_r;
	$esuccess='empire.cms';
	$gid=(int)$gid;
	$ckuser['groupid']=(int)$ckuser['groupid'];
	if($level_r[$gid]['level']>$level_r[$ckuser['groupid']]['level'])
	{
		return 'NotLevelM';
	}
	else
	{
		return $esuccess;
	}
}

//返回验证会员组+访问组
function eMember_ReturnCheckMVGroup($ckuser,$gid){
	if($gid>0)//会员组
	{
		return eMember_ReturnCheckMemberGroup($ckuser,$gid);
	}
	elseif($gid==-99999999)//关闭
	{
		printerror('MVGClose','',1);
	}
	else//访问组
	{
		$vgid=0-$gid;
		return eMember_ReturnCheckViewGroup($ckuser,$vgid);
	}
}


//--------------- 会员实名函数 ---------------

//实名验证
function eCheckHaveTruename($mod,$userid,$username,$isern,$checked,$ecms=0){
	global $empire,$dbtbpre,$public_r,$ecms_config,$ecms_topagesetr,$enews;
	if(empty($public_r['openern']))
	{
		return '';
	}
	if(!strstr($public_r['openern'],','.$mod.','))
	{
		return '';
	}
	if($userid)
	{
		if($checked==0)
		{
			printerror("NotCheckedUser",'',1);
		}
	}
	if(!$isern)
	{
		printerror('NotHaveTrueName',$public_r['ernurl'],1);
	}
}

//实名验证2
function eCheckHaveTruenameCK($mod,$ecms=0){
	global $empire,$dbtbpre,$public_r,$ecms_config,$ecms_topagesetr,$enews;
	if(empty($public_r['openern']))
	{
		return '';
	}
	if(!strstr($public_r['openern'],','.$mod.','))
	{
		return '';
	}
	$isern=0;
	$cklgr=qCheckLoginAuthstr();
	if($cklgr['islogin'])
	{
		$userid=(int)$cklgr['userid'];
		$ernr=eReturnHaveTruename($userid,'',1);
		$isern=$ernr['isern'];
		if($ernr['checked']==0)
		{
			printerror("NotCheckedUser",'',1);
		}
	}
	if(!$isern)
	{
		printerror('NotHaveTrueName',$public_r['ernurl'],1);
	}
}

//返回是否实名
function eReturnHaveTruename($userid,$username='',$ecms=0){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	$r=$empire->fetch1("select ".eReturnSelectMemberF('userid,checked,isern')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid'".do_dblimit_one());
	if($ecms)
	{
		return $r;
	}
	else
	{
		return $r['isern'];
	}
}

//更新实名状态
function eUpdateTruenameStatus($userid,$username,$checked,$isern,$ecms=0){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	$checked=(int)$checked;
	$isern=(int)$isern;
	$username=RepPostVar($username);
	//更新审核和实名状态
	$upstr='';
	if($ecms==2)
	{
		$upstr="".egetmf('checked')."='$checked',".egetmf('isern')."='$isern'";
	}
	elseif($ecms==1)//审核
	{
		$upstr="".egetmf('checked')."='$checked'";
	}
	else//实名
	{
		$upstr="".egetmf('isern')."='$isern'";
	}
	if($upstr)
	{
		$sql=$empire->query("update ".eReturnMemberTable()." set ".$upstr." where ".egetmf('userid')."='$userid'");
		return $sql;
	}
	return 0;
}


//--------------- 其他函数 ---------------

//增加点数
function AddInfoFen($cardfen,$userid,$checkfen=1){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	$cardfen=(int)$cardfen;
	if(!$cardfen)
	{
		return '';
	}
	//checkfen
	if($checkfen==1)
	{
		if($cardfen<0)
		{
			$ur=$empire->fetch1("select ".eReturnSelectMemberF('userid,userdate,userfen')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$userid'".do_dblimit_one());
			if(!$ur['userid'])
			{
				return '';
			}
			if($ur['userdate']-time()>0)
			{
				return '';
			}
			if($cardfen+$ur['userfen']<0)
			{
				$cardfen=$ur['userfen']*-1;
			}
		}
	}
	$sql=$empire->query("update ".eReturnMemberTable()." set ".egetmf('userfen')."=".egetmf('userfen')."+".$cardfen." where ".egetmf('userid')."='$userid'");
}

//转向会员组
function OutTimeZGroup($userid,$zgroupid){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	$zgroupid=(int)$zgroupid;
	if($zgroupid)
	{
		$sql=$empire->query("update ".eReturnMemberTable()." set ".egetmf('groupid')."='".$zgroupid."',".egetmf('userdate')."=0 where ".egetmf('userid')."='$userid'");
	}
	else
	{
		$sql=$empire->query("update ".eReturnMemberTable()." set ".egetmf('userdate')."=0 where ".egetmf('userid')."='$userid'");
	}
}

//充值有效期判断
function eCardCheckUserdate($userdate,$usergroupid,$buygroupid){
	global $public_r;
	if($usergroupid==$buygroupid)
	{
		return $userdate;
	}
	//已有有效期
	if($userdate&&$userdate>=time())
	{
		if($public_r['mhavedatedo']==1)//覆盖
		{
			$userdate=0;
		}
		elseif($public_r['mhavedatedo']==2)//叠加
		{
		}
		else//不让充值
		{
			printerror('CardHaveUserdate','history.go(-1)',1);
		}
	}
	return $userdate;
}

//充值
function eAddFenToUser($fen,$date,$groupid,$zgroupid,$user,$money=0){
	global $empire,$dbtbpre,$public_r;
	if(!($fen||$date||$money))
	{
		return '';
	}
	$fen=(int)$fen;
	$date=(int)$date;
	$groupid=(int)$groupid;
	$zgroupid=(int)$zgroupid;
	$money=(float)$money;
	$user['userid']=(int)$user['userid'];
	$user['groupid']=(int)$user['groupid'];
	$user['userdate']=(int)$user['userdate'];
	$update='';
	$dh='';
	//积分
	if($fen)
	{
		$update.=egetmf('userfen')."=".egetmf('userfen')."+$fen";
	}
	//预付款
	if($money)
	{
		if($update)
		{
			$dh=',';
		}
		$update.=$dh.egetmf('money')."=".egetmf('money')."+$money";
	}
	//有效期
	if($date)
	{
		$user['userdate']=eCardCheckUserdate($user['userdate'],$user['groupid'],$groupid);//有效期验证
		if($update)
		{
			$dh=',';
		}
		if($user['userdate']<time())
		{
			$userdate=time()+$date*24*3600;
		}
		else
		{
			$userdate=$user['userdate']+$date*24*3600;
		}
		$userdate=(int)$userdate;
		$update.=$dh.egetmf('userdate')."='$userdate'";
		//转向会员组
		if($groupid)
		{
			$update.=",".egetmf('groupid')."='$groupid'";
		}
		if($zgroupid)
		{
			$update.=",".egetmf('zgroupid')."='$zgroupid'";
		}
	}
	$sql=$empire->query("update ".eReturnMemberTable()." set ".$update." where ".egetmf('userid')."='".$user['userid']."'");
	if(!$sql)
	{
		printerror('DbError',$public_r['newsurl'],1);
	}
}

//检查下载数
function DoCheckMDownNum($userid,$groupid,$ecms=0){
	global $empire,$dbtbpre,$level_r;
	$userid=(int)$userid;
	$groupid=(int)$groupid;
	$ur=$empire->fetch1("select userid,todaydate,todaydown from {$dbtbpre}enewsmemberpub where userid='$userid'".do_dblimit_one());
	$thetoday=date("Y-m-d");
	if($ur['userid'])
	{
		if($thetoday!=$ur['todaydate'])
		{
			$query="update {$dbtbpre}enewsmemberpub set todaydate='$thetoday',todaydown=1 where userid='$userid'";
		}
		else
		{
			if($ur['todaydown']>=$level_r[$groupid]['daydown'])
			{
				if($ecms==1)
				{
					exit();
				}
				elseif($ecms==2)
				{
					return 'error';
				}
				else
				{
					printerror("CrossDaydown","history.go(-1)",1);
				}
			}
			$query="update {$dbtbpre}enewsmemberpub set todaydown=todaydown+1 where userid='$userid'";
		}
	}
	else
	{
		$query=do_dbReplaceInto()." into {$dbtbpre}enewsmemberpub(userid,todaydate,todaydown) values('$userid','$thetoday',1);";
	}
	return $query;
}

//更新激活认证码
function DoUpdateMemberAuthstr($userid,$authstr){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	$authstr=dgdbe_rpstr($authstr);
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsmemberpub where userid='$userid'".do_dblimit_cone());
	if($num)
	{
		$sql=$empire->query("update {$dbtbpre}enewsmemberpub set authstr='$authstr' where userid='$userid'");
	}
	else
	{
		$sql=$empire->query(do_dbReplaceInto()." into {$dbtbpre}enewsmemberpub(userid,authstr) values('$userid','$authstr');");
	}
	return $sql;
}
?>