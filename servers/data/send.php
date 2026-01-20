<?php header('Content-Type:application/json; charset=utf-8');
	require("db.php"); 
	$tag=$db->getpar("tag");
	switch($tag)
	{
		case "send":
			set_time_limit(0);
			//今日需要巡检点
			$jrxj=0;	
			$bzxj=0;
			$byxj=0;
			$bjxj=0;
			$bnxj=0;
			$drores=$db->getAll("blueInspect","tId='$tid' and hyId='$hyid'");
			if(count($drores)>0)
			{
				foreach($drores as $res)
				{
					$row=$res["BlueInspect"];
					$dropId=$row["id"];
					$patrolCycle=$row["patrolCycle"];
					$patrolNum=$row["patrolNum"];
					$patrolDiff=$row["patrolDiff"];
					
					switch($patrolCycle)
					{
						case '每天':
							$count = $db->getCount("blueOrder","dropId='$dropId' and hyId='$hyid' and addTime>='$start'");
							$jrxj+=$patrolNum-$count;//该点剩余巡检次数。	
						break;
						case '每周':					
							//本周开始时间戳
							$startTime = date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d')-date('w')+1,date('y')));
							//本周结束时间戳
							$overTime = date("Y-m-d H:i:s",mktime(23,59,59,date('m'),date('d')-date('w')+7,date('y')));
							$count = $db->getCount("blueOrder","dropId='$dropId' and hyId='$hyid' and addTime>='$startTime' and addTime<='$overTime'");
							$bzxj+=$patrolNum-$count;//该点剩余巡检次数。	
						break;
						case '每月':					
							//本月起始时间日期格式
							$startTime = date("Y-m-d H:i:s",mktime(0,0,0,date('m'),1,date('Y')));  
							//本月结束时间日期格式
							$overTime = date("Y-m-d H:i:s",mktime(23,59,59,date('m'),date('t'),date('Y'))); 
	
							$count = $db->getCount("blueOrder","dropId='$dropId' and hyId='$hyid' and addTime>='$startTime' and addTime<='$overTime'");
							$byxj+=$patrolNum-$count;//该点剩余巡检次数。	
						break;
						case '每季':					
							 //获取当前季度
							$season = ceil((date('m'))/3);
							 //当前季度开始时间戳
							$starTime=mktime(0, 0, 0,$season*3-3+1,1,date('Y'));
							 //获取当前季度结束时间戳
							$overTime = mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'));
							$startTime=date("Y-m-d H:i:s",$starTime);
							$overTime=date("Y-m-d H:i:s",$overTime);
	
							$count = $db->getCount("blueOrder","dropId='$dropId' and hyId='$hyid' and addTime>='$startTime' and addTime<='$overTime'");
							$bjxj+=$patrolNum-$count;//该点剩余巡检次数。	
						break;
						case '每年':					
							//本年开始  
							$startTime  = date("Y-m-d 00:00:00",strtotime(date("Y",time())."-1"."-1"));
							//本年结束
							$overTime  =  date("Y-m-d 23:59:59",strtotime(date("Y",time())."-12"."-31")); 
	
							$count = $db->getCount("blueOrder","dropId='$dropId' and hyId='$hyid' and addTime>='$startTime' and addTime<='$overTime'");
							$bnxj+=$patrolNum-$count;//该点剩余巡检次数。	
						break;
					}			
				}
			}	
			break;
		
		default:
			echo "没有数据~";
			break;
	}
	
?>
