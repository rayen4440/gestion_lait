<?php
session_start();
include('config.php');
include('header.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$lang_code = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

if(isset($_GET['supprimer'])){
    $id=intval($_GET['supprimer']);
    if($id>0){
        $stmt=$conn->prepare("DELETE FROM presence WHERE id=?");
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $stmt->close();
        header("Location: presence.php");
        exit();
    }
}

$presences = $conn->query("SELECT p.id,e.nom,e.prenom,p.date,p.statut FROM presence p JOIN employes e ON p.employe_id=e.id ORDER BY p.date DESC");
?>

<!DOCTYPE html>
<html lang="<?= $lang_code ?>">
<head>
<meta charset="UTF-8">
<title><?= $lang_code=='fr'?'Présences':'الحضور' ?></title>
<style>
body { 
    background-color: <?= $dark_mode?'#121212':'#90D5FF' ?>; 
    color: <?= $dark_mode?'#eee':'#222' ?>;
    font-family:'Segoe UI',Tahoma,sans-serif;
    margin:20px;
    direction: <?= $lang_code==='ar'?'rtl':'ltr' ?>;
    text-align: <?= $lang_code==='ar'?'right':'left' ?>;
}
h2 { color: <?= $dark_mode?'#ffffffff':'#000000ff' ?>; margin-bottom:15px;text-align: center; }
table { width:100%; border-collapse:collapse; margin-top:20px; box-shadow:0 0 10px rgba(0,0,0,0.1);}
th,td {border:1px solid <?= $dark_mode?'#333':'#000000ff' ?>; padding:12px; text-align:center;}
th {background-color: <?= $dark_mode?'#1565C0':'#1565C0' ?>; color:white;}
tr:{background-color:<?= $dark_mode?'#1e1e1e':'#1565C0' ?>;}
tr:hover{background-color:<?= $dark_mode?'#333':'#d6eaff' ?>;}
.btn-action {display:inline-block; padding:6px 12px; border-radius:4px; font-weight:600; text-decoration:none; margin:0 3px;}
.btn-edit {background-color:#007BFF;color:white;} .btn-edit:hover{background-color:#0056b3;}
.btn-delete {background-color:#dc3545;color:white;} .btn-delete:hover{background-color:#a71d2a;}
.btn-add, .btn-print {display:inline-block; padding:10px 18px; margin-bottom:15px; color:#fff; text-decoration:none; border-radius:6px; font-weight:600;}
.btn-add {background-color:<?= $dark_mode?'#007BFF':'#007BFF' ?>;}
.btn-add:hover {background-color:<?= $dark_mode?'#0056b3':'#0056b3' ?>;}
.btn-print {background-color:#6c757d;} .btn-print:hover {background-color:#6c757d;}
@media print {
    body * {visibility:hidden;}
    table, table * {visibility:visible;}
    table {position:absolute; left:0; top:0; width:100%;}
    table th:last-child, table td:last-child {display:none;}
}
</style>
</head>
<body>

<h2><?= $lang_code=='fr'?'Liste Présences 👤':'👤 قائمة الحضور' ?></h2>
<a href="presence_add.php" class="btn-add"><?= $lang_code=='fr'?'Ajouter Présence ➕':'➕ إضافة حضور' ?></a>
<a href="#" onclick="window.print();" class="btn-print"><?= $lang_code=='fr'?'Imprimer 🖨️':'🖨️ طباعة' ?></a>

<table>
<thead>
<tr>
<th><?= $lang_code=='fr'?'Employé':'الموظف' ?></th>
<th><?= $lang_code=='fr'?'Date':'التاريخ' ?></th>
<th><?= $lang_code=='fr'?'Statut':'الحالة' ?></th>
<th><?= $lang_code=='fr'?'Actions':'الإجراءات' ?></th>
</tr>
</thead>
<tbody>
<?php while($row=$presences->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['nom'].' '.$row['prenom']) ?></td>
<td><?= $row['date'] ?></td>
<td><?= $lang_code=='fr'?($row['statut']=='present'?'Présent':'Absent'):($row['statut']=='present'?'حاضر':'غائب') ?></td>
<td>
<a href="presence_edit.php?modifier=<?= $row['id'] ?>" class="btn-action btn-edit"><?= $lang_code=='fr'?'Modifier ✏️':'✏️ تعديل' ?></a>
<a href="presence.php?supprimer=<?= $row['id'] ?>" class="btn-action btn-delete" onclick="return confirm('<?= $lang_code=='fr'?'Voulez-vous vraiment supprimer 🗑️ ?':'🗑️ هل تريد الحذف فعلا؟' ?>');"><?= $lang_code=='fr'?'Supprimer 🗑️':'🗑️ حذف' ?></a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>


<?php include('footer.php'); ?>
</body>
</html>
