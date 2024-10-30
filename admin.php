<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Admin Panel</h1>
    <a href="add_section.php" class="button">Bo'lim Qo'shish</a>
    <a href="add_question.php" class="button">Savol Qo'shish</a>
    <a href="view_questions.php" class="button">Savollarni Ko'rish</a>
    <a href="statistics.php" class="button">Statistika</a>
    <a href="settings.php" class="button">Sozlamalar</a> <!-- Yangi tugma qo'shildi -->
    <a href="index.php" class="button">Asosiy Sahifaga O'tish</a>
</body>
</html>
