<?php
$adminTitle = 'تعديل مقال منشور';
require_once __DIR__ . '/includes/admin-header.php';

$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$db = getDB();
$post = null;
$error = '';

if ($db && $postId > 0) {
    try {
        $stmt = $db->prepare("SELECT * FROM posts WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $postId]);
        $post = $stmt->fetch();
    } catch (PDOException $e) {
        $post = null;
    }
}

if (!$post) {
    $fallback = getFallbackPosts();
    foreach ($fallback as $f) {
        if ($f['id'] == $postId) {
            $post = $f;
            break;
        }
    }
    if (!$post) {
        $post = $fallback[0];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim(sanitize($_POST['title'])) : '';
    $category = isset($_POST['category']) ? trim(sanitize($_POST['category'])) : 'أخبار عامة';
    $excerpt = isset($_POST['excerpt']) ? trim(sanitize($_POST['excerpt'])) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $status = isset($_POST['status']) ? sanitize($_POST['status']) : 'published';
    $currentImage = isset($_POST['current_image']) ? sanitize($_POST['current_image']) : 'default_post.jpg';

    if (empty($title) || empty($content)) {
        $error = 'يرجى ملء عنوان المقال والمحتوى الرئيسي.';
    } else {
        $imageName = $currentImage;

        // معالجة رفع صورة جديدة إذا تم تحديدها
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                if ($fileSize <= 5 * 1024 * 1024) {
                    $newFileName = 'post_' . time() . '_' . rand(100, 999) . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../uploads/';
                    $destPath = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $imageName = $newFileName;
                    }
                } else {
                    $error = 'حجم الصورة كبير جداً.';
                }
            } else {
                $error = 'صيغة الصورة غير مدعومة.';
            }
        }

        if (empty($error)) {
            if ($db) {
                try {
                    $sql = "UPDATE posts SET title = :title, category = :category, excerpt = :excerpt, content = :content, image = :image, status = :status WHERE id = :id";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([
                        ':title'    => $title,
                        ':category' => $category,
                        ':excerpt'  => $excerpt,
                        ':content'  => $content,
                        ':image'    => $imageName,
                        ':status'   => $status,
                        ':id'       => $postId
                    ]);

                    header('Location: dashboard.php?msg=' . urlencode('تم تحديث المقال بنجاح!'));
                    exit;
                } catch (PDOException $e) {
                    $error = 'خطأ أثناء تحديث قاعدة البيانات: ' . $e->getMessage();
                }
            } else {
                header('Location: dashboard.php?msg=' . urlencode('تم تحديث المقال في وضع المعاينة المؤقت!'));
                exit;
            }
        }
    }
}
?>

<div style="background: #FFF; padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); max-width: 900px; margin: 0 auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
        <h2 style="font-size: 1.5rem; color: var(--primary); font-weight: 800;">
            <i class="fas fa-edit" style="color: var(--secondary);"></i> تعديل المقال رقم #<?php echo $post['id']; ?>
        </h2>
        <a href="dashboard.php" class="btn btn-outline" style="color: var(--dark-muted); border-color: var(--border-color);">
            <i class="fas fa-arrow-right"></i> إلغاء ورجوع
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div><?php echo $error; ?></div>
        </div>
    <?php endif; ?>

    <form action="edit-post.php?id=<?php echo $postId; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="current_image" value="<?php echo sanitize($post['image'] ?? ''); ?>">

        <div class="form-group">
            <label class="form-label">عنوان المقال *</label>
            <input type="text" name="title" required value="<?php echo sanitize($post['title']); ?>" class="form-control">
        </div>

        <div class="grid grid-2" style="gap: 1.2rem;">
            <div class="form-group">
                <label class="form-label">الفئة / التصنيف *</label>
                <select name="category" class="form-control">
                    <option value="أخبار عامة" <?php echo ($post['category'] ?? '') === 'أخبار عامة' ? 'selected' : ''; ?>>أخبار عامة</option>
                    <option value="الأنشطة المدرسية" <?php echo ($post['category'] ?? '') === 'الأنشطة المدرسية' ? 'selected' : ''; ?>>الأنشطة المدرسية</option>
                    <option value="تكريم وتفوق" <?php echo ($post['category'] ?? '') === 'تكريم وتفوق' ? 'selected' : ''; ?>>تكريم وتفوق</option>
                    <option value="إعلانات الإدارة" <?php echo ($post['category'] ?? '') === 'إعلانات الإدارة' ? 'selected' : ''; ?>>إعلانات الإدارة</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">حالة النشر *</label>
                <select name="status" class="form-control">
                    <option value="published" <?php echo ($post['status'] ?? '') === 'published' ? 'selected' : ''; ?>>منشور مباشرة</option>
                    <option value="draft" <?php echo ($post['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>مسودة</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">الملخص القصير (Excerpt)</label>
            <textarea name="excerpt" class="form-control" style="min-height: 80px;"><?php echo sanitize($post['excerpt'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">المحتوى الكامل للمقال *</label>
            <textarea name="content" required class="form-control" style="min-height: 220px;"><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>

        <div class="form-group" style="background: var(--light-bg); padding: 1.5rem; border-radius: var(--radius-sm); border: 1px dashed var(--border-color);">
            <label class="form-label">تغيير الصورة البارزة (اختياري)</label>
            <div style="display: flex; gap: 1.5rem; align-items: center; margin-bottom: 1rem;">
                <div>
                    <span style="font-size: 0.85rem; color: var(--gray-text); display: block; margin-bottom: 0.3rem;">الصورة الحالية:</span>
                    <img src="<?php echo '../' . getPostImageUrl($post['image']); ?>" alt="Current" style="width: 100px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color);" onerror="this.src='../assets/images/hero.jpg'">
                </div>
                <div style="flex-grow: 1;">
                    <input type="file" name="image" id="postImageInput" accept="image/*" class="form-control" style="background: #FFF;">
                </div>
            </div>
            <img id="postImagePreview" src="#" alt="معاينة الصورة الجديدة" style="max-height: 160px; border-radius: var(--radius-sm); display: none;">
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem;">
                <i class="fas fa-save"></i> حفظ التعديلات
            </button>
        </div>

    </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
