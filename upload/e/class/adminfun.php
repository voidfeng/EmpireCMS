<?php
//错误登陆记录
function InsertErrorLoginNum($username,$password,$loginauth,$ip,$time,$userid=0){
	global $empire,$public_r,$dbtbpre;
	$userid=(int)$userid;
	$username=RepPostVar($username);
	$password='';
	$loginauth=RepPostVar($loginauth);
	$ip=RepPostVar($ip);
	$time=(int)$time;
	//COOKIE
	$loginnum=intval(getcvar('loginnum'));
	$logintime=$time;
	$lastlogintime=intval(getcvar('lastlogintime'));
	if($lastlogintime&&($logintime-$lastlogintime>$public_r['logintime']*60))
	{
		$loginnum=0;
	}
	$loginnum++;
	esetcookie("loginnum",$loginnum,$logintime+3600*24);
	esetcookie("lastlogintime",$logintime,$logintime+3600*24);
	//数据库
	$chtime=$time-$public_r['logintime']*60;
	$chtime=(int)$chtime;
	//ip
	if($public_r['loginckt']==2||$public_r['loginckt']==0)
	{
		$empire->query("delete from {$dbtbpre}enewsloginfail where lasttime<$chtime");
		$r=$empire->fetch1("select ip from {$dbtbpre}enewsloginfail where ip='$ip'".do_dblimit_one());
		if($r['ip'])
		{
			$empire->query("update {$dbtbpre}enewsloginfail set num=num+1,lasttime='$time' where ip='$ip'".do_dblimit_upone());
		}
		else
		{
			$empire->query("insert into {$dbtbpre}enewsloginfail(ip,num,lasttime) values('$ip',1,'$time');");
		}
	}
	//uname
	if($public_r['loginckt']==2||$public_r['loginckt']==1)
	{
		$empire->query("delete from {$dbtbpre}enewsloginfail_u where lasttime<$chtime");
		$lcur=$empire->fetch1("select uname from {$dbtbpre}enewsloginfail_u where uname='$username'".do_dblimit_one());
		if($lcur['uname'])
		{
			$empire->query("update {$dbtbpre}enewsloginfail_u set num=num+1,lasttime='$time' where uname='$username'".do_dblimit_upone());
		}
		else
		{
			$empire->query("insert into {$dbtbpre}enewsloginfail_u(uname,num,lasttime) values('$username',1,'$time');");
		}
	}
	//日志
	insert_log($username,$password,0,$ip,$loginauth,$userid);
}

//验证登录次数
function CheckLoginNum($username,$ip,$time){
	global $empire,$public_r,$dbtbpre;
	$public_r['loginnum']=(int)$public_r['loginnum'];
	//COOKIE验证
	$loginnum=intval(getcvar('loginnum'));
	$lastlogintime=intval(getcvar('lastlogintime'));
	if($lastlogintime)
	{
		if($time-$lastlogintime<$public_r['logintime']*60)
		{
			if($loginnum>=$public_r['loginnum'])
			{
				printerror("LoginOutNum",eAdminLoginReturnUrl(0));
			}
		}
	}
	$username=RepPostVar($username);
	$ip=RepPostVar($ip);
	//数据库验证
	$chtime=$time-$public_r['logintime']*60;
	$chtime=(int)$chtime;
	//ip
	if($public_r['loginckt']==2||$public_r['loginckt']==0)
	{
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsloginfail where ip='$ip' and num>=".$public_r['loginnum']." and lasttime>$chtime".do_dblimit_cone());
		if($num)
		{
			printerror("LoginOutNum",eAdminLoginReturnUrl(0));
		}
	}
	//uname
	if($public_r['loginckt']==2||$public_r['loginckt']==1)
	{
		$numcu=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsloginfail_u where uname='$username' and num>=".$public_r['loginnum']." and lasttime>$chtime".do_dblimit_cone());
		if($numcu)
		{
			printerror("LoginOutNum",eAdminLoginReturnUrl(0));
		}
	}
}

