<?php
require_once('functions.php');

$ymd = $_GET['ymd'];

$pdo = connectDB();

// Get schedules by date.
$results = getSchedulesByDate($pdo, $ymd);

unset($pdo);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Detail | PHP Calendar</title>
    <?php include 'head.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <h3><?php echo date('M j, Y', strtotime($ymd)); ?></h3>
            <?php if ($results): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 25%;"><span class="glyphicon glyphicon-time" aria-hidden="true"></span></th>
                            <th>Task</th>
                            <th style="width: 25%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($results as $result): ?>
                            <tr>
                                <td><?php echo date('H:i', strtotime($result['start_datetime'])) . ' ~ ' . date('H:i', strtotime($result['end_datetime'])); ?></td>
                                <td><?php echo $result['task']; ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $result['schedule_id']; ?>">Edit</a>&nbsp;&nbsp;
                                    <a href="javascript:void(0);"
                                    onclick="var ok=confirm('Are you sure?'); if(ok) location.href='delete.php?id=<?php echo $result['schedule_id']; ?>'">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                No schedule.
            <?php endif; ?>
            <a href="add.php?ymd=<?php echo $ymd; ?>" class="btn btn-default pull-right">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>
            </a>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>