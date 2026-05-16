<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../classes/Database.php";
require_once "../classes/Encryptor.php";

$db = new Database();
$conn = $db->connect();

$sql = "SELECT * FROM passwords WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([":user_id" => $_SESSION["user_id"]]);

$passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Slaptažodžių saugykla</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="page">

    <div class="topbar">
        <h2>Slaptažodžių saugykla</h2>
        <p>Prisijungęs vartotojas: <strong><?= htmlspecialchars($_SESSION["username"]) ?></strong></p>

        <div class="nav-actions">
            <a class="btn-link" href="add_password.php">+ Pridėti slaptažodį</a>
            <a class="btn-link btn-danger" href="../logout.php">Atsijungti</a>
        </div>
    </div>

    <div class="table-card">
        <table>
            <tr>
                <th>Pavadinimas</th>
                <th>Slaptažodis</th>
                <th>Data</th>
            </tr>

            <?php if (count($passwords) === 0): ?>
                <tr>
                    <td colspan="3">Slaptažodžių dar nėra.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($passwords as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item["title"]) ?></td>
                    <td><?= htmlspecialchars(Encryptor::decrypt($item["encrypted_password"], $_SESSION["user_key"])) ?></td>
                    <td><?= htmlspecialchars($item["created_at"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>

</body>
</html>