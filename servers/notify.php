<?php header('Content-Type:application/json; charset=utf-8');
require("data/db.php"); 
$postXml = $GLOBALS["HTTP_RAW_POST_DATA"]; //接收微信参数 
// 接受不到参数可以使用file_get_contents("php://input"); PHP高版本中$GLOBALS好像已经被废弃了
if (empty($postXml))
{
  return false;
}

$attr = xmlToArray($postXml);
$total_fee = $attr['total_fee']/100;
$open_id = $attr['openid'];
$out_trade_no = $attr['out_trade_no'];
$time = $attr['time_end'];

/*$db->writeMsg("open_id:".$open_id);
$db->writeMsg("total_fee:".$total_fee);
$db->writeMsg("out_trade_no:".$out_trade_no);
$db->writeMsg("time:".$time);*/
$tuanres = $db->getOne("qdtuanorder","odid='$out_trade_no' and wxid='$open_id'");
if(count($tuanres)>0)
{
	$row = $tuanres["Qdtuanorder"];
	$mdid=$row["stid"];//门店id
	
	$oid=$row["id"];
	$hynote="您有新团购订单！";
	$shopname=$row["tuanname"];
	$odtime=$row["addtime"];
	$hyinfo=$row["hyname"]."，".$row["hytel"];
	$odfee=$row["odfee"];
	$tuannum=$row["tuannum"];
	$tuanid=$row["tuanid"];
	$addname="到店使用";
	$hy=$db->getAll("qdbusiness","qdstate=2 and mdid='$mdid'");	
	foreach($hy as $res)
	{
		$bus=$res["Qdbusiness"];
		$wxid=$bus["wxsendid"];
		//$db->writeMsg($mdid."///".$wxid);	
		$db->sendorder($wxid,$hynote,"tuanorder",$shopname,$odtime,$addname,$hyinfo,"已支付".$odfee."元");
	}		
				
	$tab = "qdtuanorder";
	$col = "odstate=1,addtime=NOW()";
	$val = "odid='$out_trade_no' and wxid='$open_id'";
	$res = $db->editRecode($tab,$col,$val);
	
	//减库存
	$tab = "qdtuan";
	$col = "tuankucun=tuankucun-$tuannum,tuansale=tuansale+$tuannum";
	$val = "id='$tuanid'";
	$res = $db->editRecode($tab,$col,$val);	
	
	$db->printtuanorder($oid,$mdid,1);
}else
{
	$odres = $db->getOne("qdorder","odid='$out_trade_no' and wxid='$open_id'");
	if(count($odres)>0)
	{
		$row = $odres["Qdorder"];
		$mdid=$row["stid"];//门店id		
		$oid=$row["id"];
		
		$hynote=$row["hynote"]==""?"请您尽快接单处理哦！":$row["hynote"].",请您尽快接单处理哦！";
		$shopname=rtrim($row["spname"],"|");
		$shopname=strtr($shopname, array("|" =>'，'));
		$odtime=$row["addtime"];
		$address=$row["hyaddname"];
		$hyinfo=$row["hyname"]."，".$row["hytel"];
		$odfee=$row["odfee"];
		
		$hy=$db->getAll("qdbusiness","qdstate=2 and mdid='$mdid'");
		foreach($hy as $res)
		{
			$bus=$res["Qdbusiness"];
			$wxid=$bus["wxsendid"];
			$db->sendorder($wxid,$hynote,"storder",$shopname,$odtime,$address,$hyinfo,"已支付".$odfee."元");
		}
		
		$nowtime=date("Y-m-d G:i:s");
		$dateline=strtotime($nowtime);
		$nowtime=date('Y-m-d',$dateline);
				
		$sort=1;
		$zh = $db->getOne("qdorder","addtime like '%$nowtime%' and odstate<>0","sort desc");
		if(count($zh)>0)
		{
			$zh=$zh["Qdorder"];
			$sort=(int)$zh["sort"]+1;
		}
					
		$spid = explode('|',$row["spid"]); 
		$spnum = explode('|',$row["spnum"]);
		
		for($index=0;$index<count($spid);$index++) 
		{
			if($spid[$index]!="")
			{
				$num=$spnum[$index];
				$id=$spid[$index];
				
				//减库存
				$tab = "qdshop";
				$col = "spstock=spstock-$num,spsale=spsale+$num";
				$val = "id='$id'";
				$res = $db->editRecode($tab,$col,$val);	
				
			}
		}
		//及时配送商品需手机接单
		$tab = "qdorder";
		$col = "odstate=1,sort='$sort',addtime=NOW()";
		$val = "odid='$out_trade_no' and wxid='$open_id' and odfee='$total_fee'";
		$res = $db->editRecode($tab,$col,$val);
		
		$db->printorder($oid,$mdid,1);
	}else
	{
		$odres = $db->getOne("viprecord","vipno='$out_trade_no' and wxid='$open_id'");
		if(count($odres)>0)
		{
			$row = $odres["Viprecord"];
			$bdmoney=$row["bdmoney"];	
			$bdjiushui=$row["bdjiushui"];			
			
			$tab = "viprecord";
			$col = "bdstate=1,addtime=NOW()";
			$val = "id='".$row["id"]."'";
			$res = $db->editRecode($tab,$col,$val);
			
			
			$tab = "hymember";
			$col = "vipmoney=vipmoney+$bdmoney,vipjiushui=vipjiushui+$bdjiushui";
			$val = "wxid='$open_id'";
			$res = $db->editRecode($tab,$col,$val);
		}
		
	}
}
echo exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>'); 
//将xml格式转换成数组
function xmlToArray($xml)
{
  //禁止引用外部xml实体 
  libxml_disable_entity_loader(true);
  $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
  $val = json_decode(json_encode($xmlstring), true);
  return $val;
}
?>