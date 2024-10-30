<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Foydalanuvchi ID'si
$user_id = $_SESSION['user_id'] ?? null;

// Foydalanuvchi natijalari
$score = $_SESSION['score'] ?? 0;

// Telegram bot tokenini o'rnatish
$token = "telegram_bot_api"; // Sizning bot tokeningiz
$website = "https://api.telegram.org/bot" . $token;

// Admin ID olish funksiyasi
function getAdminId($conn) {
    $sql = "SELECT telegram_id FROM admins LIMIT 1"; // Admin ID ni olish
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) { // Natijani tekshirish
        $row = $result->fetch_assoc();
        return $row['telegram_id'];
    }
    return null;
}

// Telegram botga xabar yuborish funksiyasi
function sendMessage($chat_id, $message) {
    global $website;
    $url = $website . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($message);
    $result = file_get_contents($url);
    if ($result === FALSE) {
        error_log("Xabar yuborishda xato: chat_id: $chat_id, message: $message");
    }
}


///////komment goooo

// MySQL ma'lumotlar bazasiga ulanish
$conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

// Ulashuv xatoliklarini tekshirish
if ($conn->connect_error) {
    die("Ma'lumotlar bazasiga ulanishda xato: " . $conn->connect_error);
}

// Foydalanuvchi ID mavjudligini tekshirish
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Agar foydalanuvchi ID mavjud bo'lmasa, index.php ga qaytish
    exit();
}

// Foydalanuvchi natijalarini olish
$score = $_SESSION['score'] ?? 0; // Ballarni olish
$answered_questions_count = count($_SESSION['answered_questions']); // Berilgan savollar soni


// Admin ID'sini olish  /////////
$telegram_id = getAdminId($conn);

// Foydalanuvchiga natijalarni yuborish
if ($user_id) {
    $user_message = "Sizning natijangiz: $score";
    sendMessage($user_id, $user_message);
}

// Adminga natijalarni yuborish
if ($telegram_id) {
    $admin_message = "Foydalanuvchi ID: $user_id\nNatija: $score";
    sendMessage($telegram_id, $admin_message);
}
 ///////////


// Natijani saqlash
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("INSERT INTO test_results (user_id, score) VALUES (?, ?)");
$stmt->bind_param("si", $user_id, $score);
$stmt->execute();

// Sessiyadagi savol va ballni tozalash
unset($_SESSION['answered_questions']);
unset($_SESSION['score']);
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natija</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Natija muvaffaqiyatli saqlandi.</h1>
    <h2>Natija</h2>
    <p>Javob berilgan savollar: <?= htmlspecialchars($answered_questions_count) ?></p>
    <p>To'g'ri javoblar: <?= htmlspecialchars($score) ?></p>
    <p>Natija: <?= $answered_questions_count > 0 ? round(($score / $answered_questions_count) * 100, 2) . '%' : '0%' ?></p>
    
    <!-- Yana sinab ko'rish tugmasi -->
    <a href="sections.php" class="button">Yana sinab ko'rish</a>
    <a href="index.php" class="button">Asosiy sahifaga qaytish</a>
</body>
</html>

<?php
$conn->close(); // Ma'lumotlar bazasini yoping
?>
