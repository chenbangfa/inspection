<?php
header('Content-Type:application/json; charset=utf-8');
date_default_timezone_set("Asia/Shanghai");
require("data/db.php"); 
$tag=$db->getpar("tag");
$resV = '{"code":"-2","msg":"请求错误'.$tag.'"}';
switch($tag)
{
	case "okservice":
		$id=$db->getpar("odid");
		$wxid=$db->getpar("wxid");
		$stid=$db->getpar("stid");
		$handlemsg=$db->getpar("handlemsg");
		$handlefee =$db->getpar("handlefee");	
		$tab =$db->getpar("tab");
		$tabs =$db->getpar("tabs");				
		$res=$db->getOne("qdbusiness","wxid='$wxid' and mdid='$stid' and qdstate=2");
		if(count($res)>0)
		{	
			$refund_fee = floatval($handlefee*100);
			$res=$db->getOne($tab,"id='$id' and stid='$stid'");
			if(count($res)>0)
			{
				$row=$res["$tabs"];
				$total_fee=floatval($row["odfee"]*100);
				if($refund_fee>0)
				{
					$payinfo=$row["payinfo"];
					$yhwxid=$row["wxid"];
					if($payinfo=='在线支付')
					{
						$xcx=$db->getxcxid($stid);
						$appid = $xcx[0]["APPID"];
						$mch_id = $xcx[0]["xcxMCHID"];
						$key = $xcx[0]["xcxKEY"];
						
						$ret=retuik($refund_fee,$row["odid"],$total_fee,$row["odid"],$appid,$mch_id,$key);
						if($ret["info"]["return_code"]=="SUCCESS")
						{
							$db->editRecode("$tab","odstate=5","id='$id'");
							$db->editRecode("qdservice","state=1,handlewxid='$wxid',handlemsg='$handlemsg',handlefee='$handlefee',handletime=now()","odid='$id'");			
							$resV = '{"code":"1","msg":"退款成功，金额将原路退回"}';
						}else
							$resV = '{"code":"0","msg":"'.$ret["info"]["return_msg"].'"}';
						}
					else if($payinfo=='余额支付')
					{
						$db->editRecode("$tab","odstate=5","id='$id'");
						$db->editRecode("qdservice","state=1,handlewxid='$wxid',handlemsg='$handlemsg',handlefee='$handlefee',handletime=now()","odid='$id'");
						$db->editRecode("hymember","vipmoney=vipmoney+$handlefee","wxid='$yhwxid'");
						$hy=$db->getOne("hymember","wxid='$yhwxid'");
						$hy=$hy["Hymember"];
						$money=$hy["vipmoney"];					
						$tab = "viprecord";
						$col = "vipno,stid,wxid,hyname,hytel,ordertype,orderid,bdtype,bdwhy,bdmsg,bdmoney,bdbalance,bdjiushui,bdstate,addtime";
						$val = "('online','$stid','$yhwxid','".$row["hyname"]."','".$row["hytel"]."','online','".$row["odid"]."','add','售后退款','售后退款','$handlefee','$money',0,1,NOW())";				
						$resid = $db->addRecode($tab,$col,$val);
						$resV = '{"code":"1","msg":"退款成功，金额将原路退回"}';
					}
					
				}else
				{
					$db->editRecode("$tab","odstate=5","id='$id'");
					$db->editRecode("qdservice","state=1,handlewxid='$wxid',handlemsg='$handlemsg',handlefee='$handlefee',handletime=now()","odid='$id'");		
					$resV = '{"code":"1","msg":"处理成功"}';
				}
			}else
				$resV = '{"code":"0","msg":"没有该订单信息"}';
		}else
			$resV = '{"code":"0","msg":"没有权限"}';
		break;	
	case "jujueservice":
		$id=$db->getpar("odid");
		$wxid=$db->getpar("wxid");
		$stid=$db->getpar("stid");
		$handlemsg=$db->getpar("handlemsg");
		$handlefee =$db->getpar("handlefee");
		$odstateservice =$db->getpar("odstateservice");			
		$tab =$db->getpar("tab");
		$tabs =$db->getpar("tabs");				
		$res=$db->getOne("qdbusiness","wxid='$wxid' and mdid='$stid' and qdstate=2");
		if(count($res)>0)
		{	
			$refund_fee = floatval($handlefee*100);
			$res=$db->getOne("$tab","id='$id' and stid='$stid'");
			if(count($res)>0)
			{
				$row=$res["$tabs"];
				$total_fee=floatval($row["odfee"]*100);
				if($refund_fee>0)
				{
					$payinfo=$row["payinfo"];
					$yhwxid=$row["wxid"];
					if($payinfo=='在线支付')
					{
						$xcx=$db->getxcxid($stid);
						$appid = $xcx[0]["APPID"];
						$mch_id = $xcx[0]["xcxMCHID"];
						$key = $xcx[0]["xcxKEY"];  
						
						$ret=retuik($refund_fee,$row["odid"],$total_fee,$row["odid"],$appid,$mch_id,$key);
						if($ret["info"]["return_code"]=="SUCCESS")
						{
							$db->editRecode("$tab","odstate='$odstateservice'","id='$id'");
							$db->editRecode("qdservice","state=1,handlewxid='$wxid',handlemsg='$handlemsg',handlefee='$handlefee',handletime=now()","odid='$id'");			
							$resV = '{"code":"1","msg":"退款成功，金额将原路退回"}';
						}else
							$resV = '{"code":"0","msg":"'.$ret["info"]["return_msg"].'"}';
						}
					else if($payinfo=='余额支付')
					{
						$db->editRecode("$tab","odstate='$odstateservice'","id='$id'");
						$db->editRecode("qdservice","state=1,handlewxid='$wxid',handlemsg='$handlemsg',handlefee='$handlefee',handletime=now()","odid='$id'");
						$db->editRecode("hymember","vipmoney=vipmoney+$handlefee","wxid='$yhwxid'");
						$hy=$db->getOne("hymember","wxid='$yhwxid'");
						$hy=$hy["Hymember"];
						$money=$hy["vipmoney"];					
						$tab = "viprecord";
						$col = "vipno,stid,wxid,hyname,hytel,ordertype,orderid,bdtype,bdwhy,bdmsg,bdmoney,bdbalance,bdjiushui,bdstate,addtime";
						$val = "('online','$stid','$yhwxid','".$row["hyname"]."','".$row["hytel"]."','online','".$row["odid"]."','add','售后退款','售后退款','$handlefee','$money',0,1,NOW())";				
						$resid = $db->addRecode($tab,$col,$val);
						$resV = '{"code":"1","msg":"退款成功，金额将原路退回"}';
					}				
				}else
				{
					$db->editRecode("$tab","odstate='$odstateservice'","id='$id'");
					$db->editRecode("qdservice","state=1,handlewxid='$wxid',handlemsg='$handlemsg',handlefee='$handlefee',handletime=now()","odid='$id'");		
					$resV = '{"code":"1","msg":"处理成功"}';
				}
			}else
				$resV = '{"code":"0","msg":"没有该订单信息"}';
		}else
			$resV = '{"code":"0","msg":"没有权限"}';
		break;
	case "jujue":
		$id=$db->getpar("odid");
		$wxid=$db->getpar("wxid");
		$stid=$db->getpar("stid");		
		$res=$db->getOne("qdbusiness","wxid='$wxid' and mdid='$stid' and qdstate=2");
		if(count($res)>0)
		{	
			
			$res=$db->getOne("qdorder","id='$id' and stid='$stid'");
			if(count($res)>0)
			{
				$row=$res["Qdorder"];
				$handlefee=$row["odfee"];
				$total_fee=floatval($handlefee*100);
				$refund_fee = $total_fee;
				$handlemsg="商家拒绝接单";
					$payinfo=$row["payinfo"];
					$yhwxid=$row["wxid"];
					if($payinfo=='在线支付')
					{
						$xcx=$db->getxcxid($stid);
						$appid = $xcx[0]["APPID"];
						$mch_id = $xcx[0]["xcxMCHID"];
						$key = $xcx[0]["xcxKEY"];  
						$ret=retuik($refund_fee,$row["odid"],$total_fee,$row["odid"],$appid,$mch_id,$key);						
						if($ret["info"]["return_code"]=="SUCCESS")
						{
							$db->editRecode("qdorder","odstate=5","id='$id'");
							$db->editRecode("qdservice","state=1,handlewxid='$wxid',handlemsg='$handlemsg',handlefee='$handlefee',handletime=now()","odid='$id'");			
							$resV = '{"code":"1","msg":"退款成功，金额将原路退回"}';
						}else
							$resV = '{"code":"0","msg":"'.$ret["info"]["return_msg"].'"}';
						}
					else if($payinfo=='余额支付')
					{
						$db->editRecode("qdorder","odstate=5","id='$id'");
						$db->editRecode("qdservice","state=1,handlewxid='$wxid',handlemsg='$handlemsg',handlefee='$handlefee',handletime=now()","odid='$id'");
						$db->editRecode("hymember","vipmoney=vipmoney+$handlefee","wxid='$yhwxid'");
						$hy=$db->getOne("hymember","wxid='$yhwxid'");
						$hy=$hy["Hymember"];
						$money=$hy["vipmoney"];					
						$tab = "viprecord";
						$col = "vipno,stid,wxid,hyname,hytel,ordertype,orderid,bdtype,bdwhy,bdmsg,bdmoney,bdbalance,bdjiushui,bdstate,addtime";
						$val = "('online','$stid','$yhwxid','".$row["hyname"]."','".$row["hytel"]."','online','".$row["odid"]."','add','售后退款','售后退款','$handlefee','$money',0,1,NOW())";				
						$resid = $db->addRecode($tab,$col,$val);
						$resV = '{"code":"1","msg":"退款成功，金额将原路退回"}';
					}
				
			}else
				$resV = '{"code":"0","msg":"没有该订单信息"}';
		}else
			$resV = '{"code":"0","msg":"没有权限"}';
		break;	
	default:
		break;
}
	echo $resV;
