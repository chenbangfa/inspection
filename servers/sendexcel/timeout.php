<?php
/**
 * 导出超时未巡检记录到Excel
 * 使用 PhpSpreadsheet 库
 */
require_once('../vendor/autoload.php');
require_once('../data/db.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 获取参数
$tid = $db->getPar("tid");
$hyid = $db->getPar("hyid");
$time = $db->getPar("time");
$times = $db->getPar("times");
$zysearch = $db->getPar("zysearch");
$together = $db->getPar("together");

$whe = "tId='$tid'";
if ($time != '' && $times != '')
    $whe .= " and startTime>='$time' and endTime<='$times'";
if ($zysearch != '')
    $whe .= " and FIND_IN_SET(dropClass,'$zysearch')";
if ($together != '')
    $whe .= " and FIND_IN_SET(hyAppointName,'$together')";

$arr = $db->getAll("noCheck", "$whe", "addTime desc");

// 创建Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("超时未巡检记录");

// 设置表头
$headers = ['序号', '巡检点编号', '巡检点分类', '巡检点名称', '巡检点介绍', '巡检人', '未检次数', '未检开始时间', '未检结束时间', '最后巡检时间', '最后巡检人'];
$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

foreach ($headers as $i => $header) {
    $sheet->setCellValue($columns[$i] . '1', $header);
}

// 设置表头样式
$sheet->getStyle('A1:K1')->getFont()->setBold(true);

// 填充数据
$rowNum = 2;
foreach ($arr as $v) {
    $v = $v["NoCheck"];

    // 填充单元格
    $sheet->setCellValue('A' . $rowNum, $rowNum - 1);
    $sheet->setCellValue('B' . $rowNum, $v['dropNo']);
    $sheet->setCellValue('C' . $rowNum, $v['dropClass']);
    $sheet->setCellValue('D' . $rowNum, $v['dropName']);
    $sheet->setCellValue('E' . $rowNum, $v['dropInfo']);
    $sheet->setCellValue('F' . $rowNum, $v['hyAppointName']);
    $sheet->setCellValue('G' . $rowNum, $v['inspectNum']);
    $sheet->setCellValue('H' . $rowNum, $v['startTime']);
    $sheet->setCellValue('I' . $rowNum, $v['endTime']);
    $sheet->setCellValue('J' . $rowNum, $v['inspectTime']);
    $sheet->setCellValue('K' . $rowNum, $v['inspectName']);

    $rowNum++;
}

// 设置列宽
$widths = ['A' => 10, 'B' => 15, 'C' => 15, 'D' => 20, 'E' => 20, 'F' => 12, 'G' => 12, 'H' => 18, 'I' => 18, 'J' => 18, 'K' => 12];
foreach ($widths as $col => $width) {
    $sheet->getColumnDimension($col)->setWidth($width);
}

// 输出文件
$filename = "超时未巡检记录表_" . date("YmdHis") . ".xlsx";
ob_end_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');