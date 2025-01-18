<?php

//处理父信息字段变量
function DoPostFzinfoVar($add){
	$edb_ir['classid']=(int)$add['classid'];
	$edb_ir['id']=(int)$add['id'];
	$edb_ir['cid']=(int)$add['cid'];
	$edb_ir['sclassid']=(int)$add['sclassid'];
	$edb_ir['usefz']=(int)$add['usefz'];
	$edb_ir['fcid']=(int)$add['fcid'];
	$edb_ir['qadd']=(int)$add['qadd'];
	return $edb_ir;
}

//增加父信息
function AddFzinfo($add,$userid,$username){
	global $empire,$dbtbpre,$class_r,$public_r;
	$edb_ir=DoPostFzinfoVar($add);
	$edb_ir['pubid']=ReturnInfoPubid($edb_ir['classid'],$edb_ir['id']);
	$edb_ir['mid']=(int)$class_r[$edb_ir['classid']]['modid'];
	$edb_ir['fzstb']=(int)$public_r['fzdeftb'];
	$tbname=$class_r[$edb_ir['classid']]['tbname'];
	$edb_ir['tid']=(int)$class_r[$edb_ir['classid']]['tid'];
	if(!$edb_ir['classid']||!$edb_ir['id']||!$edb_ir['pubid']||!$edb_ir['mid']||!$edb_ir['fzstb']||!$tbname||!$edb_ir['tid'])
	{
		printerror("EmptyFzinfo","history.go(-1)");
    }
	CheckLevel($userid,$username,$classid,"fzinfo");
	//父信息
	$index_infor=$empire->fetch1("select id,checked from {$dbtbpre}ecms_".$tbname."_index where id='".$edb_ir['id']."'");
	if(!$index_infor['id'])
	{
		printerror('ErrorUrl','');
	}
	$fzinfor=$empire->fetch1("select pubid from {$dbtbpre}enewsfz_info where pubid='".$edb_ir['pubid']."'".do_dblimit_one());
	if($fzinfor['pubid'])
	{
		printerror('ReFzinfo','');
	}
	//变量
	$edb_ir['fclast']=time();
	$sql=$empire->query("insert into {$dbtbpre}enewsfz_info(pubid,id,classid,mid,fzstb,cid,usefz,sclassid,fclast,tid,qadd) values('".$edb_ir['pubid']."','".$edb_ir['id']."','".$edb_ir['classid']."','".$edb_ir['mid']."','".$edb_ir['fzstb']."','".$edb_ir['cid']."','".$edb_ir['usefz']."','".$edb_ir['sclassid']."','".$edb_ir['fclast']."','".$edb_ir['tid']."','".$edb_ir['qadd']."');");
	//更新信息表
	PubFzInfoUpEfz($edb_ir['classid'],$edb_ir['id'],$edb_ir['pubid'],1);
	//是否开启
	PubFzinfoSetOpen();
	if($sql)
	{
		//操作日志
	    insert_dolog("pubid=".$edb_ir['pubid']);
		printerror("AddFzinfoSuccess","ListFzinfo.php?fcid=".$edb_ir['fcid'].hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改父信息
function EditFzinfo($add,$userid,$username){
	global $empire,$dbtbpre,$class_r,$public_r;
	$edb_ir=DoPostFzinfoVar($add);
	$edb_ir['pubid']=ReturnInfoPubid($edb_ir['classid'],$edb_ir['id']);
	$edb_ir['mid']=(int)$class_r[$edb_ir['classid']]['modid'];
	$edb_ir['fzstb']=(int)$public_r['fzdeftb'];
	$tbname=$class_r[$edb_ir['classid']]['tbname'];
	$edb_ir['tid']=(int)$class_r[$edb_ir['classid']]['tid'];
	if(!$edb_ir['classid']||!$edb_ir['id']||!$edb_ir['pubid']||!$edb_ir['mid']||!$edb_ir['fzstb']||!$tbname||!$edb_ir['tid'])
	{
		printerror("EmptyFzinfo","history.go(-1)");
    }
	CheckLevel($userid,$username,$classid,"fzinfo");
	//父信息
	$index_infor=$empire->fetch1("select id,classid,checked from {$dbtbpre}ecms_".$tbname."_index where id='".$edb_ir['id']."'");
	if(!$index_infor['id'])
	{
		printerror('ErrorUrl','');
	}
	if($index_infor['classid'])
	{
		if($index_infor['classid']!=$edb_ir['classid'])
		{
			$edb_ir['classid']=$index_infor['classid'];
		}
		$mid=(int)$class_r[$index_infor['classid']]['modid'];
		if($mid&&$mid!=$edb_ir['mid'])
		{
			$edb_ir['mid']=$mid;
		}
	}
	//变量
	$edb_ir['fclast']=time();
	//修改
	$sql=$empire->query("update {$dbtbpre}enewsfz_info set classid='".$edb_ir['classid']."',mid='".$edb_ir['mid']."',cid='".$edb_ir['cid']."',sclassid='".$edb_ir['sclassid']."',usefz='".$edb_ir['usefz']."',fclast='".$edb_ir['fclast']."',qadd='".$edb_ir['qadd']."' where pubid='".$edb_ir['pubid']."'");
	if($sql)
	{
		insert_dolog("pubid=".$edb_ir['pubid']);//操作日志
		printerror("EditFzinfoSuccess","ListFzinfo.php?fcid=".$edb_ir['fcid'].hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","");
	}
}

//取消父信息
function DelFzinfo($add,$userid,$username){
	global $empire,$dbtbpre,$class_r,$emod_r,$etable_r;
	$edb_ir['classid']=(int)$add['classid'];
	$edb_ir['id']=(int)$add['id'];
	$edb_ir['pubid']=ReturnInfoPubid($edb_ir['classid'],$edb_ir['id']);
	$edb_ir['fcid']=(int)$add['fcid'];
	if(!$edb_ir['classid']||!$edb_ir['id']||!$edb_ir['pubid'])
	{
		printerror("NotDelFzinfoid","");
	}
	CheckLevel($userid,$username,$classid,"fzinfo");
	//父信息
	$r=$empire->fetch1("select * from {$dbtbpre}enewsfz_info where pubid='".$edb_ir['pubid']."'");
	if(empty($r['pubid']))
	{
		printerror("NotDelFzinfoid","");
	}
	//取消父信息
	PubDelFzInfo($r['classid'],$r['id'],$r['pubid'],1,1);
	//是否开启
	PubFzinfoSetOpen();
	
	insert_dolog("pubid=".$edb_ir['pubid']);//操作日志
	printerror("DelFzinfoSuccess","ListFzinfo.php?fcid=".$edb_ir['fcid'].hReturnEcmsHashStrHref2(0));
}


//处理分类字段变量
function DoPostFzDataVar($add){
	$edb_ir['fzclassid']=(int)$add['fzclassid'];
	$edb_ir['fzid']=(int)$add['fzid'];
	$edb_ir['cid']=(int)$add['cid'];
	$edb_ir['cname']=hRepPostStr($add['cname'],1);
	$edb_ir['bcid']=(int)$add['bcid'];
	$edb_ir['myorder']=(int)$add['myorder'];
	$edb_ir['islist']=(int)$add['islist'];
	$edb_ir['lencord']=(int)$add['lencord'];
	$edb_ir['classtempid']=(int)$add['classtempid'];
	$edb_ir['listtempid']=(int)$add['listtempid'];
	$edb_ir['bdinfoid']=RepPostVar($add['bdinfoid']);
	$edb_ir['isopen']=(int)$add['isopen'];
	$edb_ir['cdiy']=eaddslashes(RepPhpAspJspcode($add['cdiy']));
	$edb_ir['sreorder']=(int)$add['sreorder'];
	$edb_ir['reorder']=$edb_ir['sreorder']==1?'newstime ASC':'newstime DESC';
	$edb_ir['qadd']=(int)$add['qadd'];
	return $edb_ir;
}

//增加子信息分类
function AddFzDataClass($add,$userid,$username){
	global $empire,$dbtbpre,$class_r;
	$edb_ir=DoPostFzDataVar($add);
	$edb_ir['pubid']=ReturnInfoPubid($edb_ir['fzclassid'],$edb_ir['fzid']);
	$tbname=$class_r[$edb_ir['fzclassid']]['tbname'];
	if(!$edb_ir['fzclassid']||!$edb_ir['fzid']||!$edb_ir['pubid']||!$tbname||!$edb_ir['cname'])
	{
		printerror("EmptyFzDataClass","history.go(-1)");
    }
	CheckLevel($userid,$username,$classid,"fzdatac");
	//父信息
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='".$edb_ir['pubid']."'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		printerror('ErrorUrl','');
	}
	//变量
	$edb_ir['islast']=1;
	$sql=$empire->updatesql("insert into {$dbtbpre}enewsfz_class(pubid,bcid,cname,islast,myorder,islist,lencord,classtempid,listtempid,bdinfoid,isopen,reorder,cdiy,qadd) values('".$edb_ir['pubid']."','".$edb_ir['bcid']."','".$edb_ir['cname']."','".$edb_ir['islast']."','".$edb_ir['myorder']."','".$edb_ir['islist']."','".$edb_ir['lencord']."','".$edb_ir['classtempid']."','".$edb_ir['listtempid']."','".$edb_ir['bdinfoid']."','".$edb_ir['isopen']."','".$edb_ir['reorder']."','".$edb_ir['cdiy']."','".$edb_ir['qadd']."');","ins");
	$cid=$empire->lastid($dbtbpre.'enewsfz_class','cid');
	//更新父分类
	if($edb_ir['bcid'])
	{
		$empire->query("update {$dbtbpre}enewsfz_class set islast=0 where cid='".$edb_ir['bcid']."'");
	}
	//更新父信息缓存
	PubFzInfoUpFclast("pubid='".$edb_ir['pubid']."'");
	if($sql)
	{
		//操作日志
	    insert_dolog("cid=".$cid."<br>cname=".$edb_ir['cname']);
		printerror("AddFzDataClassSuccess","ListFzDataClass.php?fzclassid=".$edb_ir['fzclassid']."&fzid=".$edb_ir['fzid'].hReturnEcmsHashStrHref2(0));
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改子信息分类
function EditFzDataClass($add,$userid,$username){
	global $empire,$dbtbpre,$class_r;
	$edb_ir=DoPostFzDataVar($add);
	$edb_ir['pubid']=ReturnInfoPubid($edb_ir['fzclassid'],$edb_ir['fzid']);
	$tbname=$class_r[$edb_ir['fzclassid']]['tbname'];
	if(!$edb_ir['cid']||!$edb_ir['fzclassid']||!$edb_ir['fzid']||!$edb_ir['pubid']||!$tbname||!$edb_ir['cname'])
	{
		printerror("EmptyFzDataClass","history.go(-1)");
    }
	CheckLevel($userid,$username,$classid,"fzdatac");
	//父信息
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='".$edb_ir['pubid']."'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		printerror('ErrorUrl','');
	}
	$cr=$empire->fetch1("select cid,pubid,bcid,islast,islist from {$dbtbpre}enewsfz_class where cid='".$edb_ir['cid']."'".do_dblimit_one());
	if(!$cr['cid'])
	{
		printerror('ErrorUrl','');
	}
	//变量
	$edb_ir['islast']=$cr['islast'];
	if($edb_ir['bcid'])
	{
		$edb_ir['islast']=1;
	}
	//修改
	$sql=$empire->query("update {$dbtbpre}enewsfz_class set bcid='".$edb_ir['bcid']."',cname='".$edb_ir['cname']."',islast='".$edb_ir['islast']."',myorder='".$edb_ir['myorder']."',islist='".$edb_ir['islist']."',lencord='".$edb_ir['lencord']."',classtempid='".$edb_ir['classtempid']."',listtempid='".$edb_ir['listtempid']."',bdinfoid='".$edb_ir['bdinfoid']."',isopen='".$edb_ir['isopen']."',reorder='".$edb_ir['reorder']."',cdiy='".$edb_ir['cdiy']."',qadd='".$edb_ir['qadd']."' where cid='".$edb_ir['cid']."'");
	//更新父分类
	if($edb_ir['bcid'])
	{
		$empire->query("update {$dbtbpre}enewsfz_class set islast=0 where cid='".$edb_ir['bcid']."'");
	}
	if($edb_ir['bcid']!=$cr['bcid'])
	{
		if($cr['bcid'])
		{
			$bcr=$empire->fetch1("select cid from {$dbtbpre}enewsfz_class where bcid='".$cr['bcid']."'".do_dblimit_one());
			if(!$bcr['cid'])
			{
				$empire->query("update {$dbtbpre}enewsfz_class set islast=0 where cid='".$cr['bcid']."'");
			}
		}
		//子信息
		$empire->query("update {$dbtbpre}enewsfz_data_check set bcid='".$edb_ir['bcid']."' where cid='".$edb_ir['cid']."'");
		if($fzinfor['fzstb'])
		{
			$empire->query("update {$dbtbpre}enewsfz_data_".$fzinfor['fzstb']." set bcid='".$edb_ir['bcid']."' where cid='".$edb_ir['cid']."'");
		}
	}
	//更新父信息缓存
	PubFzInfoUpFclast("pubid='".$edb_ir['pubid']."'");
	if($sql)
	{
		insert_dolog("cid=".$edb_ir['cid']."<br>cname=".$edb_ir['cname']);//操作日志
		printerror("EditFzDataClassSuccess","ListFzDataClass.php?fzclassid=".$edb_ir['fzclassid']."&fzid=".$edb_ir['fzid'].hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","");
	}
}

//删除子信息分类
function DelFzDataClass($add,$userid,$username){
	global $empire,$dbtbpre,$class_r;
	$edb_ir['fzclassid']=(int)$add['fzclassid'];
	$edb_ir['fzid']=(int)$add['fzid'];
	$edb_ir['cid']=(int)$add['cid'];
	$edb_ir['pubid']=ReturnInfoPubid($edb_ir['fzclassid'],$edb_ir['fzid']);
	$tbname=$class_r[$edb_ir['fzclassid']]['tbname'];
	if(!$edb_ir['cid']||!$edb_ir['fzclassid']||!$edb_ir['fzid']||!$edb_ir['pubid']||!$tbname)
	{
		printerror("NotDelFzDataClassid","");
	}
	CheckLevel($userid,$username,$classid,"fzdatac");
	//父信息
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='".$edb_ir['pubid']."'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		//printerror('ErrorUrl','');
	}
	$cr=$empire->fetch1("select cid,pubid,bcid,cname,islast,islist from {$dbtbpre}enewsfz_class where cid='".$edb_ir['cid']."'".do_dblimit_one());
	if(!$cr['cid'])
	{
		printerror("NotDelFzDataClassid","");
	}
	//删除
	$sql=$empire->query("delete from {$dbtbpre}enewsfz_class where cid='".$edb_ir['cid']."'");
	$sql2=$empire->query("delete from {$dbtbpre}enewsfz_class where bcid='".$edb_ir['cid']."'");
	//父分类
	if($cr['bcid'])
	{
		$bcr=$empire->fetch1("select cid from {$dbtbpre}enewsfz_class where bcid='".$cr['bcid']."'".do_dblimit_one());
		if(!$bcr['cid'])
		{
			$empire->query("update {$dbtbpre}enewsfz_class set islast=0 where cid='".$cr['bcid']."'");
		}
	}
	//子信息
	if($cr['bcid'])
	{
		$datawhere="cid='".$edb_ir['cid']."'";
		$upstr='cid=0';
	}
	else
	{
		$datawhere="bcid='".$edb_ir['cid']."'";
		$upstr='bcid=0,cid=0';
	}
	$empire->query("update {$dbtbpre}enewsfz_data_check set ".$upstr." where ".$datawhere);
	if($fzinfor['fzstb'])
	{
		$empire->query("update {$dbtbpre}enewsfz_data_".$fzinfor['fzstb']." set ".$upstr." where ".$datawhere);
	}
	//更新父信息缓存
	PubFzInfoUpFclast("pubid='".$edb_ir['pubid']."'");
	if($sql)
	{
		insert_dolog("cid=".$edb_ir['cid']."<br>cname=".$cr['cname']);//操作日志
		printerror("DelFzDataClassSuccess","ListFzDataClass.php?fzclassid=".$edb_ir['fzclassid']."&fzid=".$edb_ir['fzid'].hReturnEcmsHashStrHref2(0));
	}
	else
	{
		printerror("DbError","");
	}
}

//修改子信息分类顺序
function EditFzDataClassOrder($add,$userid,$username){
	global $empire,$dbtbpre,$class_r;
	$edb_ir['fzclassid']=(int)$add['fzclassid'];
	$edb_ir['fzid']=(int)$add['fzid'];
	$edb_ir['pubid']=ReturnInfoPubid($edb_ir['fzclassid'],$edb_ir['fzid']);
	$tbname=$class_r[$edb_ir['fzclassid']]['tbname'];
	$cid=eCheckEmptyArray($add['cid']);
	$myorder=$add['myorder'];
	if(!$edb_ir['fzclassid']||!$edb_ir['fzid']||!$edb_ir['pubid']||!$tbname)
	{
		printerror('ErrorUrl','');
	}
	CheckLevel($userid,$username,$classid,"fzdatac");
	//父信息
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='".$edb_ir['pubid']."'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		//printerror('ErrorUrl','');
	}
	for($i=0;$i<count($cid);$i++)
	{
		$newmyorder=(int)$myorder[$i];
		$cid[$i]=(int)$cid[$i];
		$sql=$empire->query("update {$dbtbpre}enewsfz_class set myorder='$newmyorder' where cid='".$cid[$i]."'");
    }
	//更新父信息缓存
	PubFzInfoUpFclast("pubid='".$edb_ir['pubid']."'");
	//操作日志
	insert_dolog("");
	printerror("EditFzDataClassOrderSuccess",EcmsGetReturnUrl());
}


//推送子信息
function PushInfoToEfz($add,$userid,$username){
	global $empire,$dbtbpre,$class_r;
	$infoclassid=(int)$add['infoclassid'];
	$infotid=(int)$add['infotid'];
	$infoid=$add['infoid'];
	$sinfo=(int)$add['sinfo'];
	
	$efzid=eCheckEmptyArray($add['efzid']);
	$count=count($efzid);
	if(!$count||!$infoid)
	{
		echo"<script>window.close();</script>";
		exit();
	}
	//表名
	$tbname='';
	if($infoclassid)
	{
		$tbname=$class_r[$infoclassid]['tbname'];
	}
	elseif($infotid)
	{
		$tbr=$empire->fetch1("select tbname from {$dbtbpre}enewstable where tid='$infotid'");
		$tbname=$tbr['tbname'];
	}
	if(!$tbname)
	{
		printerror('ErrorUrl','');
	}
	
	//ID
	$infoid=eReturnInids($infoid);
	$where='id in ('.$infoid.')';
	$fzids='';
	for($i=0;$i<$count;$i++)
	{
		$fzidr=explode('|',$efzid[$i]);
		$fzclassid=(int)$fzidr[0];
		$fzid=(int)$fzidr[1];
		$fzbcid=(int)$fzidr[2];
		$fzcid=(int)$fzidr[3];
		if(!$fzclassid||!$fzid)
		{
			continue;
		}
		$fzids.=$dh.$fzclassid.'.'.$fzid;
		$dh=',';
		PubAddMoreInfoToFzData($fzclassid,$fzid,$fzbcid,$fzcid,$tbname,$where,1,1);
	}
	//操作日志
	insert_dolog("classid=$infoclassid&tid=$infotid<br>fzid=".$fzids."<br>id=".$infoid);
	echo"<script>alert('推送成功');window.close();</script>";
	exit();
}

//移除子信息
function DoDelFzData($add,$userid,$username){
	global $empire,$dbtbpre,$class_r;
	CheckLevel($userid,$username,$classid,"fzdata");
	$fzclassid=(int)$add['fzclassid'];
	$fzid=(int)$add['fzid'];
	$ecmscheck=(int)$add['ecmscheck'];
	$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
	$tbname=$class_r[$fzclassid]['tbname'];
	$zid=eCheckEmptyArray($add['zid']);
	$count=count($zid);
	if(!$count||!$fzclassid||!$fzid||!$fzpubid||!$tbname)
	{
		printerror('EmptyFzDataZid','history.go(-1)');
	}
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='$fzpubid'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		printerror('EmptyFzDataZid','');
	}
	$checked=$ecmscheck?0:1;
	//更新信息表
	$fzdatatb=$checked==1?$dbtbpre.'enewsfz_data_'.$fzinfor['fzstb']:$dbtbpre.'enewsfz_data_check';
	for($i=0;$i<$count;$i++)
	{
		$zr=explode('.',$zid[$i]);
		$zinfotid=(int)$zr[0];
		$zinfoid=(int)$zr[1];
		if(!$zinfotid||!$zinfoid)
		{
			continue;
		}
		$empire->query("delete from ".$fzdatatb." where id='$zinfoid' and tid='$zinfotid' and bpubid='$fzpubid'");
	}
	//更新父信息缓存
	PubFzInfoUpFclast("pubid='".$fzpubid."'");
	insert_dolog("classid=$fzclassid&id=$fzid");//操作日志
	printerror('DelFzDataSuccess',EcmsGetReturnUrl());
}

//转移子信息
function DoMoveFzData($add,$userid,$username){
	global $empire,$dbtbpre,$class_r;
	CheckLevel($userid,$username,$classid,"fzdata");
	$fzclassid=(int)$add['fzclassid'];
	$fzid=(int)$add['fzid'];
	$ecmscheck=(int)$add['ecmscheck'];
	$to_cid=str_replace('b','',$add['to_cid']);
	$to_cid=(int)$to_cid;
	$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
	$tbname=$class_r[$fzclassid]['tbname'];
	$zid=eCheckEmptyArray($add['zid']);
	$count=count($zid);
	if(!$count||!$fzclassid||!$fzid||!$fzpubid||!$tbname)
	{
		printerror('EmptyFzDataZid','history.go(-1)');
	}
	if(!$to_cid)
	{
		printerror('EmptyMoveFzDataCid','history.go(-1)');
	}
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='$fzpubid'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		printerror('EmptyFzDataZid','');
	}
	$cr=$empire->fetch1("select cid,pubid,bcid from {$dbtbpre}enewsfz_class where cid='$to_cid'".do_dblimit_one());
	if(!$cr['cid'])
	{
		printerror('EmptyMoveFzDataCid','history.go(-1)');
	}
	if($cr['bcid'])
	{
		$upstr="bcid='".$cr['bcid']."',cid='".$to_cid."'";
	}
	else
	{
		$upstr="bcid='".$to_cid."',cid='0'";
	}
	$fzdatatb=$ecmscheck==0?$dbtbpre.'enewsfz_data_'.$fzinfor['fzstb']:$dbtbpre.'enewsfz_data_check';
	for($i=0;$i<$count;$i++)
	{
		$zr=explode('.',$zid[$i]);
		$zinfotid=(int)$zr[0];
		$zinfoid=(int)$zr[1];
		if(!$zinfotid||!$zinfoid)
		{
			continue;
		}
		$empire->query("update ".$fzdatatb." set ".$upstr." where id='$zinfoid' and tid='$zinfotid' and bpubid='$fzpubid'");
	}
	//更新父信息缓存
	PubFzInfoUpFclast("pubid='".$fzpubid."'");
	insert_dolog("classid=$fzclassid&id=$fzid&to_cid=$to_cid");//操作日志
	printerror('MoveFzDataSuccess',EcmsGetReturnUrl());
}

//推荐子信息
function DoGoodFzData($add,$userid,$username){
	global $empire,$dbtbpre,$class_r,$emod_r,$etable_r;
	CheckLevel($userid,$username,$classid,"fzdata");
	$fzclassid=(int)$add['fzclassid'];
	$fzid=(int)$add['fzid'];
	$ecmscheck=(int)$add['ecmscheck'];
	$doing=(int)$add['doing'];
	$isgood=(int)$add['isgood'];
	$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
	$tbname=$class_r[$fzclassid]['tbname'];
	$zid=eCheckEmptyArray($add['zid']);
	$count=count($zid);
	if(!$count||!$fzclassid||!$fzid||!$fzpubid||!$tbname)
	{
		printerror('EmptyFzDataZid','history.go(-1)');
	}
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='$fzpubid'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		printerror('EmptyFzDataZid','');
	}
	$fzdatatb=$ecmscheck==0?$dbtbpre.'enewsfz_data_'.$fzinfor['fzstb']:$dbtbpre.'enewsfz_data_check';
	for($i=0;$i<$count;$i++)
	{
		$zr=explode('.',$zid[$i]);
		$zinfotid=(int)$zr[0];
		$zinfoid=(int)$zr[1];
		if(!$zinfotid||!$zinfoid)
		{
			continue;
		}
		$empire->query("update ".$fzdatatb." set isgood='$isgood' where id='$zinfoid' and tid='$zinfotid' and bpubid='$fzpubid'");
	}
	//更新父信息缓存
	PubFzInfoUpFclast("pubid='".$fzpubid."'");
	insert_dolog("classid=$fzclassid&id=$fzid&isgood=$isgood");//操作日志
	printerror('GoodFzDataSuccess',EcmsGetReturnUrl());
}

