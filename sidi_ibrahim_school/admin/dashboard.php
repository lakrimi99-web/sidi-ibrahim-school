<?php
$adminTitle = 'لوحة التحكم وإدارة المقالات';
require_once __DIR__ . '/includes/admin-header.php';

$db = getDB();
$posts = [];
$totalViews = 0;
$msg = isset($_GET['msg']) ? sanitize($_GET['msg']) : '';

if ($db) {
    try {
        $stmt = $db->query("SELECT p.*, u.full_name as author_name FROM posts p LEFT JOIN users u ON p.author_id = u.id ORDER BY p.created_at DESC");
        $posts = $stmt->fetchAll();

        // حساب إجمالي المشاهدات
        $viewsStmt = $db->query("SELECT SUM(views) as total FROM posts");
        $viewsResult = $viewsStmt->fetch();
        $totalViews = (int)($viewsResult['total'] ?? 0);
    } catch (PDOException $e) {
        $posts = getFallbackPosts();
        $totalViews = 455;
    }
} else {
    $posts = getFallbackPosts();
    $totalViews = 455;
}
?>

<!-- الإشعارات والرسائل التوضيحية -->
<?php if (!empty($msg)): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle" style="font-size: 1.3rem;"></i>
        <div><?php echo $msg; ?></div>
    </div>
<?php endif; ?>

<!-- بطاقات الإحصائيات السريعة -->
<div class="grid grid-3" style="margin-bottom: 2.5rem;">
    <div style="background: #FFF; padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; align-items: center; gap: 1.2rem; box-shadow: var(--shadow-sm);">
        <div class="contact-icon" style="background: var(--primary-light); color: var(--primary);"><i class="fas fa-newspaper"></i></div>
        <div>
            <div style="font-size: 1.8rem; font-weight: 800; color: var(--primary);"><?php echo count($posts); ?></div>
            <div style="color: var(--gray-text); font-size: 0.9rem;">إجمالي المقالات المنشورة</div>
        </div>
    </div>

    <div style="background: #FFF; padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; align-items: center; gap: 1.2rem; box-shadow: var(--shadow-sm);">
        <div class="contact-icon" style="background: var(--secondary-light); color: var(--secondary);"><i class="fas fa-eye"></i></div>
        <div>
            <div style="font-size: 1.8rem; font-weight: 800; color: var(--secondary);"><?php echo number_format($totalViews); ?></div>
            <div style="color: var(--gray-text); font-size: 0.9rem;">إجمالي مشاهدات الزوار</div>
        </div>
    </div>

    <div style="background: #FFF; padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); display: flex; align-items: center; gap: 1.2rem; box-shadow: var(--shadow-sm);">
        <div class="contact-icon" style="background: #FFE4E6; color: #E11D48;"><i class="fas fa-user-shield"></i></div>
        <div>
            <div style="font-size: 1.2rem; font-weight: 800; color: var(--dark);"><?php echo sanitize($adminName); ?></div>
            <div style="color: var(--gray-text); font-size: 0.9rem;">مشرف النظام الحالي</div>
        </div>
    </div>
</div>

<!-- شريط العنوان والعمليات السريعة -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <h3 style="font-size: 1.4rem; color: var(--primary); font-weight: 800;">
        <i class="fas fa-list-alt"></i> قائمة المقالات والأخبار المنشورة
    </h3>
    <a href="add-post.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> إضافة مقال / إعلان جديد
    </a>
</div>

<!-- جدول عرض المقالات -->
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 80px;">الصورة</th>
                <th>عنوان المقال</th>
                <th>الفئة</th>
                <th>تاريخ النشر</th>
                <th>المشاهدات</th>
                <th>الحالة</th>
                <th style="width: 140px; text-align: center;">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><strong><?php echo $post['id']; ?></strong></td>
                        <td>
                            <img src="<?php echo '../' . getPostImageUrl($post['image']); ?>" alt="Thumb" style="width: 55px; height: 42px; object-fit: cover; border-radius: 4px;" onerror="this.src='../assets/images/hero.jpg'">
                        </td>
                        <td>
                            <a href="../post.php?id=<?php echo $post['id']; ?>" target="_blank" style="font-weight: 700; color: var(--dark);">
                                <?php echo sanitize($post['title']); ?>
                            </a>
                        </td>
                        <td>
                            <span class="badge" style="background: var(--primary-light); color: var(--primary);">
                                <?php echo sanitize($post['category'] ?? 'عام'); ?>
                            </span>
                        </td>
                        <td style="font-size: 0.88rem; color: var(--gray-text);">
                            <?php echo date('Y/m/d', strtotime($post['created_at'])); ?>
                        </td>
                        <td>
                            <span style="font-weight: 700; color: var(--secondary);"><i class="far fa-eye"></i> <?php echo (int)($post['views'] ?? 0); ?></span>
                        </td>
                        <td>
                            <?php if (($post['status'] ?? 'published') === 'published'): ?>
                                <span class="badge badge-success">منشور</span>
                            <?php else: ?>
                                <span class="badge badge-warning">مسودة</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 0.4rem; justify-content: center;">
                                <a href="../post.php?id=<?php echo $post['id']; ?>" target="_blank" class="action-btn action-btn-edit" title="معاينة" style="background: #ECFDF5; color: #047857;">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="action-btn action-btn-edit" title="تعديل">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="delete-post.php?id=<?php echo $post['id']; ?>" class="action-btn action-btn-delete confirm-delete" title="حذف">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2rem;">لا توجد مقالات منشورة حالياً. قم بإضافة أول مقال من الزر أعلاه!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
