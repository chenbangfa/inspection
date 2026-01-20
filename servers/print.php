<?php 
require("data/db.php"); 
$tag = $db->getPar("tag");
$resV = '{"st":"-2","msg":"请求错误'.$tag.'","url":""}';
$url = "";
$msg = "";
switch($tag)
{
	case "setprint":
		$stid = $db->getpar("stid");
		$printSn = $db->getpar("printSn");
		$printKey = $db->getpar("printKey");
		$printName = $db->getpar("printName");		
		$res=$db->addPrinter($printSn,$printKey,$printName);
		if($res=='成功')
		{
			$tab="qdstore";
			$col = "printSn='$printSn',printKey='$printKey'";
			$val="id='$stid'";
			$db->editRecode($tab,$col,$val);			
			$resV = '{"msg":"绑定成功！"}';	
		}else
			$resV = '{"msg":"'.$res.'"}';	
    	break;	
	case "loadprint":
		$stid = $db->getpar("stid");
		$hy = $db->getOne("qdstore","id='$stid'");
		$hy = $hy["Qdstore"];
		$printZt='';
		$onlineStatus='';
		$workStatus='';
		if($hy["printSn"]!="")
		{
			$res=$db->printzt($hy["printSn"]);
			$printZt=$res["workStatusDesc"];
			$onlineStatus=$res["onlineStatus"];
			$workStatus=$res["workStatus"];
		}
		$resV = '{"printZt":"'.$printZt.'","onlineStatus":"'.$onlineStatus.'","workStatus":"'.$workStatus.'","printSn":"'.$hy["printSn"].'","printKey":"'.$hy["printKey"].'","stname":"'.$hy["stname"].'"}';		
    	break;	
	case "printorder":
		$odid = $db->getpar("odid");
		$stid = $db->getpar("stid");
		$resV=$db->printorder($odid,$stid,0);			
    	break;	
	case "printtuanorder":
		$odid = $db->getpar("odid");
		$stid = $db->getpar("stid");
		$resV=$db->printtuanorder($odid,$stid,0);			
    	break;	
	case "printtest":
		$printSn = $db->getpar("printSn");
		$printKey = $db->getpar("printKey");
		$res=$db->Printtest($printSn);
		$resV = '{"code":"0","msg":"'.$res.'"}';
    	break;		
	default:
		break;
}
echo $resV;
?>