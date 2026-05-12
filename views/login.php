<?php
session_start();
require_once "../classes/Database.php";
require_once "../classes/User.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new Database();
    $conn = $db->connect();

    $user = new User($conn);

    if ($user->login($_POST["username"], $_POST["password"])) {
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Neteisingas vartotojo vardas arba slaptažodis.";
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Prisijungimas</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="auth-page">

<div class="card">
    <h2>Prisijungimas</h2>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Vartotojo vardas</label>
        <input type="text" name="username" required>

        <br><br>

        <label>Slaptažodis</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit">Prisijungti</button>
    </form>

    <div class="link">
        <a href="register.php">Neturite paskyros? Registruotis</a>
    </div>
</div>

</body>
</html>