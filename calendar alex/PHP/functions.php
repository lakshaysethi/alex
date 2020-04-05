<?php
// Set your timezone!
date_default_timezone_set('Asia/Tokyo');


// Connect to Database
function connectDB() {
    $dbname = 'my_calendar';
    $host = 'localhost';
    $user = 'root';
    $password = 'password';
    $param = 'mysql:dbname=' . $dbname . ';host=' . $host;  // mysql:dbname=my_calendar;host=localhost

    try {
        $pdo = new PDO($param, $user, $password);
        return  $pdo;

    } catch (PDOException $e) {
        // Show error message.
        echo $e->getMessage();
        exit;
    }
}

function getSchedulesByDate($pdo, $date) {

    // Get schedules by date.
    $sql = 'SELECT * FROM schedules WHERE CAST(start_datetime AS DATE) = :start_datetime ORDER BY start_datetime ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':start_datetime', $date, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll();

    return $results ? $results : false;

}
?>