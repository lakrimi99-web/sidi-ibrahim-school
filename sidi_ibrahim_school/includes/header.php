<?php
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="الموقع الرسمي لمدرسة سيدي إبراهيم الرائدة - تقديم تعليم متميز لبناء جيل متعلم ومبدع ومسؤول.">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' . SITE_NAME : SITE_NAME . ' - التعليم المتميز والتربية الرائدة'; ?></title>
    
    <!-- الخطوط العربية من Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- الأيقونات Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- ملف التنسيقات الرئيسي -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- الشريط العلوي للتواصل والمعلومات -->
    <div class="top-bar">
        <div class="container">
            <div class="top-contacts">
                <span><i class="fas fa-phone-alt"></i> الهاتف: +212 522 123 456</span>
                <span><i class="fas fa-envelope"></i> البريد: contact@sidi-ibrahim.edu</span>
                <span><i class="fas fa-clock"></i> أوقات العمل: الاثنين - الجمعة (08:00 - 16:30)</span>
            </div>
            <div>
                <a href="admin/login.php" class="top-admin-link">
                    <i class="fas fa-user-lock"></i> لوحة التحكم
                </a>
            </div>
        </div>
    </div>

    <!-- الترويسة وقائمة التنقل الرئيسية -->
    <header class="main-header">
        <div class="container">
            <nav class="navbar">
                <!-- الشعار واسم المدرسة -->
                <a href="index.php" class="brand-logo">
                    <img src="assets/images/logo.jpg" alt="شعار مدرسة سيدي إبراهيم الرائدة" onerror="this.src='assets/images/hero.jpg'">
                    <div class="brand-title">
                        <span class="brand-name">مدرسة سيدي إبراهيم الرائدة</span>
                        <span class="brand-sub">Sidi Ibrahim Pioneer School</span>
                    </div>
                </a>

                <!-- زر الموبايل -->
                <button class="mobile-toggle" id="mobileToggle" aria-label="فتح القائمة">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- روابط التنقل -->
                <ul class="nav-menu" id="navMenu">
                    <li><a href="index.php" class="nav-link">الرئيسية</a></li>
                    <li><a href="about.php" class="nav-link">من نحن والإدارة</a></li>
                    <li><a href="gallery.php" class="nav-link">الأنشطة والمعرض</a></li>
                    <li><a href="news.php" class="nav-link">الأخبار والإعلانات</a></li>
                    <li><a href="contact.php" class="nav-link">اتصل بنا</a></li>
                    <li>
                        <a href="contact.php#enroll" class="btn btn-primary" style="padding: 0.5rem 1.2rem; font-size: 0.9rem;">
                            <i class="fas fa-user-plus"></i> التسجيل المالي
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
