<?php

//是否开启父子信息
function PubFzinfoSetOpen(){
	global $empire,$dbtbpre,$public_r;
	$tr=$empire->fetch1("select openfz from {$dbtbpre}enewsfz_set".do_dblimit_one());
	$num=$empire->gettotal('select count(*) as total from '.$dbtbpre.'enewsfz_info');
	$openfz=$num?1:0;
	if($openfz!=$tr['openfz'])
	{
		$empire->query("update {$dbtbpre}enewsfz_set set openfz='$openfz'".do_dblimit_upone());
		GetConfig();//更新缓存
	}
}

//返回子信息分类
function PubReturnFzClass($pubid,$ckcid=0,$ecms=0,$addb='b'){
	global $empire,$dbtbpre;
	//子类
	$csql=$empire->query("select cid,bcid,cname from {$dbtbpre}enewsfz_class where pubid='$pubid' order by bcid desc,myorder");
	$bcr=array();
	$movebcr=array();
	$chbcr=array();
	$fzdatacs='';
	$movefzdatacs='';
	$chfzdatacs='';
	while($cr=$empire->fetch($csql))
	{
		$thiscid=$cr['bcid']==0?$addb.$cr['cid']:$cr['cid'];
		$selected='';
		if($thiscid==$ckcid)
		{
			$selected=' selected';
		}
		if(empty($cr['bcid']))//一级
		{
			if($ecms==1||$ecms==0)
			{
				$fzdatacs.="<option value='".$addb.$cr['cid']."'".$selected.">".$cr['cname']."</option>".$bcr[$cr['cid']];
			}
			if($ecms==2||$ecms==0)
			{
				$movefzdatacs.="<option value='".$addb.$cr['cid']."'>".$cr['cname']."</option>".$movebcr[$cr['cid']];
			}
			if($ecms==3)
			{
				$chval=$cr['cid'].'|0|'.$cr['cname'];
				$chfzdatacs.="<option value='".$chval."'>".$cr['cname']."</option>".$chbcr[$cr['cid']];
			}
		}
		else//二级
		{
			if($ecms==1||$ecms==0)
			{
				$bcr[$cr['bcid']].="<option value='".$cr['cid']."'".$selected."> |-".$cr['cname']."</option>";
			}
			if($ecms==2||$ecms==0)
			{
				$movebcr[$cr['bcid']].="<option value='".$cr['cid']."'> |-".$cr['cname']."</option>";
			}
			if($ecms==3)
			{
				$chval=$cr['bcid'].'|'.$cr['cid'].'|'.$cr['cname'];
				$chbcr[$cr['bcid']].="<option value='".$chval."'> |-".$cr['cname']."</option>";
			}
		}
	}
	$ret_r=array();
	$ret_r['fzdatacs']=$fzdatacs;
	$ret_r['movefzdatacs']=$movefzdatacs;
	$ret_r['chfzdatacs']=$chfzdatacs;
	return $ret_r;
}

//取消加入子信息
function PubDelFzData($where,$stb,$checked=1){
	global $empire,$dbtbpre;
	if(!$where)
	{
		return '';
	}
	$tbname=$checked==1?$dbtbpre.'enewsfz_data_'.$stb:$dbtbpre.'enewsfz_data_check';
	$empire->query("delete from ".$tbname." where ".$where);
}

//取消子信息的分表
function PubFzDataUpEfz($fzstb){
	global $empire,$dbtbpre,$class_r;
	if(!$fzstb)
	{
		return '';
	}
	$oldstr=','.$fzstb.',';
	$newstr=',';
	$tbsql=$empire->query("select tid,tbname,datatbs from {$dbtbpre}enewstable");
	while($tbr=$empire->fetch($tbsql))
	{
		$datatbname=$dbtbpre.'ecms_'.$tbr['tbname'].'_data_';
		$tr=explode(',',$tbr['datatbs']);
		$count=count($tr)-1;
		for($i=1;$i<$count;$i++)
		{
			$tr[$i]=(int)$tr[$i];
			$empire->query("update ".$datatbname.$tr[$i]." set efzstb=REPLACE(efzstb,'".$oldstr."','".$newstr."') where efzstb like '%".$oldstr."%'");
		}
		//未审核表
		$empire->query("update {$dbtbpre}ecms_".$tbr['tbname']."_check_data set efzstb=REPLACE(efzstb,'".$oldstr."','".$newstr."') where efzstb like '%".$oldstr."%'");
		//归档表
		$empire->query("update {$dbtbpre}ecms_".$tbr['tbname']."_doc_data set efzstb=REPLACE(efzstb,'".$oldstr."','".$newstr."') where efzstb like '%".$oldstr."%'");
	}
}

