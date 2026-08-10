<?php
require_once __DIR__ . '/../config/db.php';

// حماية الصفحة والتأكد من جلسة المشرف
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($postId > 0) {
    $db = getDB();
    if ($db) {
        try {
            // 1. جلب اسم الصورة لحذفها من السيرفر إن وجدت
            $stmtImg = $db->prepare("SELECT image FROM posts WHERE id = :id LIMIT 1");
            $stmtImg->execute([':id' => $postId]);
            $post = $stmtImg->fetch();

            if ($post && !empty($post['image']) && $post['image'] !== 'default_post.jpg') {
                $filePath = __DIR__ . '/../uploads/' . $post['image'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            // 2. حذف المقال من السجل
            $stmt = $db->prepare("DELETE FROM posts WHERE id = :id");
            $stmt->execute([':id' => $postId]);

            header('Location: dashboard.php?msg=' . urlencode('تم حذف المقال بنجاح.'));
            exit;
        } catch (PDOException $e) {
            header('Location: dashboard.php?msg=' . urlencode('خطأ أثناء عملية الحذف من قاعدة البيانات.'));
            exit;
        }
    } else {
        header('Location: dashboard.php?msg=' . urlencode('تم معالجة طلب الحذف.'));
        exit;
    }
}

header('Location: dashboard.php');
exit;
