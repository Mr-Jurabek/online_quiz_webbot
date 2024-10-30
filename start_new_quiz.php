<?php
session_start();

// Sessiya ma'lumotlarini tozalash
unset($_SESSION['score']);
unset($_SESSION['answered_questions']);

// Foydalanuvchini test sahifasiga yo'naltirish
header("Location: start_quiz.php");
exit();
?>
