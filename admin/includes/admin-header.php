<?php
require_once __DIR__ . '/../../config/db.php';

// التحقق من تسجيل دخول المشرف
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$adminName = $_SESSION['admin_name'] ?? 'مدير النظام';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($adminTitle) ? $adminTitle . ' | ' . SITE_NAME : 'لوحة التحكم الإدارية'; ?></title>
    
    <!-- الخطوط والأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background: #F1F5F9;">

<div class="admin-layout">
    
    <!-- القائمة الجانبية للوحة التحكم (Sidebar) -->
    <aside class="admin-sidebar">
        <div class="admin-sidebar-header">
            <img src="../assets/images/logo.jpg" alt="Logo" style="width: 40px; height: 40px; border-radius: 50%;" onerror="this.style.display='none'">
            <div>
                <h4 style="color: #FFF; font-size: 1.1rem; line-height: 1.2;">سيدي إبراهيم</h4>
                <span style="font-size: 0.75rem; color: var(--secondary);">لوحة التحكم والإدارة</span>
            </div>
        </div>

        <nav class="admin-nav">
            <a href="dashboard.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i> الرئيسية والإحصائيات
            </a>
            <a href="add-post.php" class="admin-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'add-post.php' ? 'active' : ''; ?>">
                <i class="fas fa-plus-circle"></i> إضافة مقال / إعلان جديد
            </a>
            <a href="../index.php" target="_blank" class="admin-nav-item">
                <i class="fas fa-external-link-alt"></i> معاينة الموقع العام
            </a>
            <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <a href="logout.php" class="admin-nav-item" style="color: #F87171;">
                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </a>
            </div>
        </nav>
    </aside>

    <!-- المحتوى الرئيسي للوحة التحكم -->
    <div class="admin-main">
        <header class="admin-topbar">
            <div>
                <h3 style="font-size: 1.25rem; color: var(--primary); font-weight: 800;">
                    <?php echo isset($adminTitle) ? $adminTitle : 'إدارة مقالات المدرسة'; ?>
                </h3>
            </div>

            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.6rem; background: var(--light-bg); padding: 0.4rem 0.9rem; border-radius: 20px; border: 1px solid var(--border-color);">
                    <i class="fas fa-user-circle" style="color: var(--primary); font-size: 1.2rem;"></i>
                    <span style="font-size: 0.9rem; font-weight: 700; color: var(--dark);"><?php echo sanitize($adminName); ?></span>
                </div>
                <a href="logout.php" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; color: #DC2626; border-color: #FCA5A5;">
                    <i class="fas fa-power-off"></i> خروج
                </a>
            </div>
        </header>

        <div class="admin-content">
