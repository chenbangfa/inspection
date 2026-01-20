<?php
require("db.php");
$tag = $db->getPar("tag");
$resV = '{"st":"-2","msg":"请求错误' . $tag . '","url":""}';
$url = "";
$msg = "";
switch ($tag) {
	case "sethyAppoint":
		$id = $db->getPar("id");
		$hyAppointName = $db->getPar("hyAppoint");
		$hyAppoint = $db->getPar("newAppointId");

		$db->editRecode("blueInspect", "hyAppoint='$hyAppoint',hyAppointName='$hyAppointName'", "id='$id'");
		$resV = '{"hyAppoint":"' . $hyAppoint . '","hyAppointName":"' . $hyAppointName . '"}';
		//$db->writeMsg("$resV");	
		break;
	case "getinspect":
		$hyid = $db->getPar("hyid");
		$tid = $db->getPar("tid");
		$odlist = array();
		$odres = $db->getOne("inspect", "hyId='$hyid'", "addTime desc");
		if (count($odres) > 0) {
			$row = $odres["Inspect"];
			$odlist[] = array("gsName" => $row["gsName"], "yhAdd" => $row["yhAdd"], "yhPosition" => $row["yhPosition"], "together" => $row["together"]);
		}
		$zylist = array();
		$prores = $db->getAll("yhzyGroup", "tId='$tid'", "gSort asc");
		if (count($prores) > 0) {
			foreach ($prores as $res) {
				$prorow = $res["YhzyGroup"];
				$zylist[] = array("gName" => $prorow["gName"], "id" => $prorow["id"]);
			}
		} else
			$zylist[] = array("gName" => "综合", "id" => 0);

		$people = array();
		$peopleres = $db->getAll("hyUser", "tId='$tid'", "hyIdentity desc,addTime asc");
		foreach ($peopleres as $res) {
			$prorow = $res["HyUser"];
			$people[] = array("hyName" => $prorow["hyName"], "id" => $prorow["id"], "ischeck" => false);
		}
		$getodlist = array("odlist" => $odlist, "zylist" => $zylist, "people" => $people);
		$resV = json_encode($getodlist);
		//$db->writeMsg("$resV");	
		break;
	case "getblueorderinfo":
		$id = $db->getPar("id");
		$odlist = array();

		$results = $db->getOne("blueOrder", "id='$id'");
		$row = $results["BlueOrder"];

		$odlist[] = array("id" => $row["id"], "issfz" => $row["issfz"], "sfzzmimg" => $row["sfzzmimg"], "sfzfmimg" => $row["sfzfmimg"], "yyzzimg" => $row["yyzzimg"], "dropNo" => $row["dropNo"], "dropName" => $row["dropName"], "odInfo" => $row["odInfo"], "odtogether" => $row["odtogether"], "odPhoto" => $row["odPhoto"], "odState" => $row["odState"], "addTime" => $row["addTime"], "hyname" => $row["hyName"]);

		$imgs = array();
		$odPhoto = $row["odPhoto"];
		if ($odPhoto != "" && $odPhoto != "|") {
			$odPhoto = substr($odPhoto, 0, -1);
			$imgs = explode('|', $odPhoto);
		}

		$prolist = array();
		$prores = $db->getAll("orderPro", "oId='$id'", "addTime asc");
		foreach ($prores as $res) {
			$prorow = $res["OrderPro"];
			$prolist[] = array("proName" => $prorow["proName"], "proState" => $prorow["proState"], "proInfo" => $prorow["proInfo"]);
		}
		$getodlist = array("odlist" => $odlist, "prolist" => $prolist, "imgs" => $imgs);
		$resV = json_encode($getodlist);
		break;
	case "setbluebind":
		$id = $db->getPar("id");
		$bName = $db->getPar("bName");
		$db->editRecode("blueInspect", "deviceId=''", "deviceId='$bName'");
		$db->editRecode("blueInspect", "deviceId='$bName'", "id='$id'");
		$resV = '{"st":"1"}';
		break;
	case "getbluestate":
		$bName = $db->getPar("bName");
		$count = $db->getCount("blueInspect", "deviceId='$bName'");
		$resV = '{"bstate":"' . $count . '"}';
		break;
	case "getblueone":
		$tid = $db->getPar("tid");
		$hyid = $db->getPar("hyid");
		$bName = $db->getPar("bName");
		$odlist = "";
		// and FIND_IN_SET(hyAppoint,'$hyid')
		$results = $db->getOne("blueInspect", "deviceId='$bName' and tId='$tid'", "addtime desc");
		if (count($results) > 0) {
			$row = $results["BlueInspect"];
			$hypic = $row["dropPhoto"];
			if ($hypic != "" && $hypic != '|') {
				$hypic = substr($hypic, 0, -1);
				$hypic = explode('|', $hypic);
				$hypic = $hypic[0];
			} else
				$hypic = "image/nopic.png";
			//$hyAppointName=$row["hyAppointName"];
			//$hyAppointName = explode('|',$hyAppointName);,"hyAppointName"=>$hyAppointName

			$xjcount = $row["inspectNum"];
			$addTime = $row["inspectTime"] == null ? "" : $row["inspectTime"];
			$hyName = $row["inspectName"];

			$odlist[] = array("id" => $row["id"], "deviceId" => "", "dropName" => $row["dropName"], "dropNo" => $row["dropNo"], "dropClass" => $row["dropClass"], "patrolCycle" => $row["patrolCycle"], "patrolNum" => $row["patrolNum"], "patrolDiff" => $row["patrolDiff"], "dropPhoto" => $hypic, "addTime" => $addTime, "hyName" => $hyName, "xjcount" => $xjcount);
		}
		$getodlist = array("odlist" => $odlist);
		$resV = json_encode($getodlist);
		//$db->writeMsg("$resV");	
		break;
	case "blueinspectedit":
		$id = $db->getPar("id");
		$tid = $db->getPar("tid");
		$hyid = $db->getPar("hyid");
		$dropNo = $db->getPar("dropNo");
		$imgs = $db->getPar("dropPhoto");
		$dropName = $db->getPar("dropName");
		$groupid = $db->getPar("groupid");
		$gName = $db->getPar("gName");
		$dropInfo = $db->getPar("dropInfo");
		$patrolCycle = $db->getPar("patrolCycle");
		$patrolNum = $db->getPar("patrolNum");
		$patrolDiff = $db->getPar("patrolDiff");
		$bluePro = $db->getPar("bluePro");
		$issfz = $db->getPar("issfz");
		$isxjfs = $db->getPar("isxjfs");
		$isaddress = $db->getPar("isaddress");
		$isphoto = $db->getPar("isphoto");
		if ($groupid == 0) {
			$tab = "blueGroup";
			$col = "tId,gName,addTime";
			$val = "('$tid','$gName',NOW())";
			$groupid = $db->addRecode($tab, $col, $val);
		}

		$dropPhoto = '';
		if ($imgs != "") {
			$imgs = str_replace('[', '', $imgs);
			$imgs = str_replace(']', '', $imgs);
			$imgs = explode(",", $imgs);
			foreach ($imgs as $imgv) {
				$dropPhoto .= $imgv . "|";
			}
			$dropPhoto = str_replace('"', '', $dropPhoto);
		}

		$tab = "blueInspect";
		$col = "issfz='$issfz',isxjfs='$isxjfs',isaddress='$isaddress',isphoto='$isphoto',tId='$tid',gId='$groupid',hyId='$hyid',dropNo='$dropNo',dropPhoto='$dropPhoto',dropName='$dropName',dropClass='$gName',dropInfo='$dropInfo',patrolCycle='$patrolCycle',patrolNum='$patrolNum',patrolDiff='$patrolDiff'";
		$val = "id='$id'";
		$blueid = $db->editRecode($tab, $col, $val);

		$db->deleteRecode("bluePro", "iId='$id'");

		$arr = json_decode($bluePro, true);
		foreach ($arr as $row) {
			$proName = $row['proName'];
			$proSort = $row['proSort'];
			$tab = "bluePro";
			$col = "iId,proName,proSort,addTime";
			$val = "('$id','$proName','$proSort',NOW())";
			$groupid = $db->addRecode($tab, $col, $val);
		}
		$resV = '{"st":"1"}';
		break;
	case "getblueinspect":
		$id = $db->getPar("id");
		$qdst = $db->getOne("blueInspect", "id='$id'");
		$blueinfo = array();
		if (count($qdst) > 0) {
			$row = $qdst["BlueInspect"];
			$tid = $row["tId"];

			$dropId = $row["id"];
			$start = date('Y-m-d 00:00:00');
			$patrolCycle = $row["patrolCycle"];
			$patrolNum = $row["patrolNum"];
			$patrolDiff = $row["patrolDiff"];

			$state = 0;//0可以巡检，1未到时间 2已完成巡检
			switch ($patrolCycle) {
				case '每天':
					$count = $db->getCount("blueOrder", "dropId='$dropId' and addTime>='$start'");
					if ($count > 0) {
						$jrxj = $patrolNum - $count;//该点剩余巡检次数
						if ($jrxj > 0) {
							$inspectTime = $row["inspectTime"];//最后一次巡检时间
							$nowtime = date("Y-m-d H:i:s");//当前时间
							$time = $db->diffTime($inspectTime, $nowtime, 4);
							if ($time < $patrolDiff) {
								$state = 1;
							}
						} else {
							$state = 2;
						}
					}
					break;
				case '每周':
					$startTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') - date('w') + 1, date('y')));
					$overTime = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('d') - date('w') + 7, date('y')));
					if (date('w') == 0) {
						$startTime = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y')));
						$overTime = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - 7, date('Y')));
					}
					$count = $db->getCount("blueOrder", "dropId='$dropId' and addTime>='$startTime' and addTime<='$overTime'");
					if ($count > 0) {
						$bzxj = $patrolNum - $count;
						if ($bzxj > 0) {
							$inspectTime = $row["inspectTime"];//最后一次巡检时间
							$nowtime = date("Y-m-d H:i:s");//当前时间
							$time = $db->diffTime($inspectTime, $nowtime, 3);
							if ($time < $patrolDiff) {
								$state = 1;
							}
						} else {
							$state = 2;
						}
					}
					break;
				case '每月':
					$startTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), 1, date('Y')));
					$overTime = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('t'), date('Y')));
					$count = $db->getCount("blueOrder", "dropId='$dropId' and addTime>='$startTime' and addTime<='$overTime'");
					if ($count > 0) {
						$byxj = $patrolNum - $count;
						if ($byxj > 0) {
							$inspectTime = $row["inspectTime"];//最后一次巡检时间
							$nowtime = date("Y-m-d H:i:s");//当前时间
							$time = $db->diffTime($inspectTime, $nowtime, 3);
							if ($time < $patrolDiff) {
								$state = 1;
							}
						} else {
							$state = 2;
						}
					}
					break;
				case '每季':
					//获取当前季度
					$season = ceil((date('m')) / 3);
					$starTime = mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y'));
					$overTime = mktime(23, 59, 59, $season * 3, date('t', mktime(0, 0, 0, $season * 3, 1, date("Y"))), date('Y'));
					$startTime = date("Y-m-d H:i:s", $starTime);
					$overTime = date("Y-m-d H:i:s", $overTime);
					$count = $db->getCount("blueOrder", "dropId='$dropId' and addTime>='$startTime' and addTime<='$overTime'");
					if ($count > 0) {
						$bjxj = $patrolNum - $count;
						if ($bjxj > 0) {
							$inspectTime = $row["inspectTime"];//最后一次巡检时间
							$nowtime = date("Y-m-d H:i:s");//当前时间
							$time = $db->diffTime($inspectTime, $nowtime, 3);
							if ($time < $patrolDiff) {
								$state = 1;
							}
						} else {
							$state = 2;
						}
					}
					break;
				case '每年':
					$startTime = date("Y-m-d 00:00:00", strtotime(date("Y", time()) . "-1" . "-1"));
					$overTime = date("Y-m-d 23:59:59", strtotime(date("Y", time()) . "-12" . "-31"));
					$count = $db->getCount("blueOrder", "dropId='$dropId' and addTime>='$startTime' and addTime<='$overTime'");
					if ($count > 0) {
						$bnxj = $patrolNum - $count;
						if ($bnxj > 0) {
							$inspectTime = $row["inspectTime"];//最后一次巡检时间
							$nowtime = date("Y-m-d H:i:s");//当前时间
							$time = $db->diffTime($inspectTime, $nowtime, 3);
							if ($time < $patrolDiff) {
								$state = 1;
							}
						} else {
							$state = 2;
						}
					}
					break;
			}


			$imgs = array();
			$dropPhoto = $row["dropPhoto"];
			if ($dropPhoto != "" && $dropPhoto != '|') {
				$dropPhoto = substr($dropPhoto, 0, -1);
				$dropPhoto = explode('|', $dropPhoto);
				$dropPhoto = $dropPhoto[0];
			} else
				$dropPhoto = "image/nopic.png";

			$xjcount = $row["inspectNum"];
			$addTime = $row["inspectTime"] == null ? "" : $row["inspectTime"];
			$hyName = $row["inspectName"];

			$prolist = array();
			$prores = $db->getAll("bluePro", "iId='$id'", "proSort asc");
			foreach ($prores as $res) {
				$prorow = $res["BluePro"];
				$prolist[] = array("id" => $prorow["id"], "proName" => $prorow["proName"], "normal" => "cshspan", "abnormal" => "cshspan", "msg" => "", "state" => "0");
			}
			$blueinfo[] = array("state" => $state, "issfz" => $row["issfz"], "isxjfs" => $row["isxjfs"], "isaddress" => $row["isaddress"], "isphoto" => $row["isphoto"], "hyAppoint" => $row["hyAppoint"], "hyAppointName" => $row["hyAppointName"], "dropNo" => $row["dropNo"], "dropName" => $row["dropName"], "gId" => $row["gId"], "dropClass" => $row["dropClass"], "dropInfo" => $row["dropInfo"], "patrolCycle" => $row["patrolCycle"], "patrolNum" => $row["patrolNum"], "patrolDiff" => $row["patrolDiff"], "addTime" => $addTime, "hyName" => $hyName, "xjcount" => $xjcount, "dropPhoto" => $dropPhoto, "prolist" => $prolist);
		}
		$blueinfo = array("blueinfo" => $blueinfo);
		$resV = json_encode($blueinfo);
		break;
	case "getblueinspectinfo":
		$id = $db->getPar("id");
		$tid = $db->getPar("tid");
		$qdst = $db->getOne("blueInspect", "id='$id'");
		$blueinfo = array();
		if (count($qdst) > 0) {
			$row = $qdst["BlueInspect"];
			$tid = $row["tId"];

			$imgs = array();
			$dropPhoto = $row["dropPhoto"];
			if ($dropPhoto != "" && $dropPhoto != '|') {
				$dropPhoto = substr($dropPhoto, 0, -1);
				$dropPhoto = explode('|', $dropPhoto);
				$dropPhoto = $dropPhoto[0];
			} else
				$dropPhoto = "image/nopic.png";


			$timelist = array();
			$start = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
			$end = date("Y-m-d", mktime(23, 59, 59, date("m"), date("t"), date("Y")));
			$diffDays = $db->diffDays($start, $end);
			for ($me = 0; $me <= $diffDays; $me++) {
				$stime = date("Y-m-d 00:00:00", (strtotime($start) + 86400 * $me));
				$etime = date("Y-m-d 23:59:59", (strtotime($start) + 86400 * $me));

				$xjnum = $db->getCount("blueOrder", "dropId='$id' and addTime>='$stime' and addTime<='$etime'");
				$ycnum = $db->getCount("blueOrder", "dropId='$id' and addTime>='$stime' and addTime<='$etime' and odState=1");
				$wjnum = $db->getCount("noCheck", "droId='$id' and startTime>='$stime' and endTime<='$etime'");

				$month = date("m", (strtotime($start) + 86400 * $me));
				$day = date("d", (strtotime($start) + 86400 * $me));
				$timelist[] = array("month" => $month, "day" => $day, "xjnum" => $xjnum, "ycnum" => $ycnum, "wjnum" => $wjnum);

			}


			$prolist = array();
			$prores = $db->getAll("bluePro", "iId='$id'", "proSort asc");
			foreach ($prores as $res) {
				$prorow = $res["BluePro"];
				$prolist[] = array("id" => $prorow["id"], "proName" => $prorow["proName"], "normal" => "cshspan", "abnormal" => "cshspan", "msg" => "", "state" => "0");
			}
			$xjcount = $row["inspectNum"];

			$people = array();
			$peopleres = $db->getAll("hyUser", "tId='$tid'", "hyIdentity desc,addTime asc");
			foreach ($peopleres as $res) {
				$prorow = $res["HyUser"];
				$people[] = array("hyName" => $prorow["hyName"], "id" => $prorow["id"]);
			}

			$blueinfo[] = array("deviceId" => $row["deviceId"], "hyAppoint" => $row["hyAppoint"], "hyAppointName" => $row["hyAppointName"], "dropNo" => $row["dropNo"], "dropName" => $row["dropName"], "gId" => $row["gId"], "dropClass" => $row["dropClass"], "dropInfo" => $row["dropInfo"], "patrolCycle" => $row["patrolCycle"], "patrolNum" => $row["patrolNum"], "patrolDiff" => $row["patrolDiff"], "xjcount" => $xjcount, "dropPhoto" => $dropPhoto, "prolist" => $prolist, "people" => $people);
		}
		$blueinfo = array("blueinfo" => $blueinfo, "timelist" => $timelist);
		$resV = json_encode($blueinfo);
		break;
	case "getstalledit":
		$id = $db->getPar("id");
		$qdst = $db->getOne("stallInspect", "id='$id'");
		$blueinfo = array();
		if (count($qdst) > 0) {
			$row = $qdst["StallInspect"];
			$tid = $row["tId"];

			$imgs = array();
			$dropPhoto = $row["dropPhoto"];
			if ($dropPhoto != "" && $dropPhoto != '|') {
				$dropPhoto = substr($dropPhoto, 0, -1);
				$imgs = explode('|', $dropPhoto);
			}

			$starttime = date("H:i", strtotime($row["starttime"]));
			$endtime = date("H:i", strtotime($row["endtime"]));
			$exittime = date("H:i", strtotime($row["exittime"]));

			$blueinfo[] = array("bmsfz" => $row["bmsfz"], "bmjygj" => $row["bmjygj"], "latitude" => $row["latitude"], "longitude" => $row["longitude"], "dropName" => $row["dropName"], "dropInfo" => $row["dropInfo"], "stallnum" => $row["stallnum"], "addname" => $row["addname"], "exitzhouqi" => $row["exitzhouqi"], "exitdate" => $row["exitdate"], "exittime" => $row["exittime"], "starttime" => $starttime, "endtime" => $endtime, "exittime" => $exittime, "dropPhoto" => $imgs);
		}
		$blueinfo = array("blueinfo" => $blueinfo);
		$resV = json_encode($blueinfo);
		break;
	case "getstallinfo":
		$id = $db->getPar("id");
		$tid = $db->getPar("tid");
		$qdst = $db->getOne("stallInspect", "id='$id'");
		$blueinfo = array();
		if (count($qdst) > 0) {
			$row = $qdst["StallInspect"];
			$tid = $row["tId"];

			$imgs = array();
			$dropPhoto = $row["dropPhoto"];
			if ($dropPhoto != "" && $dropPhoto != '|') {
				$dropPhoto = substr($dropPhoto, 0, -1);
				$dropPhoto = explode('|', $dropPhoto);
				$dropPhoto = $dropPhoto[0];
			} else
				$dropPhoto = "image/nopic.png";


			$timelist = array();
			$start = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
			$end = date("Y-m-d", mktime(23, 59, 59, date("m"), date("t"), date("Y")));
			$diffDays = $db->diffDays($start, $end);
			for ($me = 0; $me <= $diffDays; $me++) {
				$stime = date("Y-m-d 00:00:00", (strtotime($start) + 86400 * $me));
				$etime = date("Y-m-d 23:59:59", (strtotime($start) + 86400 * $me));

				$xjnum = $db->getCount("stallOrder", "stallId='$id' and addTime>='$stime' and addTime<='$etime'");

				$month = date("m", (strtotime($start) + 86400 * $me));
				$day = date("d", (strtotime($start) + 86400 * $me));
				$timelist[] = array("month" => $month, "day" => $day, "xjnum" => $xjnum, );

			}

			$starttime = date("H:i", strtotime($row["starttime"]));
			$endtime = date("H:i", strtotime($row["endtime"]));
			$exittime = date("H:i", strtotime($row["exittime"]));

			$blueinfo[] = array("dropName" => $row["dropName"], "dropInfo" => $row["dropInfo"], "stallnum" => $row["stallnum"], "addname" => $row["addname"], "exitzhouqi" => $row["exitzhouqi"], "exitdate" => $row["exitdate"], "exittime" => $row["exittime"], "starttime" => $starttime, "endtime" => $endtime, "exittime" => $exittime, "dropPhoto" => $dropPhoto);
		}
		$blueinfo = array("blueinfo" => $blueinfo, "timelist" => $timelist);
		$resV = json_encode($blueinfo);
		break;
	case "getblueinfo":
		$id = $db->getPar("id");
		$qdst = $db->getOne("blueInspect", "id='$id'");
		$row = $qdst["BlueInspect"];
		$tid = $row["tId"];

		$imgs = array();
		$dropPhoto = $row["dropPhoto"];
		if ($dropPhoto != "" && $dropPhoto != "|") {
			$dropPhoto = substr($dropPhoto, 0, -1);
			$imgs = explode('|', $dropPhoto);
		}

		$group[] = array("id" => 0, "gName" => "请选择分组");
		$results = $db->getAll("blueGroup", "tid='$tid'", "addtime desc");
		foreach ($results as $odres) {
			$Grouprow = $odres["BlueGroup"];
			$group[] = array("id" => $Grouprow["id"], "gName" => $Grouprow["gName"]);
		}

		$prolist = array();
		$prores = $db->getAll("bluePro", "iId='$id'", "proSort asc");
		foreach ($prores as $res) {
			$prorow = $res["BluePro"];
			$prolist[] = array("id" => $prorow["id"], "proName" => $prorow["proName"], "proSort" => $prorow["proSort"]);
		}


		$blueinfo[] = array("issfz" => $row["issfz"], "isxjfs" => $row["isxjfs"], "isaddress" => $row["isaddress"], "isphoto" => $row["isphoto"], "dropNo" => $row["dropNo"], "dropName" => $row["dropName"], "gId" => $row["gId"], "dropClass" => $row["dropClass"], "dropInfo" => $row["dropInfo"], "patrolCycle" => $row["patrolCycle"], "patrolNum" => $row["patrolNum"], "patrolDiff" => $row["patrolDiff"], "hyAppoint" => $row["hyAppoint"], "hyAppointName" => $row["hyAppointName"], "dropPhoto" => $imgs, "group" => $group, "prolist" => $prolist);
		$blueinfo = array("blueinfo" => $blueinfo);
		$resV = json_encode($blueinfo);
		break;
	case "getbluelist":
		$p = $db->getPar("p");
		$tid = $db->getPar("tid");
		$hyid = $db->getPar("hyid");
		$zqvalue = $db->getPar("zqvalue");
		$together = $db->getPar("together");
		$drores = $db->getPar("drores");
		$drostate = $db->getPar("drostate");
		$gname = $db->getPar("gname");
		$droname = $db->getPar("droname");
		$myblue = $db->getPar("myblue");
		$hyIdentity = $db->getPar("hyIdentity");
		// Fix for PHP 8: empty string >= 0 is false, logic requires it to be true if not provided (legacy behavior)
		if ($hyIdentity === "" || $hyIdentity >= 0)
			$whe = "tId='$tid'";
		else
			$whe = "1=2";
		if ($myblue != '' && $hyIdentity == 0)
			$whe .= " and FIND_IN_SET(hyAppoint,'$hyid')";
		if ($zqvalue != '')
			$whe .= " and patrolCycle='$zqvalue'";
		if ($together != '')
			$whe .= " and FIND_IN_SET(hyAppointName,'$together')";
		if ($drores != '')
			$whe .= " and drores='$drores'";
		if ($drostate != '')
			$whe .= " and drostate='$drostate'";
		if ($droname != '')
			$whe .= " and (dropName like '%$droname%' or dropNo='$droname')";
		if ($gname != '')
			$whe .= " and FIND_IN_SET(dropClass,'$gname')";

		$odlist = array();
		$group = array();

		list($cur, $co, $pg, $pe, $ne, $cu, $results) = $db->getList("blueInspect", " $whe", "addtime desc", "10");
		foreach ($results as $odres) {
			$row = $odres["BlueInspect"];
			$hypic = $row["dropPhoto"];
			if ($hypic != "" && $hypic != '|') {
				$hypic = substr($hypic, 0, -1);
				$hypic = explode('|', $hypic);
				$hypic = $hypic[0];
			} else
				$hypic = "image/nopic.png";

			$start = date('Y-m-d 00:00:00');
			$dropId = $row["id"];
			$patrolCycle = $row["patrolCycle"];
			$patrolNum = $row["patrolNum"];
			$patrolDiff = $row["patrolDiff"];

			$count = 0;//周期内巡检次数
			$xjtit = "";
			switch ($patrolCycle) {
				case '每天':
					$count = $db->getCount("blueOrder", "dropId='$dropId' and addTime>='$start'");
					if ($count == 0) {
						$xjtit = "已到巡检时间";
						$db->editRecode("blueInspect", "drostate=0,inspectNum=0", "id='$dropId'");
					} else {
						$jrxj = $patrolNum - $count;//该点剩余巡检次数
						if ($jrxj > 0) {
							$inspectTime = $row["inspectTime"];//最后一次巡检时间
							$nowtime = date("Y-m-d H:i:s");//当前时间
							$time = $db->diffTime($inspectTime, $nowtime, 4);
							if ($time >= $patrolDiff) {
								$xjtit = "已到巡检时间";
								$db->editRecode("blueInspect", "drostate=0", "id='$dropId'");
							} else {
								$xjtit = "未到巡检时间";
								$db->editRecode("blueInspect", "drostate=1", "id='$dropId'");
							}
						} else {
							$xjtit = "巡检已完成！";
							$db->editRecode("blueInspect", "drostate=2", "id='$dropId'");
						}
					}
					break;
				case '每周':
					$startTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') - date('w') + 1, date('y')));
					$overTime = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('d') - date('w') + 7, date('y')));
					if (date('w') == 0) {
						$startTime = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y')));
						$overTime = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - 7, date('Y')));
					}
					$count = $db->getCount("blueOrder", "dropId='$dropId' and addTime>='$startTime' and addTime<='$overTime'");
					if ($count == 0) {
						$xjtit = "已到巡检时间";
						$db->editRecode("blueInspect", "drostate=0,inspectNum=0", "id='$dropId'");
					} else {
						$bzxj = $patrolNum - $count;
						if ($bzxj > 0) {
							$inspectTime = $row["inspectTime"];//最后一次巡检时间
							$nowtime = date("Y-m-d H:i:s");//当前时间
							$time = $db->diffTime($inspectTime, $nowtime, 3);
							if ($time >= $patrolDiff) {
								$xjtit = "已到巡检时间";
								$db->editRecode("blueInspect", "drostate=0", "id='$dropId'");
							} else {
								$xjtit = "未到巡检时间";
								$db->editRecode("blueInspect", "drostate=1", "id='$dropId'");
							}
						} else {
							$xjtit = "巡检已完成！";
							$db->editRecode("blueInspect", "drostate=2", "id='$dropId'");
						}
					}
					break;
				case '每月':
					$startTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), 1, date('Y')));
					$overTime = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('t'), date('Y')));
					$count = $db->getCount("blueOrder", "dropId='$dropId' and addTime>='$startTime' and addTime<='$overTime'");
					if ($count == 0) {
						$xjtit = "已到巡检时间";
						$db->editRecode("blueInspect", "drostate=0,inspectNum=0", "id='$dropId'");
					} else {
						$byxj = $patrolNum - $count;
						if ($byxj > 0) {
							$inspectTime = $row["inspectTime"];//最后一次巡检时间
							$nowtime = date("Y-m-d H:i:s");//当前时间
							$time = $db->diffTime($inspectTime, $nowtime, 3);
							if ($time >= $patrolDiff) {
								$xjtit = "已到巡检时间";
								$db->editRecode("blueInspect", "drostate=0", "id='$dropId'");
							} else {
								$xjtit = "未到巡检时间";
								$db->editRecode("blueInspect", "drostate=1", "id='$dropId'");
							}
						} else {
							$xjtit = "巡检已完成！";
							$db->editRecode("blueInspect", "drostate=2", "id='$dropId'");
						}
					}
					break;
				case '每季':
					//获取当前季度
					$season = ceil((date('m')) / 3);
					$starTime = mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y'));
					$overTime = mktime(23, 59, 59, $season * 3, date('t', mktime(0, 0, 0, $season * 3, 1, date("Y"))), date('Y'));
					$startTime = date("Y-m-d H:i:s", $starTime);
					$overTime = date("Y-m-d H:i:s", $overTime);
					$count = $db->getCount("blueOrder", "dropId='$dropId' and addTime>='$startTime' and addTime<='$overTime'");
					if ($count == 0) {
						$xjtit = "已到巡检时间";
						$db->editRecode("blueInspect", "drostate=0,inspectNum=0", "id='$dropId'");
					} else {
						$bjxj = $patrolNum - $count;
						if ($bjxj > 0) {
							$inspectTime = $row["inspectTime"];//最后一次巡检时间
							$nowtime = date("Y-m-d H:i:s");//当前时间
							$time = $db->diffTime($inspectTime, $nowtime, 3);
							if ($time >= $patrolDiff) {
								$xjtit = "已到巡检时间";
								$db->editRecode("blueInspect", "drostate=0", "id='$dropId'");
							} else {
								$xjtit = "未到巡检时间";
								$db->editRecode("blueInspect", "drostate=1", "id='$dropId'");
							}
						} else {
							$xjtit = "巡检已完成！";
							$db->editRecode("blueInspect", "drostate=2", "id='$dropId'");
						}
					}
					break;
				case '每年':
					$startTime = date("Y-m-d 00:00:00", strtotime(date("Y", time()) . "-1" . "-1"));
					$overTime = date("Y-m-d 23:59:59", strtotime(date("Y", time()) . "-12" . "-31"));
					$count = $db->getCount("blueOrder", "dropId='$dropId' and addTime>='$startTime' and addTime<='$overTime'");
					if ($count == 0) {
						$xjtit = "已到巡检时间";
						$db->editRecode("blueInspect", "drostate=0,inspectNum=0", "id='$dropId'");
					} else {
						$bnxj = $patrolNum - $count;
						if ($bnxj > 0) {
							$inspectTime = $row["inspectTime"];//最后一次巡检时间
							$nowtime = date("Y-m-d H:i:s");//当前时间
							$time = $db->diffTime($inspectTime, $nowtime, 3);
							if ($time >= $patrolDiff) {
								$xjtit = "已到巡检时间";
								$db->editRecode("blueInspect", "drostate=0", "id='$dropId'");
							} else {
								$xjtit = "未到巡检时间";
								$db->editRecode("blueInspect", "drostate=1", "id='$dropId'");
							}
						} else {
							$xjtit = "巡检已完成！";
							$db->editRecode("blueInspect", "drostate=2", "id='$dropId'");
						}
					}
					break;
			}

			$xjcount = $row["inspectNum"];
			$addTime = $row["inspectTime"] == null ? "" : $row["inspectTime"];
			$hyName = $row["inspectName"];
			$odlist[] = array("id" => $row["id"], "xjcount" => $xjcount, "xjtit" => $xjtit, "dropName" => $row["dropName"], "dropNo" => $row["dropNo"], "dropClass" => $row["dropClass"], "patrolCycle" => $row["patrolCycle"], "patrolNum" => $row["patrolNum"], "patrolDiff" => $row["patrolDiff"], "deviceId" => $row["deviceId"], "dropPhoto" => $hypic, "addTime" => $addTime, "hyName" => $hyName, "hyAppointName" => $row["hyAppointName"]);
		}

		$results = $db->getAll("blueGroup", "tid='$tid'", "gSort asc");
		foreach ($results as $odres) {
			$row = $odres["BlueGroup"];
			$group[] = array("id" => $row["id"], "gName" => $row["gName"]);
		}
		$getodlist = array("odlist" => $odlist, "group" => $group, "count" => $co);
		$resV = json_encode($getodlist);
		break;
	case "getstalllist":
		$p = $db->getPar("p");
		$tid = $db->getPar("tid");
		$wxid = $db->getPar("wxid");
		$hyid = $db->getPar("hyid");
		$hyIdentity = $db->getPar("hyIdentity");

		$hy = $db->getCount("hyUser", "weId='$wxid' and tId='$tid'");
		if ($hy == 0) {
			$tab = "hyUser";
			$col = "tId,weId,hyPwd,hyState,hyIdentity,addTime";
			$val = "('$tid','$wxid','" . md5('000000') . "',1,-1,NOW())";
			$hyid = $db->addRecode($tab, $col, $val);
			$hyIdentity = -1;
		}
		$whe = "tId='$tid'";

		$odlist = array();
		$group = array();

		list($cur, $co, $pg, $pe, $ne, $cu, $results) = $db->getList("stallInspect", " $whe", "addtime desc", "10");
		foreach ($results as $odres) {
			$row = $odres["StallInspect"];
			$hypic = $row["dropPhoto"];
			if ($hypic != "" && $hypic != '|') {
				$hypic = substr($hypic, 0, -1);
				$hypic = explode('|', $hypic);
				$hypic = $hypic[0];
			} else
				$hypic = "image/nopic.png";

			$starttime = date("H:i", strtotime($row["starttime"]));
			$endtime = date("H:i", strtotime($row["endtime"]));
			$exittime = date("H:i", strtotime($row["exittime"]));

			$odlist[] = array("id" => $row["id"], "dropName" => $row["dropName"], "stallnum" => $row["stallnum"], "addname" => $row["addname"], "dropInfo" => $row["dropInfo"], "starttime" => $starttime, "endtime" => $endtime, "exitzhouqi" => $row["exitzhouqi"], "exitdate" => $row["exitdate"], "exittime" => $exittime, "dropInfo" => $row["dropInfo"], "dropPhoto" => $hypic);
		}
		$getodlist = array("odlist" => $odlist, "count" => $co, "hyid" => $hyid, "hyIdentity" => $hyIdentity);
		$resV = json_encode($getodlist);
		break;
	case "getstallrecord":
		$p = $db->getPar("p");
		$hyid = $db->getPar("hyid");
		$tid = $db->getPar("tid");
		$whe = "hyid='$hyid'";

		$odlist = array();

		list($cur, $co, $pg, $pe, $ne, $cu, $results) = $db->getList("stallOrder", " $whe", "addTime desc", "10");
		foreach ($results as $odres) {
			$rowod = $odres["StallOrder"];
			$stallid = $rowod["stallId"];
			$djtime = $rowod["addTime"];

			$res = $db->getOne("stallInspect", "id='$stallid'");
			$row = $res["StallInspect"];

			$hypic = $row["dropPhoto"];
			if ($hypic != "" && $hypic != '|') {
				$hypic = substr($hypic, 0, -1);
				$hypic = explode('|', $hypic);
				$hypic = $hypic[0];
			} else
				$hypic = "image/nopic.png";

			$starttime = date("H:i", strtotime($row["starttime"]));
			$endtime = date("H:i", strtotime($row["endtime"]));
			$exittime = date("H:i", strtotime($row["exittime"]));

			$odlist[] = array("id" => $row["id"], "djtime" => $djtime, "dropName" => $row["dropName"], "stallnum" => $row["stallnum"], "addname" => $row["addname"], "dropInfo" => $row["dropInfo"], "starttime" => $starttime, "endtime" => $endtime, "exitzhouqi" => $row["exitzhouqi"], "exitdate" => $row["exitdate"], "exittime" => $exittime, "dropInfo" => $row["dropInfo"], "dropPhoto" => $hypic);
		}
		$getodlist = array("odlist" => $odlist, "count" => $co);
		$resV = json_encode($getodlist);
		break;
	case "blueOrderadd":
		$id = $db->getPar("id");
		$tid = $db->getPar("tid");
		$weId = $db->getPar("wxid");
		$hyid = $db->getPar("hyid");
		$hyname = $db->getPar("hyname");
		$odInfo = $db->getPar("odInfo");
		$posAdd = $db->getPar("hyaddress");
		$odtogether = $db->getPar("odtogether");
		$imgs = $db->getPar("imgs");
		$bluePro = $db->getPar("bluePro");
		$dropNo = $db->getPar("dropNo");
		$dropName = $db->getPar("dropName");
		$gName = $db->getPar("gName");
		$gId = $db->getPar("gId");

		$issfz = $db->getPar("issfz");
		$yyzzimg = $db->getPar("imgurl");
		$daima = $db->getPar("daima");
		$faren = $db->getPar("faren");
		$gsname = $db->getPar("gsname");
		$gstypes = $db->getPar("gstypes");
		$business = $db->getPar("business");
		$zhucetime = $db->getPar("zhucetime");

		$sfzname = $db->getPar("sfzname");
		$sfznum = $db->getPar("sfznum");
		$address = $db->getPar("address");
		$gender = $db->getPar("gender");
		$sfzzm = $db->getPar("sfzzm");
		$sfzfm = $db->getPar("sfzfm");



		$odPhoto = '';
		if ($imgs != "") {
			$imgs = str_replace('[', '', $imgs);
			$imgs = str_replace(']', '', $imgs);
			$imgs = explode(",", $imgs);
			foreach ($imgs as $imgv) {
				$odPhoto .= $imgv . "|";
			}
			$odPhoto = str_replace('"', '', $odPhoto);
		}
		$tab = "blueOrder";
		$col = "sfzfmimg,sfzzmimg,gender,address,sfznum,sfzname,zhucetime,business,gstypes,gsname,faren,daima,yyzzimg,issfz,gId,gName,dropId,tId,hyId,weId,hyName,dropNo,odPhoto,dropName,odInfo,odtogether,posAdd,addTime";
		$val = "('$sfzfm','$sfzzm','$gender','$address','$sfznum','$sfzname','$zhucetime','$business','$gstypes','$gsname','$faren','$daima','$yyzzimg','$issfz','$gId','$gName','$id','$tid','$hyid','$weId','$hyname','$dropNo','$odPhoto','$dropName','$odInfo','$odtogether','$posAdd',NOW())";
		$oid = $db->addRecode($tab, $col, $val);
		$arr = json_decode($bluePro, true);
		$iszc = 0;
		foreach ($arr as $row) {
			$proName = $row['proName'];
			$state = intval($row['state']);
			$msg = $row['msg'];
			$iszc = ($iszc + $state);
			$tab = "orderPro";
			$col = "oId,proName,proState,proInfo,addTime";
			$val = "('$oid','$proName','$state','$msg',NOW())";
			$db->addRecode($tab, $col, $val);
		}
		$drores = 0;
		if ($iszc > 0) {
			$db->editRecode("blueOrder", "odState=1", "id='$oid'");
			$drores = 1;
		}
		$db->editRecode("blueInspect", "inspectNum=inspectNum+1,inspectTime=NOW(),inspectName='$hyname',drores='$drores',drostate='1'", "id='$id'");
		$resV = '{"st":"1"}';
		break;
	case "inspectset":
		$tid = $db->getPar("tid");
		$hyid = $db->getPar("hyid");
		$dropNo = $db->getPar("dropNo");
		$imgs = $db->getPar("dropPhoto");
		$dropName = $db->getPar("dropName");
		$groupid = $db->getPar("groupid");
		$gName = $db->getPar("gName");
		$dropInfo = $db->getPar("dropInfo");
		$patrolCycle = $db->getPar("patrolCycle");
		$patrolNum = $db->getPar("patrolNum");
		$patrolDiff = $db->getPar("patrolDiff");
		$bluePro = $db->getPar("bluePro");
		$hyIdentity = $db->getPar("hyIdentity");
		$hyname = $db->getPar("hyname");
		$newAppoint = $db->getPar("newAppoint");
		$newAppointId = $db->getPar("newAppointId");
		$issfz = $db->getPar("issfz");
		$isxjfs = $db->getPar("isxjfs");
		$isaddress = $db->getPar("isaddress");
		$isphoto = $db->getPar("isphoto");
		if ($groupid == 0) {
			$tab = "blueGroup";
			$col = "tId,gName,addTime";
			$val = "('$tid','$gName',NOW())";
			$groupid = $db->addRecode($tab, $col, $val);
		}

		$dropPhoto = '';
		if ($imgs != "") {
			$imgs = str_replace('[', '', $imgs);
			$imgs = str_replace(']', '', $imgs);
			$imgs = explode(",", $imgs);
			foreach ($imgs as $imgv) {
				$dropPhoto .= $imgv . "|";
			}
			$dropPhoto = str_replace('"', '', $dropPhoto);
		}

		$tab = "blueInspect";
		$col = "issfz,isxjfs,isaddress,isphoto,tId,gId,hyId,dropNo,dropPhoto,dropName,dropClass,dropInfo,patrolCycle,patrolNum,patrolDiff,hyAppoint,hyAppointName,addTime";
		$val = "('$issfz','$isxjfs','$isaddress','$isphoto','$tid','$groupid','$hyid','$dropNo','$dropPhoto','$dropName','$gName','$dropInfo','$patrolCycle','$patrolNum','$patrolDiff','$newAppointId','$newAppoint',NOW())";
		$blueid = $db->addRecode($tab, $col, $val);

		$arr = json_decode($bluePro, true);
		foreach ($arr as $row) {
			$proName = $row['proName'];
			$proSort = $row['proSort'];
			$tab = "bluePro";
			$col = "iId,proName,proSort,addTime";
			$val = "('$blueid','$proName','$proSort',NOW())";
			$groupid = $db->addRecode($tab, $col, $val);
		}
		$resV = '{"st":"1"}';
		break;
	case "blueinspectadd":
		$tid = $db->getPar("tid");
		$hyid = $db->getPar("hyid");
		$dropNo = $db->getPar("dropNo");
		$imgs = $db->getPar("dropPhoto");
		$dropName = $db->getPar("dropName");
		$groupid = $db->getPar("groupid");
		$gName = $db->getPar("gName");
		$dropInfo = $db->getPar("dropInfo");
		$patrolCycle = $db->getPar("patrolCycle");
		$patrolNum = $db->getPar("patrolNum");
		$patrolDiff = $db->getPar("patrolDiff");
		$bluePro = $db->getPar("bluePro");
		$newAppoint = $db->getPar("newAppoint");
		$newAppointId = $db->getPar("newAppointId");
		$issfz = $db->getPar("issfz");
		$isxjfs = $db->getPar("isxjfs");
		$isaddress = $db->getPar("isaddress");
		$isphoto = $db->getPar("isphoto");
		if ($groupid == 0) {
			$tab = "blueGroup";
			$col = "tId,gName,addTime";
			$val = "('$tid','$gName',NOW())";
			$groupid = $db->addRecode($tab, $col, $val);
		}

		$dropPhoto = '';
		if ($imgs != "") {
			$imgs = str_replace('[', '', $imgs);
			$imgs = str_replace(']', '', $imgs);
			$imgs = explode(",", $imgs);
			foreach ($imgs as $imgv) {
				$dropPhoto .= $imgv . "|";
			}
			$dropPhoto = str_replace('"', '', $dropPhoto);
		}

		$tab = "blueInspect";
		$col = "issfz,isxjfs,isaddress,isphoto,hyAppoint,hyAppointName,tId,gId,hyId,dropNo,dropPhoto,dropName,dropClass,dropInfo,patrolCycle,patrolNum,patrolDiff,addTime";
		$val = "('$issfz','$isxjfs','$isaddress','$isphoto','$newAppointId','$newAppoint','$tid','$groupid','$hyid','$dropNo','$dropPhoto','$dropName','$gName','$dropInfo','$patrolCycle','$patrolNum','$patrolDiff',NOW())";
		$blueid = $db->addRecode($tab, $col, $val);

		$arr = json_decode($bluePro, true);
		foreach ($arr as $row) {
			$proName = $row['proName'];
			$proSort = $row['proSort'];
			$tab = "bluePro";
			$col = "iId,proName,proSort,addTime";
			$val = "('$blueid','$proName','$proSort',NOW())";
			$groupid = $db->addRecode($tab, $col, $val);
		}
		$resV = '{"st":"1"}';
		break;
	case "stallinspectadd":

		$tid = $db->getPar("tid");
		$hyid = $db->getPar("hyid");
		$dropName = $db->getPar("dropName");
		$starttime = $db->getPar("starttime");
		$endtime = $db->getPar("endtime");
		$stallnum = $db->getPar("stallnum");

		$addname = $db->getPar("addname");
		$latitude = $db->getPar("latitude");
		$longitude = $db->getPar("longitude");
		$imgs = $db->getPar("dropPhoto");
		$dropInfo = $db->getPar("dropInfo");

		$exitzhouqi = $db->getPar("exitzhouqi");
		$exitdate = $db->getPar("exitdate");
		$exittime = $db->getPar("exittime");

		$bmsfz = $db->getPar("bmsfz");
		$bmjyhy = $db->getPar("bmjyhy");
		$bmjygj = $db->getPar("bmjygj");
		$bmtel = $db->getPar("bmtel");

		$patrolCycle = $db->getPar("patrolCycle");
		$patrolNum = $db->getPar("patrolNum");
		$patrolDiff = $db->getPar("patrolDiff");
		$bluePro = $db->getPar("bluePro");
		$newAppoint = $db->getPar("newAppoint");
		$newAppointId = $db->getPar("newAppointId");


		$dropPhoto = '';
		if ($imgs != "") {
			$imgs = str_replace('[', '', $imgs);
			$imgs = str_replace(']', '', $imgs);
			$imgs = explode(",", $imgs);
			foreach ($imgs as $imgv) {
				$dropPhoto .= $imgv . "|";
			}
			$dropPhoto = str_replace('"', '', $dropPhoto);
		}


		$tab = "stallInspect";
		$col = "tid,hyid,dropName,starttime,endtime,stallnum,addname,latitude,longitude,dropPhoto,dropInfo,exitzhouqi,exitdate,exittime,bmsfz,bmjyhy,bmjygj,bmtel,addTime";
		$val = "('$tid','$hyid','$dropName','$starttime','$endtime','$stallnum','$addname','$latitude','$longitude','$dropPhoto','$dropInfo','$exitzhouqi','$exitdate','$exittime','$bmsfz','$bmjyhy','$bmjygj','$bmtel',NOW())";
		$stallid = $db->addRecode($tab, $col, $val);


		$tab = "blueInspect";
		$col = "issfz,isxjfs,isaddress,isphoto,hyAppoint,hyAppointName,tId,gId,hyId,dropNo,dropPhoto,dropName,dropClass,dropInfo,patrolCycle,patrolNum,patrolDiff,addTime";
		$val = "('0','0','1','1','$newAppointId','$newAppoint','$tid','$stallid','$hyid','0','$dropPhoto','$dropName','流动摊','$dropInfo','$patrolCycle','$patrolNum','$patrolDiff',NOW())";
		$blueid = $db->addRecode($tab, $col, $val);

		$arr = json_decode($bluePro, true);
		foreach ($arr as $row) {
			$proName = $row['proName'];
			$proSort = $row['proSort'];
			$tab = "bluePro";
			$col = "iId,proName,proSort,addTime";
			$val = "('$blueid','$proName','$proSort',NOW())";
			$groupid = $db->addRecode($tab, $col, $val);
		}
		$resV = '{"st":"1"}';
		break;
	case "stallinspectsave":
		$id = $db->getPar("id");
		$dropName = $db->getPar("dropName");
		$starttime = $db->getPar("starttime");
		$endtime = $db->getPar("endtime");
		$stallnum = $db->getPar("stallnum");

		$addname = $db->getPar("addname");
		$latitude = $db->getPar("latitude");
		$longitude = $db->getPar("longitude");
		$imgs = $db->getPar("dropPhoto");
		$dropInfo = $db->getPar("dropInfo");

		$exitzhouqi = $db->getPar("exitzhouqi");
		$exitdate = $db->getPar("exitdate");
		$exittime = $db->getPar("exittime");

		$bmsfz = $db->getPar("bmsfz");
		$bmjyhy = $db->getPar("bmjyhy");
		$bmjygj = $db->getPar("bmjygj");
		$bmtel = $db->getPar("bmtel");


		$dropPhoto = '';
		if ($imgs != "") {
			$imgs = str_replace('[', '', $imgs);
			$imgs = str_replace(']', '', $imgs);
			$imgs = explode(",", $imgs);
			foreach ($imgs as $imgv) {
				$dropPhoto .= $imgv . "|";
			}
			$dropPhoto = str_replace('"', '', $dropPhoto);
		}


		$tab = "stallInspect";
		$col = "dropName='$dropName',starttime='$starttime',endtime='$endtime',stallnum='$stallnum',addname='$addname',latitude='$latitude',longitude='$longitude',dropPhoto='$dropPhoto',dropInfo='$dropInfo',exitzhouqi='$exitzhouqi',exitdate='$exitdate',exittime='$exittime',bmsfz='$bmsfz',bmjyhy='$bmjyhy',bmjygj='$bmjygj',bmtel='$bmtel'";
		$val = "id='$id'";
		$stallid = $db->editRecode($tab, $col, $val);

		$resV = '{"st":"1"}';
		break;
	case "gettimeout":
		$p = $db->getPar("p");
		$tid = $db->getPar("tid");
		$hyid = $db->getPar("hyid");
		$hyIdentity = $db->getPar("hyIdentity");

		$time = $db->getPar("time");
		$times = $db->getPar("times");
		$zysearch = $db->getPar("zysearch");
		$together = $db->getPar("together");

		$odlist = array();
		$whe = "tId='$tid'";
		if ($time != '' && $times != '')
			$whe .= " and startTime>='$time' and endTime<='$times'";
		if ($zysearch != '')
			$whe .= " and FIND_IN_SET(dropClass,'$zysearch')";
		if ($together != '')
			$whe .= " and FIND_IN_SET(hyAppointName,'$together')";
		if ($hyIdentity == '0')
			$whe .= " and FIND_IN_SET(hyAppoint,'$hyid')";

		list($cur, $co, $pg, $pe, $ne, $cu, $results) = $db->getList("noCheck", " $whe", "addtime desc", "10");
		foreach ($results as $odres) {
			$row = $odres["NoCheck"];
			$hypic = $row["dropPhoto"];
			if ($hypic != "") {
				$hypic = substr($hypic, 0, -1);
				$hypic = explode('|', $hypic);
				$hypic = $hypic[0];
			} else
				$hypic = "image/nopic.png";

			$startTime = date("m-d", strtotime($row["startTime"]));
			$endTime = date("m-d", strtotime($row["endTime"]));

			$odlist[] = array("id" => $row["id"], "startTime" => $startTime, "endTime" => $endTime, "inspectNum" => $row["inspectNum"], "dropName" => $row["dropName"], "droId" => $row["droId"], "hyAppointName" => $row["hyAppointName"], "dropNo" => $row["dropNo"], "dropClass" => $row["dropClass"], "patrolCycle" => $row["patrolCycle"], "patrolNum" => $row["patrolNum"], "patrolDiff" => $row["patrolDiff"], "hypic" => $hypic);
		}
		$getodlist = array("odlist" => $odlist, "count" => $co);
		$resV = json_encode($getodlist);
		//$db->writeMsg("$resV");	
		break;
	case "getblueorder":
		$p = $db->getPar("p");
		$tid = $db->getPar("tid");
		$hyid = $db->getPar("hyid");
		$hyIdentity = $db->getPar("hyIdentity");

		$time = $db->getPar("time");
		$times = $db->getPar("times");
		$zysearch = $db->getPar("zysearch");
		$together = $db->getPar("together");
		$states = $db->getPar("states");

		$odlist = array();
		$whe = "tId='$tid'";
		if ($time != '' && $times != '')
			$whe .= " and addTime>'$time' and addTime<'$times'";
		if ($zysearch != '')
			$whe .= " and FIND_IN_SET(gName,'$zysearch')";
		if ($together != '')
			$whe .= " and FIND_IN_SET(hyName,'$together')";
		if ($states != '')
			$whe .= " and odState='$states'";
		if ($hyIdentity == "0")
			$whe .= " and hyId='$hyid'";


		list($cur, $co, $pg, $pe, $ne, $cu, $results) = $db->getList("blueOrder", " $whe", "addtime desc", "10");
		foreach ($results as $odres) {
			$row = $odres["BlueOrder"];
			$hypic = $row["odPhoto"];
			if ($hypic != "") {
				$hypic = substr($hypic, 0, -1);
				$hypic = explode('|', $hypic);
				$hypic = $hypic[0];
			} else
				$hypic = "image/nopic.png";

			$odlist[] = array("id" => $row["id"], "hyName" => $row["hyName"], "dropName" => $row["dropName"], "posAdd" => $row["posAdd"], "odInfo" => $row["odInfo"], "odState" => $row["odState"], "addTime" => $row["addTime"], "hypic" => $hypic);
		}
		$getodlist = array("odlist" => $odlist, "count" => $co);
		$resV = json_encode($getodlist);
		//$db->writeMsg("$resV");	
		break;
	case "getGroup":
		$tid = $db->getPar("tid");
		$group[] = array("id" => 0, "gName" => "请选择分类", "gSort" => 0);
		$whe = "tId='$tid'";
		$results = $db->getAll("blueGroup", " $whe", "addtime desc");
		$count = count($results);
		foreach ($results as $odres) {
			$row = $odres["BlueGroup"];

			$bcount = $db->getCount("blueInspect", "gId='" . $row["id"] . "'");
			$group[] = array("id" => $row["id"], "gName" => $row["gName"], "gSort" => $row["gSort"], "counts" => $bcount);
		}
		$group = array("group" => $group, "count" => $count);
		$resV = json_encode($group);
		break;
	case "inspectsave":
		$id = $db->getPar("id");
		$zgAsk = $db->getPar("zgAsk");
		$zgTime = $db->getPar("zgTime");
		$tab = "inspect";
		$col = "zgAsk='$zgAsk',zgTime='$zgTime',zgState=1";
		$val = "id='$id'";
		$res = $db->editRecode($tab, $col, $val);
		$resV = '{"st":"1"}';
		break;
	case "inspectinfo":
		$id = $db->getPar("id");
		$qdst = $db->getOne("inspect", "id='$id'");
		$row = $qdst["Inspect"];
		$hypic = $row["yhPhoto"];
		if ($hypic != "") {
			$hypic = substr($hypic, 0, -1);
			$hypic = explode('|', $hypic);
		}

		$zgState = $db->getzgstate($row["zgState"]);
		$dtinfo[] = array("id" => $row["id"], "hypic" => $hypic, "hyname" => $row["hyName"], "gsName" => $row["gsName"], "yhAdd" => $row["yhAdd"], "yhPosition" => $row["yhPosition"], "yhContent" => $row["yhContent"], "yhSpeciality" => $row["yhSpeciality"], "together" => $row["together"], "posAdd" => $row["posAdd"], "inState" => ($row["inState"] ?? ""), "zgAsk" => $row["zgAsk"], "zgTime" => $row["zgTime"], "zgState" => $zgState, "State" => $row["zgState"], "addTime" => $row["addTime"], "zgUserId" => $row["zgUserId"], "zgName" => $row["zgName"], "zgPhoto" => $row["zgPhoto"], "zgInfo" => $row["zgInfo"]);
		$dtinfo = array("dtinfo" => $dtinfo);
		$resV = json_encode($dtinfo);
		break;
	case "inspectlist":
		$p = $db->getPar("p");
		$tid = $db->getPar("tid");
		$hyid = $db->getPar("hyid");

		$hyIdentity = $db->getPar("hyIdentity");

		$time = $db->getPar("time");
		$times = $db->getPar("times");
		$zysearch = $db->getPar("zysearch");
		$together = $db->getPar("together");
		$odlist = array();
		$whe = "tId='$tid'";
		if ($time != '' && $times != '')
			$whe .= " and addTime>'$time' and addTime<'$times'";
		if ($zysearch != '')
			$whe .= " and FIND_IN_SET(yhSpeciality,'$zysearch')";
		if ($together != '')
			$whe .= " and FIND_IN_SET(hyName,'$together')";
		$zgState = $db->getPar("zgState");
		if ($zgState != '') {
			$whe .= " and zgState='$zgState'";
		}
		//if($hyIdentity=='0')			
		//	$whe.=" and hyId='$hyid'";

		list($cur, $co, $pg, $pe, $ne, $cu, $results) = $db->getList("inspect", " $whe", "addtime desc", "10");
		foreach ($results as $odres) {
			$row = $odres["Inspect"];
			$hypic = $row["yhPhoto"];
			if ($hypic != "") {
				$hypic = substr($hypic, 0, -1);
				$hypic = explode('|', $hypic);
				$hypic = $hypic[0];
			} else
				$hypic = "image/nopic.png";
			$odlist[] = array("id" => $row["id"], "gsName" => $row["gsName"], "posAdd" => $row["posAdd"], "yhContent" => $row["yhContent"], "hyName" => $row["hyName"], "addTime" => $row["addTime"], "hypic" => $hypic, "count" => $co, "zgState" => $row["zgState"], "zgName" => $row["zgName"]);
		}
		$getodlist = array("odlist" => $odlist);
		$resV = json_encode($getodlist);
		break;
	case "mytasklist":
		$p = $db->getPar("p");
		$hyid = $db->getPar("hyid");
		$time = $db->getPar("time");
		$times = $db->getPar("times");
		$zgState = $db->getPar("zgState");

		$odlist = array();

		// Filter: Assigned to ME
		$whe = "zgUserId='$hyid'";

		if ($time != '' && $times != '')
			$whe .= " and addTime>'$time' and addTime<'$times'";

		if ($zgState != '') {
			$whe .= " and zgState='$zgState'";
		}

		list($cur, $co, $pg, $pe, $ne, $cu, $results) = $db->getList("inspect", " $whe", "zgTime asc, addTime desc", "10");

		foreach ($results as $odres) {
			$row = $odres["Inspect"];
			$hypic = $row["yhPhoto"];
			if ($hypic != "") {
				$hypic = substr($hypic, 0, -1);
				$hypic = explode('|', $hypic);
				$hypic = $hypic[0];
			} else
				$hypic = "image/nopic.png";

			$odlist[] = array(
				"id" => $row["id"],
				"gsName" => $row["gsName"],
				"posAdd" => $row["posAdd"],
				"yhContent" => $row["yhContent"],
				"hyName" => $row["hyName"],
				"addTime" => $row["addTime"],
				"hypic" => $hypic,
				"count" => $co,
				"zgState" => $row["zgState"],
				"zgName" => $row["zgName"],
				"zgAsk" => $row["zgAsk"],
				"zgTime" => $row["zgTime"],
				"address" => $row["yhPosition"],
				"dangerType" => $row["yhSpeciality"]
			);
		}
		$getodlist = array("odlist" => $odlist);
		$resV = json_encode($getodlist);
		break;
	case "gethylist":
		$tid = $db->getPar("tid");
		$hylist = array();
		$results = $db->getAll("hyUser", "tId='$tid'", "addtime desc");
		foreach ($results as $odres) {
			$row = $odres["HyUser"];
			$hylist[] = array("id" => $row["id"], "hyName" => $row["hyName"]);
		}
		$getodlist = array("hylist" => $hylist);
		$resV = json_encode($getodlist);
		break;
	case "getzylist":
		$tid = $db->getPar("tid");
		$zylist = array();
		$results = $db->getAll("yhzyGroup", "1=1", "gSort asc");
		foreach ($results as $odres) {
			$row = $odres["YhzyGroup"];
			$zylist[] = array("id" => $row["id"], "gName" => $row["gName"], "gSort" => $row["gSort"]);
		}
		$getodlist = array("zylist" => $zylist, "count" => count($results));
		$resV = json_encode($getodlist);
		break;
	case "getindex":
		$hyid = $db->getPar("hyid");
		$tid = $db->getPar("tid");
		$start = date('Y-m-d 00:00:00');
		$rcxj = $db->getCount("inspect", "tId='$tid' and hyId='$hyid' and addTime>='$start'");
		$zqxj = $db->getCount("blueOrder", "tId='$tid' and hyId='$hyid' and addTime>='$start'");
		$ycnum = $db->getCount("blueOrder", "tId='$tid' and hyId='$hyid' and addTime>='$start' and odState=1");

		//今日需要巡检点
		$jrxj = 0;
		$bzxj = 0;
		$byxj = 0;
		$bjxj = 0;
		$bnxj = 0;
		$drores = $db->getAll("blueInspect", "tId='$tid' and FIND_IN_SET(hyAppoint,'$hyid')");
		if (count($drores) > 0) {
			foreach ($drores as $res) {
				$row = $res["BlueInspect"];
				$dropId = $row["id"];
				$patrolCycle = $row["patrolCycle"];
				$patrolNum = $row["patrolNum"];
				$patrolDiff = $row["patrolDiff"];

				switch ($patrolCycle) {
					case '每天':
						$count = $db->getCount("blueOrder", "dropId='$dropId' and hyId='$hyid' and addTime>='$start'");
						$jrxj += $patrolNum - $count;//该点剩余巡检次数。	
						break;
					case '每周':
						$startTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d') - date('w') + 1, date('y')));
						$overTime = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('d') - date('w') + 7, date('y')));
						if (date('w') == 0) {
							$startTime = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y')));
							$overTime = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - 7, date('Y')));
						}
						$count = $db->getCount("blueOrder", "dropId='$dropId' and hyId='$hyid' and addTime>='$startTime' and addTime<='$overTime'");
						$bzxj += $patrolNum - $count;//该点剩余巡检次数。	
						break;
					case '每月':
						//本月起始时间日期格式
						$startTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), 1, date('Y')));
						//本月结束时间日期格式
						$overTime = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('t'), date('Y')));

						$count = $db->getCount("blueOrder", "dropId='$dropId' and hyId='$hyid' and addTime>='$startTime' and addTime<='$overTime'");
						$byxj += $patrolNum - $count;//该点剩余巡检次数。	
						break;
					case '每季':
						//获取当前季度
						$season = ceil((date('m')) / 3);
						//当前季度开始时间戳
						$starTime = mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y'));
						//获取当前季度结束时间戳
						$overTime = mktime(23, 59, 59, $season * 3, date('t', mktime(0, 0, 0, $season * 3, 1, date("Y"))), date('Y'));
						$startTime = date("Y-m-d H:i:s", $starTime);
						$overTime = date("Y-m-d H:i:s", $overTime);

						$count = $db->getCount("blueOrder", "dropId='$dropId' and hyId='$hyid' and addTime>='$startTime' and addTime<='$overTime'");
						$bjxj += $patrolNum - $count;//该点剩余巡检次数。	
						break;
					case '每年':
						//本年开始  
						$startTime = date("Y-m-d 00:00:00", strtotime(date("Y", time()) . "-1" . "-1"));
						//本年结束
						$overTime = date("Y-m-d 23:59:59", strtotime(date("Y", time()) . "-12" . "-31"));

						$count = $db->getCount("blueOrder", "dropId='$dropId' and hyId='$hyid' and addTime>='$startTime' and addTime<='$overTime'");
						$bnxj += $patrolNum - $count;//该点剩余巡检次数。	
						break;
				}
			}
		}
		$resV = '{"rcxj":"' . $rcxj . '","zqxj":"' . $zqxj . '","ycnum":"' . $ycnum . '","hyid":"' . $hyid . '","jrxj":"' . $jrxj . '","bzxj":"' . $bzxj . '","byxj":"' . $byxj . '","bjxj":"' . $bjxj . '","bnxj":"' . $bnxj . '"}';
		break;
	case "getbluecount":
		$hyid = $db->getPar("hyid");
		$tid = $db->getPar("tid");
		$hyIdentity = $db->getPar("hyIdentity");
		$time = $db->getPar("time");
		$times = $db->getPar("times");
		$zysearch = $db->getPar("zysearch");
		$together = $db->getPar("together");
		$droname = $db->getPar("droname");

		$timelist = array();
		$whe = "tId='$tid'";
		if ($zysearch != '')
			$whe .= " and FIND_IN_SET(gName,'$zysearch')";
		if ($together != '')
			$whe .= " and FIND_IN_SET(hyName,'$together')";
		if ($hyIdentity == '0')
			$whe .= " and hyId='$hyid'";
		if ($droname != '')
			$whe .= " and dropName like '%$droname%'";


		$nowhe = "tId='$tid'";
		if ($zysearch != '')
			$nowhe .= " and FIND_IN_SET(dropClass,'$zysearch')";
		if ($together != '')
			$nowhe .= " and FIND_IN_SET(hyAppointName,'$together')";
		if ($hyIdentity == '0')
			$nowhe .= " and FIND_IN_SET(hyAppoint,'$hyid')";
		if ($droname != '')
			$nowhe .= " and dropName like '%$droname%'";

		$diffDays = $db->diffDays($time, $times);

		$xjcount = 0;
		$yccount = 0;
		$wjcount = 0;
		for ($me = 0; $me <= $diffDays; $me++) {
			$stime = date("Y-m-d 00:00:00", (strtotime($time) + 86400 * $me));
			$etime = date("Y-m-d 23:59:59", (strtotime($time) + 86400 * $me));

			$xjnum = $db->getCount("blueOrder", $whe . " and addTime>='$stime' and addTime<='$etime'");
			$ycnum = $db->getCount("blueOrder", $whe . " and addTime>='$stime' and addTime<='$etime' and odState=1");
			$wjnum = $db->getCount("noCheck", $nowhe . " and startTime>='$stime' and endTime<='$etime'");

			$xjcount += $xjnum;
			$yccount += $ycnum;
			$wjcount += $wjnum;

			$month = date("m", (strtotime($time) + 86400 * $me));
			$day = date("d", (strtotime($time) + 86400 * $me));
			$timelist[] = array("month" => $month, "day" => $day, "xjnum" => $xjnum, "ycnum" => $ycnum, "wjnum" => $wjnum);

		}
		$getodlist = array("timelist" => $timelist, "xjcount" => $xjcount, "yccount" => $yccount, "wjcount" => $wjcount);
		$resV = json_encode($getodlist);
		break;
	case "setuser":
		$id = $db->getPar("id");
		$gId = $db->getPar("gId");
		$gName = $db->getPar("gName");
		$hyentity = $db->getPar("hyentity");
		$db->editRecode("hyUser", "gId='$gId',gName='$gName',hyIdentity='$hyentity'", "id='$id'");
		$resV = '{"st":"1"}';
		break;
	case "savebluegroup":
		$id = $db->getPar("id");
		$showname = $db->getPar("showname");
		$showsort = $db->getPar("showsort");
		$db->editRecode("blueGroup", "gName='$showname',gSort='$showsort'", "id='$id'");
		$resV = '{"st":"1"}';
		break;
	case "saveinspgroup":
		$id = $db->getPar("id");
		$showname = $db->getPar("showname");
		$showsort = $db->getPar("showsort");
		$db->editRecode("yhzyGroup", "gName='$showname',gSort='$showsort'", "id='$id'");
		$resV = '{"st":"1"}';
		break;
	case "addbluegroup":
		$tid = $db->getPar("tid");
		$addname = $db->getPar("addname");
		$addsort = $db->getPar("addsort");
		$res = $db->getCount("blueGroup", "tId='$tid' and gName='$addname'");
		if ($res == 0) {
			$db->addRecode("blueGroup", "tId,gName,gSort,addTime", "('$tid','$addname','$addsort',NOW())");
			$resV = '{"st":"1"}';
		} else
			$resV = '{"st":"0"}';
		break;
	case "addinspgroup":
		$tid = $db->getPar("tid");
		$addname = $db->getPar("addname");
		$addsort = $db->getPar("addsort");
		$res = $db->getCount("yhzyGroup", "tId='$tid' and gName='$addname'");
		if ($res == 0) {
			$db->addRecode("yhzyGroup", "tId,gName,gSort,addTime", "('$tid','$addname','$addsort',NOW())");
			$resV = '{"st":"1"}';
		} else
			$resV = '{"st":"0"}';
		break;
	case "teaminfo":
		$p = $db->getPar("p");
		$tid = $db->getPar("tid");
		$hylist = array();
		$group[] = array("groupid" => 0, "groupname" => "默认");
		$whe = "tId='$tid'";
		list($cur, $co, $pg, $pe, $ne, $cu, $results) = $db->getList("hyUser", " $whe", "hyIdentity desc", "10");
		foreach ($results as $odres) {
			$row = $odres["HyUser"];
			$identity = "普通";
			if ($row["hyIdentity"] == "1")
				$identity = "主管";
			else if ($row["hyIdentity"] == "2")
				$identity = "管理员";
			$hylist[] = array("id" => $row["id"], "hyName" => $row["hyName"], "hyIdentity" => $row["hyIdentity"], "identity" => $identity, "count" => $co, "gId" => $row["gId"], "gName" => $row["gName"]);
		}

		$results = $db->getAll("hyGroup", " $whe", "gSort asc");
		foreach ($results as $odres) {
			$row = $odres["HyGroup"];

			$group[] = array("groupid" => $row["id"], "groupname" => $row["gName"]);
		}
		$getodlist = array("hylist" => $hylist, "grouplist" => $group);
		$resV = json_encode($getodlist);
		break;
	case "stallreg":
		$hyid = $db->getPar("hyid");
		$stallid = $db->getPar("stallid");
		$tid = $db->getPar("tid");
		$hyId = $db->getPar("hyid");
		$wxid = $db->getPar("wxid");
		$hyName = $db->getPar("hyName");
		$hyTel = $db->getPar("hyTel");
		$stalljyhy = $db->getPar("stalljyhy");
		$stallgj = $db->getPar("stallgj");
		$issfz = $db->getPar("bmsfz");
		$yyzzimg = $db->getPar("yyzzimg");
		$sfzzmimg = $db->getPar("sfzzmimg");
		$sfzfmimg = $db->getPar("sfzfmimg");

		$tanweinum = $db->gettanweinum($stallid);
		if ($tanweinum > 0) {
			$tab = "stallOrder";
			$col = "issfz,tId,hyName,hyTel,hyId,weId,stallId,stalljyhy,stallgj,sfzzmimg,sfzfmimg,yyzzimg,addTime";
			$val = "('$issfz','$tid','$hyName','$hyTel','$hyId','$wxid','$stallid','$stalljyhy','$stallgj','$sfzzmimg','$sfzfmimg','$yyzzimg',NOW())";
			$hyid = $db->addRecode($tab, $col, $val);

			$db->editRecode("hyUser", "hyName='$hyName',hyTel='$hyTel'", "id='" . $hyId . "'");

			$resV = '{"st":"登记成功！"}';
		} else {
			$resV = '{"st":"摊位已被抢光了！"}';
		}
		break;
	case "jointeam":
		$tid = $db->getPar("tid");
		$weId = $db->getPar("weId");
		$tName = $db->getPar("tName");
		$hyName = $db->getPar("hyName");
		$hyTel = $db->getPar("hyTel");
		$hyPwd = $db->getPar("hyPwd");

		$types = $db->getPar("types");
		$faren = $db->getPar("faren");
		$gsname = $db->getPar("gsname");
		$address = $db->getPar("address");
		$gstypes = $db->getPar("gstypes");
		$business = $db->getPar("business");
		$zhucetime = $db->getPar("zhucetime");
		$imgurl = $db->getPar("imgurl");

		$myres = $db->getOne("hyUser", "hyTel='$hyTel' and tid='$tid'");
		if (count($myres) > 0) {
			$user = $myres["HyUser"];
			$resV = '{"st":"您已加入该团队！","tId":"' . $tid . '","hyid":"' . $user["id"] . '"}';
		} else {
			$tab = "hyUser";
			$col = "imgurl,zhucetime,business,gstypes,address,gsname,faren,types,tId,weId,hyName,hyTel,hyPwd,hyState,hyIdentity,addTime";
			$val = "('$imgurl','$zhucetime','$business','$gstypes','$address','$gsname','$faren','$types','$tid','$weId','$hyName','$hyTel','" . md5($hyPwd) . "',1,0,NOW())";
			$hyid = $db->addRecode($tab, $col, $val);

			$resV = '{"st":"加入成功！","tId":"' . $tid . '","hyid":"' . $hyid . '"}';
		}
		break;
	case "getteam":
		$tid = $db->getPar("tid");
		$teamres = $db->getOne("hyTeam", "id='$tid'");
		if (count($teamres) > 0) {
			$reamrow = $teamres["HyTeam"];
			$resV = '{"st":"1","tName":"' . $reamrow["tName"] . '"}';
		} else
			$resV = '{"st":"0"}';
		break;
	case "gettallid":
		$stallid = $db->getPar("stallid");
		$wxid = $db->getPar("wxid");
		$hyid = $db->getPar("hyid");
		$teamres = $db->getOne("stallInspect", "id='$stallid'");
		$blueinfo = array();
		if (count($teamres) > 0) {
			$row = $teamres["StallInspect"];
			$tid = $row["tId"];

			$hy = $db->getCount("hyUser", "weId='$wxid' and tId='$tid'");

			if ($hy == 0) {
				$tab = "hyUser";
				$col = "tId,weId,hyPwd,hyState,hyIdentity,addTime";
				$val = "('$tid','$wxid','" . md5('000000') . "',1,-1,NOW())";
				$hyid = $db->addRecode($tab, $col, $val);
			}
			$imgs = array();
			$dropPhoto = $row["dropPhoto"];
			if ($dropPhoto != "" && $dropPhoto != '|') {
				$dropPhoto = substr($dropPhoto, 0, -1);
				$dropPhoto = explode('|', $dropPhoto);
				$dropPhoto = $dropPhoto[0];
			} else
				$dropPhoto = "image/nopic.png";

			$starttime = date("H:i", strtotime($row["starttime"]));
			$endtime = date("H:i", strtotime($row["endtime"]));
			$exittime = date("H:i", strtotime($row["exittime"]));

			$blueinfo[] = array("longitude" => $row["longitude"], "latitude" => $row["latitude"], "bmsfz" => $row["bmsfz"], "bmjyhy" => $row["bmjyhy"], "bmjygj" => $row["bmjygj"], "bmtel" => $row["bmtel"], "dropName" => $row["dropName"], "dropInfo" => $row["dropInfo"], "stallnum" => $row["stallnum"], "addname" => $row["addname"], "exitzhouqi" => $row["exitzhouqi"], "exitdate" => $row["exitdate"], "exittime" => $row["exittime"], "starttime" => $starttime, "endtime" => $endtime, "exittime" => $exittime, "dropPhoto" => $dropPhoto);

			$stallnum = $row["stallnum"];
			$exitzhouqi = $row["exitzhouqi"];
			$exitdate = $row["exitdate"];
			$exittime = $row["exittime"];
			switch ($exitzhouqi) {
				case '每天':
					$startTime = date('Y-m-d ' . $exittime);
					$count = $db->getCount("stallOrder", "stallId='$stallid' and addTime>='$startTime'");
					$tanwei = $stallnum - $count;//剩余摊位
					break;
				case '每周':
					$w = date('w') == 0 ? 7 : date('w');
					$week = $db->getzhouqi($exitdate);//重置时间					
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
					$count = $db->getCount("stallOrder", "stallId='$stallid' and addTime>='$startTime'");
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
					$count = $db->getCount("stallOrder", "stallId='$stallid' and addTime>='$startTime'");
					$tanwei = $stallnum - $count;//剩余摊位
					break;
			}
		}
		$getodlist = array("blueinfo" => $blueinfo, "tanwei" => $tanwei, "tid" => $tid, "hyid" => $hyid, "hyIdentity" => "-1");
		$resV = json_encode($getodlist);

		break;
	case "login":
		$hyTel = $db->getPar("hyTel");
		$hyPwd = $db->getPar("hyPwd");
		$myres = $db->getOne("hyUser", "hyTel='$hyTel' and hyPwd='" . md5($hyPwd) . "'");
		if (count($myres) > 0) {
			$row = $myres["HyUser"];
			$resV = '{"st":"1","hyid":"' . $row["id"] . '","tid":"' . $row["tId"] . '"}';
		} else
			$resV = '{"st":"0","msg":"密码错误"}';
		break;
	case "welcome":
		$hytel = $db->getPar("hytel");
		$odlist = array();
		$results = $db->getAll("hyUser", "hyTel='$hytel'", "addtime desc");
		foreach ($results as $odres) {
			$row = $odres["HyUser"];
			$hyid = $row["id"];
			$tid = $row["tId"];
			$hycount = $db->getCount("hyUser", "tId='$tid'");

			$tres = $db->getOne("hyTeam", "id='$tid'");
			$trow = $tres["HyTeam"];
			$tname = $trow["tName"];

			$teamlist[] = array("tId" => $tid, "tName" => $tname, "hyid" => $hyid, "hycount" => $hycount);
		}
		$listarr = array("teamlist" => $teamlist);
		$resV = json_encode($listarr);
		break;
	case "inspectadd":
		$hyId = $db->getPar("hyid");
		$weId = $db->getPar("wxid");
		$tid = $db->getPar("tid");
		$hyname = $db->getPar("hyname");
		$posAdd = $db->getPar("posAdd");
		$gsName = $db->getPar("gsName");
		$yhAdd = $db->getPar("yhAdd");
		$yhPosition = $db->getPar("yhPosition");
		$yhContent = $db->getPar("yhContent");
		$imgs = $db->getPar("imgs");
		$yhSpeciality = $db->getPar("yhSpeciality");
		$together = $db->getPar("together");
		$yhPhoto = "";
		if ($imgs != "") {
			$imgs = str_replace('[', '', $imgs);
			$imgs = str_replace(']', '', $imgs);
			$imgs = explode(",", $imgs);
			foreach ($imgs as $imgv) {
				$yhPhoto .= $imgv . "|";
			}
			$yhPhoto = str_replace('"', '', $yhPhoto);
		}
		$tres = $db->getOne("hyTeam", "id='$tid'");
		$trow = $tres["HyTeam"];
		$tname = $trow["tName"];

		$tab = "inspect";
		$col = "tId,hyId,hyName,tName,weId,gsName,yhAdd,yhPosition,yhContent,yhSpeciality,together,yhPhoto,posAdd,addTime";
		$val = "('$tid','$hyId','$hyname','$tname','$weId','$gsName','$yhAdd','$yhPosition','$yhContent','$yhSpeciality','$together','$yhPhoto','$posAdd',NOW())";
		$tId = $db->addRecode($tab, $col, $val);
		$resV = '{"st":"1","msg":"恭喜您，保存成功"}';
		break;
	/* Duplicate case removed for PHP 8 compatibility
	case "getteam":
		$tid = $db->getPar("tid");
		$teamres = $db->getOne("hyTeam", "id='$tid'");
		$reamrow = $teamres["HyTeam"];
		$resV = '{"st":"1","tName":"' . $reamrow["tName"] . '"}';
		break;
	*/
	case "mycenter":
		$hyid = $db->getPar("hyid");
		$memarr = array();
		$memres = $db->getOne("hyUser", "id='$hyid'");
		if (!empty($memres) && isset($memres["HyUser"])) {
			$row = $memres["HyUser"];
			$tid = $row["tId"];

			$teamres = $db->getOne("hyTeam", "id='$tid'");
			$tName = "";
			if (!empty($teamres) && isset($teamres["HyTeam"])) {
				$reamrow = $teamres["HyTeam"];
				$tName = $reamrow["tName"];
			}

			$bluecount = $db->getCount("blueInspect", "tId='$tid' and hyid='$hyid'");
			$bluegroup = $db->getCount("blueGroup", "tId='$tid'");
			$memarr[] = array("id" => $row["id"], "hyName" => $row["hyName"], "hyIdentity" => $row["hyIdentity"], "tName" => $tName, "bluecount" => $bluecount, "bluegroup" => $bluegroup);
		} else {
			// Handle case where user is not found or empty
			$memarr[] = array("id" => 0, "hyName" => "", "hyIdentity" => "", "tName" => "", "bluecount" => 0, "bluegroup" => 0);
		}
		$getcenterarr = array("memarr" => $memarr);
		$resV = json_encode($getcenterarr);
		break;
	case "assign_rectification":
		$ids = $db->getPar("ids"); // Comma separated IDs
		$zgUserId = $db->getPar("zgUserId");
		$zgName = $db->getPar("zgName");
		$zgAsk = $db->getPar("zgAsk");
		$zgTime = $db->getPar("zgTime");

		if ($ids != "") {
			$idArray = explode(",", $ids);
			foreach ($idArray as $id) {
				if ($id != "") {
					$sql = "UPDATE inspect SET zgUserId='$zgUserId', zgName='$zgName', zgAsk='$zgAsk', zgTime='$zgTime', zgState=1 WHERE id='$id'";
					$db->execut($sql);
				}
			}
			$resV = '{"code":"200","msg":"分配成功"}';
		} else {
			$resV = '{"code":"400","msg":"未选择记录"}';
		}
		break;

	case "del":
		$tab = $db->getPar("t");
		$id = $db->getPar("i");
		$db->deleteRecode("$tab", "id='$id'");
		$resV = '{"st":"0","msg":"操作完成！"}';
		break;
	case "memberadd":
		$id = $db->getPar("hyid");
		$weId = $db->getPar("weId");
		$tName = $db->getPar("tName");
		$hyName = $db->getPar("hyName");
		$hyTel = $db->getPar("hyTel");
		$hyPwd = $db->getPar("hyPwd");
		$tab = "hyTeam";
		$col = "tName,weId,admName,admTel,addTime";
		$val = "('$tName','$weId','$hyName','$hyTel',NOW())";
		$tId = $db->addRecode($tab, $col, $val);

		$tab = "hyUser";
		$col = "tId,weId,hyName,hyTel,hyPwd,hyState,hyIdentity,addTime";
		$val = "('$tId','$weId','$hyName','$hyTel','" . md5($hyPwd) . "',1,2,NOW())";
		$hyid = $db->addRecode($tab, $col, $val);
		$resV = '{"st":"1","tId":"' . $tId . '","hyid":"' . $hyid . '"}';
		break;
	case "changeteam":
		$hyid = $db->getpar("hyid");
		$tid = $db->getpar("tid");
		$hy = $db->getOne("hyUser", "id='$hyid'", "logTime desc");
		if (count($hy) > 0) {
			$hy = $hy["HyUser"];
			$db->editRecode("hyUser", "logNum=logNum+1,logTime=NOW()", "id='" . $hy["id"] . "'");
			$resV = '{"weid":"' . $hy["weId"] . '","id":"' . $hy["id"] . '","tId":"' . $hy["tId"] . '","hyname":"' . $hy["hyName"] . '","hytel":"' . $hy["hyTel"] . '","hyIdentity":"' . $hy["hyIdentity"] . '"}';
		} else {
			$resV = '{"code":"1","weid":"' . $wxid . '","id":"0","tId":"0","hytel":"","hyIdentity":"0","hyname":""}';
		}
		break;
	case "getopenid":
		$code = $db->getpar("code");
		$APPID = APPID;
		$APPSECRET = APPSECRET;
		$url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $APPID . "&secret=" . $APPSECRET . "&js_code=" . $code . "&grant_type=authorization_code";
		$reqRes = $db->http_json($url);
		$wxid = $reqRes["openid"];
		$hy = $db->getOne("hyUser", "weId='$wxid'", "logTime desc");
		if (count($hy) > 0) {
			$hy = $hy["HyUser"];
			$db->editRecode("hyUser", "logNum=logNum+1,logTime=NOW()", "id='" . $hy["id"] . "'");
			$resV = '{"code":"1","weid":"' . $wxid . '","id":"' . $hy["id"] . '","tId":"' . $hy["tId"] . '","hyname":"' . $hy["hyName"] . '","hytel":"' . $hy["hyTel"] . '","hyIdentity":"' . $hy["hyIdentity"] . '"}';
		} else {
			$resV = '{"code":"1","weid":"' . $wxid . '","id":"0","tId":"0","hytel":"","hyIdentity":"0","hyname":""}';
		}
		break;
	default:
		break;
}
echo $resV;