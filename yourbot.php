<?php
session_start();
$token = "Your_bot_api"; // Sizning bot tokeningiz
$website = "https://api.telegram.org/bot" . $token;

// Ma'lumotlar bazasiga ulanish
$conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

// Ulashuv xatoliklarini tekshirish
if ($conn->connect_error) {
    die("Ma'lumotlar bazasiga ulanishda xato: " . $conn->connect_error);
}

$update = file_get_contents("php://input");
$update = json_decode($update, TRUE);

if (isset($update["message"])) {
    $chat_id = $update["message"]["chat"]["id"];
    $user_id = $update["message"]["from"]["id"];
    
    // Foydalanuvchining ism va familyasini olish
    $first_name = $update["message"]["from"]["first_name"] ?? 'Foydalanuvchi';
    $last_name = $update["message"]["from"]["last_name"] ?? '';
    $_SESSION['first_name'] = 'Salooom';
    $_SESSION['last_name'] = $last_name;

    // Admin ID ni ma'lumotlar bazasidan olish
    $telegram_id = getAdminId($conn);

    if (isset($update["message"]["text"])) {
        $text = $update["message"]["text"];

        switch ($text) {
            case "/start":
                $response = "Salom, $first_name $last_name! Sizning User ID: " . $user_id . "\nOnlayn test ishlash uchun sayt linki: https://u15501.xvest1.ru?user_id=$user_id";
                sendMessage($chat_id, $response);
                break;
            default:
                sendMessage($chat_id, "Boshqa buyruq kiritildi.");
                break;
        }
    }
}

function getAdminId($conn) {
    $sql = "SELECT telegram_id FROM admins LIMIT 1"; // Admin ID olish
    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        return $row['admin_id'];
    }
    return null; // Agar admin topilmasa
}

function sendMessage($chat_id, $message) {
    global $website;
    $url = $website . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($message);
    
    $result = file_get_contents($url);
    if ($result === FALSE) {
        // Xato bo‘lganda log yozing
        error_log("Xabar yuborishda xato: chat_id: $chat_id, message: $message");
    }
}

// Ma'lumotlar bazasini yoping
$conn->close();
?>
