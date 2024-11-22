<!DOCTYPE html>
<html>
<head>
<title>***NETWORK_EQUIP-CONFIDENTIAL***</title>
<?php
$localhost = "127.0.0.1";
$username = "root"; // Add your database username
$password = "admin123"; // Add your database password
$dbname = "testdb";

// Establish database connection
$conn = new mysqli($localhost, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$show_success = false; // Flag to control the success message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $device_name = $conn->real_escape_string($_POST['Drvice_name']);
    $enrol_datex = $conn->real_escape_string($_POST['enrol_datex']);
    $device_type = $conn->real_escape_string($_POST['device_type']);
    $sn = $conn->real_escape_string($_POST['sn']);
    $manufacture = $conn->real_escape_string($_POST['manufacture']);
    $model = $conn->real_escape_string($_POST['model']);
    $usr_email = $conn->real_escape_string($_POST['usr_email']);
    $usr_name = $conn->real_escape_string($_POST['usr_name']);
    $compliance = $conn->real_escape_string($_POST['compliance']);
    $skufamily = $conn->real_escape_string($_POST['skufamily']);

    // Validate email
    if (!filter_var($usr_email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Insert query
        $sql = "INSERT INTO Laptop_inventory (Drvice_name, enrol_datex, device_type, sn, manufacture, model, usr_email, usr_name, compliance, skufamily) 
                VALUES ('$device_name', '$enrol_datex', '$device_type', '$sn', '$manufacture', '$model', '$usr_email', '$usr_name', '$compliance', '$skufamily')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to the same page with success parameter
            header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
            exit; // Stop further execution
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }

    $conn->close();
}

// Check if success parameter is set
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $show_success = true; // Set success flag
}
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<style>
  /* Notification container */
  .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #28a745; /* Green background for success */
      color: white; /* White text for contrast */
      padding: 15px 20px;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      font-size: 16px;
      z-index: 1050;
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 200px;
      text-align: center;
  }

  /* Optional: Add an animation */
  .notification.fade-in {
      animation: fadeIn 0.5s ease-in-out;
  }

  @keyframes fadeIn {
      from {
          opacity: 0;
          transform: translateY(-20px);
      }
      to {
          opacity: 1;
          transform: translateY(0);
      }
  }
</style>

</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">CIPRIANI Network & SECURITY DB</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="indexEQIP.php">HOME</a></li>
      <li><a href="indexNET.php">NETWORK</a></li>
      <li><a href="#">DELETE ENTIRE</a></li>
    </ul>
  </div>
</nav>
<div class="container">
    <h1 class="text-center">Storing Form Data in Database</h1>
    <!-- Success Notification -->
    <?php if ($show_success): ?>
      <div id="success-notification" class="notification fade-in">
          Success!
      </div>
      <script>
          // Automatically hide the notification after 5 seconds and redirect
          setTimeout(function () {
              window.location.href = "dump.php";
          }, 3000); // Redirect after 3 seconds
      </script>
    <?php endif; ?>

    <form action="" method="post" class="form-horizontal">
        <div class="form-group">
            <label for="device_name" class="col-sm-2 control-label">Device Name:</label>
            <div class="col-sm-10">
                <input type="text" name="Drvice_name" id="device_name" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="enrol_datex" class="col-sm-2 control-label">Enrollment Date:</label>
            <div class="col-sm-10">
                <input type="text" name="enrol_datex" id="enrol_datex" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="device_type" class="col-sm-2 control-label">Device Type:</label>
            <div class="col-sm-10">
                <input type="text" name="device_type" id="device_type" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="sn" class="col-sm-2 control-label">Serial Number:</label>
            <div class="col-sm-10">
                <input type="text" name="sn" id="sn" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="manufacture" class="col-sm-2 control-label">Manufacture:</label>
            <div class="col-sm-10">
                <input type="text" name="manufacture" id="manufacture" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="model" class="col-sm-2 control-label">Model:</label>
            <div class="col-sm-10">
                <input type="text" name="model" id="model" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="usr_email" class="col-sm-2 control-label">User Email:</label>
            <div class="col-sm-10">
                <input type="email" name="usr_email" id="usr_email" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="usr_name" class="col-sm-2 control-label">User Name:</label>
            <div class="col-sm-10">
                <input type="text" name="usr_name" id="usr_name" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="compliance" class="col-sm-2 control-label">Compliance:</label>
            <div class="col-sm-10">
                <input type="text" name="compliance" id="compliance" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label for="skufamily" class="col-sm-2 control-label">SKU Family:</label>
            <div class="col-sm-10">
                <input type="text" name="skufamily" id="skufamily" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>
<script>
$(document).ready(function() {
    // Initialize Bootstrap datepicker
    $('#enrol_datex').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
});
</script>
</body>
</html>