//取消加入父信息
function PubDelFzInfo($classid,$id,$pubid=0,$upinfo=0,$upzinfo=0){
	global $empire,$dbtbpre,$class_r;
	$classid=(int)$classid;
	$id=(int)$id;
	if(!$pubid)
	{
		$pubid=ReturnInfoPubid($classid,$id);
	}
	$pubid=RepPostVar($pubid);
	$infor=$empire->fetch1("select fzstb from {$dbtbpre}enewsfz_info where pubid='".$pubid."'".do_dblimit_one());
	if(!$infor['fzstb'])
	{
		return '';
	}
	$empire->query("delete from {$dbtbpre}enewsfz_info where pubid='".$pubid."'");
	$empire->query("delete from {$dbtbpre}enewsfz_class where pubid='".$pubid."'");
	PubDelFzData("bpubid='".$pubid."'",$infor['fzstb'],1);
	PubDelFzData("bpubid='".$pubid."'",$infor['fzstb'],0);
	if($upinfo==1)
	{
		PubFzInfoUpEfz($classid,$id,$pubid,2);
	}
	if($upzinfo==1)
	{
	}
}

//更新父子信息状态
function PubFzInfoUpEfz($classid,$id,$pubid=0,$ecms=1){
	global $empire,$dbtbpre,$class_r;
	$classid=(int)$classid;
	$id=(int)$id;
	$pubid=RepPostVar($pubid);
	$tbname=$class_r[$classid]['tbname'];
	if(!$tbname)
	{
		return '';
	}
	$upindex_r=$empire->fetch1("select classid,checked from {$dbtbpre}ecms_".$tbname."_index where id='".$id."'".do_dblimit_one());
	if(!$upindex_r['classid'])
	{
		return '';
	}
	$infotbr=ReturnInfoTbname($tbname,$upindex_r['checked'],$checkr['stb']);
	if($ecms==1)//加入
	{
		$newefz=1;
	}
	else//取消
	{
		$newefz=0;
	}
	$empire->query("update ".$infotbr['tbname']." set efz='$newefz' where id='".$id."'".do_dblimit_upone());
}

//更新父信息缓存
function PubFzInfoUpFclast($where){
	global $empire,$dbtbpre;
	if(!$where)
	{
		return '';
	}
	$fclast=time();
	$sql=$empire->query("update {$dbtbpre}enewsfz_info set fclast='".$fclast."' where ".$where);
}


//多信息加入子信息
function PubAddMoreInfoToFzData($fzclassid,$fzid,$fzbcid,$fzcid,$tbname,$where,$upfc=1,$upefz=1){
	global $empire,$dbtbpre,$class_r;
	if(empty($where))
	{
		return '';
	}
	$fzclassid=(int)$fzclassid;
	$fzid=(int)$fzid;
	$fzbcid=(int)$fzbcid;
	$fzcid=(int)$fzcid;
	$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='$fzpubid'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		return '';
	}
	$sql=$empire->query("select id,classid,checked,newstime from {$dbtbpre}ecms_".$tbname."_index where ".$where);
	while($r=$empire->fetch($sql))
	{
		$mid=(int)$class_r[$r['classid']]['modid'];
		$tid=(int)$class_r[$r['classid']]['tid'];
		$fzdatatb=$r['checked']?$dbtbpre.'enewsfz_data_'.$fzinfor['fzstb']:$dbtbpre.'enewsfz_data_check';
		$zinfor=$empire->fetch1("select id,classid,mid,bcid,cid from ".$fzdatatb." where bpubid='".$fzpubid."' and id='".$r['id']."' and tid='".$tid."'".do_dblimit_one());
		if($zinfor['id'])
		{
			if($zinfor['cid']!=$fzcid||$zinfor['bcid']!=$fzbcid||$zinfor['mid']!=$mid||$zinfor['classid']!=$r['classid'])
			{
				$empire->query("update ".$fzdatatb." set bcid='$fzbcid',cid='$fzcid',mid='$mid',classid='".$r['classid']."' where bpubid='".$fzpubid."' and id='".$r['id']."' and tid='".$tid."'".do_dblimit_upone());
			}
		}
		else
		{
			$empire->query("insert into ".$fzdatatb."(bpubid,id,classid,mid,bcid,cid,newstime,tid,isgood,firsttitle) values('".$fzpubid."','".$r['id']."','".$r['classid']."','".$mid."','".$fzbcid."','".$fzcid."','".$r['newstime']."','".$tid."',0,0);");
		}
		if($upefz)
		{
			PubAddFzDataUpEfz($fzclassid,$fzid,$r['classid'],$r['id'],$tbname,$r['checked'],$fzinfor['fzstb'],0);
		}
	}
	//更新父信息缓存
	if($upfc)
	{
		PubFzInfoUpFclast("pubid='".$fzpubid."'");
	}
}

