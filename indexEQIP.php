<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
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
    $delete_sql = "DELETE FROM networksallsites WHERE index_no IN ($ids)";
    $conn->query($delete_sql);
}

// Handle deletion of a single row
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_single'])) {
    $id = intval($_POST['delete_single']); // Sanitize the input
    $delete_sql = "DELETE FROM networksallsites WHERE index_no = $id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Record deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}

// Handle update row
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_row'])) {
    $id = intval($_POST['edit_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $address = $conn->real_escape_string($_POST['address']);
    $networktype = $conn->real_escape_string($_POST['networktype']);
    $no_of_devices = intval($_POST['no_of_devices']);
    $offline_devices = intval($_POST['offline_devices']);
    $offlinepercentage = floatval($_POST['offlinepercentage']);
    $clients = intval($_POST['clients']);
    $trafficusage = $conn->real_escape_string($_POST['trafficusage']);
    $firmware_update = $conn->real_escape_string($_POST['firmware_update']);

    $update_sql = "UPDATE networksallsites SET 
        name='$name', 
        address='$address', 
        networktype='$networktype', 
        no_of_devices=$no_of_devices, 
        offline_devices=$offline_devices, 
        offlinepercentage=$offlinepercentage, 
        clients=$clients, 
        trafficusage='$trafficusage', 
        firmware_update='$firmware_update' 
        WHERE index_no=$id";
    $conn->query($update_sql);
}

// Fetch data for the table
$sql = "SELECT * FROM networksallsites";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Network Devices</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to your custom CSS -->
</head>
<body>

<?php include 'logo.php'; ?>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Network Devices</h2>
    <form method="POST">
        <button type="submit" name="delete_selected" class="btn btn-danger bulk-delete" style="margin: 20px 0">Delete Selected</button>
        <table id="deviceTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select_all"></th>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>ADDRESS</th>
                    <th>NETWORK TYPE</th>
                    <th>DEVICES</th>
                    <th>OFFLINE</th>
                    <th>OFFLINE (%)</th>
                    <th>CUSTOMERS</th>
                    <th>USAGE</th>
                    <th>FIRMWARE</th>
                    <th style="width:80px;">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><input type="checkbox" name="selected_ids[]" value="<?= $row['index_no'] ?>"></td>
                        <td><?= htmlspecialchars($row['index_no']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= htmlspecialchars($row['networktype']) ?></td>
                        <td><?= htmlspecialchars($row['no_of_devices']) ?></td>
                        <td><?= htmlspecialchars($row['offline_devices']) ?></td>
                        <td><?= htmlspecialchars($row['offlinepercentage']) ?></td>
                        <td><?= htmlspecialchars($row['clients']) ?></td>
                        <td><?= htmlspecialchars($row['trafficusage']) ?></td>
                        <td><?= htmlspecialchars($row['firmware_update']) ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm edit-btn" 
                                    data-id="<?= $row['index_no'] ?>"
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-address="<?= htmlspecialchars($row['address']) ?>"
                                    data-networktype="<?= htmlspecialchars($row['networktype']) ?>"
                                    data-no_of_devices="<?= htmlspecialchars($row['no_of_devices']) ?>"
                                    data-offline_devices="<?= htmlspecialchars($row['offline_devices']) ?>"
                                    data-offlinepercentage="<?= htmlspecialchars($row['offlinepercentage']) ?>"
                                    data-clients="<?= htmlspecialchars($row['clients']) ?>"
                                    data-trafficusage="<?= htmlspecialchars($row['trafficusage']) ?>"
                                    data-firmware_update="<?= htmlspecialchars($row['firmware_update']) ?>">
                                    <i class="fas fa-edit"></i>
                            </button>
                            <button type="submit" name="delete_single" value="<?= $row['index_no'] ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
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
                        <label>Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" id="edit_address" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Network Type</label>
                        <input type="text" name="networktype" id="edit_networktype" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>No. of Devices</label>
                        <input type="number" name="no_of_devices" id="edit_no_of_devices" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Offline Devices</label>
                        <input type="number" name="offline_devices" id="edit_offline_devices" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Offline (%)</label>
                        <input type="number" step="0.01" name="offlinepercentage" id="edit_offlinepercentage" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Customers</label>
                        <input type="number" name="clients" id="edit_clients" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Traffic Usage</label>
                        <input type="text" name="trafficusage" id="edit_trafficusage" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Firmware Update</label>
                        <input type="date" name="firmware_update" id="edit_firmware_update" class="form-control">
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
    $('#deviceTable').DataTable({
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
            e.preventDefault();
            alert('Please select at least one item to delete.');
        }
    });

    // Fill modal with data for editing
    $('.edit-btn').on('click', function() {
        const data = $(this).data();
        $('#edit_id').val(data.id);
        $('#edit_name').val(data.name);
        $('#edit_address').val(data.address);
        $('#edit_networktype').val(data.networktype);
        $('#edit_no_of_devices').val(data.no_of_devices);
        $('#edit_offline_devices').val(data.offline_devices);
        $('#edit_offlinepercentage').val(data.offlinepercentage);
        $('#edit_clients').val(data.clients);
        $('#edit_trafficusage').val(data.trafficusage);
        $('#edit_firmware_update').val(data.firmware_update);
        $('#editModal').modal('show');
    });
});
</script>
</body>
</html>