//登陆
function login($username,$password,$key,$post,$file,$file_name,$file_type,$file_size){
	global $empire,$public_r,$dbtbpre,$ecms_config;
	$username=RepPostVar($username);
	$password=RepPostVar($password);
	if(!$username||!$password)
	{
		printerror("EmptyKey",eAdminLoginReturnUrl(0));
	}
	//一次性密码
	$useonepass=(int)$post['useonepass'];
	$onepasspno=RepPostVar($post['onepasspno']);
	if($useonepass&&!$onepasspno)
	{
		printerror("EmptyKey",eAdminLoginReturnUrl(0));
	}
	//验证码
	$keyvname='checkkey';
	if(!$public_r['adminloginkey'])
	{
		ecmsCheckShowKey($keyvname,$key,0,0,1);
	}
	if(strlen($username)>100)
	{
		printerror("EmptyKey",eAdminLoginReturnUrl(0));
	}
	$loginip=egetip();
	$logintime=time();
	CheckLoginNum($username,$loginip,$logintime);
	//认证码
	if($ecms_config['esafe']['loginauth'])
	{
		if('dg'.$ecms_config['esafe']['loginauth']!='dg'.$post['loginauth'])
		{
			InsertErrorLoginNum($username,$password,1,$loginip,$logintime,0);
			printerror("ErrorLoginAuth",eAdminLoginReturnUrl(0));
		}
	}
	$user_r=$empire->fetch1("select userid,username,password,salt,salt2,lasttime,lastip,addtime,addip,userprikey,lastipport,addipport,rnds,ckinfos,tuser,lgac,goac,lgactime from {$dbtbpre}enewsuser where tuser='".$username."' and checked=0".do_dblimit_one());
	if(!$user_r['userid'])
	{
		InsertErrorLoginNum($username,$password,0,$loginip,$logintime,$user_r['userid']);
		printerror("LoginFail",eAdminLoginReturnUrl(0));
	}
	if(strlen($password)>30||strlen($password)<6)
	{
		InsertErrorLoginNum($username,$password,0,$loginip,$logintime,$user_r['userid']);
		printerror("LoginFail",eAdminLoginReturnUrl(0));
	}
	//一次性密码
	$onepass_r=array();
	$onepassupdate='';
	if($useonepass)
	{
		if($ecms_config['esafe']['loginonepass'])
		{
			$onepass_r=$empire->fetch1("select * from {$dbtbpre}enewsuseronepass where userid='".$user_r['userid']."' and pno='".$onepasspno."'".do_dblimit_one());
			if(!$onepass_r['userid']||!$onepass_r['salt']||!$onepass_r['salt2']||!$onepass_r['password']||!$onepass_r['pno']||!$onepass_r['id'])
			{
				InsertErrorLoginNum($username,$password,0,$loginip,$logintime,$user_r['userid']);
				printerror("LoginFail",eAdminLoginReturnUrl(0));
			}
			if($onepass_r['isopen']!=1)
			{
				InsertErrorLoginNum($username,$password,0,$loginip,$logintime,$user_r['userid']);
				printerror("LoginFail",eAdminLoginReturnUrl(0));
			}
			$user_r['salt']=$onepass_r['salt'];
			$user_r['salt2']=$onepass_r['salt2'];
			$user_r['password']=$onepass_r['password'];
			$onepassupdate=',onepassnum=onepassnum+1';
		}
	}
	//验证
	$ch_password=DoEmpireCMSAdminPassword($password,$user_r['salt'],$user_r['salt2']);
	if('dg'.$user_r['password']!='dg'.$ch_password)
	{
		InsertErrorLoginNum($username,$password,0,$loginip,$logintime,$user_r['userid']);
		printerror("LoginFail",eAdminLoginReturnUrl(0));
	}
	//安全问答
	$user_addr=$empire->fetch1("select userid,equestion,eanswer,openip,certkey,ckffname,ckftime,certkeyrnd from {$dbtbpre}enewsuseradd where userid='".$user_r['userid']."'");
	if(!$user_addr['userid'])
	{
		InsertErrorLoginNum($username,$password,0,$loginip,$logintime,$user_r['userid']);
		printerror("LoginFail",eAdminLoginReturnUrl(0));
	}
	if($user_addr['equestion'])
	{
		$equestion=(int)$post['equestion'];
		$eanswer=$post['eanswer'];
		if($user_addr['equestion']!=$equestion)
		{
			InsertErrorLoginNum($username,$password,0,$loginip,$logintime,$user_r['userid']);
			printerror("LoginFail",eAdminLoginReturnUrl(0));
		}
		$ckeanswer=ReturnHLoginQuestionStr($user_r['userid'],$user_r['username'],$user_addr['equestion'],$eanswer);
		if('dg'.$ckeanswer!='dg'.$user_addr['eanswer'])
		{
			InsertErrorLoginNum($username,$password,0,$loginip,$logintime,$user_r['userid']);
			printerror("LoginFail",eAdminLoginReturnUrl(0));
		}
	}
	//IP限制
	if($user_addr['openip'])
	{
		eCheckAccessAdminLoginIp($user_addr['openip']);
	}
	//取得随机密码
	$rndsr=eRndSource_make($user_r);
	$rnd=make_password(20).substr($rndsr['rnds2'],0,10);
	$loginipport=egetipport();
	$sql=$empire->query("update {$dbtbpre}enewsuser set rnd='$rnd',loginnum=loginnum+1,lastip='$loginip',lasttime='$logintime',pretime='".$user_r['lasttime']."',preip='".RepPostVar($user_r['lastip'])."',lastipport='$loginipport',preipport='".RepPostVar($user_r['lastipport'])."',isot=1,rnds='".$rndsr['newrnds']."',lgac=0,goac=1".$onepassupdate." where username='".$user_r['username']."'".do_dblimit_upone());
	$r=$empire->fetch1("select groupid,userid,styleid,userprikey,username from {$dbtbpre}enewsuser where username='".$user_r['username']."'".do_dblimit_one());
	//样式
	if(empty($r['styleid']))
	{
		$stylepath=$public_r['defadminstyle']?$public_r['defadminstyle']:1;
	}
	else
	{
		$styler=$empire->fetch1("select path,styleid from {$dbtbpre}enewsadminstyle where styleid='".$r['styleid']."'");
		if(empty($styler['styleid']))
		{
			$stylepath=$public_r['defadminstyle']?$public_r['defadminstyle']:1;
		}
		else
		{
			$stylepath=$styler['path'];
		}
	}
	//设置
	$alogin_gr=$empire->fetch1("select groupid,dodbdata,dolgac from {$dbtbpre}enewsgroup where groupid='".$r['groupid']."'");
	if(!$alogin_gr['groupid'])
	{
		echo'Error group.';
		exit();
	}
	$lgacupdate='';
	if($alogin_gr['dolgac'])
	{
		$lgacupdate='lgac=0,goac=1';
	}
	else
	{
		$lgacupdate='lgac=1,goac=0';
	}
	$empire->query("update {$dbtbpre}enewsuser set ".$lgacupdate." where username='".$user_r['username']."'".do_dblimit_upone());
	
	$cdbdata=0;
	$bnum=$alogin_gr['dodbdata'];
	if($bnum)
	{
		$cdbdata=1;
		$set5=esetcookie("ecmsdodbdata","empirecms",0,1);
    }
	else
	{
		$set5=esetcookie("ecmsdodbdata","",0,1);
	}
	
	ecmsEmptyShowKey($keyvname,0,1);//清空验证码
	$set4=esetcookie("loginuserid",$r['userid'],0,1);
	$set1=esetcookie("loginusername",$r['username'],0,1);
	$set2=esetcookie("loginrnd",$rnd,0,1);
	$set3=esetcookie("loginlevel",$r['groupid'],0,1);
	$set5=esetcookie("eloginlic","empirecmslic",0,1);
	$set6=esetcookie("loginadminstyleid",$stylepath,0,1);
	//COOKIE加密验证
	DoEDelFileRnd($r['userid']);
	DoECookieRnd($r['userid'],$r['username'],$rnd,$r['userprikey'],$cdbdata,$r['groupid'],intval($stylepath),$logintime,$rndsr);
	$ecmsaddrnd=DoECreatAddAuthRnd($r['userid'],$r['username'],$rnd,$r['groupid'],$rndsr,1);
	$ecmsar=substr($ecmsaddrnd,-14);
	//最后登陆时间
	$set4=esetcookie("logintime",$logintime,0,1);
	$set5=esetcookie("truelogintime",$logintime,0,1);
	esetcookie('ecertkeyrnds','',0);
	//formhash
	heformhash_del($r['userid'],0);
	//一次性密码
	if($useonepass)
	{
		if($onepass_r['id'])
		{
			$onepass_r['id']=(int)$onepass_r['id'];
			$empire->query("delete from {$dbtbpre}enewsuseronepass where id='".$onepass_r['id']."'");
		}
	}
	//写入日志
	insert_log($username,'',1,$loginip,0,$user_r['userid'],$onepass_r['pno']);
	//FireWall
	FWSetPassword();
	if($set1&&$set2&&$set3)
	{
		$cache_enews='doclass,doinfo,douserinfo';
		$cache_ecmstourl='ear.php'.urlencode('?ecmsar='.$ecmsar.hReturnEcmsHashStrDef(0,'ehref'));
		$cache_mess='LoginSuccess';
		$cache_url="CreacjCache.php?enews=$cache_enews&ecmstourl=$cache_ecmstourl&mess=$cache_mess".hReturnEcmsHashStrDef(0,'ehref');
		//操作日志
		$GLOBALS['logininid']=$r['userid'];
		$GLOBALS['loginin']=$r['username'];
	    insert_dolog($onepass_r['id']?'onepassid='.intval($onepass_r['id']):'');
		if($post['adminwindow'])
		{
		?>
			<script>
			AdminWin=window.open("<?=$cache_url?>","EmpireCMS","scrollbars");
			AdminWin.moveTo(0,0);
			AdminWin.resizeTo(screen.width,screen.height-30);
			self.location.href="blank.php";
			</script>
		<?php
		exit();
		}
		else
		{
			//printerror("LoginSuccess",$cache_url);
			echo'<meta http-equiv="refresh" content="0;url='.$cache_url.'">';
			db_close();
			$empire=null;
			exit();
		}
	}
	else
	{
		printerror("NotCookie",eAdminLoginReturnUrl(0));
	}
}

