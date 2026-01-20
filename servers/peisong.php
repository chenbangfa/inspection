<?php
require "Classes/PHPExcel.php";
require "Classes/PHPExcel/Writer/Excel2007.php";
require "Classes/PHPExcel/Worksheet/Drawing.php";


$arr = [
    [
        'id' => 1,
        'name' => 'wyq',
        'nickname' => '心如止水',
        'password' => '123456',
        'phone' => '13451167651',
        'init_time' => 1622632002,
        'img'   => ''
    ],
    [
        'id' => 2,
        'name' => 'fj',
        'nickname' => 'xj',
        'password' => '123456',
        'phone' => '13451163651',
        'init_time' => 1622632002,
        'img' => ''
    ],
    [
        'id' => 3,
        'name' => 'szy',
        'nickname' => 'szy',
        'password' => '123456',
        'phone' => '13451163641',
        'init_time' => 1622632002,
        'img'       => ['upimg/20230611/160537_7705.png','upimg/20230609/135640_5436.png']
    ],
    [
        'id' => 4,
        'name' => 'zw',
        'nickname' => 'zw',
        'password' => '123456',
        'phone' => '13451163631',
        'init_time' => 1622632002,
		'img'   => 'upimg/20230611/160537_7705.png'
    ]
];
//实例化
$objExcel = new \PHPExcel();
//设置文档属性
$objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
//设置内容；
$objActSheet = $objExcel->getActiveSheet();
$key = ord("A");
$letter = explode(',', "A,B,C,D,E,F,G");
//设置表头
$arrHeader = array('用户ID', '用户名', '昵称','用户密码','手机号码' ,'注册时间','图片');
$lenth = count($arrHeader);
//填充表头信息 A1:用户ID、B1:用户名、C1:昵称
for ($i = 0; $i < $lenth; $i++) {
    $objActSheet->setCellValue("$letter[$i]1", "$arrHeader[$i]");
};

//填充表格信息
foreach ($arr as $k => $v) {
    //从第二行开始
    $k += 2;
    //表格内容
    $objActSheet->setCellValue('A' . $k, $v['id']);
    $objActSheet->setCellValue('B' . $k, $v['name']);
    $objActSheet->setCellValue('C' . $k, $v['nickname']);
    $objActSheet->setCellValue('D' . $k, $v['password']);
    $objActSheet->setCellValue('E' . $k, $v['phone']);
    $objActSheet->setCellValue('F' . $k, date('Y-m-d H:i:s', $v['init_time']));


    if ($v['img']){
        foreach ($v['img'] as $k1=>$v1){
			
			
			print_r("mmmmmmmmmmmmmm".$v1);
            //实例化图片操作类
            $objDrawing  = new PHPExcel_Worksheet_Drawing();
            //设置图片地址
            $objDrawing -> setPath($v1);
            //设置图片高
            $objDrawing ->setHeight(80);
            //设置图片宽
            $objDrawing ->setWidth(80);
            //设置图片存放在表格的位置
            $objDrawing ->setCoordinates('G' . $k);

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
    $objActSheet->getColumnDimension('A')->setWidth($width[5]);
    $objActSheet->getColumnDimension('B')->setWidth($width[1]);
    $objActSheet->getColumnDimension('C')->setWidth($width[0]);
    $objActSheet->getColumnDimension('D')->setWidth($width[5]);
    $objActSheet->getColumnDimension('E')->setWidth($width[5]);
    $objActSheet->getColumnDimension('F')->setWidth($width[5]);
    $objActSheet->getColumnDimension('G')->setWidth(80);

}
    $outfile = "人员表" . time() . ".xlsx";
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