//头条子信息
function DoFirsttitleFzData($add,$userid,$username){
	global $empire,$dbtbpre,$class_r,$emod_r,$etable_r;
	CheckLevel($userid,$username,$classid,"fzdata");
	$fzclassid=(int)$add['fzclassid'];
	$fzid=(int)$add['fzid'];
	$ecmscheck=(int)$add['ecmscheck'];
	$doing=(int)$add['doing'];
	$firsttitle=(int)$add['firsttitle'];
	$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
	$tbname=$class_r[$fzclassid]['tbname'];
	$zid=eCheckEmptyArray($add['zid']);
	$count=count($zid);
	if(!$count||!$fzclassid||!$fzid||!$fzpubid||!$tbname)
	{
		printerror('EmptyFzDataZid','history.go(-1)');
	}
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='$fzpubid'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		printerror('EmptyFzDataZid','');
	}
	$fzdatatb=$ecmscheck==0?$dbtbpre.'enewsfz_data_'.$fzinfor['fzstb']:$dbtbpre.'enewsfz_data_check';
	for($i=0;$i<$count;$i++)
	{
		$zr=explode('.',$zid[$i]);
		$zinfotid=(int)$zr[0];
		$zinfoid=(int)$zr[1];
		if(!$zinfotid||!$zinfoid)
		{
			continue;
		}
		$empire->query("update ".$fzdatatb." set firsttitle='$firsttitle' where id='$zinfoid' and tid='$zinfotid' and bpubid='$fzpubid'");
	}
	//更新父信息缓存
	PubFzInfoUpFclast("pubid='".$fzpubid."'");
	insert_dolog("classid=$fzclassid&id=$fzid&firsttitle=$firsttitle");//操作日志
	printerror('FirsttitleFzDataSuccess',EcmsGetReturnUrl());
}

