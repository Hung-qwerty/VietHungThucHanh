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

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $u = $_POST['username'] ?? '';
    $n = $_POST['fullname'] ?? '';
    $r = $_POST['role'] ?? 'student';

    if (!empty($u) && !empty($n)) {
        $users[] = ["username" => $u, "name" => $n, "role" => $r];
        $message = "<div class='alert alert-success bg-success text-white border-0'>Thêm tài khoản $u thành công!</div>";
    } else {
        $message = "<div class='alert alert-danger bg-danger text-white border-0'>Vui lòng nhập đầy đủ thông tin!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Tài khoản</title>
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
            <h3 class="mb-1" style="color: var(--hnmu-blue);">HỆ THỐNG QUẢN LÝ PHÒNG THỰC HÀNH</h3>
            <p class="text-dark">Cổng quản lý và phân quyền tài khoản (Bài tập cá nhân - TV1)</p>
        </div>

        <?php echo $message; ?>

        <div class="card card-custom p-4 mb-4">
            <h5 class="text-dark">Thêm mới tài khoản người dùng</h5>
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label text-muted small">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" placeholder="Nhập username..." required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small">Họ và tên</label>
                    <input type="text" name="fullname" class="form-control" placeholder="Nhập họ tên..." required>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">Vai trò</label>
                    <select name="role" class="form-select">
                        <option value="student">Sinh viên</option>
                        <option value="staff">Cán bộ Lab</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary-custom w-100 text-white">Thêm</button>
                </div>
            </form>
        </div>

        <div class="card card-custom p-4">
            <h5 class="text-dark">Danh sách tài khoản trong hệ thống</h5>
            <div class="table-responsive">
                <table class="table table-dark-custom align-middle">
                    <thead>
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
                            <td class="fw-bold text-info"><?php echo htmlspecialchars($user['username']); ?></td>
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