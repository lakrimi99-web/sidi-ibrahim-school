<?php
$adminTitle = 'إضافة مقال جديد';
require_once __DIR__ . '/includes/admin-header.php';

$error = '';
$title = '';
$category = 'أخبار عامة';
$excerpt = '';
$content = '';
$status = 'published';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim(sanitize($_POST['title'])) : '';
    $category = isset($_POST['category']) ? trim(sanitize($_POST['category'])) : 'أخبار عامة';
    $excerpt = isset($_POST['excerpt']) ? trim(sanitize($_POST['excerpt'])) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : ''; // السماح بنسق HTML المحرر
    $status = isset($_POST['status']) ? sanitize($_POST['status']) : 'published';

    if (empty($title) || empty($content)) {
        $error = 'يرجى ملء عنوان المقال والمحتوى الرئيسي على الأقل.';
    } else {
        $imageName = 'default_post.jpg';

        // 1. معالجة رفع الصورة البارزة (Image Uploading)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($fileExtension, $allowedExtensions)) {
                if ($fileSize <= 5 * 1024 * 1024) { // حد أقصى 5 ميجابايت
                    $newFileName = 'post_' . time() . '_' . rand(100, 999) . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../uploads/';

                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }

                    $destPath = $uploadFileDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $destPath)) {
                        $imageName = $newFileName;
                    } else {
                        $error = 'حدث خطأ أثناء نقل الصورة المرفوعة إلى المجلد.';
                    }
                } else {
                    $error = 'حجم الصورة كبير جداً. الحد الأقصى المسموح به هو 5 ميجابايت.';
                }
            } else {
                $error = 'صيغة الصورة غير مدعومة. يرجى اختيار صورة بصيغة (JPG, PNG, WEBP).';
            }
        }

        // 2. إدخال المقال في قاعدة البيانات إذا لم توجد أخطاء
        if (empty($error)) {
            $db = getDB();
            $slug = generateSlug($title);
            $authorId = $_SESSION['admin_id'] ?? 1;

            if ($db) {
                try {
                    $sql = "INSERT INTO posts (title, slug, category, excerpt, content, image, status, author_id, created_at) 
                            VALUES (:title, :slug, :category, :excerpt, :content, :image, :status, :author_id, NOW())";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([
                        ':title'     => $title,
                        ':slug'      => $slug,
                        ':category'  => $category,
                        ':excerpt'   => $excerpt,
                        ':content'   => $content,
                        ':image'     => $imageName,
                        ':status'    => $status,
                        ':author_id' => $authorId
                    ]);

                    header('Location: dashboard.php?msg=' . urlencode('تم إضافة المقال ونشره بنجاح!'));
                    exit;
                } catch (PDOException $e) {
                    $error = 'خطأ أثناء الإضافة بقاعدة البيانات: ' . $e->getMessage();
                }
            } else {
                header('Location: dashboard.php?msg=' . urlencode('تم إضافة المقال في وضع المعاينة المؤقت!'));
                exit;
            }
        }
    }
}
?>

<div style="background: #FFF; padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); max-width: 900px; margin: 0 auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
        <h2 style="font-size: 1.5rem; color: var(--primary); font-weight: 800;">
            <i class="fas fa-edit" style="color: var(--secondary);"></i> إضافة مقال / إعلان جديد
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

    <form action="add-post.php" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label class="form-label">عنوان المقال *</label>
            <input type="text" name="title" required value="<?php echo sanitize($title); ?>" class="form-control" placeholder="أدخل عنوان المقال أو الإعلان بشكل واضح">
        </div>

        <div class="grid grid-2" style="gap: 1.2rem;">
            <div class="form-group">
                <label class="form-label">الفئة / التصنيف *</label>
                <select name="category" class="form-control">
                    <option value="أخبار عامة" <?php echo $category === 'أخبار عامة' ? 'selected' : ''; ?>>أخبار عامة</option>
                    <option value="الأنشطة المدرسية" <?php echo $category === 'الأنشطة المدرسية' ? 'selected' : ''; ?>>الأنشطة المدرسية</option>
                    <option value="تكريم وتفوق" <?php echo $category === 'تكريم وتفوق' ? 'selected' : ''; ?>>تكريم وتفوق</option>
                    <option value="إعلانات الإدارة" <?php echo $category === 'إعلانات الإدارة' ? 'selected' : ''; ?>>إعلانات الإدارة</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">حالة النشر *</label>
                <select name="status" class="form-control">
                    <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>منشور مباشرة</option>
                    <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>مسودة (حفظ فقط)</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">ملخص قصير عن المقال (Excerpt)</label>
            <textarea name="excerpt" class="form-control" style="min-height: 80px;" placeholder="سطران يشرحان باختصار مضمون الخبر لعرضهما بالواجهة الرئيسية..."><?php echo sanitize($excerpt); ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">المحتوى الكامل للمقال (Textarea / HTML) *</label>
            <textarea name="content" required class="form-control" style="min-height: 220px;" placeholder="اكتب التفاصيل الكاملة للمقال هنا... يمكنك استخدام فقرات HTML مثل &lt;p&gt; و &lt;strong&gt;"><?php echo htmlspecialchars($content); ?></textarea>
        </div>

        <div class="form-group" style="background: var(--light-bg); padding: 1.5rem; border-radius: var(--radius-sm); border: 1px dashed var(--border-color);">
            <label class="form-label"><i class="fas fa-image" style="color: var(--secondary);"></i> رفع الصورة البارزة (Featured Image Upload)</label>
            <input type="file" name="image" id="postImageInput" accept="image/*" class="form-control" style="background: #FFF; padding: 0.5rem;">
            <small style="color: var(--gray-text); display: block; margin-top: 0.5rem;">الصيغ المدعومة: JPG, PNG, WEBP. الحد الأقصى 5 ميجابايت.</small>
            
            <!-- معاينة الصورة المرفوعة -->
            <div style="margin-top: 1rem;">
                <img id="postImagePreview" src="#" alt="معاينة الصورة" style="max-height: 180px; border-radius: var(--radius-sm); display: none; border: 1px solid var(--border-color);">
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.05rem;">
                <i class="fas fa-check-circle"></i> حفظ ونشر المقال الآن
            </button>
        </div>

    </form>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
