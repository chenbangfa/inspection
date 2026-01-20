
<?php
$exitdate=9;
$exittime="08:12";
echo date('Y-m-'.$exitdate.' '.$exittime);
echo '-----------';
echo $month = date('m', strtotime('midnight first day of -1 month'));


	header('Content-Type:application/json; charset=utf-8');
	require_once("data/db.php");
	
	$w=date('w')==0?7:date('w');
	$exittime="06:00";
	$week=$db->getzhouqi("周日");//重置时间
	
	$startTime = date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d')-date('w')+1,date('y')));
	if(date('w')==0)
		$startTime = date('Y-m-d H:i:s',mktime(0, 0, 0, date('m'), date('d') - date('w') + 1 - 7, date('Y')));
					
	if($w>=$week)
	{
		$week--;
		$startTime= date("Y-m-d ".$exittime,strtotime("+$week day",strtotime($startTime)));
	}else
	{
		$startTime= date("Y-m-d ".$exittime,strtotime("-7 day",strtotime($startTime)));
		
		$week--;
		$startTime= date("Y-m-d ".$exittime,strtotime("+$week day",strtotime($startTime)));
		
	}
	
	echo $startTime;
				