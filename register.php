<?php
session_start();
$localhost = "127.0.0.1";
$username = "root";
$password = "admin123";
$dbname = "testdb";

$conn = new mysqli($localhost, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$message = ""; // To store messages (e.g., warnings or success)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Input validation
    if (empty($username) || !ctype_alnum($username)) {
        $message = "Username must be alphanumeric and cannot be empty.";
    } else {
        // Check maximum user limit
        $user_count_sql = "SELECT COUNT(*) AS user_count FROM users";
        $result = $conn->query($user_count_sql);
        $row = $result->fetch_assoc();
        $user_count = $row['user_count'];

        if ($user_count >= 10) {
            $message = "Maximum user limit (10) reached. Registration is not allowed.";
        } else {
            // Check if the username already exists
            $check_sql = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $check_sql->bind_param("s", $username);
            $check_sql->execute();
            $check_result = $check_sql->get_result();

            if ($check_result->num_rows > 0) {
                $message = "Username already exists. Please choose a different username.";
            } else {
                // Insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_sql = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $insert_sql->bind_param("ss", $username, $hashed_password);

                if ($insert_sql->execute()) {
                    // Automatically log in the user after registration
                    $_SESSION['user_id'] = $conn->insert_id;
                    $_SESSION['username'] = $username;
                    header("Location: lapinv.php");
                    exit;
                } else {
                    $message = "Error: " . $conn->error;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="mt-4">Register</h2>
    <?php if ($message): ?>
        <div class="alert alert-warning"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label>Username:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
        <a href="login.php" class="btn btn-secondary">Login</a>
    </form>
</div>
</body>
</html>
