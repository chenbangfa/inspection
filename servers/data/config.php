<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//将出错信息输出到一个文本文件
ini_set('error_log', $_SERVER["DOCUMENT_ROOT"] . '/error_log.txt');

#数据库设置
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'inspection');
define('DB_PSWD', 'B7bFh58sHmzJwiF4');
define('DB_NAME', 'inspection');
define("SUFFIX", "");//tp_
define("PREFIX", "");//s


#网站设置
define("EJYM", "");
define("HTTP", "http://" . $_SERVER["HTTP_HOST"] . "/" . EJYM . "/");
define("IP", $_SERVER["REMOTE_ADDR"]);
define("REFRESH", "3");
define("COOKEXP", time() + 24 * 60 * 60);
define("ROOT", $_SERVER["DOCUMENT_ROOT"] . "/" . EJYM . "/");
define("LOGURL", $_SERVER["DOCUMENT_ROOT"] . "/" . EJYM . "/data/logs/log.txt");
define("LOGINFO", $_SERVER["DOCUMENT_ROOT"] . "/" . EJYM . "/data/logs/info.txt");
define("URL", "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);



#小程序微信设置
define("APPID", "wxd53bea03ffb467cc");//小程序id
define("APPSECRET", "732a201b5d0e82015d213319dfd3b111");//小程序密钥


//公众号设置
define("wxAPPID", "wxbcfb77ae25ddf24d");//小程序id
define("wxAPPSECRET", "f5bfc27e6c33a58a4ba27a541a5b03d5");//小程序密钥


//模版消息
define("bsjdtz", "4j8Lt0kYlPKw-e2QJhqkRZSOm0_U8q2DSoOLk2Byab8");//办件进度通知



