//单信息加入子信息
function PubAddOneInfoToFzData($fzclassid,$fzid,$fzbcid,$fzcid,$tbname,$classid,$id,$newstime,$checked,$ecms=1,$upfc=1,$upefz=1){
	global $empire,$dbtbpre,$class_r;
	$id=(int)$id;
	if(!$id)
	{
		return '';
	}
	$fzclassid=(int)$fzclassid;
	$fzid=(int)$fzid;
	$fzbcid=(int)$fzbcid;
	$fzcid=(int)$fzcid;
	$classid=(int)$classid;
	$newstime=(int)$newstime;
	$checked=(int)$checked;
	$fzpubid=ReturnInfoPubid($fzclassid,$fzid);
	$fzinfor=$empire->fetch1("select id,classid,mid,fzstb,cid from {$dbtbpre}enewsfz_info where pubid='$fzpubid'".do_dblimit_one());
	if(!$fzinfor['id']||!$fzinfor['fzstb'])
	{
		return '';
	}
	if(!$newstime)
	{
		$r=$empire->fetch1("select classid,checked,newstime from {$dbtbpre}ecms_".$tbname."_index where id='$id'");
		$classid=$r['classid'];
		$newstime=$r['newstime'];
		$checked=$r['checked'];
	}
	$fzdatatb=$checked?$dbtbpre.'enewsfz_data_'.$fzinfor['fzstb']:$dbtbpre.'enewsfz_data_check';
	$mid=(int)$class_r[$classid]['modid'];
	$tid=(int)$class_r[$classid]['tid'];
	if($ecms==1)//修改
	{
		$zinfor=$empire->fetch1("select id,classid,mid,bcid,cid from ".$fzdatatb." where bpubid='".$fzpubid."' and id='".$id."' and tid='".$tid."'".do_dblimit_one());
	}
	else
	{
		$zinfor['id']=0;
	}
	if($zinfor['id'])
	{
		if($zinfor['cid']!=$fzcid||$zinfor['bcid']!=$fzbcid||$zinfor['mid']!=$mid||$zinfor['classid']!=$classid)
		{
			$empire->query("update ".$fzdatatb." set bcid='$fzbcid',cid='$fzcid',mid='$mid',classid='$classid' where bpubid='".$fzpubid."' and id='".$id."' and tid='".$tid."'".do_dblimit_upone());
		}
	}
	else
	{
		$empire->query("insert into ".$fzdatatb."(bpubid,id,classid,mid,bcid,cid,newstime,tid,isgood,firsttitle) values('".$fzpubid."','".$id."','".$classid."','".$mid."','".$fzbcid."','".$fzcid."','".$newstime."','".$tid."',0,0);");
	}
	if($upefz)
	{
		PubAddFzDataUpEfz($fzclassid,$fzid,$classid,$id,$tbname,$checked,$fzinfor['fzstb'],0);
	}
	//更新父信息缓存
	if($upfc)
	{
		PubFzInfoUpFclast("pubid='".$fzpubid."'");
	}
	return $fzinfor['fzstb'];
}

