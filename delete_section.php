<?php
session_start();

// Faqat adminlarga ruxsat
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// MySQL bilan bog'lanish
$conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

// Bo'lim IDsi orqali o'chirish
if (isset($_GET['id'])) {
    $section_id = $_GET['id'];

    // Bo'limni o'chirish so'rovi
    $sql = "DELETE FROM sections WHERE id = $section_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Bo'lim muvaffaqiyatli o'chirildi.";
    } else {
        echo "Xatolik: " . $conn->error;
    }

    header("Location: admin.php");
    exit();
} else {
    echo "Bo'lim topilmadi.";
}
?>