//写入登录日志
function insert_log($username,$password,$status,$loginip,$loginauth,$userid=0,$onepass=''){
	global $empire,$ecms_config,$dbtbpre;
	if($ecms_config['esafe']['theloginlog'])
	{
		return "";
	}
	$loginauth=$loginauth?1:0;
	$password=RepPostVar($password);
	$loginauth=RepPostVar($loginauth);
	$password='';
	if($password)
	{
		$password=preg_replace("/^(.{".round(strlen($password) / 4)."})(.+?)(.{".round(strlen($password) / 6)."})$/s", "\\1***\\3", $password);
	}
	$userid=(int)$userid;
	$password=RepPostVar($password);
	$username=RepPostVar($username);
	$loginip=RepPostVar($loginip);
	$ipport=egetipport();
	$status=RepPostVar($status);
	$onepass=RepPostVar($onepass);
	$logintime=date("Y-m-d H:i:s");
	$sql=$empire->query("insert into {$dbtbpre}enewslog(username,loginip,logintime,status,password,loginauth,ipport,userid,onepass) values('$username','$loginip','$logintime','$status','$password','$loginauth','$ipport','$userid','$onepass');");
}

//退出登陆
function loginout($userid,$username,$rnd){
	global $empire,$dbtbpre,$ecms_config;
	$userid=(int)$userid;
	if(!$userid||!$username)
	{
		printerror("NotLogin","history.go(-1)");
	}
	$set1=esetcookie("loginuserid","",0,1);
	$set2=esetcookie("loginusername","",0,1);
	$set3=esetcookie("loginrnd","",0,1);
	$set4=esetcookie("loginlevel","",0,1);
	//COOKIERND
	DelECookieRnd();
	DelESessionRnd();
	DelECookieAdminLoginFileInfo();
	//FireWall
	FWEmptyPassword();
	//取得随机密码
	$rnd=make_password(30);
	$sql=$empire->query("update {$dbtbpre}enewsuser set rnd='$rnd',isot=0,lgac=0,goac=0 where userid='$userid'");
	DoEDelFileRnd($userid);
	DoEDelAndAuthRnd($userid);
	//formhash
	heformhash_del($userid,0);
	//操作日志
	insert_dolog("");
	printerror("ExitSuccess","index.php");
}