//修改子信息发布时间
function DoEditFzDataTime($add,$userid,$username){
	global $empire,$dbtbpre,$class_r,$emod_r,$etable_r;
	CheckLevel($userid,$username,$classid,"fzdata");
	$fzclassid=(int)$add['fzclassid'];
	$fzid=(int)$add['fzid'];
	$ecmscheck=(int)$add['ecmscheck'];
	$newstime=$add['newstime'];
	$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
	$tbname=$class_r[$fzclassid]['tbname'];
	$dozid=eCheckEmptyArray($add['dozid']);
	$count=count($dozid);
	if(!$count||!$fzclassid||!$fzid||!$fzpubid||!$tbname)
	{
		printerror('EmptyFzDataZid','history.go(-1)');
	}
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='$fzpubid'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		printerror('EmptyFzDataZid','');
	}
	$fzdatatb=$ecmscheck==0?$dbtbpre.'enewsfz_data_'.$fzinfor['fzstb']:$dbtbpre.'enewsfz_data_check';
	for($i=0;$i<$count;$i++)
	{
		$zr=explode('.',$dozid[$i]);
		$zinfotid=(int)$zr[0];
		$zinfoid=(int)$zr[1];
		if(!$zinfotid||!$zinfoid)
		{
			continue;
		}
		$donewstime=$newstime[$i]?to_time($newstime[$i]):time();
		$empire->query("update ".$fzdatatb." set newstime='$donewstime' where id='$zinfoid' and tid='$zinfotid' and bpubid='$fzpubid'");
	}
	//更新父信息缓存
	PubFzInfoUpFclast("pubid='".$fzpubid."'");
	insert_dolog("classid=$fzclassid&id=$fzid");//操作日志
	printerror('EditFzDataTimeSuccess',EcmsGetReturnUrl());
}

