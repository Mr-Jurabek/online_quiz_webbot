<?php
session_start();
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Admin ID
$admin_id = "Admin_id_raqami"; // Sizning admin ID

// Agar foydalanuvchi ID mavjud bo'lsa, uni sessiyaga saqlang
if ($user_id) {
    $_SESSION['user_id'] = $user_id;

    // Agar foydalanuvchi admin bo'lsa, sessiyaga admin belgisini qo'shish
    if ($user_id == $admin_id) {
        $_SESSION['is_admin'] = true;
    } else {
        $_SESSION['is_admin'] = false;
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Test Sayti</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Quiz Test Sayti</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Foydalanuvchi ID: <?= $_SESSION['user_id'] ?></p>
       
        <a href="sections.php" class="button">Bo'limlar Sahifasiga O'tish</a>

    <?php else: ?>
        <p>Foydalanuvchi ID mavjud emas. Iltimos, Telegram orqali kiriting.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        
        <a href="admin.php" class="button">Admin Panel</a>
    <?php endif; ?>
</body>
</html>
