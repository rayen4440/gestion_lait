<?php
session_start();
include('config.php');
include('header.php');

$lang = $_SESSION['lang'] ?? 'fr';
$dark_mode = $_SESSION['dark_mode'] ?? false;

$result = $conn->query("SELECT * FROM alimentation ORDER BY date_achat DESC");
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8" />
    <title><?= $lang === 'ar' ? 'قائمة العلف' : 'Liste de l\'alimentation' ?></title>
    <style>
        body {
            background-color: <?= $dark_mode ? '#121212' : '#fff' ?>;
            color: <?= $dark_mode ? '#eee' : '#222' ?>;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            direction: <?= $lang === 'ar' ? 'rtl' : 'ltr' ?>;
            text-align: <?= $lang === 'ar' ? 'right' : 'left' ?>;
        }
        h2 {
            color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
            margin-bottom: 15px;
        }
        a.btn-add {
            display: inline-block;
            margin-bottom: 20px;
            background-color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        a.btn-add:hover {
            background-color: <?= $dark_mode ? '#1e7e34' : '#0056b3' ?>;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid <?= $dark_mode ? '#333' : '#ccc' ?>;
            padding: 12px 15px;
            text-align: center;
        }
        th {
            background-color: <?= $dark_mode ? '#28a745' : '#007BFF' ?>;
            color: white;
            user-select: none;
        }
        tr:nth-child(even) {
            background-color: <?= $dark_mode ? '#1e1e1e' : '#f9f9f9' ?>;
        }
        tr:hover {
            background-color: <?= $dark_mode ? '#333' : '#d6eaff' ?>;
        }
        a.action-link {
            margin: 0 5px;
            color: <?= $dark_mode ? '#80d080' : '#0056b3' ?>;
            text-decoration: none;
            font-weight: 600;
        }
        a.action-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            table, th, td {
                font-size: 0.9rem;
            }
            a.btn-add {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<h2><?= $lang === 'ar' ? 'قائمة العلف' : 'Liste de l\'alimentation' ?></h2>

<a href="alimentation_add.php" class="btn-add"><?= $lang === 'ar' ? 'إضافة علف' : 'Ajouter Alimentation' ?></a>

<table>
    <thead>
        <tr>
            <th><?= $lang === 'ar' ? 'نوع العلف' : 'Type d\'aliment' ?></th>
            <th><?= $lang === 'ar' ? 'الكمية' : 'Quantité' ?></th>
            <th><?= $lang === 'ar' ? 'تاريخ الشراء' : 'Date d\'achat' ?></th>
            <th><?= $lang === 'ar' ? 'المورد' : 'Fournisseur' ?></th>
            <th><?= $lang === 'ar' ? 'الإجراءات' : 'Actions' ?></th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['type_aliment']) ?></td>
                <td><?= number_format($row['quantite'], 2) ?></td>
                <td><?= htmlspecialchars($row['date_achat']) ?></td>
                <td><?= htmlspecialchars($row['fournisseur']) ?></td>
                <td>
                    <a href="alimentation_edit.php?id=<?= $row['id'] ?>" class="action-link"><?= $lang === 'ar' ? '✏️ تعديل' : 'Modifier ✏️' ?></a> |
                    <a href="alimentation_delete.php?id=<?= $row['id'] ?>" class="action-link" onclick="return confirm('<?= $lang === 'ar' ? 'هل أنت متأكد من الحذف؟' : 'Confirmer la suppression 🗑️ ?' ?>');"><?= $lang === 'ar' ? '🗑️ حذف' : 'Supprimer 🗑️' ?></a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include('footer.php'); ?>

</body>
</html>
