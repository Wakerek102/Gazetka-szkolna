<?php
$conn = mysqli_connect("localhost", "root", "", "Gazetka");
if (!$conn) {
    die("Błąd połączenia: " . mysqli_connect_error());
}

$title = "";
$description = "";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT Tytuł, Opis FROM Wpisy WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $title, $description);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Aktualizacja wpisu po wysłaniu formularza
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $query = "UPDATE Wpisy SET Tytuł = ?, Opis = ? WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $title, $description, $id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Wpis został zaktualizowany!'); window.location.href='gazetka.php';</script>";
        } else {
            echo "Błąd podczas aktualizacji: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Wpis</title>
    <link href="gazStyl.css" rel="stylesheet">
</head>
<body>
    <div id="entry">
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
            <h3>Tytuł ogłoszenia:</h3>
            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>" required />
            <h3>Opis ogłoszenia:</h3>
            <textarea name="description" id="description" required><?php echo htmlspecialchars($description); ?></textarea>
            <br>
            <input type="reset" class="button" value="Anuluj" onclick="Cancel()" />
            <input type="submit" class="button" value="Zapisz zmiany" />
        </form>
    </div>
    <script>
        function Cancel() {
            window.location.href = "gazetka.php";
        }
    </script>
</body>
</html>
