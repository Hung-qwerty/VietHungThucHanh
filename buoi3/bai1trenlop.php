<?php
$name = $email = $subject = $message = "";
$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if (empty($name)) {
        $errors['name'] = "Họ tên không được để trống.";
    }

    if (empty($email)) {
        $errors['email'] = "Email không được để trống.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email không đúng định dạng.";
    }

    $len = mb_strlen($message, 'UTF-8');
    if (empty($message)) {
        $errors['message'] = "Nội dung không được để trống.";
    } elseif ($len < 10 || $len > 500) {
        $errors['message'] = "Nội dung phải từ 10 đến 500 ký tự.";
    }

    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] == UPLOAD_ERR_NO_FILE) {
        $errors['avatar'] = "Vui lòng chọn ảnh đại diện.";
    } else {
        $file = $_FILES['avatar'];
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_exts)) {
            $errors['avatar'] = "Định dạng file không hợp lệ (chỉ chấp nhận JPG, PNG, GIF)."; 
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $errors['avatar'] = "Kích thước file không được vượt quá 2MB.";
        }
    }


    if (empty($errors)) {
     
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $new_file_name = time() . '_' . basename($_FILES['avatar']['name']);
        move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_dir . $new_file_name);

        $success = "Gửi thông tin liên hệ thành công!";

        $name = $email = $subject = $message = "";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Form Liên Hệ</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; padding: 30px; }
        .contact-card { background: #fff; width: 450px; padding: 25px 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .contact-card h2 { text-align: center; color: #1e4b7a; margin-bottom: 5px; }
        .contact-card p.subtitle { text-align: center; color: #666; font-size: 13px; margin-top: 0; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; color: #333; }
        .form-group input[type="text"], .form-group select, .form-group textarea, .form-group input[type="file"] {
            width: 100%; padding: 9px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;
        }
        .form-group textarea { height: 90px; resize: vertical; }
        .error { color: #d93025; font-size: 12px; margin-top: 4px; display: block; }
        .alert-success { background: #e6f4ea; color: #137333; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; font-size: 14px; }
        .btn-submit { width: 100%; background-color: #1976d2; color: #fff; border: none; padding: 10px; border-radius: 4px; font-size: 15px; font-weight: bold; cursor: pointer; }
        .btn-submit:hover { background-color: #1565c0; }
    </style>
</head>
<body>

<div class="contact-card">
    <h2>Liên hệ</h2>
    <p class="subtitle">Vui lòng nhập đầy đủ thông tin bên dưới.</p>

    <?php if (!empty($success)): ?>
        <div class="alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Họ tên</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <?php if (isset($errors['name'])): ?><span class="error"><?php echo $errors['name']; ?></span><?php endif; ?>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php if (isset($errors['email'])): ?><span class="error"><?php echo $errors['email']; ?></span><?php endif; ?>
        </div>

        <div class="form-group">
            <label>Chủ đề</label>
            <select name="subject">
                <option value="Hỗ trợ kỹ thuật" <?php echo ($subject == 'Hỗ trợ kỹ thuật') ? 'selected' : ''; ?>>Hỗ trợ kỹ thuật</option>
                <option value="Tư vấn dịch vụ" <?php echo ($subject == 'Tư vấn dịch vụ') ? 'selected' : ''; ?>>Tư vấn dịch vụ</option>
                <option value="Khác" <?php echo ($subject == 'Khác') ? 'selected' : ''; ?>>Khác</option>
            </select>
        </div>

        <div class="form-group">
            <label>Ảnh đại diện</label>
            <input type="file" name="avatar" accept="image/*">
            <?php if (isset($errors['avatar'])): ?><span class="error"><?php echo $errors['avatar']; ?></span><?php endif; ?>
        </div>

        <div class="form-group">
            <label>Nội dung</label>
            <textarea name="message" placeholder="Nhập nội dung liên hệ..."><?php echo htmlspecialchars($message); ?></textarea>
            <?php if (isset($errors['message'])): ?>
                <span class="error"><?php echo $errors['message']; ?></span>
            <?php else: ?>
                <span style="font-size: 12px; color: #888;">Nội dung phải từ 10 đến 500 ký tự.</span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">Gửi liên hệ</button>
    </form>
</div>

</body>
</html>