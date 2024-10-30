<?php
session_start();

// Faqat adminlarga ruxsat
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// MySQL bilan bog'lanish
$conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

// Bo'lim IDsi orqali tahrirlash
if (isset($_GET['id'])) {
    $section_id = $_GET['id'];
    
    // Bo'lim ma'lumotini olish
    $sql = "SELECT * FROM sections WHERE id = $section_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $section = $result->fetch_assoc();
    } else {
        echo "Bo'lim topilmadi.";
        exit();
    }
}

// POST so'rovi bilan bo'limni yangilash
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $section_name = $_POST['section_name'];

    // Yangilash so'rovi
    $sql = "UPDATE sections SET section_name='$section_name' WHERE id = $section_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Bo'lim muvaffaqiyatli yangilandi.";
        header("Location: admin.php");
        exit();
    } else {
        echo "Xatolik: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bo'limni Tahrirlash</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bo'limni Tahrirlash</h1>
    <form method="POST">
        <input type="text" name="section_name" required value="<?= $section['section_name'] ?>" placeholder="Bo'lim nomi">
        <input type="submit" value="Yangilash">
    </form
    
    <br>
    <br>
    <a href="admin.php" class="button">Orqaga</a>
</body>
</html>
