<?php 
header("Content-type: text/html; charset=utf-8");
define("TOKEN", "anquan");
$wechatObj = new wechatCallbackAPI();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
} else {
    $wechatObj->response();
}
class wechatCallbackAPI {	
    public $_fromUsername = "";
    public $_toUsername = "";
    public $_msgType = "";
    public $_createTime = "";
    public $_time = "";
    /* 验证微信签名 */
    public function checkSignature() {
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
    public function valid() {
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }
    /* 微信互动请求页面 */
    public function response() {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //extract post data
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->_fromUsername = $postObj->FromUserName;
            $this->_toUsername = $postObj->ToUserName;
            $this->_msgType = trim($postObj->MsgType);
            $this->_createTime = trim($postObj->CreateTime);
            //setcookie("wxid",$this->_fromUsername,time()+360*24*3600,"/");
            $this->_time = time();
            $responseRes = $this->requestMsg($this->_msgType, $postObj);
            echo $responseRes;
        } else {
            echo "";
            exit;
        }
    }
	
	
    /* 处理微信端的请求事件 */
    public function requestMsg($msgType, $postObj) {
        switch ($msgType) {
			case "text": //事件推送			 
				$wxid=$this->_fromUsername;
				$keyword = trim($postObj->Content);
				if($keyword=="抽奖")
				{
					$wb="Hi 您好，欢迎关注~";
					$textTpl = "<xml>
					<ToUserName><![CDATA[" . $wxid . "]]></ToUserName>
					<FromUserName><![CDATA[" . $this->_toUsername . "]]></FromUserName>
					<CreateTime>" . $this->_time . "</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[".$wb."]]></Content>
					</xml>";
					return $textTpl;	
				}else
				{
					$textTpl = "<xml>
					<ToUserName><![CDATA[" . $wxid . "]]></ToUserName>
					<FromUserName><![CDATA[" . $this->_toUsername . "]]></FromUserName>
					<CreateTime>" . $this->_time . "</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[客服正在赶来路上~]]></Content>
					</xml>";
					echo $textTpl;
				}
				break;
            case "event": //事件推送
                $event = trim($postObj->Event);
                $eventKey = trim($postObj->EventKey);
                $ticket = trim($postObj->Ticket);
				$wxid=$this->_fromUsername;
                switch ($event) {
					case "subscribe":
						$wb="Hi 您好，欢迎关注~";
						if($eventKey!="")
						{
							$id=substr($eventKey,8);
							$con = mysql_connect("127.0.0.1", "root", "root");
							mysql_select_db("anquan", $con);
							mysql_query("UPDATE hymember set wxsendid='$wxid' where id='$id'",$con);										
							$wb="Hi 您好，微信绑定成功~";
						}
						$textTpl = "<xml>
						<ToUserName><![CDATA[" . $wxid . "]]></ToUserName>
						<FromUserName><![CDATA[" . $this->_toUsername . "]]></FromUserName>
						<CreateTime>" . $this->_time . "</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[".$wb."]]></Content>
						</xml>";
						echo $textTpl;					
                    break;						
                    case "unsubscribe":					
                        break;
                    case "SCAN": //用户已关注时的事件推送
						$wb="Hi 您好，欢迎关注~";
						if($eventKey!="")
						{
							$id=$eventKey;//substr($eventKey,8);
							$con = mysql_connect("127.0.0.1", "root", "root");
							mysql_select_db("anquan", $con);
							mysql_query("UPDATE hymember set wxsendid='$wxid' where id='$id'",$con);
							$wb="Hi 您好，微信绑定成功~~~";
						}
						$textTpl = "<xml>
						<ToUserName><![CDATA[" . $wxid . "]]></ToUserName>
						<FromUserName><![CDATA[" . $this->_toUsername . "]]></FromUserName>
						<CreateTime>" . $this->_time . "</CreateTime>
						<MsgType><![CDATA[text]]></MsgType>
						<Content><![CDATA[".$wb."]]></Content>
						</xml>";
						echo $textTpl;
					    break;
                    case "CLICK":
						if($eventKey=="youli")
						{
							
							
						}
                        break;

                    case "VIEW": 
                        break;

                    case "LOCATION":
                        break;

