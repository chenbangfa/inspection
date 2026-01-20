<?php
/**
 * 导出巡检点信息到Excel（包含二维码图片）
 */
require_once('../data/db.php');
require "../Classes/PHPExcel.php";
require "../Classes/PHPExcel/Writer/Excel2007.php";
require "../Classes/PHPExcel/Worksheet/Drawing.php";

// 获取参数
$tid = $db->getPar("tid");
$hyid = $db->getPar("hyid");
$zqvalue = $db->getPar("zqvalue");
$drostate = $db->getPar("drostate");
$drores = $db->getPar("drores");
$gname = $db->getPar("gname");
$droname = $db->getPar("droname");

// 构建查询条件（与getbluelist一致）
$whe = "tId='$tid'";
if ($zqvalue != '')
    $whe .= " and patrolCycle='$zqvalue'";
if ($drores != '')
    $whe .= " and drores='$drores'";
if ($drostate != '')
    $whe .= " and drostate='$drostate'";
if ($droname != '')
    $whe .= " and (dropName like '%$droname%' or dropNo='$droname')";
if ($gname != '')
    $whe .= " and FIND_IN_SET(dropClass,'$gname')";

// 查询数据
$arr = $db->getAll("blueInspect", "$whe", "addtime desc");

// 创建临时二维码目录
$qrDir = ROOT . "upimg/qrcode/";
if (!is_dir($qrDir)) {
    mkdir($qrDir, 0777, true);
}

// 创建Excel
$objExcel = new \PHPExcel();
$objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
$objActSheet = $objExcel->getActiveSheet();
$objActSheet->setTitle("巡检点列表");

// 设置表头
$arrHeader = array('序号', '巡检点编号', '巡检点名称', '分类', '巡检周期', '每周期次数', '间隔', '巡检人', '最近巡检', '巡检次数', '二维码');
$letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K');
$lenth = count($arrHeader);
for ($i = 0; $i < $lenth; $i++) {
    $objActSheet->setCellValue("{$letter[$i]}1", $arrHeader[$i]);
}

// 设置表头样式
$objActSheet->getStyle('A1:K1')->getFont()->setBold(true);
$objActSheet->getStyle('A1:K1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
$objActSheet->getStyle('A1:K1')->getFill()->getStartColor()->setRGB('CCCCCC');

// 填充数据
$sort = 0;
foreach ($arr as $k => $v) {
    $v = $v["BlueInspect"];
    $k += 2; // 从第二行开始
    $sort++;

    // 生成二维码图片（使用Google Charts API）
    $qrData = $v['id']; // 二维码内容为巡检点ID
    $qrFile = $qrDir . "qr_{$v['id']}.png";

    // 如果二维码不存在则生成
    if (!file_exists($qrFile)) {
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
        $qrContent = @file_get_contents($qrUrl);
        if ($qrContent) {
            file_put_contents($qrFile, $qrContent);
        }
    }

    // 填充单元格
    $objActSheet->setCellValue('A' . $k, $sort);
    $objActSheet->setCellValue('B' . $k, $v['dropNo']);
    $objActSheet->setCellValue('C' . $k, $v['dropName']);
    $objActSheet->setCellValue('D' . $k, $v['dropClass']);
    $objActSheet->setCellValue('E' . $k, $v['patrolCycle']);
    $objActSheet->setCellValue('F' . $k, $v['patrolNum']);
    $objActSheet->setCellValue('G' . $k, $v['patrolDiff']);
    $objActSheet->setCellValue('H' . $k, $v['hyAppointName']);
    $objActSheet->setCellValue('I' . $k, $v['inspectTime'] ?? '');
    $objActSheet->setCellValue('J' . $k, $v['inspectNum'] ?? 0);

    // 嵌入二维码图片
    if (file_exists($qrFile)) {
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        $objDrawing->setPath($qrFile);
        $objDrawing->setHeight(80);
        $objDrawing->setWidth(80);
        $objDrawing->setCoordinates('K' . $k);
        $objDrawing->setOffsetX(5);
        $objDrawing->setOffsetY(5);
        $objDrawing->setWorksheet($objActSheet);

        // 设置行高以容纳二维码
        $objActSheet->getRowDimension($k)->setRowHeight(70);
    }
}

// 设置列宽
$objActSheet->getColumnDimension('A')->setWidth(8);
$objActSheet->getColumnDimension('B')->setWidth(15);
$objActSheet->getColumnDimension('C')->setWidth(25);
$objActSheet->getColumnDimension('D')->setWidth(15);
$objActSheet->getColumnDimension('E')->setWidth(12);
$objActSheet->getColumnDimension('F')->setWidth(12);
$objActSheet->getColumnDimension('G')->setWidth(10);
$objActSheet->getColumnDimension('H')->setWidth(15);
$objActSheet->getColumnDimension('I')->setWidth(20);
$objActSheet->getColumnDimension('J')->setWidth(12);
$objActSheet->getColumnDimension('K')->setWidth(15);

// 输出文件
$outfile = "巡检点列表_" . date("YmdHis") . ".xlsx";

// 清空输出缓冲区
ob_end_clean();

// 设置下载头
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header('Content-Disposition: attachment; filename="' . $outfile . '"');
header("Content-Transfer-Encoding: binary");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");

$objWriter->save('php://output');