//加入子信息更新efz状态
function PubAddFzDataUpEfz($fzclassid,$fzid,$classid,$id,$tbname,$checked,$fzstb,$ecms=0){
	global $empire,$dbtbpre,$class_r;
	$fzclassid=(int)$fzclassid;
	$fzid=(int)$fzid;
	$classid=(int)$classid;
	$id=(int)$id;
	$checked=(int)$checked;
	$fzstb=(int)$fzstb;
	if(!$fzstb)
	{
		return '';
	}
	//主表
	$finfotb=ReturnInfoMainTbname($tbname,$checked);
	$infor=$empire->fetch1("select stb from ".$finfotb." where id='".$id."'".do_dblimit_one());
	if(!$infor['stb'])
	{
		return '';
	}
	//副表
	$infotbr=ReturnInfoTbname($tbname,$checked,$infor['stb']);
	$up_r=$empire->fetch1("select efzstb from ".$infotbr['datatbname']." where id='".$id."'".do_dblimit_one());
	if(strstr($up_r['efzstb'],','.$fzstb.','))
	{
		return '';
	}
	if($up_r['efzstb'])
	{
		$newefzstb=$up_r['efzstb'].$fzstb.',';
	}
	else
	{
		$newefzstb=','.$fzstb.',';
	}
	$empire->query("update ".$infotbr['datatbname']." set efzstb='$newefzstb' where id='".$id."'".do_dblimit_upone());
}

//增加信息时加入父信息
function PubAEInfoToFzData($tbname,$classid,$id,$newstime,$checked,$efz,$efzid,$delefzid,$ecms=0,$doupefz=0){
	global $empire,$dbtbpre,$class_r,$public_r;
	if(!$public_r['openfz'])
	{
		return '';
	}
	if(!$tbname||!$id)
	{
		return '';
	}
	$efzid=eCheckEmptyArray($efzid);
	$delefzid=eCheckEmptyArray($delefzid);
	$count=count($efzid);
	if($ecms==0)//增加
	{
		if(!$count)
		{
			return '';
		}
	}
	else
	{
		if(!$count)
		{
			return '';
			if(!$efz)
			{
				return '';
			}
			$oefzr=explode('|',$efz);
			if(!$oefzr[1])
			{
				return '';
			}
		}
	}
	//删除
	$delcount=count($delefzid);
	$delpids='';
	$deldh='';
	if($delcount)
	{
		for($di=0;$di<$delcount;$di++)
		{
			if(!$delefzid[$di])
			{
				continue;
			}
			$defzr=explode(',',$delefzid[$di]);
			$dfzclassid=(int)$defzr[0];
			$dfzid=(int)$defzr[1];
			if(!$dfzid)
			{
				continue;
			}
			$delpids.=$deldh.$dfzclassid.'.'.$dfzid;
			$deldh=',';
		}
		if($delpids)
		{
			$delpids=','.$delpids.',';
		}
	}
	//修改
	$newefzstb='';
	$dh='';
	for($i=0;$i<$count;$i++)
	{
		if(!$efzid[$i])
		{
			continue;
		}
		$efzr=explode(',',$efzid[$i]);
		$fzclassid=(int)$efzr[0];
		$fzid=(int)$efzr[1];
		$fzbcid=(int)$efzr[2];
		$fzcid=(int)$efzr[3];
		$fztbname=$class_r[$fzclassid]['tbname'];
		$fztid=$class_r[$fzclassid]['tid'];
		if(!$fztbname)
		{
			continue;
		}
		//删除
		if(strstr($delpids,','.$fzclassid.'.'.$fzid.','))
		{
			continue;
		}
		$efzpid=$fztid.'.'.$fzid;
		$efzstb=PubAddOneInfoToFzData($fzclassid,$fzid,$fzbcid,$fzcid,$tbname,$classid,$id,$newstime,$checked,$ecms,1,0);
		if($efzstb&&!strstr(','.$newefzstb.',',','.$efzstb.','))
		{
			$newefzstb.=$dh.$efzstb;
			$dh=',';
		}
	}
	if($newefzstb)
	{
		$newefzstb=','.$newefzstb.',';
	}
	if($doupefz)
	{
		$finfotb=ReturnInfoMainTbname($tbname,$checked);
		$infor=$empire->fetch1("select stb from ".$finfotb." where id='".$id."'".do_dblimit_one());
		$infotbr=ReturnInfoTbname($tbname,$checked,$infor['stb']);
		$empire->query("update ".$infotbr['datatbname']." set efzstb='$newefzstb' where id='".$id."'".do_dblimit_upone());
	}
	if(!$newefzstb)
	{
		$newefzstb='null';
	}
	return $newefzstb;
}