//导入子信息
function LoadInFzData($add,$userid,$username){
	global $empire,$dbtbpre,$class_r,$emod_r,$etable_r;
	//验证
	CheckLevel($userid,$username,$classid,"fzdata");
	
	$fzclassid=(int)$add['fzclassid'];
	$fzid=(int)$add['fzid'];
	$add['fzcid']=str_replace('b','',$add['fzcid']);
	$fzcid=(int)$add['fzcid'];
	$ecmscheck=(int)$add['ecmscheck'];
	$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
	$tbname=$class_r[$fzclassid]['tbname'];
	if(!$fzclassid||!$fzid||!$fzpubid||!$tbname)
	{
		printerror('ErrorUrl','');
	}
	//表
	$loadtbname=RepPostVar($add['tbname']);
	$infoids=RepPostVar($add['infoids']);
	if(!$tbname||!$infoids)
	{
		printerror('ErrorUrl','');
	}
	$tbr=$empire->fetch1("select tbname from {$dbtbpre}enewstable where tbname='$loadtbname'".do_dblimit_one());
	if(!$tbr['tbname'])
	{
		printerror('ErrorUrl','');
	}
	//父信息
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='$fzpubid'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		printerror('ErrorUrl','');
	}
	//分类
	$fzbcid=0;
	if($fzcid)
	{
		$cr=$empire->fetch1("select cid,pubid,bcid from {$dbtbpre}enewsfz_class where cid='$fzcid'".do_dblimit_one());
		if($cr['cid'])
		{
			$fzbcid=$cr['bcid'];
		}
		else
		{
			$fzcid=0;
		}
	}
	//ID
	$infoids=eReturnInids($infoids);
	$where='id in ('.$infoids.')';
	PubAddMoreInfoToFzData($fzclassid,$fzid,$fzbcid,$fzcid,$loadtbname,$where,1,1);
	//操作日志
	insert_dolog("classid=$fzclassid&id=$fzid&tbname=$loadtbname<br>id=$infoids");
	printerror("LoadInFzDataSuccess","ListFzData.php?fzclassid=$fzclassid&fzid=$fzid&ecmscheck=$ecmscheck".hReturnEcmsHashStrHref2(0));
}


