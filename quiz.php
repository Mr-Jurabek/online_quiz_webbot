<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Foydalanuvchi ID mavjudligini tekshirish
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Agar foydalanuvchi ID mavjud bo'lmasa, index.php ga qaytish
    exit();
}

// Bo'lim ID'sini olish
if (!isset($_SESSION['section_id'])) {
    header("Location: index.php"); // Agar bo'lim ID'si mavjud bo'lmasa, qaytish
    exit();
}
$section_id = $_SESSION['section_id'];

// MySQL ma'lumotlar bazasiga ulanish
$conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

// Ulashuv xatoliklarini tekshirish
if ($conn->connect_error) {
    die("Ma'lumotlar bazasiga ulanishda xato: " . $conn->connect_error);
}

// `settings` jadvalidan savollar sonini olish
$settings_query = "SELECT question_count FROM settings LIMIT 1";
$settings_result = $conn->query($settings_query);

if ($settings_result->num_rows > 0) {
    $settings_row = $settings_result->fetch_assoc();
    $_SESSION['question_count'] = $settings_row['question_count']; // Sessiyada saqlash
} else {
    die("Settings ma'lumotlari topilmadi.");
}

// Foydalanuvchining avvalgi javoblarini saqlash uchun SESSIONdan foydalanamiz
if (!isset($_SESSION['answered_questions'])) {
    $_SESSION['answered_questions'] = []; // Bo'sh ro'yxat
}

// Savollar sonini cheklash
if (count($_SESSION['answered_questions']) >= $_SESSION['question_count']) {
    header("Location: result.php"); // Savollar soniga yetganda natijalar sahifasiga o'tish
    exit();
}

// Keyingi savolni tanlash
$answered_questions = $_SESSION['answered_questions'];


// Agar ro'yxat bo'sh bo'lsa, yangi savollar olish
if (count($answered_questions) === 0) {
    // Savollarni tasodifiy tanlash uchun ORDER BY RAND() ishlatamiz
    $sql = "SELECT * FROM questions WHERE section_id = ? ORDER BY RAND() LIMIT 1";
} else {
    $placeholders = implode(',', array_fill(0, count($answered_questions), '?'));
    // Avvalgi savollarni chiqarib tashlash va tasodifiy savollar olish
    $sql = "SELECT * FROM questions WHERE section_id = ? AND id NOT IN ($placeholders) ORDER BY RAND() LIMIT 1";
}
/*
// Agar ro'yxat bo'sh bo'lsa, yangi savollar olish
if (count($answered_questions) === 0) {
    $sql = "SELECT * FROM questions WHERE section_id = ? LIMIT 1"; // Bo'lim ID'siga mos savolni olish
} else {
    $placeholders = implode(',', array_fill(0, count($answered_questions), '?'));
    // Avvalgi savollarni chiqarib tashlash
    $sql = "SELECT * FROM questions WHERE section_id = ? AND id NOT IN ($placeholders) LIMIT 1";
}

*/

// Ma'lumotlar bazasidan navbatdagi savolni olish
$stmt = $conn->prepare($sql);
if (count($answered_questions) > 0) {
    $types = str_repeat('i', count($answered_questions) + 1); // '+' 1 bo'lim ID'si uchun
    $stmt->bind_param($types, $section_id, ...$answered_questions);
} else {
    $stmt->bind_param("i", $section_id);
}
$stmt->execute();
$result = $stmt->get_result();

// Savollarni olishda xatolarni tekshirish
if ($result->num_rows > 0) {
    $question = $result->fetch_assoc(); // Navbatdagi savolni olamiz
} else {
    // Agar savol qolmagan bo'lsa, natijalar sahifasiga yo'naltiramiz
    header("Location: result.php");
    exit();
}

// Agar POST so'rov kelsa, foydalanuvchi javobni qayta ishlash
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer'])) {
    $question_id = $_POST['question_id'];
    $user_answer = $_POST['answer'];

    // Javobni saqlash uchun SESSION ishlatamiz
    $_SESSION['answered_questions'][] = $question_id;
    if ($user_answer == $question['correct_answer']) {
        $_SESSION['score'] = ($_SESSION['score'] ?? 0) + 1; // To'g'ri javoblar uchun ballni oshirish
    }

    // Keyingi savolga o'tish
    header("Location: quiz.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Test</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Quiz Test</h1>
    <form method="POST">
        <div class="question">
            <h3><?= htmlspecialchars($question['question_text']) ?></h3> <!-- Savol matnini chiqarish -->
            <label><input type="radio" name="answer" value="A" required> <?= htmlspecialchars($question['option_a']) ?></label><br>
            <label><input type="radio" name="answer" value="B"> <?= htmlspecialchars($question['option_b']) ?></label><br>
            <label><input type="radio" name="answer" value="C"> <?= htmlspecialchars($question['option_c']) ?></label><br>
            <label><input type="radio" name="answer" value="D"> <?= htmlspecialchars($question['option_d']) ?></label><br>
        </div>
        <input type="hidden" name="question_id" value="<?= htmlspecialchars($question['id']) ?>">
        <input type="submit" value="Keyingi savol" class="button">
    </form>
</body>
</html>

<?php
$conn->close(); // Ma'lumotlar bazasini yoping
?>
