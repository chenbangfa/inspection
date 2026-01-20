<?php
/**
 * 导出周期巡检记录到Excel（包含图片）
 * 使用 PhpSpreadsheet 库
 */
require_once('../vendor/autoload.php');
require_once('../data/db.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// 获取参数
$tid = $db->getPar("tid");
$hyid = $db->getPar("hyid");
$time = $db->getPar("time");
$times = $db->getPar("times");
$zysearch = $db->getPar("zysearch");
$together = $db->getPar("together");
$states = $db->getPar("states");

$whe = "tId='$tid'";
if ($time != '' && $times != '')
    $whe .= " and addTime>'$time' and addTime<'$times'";
if ($zysearch != '')
    $whe .= " and FIND_IN_SET(gName,'$zysearch')";
if ($together != '')
    $whe .= " and FIND_IN_SET(hyName,'$together')";
if ($states != '')
    $whe .= " and odState='$states'";

$arr = $db->getAll("blueOrder", "$whe", "addTime desc");

// 创建Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("周期巡检记录");

// 设置表头
$headers = ['序号', '巡检点编号', '巡检点分类', '巡检点名称', '巡检人', '随检人员', '巡检备注', '巡检状态', '巡检时间', '图片'];
$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

foreach ($headers as $i => $header) {
    $sheet->setCellValue($columns[$i] . '1', $header);
}

// 设置表头样式
$sheet->getStyle('A1:J1')->getFont()->setBold(true);

// 填充数据
$rowNum = 2;
foreach ($arr as $v) {
    $v = $v["BlueOrder"];

    // 处理图片
    $yhPhoto = "";
    if (!empty($v["odPhoto"])) {
        $yhPhoto = substr($v["odPhoto"], 0, -1);
        $yhPhoto = explode('|', $yhPhoto);
    }

    // 填充单元格
    $sheet->setCellValue('A' . $rowNum, $rowNum - 1);
    $sheet->setCellValue('B' . $rowNum, $v['dropNo']);
    $sheet->setCellValue('C' . $rowNum, $v['gName']);
    $sheet->setCellValue('D' . $rowNum, $v['dropName']);
    $sheet->setCellValue('E' . $rowNum, $v['hyName']);
    $sheet->setCellValue('F' . $rowNum, $v['odtogether']);
    $sheet->setCellValue('G' . $rowNum, $v['odInfo']);
    $sheet->setCellValue('H' . $rowNum, $v['odState'] == 0 ? "正常" : "异常");
    $sheet->setCellValue('I' . $rowNum, $v['addTime']);

    // 嵌入图片
    if ($yhPhoto) {
        foreach ($yhPhoto as $k1 => $v1) {
            $imgPath = ROOT . $v1;
            if (file_exists($imgPath)) {
                $drawing = new Drawing();
                $drawing->setPath($imgPath);
                $drawing->setHeight(80);
                $drawing->setWidth(80);
                $drawing->setCoordinates('J' . $rowNum);
                $drawing->setOffsetX(80 * ($k1 + 1));
                $drawing->setOffsetY(0);
                $drawing->setWorksheet($sheet);
                $sheet->getRowDimension($rowNum)->setRowHeight(70);
            }
        }
    }

    $rowNum++;
}

// 设置列宽
$widths = ['A' => 10, 'B' => 15, 'C' => 15, 'D' => 20, 'E' => 10, 'F' => 15, 'G' => 20, 'H' => 10, 'I' => 20, 'J' => 80];
foreach ($widths as $col => $width) {
    $sheet->getColumnDimension($col)->setWidth($width);
}

// 输出文件
$filename = "周期巡检记录表_" . date("YmdHis") . ".xlsx";
ob_end_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');