//整理父子信息数据
function ClearFzinfo($add,$userid,$username){
	global $empire,$dbtbpre,$class_r,$etable_t,$fun_r;
	CheckLevel($userid,$username,$classid,"fzinfocl");
	$doall=(int)$add['doall'];
	if($doall==9)
	{
		ClearHaveMoreFzinfo();//清理多余
		//操作日志
		insert_dolog("");
		printerror('ClearFzinfoSuccess','ClearFzinfo.php'.hReturnEcmsHashStrHref2(1));
	}
	$line=(int)$add['line'];
	if(empty($line))
	{
		$line=1;
	}
	$startr=explode(',',$add['start']);
	$stid=(int)$startr[0];
	$sid=(int)$startr[1];
	if($stid&&$sid)
	{
		$spubid=ReturnInfoPubid(0,$sid,$stid);
	}
	else
	{
		$spubid=0;
	}
	$newpubid=0;
	$b=0;
	$sql=$empire->query("select pubid,id,classid,mid,fzstb,tid from {$dbtbpre}enewsfz_info where pubid>".$spubid." order by pubid".do_dblimit($line));
	while($r=$empire->fetch($sql))
	{
		$b=1;
		$newstart=$r['tid'].','.$r['id'];
		$newpubid=$r['pubid'];
		//整理
		$upfc=OneClearFzInfo($r);
		if($upfc)
		{
			PubFzInfoUpFclast("pubid='".$r['pubid']."'");
		}
	}
	if(empty($b))
	{
		echo"<meta http-equiv=\"refresh\" content=\"0;url=ecmsfzinfo.php?enews=ClearFzinfo&doall=9".hReturnEcmsHashStrHref(0).heformhash_get('ClearFzinfo',1)."\">";
		exit();
	}
	echo"<meta http-equiv=\"refresh\" content=\"0;url=ecmsfzinfo.php?enews=ClearFzinfo&line=$line&start=$newstart".hReturnEcmsHashStrHref(0).heformhash_get('ClearFzinfo',1)."\">".$fun_r['OneClearFzinfoSuccess']."(ID:<font color=red><b>".$newpubid."</b></font>)";
	exit();
}

