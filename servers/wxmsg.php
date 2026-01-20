<?php 
header("Content-type: text/html; charset=utf-8");
define("TOKEN", "remsg");
$wechatObj = new wechatCallbackAPI();
if (isset($_GET['echostr']))
{
    $wechatObj->valid();
} else
{
    $wechatObj->response();
}
class wechatCallbackAPI {	
    public $_fromUsername = "";
    public $_toUsername = "";
    public $_msgType = "";
    public $_createTime = "";
    public $_time = "";
    /* 验证微信签名 */
    public function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        );
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
    /* 验证微信的有效性 */
    public function valid()
	{
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }
    /* 微信互动请求页面 */
    public function response()
	{		
		//require("data/db.php"); $db->writeMsg("page:".$openid);
        //get post data, May be due to the different environments
		require("data/db.php");	
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];	
        //extract post data
        if (!empty($postStr))
		{
			$db->writeMsg($postStr);	
			$arr =(array)json_decode($postStr,true);
			$MsgType=$arr["MsgType"];
			switch($MsgType)
			{
				case "event": 
					$Event=$arr["Event"];
					if($Event=="update_waybill_status")
					{
						$order_status=$arr["order_status"];
						$odid=$arr["shop_order_id"];
						$psname=$arr["agent"]["name"];
						$pstel=$arr["agent"]["phone"];						
						$str="";
						switch($order_status)
						{
							case "201":
								$str=",odstate=2,odfhtime=NOW()";
							break;
							case "202":
								$str=",odstate=2,odfhtime=NOW()";
							break;
							case "301":
								$str=",odstate=2";
							break;
							case "302":
								$str=",odstate=3,odendtime=NOW()";
							break;
							default:
								$str="";
						}
						if($psname!=null&&$psname!="")
							$str.=",odpsname='$psname',odpstel='$pstel'";
							
						$tab = "qdorder";
						$col = "odpsmode='闪送',odpsstatus='$order_status'".$str;
						$val = "odid='$odid'";
						$res = $db->editRecode($tab,$col,$val);			
					}
			 	break;				
			 	default:
					$ghid=$arr["ToUserName"];
					$xcx=$db->getxcxghid($ghid);
					$APPID = $xcx[0]["APPID"];
					$APPSECRET = $xcx[0]["APPSECRET"];
					$token =$db->xcxToken($APPID,$APPSECRET);	
					$Content="非常抱歉，人工客服繁忙~\n\r请拨打电话咨询:".$xcx[0]["sttel"]."\n\r".$xcx[0]["staddinfo"];
					$FromUserName=$arr["FromUserName"];
					return $this->_reply_customer($token,$FromUserName,"text",$Content);
                break;
			}
        } else {
            exit;
        }
    }
	
	function _reply_customer($ACC_TOKEN,$touser,$type,$content)
	{  
		
		if($type=="text")
		{
			$data = '{
				"touser":"'.$touser.'",
				"msgtype":"text",
				"text":
				{
					 "content":"'.$content.'"
				}
			}';
		}else if($type=="image"){
			$data = '{
				"touser":"'.$touser.'",
				"msgtype":"image",
				"image":
				{
					 "media_id":"'.$content.'"
				}
			}';
		}
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$ACC_TOKEN;
		
		$result = $this->http_request($url,$data);
		$final = json_decode($result);
		return $final;
	}
	
	function http_request($url, $data = null)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
}