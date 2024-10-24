<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];

// Fetch user's tasks
$query = "SELECT * FROM tasks WHERE assigned_to = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Add new task
    $query = "INSERT INTO tasks (title, description, assigned_to, created_by) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssii', $title, $description, $user_id, $user_id);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>User Dashboard</h1>
    <h2>Your Tasks</h2>
    <table border="1">
        <thead>
            <tr>
                <th>KPI</th>
                <th>Metrics</th>
                <th>Status</th>
                <th>Rating</th>
                <th>Document Format</th>
                <th>Submit Task</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($task = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $task['title']; ?></td>
                <td><?php echo $task['description']; ?></td>
                <td><?php echo $task['status']; ?></td>
                <td><?php echo ($task['rating'] !== NULL) ? $task['rating'] : 'Not Rated'; ?></td>
                <td>
                    <!-- Check if the admin uploaded a document (stored in 'format' column) -->
                    <?php if (!empty($task['format'])) { ?>
                        <a href="uploads/<?php echo $task['format']; ?>" target="_blank">View Document</a>
                    <?php } else { ?>
                        No Document
                    <?php } ?>
                </td>
                <td>
                    <?php if ($task['status'] == 'pending') { ?>
                        <form method="POST" enctype="multipart/form-data">
                            <label>Upload document:</label>
                            <input type="file" name="file" required><br><br>
                            <label>Rate this task (1-5):</label><br>
                            <input type="radio" id="1" name="rating" value="1">
                            <label for="1">1</label>
                            <input type="radio" id="2" name="rating" value="2">
                            <label for="2">2</label>
                            <input type="radio" id="3" name="rating" value="3">
                            <label for="3">3</label>
                            <input type="radio" id="4" name="rating" value="4">
                            <label for="4">4</label>
                            <input type="radio" id="5" name="rating" value="5">
                            <label for="5">5</label><br>
                            <input type="submit" value="Submit Task" class="submit-btn">
                        </form>
                    <?php } else { ?>
                        Completed
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
