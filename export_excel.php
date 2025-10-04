<?php
require 'vendor/autoload.php'; // مسار مكتبة PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include 'config.php';

// استعلام بيانات الموظفين (كمثال)
$sql = "SELECT id, nom, prenom, poste FROM employes ORDER BY nom";
$result = $conn->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// العناوين
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nom');
$sheet->setCellValue('C1', 'Prénom');
$sheet->setCellValue('D1', 'Poste');

// تعبئة البيانات
$rowNum = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['id']);
    $sheet->setCellValue('B' . $rowNum, $row['nom']);
    $sheet->setCellValue('C' . $rowNum, $row['prenom']);
    $sheet->setCellValue('D' . $rowNum, $row['poste']);
    $rowNum++;
}

// ضبط الهيدر لتحميل الملف
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="employes.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
