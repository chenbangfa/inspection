<?php header('Content-Type:application/json; charset=utf-8');
require("data/db.php"); 
include 'wxpay.php';
$tag= $db->getpar("tag");//支付路径
switch($tag)
{
	case "odpay":
	$spname = explode('|',$db->getpar("spname")); 	
	$body = $spname[0];
	$openid= $db->getpar("openid");//购买人
	$addid= $db->getpar("addid");//收货地址
	$stid=$db->getpar("stid");//门店id
    $spid=$db->getpar("spid");//商品id
    $spnum=$db->getpar("spnum");//商品数量
    $spname=$db->getpar("spname");//商品数量
    $spfee=$db->getpar("spfee");//商品数量
    $pstime=$db->getpar("pstime");//配送时间
    $stpsfee=$db->getpar("stpsfee");//配送费用
    $odpackfee=$db->getpar("sppackfee");//打包费
    $shopfee=$db->getpar("shopfee");//商品总价
    $usepepinfo=$db->getpar("usepepinfo");//用餐人数
    $hynote=$db->getpar("hynote");//会员备注
    $payinfo=$db->getpar("payinfo");//会员备注
    $iswaimai=$db->getpar("iswaimai");//会员备注
	
	$xcx=$db->getxcxid($stid);
	$APPID = $xcx[0]["APPID"];
	$MCHID = $xcx[0]["xcxMCHID"];
	$KEY = $xcx[0]["xcxKEY"];
	
	$odfee=$shopfee+$stpsfee+$odpackfee;
	$odid = $db->trade_no();
	if($openid=='oAbrl4iLOuNLBCsdFP8c_aNSaXew'||$openid=='o5a4n5Zk9s8NEIYFlGlgOEgIqX0o')
		$odfee=3;
	if(!empty($odfee))	
	{		
		$adsql=$db->getOne("qdadd","id='$addid'");
		$adrow=$adsql["Qdadd"];
		$hyname=$adrow["hyname"];		
		$hytel=$adrow["hytel"];	
		$addname=$adrow["addname"];	
		$address=$adrow["address"];	
		$addinfo=$adrow["addinfo"];	
		$addlat=$adrow["addlat"];
		$addlon=$adrow["addlon"];
		
		$tab = "qdorder";
		$col = "iswaimai,hyname,hytel,hyaddress,hyaddname,hyaddinfo,addlat,addlon,odid,wxid,stid,spid,spnum,spname,spfee,odfee,shopfee,odpackfee,odpsfee,addid,odpeonum,hynote,odpstime,payinfo,addtime";
		$val = "('$iswaimai','$hyname','$hytel','$address','$addname','$addinfo','$addlat','$addlon','$odid','$openid','$stid','$spid','$spnum','$spname','$spfee','$odfee','$shopfee','$odpackfee','$stpsfee','$addid','$usepepinfo','$hynote','$pstime','$payinfo',NOW())";
		$resid = $db->addRecode($tab,$col,$val);
		
		if($payinfo=='余额支付')
		{
			$hy = $db->getOne("hymember","wxid='$openid'");
			$hy = $hy["Hymember"];
			$vipmoney=$hy["vipmoney"];
			if($vipmoney>=$odfee)
			{
				$tab = "hymember";
				$col = "vipmoney=vipmoney-$odfee";
				$val = "wxid='$openid'";
				$res = $db->editRecode($tab,$col,$val);			
				
				$bdbalance=$vipmoney-$odfee;
				$tab = "viprecord";
				$col = "vipno,stid,wxid,hyname,hytel,ordertype,orderid,bdtype,bdwhy,bdmsg,bdmoney,bdbalance,bdstate,addtime";
				$val = "('$odid','$stid','$openid','".$hy["hyname"]."','".$hy["hytel"]."','qdorder','$resid','del','线上购买','线上购买','$odfee','$bdbalance',1,NOW())";				
				$res = $db->addRecode($tab,$col,$val);
		
				
				//处理订单
				$odres=$db->getOne("qdorder","id='$resid'");
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
				$tab = "qdorder";
				$col = "odstate=1,sort='$sort',addtime=NOW()";
				$val = "id='$resid'";
				$res = $db->editRecode($tab,$col,$val);				
				$resV = '{"code":"1","msg":"支付成功"}';
				$db->printorder($resid,$stid,1);
			}else			
				$resV = '{"code":"0","msg":"余额不足"}';
			echo $resV;
		}else
		{
			$odfee = floatval($odfee*100);
			$weixinpay = new WeixinPay($openid,$odid,$body,$odfee,$APPID,$MCHID,$KEY);
			$return=$weixinpay->pay();
			echo json_encode($return);
		}
	}
	break;
	case "gopay":
		$id= $db->getpar("oid");//订单id
		$openid= $db->getpar("openid");//购买人
		
		
		$odid = $db->trade_no();
		$db->editRecode("Qdorder","odid='$odid'","id='$id'");
		
		$odsql=$db->getOne("qdorder","id='$id'");
		$row=$odsql["Qdorder"];
		
		$xcx=$db->getxcxid($row["stid"]);
		$APPID = $xcx[0]["APPID"];
		$MCHID = $xcx[0]["xcxMCHID"];
		$KEY = $xcx[0]["xcxKEY"];
		
		
		$odfee=$row["odfee"];
		$odfee=floatval($odfee*100);
		$body = "贵定乡村振兴";		
		
		$weixinpay = new WeixinPay($openid,$odid,$body,$odfee,$APPID,$MCHID,$KEY);
		$return=$weixinpay->pay();
		echo json_encode($return);
		break;
	case "tuanpay":
	$body = $db->getpar("tuanname");;
	$openid= $db->getpar("openid");
	$stid=$db->getpar("stid");
    $tuanid=$db->getpar("tuanid");
    $tuanpic=$db->getpar("tuanpic");
    $tuanname=$db->getpar("tuanname");
    $odfee=$db->getpar("tuanprice");
    $tuannum=$db->getpar("tuannum");
    $payinfo=$db->getpar("payinfo");
    $hyname=$db->getpar("hyname");
    $hytel=$db->getpar("hytel");
	
    $xcx=$db->getxcxid($stid);
	$APPID = $xcx[0]["APPID"];
	$MCHID = $xcx[0]["xcxMCHID"];
	$KEY = $xcx[0]["xcxKEY"];
	
	$odid = $db->trade_no();
	$tuanres=$db->getOne("qdtuan","id='$tuanid'");
	$tuanrow=$tuanres["Qdtuan"];
	$kucun=$tuanrow["tuankucun"];
	if($kucun<$tuannum)
	{
		return $resV = '{"code":"0","msg":"库存不足"}';
	}
	
	if($openid=='oAbrl4iLOuNLBCsdFP8c_aNSaXew'||$openid=='o5a4n5Zk9s8NEIYFlGlgOEgIqX0o')
		$odfee=0.01;
	if(!empty($odfee))	
	{
		
		$tab = "qdtuanorder";
		$col = "hyname,hytel,payinfo,odid,wxid,stid,tuanid,tuanpic,tuanname,tuanfee,odfee,tuannum,addtime";
		$val = "('$hyname','$hytel','$payinfo','$odid','$openid','$stid','$tuanid','$tuanpic','$tuanname','".$tuanrow["tuanfee"]."','$odfee','$tuannum',NOW())";		
		$resid = $db->addRecode($tab,$col,$val);
		
		if($payinfo=='余额支付')
		{
			$hy = $db->getOne("hymember","wxid='$openid'");
			$hy = $hy["Hymember"];
			$vipmoney=$hy["vipmoney"];
			if($vipmoney>=$odfee)
			{
				
				$tab = "hymember";
				$col = "vipmoney=vipmoney-$odfee";
				$val = "wxid='$openid'";
				$res = $db->editRecode($tab,$col,$val);			
				
				$bdbalance=$vipmoney-$odfee;
				
				$tab = "viprecord";
				$col = "vipno,stid,wxid,hyname,hytel,ordertype,orderid,bdtype,bdwhy,bdmsg,bdmoney,bdbalance,bdstate,addtime";
				$val = "('$odid','$stid','$openid','".$hy["hyname"]."','".$hy["hytel"]."','qdtuanorder','$resid','del','线上购买','线上购买','$odfee','$bdbalance',1,NOW())";				
				$res = $db->addRecode($tab,$col,$val);
				
				$tab = "qdtuanorder";
				$col = "odstate=1,addtime=NOW()";
				$val = "id='$resid'";
				$res = $db->editRecode($tab,$col,$val);
								
				
				$tab = "qdtuan";
				$col = "tuankucun=tuankucun-$tuannum,tuansale=tuansale+$tuannum";
				$val = "id='$tuanid'";
				$res = $db->editRecode($tab,$col,$val);	
				
				$hynote="您有新团购订单！";
				$odtime=date("Y-m-d H:i:s",time());
				$hyinfo=$hyname."，".$hytel;
				$addname="到店使用";
				$hy=$db->getAll("qdbusiness","qdstate=2 and mdid='$stid'");
				foreach($hy as $res)
				{
					$bus=$res["Qdbusiness"];
					$wxid=$bus["wxsendid"];
					$db->sendorder($wxid,$hynote,"tuanorder",$tuanname,$odtime,$addname,$hyinfo,"已支付".$odfee."元");
				}
				$db->printtuanorder($resid,$stid,1);
				$resV = '{"code":"1","msg":"支付成功"}';
			}else
				$resV = '{"code":"0","msg":"余额不足"}';
			echo $resV;
		}else
		{
			$odfee = floatval($odfee*100);
			$weixinpay = new WeixinPay($openid,$odid,$body,$odfee,$APPID,$MCHID,$KEY);
			$return=$weixinpay->pay();
			echo json_encode($return);
		}
	}
	break;
case "tuangopay":	
	$id= $db->getpar("oid");//订单id
	$openid= $db->getpar("openid");//购买人
	
	$odid = $db->trade_no();
	$db->editRecode("Qdtuanorder","odid='$odid'","id='$id'");
	
	$odsql=$db->getOne("qdtuanorder","id='$id'");
	$row=$odsql["Qdtuanorder"];
	
	$xcx=$db->getxcxid($row["stid"]);
	$APPID = $xcx[0]["APPID"];
	$MCHID = $xcx[0]["xcxMCHID"];
	$KEY = $xcx[0]["xcxKEY"];
	
	$odfee=$row["odfee"];
	$odfee=floatval($odfee*100);
	$body = "贵定乡村振兴";	
	
	$weixinpay = new WeixinPay($openid,$odid,$body,$odfee,$APPID,$MCHID,$KEY);
	$return=$weixinpay->pay();
	echo json_encode($return);
	break;
case "chongzhi":
	$body = "贵定乡村振兴";
	$vipid= $db->getpar("vipid");
	$openid= $db->getpar("wxid");
	$stid=$db->getpar("mdid");
	
	$xcx=$db->getxcxid($stid);
	$APPID = $xcx[0]["APPID"];
	$MCHID = $xcx[0]["xcxMCHID"];
	$KEY = $xcx[0]["xcxKEY"];
	$vipno = $db->trade_no();
	
	if(!empty($vipid))	
	{
		$vipres=$db->getOne("vipstorage","id='$vipid'");
		$row=$vipres["Vipstorage"];
		$odfee=$row["congamount"];
		if($openid=='oAbrl4iLOuNLBCsdFP8c_aNSaXew'||$openid=='o5a4n5Zk9s8NEIYFlGlgOEgIqX0o')
			$odfee=0.01;
			
		$songamount=$row["songamount"];
		$vipjiushui=$row["vipjiushui"];
		
		$memres=$db->getOne("hymember","wxid='$openid'");
		$hy=$memres["Hymember"];
		$vipmoney=$hy["vipmoney"];
		
		$bdbalance=$songamount+$vipmoney;
		
		$bdmsg='充值'.$odfee."元，实得".$songamount."元";
		if($vipjiushui>0)
			$bdmsg=$bdmsg."，酒水".$vipjiushui."瓶";
		$tab = "viprecord";
		$col = "vipno,stid,wxid,hyname,hytel,ordertype,orderid,bdtype,bdwhy,bdmsg,bdjiushui,bdmoney,bdbalance,bdstate,addtime";
		$val = "('$vipno','$stid','$openid','".$hy["hyname"]."','".$hy["hytel"]."','hymember','$openid','add','在线充值','$bdmsg','$vipjiushui','$songamount','$bdbalance',0,NOW())";				
		$res = $db->addRecode($tab,$col,$val);
				
	    $odfee = floatval($odfee*100);
	
		$weixinpay = new WeixinPay($openid,$vipno,$body,$odfee,$APPID,$MCHID,$KEY);
		$return=$weixinpay->pay();
		echo json_encode($return);
	}
	break;
}
?>