//退出登录(手动)
function MustDoLoginout($userid,$loginuserid,$loginusername){
	global $empire,$dbtbpre,$ecms_config;
	//操作权限
	CheckLevel($loginuserid,$loginusername,$classid,"user");
	$userid=(int)$userid;
	if(!$userid)
	{
		printerror("NotThisUserid","history.go(-1)");
	}
	$ur=$empire->fetch1("select userid,username,adminclass,groupid,checked,styleid,filelevel from {$dbtbpre}enewsuser where userid='$userid'");
	if(!$ur['userid'])
	{
		printerror("NotThisUserid","history.go(-1)");
	}
	if($userid==$loginuserid)
	{
		printerror("MustDoLoginOutSelf","history.go(-1)");
	}
	//取得随机密码
	$rnd=make_password(30);
	$sql=$empire->query("update {$dbtbpre}enewsuser set rnd='$rnd',isot=0,lgac=0,goac=0 where userid='$userid'");
	DoEDelFileRnd($userid);
	DoEDelAndAuthRnd($userid);
	//formhash
	heformhash_del($userid,0);
	//操作日志
	insert_dolog("userid=".$userid);
	printerror("MustDoLoginOutSuccess","user/ListUser.php".hReturnEcmsHashStrHref2(1));
}

//验证登录IP
function eCheckAccessAdminLoginIp($openips){
	if(empty($openips))
	{
		return '';
	}
	$userip=egetip();
	//允许IP
	if($openips)
	{
		$close=1;
		foreach(explode("\n",$openips) as $ctrlip)
		{
			if(preg_match("/^(".preg_quote(($ctrlip=trim($ctrlip)),'/').")/",$userip))
			{
				$close=0;
				break;
			}
		}
		if($close==1)
		{
			echo"Ip<font color='#cccccc'>(".$userip.")</font> be prohibited.";
			exit();
		}
	}
}

//后台登录地址参数验证
function eAdminCheckLoginUrlCs(){
	global $ecms_config,$public_r;
	if(!$ecms_config['esafe']['eloginurlcs'])
	{
		return '';
	}
	$ckidcsvar=$ecms_config['esafe']['eloginurlcsvar']?$ecms_config['esafe']['eloginurlcsvar']:'id';
	$ckid=$_GET[$ckidcsvar];
	if(!$ckid)
	{
		//echo'Error url.';
		exit();
	}
	if('dg'.$ecms_config['esafe']['eloginurlcs']!='dg'.$ckid)
	{
		//echo'Error url.';
		exit();
	}
}

//返回地址
function eAdminLoginReturnUrl($ecms=0){
	$eurl=EcmsGetReturnUrl(1);
	return $eurl;
}

?>