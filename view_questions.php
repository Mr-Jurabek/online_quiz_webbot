<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM questions";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savollarni Ko'rish</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Savollarni Ko'rish</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Bo'lim ID</th>
                <th>Savol Matni</th>
                <th>Javob A</th>
                <th>Javob B</th>
                <th>Javob C</th>
                <th>Javob D</th>
                <th>To'g'ri Javob</th>
                <th>Amallar</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['section_id'] ?></td>
                        <td><?= $row['question_text'] ?></td>
                        <td><?= $row['option_a'] ?></td>
                        <td><?= $row['option_b'] ?></td>
                        <td><?= $row['option_c'] ?></td>
                        <td><?= $row['option_d'] ?></td>
                        <td><?= $row['correct_answer'] ?></td>
                        <td>
                            <a href="edit_question.php?id=<?= $row['id'] ?>" class="button2">Tahrirlash</a>
                            <a href="delete_question.php?id=<?= $row['id'] ?>" class="button3">O'chirish</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">Savollar topilmadi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <br>
    <br>
    <a href="admin.php" class="button">Orqaga</a>
</body>
</html>
