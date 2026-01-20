<?php
/**
 * 导出日常巡检记录到Excel（包含图片）
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

$whe = "tId='$tid'";
if ($time != '' && $times != '')
    $whe .= " and addTime>'$time' and addTime<'$times'";
if ($zysearch != '')
    $whe .= " and FIND_IN_SET(yhSpeciality,'$zysearch')";
if ($together != '')
    $whe .= " and FIND_IN_SET(hyName,'$together')";

$arr = $db->getAll("inspect", "$whe", "addTime desc");

// 创建Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("日常巡检记录");

// 设置表头
$headers = ['序号', '公司名称', '隐患地址', '隐患部位', '隐患内容', '隐患专业', '检查时间', '检查组名称', '检查人', '随检人员', '整治要求', '整治时限', '图片'];
$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];

foreach ($headers as $i => $header) {
    $sheet->setCellValue($columns[$i] . '1', $header);
}

// 设置表头样式
$sheet->getStyle('A1:M1')->getFont()->setBold(true);

// 填充数据
$rowNum = 2;
foreach ($arr as $v) {
    $v = $v["Inspect"];

    // 处理图片
    $yhPhoto = "";
    if (!empty($v["yhPhoto"])) {
        $yhPhoto = substr($v["yhPhoto"], 0, -1);
        $yhPhoto = explode('|', $yhPhoto);
    }

    // 填充单元格
    $sheet->setCellValue('A' . $rowNum, $rowNum - 1);
    $sheet->setCellValue('B' . $rowNum, $v['gsName']);
    $sheet->setCellValue('C' . $rowNum, $v['yhAdd']);
    $sheet->setCellValue('D' . $rowNum, $v['yhPosition']);
    $sheet->setCellValue('E' . $rowNum, $v['yhContent']);
    $sheet->setCellValue('F' . $rowNum, $v['yhSpeciality']);
    $sheet->setCellValue('G' . $rowNum, $v['addTime']);
    $sheet->setCellValue('H' . $rowNum, $v['tName']);
    $sheet->setCellValue('I' . $rowNum, $v['hyName']);
    $sheet->setCellValue('J' . $rowNum, $v['together']);
    $sheet->setCellValue('K' . $rowNum, $v['zgAsk']);
    $sheet->setCellValue('L' . $rowNum, $v['zgTime']);

    // 嵌入图片
    if ($yhPhoto) {
        foreach ($yhPhoto as $k1 => $v1) {
            $imgPath = ROOT . $v1;
            if (file_exists($imgPath)) {
                $drawing = new Drawing();
                $drawing->setPath($imgPath);
                $drawing->setHeight(80);
                $drawing->setWidth(80);
                $drawing->setCoordinates('M' . $rowNum);
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
$widths = ['A' => 10, 'B' => 20, 'C' => 20, 'D' => 15, 'E' => 30, 'F' => 15, 'G' => 20, 'H' => 15, 'I' => 10, 'J' => 15, 'K' => 20, 'L' => 15, 'M' => 80];
foreach ($widths as $col => $width) {
    $sheet->getColumnDimension($col)->setWidth($width);
}

// 输出文件
$filename = "日常巡检记录表_" . date("YmdHis") . ".xlsx";
ob_end_clean();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');