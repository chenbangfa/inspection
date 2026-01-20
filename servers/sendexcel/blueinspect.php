<?php
/**
 * 导出巡检点信息到Excel（包含二维码图片）
 * 使用 PhpSpreadsheet 和 endroid/qr-code 库
 */

// 加载 Composer 自动加载器
require_once('../vendor/autoload.php');
require_once('../data/db.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("巡检点列表");

// 设置表头
$headers = ['序号', '巡检点编号', '巡检点名称', '分类', '巡检周期', '每周期次数', '间隔', '巡检人', '最近巡检', '巡检次数', '二维码'];
$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

foreach ($headers as $i => $header) {
    $sheet->setCellValue($columns[$i] . '1', $header);
}

// 设置表头样式
$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'CCCCCC']
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
];
$sheet->getStyle('A1:K1')->applyFromArray($headerStyle);

// QR Writer
$qrWriter = new PngWriter();

// 填充数据
$rowNum = 2;
foreach ($arr as $v) {
    $v = $v["BlueInspect"];

    // 生成二维码图片
    $qrData = strval($v['id']);
    $qrFile = $qrDir . "qr_{$v['id']}.png";

    // 如果二维码不存在则生成
    if (!file_exists($qrFile)) {
        try {
            $qrCode = QrCode::create($qrData)
                ->setSize(150)
                ->setMargin(10);
            $result = $qrWriter->write($qrCode);
            $result->saveToFile($qrFile);
        } catch (Exception $e) {
            // 如果本地生成失败，使用在线API
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
            $qrContent = @file_get_contents($qrUrl);
            if ($qrContent) {
                file_put_contents($qrFile, $qrContent);
            }
        }
    }

    // 填充单元格
    $sheet->setCellValue('A' . $rowNum, $rowNum - 1);
    $sheet->setCellValue('B' . $rowNum, $v['dropNo']);
    $sheet->setCellValue('C' . $rowNum, $v['dropName']);
    $sheet->setCellValue('D' . $rowNum, $v['dropClass']);
    $sheet->setCellValue('E' . $rowNum, $v['patrolCycle']);
    $sheet->setCellValue('F' . $rowNum, $v['patrolNum']);
    $sheet->setCellValue('G' . $rowNum, $v['patrolDiff']);
    $sheet->setCellValue('H' . $rowNum, $v['hyAppointName'] ?? '');
    $sheet->setCellValue('I' . $rowNum, $v['inspectTime'] ?? '');
    $sheet->setCellValue('J' . $rowNum, $v['inspectNum'] ?? 0);

    // 嵌入二维码图片
    if (file_exists($qrFile)) {
        $drawing = new Drawing();
        $drawing->setPath($qrFile);
        $drawing->setHeight(60);
        $drawing->setCoordinates('K' . $rowNum);
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(5);
        $drawing->setWorksheet($sheet);

        // 设置行高以容纳二维码
        $sheet->getRowDimension($rowNum)->setRowHeight(50);
    }

    $rowNum++;
}

// 设置列宽
$columnWidths = [
    'A' => 8,
    'B' => 15,
    'C' => 25,
    'D' => 15,
    'E' => 12,
    'F' => 12,
    'G' => 10,
    'H' => 15,
    'I' => 20,
    'J' => 12,
    'K' => 12
];
foreach ($columnWidths as $col => $width) {
    $sheet->getColumnDimension($col)->setWidth($width);
}

// 输出文件
$filename = "巡检点列表_" . date("YmdHis") . ".xlsx";

// 清空输出缓冲区
ob_end_clean();

// 设置下载头
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