function retuik($refund_fee,$out_trade_no,$total_fee,$date,$appid,$mch_id,$key)
{	
  $out_refund_no = $date;
  $nonce_str = nonceStr();

  $ref = strtoupper(md5("appid=$appid&mch_id=$mch_id&nonce_str=$nonce_str"."&out_refund_no=$out_refund_no&out_trade_no=$out_trade_no&refund_fee=$refund_fee&total_fee=$total_fee"."&key=$key")); //sign加密MD5


  $refund = array(
  'appid' =>$appid, //应用ID，固定
  'mch_id' => $mch_id, //商户号，固定
  'nonce_str' => $nonce_str, //随机字符串  
  'out_refund_no' => $out_refund_no, //商户内部唯一退款单号
  'out_trade_no' => $out_trade_no, //商户订单号,pay_sn码 1.1二选一,微信生成的订单号，在支付通知中有返回
  'refund_fee' => $refund_fee, //退款金额
  'total_fee' => $total_fee, //总金额
  'sign' => $ref//签名
  );

  $url = "https://api.mch.weixin.qq.com/secapi/pay/refund";
 
  $xml = arrayToXml($refund);
  
  $ch = curl_init();
      
        curl_setopt($ch,CURLOPT_TIMEOUT,30);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

    // 设置证书
   curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
    curl_setopt($ch, CURLOPT_SSLCERT,'D:\phpStudy\WWW\\'.EJYM.'\cert\apiclient_cert.pem');
	curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
    curl_setopt($ch, CURLOPT_SSLKEY,'D:\phpStudy\WWW\\'.EJYM.'\cert\apiclient_key.pem');
	 curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'pem');
    curl_setopt($ch, CURLOPT_CAINFO,'D:\phpStudy\WWW\\'.EJYM.'\cert\rootca.pem');
   
  $xml = curl_exec($ch);
 
  // 返回结果0的时候能只能表明程序是正常返回不一定说明退款成功而已
  if ($xml) {
    curl_close($ch);
    // 把xml转化成数组
    libxml_disable_entity_loader(true);
    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
//    var_dump($xmlstring);
    $result['errNum'] = 0;
    $result['info'] = object_to_array($xmlstring);
//    var_dump($result);
    return $result;
  } else {
    $error = curl_errno($ch);
    curl_close($ch);
    // 错误的时候返回错误码。
	print_r($error);
    $result['errNum'] = $error;
    return $result;
  }
}

function arrayToXml($arr) {
  $xml = "<root>";
  foreach ($arr as $key => $val) {
    if (is_array($val)) {
      $xml .= "<" . $key . ">" . arrayToXml($val) . "</" . $key . ">";
    } else {
      $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
    }
  }
  $xml .= "</root>";
  return $xml;
}

function object_to_array($obj) {
  $obj = (array) $obj;
  foreach ($obj as $k => $v) {
    if (gettype($v) == 'resource') {
      return;
    }
    if (gettype($v) == 'object' || gettype($v) == 'array') {
      $obj[$k] = (array) object_to_array($v);
    }
  }


  return $obj;
}

function nonceStr() {
  $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
  $str = "";
  $length = 32;
  for ($i = 0; $i < $length; $i++) {
    $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
  }
  // 随机字符串 
  return $str;
}


?>