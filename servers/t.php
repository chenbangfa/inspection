<?php 
	header('Content-Type:application/json; charset=utf-8');
	require_once("data/db.php");
	
	//未巡检统计
	$results = $db->getAll("blueInspect","1=1","addtime desc");
	foreach($results as $odres)
	{
		$row=$odres["BlueInspect"];	
		$dropId=$row["id"];
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
		switch($patrolCycle)
		{
			case '每天':
				echo $startTime = date('Y-m-d 00:00:00',strtotime('-1 day'));
				echo $endTime =  date('Y-m-d 23:59:59',strtotime('-1 day'));
				if($addTime<$endTime)
				{
					$count = $db->getCount("blueOrder","dropId='$dropId' and addTime>='$startTime' and addTime<='$endTime'");
					$inspectNum=$patrolNum-$count;
					if($inspectNum>0)
					{
						$col="tId,droId,dropNo,dropName,dropInfo,patrolCycle,patrolNum,patrolDiff,hyAppoint,hyAppointName,inspectNum,inspectTime,inspectName,startTime,endTime,addTime";
						$val="('$tId','$dropId','$dropNo','$dropName','$dropInfo','$patrolCycle','$patrolNum','$patrolDiff','$hyAppoint','$hyAppointName','$inspectNum','$inspectTime','$inspectName','$startTime','$endTime',NOW())";
						$db->addRecode("noCheck",$col,$val);
						
					}
				}
				break;
			case '每周':	
				echo $startTime =date('Y-m-d H:i:s', mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y')));
				echo $endTime =date('Y-m-d H:i:s', mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y')));
				if($addTime<$endTime)
				{
					$count = $db->getCount("blueOrder","dropId='$dropId' and addTime>='$startTime' and addTime<='$endTime'");
					$inspectNum=$patrolNum-$count;
					if($inspectNum>0)
					{
						$col="tId,droId,dropNo,dropName,dropInfo,patrolCycle,patrolNum,patrolDiff,hyAppoint,hyAppointName,inspectNum,inspectTime,inspectName,startTime,endTime,addTime";
						$val="('$tId','$dropId','$dropNo','$dropName','$dropInfo','$patrolCycle','$patrolNum','$patrolDiff','$hyAppoint','$hyAppointName','$inspectNum','$inspectTime','$inspectName','$startTime','$endTime',NOW())";
						$db->addRecode("noCheck",$col,$val);
						
					}
				}
				break;
			case '每月':					
				echo $startTime=date("Y-m-d H:i:s", mktime(0,0,0,date("m")-1,1,date("Y")));
				echo $endTime=date("Y-m-d H:i:s", mktime(23,59,59,date("m") ,0,date("Y")));
				if($addTime<$endTime)
				{
					$count = $db->getCount("blueOrder","dropId='$dropId' and addTime>='$startTime' and addTime<='$endTime'");
					$inspectNum=$patrolNum-$count;
					if($inspectNum>0)
					{
						$col="tId,droId,dropNo,dropName,dropInfo,patrolCycle,patrolNum,patrolDiff,hyAppoint,hyAppointName,inspectNum,inspectTime,inspectName,startTime,endTime,addTime";
						$val="('$tId','$dropId','$dropNo','$dropName','$dropInfo','$patrolCycle','$patrolNum','$patrolDiff','$hyAppoint','$hyAppointName','$inspectNum','$inspectTime','$inspectName','$startTime','$endTime',NOW())";
						$db->addRecode("noCheck",$col,$val);
						
					}
				}
				break;
			case '每季':	
				$season = ceil((date('n'))/3)-1;
				echo $startTime=date('Y-m-d H:i:s', mktime(0,0,0,$season*3-3+1,1,date('Y')));
				echo $endTime=date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0,0,0,$season*3,1,date("Y"))),date('Y')));
				if($addTime<$endTime)
				{
					$count = $db->getCount("blueOrder","dropId='$dropId' and addTime>='$startTime' and addTime<='$endTime'");
					$inspectNum=$patrolNum-$count;
					if($inspectNum>0)
					{
						$col="tId,droId,dropNo,dropName,dropInfo,patrolCycle,patrolNum,patrolDiff,hyAppoint,hyAppointName,inspectNum,inspectTime,inspectName,startTime,endTime,addTime";
						$val="('$tId','$dropId','$dropNo','$dropName','$dropInfo','$patrolCycle','$patrolNum','$patrolDiff','$hyAppoint','$hyAppointName','$inspectNum','$inspectTime','$inspectName','$startTime','$endTime',NOW())";
						$db->addRecode("noCheck",$col,$val);
						
					}
				}
				break;
			case '每年':	
				echo $startTime = date('Y-m-d H:i:s',mktime(0,0,0,1,1,date('Y',strtotime("-1 year"))));
            	echo $endTime = date('Y-m-d H:i:s',mktime(23,59,59,12,31,date('Y',strtotime("-1 year"))));
				if($addTime<$endTime)
				{
					$count = $db->getCount("blueOrder","dropId='$dropId' and addTime>='$startTime' and addTime<='$endTime'");
					$inspectNum=$patrolNum-$count;
					if($inspectNum>0)
					{
						$col="tId,droId,dropNo,dropName,dropInfo,patrolCycle,patrolNum,patrolDiff,hyAppoint,hyAppointName,inspectNum,inspectTime,inspectName,startTime,endTime,addTime";
						$val="('$tId','$dropId','$dropNo','$dropName','$dropInfo','$patrolCycle','$patrolNum','$patrolDiff','$hyAppoint','$hyAppointName','$inspectNum','$inspectTime','$inspectName','$startTime','$endTime',NOW())";
						$db->addRecode("noCheck",$col,$val);
						
					}
				}
				break;
			}		
		}
?>