<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Foydalanuvchi ID mavjudligini tekshirish
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// MySQL ma'lumotlar bazasiga ulanish
$conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

// Ulashuv xatoliklarini tekshirish
if ($conn->connect_error) {
    die("Ma'lumotlar bazasiga ulanishda xato: " . $conn->connect_error);
}

// Bo'limlarni olish
$sql = "SELECT * FROM sections";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bo'limlar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bo'limlar</h1>

    <div class="sections">
        <?php while ($row = $result->fetch_assoc()): ?>
            <form method="POST" action="start_quiz.php">
                <input type="hidden" name="section_id" value="<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">
                <button  type="submit" class="button"><?= htmlspecialchars($row['section_name'], ENT_QUOTES, 'UTF-8') ?></button>
                
            </form>
        <?php endwhile; ?>
    </div>
<br>
    <a href="index.php" class="button">Asosiy sahifaga qaytish</a>
</body>
</html>

<?php
$conn->close();
?>
