<?php
require("db.php"); 
$tag = $db->getPar("tag");
$resV = '{"st":"-2","msg":"请求错误'.$tag.'","url":""}';
$url = "";
$msg = "";
switch($tag)
{
	case "stz_dtedit":
		$id = $db->getPar("id");
		$hytit = $db->getPar("hytit");
		$hymsg = $db->getPar("hymsg");
		$fkmsg = $db->getPar("hyfkmsg");
		$clmsg = $db->getPar("hyclmsg");
		$hyaddress = $db->getPar("hyaddress");	
		
		$tab = "stz_dt";
		$col = "fkmsg='$fkmsg',clmsg='$clmsg',hymsg='$hymsg',hytit='$hytit',hyaddress='$hyaddress'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"stz_dtedit.php?id='.$id.'"}';		
		break;	
	case "dongtaiedit":
		$id = $db->getPar("id");
		$hyzt = $db->getPar("hyzt");
		$hymsg = $db->getPar("hymsg");
		$hyfkmsg = $db->getPar("hyfkmsg");
		$hyclmsg = $db->getPar("hyclmsg");
		$hyaddress = $db->getPar("hyaddress");	
		
		$tab = "dongtai";
		$col = "hyfkmsg='$hyfkmsg',hyclmsg='$hyclmsg',hymsg='$hymsg',hyzt='$hyzt',hyaddress='$hyaddress'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"dtedit.php?id='.$id.'"}';		
		break;	
	case "fwdongtaiedit":
		$id = $db->getPar("id");
		$hymsg = $db->getPar("hymsg");
		$fkmsg = $db->getPar("fkmsg");
		$clmsg = $db->getPar("clmsg");
		$hyaddress = $db->getPar("hyaddress");	
		
		$tab = "fwdongtai";
		$col = "hymsg='$hymsg',fkmsg='$fkmsg',clmsg='$clmsg',hyaddress='$hyaddress'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"fwdongtaiedit.php?id='.$id.'"}';		
		break;	
	case "zlmaodunedit":
		$id = $db->getPar("id");
		$mdtype = $db->getPar("mdtype");
		$mttitle = $db->getPar("mttitle");
		$mdzhen = $db->getPar("zjname");
		$mdcun = $db->getPar("cjname");		
		$mdadd = $db->getPar("mdadd");
		$zrbumen = $db->getPar("bmname");		
		$zrrwxid = $db->getPar("zrwangge");
		$zrrname = $db->getPar("zrrname");
		$zrrtel = $db->getPar("zrrtel");
		$mdstate = $db->getPar("mdstate");
		$mdinfo = $db->getPar("mdinfo");
		
		$tab = "zlmaodun";
		$col = "mdtype='$mdtype',mttitle='$mttitle',mdzhen='$mdzhen',mdcun='$mdcun',mdadd='$mdadd',zrbumen='$zrbumen',zrrname='$zrrname',zrrtel='$zrrtel',zrrwxid='$zrrwxid',mdinfo='$mdinfo',mdstate='$mdstate'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"zlmaodun.php"}';		
	break;	
	
	case "zlmaodunadd":
		$mdtype = $db->getPar("mdtype");
		$mttitle = $db->getPar("mttitle");
		$mdzhen = $db->getPar("zjname");
		$mdcun = $db->getPar("cjname");		
		$mdadd = $db->getPar("mdadd");
		$zrbumen = $db->getPar("bmname");		
		$zrrwxid = $db->getPar("zrwangge");
		$zrrname = $db->getPar("zrrname");
		$zrrtel = $db->getPar("zrrtel");
		$mdstate = $db->getPar("mdstate");
		$mdinfo = $db->getPar("mdinfo");
		
		$tab = "zlmaodun";
		$col = "mdtype,mttitle,mdzhen,mdcun,mdadd,zrbumen,zrrname,zrrtel,zrrwxid,mdinfo,mdstate,addtime";
		$val = "('$mdtype','$mttitle','$mdzhen','$mdcun','$mdadd','$zrbumen','$zrrname','$zrrtel','$zrrwxid','$mdinfo','$mdstate',NOW())";
		$res = $db->addRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"zlmaodun.php"}';		
	break;	
	
	case "gyprojectedit":
		$id = $db->getPar("id");
		$pxtype = $db->getPar("pxtype");
		$proname = $db->getPar("proname");
		$protype = $db->getPar("protype");
		$profzr = $db->getPar("profzr");
		$profzrtel = $db->getPar("profzrtel");
		$prossdw = $db->getPar("prossdw");
		$zrrbumen = $db->getPar("bmname");
		$zrrwxid = $db->getPar("zrlingdao");
		$zrrname = $db->getPar("proldname");
		$zrrtel = $db->getPar("proldtel");
		$proinfo = $db->getPar("proinfo");
		$projindu = $db->getPar("projindu");
		$tab = "gyproject";
		$col = "pxtype='$pxtype',protype='$protype',projindu='$projindu',prossdw='$prossdw',zrrbumen='$zrrbumen',zrrwxid='$zrrwxid',proinfo='$proinfo',proname='$proname',profzr='$profzr',profzrtel='$profzrtel',zrrname='$zrrname',zrrtel='$zrrtel'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"gyproject.php"}';		
	break;
	case "wuyiproadd":
		$pxtype = $db->getPar("pxtype");
		$protype = $db->getPar("protype");
		$proname = $db->getPar("proname");
		$profzr = $db->getPar("profzr");
		$profzrtel = $db->getPar("profzrtel");		
		$zrrbumen = $db->getPar("bmname");
		$zrrwxid = $db->getPar("zrlingdao");
		$zrrname = $db->getPar("proldname");
		$zrrtel = $db->getPar("proldtel");		
		$prozhen = $db->getPar("zjname");
		$procun = $db->getPar("cjname");
		$prozu ="";
		$proinfo = $db->getPar("proinfo");
		$projindu = $db->getPar("projindu");
		$prowenti = $db->getPar("prowenti");
		
		
		$prozijin = $db->getPar("prozijin");
		$proqkzj = $db->getPar("proqkzj");
		$probzzj = $db->getPar("probzzj");
		$proneirong = $db->getPar("proneirong");
		$projihua = $db->getPar("projihua");
		$prowctime = $db->getPar("prowctime");
		$iswangong = $db->getPar("iswangong");
		$isliyi = $db->getPar("isliyi");
		$proljms = $db->getPar("proljms");
		$profhms = $db->getPar("profhms");
		$profhtime = $db->getPar("profhtime");
		
		
		
		$tab = "wuyipro";
		$col = "profhtime,profhms,proljms,isliyi,iswangong,prowctime,projihua,proneirong,probzzj,proqkzj,prozijin,prowenti,projindu,protype,pxtype,proname,profzr,profzrtel,zrrbumen,zrrwxid,proinfo,zrrname,zrrtel,prozhen,procun,prozu,addtime";
		$val = "('$profhtime','$profhms','$proljms','$isliyi','$iswangong','$prowctime','$projihua','$proneirong','$probzzj','$proqkzj','$prozijin','$prowenti','$projindu','$protype','$pxtype','$proname','$profzr','$profzrtel','$zrrbumen','$zrrwxid','$proinfo','$zrrname','$zrrtel','$prozhen','$procun','$prozu',NOW())";
		$res = $db->addRecode($tab,$col,$val);
		if($protype=='wuyi')
			$urls='wuyipro.php?tag=wuyi';
		else
			$urls='cyzdpro.php?tag='.$protype;
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"'.$urls.'"}';		
	break;	
	case "wuyiproedit":
		$id = $db->getPar("id");
		$protype = $db->getPar("protype");
		$pxtype = $db->getPar("pxtype");
		$proname = $db->getPar("proname");
		$profzr = $db->getPar("profzr");
		$profzrtel = $db->getPar("profzrtel");		
		$zrrbumen = $db->getPar("bmname");
		$zrrwxid = $db->getPar("zrlingdao");
		$zrrname = $db->getPar("proldname");
		$zrrtel = $db->getPar("proldtel");		
		$prozhen = $db->getPar("zjname");
		$procun = $db->getPar("cjname");
		$prozu = "";//$db->getPar("zuname");
		$proinfo = $db->getPar("proinfo");
		$projindu = $db->getPar("projindu");
		$prowenti = $db->getPar("prowenti");
		
		
		$prozijin = $db->getPar("prozijin");
		$proqkzj = $db->getPar("proqkzj");
		$probzzj = $db->getPar("probzzj");
		$proneirong = $db->getPar("proneirong");
		$projihua = $db->getPar("projihua");
		$prowctime = $db->getPar("prowctime");
		$iswangong = $db->getPar("iswangong");
		$isliyi = $db->getPar("isliyi");
		$proljms = $db->getPar("proljms");
		$profhms = $db->getPar("profhms");
		$profhtime = $db->getPar("profhtime");
		
		$tab = "wuyipro";
		$col = "prozijin='$prozijin',proqkzj='$proqkzj',probzzj='$probzzj',proneirong='$proneirong',projihua='$projihua',prowctime='$prowctime',iswangong='$iswangong',isliyi='$isliyi',proljms='$proljms',profhms='$profhms',profhtime='$profhtime',projindu='$projindu',prowenti='$prowenti',pxtype='$pxtype',proname='$proname',profzr='$profzr',profzrtel='$profzrtel',zrrbumen='$zrrbumen',zrrwxid='$zrrwxid',proinfo='$proinfo',zrrname='$zrrname',zrrtel='$zrrtel',prozhen='$prozhen',procun='$procun',prozu='$prozu'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		if($protype=='wuyi')
			$urls='wuyipro.php?tag=wuyi';
		else
			$urls='cyzdpro.php?tag='.$protype;
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"'.$urls.'"}';	
	break;	
	case "gyprojectadd":
		$pxtype = $db->getPar("pxtype");
		$proname = $db->getPar("proname");
		$protype = $db->getPar("protype");
		$profzr = $db->getPar("profzr");
		$profzrtel = $db->getPar("profzrtel");
		$prossdw = $db->getPar("prossdw");
		$zrrbumen = $db->getPar("bmname");
		$zrrwxid = $db->getPar("zrlingdao");
		$zrrname = $db->getPar("proldname");
		$zrrtel = $db->getPar("proldtel");
		$proinfo = $db->getPar("proinfo");
		$projindu = $db->getPar("projindu");
		$tab = "gyproject";
		$col = "pxtype,proname,protype,profzr,profzrtel,prossdw,zrrbumen,zrrwxid,proinfo,zrrname,zrrtel,projindu,addtime";
		$val = "('$pxtype','$proname','$protype','$profzr','$profzrtel','$prossdw','$zrrbumen','$zrrwxid','$proinfo','$zrrname','$zrrtel','$projindu',NOW())";
		$res = $db->addRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"gyproject.php"}';		
	break;	
	case "gyrenwuedit":
		$id = $db->getPar("id");
		$pxtype = $db->getPar("pxtype");
		$qyname = $db->getPar("qyname");
		$qyfzr = $db->getPar("qyfzr");
		$qyfzrtel = $db->getPar("qyfzrtel");
		$qyinfo = $db->getPar("qyinfo");
		
		
		$zrbumen = $db->getPar("bmname");
		$zrldwxid = $db->getPar("zrlingdao");
		$zrldname = $db->getPar("zrldname");
		$zrldtel = $db->getPar("zrldtel");
		
		$zrzzwxid = $db->getPar("zrzuzhang");
		$zrzzname = $db->getPar("zrzzname");
		$zrzztel = $db->getPar("zrzztel");
		
		$zrrwxid = $db->getPar("zrwangge");
		$zrrname = $db->getPar("zrrname");
		$zrrtel = $db->getPar("zrrtel");
		
		$tab = "gyrenwu";
		$col = "pxtype='$pxtype',zrbumen='$zrbumen',zrldwxid='$zrldwxid',zrldname='$zrldname',zrldtel='$zrldtel',zrzzwxid='$zrzzwxid',zrzzname='$zrzzname',zrzztel='$zrzztel',zrrwxid='$zrrwxid',zrrname='$zrrname',zrrtel='$zrrtel',qyinfo='$qyinfo',qyname='$qyname',qyfzr='$qyfzr',qyfzrtel='$qyfzrtel'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"gyrenwu.php"}';		
	break;
	case "gyrenwuadd":
		$pxtype = $db->getPar("pxtype");
		$qyname = $db->getPar("qyname");
		$qyfzr = $db->getPar("qyfzr");
		$qyfzrtel = $db->getPar("qyfzrtel");		
		$qyinfo = $db->getPar("qyinfo");
		
		$zrbumen = $db->getPar("bmname");
		$zrldwxid = $db->getPar("zrlingdao");
		$zrldname = $db->getPar("zrldname");
		$zrldtel = $db->getPar("zrldtel");
		
		$zrzzwxid = $db->getPar("zrzuzhang");
		$zrzzname = $db->getPar("zrzzname");
		$zrzztel = $db->getPar("zrzztel");
		
		$zrrwxid = $db->getPar("zrwangge");
		$zrrname = $db->getPar("zrrname");
		$zrrtel = $db->getPar("zrrtel");
		
		$tab = "gyrenwu";
		$col = "pxtype,zrbumen,zrldwxid,zrldname,zrldtel,zrzzwxid,zrzzname,zrzztel,zrrwxid,zrrname,zrrtel,qyname,qyfzr,qyfzrtel,qyinfo,addtime";
		$val = "('$pxtype','$zrbumen','$zrldwxid','$zrldname','$zrldtel','$zrzzwxid','$zrzzname','$zrzztel','$zrrwxid','$zrrname','$zrrtel','$qyname','$qyfzr','$qyfzrtel','$qyinfo',NOW())";
		$res = $db->addRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"gyrenwu.php"}';		
	break;	
	case "stz_ztadd":
		$zttype = $db->getPar("zttype");
		$ztname = $db->getPar("ztname");
		$ztfzr = $db->getPar("ztfzr");
		$zttel = $db->getPar("zttel");	
		$ztadd = $db->getPar("ztadd");	
		$zzmianji = $db->getPar("zzmianji");	
		$zzcount = $db->getPar("zzcount");		
		$zzinfo = $db->getPar("zzinfo");
		
		$zrbumen = $db->getPar("bmname");
		$zrldwxid = $db->getPar("zrlingdao");
		$zrldname = $db->getPar("zrldname");
		$zrldtel = $db->getPar("zrldtel");
		
		$zrzzwxid = $db->getPar("zrzuzhang");
		$zrzzname = $db->getPar("zrzzname");
		$zrzztel = $db->getPar("zrzztel");
		
		$zrrwxid = $db->getPar("zrwangge");
		$zrrname = $db->getPar("zrrname");
		$zrrtel = $db->getPar("zrrtel");
		
		$tab = "stz_zt";
		$col = "zttype,zrbumen,zrldwxid,zrldname,zrldtel,zrzzwxid,zrzzname,zrzztel,zrrwxid,zrrname,zrrtel,ztname,ztfzr,zttel,zzmianji,zzcount,ztadd,zzinfo,addtime";
		$val = "('$zttype','$zrbumen','$zrldwxid','$zrldname','$zrldtel','$zrzzwxid','$zrzzname','$zrzztel','$zrrwxid','$zrrname','$zrrtel','$ztname','$ztfzr','$zttel','$zzmianji','$zzcount','$ztadd','$zzinfo',NOW())";
		$res = $db->addRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"stz_zt.php"}';		
	break;	
	case "stz_ztedit":
		$id = $db->getPar("id");
		$zttype = $db->getPar("zttype");
		$ztname = $db->getPar("ztname");
		$ztfzr = $db->getPar("ztfzr");
		$zttel = $db->getPar("zttel");	
		$ztadd = $db->getPar("ztadd");	
		$zzmianji = $db->getPar("zzmianji");	
		$zzcount = $db->getPar("zzcount");		
		$zzinfo = $db->getPar("zzinfo");
		
		
		$zrbumen = $db->getPar("bmname");
		$zrldwxid = $db->getPar("zrlingdao");
		$zrldname = $db->getPar("zrldname");
		$zrldtel = $db->getPar("zrldtel");
		
		$zrzzwxid = $db->getPar("zrzuzhang");
		$zrzzname = $db->getPar("zrzzname");
		$zrzztel = $db->getPar("zrzztel");
		
		$zrrwxid = $db->getPar("zrwangge");
		$zrrname = $db->getPar("zrrname");
		$zrrtel = $db->getPar("zrrtel");
		
		$tab = "stz_zt";
		$col = "zttype='$zttype',zrbumen='$zrbumen',zrldwxid='$zrldwxid',zrldname='$zrldname',zrldtel='$zrldtel',zrzzwxid='$zrzzwxid',zrzzname='$zrzzname',zrzztel='$zrzztel',zrrwxid='$zrrwxid',zrrname='$zrrname',zrrtel='$zrrtel',zzinfo='$zzinfo',ztname='$ztname',ztfzr='$ztfzr',zttel='$zttel',ztadd='$ztadd',zzmianji='$zzmianji',zzcount='$zzcount'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"stz_ztedit.php?id='.$id.'"}';		
	break;
	case "stz_fbadd":
		$ztid = $db->getPar("ztid");
		$ztname = $db->getPar("ztname");
		$fbname = $db->getPar("fbname");
		$zttel = $db->getPar("zttel");	
		$zzmianji = $db->getPar("zzmianji");	
		$zzcount = $db->getPar("zzcount");		
		$zzinfo = $db->getPar("zzinfo");
		
		$zjname = $db->getPar("zjname");
		$cjname = $db->getPar("cjname");
		$zuname = $db->getPar("zuname");
		$zhainame = $db->getPar("zhainame");
		
		$tab = "stz_fb";
		$col = "fbname,ztid,ztname,zttel,zzmianji,zzcount,zjname,cjname,zuname,zhainame,zzinfo,addtime";
		$val = "('$fbname','$ztid','$ztname','$zttel','$zzmianji','$zzcount','$zjname','$cjname','$zuname','$zhainame','$zzinfo',NOW())";
		$res = $db->addRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"stz_fb.php"}';		
	break;	
	case "stz_fbedit":
		$id = $db->getPar("id");
		$ztid = $db->getPar("ztid");
		$fbname = $db->getPar("fbname");
		$ztname = $db->getPar("ztname");
		$zttel = $db->getPar("zttel");	
		$zzmianji = $db->getPar("zzmianji");	
		$zzcount = $db->getPar("zzcount");		
		$zzinfo = $db->getPar("zzinfo");
		
		$zjname = $db->getPar("zjname");
		$cjname = $db->getPar("cjname");
		$zuname = $db->getPar("zuname");
		$zhainame = $db->getPar("zhainame");
		
		$tab = "stz_fb";
		$col = "fbname='$fbname',ztid='$ztid',ztname='$ztname',zttel='$zttel',zzmianji='$zzmianji',zzcount='$zzcount',zjname='$zjname',cjname='$cjname',zuname='$zuname',zhainame='$zhainame',zzinfo='$zzinfo'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"stz_fbedit.php?id='.$id.'"}';	
	break;	
	case "stz_treeadd":
		$ztid = $db->getPar("ztid");
		$ztname = $db->getPar("ztname");
		$zttel = $db->getPar("zttel");	
		$fbid = $db->getPar("fbid");	
		$fbname = $db->getPar("fbname");
		$treeid = $db->getPar("treeid");	
		$treeage = $db->getPar("treeage");
		$treestate = $db->getPar("treestate");	
		$zzinfo = $db->getPar("zzinfo");
		
		$tab = "stz_tree";
		$col = "ztid,ztname,zttel,fbid,fbname,treeid,treeage,treestate,zzinfo,addtime";
		$val = "('$ztid','$ztname','$zttel','$fbid','$fbname','$treeid','$treeage','$treestate','$zzinfo',NOW())";
		$res = $db->addRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"stz_tree.php"}';		
	break;	
	case "stz_treeedit":
		$id = $db->getPar("id");
		$ztid = $db->getPar("ztid");
		$ztname = $db->getPar("ztname");
		$zttel = $db->getPar("zttel");	
		$fbid = $db->getPar("fbid");	
		$fbname = $db->getPar("fbname");
		$treeid = $db->getPar("treeid");	
		$treeage = $db->getPar("treeage");
		$treestate = $db->getPar("treestate");	
		$zzinfo = $db->getPar("zzinfo");
		
		$tab = "stz_tree";
		$col = "ztid='$ztid',ztname='$ztname',zttel='$zttel',fbid='$fbid',fbname='$fbname',treeid='$treeid',treeage='$treeage',treestate='$treestate',zzinfo='$zzinfo'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"stz_treeedit.php?id='.$id.'"}';		
	break;	
	case "gymubiaoadd":
		$menu = $db->getPar("menu");
		$mbtype = $db->getPar("types");
		$pxtype = $db->getPar("pxtype");
		$zrrbumen = $db->getPar("bmname");
		$zrrwxid = $db->getPar("zrlingdao");
		$zrrname = $db->getPar("proldname");
		$zrrtel = $db->getPar("proldtel");
		
		$cyzrrbumen = $db->getPar("cybmname");
		$cyzrrwxid = $db->getPar("cyzrlingdao");
		$cyzrrname = $db->getPar("cyproldname");
		$cyzrrtel = $db->getPar("cyproldtel");
		
		$mbinfo = $db->getPar("mbinfo");
		$wcinfo = $db->getPar("wcinfo");
		$tab = "gymubiao";
		$col = "cyzrrbumen,cyzrrwxid,cyzrrname,cyzrrtel,mbtype,pxtype,zrrbumen,zrrwxid,mbinfo,wcinfo,zrrname,zrrtel,addtime";
		$val = "('$cyzrrbumen','$cyzrrwxid','$cyzrrname','$cyzrrtel','$mbtype','$pxtype','$zrrbumen','$zrrwxid','$mbinfo','$wcinfo','$zrrname','$zrrtel',NOW())";
		$res = $db->addRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"gymubiao.php?menu='.$menu.'&tag='.base64_encode($mbtype).'"}';		
	break;	
	case "gymubiaoedit":
		$id = $db->getPar("id");
		$menu = $db->getPar("menu");
		$mbtype = $db->getPar("types");
		$pxtype = $db->getPar("pxtype");
		$zrrbumen = $db->getPar("bmname");
		$zrrwxid = $db->getPar("zrlingdao");
		$mbinfo = $db->getPar("mbinfo");
		$wcinfo = $db->getPar("wcinfo");
		$zrrname = $db->getPar("proldname");
		$zrrtel = $db->getPar("proldtel");		
		
		$cyzrrbumen = $db->getPar("cybmname");
		$cyzrrwxid = $db->getPar("cyzrlingdao");
		$cyzrrname = $db->getPar("cyproldname");
		$cyzrrtel = $db->getPar("cyproldtel");
		
		$tab = "gymubiao";
		$col = "cyzrrbumen='$cyzrrbumen',cyzrrwxid='$cyzrrwxid',cyzrrname='$cyzrrname',cyzrrtel='$cyzrrtel',mbtype='$mbtype',pxtype='$pxtype',zrrbumen='$zrrbumen',zrrwxid='$zrrwxid',mbinfo='$mbinfo',wcinfo='$wcinfo',zrrname='$zrrname',zrrtel='$zrrtel'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"gymubiao.php?menu='.$menu.'&tag='.base64_encode($mbtype).'"}';		
	break;
	case "rczx_fwadd":
		$pxtype = $db->getPar("pxtype");
		$hyname = $db->getPar("hyname");
		$hytel = $db->getPar("hytel");
		$hysex = $db->getPar("hysex");
		$hygzdw = $db->getPar("hygzdw");
		$hyzyzc = $db->getPar("hyzyzc");
		$hyzhen = $db->getPar("zjname");
		$hycun = $db->getPar("cjname");
		$zrrbumen = $db->getPar("bmname");
		$zrrwxid = $db->getPar("zrlingdao");
		$tab = "rczx_fw";
		$col = "pxtype,hyname,hytel,hysex,hygzdw,hyzyzc,hyzhen,hycun,zrrbumen,zrrwxid,addtime";
		$val = "('$pxtype','$hyname','$hytel','$hysex','$hygzdw','$hyzyzc','$hyzhen','$hycun','$zrrbumen','$zrrwxid',NOW())";
		$res = $db->addRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"rczx_fw.php"}';		
	break;
	case "rczx_fwedit":
		$id = $db->getPar("id");
		$pxtype = $db->getPar("pxtype");
		$hyname = $db->getPar("hyname");
		$hytel = $db->getPar("hytel");
		$hysex = $db->getPar("hysex");
		$hygzdw = $db->getPar("hygzdw");
		$hyzyzc = $db->getPar("hyzyzc");
		$hyzhen = $db->getPar("zjname");
		$hycun = $db->getPar("cjname");
		$zrrbumen = $db->getPar("bmname");
		$zrrwxid = $db->getPar("zrlingdao");
		$tab = "rczx_fw";
		$col = "pxtype='$pxtype',hyname='$hyname',hytel='$hytel',hysex='$hysex',hygzdw='$hygzdw',hyzyzc='$hyzyzc',hyzhen='$hyzhen',hycun='$hycun',zrrbumen='$zrrbumen',zrrwxid='$zrrwxid'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"rczx_fw.php"}';		
	break;
	case "rczx_pyadd":
		$pxtype = $db->getPar("pxtype");
		$pxzt = $db->getPar("pxzt");
		$pxrc = $db->getPar("pxrc");
		$pxtime = $db->getPar("pxtime");
		$pxadd = $db->getPar("pxadd");
		$zrrbumen = $db->getPar("bmname");
		$zrrwxid = $db->getPar("zrlingdao");
		$pxmsg = $db->getPar("pxmsg");
		$pxxg = $db->getPar("pxxg");
		$zrrname = $db->getPar("proldname");
		$zrrtel = $db->getPar("proldtel");
		$tab = "rczx_py";
		$col = "pxtype,pxzt,pxrc,pxtime,pxadd,zrrbumen,zrrwxid,pxmsg,pxxg,zrrname,zrrtel,addtime";
		$val = "('$pxtype','$pxzt','$pxrc','$pxtime','$pxadd','$zrrbumen','$zrrwxid','$pxmsg','$pxxg','$zrrname','$zrrtel',NOW())";
		$res = $db->addRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"rczx_py.php"}';		
	break;
	case "rczx_pyedit":
		$id = $db->getPar("id");
		$pxtype = $db->getPar("pxtype");
		$pxzt = $db->getPar("pxzt");
		$pxrc = $db->getPar("pxrc");
		$pxtime = $db->getPar("pxtime");
		$pxadd = $db->getPar("pxadd");
		$zrrbumen = $db->getPar("bmname");
		$zrrwxid = $db->getPar("zrlingdao");
		$pxmsg = $db->getPar("pxmsg");
		$pxxg = $db->getPar("pxxg");
		$zrrname = $db->getPar("proldname");
		$zrrtel = $db->getPar("proldtel");
		$tab = "rczx_py";
		$col = "pxtype='$pxtype',pxzt='$pxzt',pxrc='$pxrc',pxtime='$pxtime',pxadd='$pxadd',zrrbumen='$zrrbumen',zrrwxid='$zrrwxid',pxmsg='$pxmsg',pxxg='$pxxg',zrrname='$zrrname',zrrtel='$zrrtel'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"rczx_py.php"}';		
	break;
	case "fpadd":
		$state = $db->getPar("state");
		$bfinfo = $db->getPar("bfinfo");
		$fpname = $db->getPar("hyname");
		$fptel = $db->getPar("hytel");
		$fpzhen = $db->getPar("zjname");
		$fpcun = $db->getPar("cjname");
		$fpaddress = $db->getPar("fpaddress");
		$fplat = $db->getPar("fplat");
		$fplon = $db->getPar("fplon");
		$fpwhy = $db->getPar("fpwhy");
		$fpjbxx = $db->getPar("fpjbxx");
		$bfbumen = $db->getPar("bmname");
		$bfzzrwxid = $db->getPar("zrlingdao");
		$bfzzrname = $db->getPar("proldname");
		$bfzzrtel = $db->getPar("proldtel");
		$tab = "fangpin";
		$col = "state,bfinfo,fpname,fptel,fpzhen,fpcun,fpaddress,fplat,fplon,fpwhy,fpjbxx,bfbumen,bfzzrwxid,bfzzrname,bfzzrtel,addtime";
		$val = "('$state','$bfinfo','$fpname','$fptel','$fpzhen','$fpcun','$fpaddress','$fplat','$fplon','$fpwhy','$fpjbxx','$bfbumen','$bfzzrwxid','$bfzzrname','$bfzzrtel',NOW())";
		$res = $db->addRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"fplist.php"}';
		
	break;
	case "fpedit":
		$id = $db->getPar("id");
		$state = $db->getPar("state");
		$bfinfo = $db->getPar("bfinfo");
		$fpname = $db->getPar("hyname");
		$fptel = $db->getPar("hytel");
		$fpzhen = $db->getPar("zjname");
		$fpcun = $db->getPar("cjname");
		$fpaddress = $db->getPar("fpaddress");
		$fplat = $db->getPar("fplat");
		$fplon = $db->getPar("fplon");
		$fpwhy = $db->getPar("fpwhy");
		$fpjbxx = $db->getPar("fpjbxx");
		$bfbumen = $db->getPar("bmname");
		$bfzzrwxid = $db->getPar("zrlingdao");
		$bfzzrname = $db->getPar("proldname");
		$bfzzrtel = $db->getPar("proldtel");
		$tab = "fangpin";
		$col = "fpname='$fpname',fptel='$fptel',fpzhen='$fpzhen',fpcun='$fpcun',fpaddress='$fpaddress',fplat='$fplat',fplon='$fplon',fpwhy='$fpwhy',fpjbxx='$fpjbxx',bfbumen='$bfbumen',bfzzrwxid='$bfzzrwxid',bfzzrname='$bfzzrname',bfzzrtel='$bfzzrtel',bfinfo='$bfinfo',state='$state'";
		$val = "id='$id'";
		$res = $db->editRecode($tab,$col,$val);
		$resV = '{"st":"1","msg":"恭喜您，编辑成功！","url":"fplist.php"}';
		
	break;
	case "proadd":
		$proclass = $db->getPar("proclass");
		$proname = $db->getPar("proname");
		$protype = $db->getPar("protype");
		$prozhen = $db->getPar("zjname");
		$procun = $db->getPar("cjname");
		$proaddress = $db->getPar("proaddress");
		$projihua = $db->getPar("projihua");
		$prozijin = $db->getPar("prozijin");
		$proliyi = $db->getPar("proliyi");
		$profenhong = $db->getPar("profenhong");
		$prostarttime = $db->getPar("prostarttime");
		$proendtime = $db->getPar("proendtime");
		$projindu = $db->getPar("projindu");
		$projindunum = $db->getPar("projindunum");
		$probumen = $db->getPar("bmname");
		$zrlingdao = $db->getPar("zrlingdao");
		$zrzuzhang = $db->getPar("zrzuzhang");
		$zrwangge = $db->getPar("zrwangge");
		$isfocus = $db->getPar("isfocus");
		$proinfo = $db->getPar("proinfo");
		$menu = $db->getPar("menu");
		$proldname = $db->getPar("proldname");
		$proldtel = $db->getPar("proldtel");
		$prozzname = $db->getPar("prozzname");
		$prowgname = $db->getPar("prowgname");
		
		$qdst = $db->getOne("projects","proname='$proname'");
		if(count($qdst)>0)
			$resV = '{"st":"1","msg":"项目已经存在，请换一个名字！","url":"proadd.php?menu='.$menu.'&tag='.base64_encode($proclass).'"}';
		else
		{
			$tab = "projects";
			$col = "proldtel,prowgname,prozzname,proldname,projindunum,proclass,proname,protype,prozhen,procun,proaddress,projihua,prozijin,proliyi,profenhong,prostarttime,proendtime,projindu,probumen,prolingdao,prozuzhang,prowangge,isfocus,proinfo,addtime";
			$val = "('$proldtel','$prowgname','$prozzname','$proldname','$projindunum','$proclass','$proname','$protype','$prozhen','$procun','$proaddress','$projihua','$prozijin','$proliyi','$profenhong','$prostarttime','$proendtime','$projindu','$probumen','$zrlingdao','$zrzuzhang','$zrwangge','$isfocus','$proinfo',NOW())";
			$res = $db->addRecode($tab,$col,$val);
			$resV = '{"st":"1","msg":"恭喜您，添加成功！","url":"prolist.php?menu='.$menu.'&tag='.base64_encode($proclass).'"}';
		}
	break;
	case "proedit":
		$id = $db->getPar("id");
		$proclass = $db->getPar("proclass");
		$proname = $db->getPar("proname");
		$protype = $db->getPar("protype");
		$prozhen = $db->getPar("zjname");
		$procun = $db->getPar("cjname");
		$proaddress = $db->getPar("proaddress");
		$projihua = $db->getPar("projihua");
		$prozijin = $db->getPar("prozijin");
		$proliyi = $db->getPar("proliyi");
		$profenhong = $db->getPar("profenhong");
		$prostarttime = $db->getPar("prostarttime");
		$proendtime = $db->getPar("proendtime");
		$projindu = $db->getPar("projindu");
		$projindunum = $db->getPar("projindunum");
		$probumen = $db->getPar("bmname");
		$prolingdao = $db->getPar("zrlingdao");
		$prozuzhang = $db->getPar("zrzuzhang");
		$prowangge = $db->getPar("zrwangge");
		$isfocus = $db->getPar("isfocus");
		$proinfo = $db->getPar("proinfo");
		$menu = $db->getPar("menu");		
		$proldname = $db->getPar("proldname");
		$proldtel = $db->getPar("proldtel");
		$prozzname = $db->getPar("prozzname");
		$prowgname = $db->getPar("prowgname");
		
		$qdst = $db->getOne("projects","proname='$proname'");
		if(count($qdst)>1)
			$resV = '{"st":"1","msg":"项目已经存在，请换一个名字！","url":"proadd.php?menu='.$menu.'&tag='.$proclass.'"}';
		else
		{
			$tab = "projects";
			$col = "proldtel='$proldtel',proldname='$proldname',prozzname='$prozzname',prowgname='$prowgname',projindunum='$projindunum',proclass='$proclass',proname='$proname',protype='$protype',prozhen='$prozhen',procun='$procun',proaddress='$proaddress',projihua='$projihua',prozijin='$prozijin',proliyi='$proliyi',profenhong='$profenhong',prostarttime='$prostarttime',proendtime='$proendtime',projindu='$projindu',probumen='$probumen',prolingdao='$prolingdao',prozuzhang='$prozuzhang',prowangge='$prowangge',isfocus='$isfocus',proinfo='$proinfo'";
			$val = "id='$id'";
			$res = $db->editRecode($tab,$col,$val);
			$resV = '{"st":"1","msg":"恭喜您，编辑成功！","url":"proedit.php?id='.$id.'&menu='.$menu.'&tag='.base64_encode($proclass).'"}';
		}
	break;
	case "newsedit":
		$id = $db->getPar("id");
		$newstype = $db->getPar("newstype");
		$newstitle = $db->getPar("newstitle");
		$newsindex = $db->getPar("newsindex");
		$newsphoto = $db->getPar("newsphoto");
		$newsauthor = $db->getPar("newsauthor");
		$newsurl = $db->getPar("newsurl");
		$newsort = $db->getPar("newsort");
		$newsdetail = $db->getPar("nrxq");
		
		$tab="qdnews";
		$col = "newstype='$newstype',newstitle='$newstitle',newsindex='$newsindex',newsphoto='$newsphoto',newsauthor='$newsauthor',newsurl='$newsurl',newsort='$newsort',newsdetail='$newsdetail'";
		$val="id='$id'";
		$db->editRecode($tab,$col,$val);
			
		$resV = '{"st":"0","msg":"恭喜您，编辑成功","url":"qdnewsedit.php?id='.$id.'"}';
	break;
	case "newsadd":
		$newstype = $db->getPar("newstype");
		$newstitle = $db->getPar("newstitle");
		$newsindex = $db->getPar("newsindex");
		$newsphoto = $db->getPar("newsphoto");
		$newsauthor = $db->getPar("newsauthor");
		$newsurl = $db->getPar("newsurl");
		$newsort = $db->getPar("newsort");
		$newsdetail = $db->getPar("nrxq");
		
		$tab = "qdnews";

		$col = "newstype,newstitle,newsindex,newsphoto,newsauthor,newsurl,newsort,newsdetail,addtime";
		$val = "('$newstype','$newstitle','$newsindex','$newsphoto','$newsauthor','$newsurl','$newsort','$newsdetail',NOW())";
		$res = $db->addRecode($tab,$col,$val);
			
		$resV = '{"st":"0","msg":"恭喜您，发布成功","url":"qdnews.php"}';
	break;
	case "zlfaguiedit":
		$id = $db->getPar("id");
		$types = $db->getPar("types");
		$menu = $db->getPar("menu");
		$newstitle = $db->getPar("newstitle");
		$newsauthor = $db->getPar("newsauthor");
		$newsort = $db->getPar("newsort");
		$newsdetail = $db->getPar("nrxq");
		$tab="zlfagui";
		$col = "newstitle='$newstitle',newsauthor='$newsauthor',newsort='$newsort',newsdetail='$newsdetail'";
		$val="id='$id'";
		$db->editRecode($tab,$col,$val);
			
		$resV = '{"st":"0","msg":"恭喜您，编辑成功","url":"zlfagui.php?menu='.$menu.'&tag='.base64_encode($types).'"}';
	break;
	case "zlfaguiadd":
		$types = $db->getPar("types");
		$menu = $db->getPar("menu");
		$newstitle = $db->getPar("newstitle");
		$newsauthor = $db->getPar("newsauthor");
		$newsort = $db->getPar("newsort");
		$newsdetail = $db->getPar("nrxq");
		$tab = "zlfagui";
		$col = "types,newstitle,newsauthor,newsort,newsdetail,addtime";
		$val = "('$types','$newstitle','$newsauthor','$newsort','$newsdetail',NOW())";
		$res = $db->addRecode($tab,$col,$val);
			
		$resV = '{"st":"0","msg":"恭喜您，发布成功","url":"zlfagui.php?menu='.$menu.'&tag='.base64_encode($types).'"}';
	break;
	case "resetpwd":
		$oldpwd = $db->getPar("oldpwd");
		$repeatpwd = $db->getPar("repeatpwd");
		$newpwd = $db->getPar("newpwd");
		$id = $db->getPar("id");		
		$oldpwd = md5($oldpwd);		
		$adm = $db->getOne("qdadmin","id='$id' and admpwd='$oldpwd'");
		if(count($adm)>0)
		{
			if($repeatpwd==$newpwd)
			{
				$newpwd = md5($newpwd);
				$db->editRecode("qdadmin","admpwd='$newpwd'","id=".$id);
				$resV = '{"st":"0","msg":"修改成功！","url":"login.php"}';
			}else
				$resV = '{"st":"0","msg":"新密码两次输入不一致，请重新输入！","url":"admpwd.php"}';
		}else{
			$resV = '{"st":"0","msg":"原密码输入错误，请重新输入！","url":"admpwd.php"}';
		}
	break;
	case "cjedit":
		$id = $db->getPar("id");
		$zjname = $db->getPar("zjname");
		$cjname = $db->getPar("cjname");
		$cjsort = $db->getPar("cjsort");
		$qdst = $db->getOne("cunji","cjname='$cjname' and zjname='$zjname'");
		if(count($qdst)>1)
			$resV = '{"st":"1","msg":"村级名称已经存在！","url":"cjedit.php?id='.$id.'"}';
		else
		{
			$tab = "cunji";
			$col = "zjname='$zjname',cjname='$cjname',cjsort='$cjsort'";
			$val = "id='$id'";
			$res = $db->editRecode($tab,$col,$val);
			$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"cjedit.php?id='.$id.'"}';
		}
	break;
	case "cjadd":
		$zjname = $db->getPar("zjname");
		$cjname = $db->getPar("cjname");
		$cjsort = $db->getPar("cjsort");
		$qdst = $db->getOne("cunji","cjname='$cjname' and zjname='$zjname'");
		if(count($qdst)>0)
			$resV = '{"st":"1","msg":"村级名称已经存在！","url":"cjadd.php"}';
		else
		{
			$tab = "cunji";
			$col = "zjname,cjname,cjsort,addtime";
			$val = "('$zjname','$cjname','$cjsort',NOW())";
			$res = $db->addRecode($tab,$col,$val);
			$resV = '{"st":"1","msg":"恭喜您，添加村级成功！","url":"cjlist.php"}';
		}
	break;	
	case "bmedit":
		$id = $db->getPar("id");
		$bmclass = $db->getPar("bmclass");
		$zjname = $db->getPar("zjname");
		$cjname = $db->getPar("cjname");
		$bmname = $db->getPar("bmname");
		$bmaddress = $db->getPar("bmaddress");
		$bmfzr = $db->getPar("bmfzr");
		$bmtel = $db->getPar("bmtel");
		$bmsort = $db->getPar("bmsort");
		$qdst = $db->getOne("bumen","cjname='$cjname' and zjname='$zjname' and bmname='$bmname'");
		if(count($qdst)>1)
			$resV = '{"st":"1","msg":"部门名称已经存在！","url":"bmedit.php?id='.$id.'"}';
		else
		{
			$tab = "bumen";
			$col = "bmclass='$bmclass',zjname='$zjname',cjname='$cjname',bmname='$bmname',bmaddress='$bmaddress',bmfzr='$bmfzr',bmtel='$bmtel',bmsort='$bmsort'";
			$val = "id='$id'";
			$res = $db->editRecode($tab,$col,$val);
			
			$res = $db->editRecode("hymember","bmclass='$bmclass'","bumen='$bmname'");
			
			$resV = '{"st":"1","msg":"恭喜您，保存成功！","url":"bmedit.php?id='.$id.'"}';
		}
	break;
	case "bmadd":
		$bmclass = $db->getPar("bmclass");
		$zjname = $db->getPar("zjname");
		$cjname = $db->getPar("cjname");
		$bmname = $db->getPar("bmname");
		$bmaddress = $db->getPar("bmaddress");
		$bmfzr = $db->getPar("bmfzr");
		$bmtel = $db->getPar("bmtel");
		$bmsort = $db->getPar("bmsort");
		
		$qdst = $db->getOne("bumen","cjname='$cjname' and zjname='$zjname' and bmname='$bmname'");
		if(count($qdst)>0)
			$resV = '{"st":"1","msg":"部门名称已经存在！","url":"bmadd.php"}';
		else
		{
			$tab = "bumen";
			$col = "bmclass,zjname,cjname,bmname,bmaddress,bmfzr,bmtel,bmsort,addtime";
			$val = "('$bmclass','$zjname','$cjname','$bmname','$bmaddress','$bmfzr','$bmtel','$bmsort',NOW())";
			$res = $db->addRecode($tab,$col,$val);
			$resV = '{"st":"1","msg":"恭喜您，添加部门成功！","url":"bmlist.php"}';
		}
	break;
	case "getcunji":
		$zjname = $db->getPar("zjname");
		$qdcl = $db->getAll("cunji","zjname='$zjname'");
		foreach($qdcl as $row)
		{
			$row=$row["Cunji"];
			$select[] = array("id"=>$row["id"],"cjname"=>$row["cjname"]); 
		}
		$resV =json_encode($select); 	
	break;
	case "getzuji":
		$cjname = $db->getPar("cjname");
		$qdcl = $db->getAll("zuji","cjname='$cjname'");
		foreach($qdcl as $row)
		{
			$row=$row["Zuji"];
			$select[] = array("id"=>$row["id"],"zuname"=>$row["zuname"]); 
		}
		$resV =json_encode($select); 	
	break;
	case "blueedit":
		$id = $db->getPar("id");
		$dropNo = $db->getPar("dropNo");
		$dropName = $db->getPar("dropName");
		$dropClass = $db->getPar("yhSpeciality");
		$patrolCycle = $db->getPar("patrolCycle");
		$patrolNum = $db->getPar("patrolNum");
		$patrolDiff = $db->getPar("patrolDiff");
		$tab="blueInspect";
		$col = "dropNo='$dropNo',dropName='$dropName',dropClass='$dropClass',patrolCycle='$patrolCycle',patrolNum='$patrolNum',patrolDiff='$patrolDiff'";
		$val="id='$id'";
		$db->editRecode($tab,$col,$val);
		$resV = '{"st":"0","msg":"恭喜您，保存成功","url":"blueedit.php?id='.$id.'"}';
	break;
	case "inspectgroupedit":
		$id = $db->getPar("id");
		$tab = $db->getPar("tab");
		$gName = $db->getPar("gName");
		$gSort = $db->getPar("gSort");
		$col = "gName='$gName',gSort='$gSort'";
		$val="id='$id'";
		$db->editRecode($tab,$col,$val);
		$resV = '{"st":"0","msg":"恭喜您，保存成功","url":""}';
	break;
	case "inspectedit":
		$id = $db->getPar("id");
		$gsName = $db->getPar("gsName");
		$yhAdd = $db->getPar("yhAdd");
		$yhPosition = $db->getPar("yhPosition");
		$yhContent = $db->getPar("yhContent");
		$yhSpeciality = $db->getPar("yhSpeciality");
		$together = $db->getPar("together");
		$zgAsk = $db->getPar("zgAsk");
		$zgTime = $db->getPar("zgTime");
		$tab="inspect";
		$col = "gsName='$gsName',yhAdd='$yhAdd',yhPosition='$yhPosition',yhContent='$yhContent',yhSpeciality='$yhSpeciality',together='$together',zgAsk='$zgAsk',zgTime='$zgTime'";
		$val="id='$id'";
		$db->editRecode($tab,$col,$val);
		$resV = '{"st":"0","msg":"恭喜您，保存成功","url":"inspectedit.php?id='.$id.'"}';
	break;
	case "hyedit":
		$id = $db->getPar("id");
		$hyName = $db->getPar("hyName");
		$hyTel = $db->getPar("hyTel");
		$hyIdentity = $db->getPar("hyIdentity");
		$tab="hyUser";
		$col = "hyName='$hyName',hyTel='$hyTel',hyIdentity='$hyIdentity'";
		$val="id='$id'";
		$db->editRecode($tab,$col,$val);
		$resV = '{"st":"0","msg":"恭喜您，保存成功","url":"hyedit.php?id='.$id.'"}';
	break;		
	case "admlg":
		$hyTel = $db->getPar("hyTel");
		$hyPwd = $db->getPar("hyPwd");
		$myres = $db->getOne("hyUser","hyTel='$hyTel' and hyPwd='".md5($hyPwd)."'","logTime desc");
		if(count($myres)>0)
		{
			$adm = $myres["HyUser"];
			$_SESSION["tId"]=$adm["tId"];
			$_SESSION["hyId"]=$adm["id"];
			$_SESSION["hyIdentity"]=$adm["hyIdentity"];
			$_SESSION["hyTel"]=$adm["hyTel"];
			
			$resV = '{"st":"0","msg":"登陆成功！","url":"index.php"}';
		}else{
			$resV = '{"st":"0","msg":"帐号和密码输入错误，请重新输入！","url":"login.php"}';
		}
	break;
	case "del":
		$tab = $db->getPar("t");
		$id = $db->getPar("i");
		$url = $db->getPar("m");
		if($tab=="shenpi"||$tab=="jubao"||$tab=="baoguang")
			$db->editRecode($tab,"states=9999","id='$id'");
		else
			$db->deleteRecode("$tab","id='$id'");			
		$resV = '{"st":"0","msg":"操作完成！","url":"'.$url.'"}';
	break;
	case "clip":
		$image = $db->getPar("dat");
		$imageName = "".date("His",time())."_".rand(1111,9999).'.png';
        if (strstr($image,","))
		{
        	$image = explode(',',$image);
        	$image = $image[1];
        }	
		$path = "upimg/".date("Ymd",time());
        if(file_exists(ROOT.$path)===false)
			mkdir (ROOT.$path,0777,true);		
        $imageSrc=  $path."/". $imageName;  //图片名字
        $r = file_put_contents(ROOT .$imageSrc, base64_decode($image));//返回的是字节数
        if (!$r)
			$resV = '{"st":"0","msg":"图片生成失败","url":""}';
        else
			$resV = '{"st":"0","msg":"图片生成成功","url":"'.$imageSrc.'"}';
	break;
	case "upimg":
		$size = 1024*1024*500;
		$sizets = 500;
		if(empty($_FILES["tp1"])){
			$resV = "";
			return false;
		}
		$picname = $_FILES['tp1']['name'];
		$picsize = $_FILES['tp1']['size'];
		if ($picsize > $size) {
			echo '图片大小不能超过'.$sizets.'M';
			exit;
		}
		$type = strstr($picname, '.');
			$rand = rand(100, 999);
		$pics = date("YmdHis") . $rand . $type;
		//上传路径
		$pic_path = $db->upFile($_FILES['tp1'],"files");
		$size = round($picsize/1024,2);
		$arr = array(
			'name'=>$pics,
			'pic'=>$pic_path,
			'size'=>$size
		);
		$resV = json_encode($arr);
	break;
	default:
	break;
}
echo $resV;