<?php
$pageTitle = 'الأخبار والإعلانات المدرسية';
require_once __DIR__ . '/includes/header.php';

$db = getDB();
$posts = [];
$searchQuery = isset($_GET['q']) ? trim(sanitize($_GET['q'])) : '';
$categoryFilter = isset($_GET['cat']) ? trim(sanitize($_GET['cat'])) : '';

if ($db) {
    try {
        $sql = "SELECT * FROM posts WHERE status = 'published'";
        $params = [];

        if (!empty($searchQuery)) {
            $sql .= " AND (title LIKE :search OR content LIKE :search OR excerpt LIKE :search)";
            $params[':search'] = "%{$searchQuery}%";
        }

        if (!empty($categoryFilter)) {
            $sql .= " AND category = :cat";
            $params[':cat'] = $categoryFilter;
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $posts = $stmt->fetchAll();
    } catch (PDOException $e) {
        $posts = getFallbackPosts();
    }
} else {
    $posts = getFallbackPosts();
}
?>

<!-- الترويسة الرئيسية لصفحة الأخبار -->
<div style="background: linear-gradient(135deg, var(--primary), var(--dark)); color: #FFF; padding: 3.5rem 0; text-align: center; margin-bottom: 3rem;">
    <div class="container">
        <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 0.5rem;">الأخبار والإعلانات المدرسية</h1>
        <p style="font-size: 1.1rem; color: #CBD5E1;">منبر الإعلانات الرسمية ومستجدات مدرسة سيدي إبراهيم الرائدة</p>
    </div>
</div>

<div class="container" style="margin-bottom: 4rem;">
    
    <!-- شريط البحث والتصفية حسب الفئة -->
    <div style="background: #FFF; padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); margin-bottom: 2.5rem;">
        <form action="news.php" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
            <div style="flex-grow: 1; position: relative;">
                <input type="text" name="q" value="<?php echo sanitize($searchQuery); ?>" placeholder="ابحث في الأخبار والقرارات..." class="form-control" style="padding-right: 2.5rem;">
                <i class="fas fa-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--gray-text);"></i>
            </div>
            
            <select name="cat" class="form-control" style="width: auto; min-width: 180px;">
                <option value="">جميع الفئات</option>
                <option value="الأنشطة المدرسية" <?php echo $categoryFilter === 'الأنشطة المدرسية' ? 'selected' : ''; ?>>الأنشطة المدرسية</option>
                <option value="تكريم وتفوق" <?php echo $categoryFilter === 'تكريم وتفوق' ? 'selected' : ''; ?>>تكريم وتفوق</option>
                <option value="إعلانات الإدارة" <?php echo $categoryFilter === 'إعلانات الإدارة' ? 'selected' : ''; ?>>إعلانات الإدارة</option>
            </select>

            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> تصفية</button>
            <?php if (!empty($searchQuery) || !empty($categoryFilter)): ?>
                <a href="news.php" class="btn btn-outline" style="color: var(--dark-muted); border-color: var(--border-color);"><i class="fas fa-undo"></i> إعادة تصفية</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- شبكة الأخبار Grid Cards -->
    <?php if (!empty($posts)): ?>
        <div class="grid grid-3">
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <div class="post-thumb">
                        <span class="post-category"><?php echo sanitize($post['category'] ?? 'عام'); ?></span>
                        <img src="<?php echo getPostImageUrl($post['image']); ?>" alt="<?php echo sanitize($post['title']); ?>" onerror="this.src='assets/images/hero.jpg'">
                    </div>
                    <div class="post-body">
                        <div class="post-meta">
                            <span><i class="far fa-calendar-alt"></i> <?php echo date('Y/m/d', strtotime($post['created_at'])); ?></span>
                            <span><i class="far fa-eye"></i> <?php echo (int)($post['views'] ?? 0); ?> مشاهدة</span>
                        </div>
                        <h3 class="post-title"><?php echo sanitize($post['title']); ?></h3>
                        <p class="post-excerpt">
                            <?php echo sanitize($post['excerpt'] ?? mb_substr(strip_tags($post['content']), 0, 130) . '...'); ?>
                        </p>
                        <div class="post-footer">
                            <a href="post.php?id=<?php echo $post['id']; ?>" class="btn-readmore">
                                قراءة المقال كاملاً <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 4rem 2rem; background: #FFF; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
            <i class="fas fa-newspaper" style="font-size: 3rem; color: var(--gray-text); margin-bottom: 1rem;"></i>
            <h3>لم يتم العثور على أي أخبار تتطابق مع بحثك</h3>
            <p style="color: var(--gray-text); margin-top: 0.5rem;">جرّب البحث بكلمات أخرى أو اختر فئة مختلفة.</p>
            <a href="news.php" class="btn btn-primary" style="margin-top: 1.5rem;">العودة لكافة الأخبار</a>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
