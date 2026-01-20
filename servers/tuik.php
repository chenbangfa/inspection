<?php header('Content-Type:application/json; charset=utf-8');
require("data/db.php"); 

$weixin_post_string = $GLOBALS["HTTP_RAW_POST_DATA"];

$refund_xml_string = refund_decrypt($weixin_post_string, md5(KEY));

 libxml_disable_entity_loader(true);
    $xmlstring = simplexml_load_string($refund_xml_string, 'SimpleXMLElement', LIBXML_NOCDATA);
   
	
	$db->writeMsg("wxfh:".$xmlstring);

	$ar=object_to_array($xmlstring);
	$db->writeMsg("ar:".$ar["return_code"]);	
	$db->writeMsg("ar1:".$ar[0]);	
	
	foreach($xmlstring as $value){

$db->writeMsg("dddd:".$value);

}
		
echo exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>'); 
function refund_decrypt($str, $key)
{

$str = base64_decode($str);

$str = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_ECB);

$block = mcrypt_get_block_size('rijndael_128', 'ecb');

$pad = ord($str[($len = strlen($str)) - 1]);

$len = strlen($str);

$pad = ord($str[$len - 1]);

return substr($str, 0, strlen($str) - $pad);

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