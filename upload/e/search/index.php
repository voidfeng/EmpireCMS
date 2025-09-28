<?php
require("../class/connect.php");
require("../data/dbcache/class.php");
require("../class/q_functions.php");
eCheckCloseMods('search');//关闭模块
$link=db_connect();
$empire=new mysqlquery();

//处理关键字
function SearchDoKeyboardVar($keyboard){
	$keyboard=str_replace('  ','',$keyboard);
	$keyboard=RepPostVar2(trim($keyboard));
	return $keyboard;
}

//返回SQL
function SearchDoKeyboard($f,$hh,$keyboard){
	$where='';
	$keyboard=SearchDoKeyboardVar($keyboard);
	if(empty($keyboard))
	{
		return "";
	}
	if(!empty($hh))
	{
		if($hh=='LT')//小于
		{
			$where=$f."<'".$keyboard."'";
		}
		elseif($hh=='GT')//大于
		{
			$where=$f.">'".$keyboard."'";
		}
		elseif($hh=='EQ')//等于
		{
			$where=$f."='".$keyboard."'";
		}
		elseif($hh=='LE')//小于等于
		{
			$where=$f."<='".$keyboard."'";
		}
		elseif($hh=='GE')//大于等于
		{
			$where=$f.">='".$keyboard."'";
		}
		elseif($hh=='NE')//不等于
		{
			$where=$f."<>'".$keyboard."'";
		}
		elseif($hh=='IN')//包含
		{
			$kr=explode(' ',$keyboard);
			$kcount=count($kr);
			$kbs='';
			$dh='';
			for($i=0;$i<$kcount;$i++)
			{
				$kr[$i]=(float)$kr[$i];
				if(empty($kr[$i]))
				{
					continue;
				}
				if($kbs)
				{
					$dh=',';
				}
				$kbs.=$dh."'".$kr[$i]."'";
			}
			if($kbs)
			{
				$where=$f." IN (".$kbs.")";
			}
			else
			{
				return '';
			}
		}
		elseif($hh=='BT')//范围
		{
			$keyboard=ltrim($keyboard);
			if(!strstr($keyboard,' '))
			{
				return '';
			}
			$kr=explode(' ',$keyboard);
			$kr[0]=(float)$kr[0];
			$kr[1]=(float)$kr[1];
			if(!trim($kr[0])||!trim($kr[1]))
			{
				return '';
			}
			$where=$f." BETWEEN '".$kr[0]."' and '".$kr[1]."'";
		}
		else//相似
		{
			$where=$f." LIKE '%".$keyboard."%'";
		}
	}
	else
	{
		$where=$f." LIKE '%".$keyboard."%'";
	}
	return $where;
}

//变量
if($_GET['searchget']==1)
{
	$_POST=$_GET;
}

