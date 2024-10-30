<?php
session_start();

// Foydalanuvchi ID mavjudligini tekshirish
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Agar foydalanuvchi ID mavjud bo'lmasa, foydalanuvchini qayta yo'naltirish
    exit();
}


// Agar POST so'rov orqali bo'lim tanlansa, sessiyaga saqlash
if (isset($_POST['section_id'])) {
    $_SESSION['section_id'] = $_POST['section_id'];
}

// Agar foydalanuvchi sinovni qayta boshlasa
if (isset($_POST['restart'])) {
    // Javoblar va ballni tozalash
    unset($_SESSION['answered_questions']);
    unset($_SESSION['score']);
    // Bo'lim ID'sini saqlab qolamiz, chunki qayta boshlaganda ham u kerak
}

// Sinov sahifasiga yo'naltirish
header("Location: quiz.php");
exit();
