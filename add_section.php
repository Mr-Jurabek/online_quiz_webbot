<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// Ma'lumotlarni bazaga qo'shish
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_section'])) {
    $section_name = $_POST['section_name'];

    $conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO sections (section_name) VALUES ('$section_name')";
    if ($conn->query($sql) === TRUE) {
        echo "Bo'lim muvaffaqiyatli qo'shildi.";
    } else {
        echo "Xatolik: " . $conn->error;
    }

    $conn->close();
}

// Ma'lumotlarni olish va tahrirlash
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_section'])) {
    $section_id = $_POST['section_id'];
    $section_name = $_POST['section_name'];
    $conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE sections SET section_name='$section_name' WHERE id='$section_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Bo'lim muvaffaqiyatli yangilandi.";
    } else {
        echo "Xatolik: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bo'lim Qo'shish va Tahrirlash</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bo'lim Qo'shish</h1>
    <form method="POST">
        <input type="text" name="section_name" required placeholder="Bo'lim nomi">
        <input type="submit" name="add_section" class="button" value="Qo'shish">
    </form>

    <h1>Mavjud Bo'limlar</h1>
    <?php
    // Mavjud bo'limlarni ko'rsatish
    $conn = new mysqli("localhost", "mysql_table", "mysql_parol", "database_nomi");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM sections";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<form method='POST' style='margin-top: 10px;'>";
            echo "<input type='hidden' name='section_id' value='" . $row['id'] . "'>";
            echo "<input type='text' name='section_name' value='" . $row['section_name'] . "' required>";
            echo "<input type='submit' name='edit_section' class='button' value='Tahrirlash'>";
            echo "</form>";
        }
    } else {
        echo "Hech qanday bo'lim mavjud emas.";
    }

    $conn->close();
    ?>
    <a href="admin.php" class="button">Orqaga</a>
</body>
</html>