$ip=egetip();
$searchtime=time();
$getvar=$_POST['getvar'];
if(empty($getvar))
{
	$getfrom="history.go(-1)";
	$dogetvar='';
}
else
{
	$getfrom="../../search/";
	$dogetvar="&getvar=1";
}
//返回
$getfrom=DoingReturnUrl($getfrom,$_POST['ecmsfrom']);
//搜索间隔
$lastsearchtime=intval(getcvar('lastsearchtime'));
if($lastsearchtime)
{
	if($searchtime-$lastsearchtime<$public_r['searchtime'])
	{
		printerror("SearchOutTime",$getfrom,1);
	}
}
//searchurl
if($_POST['keyboard'])
{
	ePostGoSearchUrl($_POST['keyboard'],0);
}
//搜索用户组
if($public_r['searchgroupid'])
{
	$psearchgroupid=$public_r['searchgroupid'];
	include('../member/class/user.php');
	@include("../data/dbcache/MemberLevel.php");
	$searchgroupid=(int)getcvar('mlgroupid');
	$user=islogin();
	$mvgresult=eMember_ReturnCheckMVGroup($user,$public_r['searchgroupid']);
	if($mvgresult<>'empire.cms')
	{
		printerror("NotLevelToSearch",$getfrom,1);
	}
}
//搜索字段
$searchclass=$_POST['show'];
if(empty($searchclass))
{
	printerror("SearchNotRecord",$getfrom,1);
}
if(!is_array($searchclass))
{
	if(empty($searchclass)||@strstr($searchclass," "))
	{
		printerror("SearchNotRecord",$getfrom,1);
	}
}
//时间范围
$add='';
$addtime='';
$starttime=RepPostVar($_POST['starttime']);
if(empty($starttime))
{
	$starttime="0000-00-00";
}
$endtime=RepPostVar($_POST['endtime']);
if(empty($endtime))
{
	$endtime="0000-00-00";
}
if($endtime!="0000-00-00")
{
	$addtime=" and (newstime BETWEEN '".to_time($starttime." 00:00:00")."' and '".to_time($endtime." 23:59:59")."')";
}
//价格
$addprice='';
$startprice=(int)$_POST['startprice'];
$endprice=(int)$_POST['endprice'];
if($endprice)
{
	$addprice=" and (price BETWEEN ".$startprice." and ".$endprice.")";
}
//搜索栏目及表
$classid=RepPostVar($_POST['classid']);
$s_tbname=RepPostVar($_POST['tbname']);
$s_tempid=(int)$_POST['tempid'];
$trueclassid=0;
if($classid)//按栏目
{
	if(strstr($classid,","))//多栏目
	{
		$son_r=sys_ReturnMoreClass($classid,1);
		$trueclassid=$son_r[0];
		$add.=' and ('.$son_r[1].')';
	}
	else
	{
		$trueclassid=intval($classid);
		$add.=$class_r[$trueclassid]['islast']?" and classid='$trueclassid'":" and ".ReturnClass($class_r[$trueclassid]['sonclass']);
	}
	$tbname=$class_r[$trueclassid]['tbname'];
	$modid=$class_r[$trueclassid]['modid'];
}
elseif($s_tbname)//按数据表
{
	$tbnamenum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewstable where tbname='$s_tbname'".do_dblimit_cone());
	if(!$tbnamenum)
	{
		printerror("SearchNotRecord",$getfrom,1);
	}
	$tbname=$s_tbname;
	//模型id
	$thestemp_r=$empire->fetch1("select modid from ".GetTemptb("enewssearchtemp")." where tempid='$s_tempid'");
	if(empty($thestemp_r['modid']))
	{
		printerror("SearchNotRecord",$getfrom,1);
	}
	$modid=$thestemp_r['modid'];
}
else
{
	$tbname=$public_r['tbname'];
	$modid=0;
}
//表不存在
if(empty($tbname)||InfoIsInTable($tbname))
{
	printerror("SearchNotRecord",$getfrom,1);
}
$modid=(int)$modid;
if(!eInfoHaveTable($tbname,0))
{
	printerror("SearchNotRecord",$getfrom,1);
}
//标题分类
$ttid=RepPostVar($_POST['ttid']);
$truettid=0;
if($ttid)
{
	if(strstr($ttid,","))//多标题分类
	{
		$son_r=sys_ReturnMoreTT($ttid);
		$truettid=$son_r[0];
		$add.=' and ('.$son_r[1].')';
	}
	else
	{
		$truettid=intval($ttid);
		$add.=" and ttid='$truettid'";
	}
}
//会员
$member=$_POST['member'];
if($member==1)
{
	$add.=' and ismember=1';
}
elseif($member==2)
{
	$add.=' and ismember=0';
}
//模型
$tempr=array();
if(empty($class_r[$trueclassid]['searchtempid']))
{
	if(empty($modid))
	{
		$tempr=$empire->fetch1("select modid from ".GetTemptb("enewssearchtemp")." where isdefault=1".do_dblimit_one());
	}
	else
	{
		$tempr['modid']=$modid;
	}
}
else
{
	$tempr['modid']=$modid;
}
$tempr['modid']=(int)$tempr['modid'];
$trueclassid=(int)$trueclassid;
$truettid=(int)$truettid;

//关键字
$keyboard=$_POST['keyboard'];
$keyboardone=0;
if(is_array($keyboard))
{}
elseif(strstr($keyboard,','))
{
	$keyboard=explode(',',$keyboard);
}
else
{
	$keyboard=trim($keyboard);
	$len=strlen($keyboard);
	if($len<$public_r['min_keyboard']||$len>$public_r['max_keyboard'])
	{
		printerror("MinKeyboard",$getfrom,1);
	}
	$keyboardone=1;
}

//符号
$hh=$_POST['hh'];
$hhone=0;
if(is_array($hh))
{}
elseif(strstr($hh,','))
{
	$hh=explode(',',$hh);
}
else
{
	$hhone=1;
}

//字段
if(!is_array($searchclass))
{
	$searchclass=explode(',',$searchclass);
}

$andor=$_POST['andor'];
$andor=$andor=='and'?'and':'or';