//未审核表子信息转换
function MoveCheckInfoForFzData($tbname,$classid,$id,$checked,$stb,$efz,$efzstb){
	global $empire,$dbtbpre,$class_r,$public_r;
	if(!$public_r['openfz'])
	{
		return '';
	}
	$classid=(int)$classid;
	$id=(int)$id;
	$checked=(int)$checked;
	$stb=(int)$stb;
	if(!$efzstb)
	{
		//副表
		$infotbr=ReturnInfoTbname($tbname,$checked,$stb);
		$up_r=$empire->fetch1("select efzstb from ".$infotbr['datatbname']." where id='".$id."'".do_dblimit_one());
		$efzstb=$up_r['efzstb'];
	}
	if(!$efzstb||$efzstb==','||$efzstb=',,')
	{
		return '';
	}
	$mid=(int)$class_r[$classid]['modid'];
	$tid=(int)$class_r[$classid]['tid'];
	if(empty($checked))//原待审核
	{
		$datasql=$empire->query("select * from ".$dbtbpre."enewsfz_data_check where id='".$id."' and tid='".$tid."'");
		while($dr=$empire->fetch($datasql))
		{
			$infor=$empire->fetch1("select fzstb from {$dbtbpre}enewsfz_info where pubid='".$dr['bpubid']."'".do_dblimit_one());
			if(!$infor['fzstb'])
			{
				continue;
			}
			$empire->query("insert into ".$dbtbpre."enewsfz_data_".$infor['fzstb']."(bpubid,id,classid,mid,bcid,cid,newstime,tid,isgood,firsttitle) values('".$dr['bpubid']."','".$dr['id']."','".$classid."','".$mid."','".$dr['bcid']."','".$dr['cid']."','".$dr['newstime']."','".$dr['tid']."','".$dr['isgood']."','".$dr['firsttitle']."');");
		}
		//删除原表
		$empire->query("delete from ".$dbtbpre."enewsfz_data_check where id='".$id."' and tid='".$tid."'");
	}
	else
	{
		$tbr=explode(',',$efzstb);
		$tbcount=count($tbr)-1;
		for($i=1;$i<$tbcount;$i++)
		{
			$tbr[$i]=(int)$tbr[$i];
			if(!$tbr[$i])
			{
				continue;
			}
			$datasql=$empire->query("select * from ".$dbtbpre."enewsfz_data_".$tbr[$i]." where id='".$id."' and tid='".$tid."'");
			while($dr=$empire->fetch($datasql))
			{
				$empire->query("insert into ".$dbtbpre."enewsfz_data_check(bpubid,id,classid,mid,bcid,cid,newstime,tid,isgood,firsttitle) values('".$dr['bpubid']."','".$dr['id']."','".$classid."','".$mid."','".$dr['bcid']."','".$dr['cid']."','".$dr['newstime']."','".$dr['tid']."','".$dr['isgood']."','".$dr['firsttitle']."');");
			}
			//删除原表
			$empire->query("delete from ".$dbtbpre."enewsfz_data_".$tbr[$i]." where id='".$id."' and tid='".$tid."'");
		}
	}
}

//删除信息时移除子信息
function DelInfoForFzData($tbname,$classid,$id,$checked,$stb,$efz,$efzstb){
	global $empire,$dbtbpre,$class_r,$public_r;
	if(!$public_r['openfz'])
	{
		return '';
	}
	$classid=(int)$classid;
	$id=(int)$id;
	$checked=(int)$checked;
	$stb=(int)$stb;
	//父信息
	if($efz)
	{
		PubDelFzInfo($classid,$id,0,0,0);
	}
	if(!$efzstb)
	{
		//副表
		if($checked==2)
		{
			$infotbr['datatbname']=$dbtbpre.'ecms_'.$tbname.'_doc_data';
		}
		else
		{
			$infotbr=ReturnInfoTbname($tbname,$checked,$stb);
		}
		$up_r=$empire->fetch1("select efzstb from ".$infotbr['datatbname']." where id='".$id."'".do_dblimit_one());
		$efzstb=$up_r['efzstb'];
	}
	if(!$efzstb||$efzstb==','||$efzstb=',,')
	{
		return '';
	}
	$mid=(int)$class_r[$classid]['modid'];
	$tid=(int)$class_r[$classid]['tid'];
	//删除原表
	$empire->query("delete from ".$dbtbpre."enewsfz_data_check where id='".$id."' and tid='".$tid."'");
	$tbr=explode(',',$efzstb);
	$tbcount=count($tbr)-1;
	for($i=1;$i<$tbcount;$i++)
	{
		$tbr[$i]=(int)$tbr[$i];
		if(!$tbr[$i])
		{
			continue;
		}
		//删除原表
		$empire->query("delete from ".$dbtbpre."enewsfz_data_".$tbr[$i]." where id='".$id."' and tid='".$tid."'");
	}
}

