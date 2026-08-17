<?php
$users = [
    ["username" => "admin", "name" => "Quản Trị Viên", "role" => "admin"],
    ["username" => "staff01", "name" => "Cán Bộ Lab A", "role" => "staff"]
];

function getRoleBadge($role) {
    if ($role == 'admin') return '<span class="badge bg-danger">Quản trị viên</span>';
    if ($role == 'staff') return '<span class="badge bg-warning text-dark">Cán bộ Lab</span>';
    return '<span class="badge bg-primary">Sinh viên</span>';
}

$errors = [];
$success_msg = "";
$u = "";
$n = "";
$r = "student";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u = trim($_POST['username'] ?? '');
    $n = trim($_POST['fullname'] ?? '');
    $r = $_POST['role'] ?? 'student';

    if (empty($u)) {
        $errors['username'] = "Tên đăng nhập không được để trống!";
    } elseif (strlen($u) < 3) {
        $errors['username'] = "Tên đăng nhập phải có ít nhất 3 ký tự!";
    }

    if (empty($n)) {
        $errors['fullname'] = "Họ và tên không được để trống!";
    } elseif (!preg_match('/^[a-zA-ZÀ-ỹ\s]+$/u', $n)) {
        $errors['fullname'] = "Họ và tên chỉ được chứa chữ cái và khoảng trắng, không được chứa số hoặc ký tự đặc biệt!";
    }

    if (empty($errors)) {
        $users[] = ["username" => $u, "name" => $n, "role" => $r];
        $success_msg = "Thêm tài khoản thành công!";
        $u = ""; 
        $n = "";
        $r = "student";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Tài khoản - Buổi 3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
        background-color: #f4f7f9; 
        color: #333;
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    .card-custom {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    :root {
        --hnmu-blue: #003399;
    }
    .btn-primary-custom {
        background-color: var(--hnmu-blue);
        border: none;
        color: white;
    }
    .btn-primary-custom:hover {
        background-color: #002266;
    }
    h3 { color: var(--hnmu-blue); }
    .table-custom thead {
        background-color: var(--hnmu-blue);
        color: white;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--hnmu-blue);
        box-shadow: 0 0 0 0.25rem rgba(0, 51, 153, 0.15);
    }
</style>
</head>
<body class="py-5">
    <div class="container" style="max-width: 900px;">
        
        <div class="card card-custom p-4 mb-4">
            <h3 class="mb-1">HỆ THỐNG QUẢN LÝ PHÒNG THỰC HÀNH</h3>
            <p class="text-dark mb-0">Cổng quản lý và phân quyền tài khoản (Bài tập cá nhân - Buổi 3)</p>
        </div>

        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success border-0 shadow-sm mb-4"><?php echo $success_msg; ?></div>
        <?php endif; ?>

        <div class="card card-custom p-4 mb-4">
            <h5 class="text-dark mb-3">Thêm mới tài khoản người dùng</h5>
            <form method="POST" class="row g-3" novalidate>
                
                <div class="col-md-4">
                    <label class="form-label text-muted small">Tên đăng nhập</label>
                    <input type="text" name="username" 
                           class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo htmlspecialchars($u); ?>" 
                           placeholder="Nhập username...">
                    <?php if (isset($errors['username'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-muted small">Họ và tên</label>
                    <input type="text" name="fullname" 
                           class="form-control <?php echo isset($errors['fullname']) ? 'is-invalid' : ''; ?>" 
                           value="<?php echo htmlspecialchars($n); ?>" 
                           placeholder="Nhập họ tên...">
                    <?php if (isset($errors['fullname'])): ?>
                        <div class="invalid-feedback"><?php echo $errors['fullname']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="col-md-2">
                    <label class="form-label text-muted small">Vai trò</label>
                    <select name="role" class="form-select">
                        <option value="student" <?php echo ($r == 'student') ? 'selected' : ''; ?>>Sinh viên</option>
                        <option value="staff" <?php echo ($r == 'staff') ? 'selected' : ''; ?>>Cán bộ Lab</option>
                        <option value="admin" <?php echo ($r == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary-custom w-100 text-white">Thêm</button>
                </div>
            </form>
        </div>

        <div class="card card-custom p-4">
            <h5 class="text-dark mb-3">Danh sách tài khoản trong hệ thống</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>STT</th>
                            <th>Username</th>
                            <th>Họ tên</th>
                            <th>Vai trò</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo getRoleBadge($user['role']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>