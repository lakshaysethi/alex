<?php
require_once('functions.php');

// Get prev & next month.
/*
if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
} else {
    // This month.
    $ym = date('Y-m');
}
*/
// You can also write like this.
$ym = (isset($_GET['ym'])) ? $_GET['ym'] : date('Y-m');

// Create timestamp and Check format.
$timestamp = strtotime($ym.'-01');
if ($timestamp === false) {
    $timestamp = time();
}

// Today (Format:2017-01-1)
$today = date('Y-m-j', time());

// For H3 title. (Format: April 2017)
$html_title = date('F Y', $timestamp);

// Create prev & next month link.
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)-1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)+1, 1, date('Y', $timestamp)));

// Number of days in the month.
$day_count = date('t', $timestamp);

// 0:Sun 1:Mon 2:Tue ...
$str = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));

// Create a calendar!!
$weeks = array();
$week = '';

// Add empty cell.
$week .= str_repeat('<td></td>', $str);

// Connect to the database.
$pdo = connectDB();

for ($day = 1; $day <= $day_count; $day++, $str++) {

    $date = $ym . '-' . $day;  // Format example: 2017-04-3

    // Get schedules by date.
/*
    $sql = 'SELECT * FROM schedules WHERE CAST(start_datetime AS DATE) = :start_datetime ORDER BY start_datetime ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':start_datetime', $date, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll();
*/
    $results = getSchedulesByDate($pdo, $date);

    if ($today == $date) {
        $week .= '<td class="today">';
    } else {
        $week .= '<td>';
    }

    $week .= '<a href="detail.php?ymd=' . $date . '">' . $day;

    if ($results) {
        $week .= '<div class="tips">';
        foreach($results as $result) {
            $title = date('H:i', strtotime($result['start_datetime'])) . '~ ' . $result['task'];
            $week .= '<span class="tip" data-toggle="tooltip" data-placement="bottom" title="' . $title . '">&#9679;</span>';
        }
        $week .= '</div>';
    }

    $week .= '</a></td>';


    // End of the week OR End of the month.
    if ($str % 7 == 6 || $day == $day_count) {

        if ($day == $day_count) {
            // Add empty cells.
            //$week .= str_repeat('<td></td>', 6 - date('w', strtotime($date)));
            // You can also write like this.
            $week .= str_repeat('<td></td>', 6 - ($str % 7));
        }

        $weeks[] = '<tr>' . $week . '</tr>';

        // Prepare for new week.
        $week = '';

    }

}

unset($pdo);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PHP Calendar</title>
    <?php include 'head.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <table class="table table-bordered calendar">
        <thead>
            <tr class="cal_head">
                <th colspan="1"><a href="?ym=<?php echo $prev; ?>" class="arrow">&lt;</a></th>
                <th colspan="5"><?php echo $html_title; ?></th>
                <th colspan="1"><a href="?ym=<?php echo $next; ?>" class="arrow">&gt;</a></th>
            </tr>
            <tr class="week_head">
                <th>S</th>
                <th>M</th>
                <th>T</th>
                <th>W</th>
                <th>T</th>
                <th>F</th>
                <th>S</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($weeks as $week) {
                    echo $week;
                }
            ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>
</body>
</html>