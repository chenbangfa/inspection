<?php 
header('Content-Type:application/json; charset=utf-8');
require("data/db.php");
$tag= $db->getpar("tag");
if($tag=="gettel")
{
	$code=$db->getpar("code");
	$token =$db->xcxToken(APPID,APPSECRET); // 取access_token 的值
	$api = 'https://api.weixin.qq.com/wxa/business/getuserphonenumber?access_token='.$token; // 获取小程序二维码post地址;	
	$post_data = '{"code": "'.$code.'"}'; // post 数据
	$resV =http_request($api,$post_data);		
	echo $resV;
	
}else if($tag=="getQRCode")
{
	$tid=$db->getpar("tid");
	$token =$db->xcxToken(APPID,APPSECRET);	
	$api="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$token;
	$post_data = '{"page": "pages/stallinfo/stallinfo","scene":"'.$tid.'","check_path":true,"env_version": "release"}';	
	$result = http_request($api,$post_data);

	$data = date('Ymd'); 
	$path ='Qrcode/'.$data;
	if(file_exists(ROOT.$path)===false)
		mkdir (ROOT.$path,0777,true);	
	$path = $path.'/'.$tid.'.png';
	
	$ifp = fopen($path, "w" );
	fwrite($ifp,$result);
	fclose($ifp );
	
	$tab="hyTeam";
	$col = "tCode='$path'";
	$val = "id='$tid'";
	$res = $db->editRecode($tab,$col,$val);	
	$resV = '{"code":"ok","path":"'.$path.'"}';	
	echo $resV;
}else if($tag=="gettallCode")
{
	$stallid=$db->getpar("stallid");
	$token =$db->xcxToken(APPID,APPSECRET);	
	$api="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$token;
	$post_data = '{"page": "pages/stallreg/stallreg","scene":"'.$stallid.'","check_path":true,"env_version": "release"}';	
	$result = http_request($api,$post_data);

	$data = date('Ymd'); 
	$path ='Qrcode/'.$data;
	if(file_exists(ROOT.$path)===false)
		mkdir (ROOT.$path,0777,true);	
	$path = $path.'/'.$stallid.'.png';
	
	$ifp = fopen($path, "w" );
	fwrite($ifp,$result);
	fclose($ifp );
	
	$tab="stallInspect";
	$col = "tCode='$path'";
	$val = "id='$stallid'";
	$res = $db->editRecode($tab,$col,$val);	
	$resV = '{"code":"ok","path":"'.$path.'"}';	
	echo $resV;
}else if($tag=="gettalllist")
{
	$tid=$db->getpar("tid");
	$token =$db->xcxToken(APPID,APPSECRET);	
	$api="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$token;
	$post_data = '{"page": "pages/stall/stall","scene":"'.$tid.'","check_path":true,"env_version": "release"}';	
	
	$db->writeMsg($post_data);
	$result = http_request($api,$post_data);

	$data = date('Ymd'); 
	$path ='Qrcode/'.$data;
	if(file_exists(ROOT.$path)===false)
		mkdir (ROOT.$path,0777,true);	
	$path = $path.'/'.$tid.'tw.png';
	
	$ifp = fopen($path, "w" );
	fwrite($ifp,$result);
	fclose($ifp );
	
	$resV = '{"code":"ok","path":"'.$path.'"}';	
	echo $resV;
}else if($tag=="bdcodeurl")
{
	$wxid=$db->getpar("wxid");//用户id
	$id=$db->getpar("hyid");;
	$token = $db->getToken(); // 取access_token 的值
	
	$data = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": '.$id.'}}}';//永久二维码10万个
	$curl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token; 
	$obj = http_request($curl,$data);
	$jsoninfo=json_decode($obj,true);	
	
	//$db->writeMsg("jsoninfo:".json_encode($jsoninfo));
	$api = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$jsoninfo["ticket"];
		
	$result = http_request($api);
	$data = date('Ymd'); 
	$path ='MpQrcode/'.$data; //ROOT_PATH 我使用的是TP5框架
	if(file_exists(ROOT.$path)===false)
		mkdir (ROOT.$path,0777,true);	
	$path = $path.'/'.$wxid.'.png'; //最后要写入的目录及文件名
	//  创建将数据流文件写入我们创建的文件内容中
	$ifp = fopen($path, "w" );
	fwrite($ifp,$result );
	fclose($ifp );
	
	$tab="hymember";
	$col = "qdqrcode='$path'";
	$val = "wxid='$wxid'";
	$res = $db->editRecode($tab,$col,$val);	
	$resV = '{"code":"ok","msg":"公众号二维码生成成功！","path":"'.$path.'"}';	
	echo $resV;
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

?>