                    default:
                        break;
                    }
                    break;
                default:
                    break;
                }
            }
        }



		//获得Token信息
	function getToken($flag=false){
		$APPID="wx9d0101ed8c35098a";
    	$APPSECRET="3909ad26ec385267ac92b4de576ce615";    
		$tokenInfo = getIni();
		$tim = $tokenInfo["tim"];
		$tim = is_numeric($tim)?$tim:0;
		$exp = $tokenInfo["exp"];
		$exp = is_numeric($exp)?$exp:0;
		$token = $tokenInfo["token"];
		$tm = $tim+$exp-200;
		if(time()>$tm||$flag){
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;
			$json = http_json($url);
			if (!empty($json["errcode"])) {
				upDateConfig("token","");
				upDateConfig("exp",0,"int");
				upDateConfig("tim",0,"int");
				upDateConfig("uptie",date("Y-m-d H:i:s",$tim));
				return "";
			} else{
				$token = $json["access_token"];
				$exp = $json["expires_in"];
				$tim = time();
				upDateConfig("token",$token);
				upDateConfig("exp",$exp,"int");
				upDateConfig("tim",$tim,"int");
				upDateConfig("uptie",date("Y-m-d H:i:s",$tim));
				return $token;
			}
		 }else
		 	return $token;
	}

function _reply_customer($touser,$type,$content)
{    
    $ACC_TOKEN=getToken();
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
    
    $result = https_post($url,$data);
    $final = json_decode($result);
    return $final;
}

function https_post($url,$data=null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	if (!empty($data)) 
	{
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

	
//下载操作
function get_file($url, $folder, $pic_name) 
{
	$destination_folder = $folder ? $folder . '/' : ''; //文件下载保存目录
 	if (!file_exists($destination_folder))
	{
		//检查是否有该文件夹，如果没有就创建，并给予最高权限
		$path = $destination_folder;
  		$i = 0;
		while (!@mkdir($path, 0777))
		{
			if (is_dir($path)) break;
			$i++;
			if (@mkdir($path . str_repeat("/..", $i) , 0777)) $i = 0;
		}
	}
	$newfname = $destination_folder . $pic_name; //文件PATH
	$file = fopen($url, 'rb');
	if ($file)
	{
		$newf = fopen($newfname, 'wb');
		if ($newf)
		{
			while (!feof($file))
			{
				fwrite($newf, fread($file, 1024 * 8) , 1024 * 8);
			}
		}
		if ($file)
		{
			fclose($file);
		}
		if ($newf)
		{
			fclose($newf);
		}
	}
	return $newfname;
}

function http_request($url, $pars = "") 
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	if ($pars != "")
	{
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $pars);
	}
	curl_setopt($curl, CURLOPT_HEADER, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //这个是重点。
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	$data = curl_exec($curl);
	curl_close($curl);
	return $data;
}

//获取INI
	function getIni($key = "",$file ="wxtoken.ini"){
		$file = dirname(__FILE__)."\/"."data/config/wxtoken.ini";
		$ini = parse_ini_file($file);
		if(empty($key)||!array_key_exists($key,$ini)){
			return $ini;
		}else{
			return $ini[$key];
		}
	}
	//修改INI
	function upDateConfig($ini, $value,$type="string",$file="wxtoken.ini") 
	{
		$file = dirname(__FILE__)."\/"."data/config/wxtoken.ini";
		$str = file_get_contents($file); 
		$str2=""; 
		if($type=="int") 
		{ 
			$str2 = preg_replace("/" . $ini . "=(.*);/", $ini . "=" . $value . ";", $str);
			$z = getIni($ini);
			if($str2==$str && $z!=$value){
				$str2 .= "\r\n".$ini . "=\"" . $value . "\";";
			} 
		} 
		else 
		{ 
			$str2 = preg_replace("/" . $ini . "=(.*);/", $ini . "=\"" . $value . "\";",$str); 
			$z = getIni($ini);
			if($str2==$str && $z!=$value){
				$str2 .= "\r\n".$ini . "=\"" . $value . "\";";
			}
		}
		file_put_contents($file, $str2); 
	}
	//将URL的JSON内容转换成ARRAY
	function http_json($url,$pars="",$head=1){
		$reqRes = http_request($url,$pars,$head);
		$pos = strpos($reqRes,"{",0);
		$strJson = substr($reqRes,$pos);
		$json = json_decode($strJson,true);	
		return $json;
	}
