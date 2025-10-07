<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

require_once "../database.php";

$admin_name = $_SESSION['username'] ?? "Admin";

// Sample data queries (You can adjust table names)
$total_employees = $conn->query("SELECT COUNT(*) AS total FROM employees")->fetch_assoc()['total'] ?? 0;
$total_departments = $conn->query("SELECT COUNT(*) AS total FROM departments")->fetch_assoc()['total'] ?? 0;
$total_attendance = $conn->query("SELECT COUNT(*) AS total FROM attendance")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
  <?php include "admin_page.php"; ?>

  <main>
    <h2 class="dashboard-title">Welcome, <?= htmlspecialchars($admin_name) ?> 👋</h2>

    <div class="dashboard-cards">
      <div class="dashboard-card">
        <h3>Total Employees</h3>
        <p><?= $total_employees ?></p>
      </div>

      <div class="dashboard-card">
        <h3>Total Departments</h3>
        <p><?= $total_departments ?></p>
      </div>

      <div class="dashboard-card">
        <h3>Attendance Records</h3>
        <p><?= $total_attendance ?></p>
      </div>
    </div>

    <div class="dashboard-table">
      <h3>Recent Employees</h3>
      <table>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Department</th>
          <th>Joined Date</th>
        </tr>
        <?php
        $result = $conn->query("SELECT e.id, e.name, d.department_name, e.created_at 
                                FROM employees e 
                                LEFT JOIN departments d ON e.department_id = d.id 
                                ORDER BY e.id DESC LIMIT 5");
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['department_name']}</td>
                        <td>{$row['created_at']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No employee records found.</td></tr>";
        }
        ?>
      </table>
    </div>
  </main>

  <script src="../assets/js/admin.js"></script>
</body>
</html>
