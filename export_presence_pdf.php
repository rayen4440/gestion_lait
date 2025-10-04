<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include 'config.php';

$sql = "SELECT p.id, e.nom, e.prenom, p.mois, p.salaire_net, p.cnss, p.jours_absence FROM paie p JOIN employes e ON p.employe_id = e.id ORDER BY p.mois DESC";
$result = $conn->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nom');
$sheet->setCellValue('C1', 'Prénom');
$sheet->setCellValue('D1', 'Mois');
$sheet->setCellValue('E1', 'Salaire Net');
$sheet->setCellValue('F1', 'CNSS');
$sheet->setCellValue('G1', 'Jours d\'absence');

$rowNum = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['id']);
    $sheet->setCellValue('B' . $rowNum, $row['nom']);
    $sheet->setCellValue('C' . $rowNum, $row['prenom']);
    $sheet->setCellValue('D' . $rowNum, $row['mois']);
    $sheet->setCellValue('E' . $rowNum, $row['salaire_net']);
    $sheet->setCellValue('F' . $rowNum, $row['cnss']);
    $sheet->setCellValue('G' . $rowNum, $row['jours_absence']);
    $rowNum++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="paie.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
