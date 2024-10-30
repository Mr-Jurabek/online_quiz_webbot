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
    
    // Savolni olish
    $stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $question = $result->fetch_assoc();
    } else {
        echo "Savol topilmadi.";
        exit();
    }
} else {
    echo "Savol IDsi ko'rsatilmagan.";
    exit();
}

// Savolni yangilash
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $section_id = intval($_POST['section_id']);
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_answer = $_POST['correct_answer'];

    // Savolni yangilash
    $stmt = $conn->prepare("UPDATE questions SET section_id = ?, question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ? WHERE id = ?");
    $stmt->bind_param("issssssi", $section_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer, $question_id);
    $stmt->execute();

    header("Location: view_questions.php"); // Savollarni ko'rish sahifasiga qaytish
    exit();
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savolni Tahrirlash</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        textarea {
            width: 100%; /* O'lcham */
            height: 60px; /* O'lcham */
            padding: 5px; /* Ichki bo'shliq */
            border: 1px solid #ccc; /* Chegara */
            border-radius: 4px; /* Qirralarni yumshatish */
            resize: none; /* O'lchamni o'zgartirish imkoniyatini olib tashlash */
        }
    </style>
</head>
<body>
    <h1>Savolni Tahrirlash</h1>
    <form method="POST">
        <label for="section_id">Bo'lim:</label>
        <select name="section_id" required>
            <!-- Bo'limlar ro'yxatini olish -->
            <?php
            $sections_result = $conn->query("SELECT * FROM sections");
            while ($section = $sections_result->fetch_assoc()) {
                echo "<option value=\"{$section['id']}\" " . ($section['id'] == $question['section_id'] ? 'selected' : '') . ">{$section['section_name']}</option>";
            }
            ?>
        </select><br>
 

        <label for="question_text">Savol:</label>
        <textarea name="question_text" required><?= htmlspecialchars($question['question_text']) ?></textarea><br>

        <label for="option_a">Variant A:</label>
        <input type="text" name="option_a" value="<?= htmlspecialchars($question['option_a']) ?>" required><br>

        <label for="option_b">Variant B:</label>
        <input type="text" name="option_b" value="<?= htmlspecialchars($question['option_b']) ?>" required><br>

        <label for="option_c">Variant C:</label>
        <input type="text" name="option_c" value="<?= htmlspecialchars($question['option_c']) ?>" required><br>

        <label for="option_d">Variant D:</label>
        <input type="text" name="option_d" value="<?= htmlspecialchars($question['option_d']) ?>" required><br>

        <label for="correct_answer">To'g'ri javob:</label>
        <input type="text" name="correct_answer" value="<?= htmlspecialchars($question['correct_answer']) ?>" required><br>

        <input type="submit" class="button" value="Yangilash">
    </form>
    <a href="view_questions.php" class="button">Orqaga</a>
</body>
</html>

<?php
$conn->close(); // Ma'lumotlar bazasini yoping
?>
