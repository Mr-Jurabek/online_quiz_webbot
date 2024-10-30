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

// Test natijalarini olish
$sql = "SELECT user_id, COUNT(*) AS test_count, AVG(score) AS average_score FROM test_results GROUP BY user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistika</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Statistika</h1>
    <table>
        <thead>
            <tr>
                <th>Foydalanuvchi ID</th>
                <th>Testlar Soni</th>
                <th>O'rtacha Ball</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['user_id'] ?></td>
                        <td><?= $row['test_count'] ?></td>
                        <td><?= number_format($row['average_score'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Statistika topilmadi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="admin.php">Orqaga</a>
</body>
</html>