//转移栏目更新父子信息
function PubFzinfoUpChangeMore($classid,$ids,$to_classid,$efz){
	global $empire,$dbtbpre,$class_r,$public_r;
	if(!$public_r['openfz'])
	{
		return '';
	}
	if(!$classid||!$ids||!$to_classid)
	{
		return '';
	}
	$to_mid=(int)$class_r[$to_classid]['modid'];
	$tid=(int)$class_r[$classid]['tid'];
	$upstr="classid='".$to_classid."',mid='".$to_mid."'";
	$where="tid='".$tid."' and id in (".$ids.")";
	//父信息
	if($efz)
	{
		$empire->query("update {$dbtbpre}enewsfz_info set ".$upstr." where ".$where);
	}
	//子信息
	PubFzDataUpChangeAll($upstr,$where);
}

//转移栏目更新父子信息(全部)
function PubFzinfoUpChangeAll($classid,$to_classid){
	global $empire,$dbtbpre,$class_r,$public_r;
	if(!$public_r['openfz'])
	{
		return '';
	}
	if(!$classid||!$to_classid)
	{
		return '';
	}
	$to_mid=(int)$class_r[$to_classid]['modid'];
	$tid=(int)$class_r[$classid]['tid'];
	$upstr="classid='".$to_classid."',mid='".$to_mid."'";
	$where="classid='".$classid."'";
	//父信息
	$empire->query("update {$dbtbpre}enewsfz_info set ".$upstr." where ".$where);
	//子信息
	PubFzDataUpChangeAll($upstr,$where);
}

//更新所有表子信息
function PubFzDataUpChangeAll($upstr,$where){
	global $empire,$dbtbpre,$class_r;
	if(!$upstr||!$where)
	{
		return '';
	}
	$fzsetr=$empire->fetch1("select fzdatatbs from {$dbtbpre}enewsfz_set".do_dblimit_one());
	$tbr=explode(',',$fzsetr['fzdatatbs']);
	$tbcount=count($tbr)-1;
	for($ti=1;$ti<$tbcount;$ti++)
	{
		$tbr[$ti]=(int)$tbr[$ti];
		if(!$tbr[$ti])
		{
			continue;
		}
		$empire->query("update ".$dbtbpre."enewsfz_data_".$tbr[$ti]." set ".$upstr." where ".$where);
	}
	$empire->query("update ".$dbtbpre."enewsfz_data_check set ".$upstr." where ".$where);
}

//子信息自动更正
function PubAutoTrueFzData($tbname,$tid,$id,$classid,$mid,$newclassid,$newmid){
	global $empire,$dbtbpre;
	$upstr='';
	$dh='';
	if($newclassid)
	{
		if($classid!=$newclassid)
		{
			$upstr.="classid='".$newclassid."'";
			$dh=',';
		}
	}
	if($newmid)
	{
		if($mid!=$newmid)
		{
			$upstr.=$dh."mid='".$newmid."'";
		}
	}
	if(!$upstr)
	{
		return '';
	}
	$where="id='$id' and tid='$tid'";
	$empire->query("update ".$tbname." set ".$upstr."  where ".$where);
}

//父信息自动更正
function PubAutoTrueFzInfo($pubid,$classid,$mid,$newclassid,$newmid){
	global $empire,$dbtbpre;
	$upstr='';
	$dh='';
	if($newclassid)
	{
		if($classid!=$newclassid)
		{
			$upstr.="classid='".$newclassid."'";
			$dh=',';
		}
	}
	if($newmid)
	{
		if($mid!=$newmid)
		{
			$upstr.=$dh."mid='".$newmid."'";
		}
	}
	if(!$upstr)
	{
		return '';
	}
	$empire->query("update {$dbtbpre}enewsfz_info set ".$upstr."  where pubid='".$pubid."'");
}


