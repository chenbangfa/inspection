<?php require_once("db.php");

$menu = '{
        "button": [
             
            {
               "type":"miniprogram",
			   "name":"乡村振兴",
			   "appid":"wxd53bea03ffb467cc",
			   "pagepath":"pages/index/index",
			   "url": "http://www.bnng.net/anquan/index.php"
            }
        ]
}';
$res = $db->createMenu($menu);
if($res==1) echo "Create Menu Successful!!";
else echo "Create Menu Failed!!";

/*{
              "type":"view",
               "name":"项目",
               "url":"http://www.bnng.net/gdfp/"
            }, 
            {
               "type":"view",
               "name":"监测",
               "url":"http://www.bnng.net/gdfpjd/index.php"
            },*/
?>
