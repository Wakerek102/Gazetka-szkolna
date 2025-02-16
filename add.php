<?php
$conn = mysqli_connect("localhost", "root", "", "Gazetka");
if (!$conn) {
    die("Błąd połączenia: " . mysqli_connect_error());
}

// Obsługa dodawania wpisu
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if (!empty($title) && !empty($description)) {
        $query = "INSERT INTO Wpisy (Tytuł, Opis) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $title, $description);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Nowy wpis został dodany!'); window.location.href='gazetka.php';</script>";
            } else {
                echo "Błąd podczas dodawania wpisu: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "<script>alert('Tytuł i opis nie mogą być puste!');</script>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gazetka Szkolna - Nowy Wpis</title>
    <link href="gazStyl.css" rel="stylesheet">
</head>
<body>
    <div id="entry">
        <form action="" method="post">
            <h3>Tytuł ogłoszenia:</h3>
            <input type="text" name="title" id="title" required />

            <h3>Opis ogłoszenia:</h3>
            <textarea name="description" id="description" required></textarea>
            <br>
            <input type="reset" class="button" name="clear" id="clear" value="Anuluj" onclick="Cancel()" />
            <input type="submit" class="button" name="add" id="add" value="Dodaj" />
        </form>
    </div>
    <script>
        function Cancel() {
            window.location.href = "gazetka.php";
        }
    </script>
</body>
</html>
