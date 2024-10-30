<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}
$conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

// Ulashuv xatoliklarini tekshirish
if ($conn->connect_error) {
    die("Ma'lumotlar bazasiga ulanishda xato: " . $conn->connect_error);
}

// Kiritilgan savollar sonini yangilash
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['question_count'])) {
        // Savollar sonini olish
        $question_count = intval($_POST['question_count']);

        // Savollar sonini yangilash
        $sql = "UPDATE settings SET question_count = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $question_count);
        $stmt->execute();

        // Muvoffaqiyatli xabar
        $message = "Savollar soni muvaffaqiyatli yangilandi.";
        $stmt->close();
    } elseif (isset($_POST['telegram_id'])) {
        // Telegram ID ni olish
        $telegram_id = trim($_POST['telegram_id']);

        // Telegram ID ni ma'lumotlar bazasiga qo'shish
        $sql = "INSERT INTO admins (telegram_id) VALUES (?) ON DUPLICATE KEY UPDATE telegram_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $telegram_id, $telegram_id);
        $stmt->execute();

        // Muvoffaqiyatli xabar
        $admin_message = "Admin muvaffaqiyatli qo'shildi.";
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sozlamalar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Sozlamalar</h1>

    <?php if (isset($message)): ?>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if (isset($admin_message)): ?>
        <p><?= htmlspecialchars($admin_message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="question_count">Savollar sonini kiriting:</label>
        <input type="number" id="question_count" name="question_count" min="1" required>
        <button type="submit" class="button">Yangilash</button>
    </form>

    <form method="POST" action="">
        <label for="telegram_id">Admin Telegram ID ni kiriting:</label>
        <input type="text" id="telegram_id" name="telegram_id" required>
        <button type="submit" class="button">Admin qo'shish</button>
    </form>

    <a href="admin.php" class="button">Admin Panelga O'tish</a>
</body>
</html>
