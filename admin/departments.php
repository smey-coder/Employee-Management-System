<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

require_once "../database.php";

$admin_name = $_SESSION['username'] ?? "Admin";

$department_added = false;
$department_updated = false;
$department_deleted = false;

// ADD DEPARTMENT
if (isset($_POST['add_department'])) {
    $dName = trim($_POST['department_name']);
    $description = trim($_POST['description']);

    if (!empty($dName) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO departments (department_name, description, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $dName, $description);
        if ($stmt->execute()) {
            $department_added = true;
        }
        $stmt->close();
    }
}

// DELETE DEPARTMENT
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM departments WHERE id = $id");
    $department_deleted = true;
}

$result = $conn->query("SELECT * FROM departments ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Departments</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="../assets/css/admin_emp.css">
<style>
body {
  font-family: "Nunito Sans", sans-serif;
  background: #f5f7fa;
  padding: 2rem;
}
.card {
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  padding: 2rem;
  background-color: #f8fafc;
  margin-top: 4.5rem;
  margin-left: 19rem;
  padding: 1.5rem;
  transition: margin-left 0.4s ease;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}
th, td {
  padding: 10px 12px;
  border-bottom: 1px solid #ddd;
}
th {
  background: #1a237e;
  color: white;
}
.btn {
  display: inline-block;
  background: #1a237e;
  color: white;
  padding: 6px 12px;
  border-radius: 5px;
  text-decoration: none;
  font-size: 0.9rem;
}
.btn:hover {
  background: #283593;
}
.btn.edit { background: orange; }
.btn.delete { background: red; }
</style>
</head>
<body>
<?php include "admin_page.php"; ?>
<div class="card">
  <h2>Manage Departments</h2>
  <button class="btn" style="margin-bottom:15px;" onclick="openAddDepartmentForm()">+ Add Department</button>

  <table>
    <tr>
      <th>ID</th>
      <th>Department Name</th>
      <th>Description</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['department_name']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td><?= htmlspecialchars($row['created_at']) ?></td>
        <td>
          <a href="#" class="btn edit"
             onclick="openEditDepartmentForm(<?= $row['id'] ?>, '<?= htmlspecialchars($row['department_name']) ?>', '<?= htmlspecialchars($row['description']) ?>')">
             Edit
          </a>
          <a href="?delete=<?= $row['id'] ?>" class="btn delete" onclick="return confirmDelete(event)">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<!-- Hidden Add Form -->
<form id="addDepartmentForm" method="POST" style="display:none;">
  <input type="hidden" name="add_department" value="1">
  <input type="hidden" name="department_name" id="addDName">
  <input type="hidden" name="description" id="addDescription">
</form>

<script>
function openAddDepartmentForm() {
  Swal.fire({
    title: 'Add New Department',
    html:
      '<input id="swalDName" class="swal2-input" placeholder="Department Name">' +
      '<input id="swalDescription" class="swal2-input" placeholder="Description">',
    showCancelButton: true,
    confirmButtonText: 'Save',
    preConfirm: () => {
      const name = document.getElementById('swalDName').value.trim();
      const desc = document.getElementById('swalDescription').value.trim();
      if (!name || !desc) {
        Swal.showValidationMessage('Please fill in all fields');
        return false;
      }
      document.getElementById('addDName').value = name;
      document.getElementById('addDescription').value = desc;
      document.getElementById('addDepartmentForm').submit();
    }
  });
}

function openEditDepartmentForm(id, name, desc) {
  Swal.fire({
    title: 'Edit Department',
    html:
      `<input id="swalEditName" class="swal2-input" value="${name}" placeholder="Department Name">` +
      `<input id="swalEditDesc" class="swal2-input" value="${desc}" placeholder="Description">`,
    showCancelButton: true,
    confirmButtonText: 'Update',
    preConfirm: () => {
      Swal.fire('Info', 'Edit function not yet implemented in this version.', 'info');
    }
  });
}

function confirmDelete(e) {
  e.preventDefault();
  const link = e.currentTarget.href;
  Swal.fire({
    title: 'Are you sure?',
    text: "This will delete the department permanently.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#e53935',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = link;
    }
  });
}
</script>

<?php if ($department_added): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Department Added',
  text: 'The department has been added successfully!',
  confirmButtonColor: '#1a237e'
});
</script>
<?php endif; ?>

<?php if ($department_deleted): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Department Deleted',
  text: 'The department has been removed successfully!',
  confirmButtonColor: '#1a237e'
});
</script>
<?php endif; ?>
</body>
</html>
