<?php 
require("data/db.php");
$tag = $db->getPar("tag");

switch($tag)
{
	case "upimg":
		$tmpP = $_FILES["file"]["tmp_name"];
		if($tmpP)
		{
			$pic_path = $db->upFile($_FILES['file'],"files");
			//$thumb = $db->makeThumb(ROOT."".$pic_path,$pic_path."_240");
		}
		$resV = '{"st":"1","url":"'.$pic_path.'"}';
	break;
	case "upvideo":
	$tmpP = $_FILES["file"]["tmp_name"];
		if($tmpP)
		{
			$pic_path = $db->upvideo($_FILES['file'],"files");
		}
		$resV = '{"st":"1","url":"'.$pic_path.'"}';
	break;
	default:
	break;
}
echo $resV;
?>