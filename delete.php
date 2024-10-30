<?php
session_start();

// Faqat adminlarga ruxsat
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// MySQL bilan bog'lanish
$conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

// Savol IDsi orqali o'chirish
if (isset($_GET['id'])) {
    $question_id = $_GET['id'];

    // Savolni o'chirish so'rovi
    $sql = "DELETE FROM questions WHERE id = $question_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Savol muvaffaqiyatli o'chirildi.";
    } else {
        echo "Xatolik: " . $conn->error;
    }

    header("Location: admin.php");
    exit();
} else {
    echo "Savol topilmadi.";
}
?>
