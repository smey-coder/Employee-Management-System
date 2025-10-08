<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

require_once "../database.php";
$admin_name = $_SESSION['username'] ?? "Admin";

// Function to get employee image path
function getEmployeeImage($imageName) {
    $uploadPath = "../uploads/employees/" . $imageName;
    $defaultImage = "../assets/img/no-image.png";
    
    // Check if image exists and is readable
    if (!empty($imageName) && $imageName !== "default.png" && file_exists($uploadPath) && is_file($uploadPath)) {
        return $uploadPath;
    }
    
    return $defaultImage;
}

// ====== Handle Add Employee ======
if (isset($_POST['add_employee'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = trim($_POST['gender']);
    $address = trim($_POST['address']);
    $hireDate = $_POST['hire_date'];
    $salary = floatval($_POST['salary']);
    $department_id = intval($_POST['department_id']);
    $created_by = intval($_POST['created_by']);
    $created_at = $_POST['created_at'];

    // ===== Handle file upload =====
    $new_image_name = "default.png"; // default
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $upload_dir = "../uploads/employees/";

        // Create upload directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate unique filename
        $file_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $new_image_name = time() . "_" . uniqid() . "." . $file_extension;
        
        // Validate and move uploaded file
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            if (move_uploaded_file($image_tmp, $upload_dir . $new_image_name)) {
                // File uploaded successfully
            } else {
                $new_image_name = "default.png";
                echo "<script>alert('⚠️ Failed to upload image. Default image used.');</script>";
            }
        } else {
            $new_image_name = "default.png";
            echo "<script>alert('⚠️ Invalid file type. Only JPG, JPEG, PNG, GIF allowed.');</script>";
        }
    }

    // ===== Validate required fields =====
    if (
        !empty($name) && !empty($email) && !empty($phone) &&
        !empty($gender) && !empty($address) && !empty($hireDate) &&
        !empty($salary) && !empty($department_id) &&
        !empty($created_by) && !empty($created_at)
    ) {
        $stmt = $conn->prepare("INSERT INTO employees 
            (name, email, phone, gender, address, hire_date, salary, department_id, image, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssssdsiis",
            $name, $email, $phone, $gender, $address, $hireDate,
            $salary, $department_id, $new_image_name, $created_by, $created_at
        );

        if ($stmt->execute()) {
            echo "<script>alert('✅ Employee added successfully!'); window.location='employees.php';</script>";
            exit();
        } else {
            echo "<script>alert('❌ Failed to add employee: " . addslashes($stmt->error) . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('⚠️ Please fill in all required fields.');</script>";
    }
}

// ====== Handle Delete Employee ======
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Delete image file first
    $imgResult = $conn->query("SELECT image FROM employees WHERE id = $id");
    if ($imgResult && $imgResult->num_rows > 0) {
        $imgRow = $imgResult->fetch_assoc();
        if (!empty($imgRow['image']) && $imgRow['image'] !== "default.png") {
            $imgPath = "../uploads/employees/" . $imgRow['image'];
            if (file_exists($imgPath)) {
                unlink($imgPath);
            }
        }
    }

    if ($conn->query("DELETE FROM employees WHERE id = $id")) {
        echo "<script>alert('🗑️ Employee deleted successfully!'); window.location='employees.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to delete employee: " . addslashes($conn->error) . "');</script>";
    }
    exit();
}

// ====== Handle Search ======
$search = "";
$where = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim($_GET['search']);
    $where = "WHERE e.name LIKE '%$search%' OR d.department_name LIKE '%$search%' OR e.email LIKE '%$search%'";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Management</title>
<link rel="stylesheet" href="../assets/css/admin_emp.css">
<style>
.employee-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
}

.employee-form input, .employee-form select, .employee-form button {
    padding: 0.6rem; 
    margin-bottom: 0.5rem; 
    width: 100%; 
    font-size: 1rem;
    border: 1px solid #ced4da;
    border-radius: 4px;
    box-sizing: border-box;
}

.employee-form label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
    color: #495057;
}

.employee-table img.emp-img {
    width: 60px;
    height: 60px;
    border-radius: 6px;
    box-shadow: 0 0 4px rgba(0,0,0,0.2);
    object-fit: cover;
    border: 2px solid #dee2e6;
}

.employee-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background: white;
}

.employee-table th {
    background-color: #053D4E;
    color: white;
    padding: 12px 8px;
    text-align: center;
    font-weight: bold;
}

.employee-table td {
    padding: 10px 8px;
    text-align: center;
    border-bottom: 1px solid #dee2e6;
}

.employee-table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.employee-table tr:hover {
    background-color: #e9ecef;
}

.page-title h2 {
    color: #053D4E;
    margin-bottom: 20px;
    font-size: 24px;
    border-bottom: 2px solid #053D4E;
    padding-bottom: 10px;
}

.search-bar {
    margin: 20px 0;
    display: flex;
    gap: 10px;
    align-items: center;
}