//单父信息整理
function OneClearFzInfo($fr){
	global $empire,$dbtbpre,$class_r,$etable_t,$public_r;
	$upfc=0;
	if(!$fr['pubid']||!$fr['fzstb'])
	{
		return $upfc;
	}
	//父信息
	$tbname=$etable_t[$fr['tid']]['tbname'];
	if(!$tbname)
	{
		PubDelFzInfo($fr['classid'],$fr['id'],$fr['pubid'],1,0);
	}
	$index_infor=$empire->fetch1("select id,classid,checked from {$dbtbpre}ecms_".$tbname."_index where id='".$fr['id']."'");
	$newclassid=$index_infor['classid'];
	if(!$index_infor['id'])
	{
		$indexdoc_infor=$empire->fetch1("select id,classid,checked from {$dbtbpre}ecms_".$tbname."_doc_index where id='".$fr['id']."'");
		if(!$indexdoc_infor['id'])
		{
			PubDelFzInfo($indexdoc_infor['classid'],$fr['id'],$fr['pubid'],0,0);
		}
		$newclassid=$indexdoc_infor['classid'];
	}
	$haveupfc=0;
	$newmid=(int)$class_r[$newclassid]['modid'];
	if($fr['classid']!=$newclassid||$fr['mid']!=$newmid)
	{
		$empire->query("update {$dbtbpre}enewsfz_info set classid='".$newclassid."',mid='".$newmid."',fclast='".time()."' where pubid='".$fr['pubid']."'");
		$haveupfc=1;
	}
	//子信息
	$sql=$empire->query("select * from {$dbtbpre}enewsfz_data_".$fr['fzstb']." where bpubid='".$fr['pubid']."'");
	while($r=$empire->fetch($sql))
	{
		$ztbname=$etable_t[$r['tid']]['tbname'];
		if(!$ztbname)
		{
			$empire->query("delete from ".$dbtbpre."enewsfz_data_".$fr['fzstb']." where bpubid='".$r['bpubid']."' and id='".$r['id']."' and tid='".$r['tid']."'".do_dblimit_delone());
			$upfc=1;
		}
		$zindex_infor=$empire->fetch1("select id,classid,checked from {$dbtbpre}ecms_".$ztbname."_index where id='".$r['id']."'");
		$znewclassid=$zindex_infor['classid'];
		$znewchecked=$zindex_infor['checked'];
		if(!$zindex_infor['id'])
		{
			$zindexdoc_infor=$empire->fetch1("select id,classid,checked from {$dbtbpre}ecms_".$ztbname."_doc_index where id='".$r['id']."'");
			if(!$zindexdoc_infor['id'])
			{
				$empire->query("delete from ".$dbtbpre."enewsfz_data_".$fr['fzstb']." where bpubid='".$r['bpubid']."' and id='".$r['id']."' and tid='".$r['tid']."'".do_dblimit_delone());
				$upfc=1;
			}
			$znewclassid=$zindexdoc_infor['classid'];
			$znewchecked=$zindexdoc_infor['checked'];
		}
		//未审核互转
		$znewmid=(int)$class_r[$znewclassid]['modid'];
		if(!$znewchecked)
		{
			$empire->query("insert into ".$dbtbpre."enewsfz_data_check(bpubid,id,classid,mid,bcid,cid,newstime,tid,isgood,firsttitle) values('".$r['bpubid']."','".$r['id']."','".$znewclassid."','".$znewmid."','".$r['bcid']."','".$r['cid']."','".$r['newstime']."','".$r['tid']."','".$r['isgood']."','".$r['firsttitle']."');");
			$empire->query("delete from ".$dbtbpre."enewsfz_data_".$fr['fzstb']." where bpubid='".$r['bpubid']."' and id='".$r['id']."' and tid='".$r['tid']."'".do_dblimit_delone());
			$upfc=1;
		}
		else
		{
			if($r['classid']!=$znewclassid||$r['mid']!=$znewmid)
			{
				$empire->query("update {$dbtbpre}enewsfz_data_".$fr['fzstb']." set classid='".$znewclassid."',mid='".$znewmid."' where bpubid='".$r['bpubid']."' and id='".$r['id']."' and tid='".$r['tid']."'".do_dblimit_upone());
				$upfc=1;
			}
		}
	}
	//子信息(未审核)
	$sql=$empire->query("select * from {$dbtbpre}enewsfz_data_check where bpubid='".$fr['pubid']."'");
	while($r=$empire->fetch($sql))
	{
		$ztbname=$etable_t[$r['tid']]['tbname'];
		if(!$ztbname)
		{
			$empire->query("delete from ".$dbtbpre."enewsfz_data_check where bpubid='".$r['bpubid']."' and id='".$r['id']."' and tid='".$r['tid']."'".do_dblimit_delone());
			$upfc=1;
		}
		$zindex_infor=$empire->fetch1("select id,classid,checked from {$dbtbpre}ecms_".$ztbname."_index where id='".$r['id']."'");
		$znewclassid=$zindex_infor['classid'];
		$znewchecked=$zindex_infor['checked'];
		if(!$zindex_infor['id'])
		{
			$zindexdoc_infor=$empire->fetch1("select id,classid,checked from {$dbtbpre}ecms_".$ztbname."_doc_index where id='".$r['id']."'");
			if(!$zindexdoc_infor['id'])
			{
				$empire->query("delete from ".$dbtbpre."enewsfz_data_check where bpubid='".$r['bpubid']."' and id='".$r['id']."' and tid='".$r['tid']."'".do_dblimit_delone());
				$upfc=1;
			}
			$znewclassid=$zindexdoc_infor['classid'];
			$znewchecked=$zindexdoc_infor['checked'];
		}
		//未审核互转
		$znewmid=(int)$class_r[$znewclassid]['modid'];
		if(!$znewchecked)
		{
			$empire->query("insert into ".$dbtbpre."enewsfz_data_".$fr['fzstb']."(bpubid,id,classid,mid,bcid,cid,newstime,tid,isgood,firsttitle) values('".$r['bpubid']."','".$r['id']."','".$znewclassid."','".$znewmid."','".$r['bcid']."','".$r['cid']."','".$r['newstime']."','".$r['tid']."','".$r['isgood']."','".$r['firsttitle']."');");
			$empire->query("delete from ".$dbtbpre."enewsfz_data_check where bpubid='".$r['bpubid']."' and id='".$r['id']."' and tid='".$r['tid']."'".do_dblimit_delone());
			$upfc=1;
		}
		else
		{
			if($r['classid']!=$znewclassid||$r['mid']!=$znewmid)
			{
				$empire->query("update {$dbtbpre}enewsfz_data_check set classid='".$znewclassid."',mid='".$znewmid."' where bpubid='".$r['bpubid']."' and id='".$r['id']."' and tid='".$r['tid']."'".do_dblimit_upone());
				$upfc=1;
			}
		}
	}
	if($haveupfc)
	{
		$upfc=0;
	}
	return $upfc;
}

//清理多余父子信息
function ClearHaveMoreFzinfo(){
	global $empire,$dbtbpre,$class_r,$etable_t,$ecms_config;
	if($ecms_config['db']['dbver']<'4.1')
	{
		return '';
	}
	//子信息(待审核)
	$empire->query("delete from ".$dbtbpre."enewsfz_data_check where bpubid not in (select pubid from ".$dbtbpre."enewsfz_info)");
	//子信息
	$fzsetr=$empire->fetch1("select fzdatatbs from {$dbtbpre}enewsfz_set".do_dblimit_one());
	$tbr=explode(',',$fzsetr['fzdatatbs']);
	$tbcount=count($tbr)-1;
	for($ti=1;$ti<$tbcount;$ti++)
	{
		$tbr[$ti]=(int)$tbr[$ti];
		if(!$tbr[$ti])
		{
			continue;
		}
		$empire->query("delete from ".$dbtbpre."enewsfz_data_".$tbr[$ti]." where bpubid not in (select pubid from ".$dbtbpre."enewsfz_info)");
	}
	//分类
	$empire->query("delete from ".$dbtbpre."enewsfz_class where pubid not in (select pubid from ".$dbtbpre."enewsfz_info)");
}

?>