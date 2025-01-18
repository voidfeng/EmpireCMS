<?php

//变量名,变量值,工具条模式,编辑器目录,高度,宽度,全页
function ECMS_ShowEditorVar($varname,$varvalue,$toolbar='full',$basepath='',$height='300',$width='100%',$fullpage=0){
	global $ecms_tofunr;
	$toolbar=strtolower($toolbar);
	
	if(empty($basepath))
	{
		$basepath='../data/ecmseditor/infoeditor/';
	}
	if(empty($height))
	{
		$height='300';
	}
	if(empty($width))
	{
		$width='100%';
	}
	
	//是否采用文本框
	$isqedtotext=eCkQEdToText($ecms_tofunr['qedmpid'],$ecms_tofunr['qedmid'],$ecms_tofunr['qedtbname'],$varname);
	if($isqedtotext)
	{
		if(strstr($varvalue,'<')||strstr($varvalue,'>')||strstr($varvalue,'"')||strstr($varvalue,"'"))
		{
			$varvalue=ehtmlspecialchars($varvalue);
		}
		$qed_tovar_r=array();
		$qed_tovar_r['fimpid']=$ecms_tofunr['qedmpid'];
		$qed_tovar_r['fimid']=$ecms_tofunr['qedmid'];
		$qed_tovar_r['fiheight']=$height;
		$qed_tovar_r['fiwidth']=$width;
		$qed_tovar_r['fifname']=$varname;
		$qed_tovar_r['fifvalue']=$varvalue;
		$echoeditor=eQEdToTextForm($qed_tovar_r);
		return $echoeditor;
	}
	
	if($varvalue)
	{
		$varvalue=ehtmlspecialchars($varvalue);
	}
	
	$editorvars='';
	if($fullpage==1)
	{
		$editorvars.="fullPage:true, ";
	}
	if($toolbar=='basic')
	{
		$editorvars.="toolbar:'basic', ";
	}
	$editorvars.="width:'".$width."', height:'".$height."'";

	$echoeditor="<textarea cols='90' rows='10' id='".$varname."' name='".$varname."'>".$varvalue."</textarea>
<script type='text/javascript'>CKEDITOR.replace('".$varname."',
{
     ".$editorvars."
});</script>";

	return $echoeditor;
}

//返回文本框显示
function eQEdToTextForm($tovar_r){
	global $empire,$dbtbpre,$public_r;
	$pr=$empire->fetch1("select qedtemp from {$dbtbpre}enewspublicadd".do_dblimit_one());
	$qedhtml=str_replace('[!--ec.fi.height--]',$tovar_r['fiheight'],$pr['qedtemp']);
	$qedhtml=str_replace('[!--ec.fi.width--]',$tovar_r['fiwidth'],$qedhtml);
	$qedhtml=str_replace('[!--ec.fi.mid--]',$tovar_r['fimid'],$qedhtml);
	$qedhtml=str_replace('[!--ec.fi.mpid--]',$tovar_r['fimpid'],$qedhtml);
	$qedhtml=str_replace('[!--ec.fi.name--]',$tovar_r['fifname'],$qedhtml);
	$qedhtml=str_replace('[!--ec.fi.value--]',$tovar_r['fifvalue'],$qedhtml);
	return $qedhtml;
}

//返回加载JS文件
function ECMS_ShowEditorJS($basepath=''){
	if(empty($basepath))
	{
		$basepath='../data/ecmseditor/infoeditor/';
	}
	$addcs=ECMS_ReturnEditorCx();

	$loadjs='<input type=hidden id=doecmseditor_eaddcs value="'.$addcs.'"> <script type="text/javascript" src="'.$basepath.'ckeditor.js?&empirecms=1"></script>';

	return $loadjs;
}

//附加参数
function ECMS_ReturnEditorCx(){
	global $classid,$id,$filepass;
	$classid=(int)$classid;
	$filepass=(int)$filepass;
	$id=(int)$id;
	$str="&classid=$classid&filepass=$filepass&infoid=$id";
	return $str;
}

//上传提示
function ECMS_PTEditorShowError($type,$error,$showstr,$add,$ecms=0){
?>
<script type='text/javascript'>
<?php
if($error)
{
	echo'alert("'.$error.'");';
}
if($showstr&&$showstr!='####')
{
	echo"window.parent.EcmsEditorReturnDoAction".$type."('".addslashes($showstr)."');";
}
?>
</script>
<?php
}

//返回type
function ECMS_EditorReturnType($page){
	if(empty($page))
	{
		$page=$_POST['doecmspage']?$_POST['doecmspage']:$_GET['doecmspage'];
	}
	if($page=='TranFile')
	{
		$r['ftype']=0;
		$r['jsfun']='EHEcmsEditorDoTranFile';
	}
	elseif($page=='TranFlash')
	{
		$r['ftype']=2;
		$r['jsfun']='EHEcmsEditorDoTranFlash';
	}
	elseif($page=='TranMedia')
	{
		$r['ftype']=3;
		$r['jsfun']='EHEcmsEditorDoTranMedia';
	}
	elseif($page=='TranMore')
	{
		$r['ftype']='TM';
		$r['jsfun']='EHEcmsEditorDoTranMore';
	}
	elseif($page=='TranImg2')
	{
		$r['ftype']=1;
		$r['jsfun']='EHEcmsEditorDoTranImgTwo';
	}
	else	//TranImg
	{
		$r['ftype']=1;
		$r['jsfun']='EHEcmsEditorDoTranImg';
	}
	return $r;
}

//选择提示
function ECMS_EditorChFileFun($page){
	$r=ECMS_EditorReturnType($page);
	return $r['jsfun'];
}

?>