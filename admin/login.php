<?php
require_once __DIR__ . '/../config/db.php';

// إذا كان المشرف مسجلاً الدخول بالفعل، نقوم بإعادة توجيهه تلقائياً إلى Dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim(sanitize($_POST['username'])) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($username) && !empty($password)) {
        $db = getDB();
        $authenticated = false;

        if ($db) {
            try {
                $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
                $stmt->execute([':username' => $username]);
                $user = $stmt->fetch();

                if ($user && (password_verify($password, $user['password']) || ($username === 'sidibrahim' && $password === '159'))) {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_name'] = $user['full_name'] ?? 'مدير النظام';
                    $_SESSION['admin_username'] = $user['username'];
                    $authenticated = true;
                }
            } catch (PDOException $e) {
                // الاعتماد الفوري
            }
        }

        // تسجيل الدخول بالبيانات الجديدة المطلوبة (sidibrahim / 159)
        if (!$authenticated && $username === 'sidibrahim' && $password === '159') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = 1;
            $_SESSION['admin_name'] = 'مدير النظام (سيدي إبراهيم)';
            $_SESSION['admin_username'] = 'sidibrahim';
            $authenticated = true;
        }

        if ($authenticated) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'اسم المستخدم أو كلمة المرور غير صحيحة!';
        }
    } else {
        $error = 'يرجى إدخال اسم المستخدم وكلمة المرور.';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة تحكم مدرسة سيدي إبراهيم الرائدة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-login-wrapper">

    <div class="admin-login-box">
        <div style="text-align: center; margin-bottom: 2rem;">
            <img src="../assets/images/logo.jpg" alt="Logo" style="width: 75px; height: 75px; border-radius: 50%; box-shadow: var(--shadow-sm); margin-bottom: 0.8rem;" onerror="this.src='../assets/images/hero.jpg'">
            <h2 style="color: var(--primary); font-size: 1.6rem; font-weight: 800;">تسجيل دخول الإدارة المحمي</h2>
            <p style="color: var(--gray-text); font-size: 0.9rem; margin-top: 0.2rem;">مدرسة سيدي إبراهيم الرائدة</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <div><?php echo $error; ?></div>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-user"></i> اسم المستخدم (Username)</label>
                <input type="text" name="username" required value="sidibrahim" class="form-control" placeholder="اسم المستخدم">
            </div>

            <div class="form-group" style="margin-bottom: 1.8rem;">
                <label class="form-label"><i class="fas fa-lock"></i> كلمة المرور (Password)</label>
                <input type="password" name="password" required value="159" class="form-control" placeholder="كلمة المرور">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1.05rem;">
                <i class="fas fa-sign-in-alt"></i> الدخول للوحة التحكم
            </button>
        </form>

        <div style="margin-top: 1.8rem; text-align: center; font-size: 0.85rem; color: var(--gray-text); background: var(--light-bg); padding: 0.8rem; border-radius: var(--radius-sm);">
            <strong>بيانات الدخول المعتمدة للإدارة:</strong><br>
            اسم المستخدم: <code>sidibrahim</code> | كلمة المرور: <code>159</code>
        </div>

        <div style="margin-top: 1.5rem; text-align: center;">
            <a href="../index.php" style="font-size: 0.9rem; color: var(--primary); font-weight: 700;">
                <i class="fas fa-arrow-right"></i> العودة للواجهة الرئيسية للموقع
            </a>
        </div>
    </div>

</body>
</html>
