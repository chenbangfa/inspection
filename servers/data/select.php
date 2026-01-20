<?php
require("config.php");
class Select
{

	// Header通用参数
	function getHeader($data)
	{
		$bh = $this->trade_no();
		$time = time();
		$json = json_encode($data);
		$str = $bh . '969184732560515072' . $time . '2e020c3861aa46e1ae47eeb3e611c941' . $json;
		$res = md5($str);
		$header = array();
		$header[] = "Content-Type:application/json";
		$header[] = "appid:969184732560515072";
		$header[] = "uid:" . $bh;
		$header[] = "stime:" . $time;
		$header[] = "sign:" . $res;
		return $header;
	}
	// 请求工具方法
	function http($url, $method = 'GET', $postfields = null, $debug = false)
	{
		$headers = $this->getHeader($postfields);
		$body = json_encode($postfields);

		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ci, CURLOPT_TIMEOUT, 30);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ci, CURLINFO_HEADER_OUT, true);
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers); // 设置通用传参
		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, true);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $body);
				}
				break;
		}
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
		curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);// 从证书中检查SSL加密算法是否存在
		curl_setopt($ci, CURLOPT_URL, $url);
		$response = curl_exec($ci);
		$http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$request_header = curl_getinfo($ci, CURLINFO_HEADER_OUT);
		if ($debug) {
			//print_r($request_header);
		}
		curl_close($ci);
		return array($http_code, $response);
	}

	function getIni($key = "", $file = "wxtoken.ini")
	{
		$file = ROOT . "data/" . $file;
		$ini = parse_ini_file($file);
		if (empty($key) || !array_key_exists($key, $ini)) {
			return $ini;
		} else {
			return $ini[$key];
		}
	}
	//修改INI
	function upDateConfig($ini, $value, $type = "string", $file = "wxtoken.ini")
	{
		$file = ROOT . "data/" . $file;
		$str = file_get_contents($file);
		$str2 = "";
		if ($type == "int") {
			$str2 = preg_replace("/" . $ini . "=(.*);/", $ini . "=" . $value . ";", $str);
			$z = $this->getIni($ini);
			if ($str2 == $str && $z != $value) {
				$str2 .= "\r\n" . $ini . "=\"" . $value . "\";";
			}
		} else {
			$str2 = preg_replace("/" . $ini . "=(.*);/", $ini . "=\"" . $value . "\";", $str);
			$z = $this->getIni($ini);
			if ($str2 == $str && $z != $value) {
				$str2 .= "\r\n" . $ini . "=\"" . $value . "\";";
			}
		}
		file_put_contents($file, $str2);
	}

	//HTTP 远程请求
	function http_request($url, $pars = "", $head = 1)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		if ($pars != "") {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $pars);
		}
		curl_setopt($curl, CURLOPT_HEADER, $head);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
	//将URL的JSON内容转换成ARRAY
	function http_json($url, $pars = "", $head = 1)
	{
		$reqRes = $this->http_request($url, $pars, $head);
		$pos = strpos($reqRes, "{", 0);
		$strJson = substr($reqRes, $pos);
		$json = json_decode($strJson, true);
		return $json;
	}
	//写入日志
	function writeMsg($msg = "")
	{
		$logDir = dirname(LOGURL);
		if (!is_dir($logDir)) {
			mkdir($logDir, 0777, true);
		}
		file_put_contents(LOGURL, "[" . date("Y-m-d h:i:s") . "]\r\n" . $msg . "\r\n\r\n", FILE_APPEND);
	}
	//写入内容
	function writeInfo($msg = "")
	{
		$logDir = dirname(LOGINFO);
		if (!is_dir($logDir)) {
			mkdir($logDir, 0777, true);
		}
		file_put_contents(LOGINFO, "$msg");
	}
	//读取内容
	function readInfo()
	{
		$file = fopen(LOGINFO, "r");
		$res = fread($file, filesize(LOGINFO));
		fclose($file);
		return $res;
	}
	//字符串截取
	function strCut($str, $len = 20)
	{
		if (mb_strlen($str, "utf8") > $len)
			return mb_substr($str, 0, $len - 1, "utf8") . "...";
		else
			return $str;
	}
	//获得参数
	function getPar($key = "")
	{
		if (empty($key))
			return "";
		else if (isset($_GET[$key]))
			return $this->replaceKeyWord($_GET[$key]);
		else if (isset($_POST[$key]))
			return $this->replaceKeyWord($_POST[$key]);
		else if (isset($_FILES[$key]))
			return $_FILES[$key];
		else if (isset($_COOKIE[$key]))
			return $this->replaceKeyWord($_COOKIE[$key]);
		else
			return "";
	}
	function replaceKeyWord($strV)
	{
		return str_replace("'", "\\'", $strV);
	}

	//时间相差天数
	function diffDays($day1, $day2)
	{
		$second1 = strtotime($day1);
		$second2 = strtotime($day2);

		if ($second1 < $second2) {
			$tmp = $second2;
			$second2 = $second1;
			$second1 = $tmp;
		}
		return ($second1 - $second2) / 86400;
	}
	//PHP计算两个时间差的方法
	function diffTime($startdate, $enddate, $t)
	{
		$year = floor((strtotime($enddate) - strtotime($startdate)) / 86400 / 365);
		$month = floor((strtotime($enddate) - strtotime($startdate)) / 86400 / 30);
		$date = floor((strtotime($enddate) - strtotime($startdate)) / 86400);
		$hour = floor((strtotime($enddate) - strtotime($startdate)) % 86400 / 3600);
		$minute = floor((strtotime($enddate) - strtotime($startdate)) % 86400 / 60);
		$second = floor((strtotime($enddate) - strtotime($startdate)) % 86400 % 60);
		switch ($t) {
			case 1:
				return $year;
				break;
			case 2:
				return $month;
				break;
			case 3:
				return $date;
				break;
			case 4:
				return $hour;
				break;
			case 5:
				return $minute;
				break;
			case 6:
				return $second;
				break;
			case 7:
				return $year . "年" . $month . "月" . $date . "天" . $hour . "时" . $minute . "分" . $second . "秒";
				break;
			default:
				return $year . "年" . $month . "月" . $date . "天" . $hour . "时" . $minute . "分" . $second . "秒";
				break;

		}
	}
	/**
	 * 获取指定类型的日期区间
	 * @param type ：1 今天、2 昨天、3 本周、4 上周、5 本月、6 上月、7 本季度、8 上季度、9 本年、10 上年
	 * @return array
	 */
	function getTenTypeTime($type)
	{
		switch ($type) {
			case 1:
				$start = date("Y-m-d 00:00:00", time());
				$end = date("Y-m-d 23:59:59", time());
				break;
			case 2:
				$start = date("Y-m-d 00:00:00", strtotime("-1 day"));
				$end = date("Y-m-d 23:59:59", strtotime("-1 day"));
				break;
			case 3:
				$start = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y")));
				$end = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y")));
				break;
			case 4:
				$start = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1 - 7, date("Y")));
				$end = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - date("w") + 7 - 7, date("Y")));
				break;
			case 5:
				$start = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), 1, date("Y")));
				$end = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("t"), date("Y")));
				break;
			case 6:
				$start = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") - 1, 1, date("Y")));
				$end = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), 0, date("Y")));
				break;
			case 7:
				$season = ceil((date('n')) / 3);//当月是第几季度
				$start = date('Y-m-d H:i:s', mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y')));
				$end = date('Y-m-d H:i:s', mktime(23, 59, 59, $season * 3, date('t', mktime(0, 0, 0, $season * 3, 1, date("Y"))), date('Y')));
				break;
			case 8:
				$season = ceil((date('n')) / 3) - 1;//上季度是第几季度
				$start = date('Y-m-d H:i:s', mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y')));
				$end = date('Y-m-d H:i:s', mktime(23, 59, 59, $season * 3, date('t', mktime(0, 0, 0, $season * 3, 1, date("Y"))), date('Y')));
				break;
			case 9:
				$start = date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, date('Y', time())));
				$end = date('Y-m-d H:i:s', mktime(23, 59, 59, 12, 31, date('Y', time())));
				break;
			case 10:
				$start = date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, date('Y', strtotime("-1 year"))));
				$end = date('Y-m-d H:i:s', mktime(23, 59, 59, 12, 31, date('Y', strtotime("-1 year"))));
				break;
			default:
				$start = date("Y-m-d 00:00:00", time());
				$end = date("Y-m-d 23:59:59", time());
				break;
		}
		return '{"start":"' . $start . '","end":"' . $end . '"}';
	}
	function trade_no()
	{
		$order_id_main = date('Ymd') . rand(1000, 9999);
		//订单号码主体长度
		$order_id_len = strlen($order_id_main);
		$order_id_sum = 0;
		for ($i = 0; $i < $order_id_len; $i++) {
			$order_id_sum += (int) (substr($order_id_main, $i, 1));
		}
		$order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);

		$timeStamp = time();

		return "QD" . $order_id . $timeStamp;
	}

	function hexiaoma()
	{
		$timeStamp = time();
		return rand(10000, 99999) . substr($timeStamp, -6) . substr(microtime(), 2, 3);
	}

	//生成菜单
	function createMenu($menu)
	{
		if (!empty($menu)) {
			$token = $this->getToken();
			$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $token;
			$json = $this->http_json($url, $menu, 0);
			if ($json["errcode"] == 0) {
				return 1;
			} else
				return 0;
		}
		return 0;
	}

	//验证身份证
	function is_idcard($id)
	{
		$id = strtoupper($id);
		$regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
		$arr_split = array();
		if (!preg_match($regx, $id)) {
			return FALSE;
		}
		if (15 == strlen($id)) //检查15位
		{
			$regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

			@preg_match($regx, $id, $arr_split);
			//检查生日日期是否正确
			$dtm_birth = "19" . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
			if (!strtotime($dtm_birth)) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else      //检查18位
		{
			$regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
			@preg_match($regx, $id, $arr_split);
			$dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
			if (!strtotime($dtm_birth)) //检查生日日期是否正确
			{
				return FALSE;
			} else {
				//检验18位身份证的校验码是否正确。
				//校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
				$arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
				$arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
				$sign = 0;
				for ($i = 0; $i < 17; $i++) {
					$b = (int) $id[$i];
					$w = $arr_int[$i];
					$sign += $b * $w;
				}
				$n = $sign % 11;
				$val_num = $arr_ch[$n];
				if ($val_num != substr($id, 17, 1)) {
					return FALSE;
				} //phpfensi.com
				else {
					return TRUE;
				}
			}
		}
	}
	/**
	 * 	作用：产生随机字符串，不长于32位
	 */

	function gettanweinum($stallid)
	{
		$teamres = $this->getOne("stallInspect", "id='$stallid'");
		$row = $teamres["StallInspect"];

		$stallnum = $row["stallnum"];
		$exitzhouqi = $row["exitzhouqi"];
		$exitdate = $row["exitdate"];
		$exittime = $row["exittime"];
		switch ($exitzhouqi) {
			case '每天':
				$startTime = date('Y-m-d ' . $exittime);
				$count = $this->getCount("stallOrder", "stallId='$stallid' and addTime>='$startTime'");
				$tanwei = $stallnum - $count;//剩余摊位
				break;
			case '每周':
				$w = date('w') == 0 ? 7 : date('w');
				$week = $this->getzhouqi($exitdate);//重置时间					
				$startTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') - date('w') + 1, date('y')));
				if (date('w') == 0)
					$startTime = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y')));
				if ($w >= $week) {
					$week--;
					$startTime = date("Y-m-d " . $exittime, strtotime("+$week day", strtotime($startTime)));
				} else {
					$startTime = date("Y-m-d " . $exittime, strtotime("-7 day", strtotime($startTime)));
					$week--;
					$startTime = date("Y-m-d " . $exittime, strtotime("+$week day", strtotime($startTime)));
				}
				$count = $this->getCount("stallOrder", "stallId='$stallid' and addTime>='$startTime'");
				$tanwei = $stallnum - $count;//剩余摊位
				break;
			case '每月':
				$day = date('d');
				if ($day >= $exitdate) {
					$startTime = date('Y-m-' . $exitdate . ' ' . $exittime);
				} else {
					$month = date('m', strtotime('midnight first day of -1 month'));
					$startTime = date('Y-' . $month . '-' . $exitdate . ' ' . $exittime);
				}
				$count = $this->getCount("stallOrder", "stallId='$stallid' and addTime>='$startTime'");
				$tanwei = $stallnum - $count;//剩余摊位
				break;
		}
		return $tanwei;
	}
	public function createNoncestr($length = 32)
	{
		$chars = "0123456789abcdefghijklmnopqrstuvwxyz";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}

	//模版消息，新订单通知
	function dbsxsend($wxid, $zt, $msg)
	{
		$title = "您好，您有新的待办";
		$remark = "请及时处理。";
		$pars = array(
			"Title" => array("M" => "$wxid", "T" => "cMNf35j-oxnKplmBwOGBu9kJuWlnhfunt-ktsWytG-M", "U" => "", "url" => "myorder"),
			"First" => array("N" => $title, "C" => ""),
			"K1" => array("N" => $zt, "C" => ""),
			"K2" => array("N" => $msg, "C" => ""),
			"Remark" => array("N" => $remark, "C" => "#0055FF")
		);
		return $this->getTemp($pars, "PUBMSG");
	}

	//发送模版消息方法
	function getTemp($pars, $mbKey)
	{
		if (empty($mbKey))
			return "";
		$mbKey = strtoupper($mbKey);
		$resKF = "";
		switch ($mbKey) {
			case "PUBMSG":
				$resKF = '{
			"touser":"' . $pars["Title"]["M"] . '",
			"template_id":"' . $pars["Title"]["T"] . '",
			"url":"' . $pars["Title"]["U"] . '",
			"miniprogram":{"appid":"wxd53bea03ffb467cc","pagepath":"pages/' . $pars["Title"]["url"] . '/' . $pars["Title"]["url"] . '"},          
			"data":{"first": {"value":"' . $pars["First"]["N"] . '","color":"' . $pars["First"]["C"] . '"},';
				if (!empty($pars["K1"]))
					$resKF .= '"keyword1":{"value":"' . $pars["K1"]["N"] . '","color":"' . $pars["K1"]["C"] . '"},';
				if (!empty($pars["K2"]))
					$resKF .= '"keyword2":{"value":"' . $pars["K2"]["N"] . '","color":"' . $pars["K2"]["C"] . '"},';
				if (!empty($pars["K3"]))
					$resKF .= '"keyword3":{"value":"' . $pars["K3"]["N"] . '","color":"' . $pars["K3"]["C"] . '"},';
				if (!empty($pars["K4"]))
					$resKF .= '"keyword4":{"value":"' . $pars["K4"]["N"] . '","color":"' . $pars["K4"]["C"] . '"},';
				if (!empty($pars["K5"]))
					$resKF .= '"keyword5":{"value":"' . $pars["K5"]["N"] . '","color":"' . $pars["K5"]["C"] . '"},';
				$resKF .= '"remark":{"value":"' . $pars["Remark"]["N"] . '","color":"' . $pars["Remark"]["C"] . '"}}
			}';
				break;
			case "PUBMSGGZH":
				$resKF = '{
					"touser":"' . $pars["Title"]["M"] . '",
					"template_id":"' . $pars["Title"]["T"] . '",
					"url":"' . $pars["Title"]["U"] . '",
					"data":{"first": {"value":"' . $pars["First"]["N"] . '","color":"' . $pars["First"]["C"] . '"},';
				if (!empty($pars["K1"]))
					$resKF .= '"keyword1":{"value":"' . $pars["K1"]["N"] . '","color":"' . $pars["K1"]["C"] . '"},';
				if (!empty($pars["K2"]))
					$resKF .= '"keyword2":{"value":"' . $pars["K2"]["N"] . '","color":"' . $pars["K2"]["C"] . '"},';
				if (!empty($pars["K3"]))
					$resKF .= '"keyword3":{"value":"' . $pars["K3"]["N"] . '","color":"' . $pars["K3"]["C"] . '"},';
				if (!empty($pars["K4"]))
					$resKF .= '"keyword4":{"value":"' . $pars["K4"]["N"] . '","color":"' . $pars["K4"]["C"] . '"},';
				if (!empty($pars["K5"]))
					$resKF .= '"keyword5":{"value":"' . $pars["K5"]["N"] . '","color":"' . $pars["K5"]["C"] . '"},';

				$resKF .= '"remark":{"value":"' . $pars["Remark"]["N"] . '","color":"' . $pars["Remark"]["C"] . '"}}
				}';
				break;
			default:
				break;
		}
		if (!empty($resKF)) {
			$token = $this->getToken();
			$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$token";
			$a = $this->http_request($url, $resKF, 0);
			return $a;
		} else
			return "";
	}

	//获得Token信息贵定乡村振兴 公众号
	function getToken($flag = false)
	{
		$tokenInfo = $this->getIni("", "xcxtoken.ini");
		$tim = $tokenInfo["tim"];
		$tim = is_numeric($tim) ? $tim : 0;
		$exp = $tokenInfo["exp"];
		$exp = is_numeric($exp) ? $exp : 0;
		$token = $tokenInfo["token"];
		$tm = $tim + $exp - 200;
		if (time() > $tm || $flag) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . wxAPPID . "&secret=" . wxAPPSECRET;
			$json = $this->http_json($url);
			if (!empty($json["errcode"])) {
				$this->upDateConfig("token", "");
				$this->upDateConfig("exp", 0, "int");
				$this->upDateConfig("tim", 0, "int");
				$this->upDateConfig("uptie", date("Y-m-d H:i:s", $tim));
				return "";
			} else {
				$token = $json["access_token"];
				$exp = $json["expires_in"];
				$tim = time();
				$this->upDateConfig("token", $token);
				$this->upDateConfig("exp", $exp, "int");
				$this->upDateConfig("tim", $tim, "int");
				$this->upDateConfig("uptie", date("Y-m-d H:i:s", $tim));
				return $token;
			}
		} else
			return $token;
	}


	//获得Token信息贵定乡村振兴 小程序
	function xcxToken($APPID, $APPSECRET, $flag = false)
	{
		$tokenInfo = $this->getIni("", "xcxtoken.ini");
		$tim = $tokenInfo["tim"];
		$tim = is_numeric($tim) ? $tim : 0;
		$exp = $tokenInfo["exp"];
		$exp = is_numeric($exp) ? $exp : 0;
		$token = $tokenInfo["token"];
		$tm = $tim + $exp - 200;
		if (time() > $tm || $flag) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $APPID . "&secret=" . $APPSECRET;
			$json = $this->http_json($url);
			if (!empty($json["errcode"])) {
				$this->upDateConfig("token", "", "string", "xcxtoken.ini");
				$this->upDateConfig("exp", 0, "int", "xcxtoken.ini");
				$this->upDateConfig("tim", 0, "int", "xcxtoken.ini");
				$this->upDateConfig("uptie", date("Y-m-d H:i:s", $tim), "string", "xcxtoken.ini");
				return "";
			} else {
				$token = $json["access_token"];
				$exp = $json["expires_in"];
				$tim = time();
				$this->upDateConfig("token", $token, "string", "xcxtoken.ini");
				$this->upDateConfig("exp", $exp, "int", "xcxtoken.ini");
				$this->upDateConfig("tim", $tim, "int", "xcxtoken.ini");
				$this->upDateConfig("uptie", date("Y-m-d H:i:s", $tim), "string", "xcxtoken.ini");
				return $token;
			}
		} else
			return $token;
	}

	//保持缩略图
	function makeThumb($filename = "", $savepath = "", $width = "300", $height = "300")
	{
		if (file_exists($filename)) {
			//上传图片的尺寸
			$imagesize = getimagesize($filename);
			$imagewidth = $imagesize[0];
			$imageheight = $imagesize[1];
			$mime = $imagesize['mime'];
			//宽高比例
			$ratio = $imagewidth / $imageheight;

			//新建一个背景图片
			$bgimg = imagecreatetruecolor($width, $height);
			$white = imagecolorallocate($bgimg, 255, 255, 255);
			//填充背景色为白色
			imagefill($bgimg, 0, 0, $white);
			if ($mime == 'image/gif') {
				$im = @imagecreatefromgif($filename); /* Attempt to open */
				$outfun = 'imagegif';
			} elseif ($mime == 'image/png') {
				$im = @imagecreatefrompng($filename); /* Attempt to open */
				$outfun = 'imagepng';
			} else {
				$im = @imagecreatefromjpeg($filename); /* Attempt to open */
				$outfun = 'imagejpeg';
			}

			if (!$im)
				$this->writeMsg("空图片：" . $mime . "\r\n文件名称：" . $filename);

			$copy = false;
			if ($ratio > 1) {
				//宽度较大   
				if ($imagewidth > $width) {
					//缩放图片到背景图片上             
					/*$new_width = $width;
					$new_height = ($width*$imageheight)/$imagewidth; 
					$bg_y = ceil(abs(($height-$new_height)/2));
					*/
					$new_height = $height;
					$new_width = ($height * $imagewidth) / $imageheight;
					$bg_x = ceil(($width - $new_width) / 2);//abs(
					imagecopyresampled($bgimg, $im, $bg_x, 0, 0, 0, $new_width, $new_height, $imagewidth, $imageheight);
				} else {
					//复制图片到背景图片上
					$copy = true;
				}
			} else {
				//高度较大
				if ($imageheight > $height) {
					//缩放图片
					/*
					$new_height = $height;
					$new_width = ($height*$imagewidth)/$imageheight;
					$bg_x = ceil(($width-$new_width)/2);
					*/
					$new_width = $width;
					$new_height = ($width * $imageheight) / $imagewidth;
					$bg_y = ceil(($height - $new_height) / 2);//abs(
					imagecopyresampled($bgimg, $im, 0, $bg_y, 0, 0, $new_width, $new_height, $imagewidth, $imageheight);
				} else {
					//复制图片到背景图片上
					$copy = true;
				}
			}
			if ($copy) {
				//复制图片到背景图片上    
				$bg_x = ceil(($width - $imagewidth) / 2);
				$bg_y = ceil(($height - $imageheight) / 2);
				imagecopy($bgimg, $im, $bg_x, $bg_y, 0, 0, $imagewidth, $imageheight);
			}
			imagepng($bgimg, ROOT . $savepath);
			imagedestroy($bgimg);
			return $savepath;
		} else {
			return false;
		}
	}


	function upFile($pic)
	{
		$picName = "";
		if (is_uploaded_file($pic["tmp_name"])) {
			$upfile = $pic;
			$name = $upfile["name"];//上传文件的文件名 
			$type = $upfile["type"];//上传文件的类型 
			$size = $upfile["size"];//上传文件的大小 
			$tmp_name = $upfile["tmp_name"];//上传文件的临时存放路径 
			$okType = false;
			switch ($type) {
				case 'image/pjpeg':
					$okType = true;
					break;
				case 'image/jpeg':
					$okType = true;
					break;
				case 'image/gif':
					$okType = true;
					break;
				case 'image/png':
					$okType = true;
					break;
				case 'image/png':
					$okType = true;
					break;
			}
			if ($okType) {
				$error = $upfile["error"];
				$pathUrl = "upimg/" . date("Ymd", time());
				if (file_exists(ROOT . $pathUrl) === false)
					mkdir(ROOT . $pathUrl, 0777, true);
				$fileName = date("His", time()) . "_" . rand(1111, 9999) . '.png';
				$imageSrc = $pathUrl . "/" . $fileName;  //图片名字
				move_uploaded_file($tmp_name, ROOT . $imageSrc);
				if ($error == 0) {
					//上传成功
					$upFlage = true;
					$picName = $imageSrc;
				} else {
					$upFlage = false;
					$picName = "";
					//失败
				}
			}
		}
		clearstatcache();
		return $picName;
	}

	function upvideo($pic)
	{
		$picName = "";
		if (is_uploaded_file($pic["tmp_name"])) {
			$upfile = $pic;
			$name = $upfile["name"];//上传文件的文件名 
			$type = $upfile["type"];//上传文件的类型 
			$size = $upfile["size"] / 1024 / 1024;//上传文件的大小 
			$tmp_name = $upfile["tmp_name"];//上传文件的临时存放路径 			
			if ($size < 30) {
				$error = $upfile["error"];
				$pathUrl = "upvideo/" . date("Ymd", time());
				if (file_exists(ROOT . $pathUrl) === false)
					mkdir(ROOT . $pathUrl, 0777, true);
				$fileName = date("His", time()) . "_" . rand(1111, 9999) . '.mp4';
				$imageSrc = $pathUrl . "/" . $fileName;  //图片名字
				move_uploaded_file($tmp_name, ROOT . $imageSrc);
				if ($error == 0) {
					//上传成功
					$upFlage = true;
					$picName = $imageSrc;
				} else {
					$upFlage = false;
					$picName = "";
					//失败
				}
			} else
				$picName = "视频太大";
		}
		clearstatcache();
		return $picName;
	}
	function getzgstate($s)
	{
		switch ($s) {
			case "0":
				return "待补充";
				break;
			case "1":
				return "待整改";
				break;
			case "2":
				return "待验收";
				break;
			case "3":
				return "已处理";
				break;
		}

	}
	function getIdentity($i)
	{
		switch ($i) {
			case "0":
				return "普通";
				break;
			case "1":
				return "主管";
				break;
			case "2":
				return "管理员";
				break;
		}

	}
	function getzhouqi($zhouqi)
	{
		switch ($zhouqi) {
			case '周一':
				$week = 1;
				break;
			case '周二':
				$week = 2;
				break;
			case '周三':
				$week = 3;
				break;
			case '周四':
				$week = 4;
				break;
			case '周五':
				$week = 5;
				break;
			case '周六':
				$week = 6;
				break;
			case '周日':
				$week = 7;
				break;
		}
		return $week;
	}
	function getTname($id)
	{
		$res = $this->getOne("hyTeam", "id='$id'");
		$row = $res["HyTeam"];
		return $row["tName"];

	}
}
?>