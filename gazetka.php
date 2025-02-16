<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gazetka Szkolna</title>
    <link href="gazStyl.css" rel="stylesheet" />
</head>
<body>
    <header>
        <div id="entries">
            <h2>Wpisy:</h2>
            <ul id="list">
                <?php
                    $conn = mysqli_connect("localhost", "root", "", "Gazetka");

                    if (!$conn) {die("Błąd połączenia: " . mysqli_connect_error());}

                    error_reporting(E_ALL);
                    ini_set('display_errors', 1);

                    $query = "SELECT Id, Tytuł, Opis FROM Wpisy";
                    $write = mysqli_query($conn, $query);

                    while ( $row = mysqli_fetch_array( $write ))
                    {
                        echo "<li id = \"".$row['Id']."\" onclick = \"SelectId(".$row['Id'].")\"><b>".$row['Tytuł']."</b><br>";
                        echo $row['Opis']."</li>";
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST')
                    {

                        //echo "Połączenie z bazą danych udane";

                        if (isset($_POST['selectedId']))
                        {
                            $selectedId = intval($_POST['selectedId']);

                            //echo "Wpis do usunięcia" . $selectedId;

                            $query = "DELETE FROM Wpisy WHERE Id = ?";
                            $stmt = mysqli_prepare($conn, $query);

                            if ($stmt)
                            {
                                mysqli_stmt_bind_param($stmt, "i", $selectedId);
                                if (mysqli_stmt_execute($stmt))
                                {
                                    //echo "Wpis został usunięty.";
                                }
                                else
                                {
                                    echo "Błąd podczas usuwania wpisu: " . mysqli_error($conn);
                                }
                                mysqli_stmt_close($stmt);
                            }
                            else 
                            {
                                echo "Błąd przygotowania zapytania." . mysqli_error($conn);
                            }
                        }

                        mysqli_close($conn);
                    }
                ?>
            </ul>
            <form action="" method="post">
                <input type="hidden" id="selectedIdform" name="selectedId" />
                <input type="button" class="button" id="edit" name="edit" value="Edytuj" onclick="Edit()" />
                <input type="button" class="button" id="delete" name="delete" value="Usuń" onclick="Delete()" />
                <input type="button" class="button" id="add" name="add" value="Dodaj" onclick="Add()" />
            </form>
        </div>
    </header>
            
    <footer>
        <div class="time">
            <h3>Lekcja: <div id="lessonTime"></div></h3>
        </div>
        <div class="time">
            <h3>Przerwa: <div id="breakTime"></div></h3>
        </div>
        <div class="time">
            <h3>Do wakacji: <div id="summerBreakTime"></div></h3>
        </div>
    </footer>
    
    <script>
        setInterval(function() {LessonEnd(); BreakEnd();}, 1000);

        function LessonEnd() {
    const now = new Date();
    let timeLeft = 0; // Poprawiono deklarację

    let hour = now.getHours();
    let minutes = now.getMinutes();

    const currentTime = hour * 60 + minutes;

    const lessons = [
        {start: 7 * 60 + 45, end: 8 * 60 + 30},
        {start: 8 * 60 + 35, end: 9 * 60 + 20},
        {start: 9 * 60 + 30, end: 10 * 60 + 15},
        {start: 10 * 60 + 25, end: 11 * 60 + 5},
        {start: 11 * 60 + 15, end: 12 * 60 + 0},
        {start: 12 * 60 + 15, end: 13 * 60 + 0},
        {start: 13 * 60 + 10, end: 13 * 60 + 55},
        {start: 14 * 60 + 0, end: 14 * 60 + 45},
        {start: 14 * 60 + 50, end: 15 * 60 + 35},
        {start: 15 * 60 + 40, end: 16 * 60 + 25},
        {start: 16 * 60 + 30, end: 17 * 60 + 15},
        {start: 17 * 60 + 20, end: 18 * 60 + 5}
    ];
    
    let found = false;
    
    for (let lesson of lessons) {
        if (currentTime >= lesson.start && currentTime <= lesson.end) {
            timeLeft = lesson.end - currentTime;
            document.getElementById("lessonTime").innerHTML = timeLeft + " min";
            document.getElementById("breakTime").innerHTML = "0 min";
            found = true;
            break;
        }
    }

    if (!found) {
        document.getElementById("lessonTime").innerHTML = "0 min";
    }
}

function BreakEnd() {
    const now = new Date();
    let timeLeft = 0; // Poprawiono deklarację

    let hour = now.getHours();
    let minutes = now.getMinutes();

    const currentTime = hour * 60 + minutes;

    const breaks = [
        {start: 8 * 60 + 30, end: 8 * 60 + 35},
        {start: 9 * 60 + 20, end: 9 * 60 + 30},
        {start: 10 * 60 + 15, end: 10 * 60 + 25},
        {start: 11 * 60 + 5, end: 11 * 60 + 15},
        {start: 12 * 60 + 0, end: 12 * 60 + 15},
        {start: 13 * 60 + 0, end: 13 * 60 + 10},
        {start: 13 * 60 + 55, end: 14 * 60 + 0},
        {start: 14 * 60 + 45, end: 14 * 60 + 50},
        {start: 15 * 60 + 35, end: 15 * 60 + 40},
        {start: 16 * 60 + 25, end: 16 * 60 + 30},
        {start: 17 * 60 + 15, end: 17 * 60 + 20}
    ];

    let found = false;
    
    for (let breakTime of breaks) {
        if (currentTime >= breakTime.start && currentTime <= breakTime.end) {
            timeLeft = breakTime.end - currentTime;
            document.getElementById("breakTime").innerHTML = timeLeft + " min";
            document.getElementById("lessonTime").innerHTML = "0 min";
            found = true;
            break;
        }
    }

    if (!found) {
        document.getElementById("breakTime").innerHTML = "0 min";
    }
}


        function SummerStartDate()
        {
            var year = new Date().getFullYear();
            var month = new Date().getMonth();
            var day = new Date().getDate();

            if( month > 7 ) //????????????
            {
                year += 1;
            }

            const specificDate = new Date(year, 6, 1, 0, 0, 0);

            return specificDate;
        }
        function DaysUntilSummer()
        {
            const currentDate = new Date();
            const summerBreak = SummerStartDate();

            const timeDifference = Math.abs(summerBreak - currentDate);
            const differenceInDays = timeDifference / (1000 * 60 * 60 * 24);
            return Math.floor(differenceInDays); 
        }
        document.getElementById("summerBreakTime").innerHTML = DaysUntilSummer() + " dni";

        /////////////////////////////
        let selectedId = -1;
        function SelectId(Id)
        {
            selectedId = Id;
            document.getElementById("selectedIdform").value = selectedId;
            console.log(selectedId);
        }

        function Add()
        {
            window.location.href = "add.php";
        }

        function Delete()
        {
            console.log("Kliknięto Delete");

            if (selectedId === -1)
            {
                alert("Wybierz wpis do usunięcia!");
                return;
            }

            const confirmation = confirm("Czy na pewno chcesz usunąć ten wpis?");
            
            if (confirmation)
            {
                document.forms[0].submit();
                setTimeout(function() {window.location.href = 'index.php';}, 10);
            }
        }
        
        function Edit()
        {
            if (selectedId === -1)
            {
                alert("Wybierz wpis do edycji!");
                return;
            }

            window.location.href = "edit.php?id=" + selectedId;
        }
    </script>
</body>
</html>