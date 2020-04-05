<?php
require_once('functions.php');

$schedule_id = $_GET['id'];

$pdo = connectDB();

$sql = 'DELETE FROM schedules WHERE schedule_id = :schedule_id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':schedule_id', $schedule_id, PDO::PARAM_INT);
$stmt->execute();

unset($pdo);

header('Location:' . $_SERVER['HTTP_REFERER']);
exit();

?>