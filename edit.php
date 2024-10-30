<?php
session_start();

// Faqat adminlarga ruxsat
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// MySQL bilan bog'lanish
$conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

// Savol IDsi orqali tahrirlaymiz
if (isset($_GET['id'])) {
    $question_id = $_GET['id'];
    
    // Savol ma'lumotini olish
    $sql = "SELECT * FROM questions WHERE id = $question_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $question = $result->fetch_assoc();
    } else {
        echo "Savol topilmadi.";
        exit();
    }
}

// POST so'rovi bilan savolni yangilash
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $section_id = $_POST['section_id'];
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_answer = $_POST['correct_answer'];

    // Yangilash so'rovi
    $sql = "UPDATE questions SET section_id='$section_id', question_text='$question_text', option_a='$option_a', option_b='$option_b', option_c='$option_c', option_d='$option_d', correct_answer='$correct_answer' WHERE id = $question_id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Savol muvaffaqiyatli yangilandi.";
        header("Location: admin.php");
        exit();
    } else {
        echo "Xatolik: " . $conn->error;
    }
}

$sections = $conn->query("SELECT * FROM sections");
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savolni Tahrirlash</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Savolni Tahrirlash</h1>
    <form method="POST">
        <select name="section_id" required>
            <option value="">Bo'lim tanlang</option>
            <?php while ($row = $sections->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= $row['id'] == $question['section_id'] ? 'selected' : '' ?>><?= $row['section_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="question_text" required value="<?= $question['question_text'] ?>">
        <input type="text" name="option_a" required value="<?= $question['option_a'] ?>">
        <input type="text" name="option_b" required value="<?= $question['option_b'] ?>">
        <input type="text" name="option_c" required value="<?= $question['option_c'] ?>">
        <input type="text" name="option_d" required value="<?= $question['option_d'] ?>">
        <input type="text" name="correct_answer" required value="<?= $question['correct_answer'] ?>">
        <input type="submit" value="Yangilash">
    </form>
    <br>
    <br>
    <a href="admin.php" class="button">Orqaga</a>
</body>
</html>
