<?php
require_once "../database.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$dateFilter = $_GET['date'] ?? date('Y-m-d');

if (isset($_POST['add_attendance'])) {
    $employee_id = (int)$_POST['employee_id'];
    $attendance_date = $_POST['attendance_date'];
    $status = $_POST['status'];

    // Prevent duplicate attendance for same employee and date
    $check = $conn->prepare("SELECT id FROM attendance WHERE employee_id = ? AND date = ?");
    $check->bind_param("is", $employee_id, $attendance_date);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {
        $insert = $conn->prepare("INSERT INTO attendance (employee_id, status, date) VALUES (?, ?, ?)");
        $insert->bind_param("iss", $employee_id, $status, $attendance_date);
        $insert->execute();
        $insert->close();
        echo "<script>window.location='attendances.php?date={$attendance_date}';</script>";
        exit();
    } else {
        echo "<script>alert('Attendance for this employee on this date already exists!');</script>";
    }
    $check->close();
}
// Fetch attendance data
$sql = "SELECT a.id, e.name AS employee_name, a.status, a.date
        FROM attendance a
        JOIN employees e ON a.employee_id = e.id
        WHERE a.date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dateFilter);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendances</title>
    <link rel="stylesheet" href="../assets/css/attendance.css">
</head>
<body>
<?php include "admin_page.php"; ?>
<div class="card">
    <h2>Attendance Management</h2>

    <!-- Search by Date -->
    <form method="GET" class="filter-form">
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($dateFilter) ?>">
        <button type="submit" class="btn-search">Search</button>
    </form>
    <!-- Form add attendance -->
    <form method="POST" class="add-attendance-form" style="margin-bottom: 20px;">
    <label for="employee_id">Employee:</label>
    <select name="employee_id" id="employee_id" required>
        <option value="">Select Employee</option>
        <?php
        $empRes = $conn->query("SELECT id, name FROM employees ORDER BY name");
        while ($emp = $empRes->fetch_assoc()) {
            echo "<option value='{$emp['id']}'>{$emp['name']}</option>";
        }
        ?>
    </select>
    <label for="attendance_date">Date:</label>
    <input type="date" name="attendance_date" id="attendance_date" value="<?= htmlspecialchars($dateFilter) ?>" required>
    <label for="status">Status:</label>
    <select name="status" id="status" required>
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
        <option value="Leave">Leave</option>
    </select>
    <button type="submit" name="add_attendance" class="btn-add">Add Attendance</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['employee_name']) ?></td>
                    <td>
                        <span class="status 
                            <?= strtolower($row['status']) ?>">
                            <?= htmlspecialchars($row['status']) ?>
                        </span>
                    </td>
                    <td><?= $row['date'] ?></td>
                    <td>
                        <a href="?action=present&id=<?= $row['id'] ?>&date=<?= $dateFilter ?>" class="btn-status present">Present</a>
                        <a href="?action=absent&id=<?= $row['id'] ?>&date=<?= $dateFilter ?>" class="btn-status absent">Absent</a>
                        <a href="?action=leave&id=<?= $row['id'] ?>&date=<?= $dateFilter ?>" class="btn-status leave">Leave</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No records found for <?= $dateFilter ?></td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
// Update attendance status action
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $id = (int)$_GET['id'];
    $validStatus = ['present', 'absent', 'leave'];

    if (in_array($action, $validStatus)) {
        $status = ucfirst($action);
        $update = $conn->prepare("UPDATE attendance SET status = ? WHERE id = ?");
        $update->bind_param("si", $status, $id);
        $update->execute();
        echo "<script>window.location='attendance.php?date={$dateFilter}';</script>";
        exit();
    }
}
?>
