<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

if ($conn->connect_error) {
    die("Ma'lumotlar bazasiga ulanishda xato: " . $conn->connect_error);
}

// Savol ID sini olish
if (isset($_GET['id'])) {
    $question_id = intval($_GET['id']);
    
    // Savolni o'chirish
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();

    header("Location: view_questions.php"); // Savollarni ko'rish sahifasiga qaytish
    exit();
} else {
    echo "Savol IDsi ko'rsatilmagan.";
    exit();
}

$conn->close(); // Ma'lumotlar bazasini yoping
?>