//整理父子信息数据(单)
function DoOneClearFzinfo($add,$userid,$username){
	global $empire,$dbtbpre,$class_r,$etable_t;
	CheckLevel($userid,$username,$classid,"fzinfocl");
	$fzclassid=(int)$_GET['fzclassid'];
	$fzid=(int)$_GET['fzid'];
	$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
	$tbname=$class_r[$fzclassid]['tbname'];
	//父信息
	if(!$fzclassid||!$fzid||!$tbname||!$fzpubid)
	{
		printerror('ErrorUrl','');
	}
	$fzinfor=$empire->fetch1("select * from {$dbtbpre}enewsfz_info where pubid='$fzpubid'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		printerror('ErrorUrl','');
	}
	//整理
	$upfc=OneClearFzInfo($fzinfor);
	if($upfc)
	{
		PubFzInfoUpFclast("pubid='".$fzinfor['pubid']."'");
	}
	//操作日志
	insert_dolog("pubid=".$fzpubid);
	printerror('ClearFzinfoSuccess','');
}




//************************************ 子信息分表管理 ************************************

//增加子信息分表
function AddFzDataTable($add,$userid,$username){
	echo'This is the Free Version of EmpireCMS.';
	exit();
}

//默认子信息存放表
function DefFzDataTable($add,$userid,$username){
	echo'This is the Free Version of EmpireCMS.';
	exit();
}

//删除子信息分表
function DelFzDataTable($add,$userid,$username){
	echo'This is the Free Version of EmpireCMS.';
	exit();
}

?>