.search-bar input {
    padding: 0.5rem;
    width: 300px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.search-bar button {
    padding: 0.5rem 1rem;
    background: #053D4E;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.search-bar button:hover {
    background: #042a35;
}

.clear-btn {
    padding: 0.5rem 1rem;
    background: #dc3545;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    display: inline-block;
}

.clear-btn:hover {
    background: #c82333;
    color: white;
    text-decoration: none;
}

.delete {
    color: #dc3545;
    text-decoration: none;
    font-weight: bold;
    padding: 5px 10px;
    border: 1px solid #dc3545;
    border-radius: 4px;
    transition: all 0.3s;
}

.delete:hover {
    background: #dc3545;
    color: white;
    text-decoration: none;
}

#imagePreview {
    max-width: 150px;
    max-height: 150px;
    border-radius: 8px;
    border: 2px solid #053D4E;
    margin-top: 10px;
    display: none;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #495057;
}
</style>
</head>
<body>
<?php include "admin_page.php"; ?>

<main>
<div class="page-title"><h2>Employee Management</h2></div>

<!-- Add Employee Form -->
<form class="employee-form" id="employee-form" method="POST" action="" enctype="multipart/form-data">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <div class="form-group">
            <label>Employee Name *</label>
            <input type="text" name="name" placeholder="Employee Name" required>
        </div>
        
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        
        <div class="form-group">
            <label>Phone *</label>
            <input type="tel" name="phone" placeholder="Phone" required>
        </div>
        
        <div class="form-group">
            <label>Gender *</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Address *</label>
            <input type="text" name="address" placeholder="Address" required>
        </div>
        
        <div class="form-group">
            <label>Hire Date *</label>
            <input type="date" name="hire_date" required>
        </div>
        
        <div class="form-group">
            <label>Salary *</label>
            <input type="number" step="0.01" name="salary" placeholder="Salary" required>
        </div>
        
        <div class="form-group">
            <label>Department *</label>
            <select name="department_id" required>
                <option value="">Select Department</option>
                <?php
                $deps = $conn->query("SELECT * FROM departments");
                while ($d = $deps->fetch_assoc()) {
                    echo "<option value='{$d['id']}'>{$d['department_name']}</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Created By *</label>
            <select name="created_by" required>
                <option value="">Select User</option>
                <?php
                $users = $conn->query("SELECT * FROM users");
                while ($u = $users->fetch_assoc()) {
                    echo "<option value='{$u['id']}'>{$u['username']}</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Created At *</label>
            <input type="date" name="created_at" required>
        </div>
    </div>
    
    <div class="form-group" style="grid-column: span 2;">
        <label>Upload Employee Image *</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif" required onchange="previewImage(event)">
        <img id="imagePreview" src="" alt="Image Preview">
        <small style="color: #6c757d;">Accepted formats: JPG, JPEG, PNG, GIF</small>
    </div>
    
    <button type="submit" name="add_employee" style="grid-column: span 2; background: #053D4E; color: white; padding: 12px; font-size: 16px; cursor: pointer;">
        ➕ Add Employee
    </button>
    <button type="button" id="clearFormBtn" style="grid-column: span 2; background: #dc3545; color: white; padding: 12px; font-size: 16px; cursor: pointer;">
    🧹 Clear Data
    </button>
</form>

<!-- Search Bar -->
<form class="search-bar" method="GET" action="">
    <input type="text" name="search" placeholder="🔍 Search by name, department, or email..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
    <?php if ($search): ?>
      <a href="employees.php" class="clear-btn">Clear Search</a>
    <?php endif; ?>
</form>

<!-- Employee Table -->
<table class="employee-table" border="1" cellspacing="0" cellpadding="5">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Image</th>
    <th>Department</th>
    <th>Created at</th>
    <th>Created By</th>
    <th>Actions</th>
</tr>
<?php
$query = "
SELECT e.id, e.name, e.email, e.phone, e.image, d.department_name, e.created_at, u.username 
FROM employees e
LEFT JOIN departments d ON e.department_id = d.id
LEFT JOIN users u ON e.created_by = u.id
$where
ORDER BY e.id DESC
";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $displayPath = getEmployeeImage($row['image']);
        
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td><img src='{$displayPath}' class='emp-img' alt='{$row['name']}' title='{$row['name']}'></td>
                <td>{$row['department_name']}</td>
                <td>{$row['created_at']}</td>
                <td>{$row['username']}</td>
                <td>
                    <a href='?delete={$row['id']}' class='delete' onclick='return confirm(\"Are you sure you want to delete employee: {$row['name']}?\");'>Delete</a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='9' style='text-align:center; color:#64748b; padding: 20px;'>No employees found.</td></tr>";
}
?>
</table>
</main>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = "";
        preview.style.display = "none";
    }
}

// Set default dates to today
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="hire_date"]').value = today;
    document.querySelector('input[name="created_at"]').value = today;
});
document.getElementById('clearFormBtn').onclick = function() {
    const form = document.querySelector('.employee-form');
    form.reset();
    document.getElementById('imagePreview').src = "";
    document.getElementById('imagePreview').style.display = "none";
    // Reset date fields to today
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="hire_date"]').value = today;
    document.querySelector('input[name="created_at"]').value = today;
};
function getEmployeeImage($imageName) {
    $uploadPath = "../uploads/employees/" . $imageName;
    $browserPath = "../uploads/employees/" . $imageName;
    $defaultImage = "../assets/img/no-image.png";
    if (!empty($imageName) && $imageName !== "default.png" && file_exists($uploadPath) && is_file($uploadPath)) {
        return $browserPath;
    }
    return $defaultImage;
}
</script>
</body>
</html>