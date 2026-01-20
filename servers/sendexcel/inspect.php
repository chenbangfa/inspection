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
			$whe.=" and addTime>'$time' and addTime<'$times'";
		if($zysearch!='')			
			$whe.=" and FIND_IN_SET(yhSpeciality,'$zysearch')";
		if($together!='')			
			$whe.=" and FIND_IN_SET(hyName,'$together')";	
			

$arr=$db->getAll("inspect","$whe","addTime desc");



$objExcel = new \PHPExcel();
$objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
$objActSheet = $objExcel->getActiveSheet();
$key = ord("A");
$letter = explode(',', "A,B,C,D,E,F,G,H,I,J,K,L,M");
//设置表头
$arrHeader = array('序号', '公司名称', '隐患地址','隐患部位','隐患内容' ,'隐患专业','检查时间','检查组名称','检查人','随检人员','整治要求','整治时限','图片');
$lenth = count($arrHeader);
for ($i = 0; $i < $lenth; $i++)
{
    $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
};

//填充表格信息
$sort=0;
foreach ($arr as $k => $v)
{
	$v = $v["Inspect"];
    //从第二行开始
    $k += 2;
	$sort++;
	
	$yhPhoto="";
	if($v["yhPhoto"]!="")
	{
		$yhPhoto =substr($v["yhPhoto"], 0, -1);
		$yhPhoto = explode('|',$yhPhoto);
	}
    //表格内容
    $objActSheet->setCellValue('A' . $k, $sort);
    $objActSheet->setCellValue('B' . $k, $v['gsName']);
    $objActSheet->setCellValue('C' . $k, $v['yhAdd']);
    $objActSheet->setCellValue('D' . $k, $v['yhPosition']);
    $objActSheet->setCellValue('E' . $k, $v['yhContent']);
    $objActSheet->setCellValue('F' . $k, $v['yhSpeciality']);
    $objActSheet->setCellValue('G' . $k, $v['addTime']);
    $objActSheet->setCellValue('H' . $k, $v['tName']);
    $objActSheet->setCellValue('I' . $k, $v['hyName']);
    $objActSheet->setCellValue('J' . $k, $v['together']);
    $objActSheet->setCellValue('K' . $k, $v['zgAsk']);
    $objActSheet->setCellValue('L' . $k, $v['zgTime']);
	
	if ($yhPhoto)
	{
        foreach($yhPhoto as $k1=>$v1)
		{
			
			 //实例化图片操作类
            $objDrawing  = new \PHPExcel_Worksheet_Drawing();
            //设置图片地址
            $objDrawing -> setPath(ROOT.$v1);
            //设置图片高
            $objDrawing ->setHeight(80);
            //设置图片宽
            $objDrawing ->setWidth(80);
            //设置图片存放在表格的位置
            $objDrawing ->setCoordinates('M' . $k);

            //设置X方向偏移量每一张图片的后面追加一个偏移量
            $objDrawing ->setOffsetX(80*($k1+1));
            //设置Y方向偏移量
            $objDrawing ->setOffsetY(0);
            $objDrawing ->setWorksheet($objActSheet);
            //设置表格的高度
            $objActSheet->getRowDimension($k)->setRowHeight(100);
        }
    }

    $width = array(20, 20, 15, 10, 10, 30, 10, 15);
    //设置表格的宽度
    $objActSheet->getColumnDimension('A')->setWidth(10);
    $objActSheet->getColumnDimension('B')->setWidth($width[1]);
    $objActSheet->getColumnDimension('C')->setWidth($width[0]);
    $objActSheet->getColumnDimension('D')->setWidth($width[5]);
    $objActSheet->getColumnDimension('E')->setWidth($width[5]);
    $objActSheet->getColumnDimension('F')->setWidth($width[5]);
    $objActSheet->getColumnDimension('G')->setWidth($width[5]);
    $objActSheet->getColumnDimension('H')->setWidth($width[5]);
    $objActSheet->getColumnDimension('I')->setWidth($width[5]);
    $objActSheet->getColumnDimension('J')->setWidth($width[5]);
    $objActSheet->getColumnDimension('K')->setWidth($width[5]);
    $objActSheet->getColumnDimension('L')->setWidth($width[5]);
    $objActSheet->getColumnDimension('M')->setWidth(80);

}
    $outfile = "日常巡检记录表" . time() . ".xlsx";
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