$mr=$empire->fetch1("select searchvar,tbname from {$dbtbpre}enewsmod where mid='".$tempr['modid']."'");
if(!strstr($mr['searchvar'],",price,"))//是否包含价格
{
	$addprice="";
	$startprice=0;
	$endprice=0;
}
//搜索特殊字段
if($ecms_config['db']['usedb']=='pgsql')
{
	$mr['searchvar'].='keyboard,username,';
}
else
{
	$mr['searchvar'].='id,keyboard,userid,username,';
}
$where='';
$newsearchclass='';
$count=count($searchclass);
//fmax
if($public_r['searchfmax'])
{
	if($count>$public_r['searchfmax'])
	{
		printerror("SearchNotRecord",$getfrom,1);
	}
}
for($i=0;$i<$count;$i++)
{
	if(empty($searchclass[$i]))
	{
		continue;
	}
	$searchclass[$i]=str_replace(',','',$searchclass[$i]);
	if(!strstr($mr['searchvar'],",".$searchclass[$i].","))
	{
		continue;
	}
	$searchclass[$i]=RepPostVar($searchclass[$i]);
	if(stristr(','.$newsearchclass.',',','.$searchclass[$i].','))
	{
		continue;
	}
	$dh=empty($newsearchclass)?'':',';
	$newsearchclass.=$dh.$searchclass[$i];
	$dohh=$hhone==1?$hh:$hh[$i];
	$dokeyboard=$keyboardone==1?$keyboard:$keyboard[$i];
	if(strlen($dokeyboard)>$public_r['max_keyboard'])
	{
		printerror("MinKeyboard",$getfrom,1);
	}
	$onewhere=SearchDoKeyboard($searchclass[$i],$dohh,$dokeyboard);
	if($onewhere)
	{
		$or=empty($where)?'':' '.$andor.' ';
		$where.=$or.'('.$onewhere.')';
	}
}
//参数错
if(empty($newsearchclass))
{
	printerror("SearchNotRecord",$getfrom,1);
}
if($where)
{
	$add.=' and ('.$where.')';
}
$allwhere=$add.$addtime.$addprice;
$keyboard=$keyboardone==1?SearchDoKeyboardVar($keyboard):'';
$andsql=addslashes($allwhere);
if(strlen($newsearchclass)>250||strlen($classid)>200||strlen($andsql)>3000||strlen($keyboard)>100||strlen($ttid)>200)
{
	printerror("SearchNotRecord",$getfrom,1);
}
//排序
$orderby=RepPostVar($_POST['orderby']);
$myorder=(int)$_POST['myorder'];
$myorder=$myorder?1:0;
if($orderby)
{
	$orderr=ReturnDoOrderF($tempr['modid'],$orderby,$myorder);
	$orderby=$orderr['returnf'];
}
else
{
	$orderby='newstime';
}
//验证码
$qmyorder=$myorder?$orderby:$orderby.' desc';
$query="select count(*) as total from {$dbtbpre}ecms_".$tbname.($allwhere?' where '.substr($allwhere,5):'');
$queryids="select id from {$dbtbpre}ecms_".$tbname.($allwhere?' where '.substr($allwhere,5):'')." order by ".$qmyorder."".do_dblimit($public_r['searchupnum']);
$checkpass=md5($allwhere.$tbname.$orderby.$myorder);
$search_r=$empire->fetch1("select searchid,searchtime from {$dbtbpre}enewssearch where checkpass='$checkpass'".do_dblimit_one());
$searchid=$search_r['searchid'];
//是否有历史记录
if($searchid)
{
	if($searchtime-$search_r['searchtime']>$public_r['searchuptime']*60)
	{
		$getids_r=eSqlToGetids($queryids,0,$public_r['searchupnum'],3000,1);
		$search_num=(int)$getids_r['num'];
		$andsql=addslashes($getids_r['ids']);
		$sql=$empire->query("update {$dbtbpre}enewssearch set searchtime='$searchtime',result_num='$search_num',onclick=onclick+1,orderby='$orderby',myorder='$myorder',tempid='$s_tempid',andsql='$andsql' where searchid='$searchid'");
		if(empty($search_num))
		{
			$searchid=0;
		}
	}
}
else
{
	$getids_r=eSqlToGetids($queryids,0,$public_r['searchupnum'],3000,1);
	$search_num=(int)$getids_r['num'];
	$andsql=addslashes($getids_r['ids']);
	if(empty($search_num))
	{
		$searchid=0;
	}
	else
	{
		$iskey=$keyboardone==1?0:1;
		$sql=$empire->updatesql("insert into {$dbtbpre}enewssearch(searchtime,keyboard,searchclass,result_num,searchip,classid,onclick,orderby,myorder,checkpass,tbname,tempid,iskey,andsql,trueclassid) values('$searchtime','$keyboard','$newsearchclass','$search_num','$ip','$classid',1,'$orderby','$myorder','$checkpass','$tbname','$s_tempid','$iskey','$andsql','$trueclassid')","ins");
		$searchid=$empire->lastid($dbtbpre.'enewssearch','searchid');
	}
}
//设置最后搜索时间
$set1=esetcookie("lastsearchtime",$searchtime,$searchtime+3600*24);
if(!$searchid)
{
	printerror("SearchNotRecord",$getfrom,1);
}
else
{
	Header("Location:result/?searchid=$searchid".$dogetvar);
}
db_close();
$empire=null;
?>