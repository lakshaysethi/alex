<?php
require_once('functions.php');

$schedule_id = $_GET['id'];

$pdo = connectDB();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // Get a schedule by schedule_id.
    $sql = 'SELECT * FROM schedules WHERE schedule_id = :schedule_id LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':schedule_id', $schedule_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();

    $start_datetime = date('Y/m/d H:i', strtotime($result['start_datetime']));
    $end_datetime = date('Y/m/d H:i', strtotime($result['end_datetime']));
    $task = $result['task'];

    $err_msg = '';

} else {

    // Get post values.
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];
    $task = $_POST['task'];

    $err_msg = '';
    if ($start_datetime == '' || $end_datetime == '' || $task == '') {
        $err_msg = 'Please fill in all fields.';
    }

    if ($err_msg == '') {    
        $sql = 'UPDATE schedules
                SET start_datetime = :start_datetime, end_datetime = :end_datetime, task = :task, modified = :modified
                WHERE schedule_id = :schedule_id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':start_datetime', $start_datetime, PDO::PARAM_STR);
        $stmt->bindValue(':end_datetime', $end_datetime, PDO::PARAM_STR);
        $stmt->bindValue(':task', $task, PDO::PARAM_STR);
        $stmt->bindValue(':modified', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':schedule_id', $schedule_id, PDO::PARAM_INT);
        $stmt->execute();

        // Go to detail.php
        header('Location:detail.php?ymd=' . date('Y-m-d', strtotime($start_datetime)));
        exit;

    }

}

unset($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit | PHP Calendar</title>
    <?php include 'head.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <h3>Edit Schedule</h3>

            <?php if ($err_msg != ''): ?>
                <div class="alert alert-warning" role="alert"><?php echo $err_msg; ?></div>
            <?php endif; ?>

            <form class="form-horizontal" method="post">
                <div class="form-group">
                    <label class="col-md-2 control-label">Start</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control custom-picker" name="start_datetime" value="<?php echo $start_datetime; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">End</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control custom-picker" name="end_datetime" value="<?php echo $end_datetime; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">Task</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="task" value="<?php echo $task; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-default btn-block">Cancel</a>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-block">SAVE</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>