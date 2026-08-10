<?php
require_once __DIR__ . '/config/db.php';

$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$db = getDB();
$post = null;
$recentPosts = [];

if ($db && $postId > 0) {
    try {
        // تحديث عدد المشاهدات
        $updateStmt = $db->prepare("UPDATE posts SET views = views + 1 WHERE id = :id");
        $updateStmt->execute([':id' => $postId]);

        // جلب المقال
        $stmt = $db->prepare("SELECT p.*, u.full_name as author_name FROM posts p LEFT JOIN users u ON p.author_id = u.id WHERE p.id = :id AND p.status = 'published'");
        $stmt->execute([':id' => $postId]);
        $post = $stmt->fetch();

        // جلب أخبار سريعة للشريط الجانبي
        $recentStmt = $db->prepare("SELECT id, title, image, created_at FROM posts WHERE id != :id AND status = 'published' ORDER BY created_at DESC LIMIT 4");
        $recentStmt->execute([':id' => $postId]);
        $recentPosts = $recentStmt->fetchAll();
    } catch (PDOException $e) {
        $post = null;
    }
}

// حل احتياطي في حالة المعاينة دون قاعدة بيانات مجهزة
if (!$post) {
    $fallback = getFallbackPosts();
    foreach ($fallback as $f) {
        if ($f['id'] == $postId || $postId == 0) {
            $post = $f;
            $post['author_name'] = 'إدارة المدرسة';
            break;
        }
    }
    if (!$post) {
        $post = $fallback[0];
        $post['author_name'] = 'إدارة المدرسة';
    }
    $recentPosts = array_slice($fallback, 0, 3);
}

$pageTitle = $post['title'];
require_once __DIR__ . '/includes/header.php';
?>

<!-- ترويسة تفاصيل المقال (Breadcrumb & Meta) -->
<div class="post-detail-container">
    <div class="container">
        
        <!-- روابط التنقل السريعة (Breadcrumbs) -->
        <div style="display: flex; gap: 0.5rem; align-items: center; font-size: 0.9rem; color: var(--gray-text); margin-bottom: 1.5rem;">
            <a href="index.php"><i class="fas fa-home"></i> الرئيسية</a>
            <span>/</span>
            <a href="news.php">الأخبار والإعلانات</a>
            <span>/</span>
            <span style="color: var(--primary); font-weight: 700; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo sanitize($post['title']); ?></span>
        </div>

        <div class="grid" style="grid-template-columns: 2.2fr 1fr; gap: 2.5rem;">
            
            <!-- العمود الرئيسي: محتوى المقال -->
            <main>
                <article style="background: #FFF; padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                    
                    <div class="post-header-full">
                        <span class="badge badge-success" style="font-size: 0.9rem; margin-bottom: 0.8rem; background: var(--secondary-light); color: var(--secondary);">
                            <i class="fas fa-tag"></i> <?php echo sanitize($post['category'] ?? 'أخبار'); ?>
                        </span>
                        
                        <h1 class="post-full-title"><?php echo sanitize($post['title']); ?></h1>
                        
                        <div class="post-full-meta">
                            <span><i class="fas fa-user-edit"></i> بقلم: <?php echo sanitize($post['author_name'] ?? 'إدارة المدرسة'); ?></span>
                            <span><i class="far fa-calendar-alt"></i> تاريخ النشر: <?php echo date('Y/m/d - H:i', strtotime($post['created_at'])); ?></span>
                            <span><i class="far fa-eye"></i> المشاهدات: <?php echo (int)($post['views'] ?? 0); ?></span>
                        </div>
                    </div>

                    <!-- الصورة البارزة للمقال -->
                    <?php if (!empty($post['image'])): ?>
                        <img src="<?php echo getPostImageUrl($post['image']); ?>" alt="<?php echo sanitize($post['title']); ?>" class="post-featured-img" onerror="this.src='assets/images/hero.jpg'">
                    <?php endif; ?>

                    <!-- النص الكامل للمقال -->
                    <div class="post-content-body">
                        <?php echo $post['content']; ?>
                    </div>

                    <!-- أزرار المشاركة والعودة -->
                    <div style="margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <a href="news.php" class="btn btn-outline" style="color: var(--primary); border-color: var(--primary);">
                            <i class="fas fa-arrow-right"></i> العودة لقائمة الأخبار
                        </a>
                        
                        <div style="display: flex; gap: 0.6rem; align-items: center;">
                            <span style="font-size: 0.9rem; font-weight: 700; color: var(--gray-text);">مشاركة المقال:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" style="background: #1877F2; color: #FFF; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($post['title']); ?>" target="_blank" style="background: #25D366; color: #FFF; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </article>
            </main>

            <!-- العمود الجانبي (Sidebar) -->
            <aside>
                <!-- ويدجت آخر الأخبار -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">أخبار وإعلانات أخرى</h3>
                    <?php if (!empty($recentPosts)): ?>
                        <?php foreach ($recentPosts as $recent): ?>
                            <a href="post.php?id=<?php echo $recent['id']; ?>" class="recent-post-item">
                                <img src="<?php echo getPostImageUrl($recent['image']); ?>" alt="Thumb" onerror="this.src='assets/images/hero.jpg'">
                                <div class="recent-post-info">
                                    <h5><?php echo sanitize($recent['title']); ?></h5>
                                    <span><i class="far fa-calendar-alt"></i> <?php echo date('Y/m/d', strtotime($recent['created_at'])); ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- ويدجت إعلان الإدارة المباشر -->
                <div class="sidebar-widget" style="background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: #FFF;">
                    <h3 class="widget-title" style="color: #FFF;">استفسار أو للتواصل؟</h3>
                    <p style="font-size: 0.95rem; margin-bottom: 1.2rem; color: #E2E8F0;">
                        يسعد فريق الاستقبال في مدرسة سيدي إبراهيم الرائدة بتقديم كافة التوضيحات والإجابة عن تساؤلاتكم.
                    </p>
                    <a href="contact.php" class="btn btn-primary" style="width: 100%; text-align: center;">
                        <i class="fas fa-envelope"></i> صفحة اتصل بنا
                    </a>
                </div>
            </aside>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
