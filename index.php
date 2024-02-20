<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple PHP Calendar</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin-bottom: 50px; /* Padding di bawah kalender untuk konten kutipan */
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
        }

        th, td {
            text-align: center;
            padding: 10px;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        td {
            color: #555;
        }

        .today {
            background-color: #ffff99 !important;
        }

        .quote-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .quote-text {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .quote-author {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Simple PHP Calendar</h2>

        <form method="get" class="form-inline justify-content-center">
            <div class="form-group mr-2">
                <label for="month">Select Month:</label>
                <select name="month" id="month" class="form-control mx-2">
                    <?php
                    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                    foreach ($months as $key => $month) {
                        $selected = ($_GET['month'] ?? date('F')) == $month ? 'selected' : '';
                        echo "<option value='$month' $selected>$month</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group mr-2">
                <label for="year">Select Year:</label>
                <select name="year" id="year" class="form-control mx-2">
                    <?php
                    $startYear = 2000;
                    $currentYear = date('Y');
                    for ($i = $startYear; $i <= $currentYear + 10; $i++) {
                        $selected = ($_GET['year'] ?? date('Y')) == $i ? 'selected' : '';
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Go</button>
        </form>

        <?php
        $month = $_GET['month'] ?? date('F');
        $year = $_GET['year'] ?? date('Y');

        $firstDayOfMonth = strtotime("1 $month $year");
        $daysInMonth = date('t', $firstDayOfMonth);
        $startDayOfWeek = date('N', $firstDayOfMonth);
        $today = date('j');

        echo "<table class='table table-bordered'>";
        echo "<thead class='thead-light'><tr><th colspan='7'>$month $year</th></tr>";
        echo "<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr></thead>";
        echo "<tbody>";

        echo "<tr>";
        for ($i = 1; $i < $startDayOfWeek; $i++) {
            echo "<td></td>";
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            if ($startDayOfWeek > 7) {
                echo "</tr><tr>";
                $startDayOfWeek = 1;
            }

            $date = strtotime("$day $month $year");
            $class = $day == $today ? 'today' : '';
            echo "<td class='$class'>$day</td>";

            $startDayOfWeek++;
        }

        echo "</tr>";
        echo "</tbody>";
        echo "</table>";
        ?>
    </div>

    <!-- Quote Container -->
    <div class="quote-container">
        <div class="quote-text" id="quote-text">Loading quote...</div>
        <div class="quote-author" id="quote-author"></div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Function to fetch daily quote from Quotable API
        function fetchDailyQuote() {
            fetch('https://api.quotable.io/random')
                .then(response => response.json())
                .then(data => {
                    $('#quote-text').text(data.content);
                    $('#quote-author').text('— ' + data.author);
                })
                .catch(error => {
                    $('#quote-text').text('Failed to fetch daily quote.');
                });
        }

        // Fetch daily quote when page loads
        $(document).ready(function() {
            fetchDailyQuote();
        });
    </script>
</body>
</html>
