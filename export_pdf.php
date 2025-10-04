<?php
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');
include 'config.php';

// استعلام بيانات الموظفين كمثال
$sql = "SELECT id, nom, prenom, poste FROM employes ORDER BY nom";
$result = $conn->query($sql);

// إعداد PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Gestion Lait');
$pdf->SetTitle('Liste des employés');
$pdf->AddPage();

// HTML لعرض جدول
$html = '<h2>Liste des employés</h2>';
$html .= '<table border="1" cellpadding="4">
<tr>
<th>ID</th>
<th>Nom</th>
<th>Prénom</th>
<th>Poste</th>
</tr>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
    <td>' . $row['id'] . '</td>
    <td>' . htmlspecialchars($row['nom']) . '</td>
    <td>' . htmlspecialchars($row['prenom']) . '</td>
    <td>' . htmlspecialchars($row['poste']) . '</td>
    </tr>';
}

$html .= '</table>';

// طباعة الـ HTML إلى PDF
$pdf->writeHTML($html, true, false, true, false, '');

// إخراج PDF للتحميل
$pdf->Output('employes.pdf', 'D'); // 'D' تعني تحميل مباشرة

exit();
