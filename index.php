<?php
date_default_timezone_set("UTC");
$year = isset($_GET["year"]) ? intval($_GET["year"]) : date("Y");
$month = isset($_GET["month"]) ? intval($_GET["month"]) : date("m");
$firstDayOfMonth = new DateTime("$year-$month-01");
$daysInMonth = $firstDayOfMonth->format("t");
$startDayOfWeek = $firstDayOfMonth->format("N");
$prevMonth = clone $firstDayOfMonth;
$prevMonth->modify("-1 month");
$nextMonth = clone $firstDayOfMonth;
$nextMonth->modify("+1 month");
// Nastavení formátovače data pro češtinu
$formatter = new IntlDateFormatter(
    "cs_CZ",
    IntlDateFormatter::FULL,
    IntlDateFormatter::NONE,
    new DateTimeZone("UTC"),
    IntlDateFormatter::GREGORIAN,
    "LLLL Y"
);
// Získání názvu měsíce a roku v češtině
$monthNameAndYear = $formatter->format($firstDayOfMonth);
$testEventDay = 10;
$testEventDescription = "La Roche en Ardennes UCI marathon series";
$testEventDateRange = "10.5 - 12.5.2019";
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>PHP Kalendář</title>
    <link rel="stylesheet" href="calendar.css">
</head>
<body>
    <div class="calendar">
        <div class="header">
            <a href="?year=<?php echo $prevMonth->format('Y'); ?>&month=<?php echo $prevMonth->format('m'); ?>">&lt; Předchozí</a>
            <span><?php echo $monthNameAndYear; ?></span>
            <a href="?year=<?php echo $nextMonth->format('Y'); ?>&month=<?php echo $nextMonth->format('m'); ?>">Následující &gt;</a>
        </div>

        <table>
            <tr>
                <th>Po</th>
                <th>Út</th>
                <th>St</th>
                <th>Čt</th>
                <th>Pá</th>
                <th>So</th>
                <th>Ne</th>
            </tr>
            <?php
            $currentDay = 1;
            for ($week = 1; $week <= 6; $week++) {
                echo "<tr>";
                for ($dayOfWeek = 1; $dayOfWeek <= 7; $dayOfWeek++) {
                    if (($week == 1 && $dayOfWeek < $startDayOfWeek) || $currentDay > $daysInMonth) {
                        echo "<td></td>";
                    } else {
                        $class = ($currentDay == date("j") && $year == date("Y") && $month == date("m")) ? "today" : "";
                        if ($currentDay == $testEventDay) {
                            $class .= " event";
                            echo "<td class='$class'>$currentDay<br></td>";
                        } else {
                            echo "<td class='$class'>$currentDay</td>";
                        }
                        $currentDay++;
                    }
                }
                echo "</tr>";
                if ($currentDay > $daysInMonth) {
                    break;
                }
            }
            ?>
        </table>
    </div>

    <div id="event-description-container"></div>

<script>
document.querySelectorAll('.event').forEach(function(cell) {
    cell.addEventListener('click', function() {
        var container = document.getElementById('event-description-container');

        container.innerHTML = 
            '<h3>Událost</h3>' +
            '<p><?php echo $testEventDescription; ?></p>' +
            '<p>Datum: <?php echo $testEventDateRange; ?></p>';

        container.classList.add('event-visible');

        setTimeout(function() {
            container.classList.remove('event-visible');
        }, 100);

        setTimeout(function() {
            container.classList.add('event-visible');
        }, 150);
    });
});

</script>
</body>
</html>
