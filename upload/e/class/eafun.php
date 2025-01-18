<?php

//验证是否开启附加页面
function eapage_IsOpenEapage(){
	global $public_r;
	if(empty($public_r['eaopenpage']))
	{
		exit();
	}
}

//信息页地址
function eaGotoGeturlInfo($classid,$id,$isck=0){
	global $empire,$dbtbpre,$public_r,$class_r;
	$classid=(int)$classid;
	$id=(int)$id;
	if(!$classid||!$id)
	{
		return '';
	}
	if(!$class_r[$classid]['tbname']||!$class_r[$classid]['classid'])
	{
		return '';
	}
	$tbname=$class_r[$classid]['tbname'];
	$thischecked=1;
	if(!$isck)
	{
		//索引表
		$index_r=$empire->fetch1("select id,classid,checked from {$dbtbpre}ecms_".$tbname."_index where id='$id'".do_dblimit_one());
		if(!$index_r['id']||$index_r['classid']!=$classid)
		{
			return '';
		}
		$thischecked=$index_r['checked'];
	}
	else
	{
		$thischecked=$isck==1?1:0;
	}
	//返回表
	$infotb=ReturnInfoMainTbname($tbname,$thischecked);
	$r=$empire->fetch1("select isurl,titleurl,classid,id from ".$infotb." where id='$id'".do_dblimit_one());
	if(!$r['id']||$r['classid']!=$classid)
	{
		return '';
	}
	$titleurl=sys_ReturnBqTitleLink($r);
	//待审核
	if(!$thischecked)
	{
		//return '';
	}
	return $titleurl;
}

//友情链接地址
function eaGotoGeturlFlink($lid){
	global $empire,$dbtbpre,$public_r;
	$lid=(int)$lid;
	if(!$lid)
	{
		return '';
	}
	$r=$empire->fetch1("select lurl from {$dbtbpre}enewslink where lid='$lid'");
	if(empty($r['lurl']))
	{
		return '';
	}
	return $r['lurl'];
}

//自定义列表地址
function eaGotoGeturlUserlist($id){
	global $empire,$dbtbpre,$public_r;
	$id=(int)$id;
	if(!$id)
	{
		return '';
	}
	$r=$empire->fetch1("select listid,listname,filepath from {$dbtbpre}enewsuserlist where listid='$id'");
	if(empty($r['listid']))
	{
		return '';
	}
	$url=$public_r['newsurl'].str_replace("../../","",$r['filepath']);
	return $url;
}

//自定义页面地址
function eaGotoGeturlUserpage($id){
	global $empire,$dbtbpre,$public_r;
	$id=(int)$id;
	if(!$id)
	{
		return '';
	}
	$r=$empire->fetch1("select id,title,path,tempid from {$dbtbpre}enewspage where id='$id'");
	if(empty($r['id']))
	{
		return '';
	}
	$url=$public_r['newsurl'].str_replace("../../","",$r['path']);
	return $url;
}

//栏目地址
function eaGotoGeturlClass($classid){
	global $empire,$dbtbpre,$public_r,$class_r;
	$classid=(int)$classid;
	if(!$classid)
	{
		return '';
	}
	if(empty($class_r[$classid]['tbname'])||InfoIsInTable($class_r[$classid]['tbname']))
	{
		return '';
    }
	$r['classid']=$classid;
	$classurl=sys_ReturnBqClassname($r,9);
	return $classurl;
}

//专题地址
function eaGotoGeturlZt($ztid){
	global $empire,$dbtbpre,$public_r,$class_zr;
	$ztid=(int)$ztid;
	if(!$ztid)
	{
		return '';
	}
	if(empty($class_zr[$ztid]['ztid']))
	{
		return '';
    }
	$r['ztid']=$ztid;
	$url=sys_ReturnBqZtname($r);
	return $url;
}

//标题分类地址
function eaGotoGeturlInfotype($ttid){
	global $empire,$dbtbpre,$public_r,$class_tr;
	$ttid=(int)$ttid;
	if(!$ttid)
	{
		return '';
	}
	if(empty($class_tr[$ttid]['tbname']))
	{
		return '';
    }
	$url=sys_ReturnBqInfoTypeUrl($ttid);
	return $url;
}

//TAGS地址
function eaGotoGeturlTags($id,$urltype=0){
	global $empire,$dbtbpre,$public_r;
	$id=(int)$id;
	if(!$id)
	{
		return '';
	}
	$r=$empire->fetch1("select tagid,tagname from {$dbtbpre}enewstags where tagid='$id'");
	if(empty($r['tagid']))
	{
		return '';
	}
	if(!empty($public_r['rewritetags']))
	{
		$rewriter=eReturnRewriteTagsUrl($r['tagid'],$r['tagname'],1);
		$tagsurl=$rewriter['pageurl'];
		$rewriterid=eReturnRewriteTagsUrl($r['tagid'],'etagid'.$r['tagid'],1);
		$tagsidurl=$rewriterid['pageurl'];
	}
	else
	{
		$tagsurl=$public_r['newsurl'].'e/tags/?tagname='.urlencode($r['tagname']);
		$tagsidurl=$public_r['newsurl'].'e/tags/?tagid='.$r['tagid'];
	}
	$url=$urltype==1?$tagsurl:$tagsidurl;
	return $url;
}

//采集页面地址
function eaGotoGeturlCjpage($id){
	global $empire,$dbtbpre,$public_r;
	$id=(int)$id;
	if(!$id)
	{
		return '';
	}
	$r=$empire->fetch1("select classid,infourl from {$dbtbpre}enewsinfoclass where classid='$id'");
	if(empty($r['classid']))
	{
		return '';
	}
	$pager=explode("\r\n",$r['infourl']);
	$url=eDoRepPostComStr($pager[0],1);
	return $url;
}

//搜索转发地址
function eaGotoGeturlSearchUrl($id){
	global $empire,$dbtbpre,$public_r;
	$id=(int)$id;
	if(!$id)
	{
		return '';
	}
	$r=$empire->fetch1("select url from {$dbtbpre}enewssearchurl where id='$id'");
	if(empty($r['url']))
	{
		return '';
	}
	return $r['url'];
}

?>