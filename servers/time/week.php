<?php 
	header('Content-Type:application/json; charset=utf-8');
	require_once("../data/db.php");
	
	//未巡检统计
	$results= $db->getAll("blueInspect","patrolCycle='每周'","addtime desc");
	foreach($results as $odres)
	{
		$row=$odres["BlueInspect"];	
		$dropId=$row["id"];
		$gId=$row["gId"];
		$dropClass=$row["dropClass"];
		$dropPhoto=$row["dropPhoto"];
		$tId=$row["tId"];
		$dropNo=$row["dropNo"];
		$dropName=$row["dropName"];
		$dropInfo=$row["dropInfo"];
		$patrolCycle=$row["patrolCycle"];
		$patrolNum=$row["patrolNum"];
		$patrolDiff=$row["patrolDiff"];
		$hyAppoint=$row["hyAppoint"];
		$hyAppointName=$row["hyAppointName"];
		$inspectNum=0;		
		$inspectTime=$row["inspectTime"];
		$inspectName=$row["inspectName"];
		$addTime=$row["addTime"];
		
				$startTime = date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d')-date('w')+1,date('y')));
				$endTime = date("Y-m-d H:i:s",mktime(23,59,59,date('m'),date('d')-date('w')+7,date('y')));
				if(date('w')==0)
				{
					$startTime = date('Y-m-d H:i:s',mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y')));
					$endTime = date('Y-m-d H:i:s',mktime(23, 59, 59, date('m'), date('d') - date('w') + 7 - 7, date('Y')));
				}
						
				if($addTime<$endTime)
				{
					$count = $db->getCount("blueOrder","dropId='$dropId' and addTime>='$startTime' and addTime<='$endTime'");
					$inspectNum=$patrolNum-$count;
					if($inspectNum>0)
					{
					$col="dropPhoto,gId,dropClass,tId,droId,dropNo,dropName,dropInfo,patrolCycle,patrolNum,patrolDiff,hyAppoint,hyAppointName,inspectNum,inspectTime,inspectName,startTime,endTime,addTime";
						$val="('$dropPhoto','$gId','$dropClass','$tId','$dropId','$dropNo','$dropName','$dropInfo','$patrolCycle','$patrolNum','$patrolDiff','$hyAppoint','$hyAppointName','$inspectNum','$inspectTime','$inspectName','$startTime','$endTime',NOW())";
						$db->addRecode("noCheck",$col,$val);
						
					}
				}
		}
?>