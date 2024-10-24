<?php
session_start();
include 'db.php';

// Fetch all users for sorting
$usersQuery = "SELECT id, username FROM users WHERE role = 'user'";
$usersResult = $conn->query($usersQuery);

$searchTerm = '';
$selectedUser = 0;
$sqlCondition = '';

// Check if a search or sorting is performed
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $sqlCondition .= " AND (tasks.title LIKE '%$searchTerm%' OR tasks.description LIKE '%$searchTerm%')";
}

if (isset($_GET['user_id']) && $_GET['user_id'] > 0) {
    $selectedUser = $_GET['user_id'];
    $sqlCondition .= " AND tasks.assigned_to = $selectedUser";
}

// Fetch all tasks based on search or user selection
$query = "SELECT tasks.*, users.username FROM tasks 
          JOIN users ON tasks.assigned_to = users.id 
          WHERE 1 $sqlCondition";
$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $assigned_to = $_POST['assigned_to'];
    $uploadedFile = '';

    // Handle file upload
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["document"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow only certain file formats
        $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'png'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["document"]["tmp_name"], $targetFilePath)) {
                $uploadedFile = $fileName;  // Store file name to be saved in the database
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only PDF, DOC, DOCX, JPG, and PNG files are allowed.";
        }
    }

    // Add the task and save file path in the database
    $query = "INSERT INTO tasks (title, description, assigned_to, created_by, format) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssiss', $title, $description, $assigned_to, $_SESSION['user_id'], $uploadedFile);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-header">
        <img src="CMRUNI\login_files\Logo-CMRU.png" alt="Logo" class="logo">
        <h1>Admin Dashboard</h1>
    </div>

    <h2>Search and Filter Tasks</h2>
    <form method="GET">
        <input type="text" name="search" placeholder="Search by title or description" value="<?php echo $searchTerm; ?>">
        <select name="user_id">
            <option value="0">All Users</option>
            <?php
            $usersResult->data_seek(0); // Reset user result set pointer
            while ($user = $usersResult->fetch_assoc()) { ?>
                <option value="<?php echo $user['id']; ?>" <?php if ($selectedUser == $user['id']) echo 'selected'; ?>>
                    <?php echo $user['username']; ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit">Filter</button>
    </form>

    <h2>All Tasks</h2>
    <table border="1">
        <thead>
            <tr>
                <th>KPI</th>
                <th>Metrics</th>
                <th>Assigned User</th>
                <th>Status</th>
                <th>Rating</th>
                <th>Uploaded Document</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($task = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $task['title']; ?></td>
                <td><?php echo $task['description']; ?></td>
                <td><?php echo $task['username']; ?></td>
                <td><?php echo $task['status']; ?></td>
                <td><?php echo ($task['rating'] !== NULL) ? $task['rating'] : 'Not Rated'; ?></td>
                <td>
                    <?php if ($task['format']) { ?>
                        <a href="uploads/<?php echo $task['format']; ?>" target="_blank">View</a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <h2>Add a New Task</h2>
    <form method="POST" enctype="multipart/form-data"> <!-- Add enctype for file upload -->
        <label>Task Title</label>
        <input type="text" name="title" required><br><br>

        <label>Task Description</label>
        <textarea name="description" required></textarea><br><br>

        <label>Assign to User</label>
        <select name="assigned_to" required>
            <?php while ($user = $usersResult->fetch_assoc()) { ?>
                <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
            <?php } ?>
        </select><br><br>

        <label>Upload Format (Optional)</label>
        <input type="file" name="document"><br><br>

        <button type="submit">Add Task</button>
    </form>
</body>
</html>
