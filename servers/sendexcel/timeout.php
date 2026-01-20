<?php require_once('../data/db.php');
require "../Classes/PHPExcel.php";
require "../Classes/PHPExcel/Writer/Excel2007.php";
require "../Classes/PHPExcel/Worksheet/Drawing.php";

		$tid = $db->getPar("tid");
		$hyid = $db->getPar("hyid");
		
		$time = $db->getPar("time");
		$times = $db->getPar("times");
		$zysearch = $db->getPar("zysearch");
		$together = $db->getPar("together");
		
		$whe="tId='$tid'";
		if($time!=''&&$times!='')
			$whe.=" and startTime>='$time' and endTime<='$times'";
		if($zysearch!='')			
			$whe.=" and FIND_IN_SET(dropClass,'$zysearch')";
		if($together!='')			
			$whe.=" and FIND_IN_SET(hyAppointName,'$together')";
			

$arr=$db->getAll("noCheck","$whe","addTime desc");

$objExcel = new \PHPExcel();
$objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
$objActSheet = $objExcel->getActiveSheet();
$key = ord("A");
$letter = explode(',', "A,B,C,D,E,F,G,H,I,J,K");
//设置表头
$arrHeader = array('序号', '巡检点编号','巡检点分类','巡检点名称','巡检点介绍','巡检人' ,'未检次数','未检开始时间','未检结束时间','最后巡检时间','最后巡检人');
$lenth = count($arrHeader);
for ($i = 0; $i < $lenth; $i++)
{
    $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
};

//填充表格信息
$sort=0;
foreach ($arr as $k => $v)
{
	$v = $v["NoCheck"];
	
    //从第二行开始
    $k += 2;
	$sort++;
	
    //表格内容
    $objActSheet->setCellValue('A' . $k, $sort);
    $objActSheet->setCellValue('B' . $k, $v['dropNo']);
    $objActSheet->setCellValue('C' . $k, $v['dropClass']);
    $objActSheet->setCellValue('D' . $k, $v['dropName']);
    $objActSheet->setCellValue('E' . $k, $v['dropInfo']);
    $objActSheet->setCellValue('F' . $k, $v['hyAppointName']);
    $objActSheet->setCellValue('G' . $k, $v['inspectNum']);
    $objActSheet->setCellValue('H' . $k, $v['startTime']);
    $objActSheet->setCellValue('I' . $k, $v['endTime']);
    $objActSheet->setCellValue('J' . $k, $v['inspectTime']);
    $objActSheet->setCellValue('K' . $k, $v['inspectName']);
	
    //设置表格的宽度
    $objActSheet->getColumnDimension('A')->setWidth(10);
    $objActSheet->getColumnDimension('B')->setWidth(10);
    $objActSheet->getColumnDimension('C')->setWidth(10);
    $objActSheet->getColumnDimension('D')->setWidth(20);
    $objActSheet->getColumnDimension('E')->setWidth(20);
    $objActSheet->getColumnDimension('F')->setWidth(10);
    $objActSheet->getColumnDimension('G')->setWidth(15);
    $objActSheet->getColumnDimension('H')->setWidth(15);
    $objActSheet->getColumnDimension('I')->setWidth(15);
    $objActSheet->getColumnDimension('J')->setWidth(15);
    $objActSheet->getColumnDimension('K')->setWidth(10);

}
    $outfile = "超时未巡检记录表" . time() . ".xlsx";
   //清空输出缓冲区
    ob_end_clean();
    //告诉浏览器强制下载
    header("Content-Type: application/force-download");
    //二进制文件类型
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");
    //设置表名
    header('Content-Disposition:inline;filename="' . $outfile . '"');
    header("Content-Transfer-Encoding: binary");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Pragma: no-cache");
    $objWriter->save('php://output'); 