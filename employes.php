<?php
session_start();
include('config.php');
include('header.php');
include('footer.php');

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

// Récupérer tous les employés
$result = $conn->query("SELECT * FROM employes ORDER BY nom ASC");
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8" />
    <title><?= $lang === 'ar' ? 'قائمة الموظفين' : 'Liste des employés' ?></title>
    <style>
        body {
            background-color: <?= $dark_mode ? '#121212' : '#90D5FF' ?>;
            color: <?= $dark_mode ? '#eee' : '#222' ?>;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            direction: <?= $lang === 'ar' ? 'rtl' : 'ltr' ?>;
            text-align: <?= $lang === 'ar' ? 'right' : 'left' ?>;
        }
        h2 { color: <?= $dark_mode ? '#ffffffff' : '#000000ff' ?>; margin-bottom: 15px; text-align: center; }
        a.btn-add, .btn-print {
            display: inline-block;
            margin-bottom: 20px;
            background-color: <?= $dark_mode ? '#007BFF' : '#007BFF' ?>;
            color: white;
            padding:10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            transform:translateY(-2px);
        }
        a.btn-add:hover { background-color: <?= $dark_mode ? '#0056b3' : '#0056b3' ?>; }
        .btn-print { background-color: #6c757d; }
        .btn-print:hover { transform: translateY(-2px); }
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid <?= $dark_mode ? '#333' : '#000000ff' ?>;
            padding: 12px;
            text-align: center;
        }
        th { background-color: <?= $dark_mode ? '#1565C0' : '#1565C0' ?>; color: white; }
tr:{background-color:<?= $dark_mode?'#1e1e1e':'#1565C0' ?>;}
        tr:hover { background-color: <?= $dark_mode ? '#333' : '#d6eaff' ?>; }

        a.btn-modifier { background-color: #007BFF; color: #fff; padding: 6px 14px; border-radius: 6px; text-decoration: none; }
        a.btn-modifier:hover { background-color: #0056b3; }
        a.btn-supprimer { background-color: #dc3545; color: #fff; padding: 6px 14px; border-radius: 6px; text-decoration: none; }
        a.btn-supprimer:hover { background-color: #a71d2a; }

        @media (max-width: 600px) {
            table, th, td { font-size: 0.9rem; }
            a.btn-add, .btn-print, a.btn-modifier, a.btn-supprimer { padding: 6px 12px; font-size: 0.85rem; }
        }

        @media print {
            body * { visibility: hidden; }
            table, table * { visibility: visible; }
            table { position: absolute; left: 0; top: 0; width: 100%; }
            table th:last-child, table td:last-child { display: none; }
        }
    </style>
</head>
<body>

<h2><?= $lang === 'ar' ? '👥 قائمة الموظفين' : 'Liste des employés 👥' ?></h2>

<a href="employes_add.php" class="btn-add"><?= $lang === 'ar' ? '➕ إضافة موظف' : 'Ajouter Employé ➕' ?></a>
<a href="#" onclick="window.print();" class="btn-print"><?= $lang === 'ar' ? ' 🖨️طباعة' : 'Imprimer 🖨️' ?></a>
</a>


<table>
    <thead>
        <tr>
            <th><?= $lang === 'ar' ? 'الاسم' : 'Nom' ?></th>
            <th><?= $lang === 'ar' ? 'اللقب' : 'Prénom' ?></th>
            <th><?= $lang === 'ar' ? 'المنصب' : 'Poste' ?></th>
            <th><?= $lang === 'ar' ? 'الراتب الأساسي' : 'Salaire de base' ?></th>
            <th><?= $lang === 'ar' ? 'الإجراءات' : 'Actions' ?></th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nom']) ?></td>
                <td><?= htmlspecialchars($row['prenom']) ?></td>
                <td><?= htmlspecialchars($row['poste']) ?></td>
                <td><?= number_format($row['salaire_base']) ?> TND</td>
                <td>
                    <a href="employes_edit.php?id=<?= $row['id'] ?>" class="btn-modifier"><?= $lang === 'ar' ? '✏️ تعديل' : 'Modifier ✏️' ?></a>
                    <a href="employes_delete.php?id=<?= $row['id'] ?>" class="btn-supprimer" onclick="return confirm('<?= $lang === 'ar' ? '🗑️ هل أنت متأكد من الحذف؟' : 'Confirmer la suppression 🗑️ ?' ?>');"><?= $lang === 'ar' ? '🗑️ حذف' : 'Supprimer 🗑️' ?></a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
