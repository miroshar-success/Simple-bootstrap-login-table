<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection credentials
$localhost = "127.0.0.1";
$username = "root";
$password = "admin123";
$dbname = "testdb";

// Create a new connection
$conn = new mysqli($localhost, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Handle deletion of multiple rows
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected'])) {
    $ids = implode(",", array_map('intval', $_POST['selected_ids']));
    $delete_sql = "DELETE FROM Laptop_inventory WHERE id IN ($ids)";
    $conn->query($delete_sql);
}

// Handle update row
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_row'])) {
    $id = intval($_POST['edit_id']);
    $device_name = $conn->real_escape_string($_POST['Drvice_name']);
    $enrol_datex = $conn->real_escape_string($_POST['enrol_datex']);
    $device_type = $conn->real_escape_string($_POST['device_type']);
    $sn = $conn->real_escape_string($_POST['sn']);
    $manufacture = $conn->real_escape_string($_POST['manufacture']);
    $model = $conn->real_escape_string($_POST['model']);
    $usr_name = $conn->real_escape_string($_POST['usr_name']);
    $skufamily = $conn->real_escape_string($_POST['skufamily']);

    $update_sql = "UPDATE Laptop_inventory SET 
        Drvice_name='$device_name', 
        enrol_datex='$enrol_datex', 
        device_type='$device_type', 
        sn='$sn', 
        manufacture='$manufacture', 
        model='$model', 
        usr_name='$usr_name', 
        skufamily='$skufamily' 
        WHERE id=$id";
    $conn->query($update_sql);
}

// Fetch data for the table
$sql = "SELECT * FROM Laptop_inventory";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laptop Inventory</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap.min.js"></script>
</head>
<body>
<style>
    #inventoryTable tbody tr:hover {
        background-color: #007bff; /* Highlight color */
        color: white; /* Text contrast */
    }
    #inventoryTable thead th {
        text-align: center; /* Center-align text in all header cells */
        vertical-align: middle; /* Vertically align to middle for better alignment */
    }
</style>
<?php include 'include/navbar.php'; ?>

<div class="container">
    <h2>Laptop Inventory</h2>
    <form method="POST">
        <button type="submit" name="delete_selected" class="btn btn-danger bulk-delete" style="margin: 40px 0">Delete Selected</button>
        <table id="inventoryTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select_all"></th>
                    <th>DEVICE NAME</th>
                    <th>ENROLL DATE</th>
                    <th>DEVICE TYPE</th>
                    <th>SERIAL NUMBER</th>
                    <th>MANUFACTURE</th>
                    <th>MODEL</th>
                    <th>USER NAME</th>
                    <th>SKU FAMILY</th>
                    <th style="width:80px;">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input type="checkbox" name="selected_ids[]" value="<?= $row['id'] ?>"></td>
                        <td><?= htmlspecialchars($row['Drvice_name']) ?></td>
                        <td><?= htmlspecialchars($row['enrol_datex']) ?></td>
                        <td><?= htmlspecialchars($row['device_type']) ?></td>
                        <td><?= htmlspecialchars($row['sn']) ?></td>
                        <td><?= htmlspecialchars($row['manufacture']) ?></td>
                        <td><?= htmlspecialchars($row['model']) ?></td>
                        <td><?= htmlspecialchars($row['usr_name']) ?></td>
                        <td><?= htmlspecialchars($row['skufamily']) ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm edit-btn" 
                                    data-id="<?= $row['id'] ?>"
                                    data-device_name="<?= htmlspecialchars($row['Drvice_name']) ?>"
                                    data-enrol_datex="<?= htmlspecialchars($row['enrol_datex']) ?>"
                                    data-device_type="<?= htmlspecialchars($row['device_type']) ?>"
                                    data-sn="<?= htmlspecialchars($row['sn']) ?>"
                                    data-manufacture="<?= htmlspecialchars($row['manufacture']) ?>"
                                    data-model="<?= htmlspecialchars($row['model']) ?>"
                                    data-usr_name="<?= htmlspecialchars($row['usr_name']) ?>"
                                    data-skufamily="<?= htmlspecialchars($row['skufamily']) ?>">
                                    <i class="fas fa-edit"></i>
                            </button>
                            <button type="submit" name="delete_single" value="<?= $row['id'] ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </form>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Row</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="form-group">
                        <label>Device Name</label>
                        <input type="text" name="Drvice_name" id="edit_device_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Enroll Date</label>
                        <input type="date" name="enrol_datex" id="edit_enrol_datex" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Device Type</label>
                        <input type="text" name="device_type" id="edit_device_type" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Serial Number</label>
                        <input type="text" name="sn" id="edit_sn" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Manufacture</label>
                        <input type="text" name="manufacture" id="edit_manufacture" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Model</label>
                        <input type="text" name="model" id="edit_model" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" name="usr_name" id="edit_usr_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>SKU Family</label>
                        <input type="text" name="skufamily" id="edit_skufamily" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_row" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#inventoryTable').DataTable({
        columnDefs: [
            { orderable: false, targets: 0 }, // Disable sorting for the first column (checkbox)
            { orderable: false, targets: -1 } // Disable sorting for the last column
        ],
        order: [] // Disable initial sorting on any column
    });

    // Handle "Select All" functionality
    $('#select_all').on('click', function() {
        $('input[name="selected_ids[]"]').prop('checked', this.checked);
    });

    // Prevent form submission if no checkboxes are selected
    $('.bulk-delete').on('click', function(e) {
        if ($('input[name="selected_ids[]"]:checked').length === 0) {
            e.preventDefault(); // Prevent form submission
            alert('Please select at least one item to delete.'); // Show alert
        }
    });

    // Fill modal with data for editing
    $('.edit-btn').on('click', function() {
        const data = $(this).data();
        $('#edit_id').val(data.id);
        $('#edit_device_name').val(data.device_name);
        $('#edit_enrol_datex').val(data.enrol_datex);
        $('#edit_device_type').val(data.device_type);
        $('#edit_sn').val(data.sn);
        $('#edit_manufacture').val(data.manufacture);
        $('#edit_model').val(data.model);
        $('#edit_usr_name').val(data.usr_name);
        $('#edit_skufamily').val(data.skufamily);
        $('#editModal').modal('show');
    });
});

</script>
</body>
</html>
