<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost", "jurabek", "Kamina_95", "x_u_15501_jurabek");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $section_id = $_POST['section_id'];
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_answer = $_POST['correct_answer'];

    $sql = "INSERT INTO questions (section_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES ('$section_id', '$question_text', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_answer')";
    if ($conn->query($sql) === TRUE) {
        echo "Savol muvaffaqiyatli qo'shildi.";
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
    <title>Savol Qo'shish</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Savol Qo'shish</h1>
    <form method="POST">
        <select name="section_id" required>
            <option value="">Bo'lim tanlang</option>
            <?php while ($row = $sections->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['section_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="question_text" required placeholder="Savol matni">
        <input type="text" name="option_a" required placeholder="Javob A">
        <input type="text" name="option_b" required placeholder="Javob B">
        <input type="text" name="option_c" required placeholder="Javob C">
        <input type="text" name="option_d" required placeholder="Javob D">
        <input type="text" name="correct_answer" required placeholder="To'g'ri javob (A/B/C/D)">
        <input type="submit" class="button" value="Qo'shish">
        
    </form>
    
    <a href="admin.php" class="button">Orqaga</a>
</body>
</html>
