<?php
$pageTitle = 'الصفحة الرئيسية';
require_once __DIR__ . '/includes/header.php';

// استعلام جلب أحدث 3 مقالات من قاعدة البيانات
$db = getDB();
$latestPosts = [];

if ($db) {
    try {
        $stmt = $db->query("SELECT * FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
        $latestPosts = $stmt->fetchAll();
    } catch (PDOException $e) {
        $latestPosts = getFallbackPosts();
    }
} else {
    $latestPosts = getFallbackPosts();
}
?>

<!-- 1. البنر الترحيبي الرئيسي (Hero Banner) -->
<section class="hero-section">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i> صرح تربوي رائد ونسيج علمي متميز
                </div>
                <h1 class="hero-title">
                    مرحبًا بكم في الموقع الرسمي لـ <span>مدرسة سيدي إبراهيم الرائدة</span>
                </h1>
                <p class="hero-subtitle">
                    نسعى إلى تقديم تعليم متميز يساهم في بناء جيل متعلم، مبدع، ومسؤول قادر على مواكبة تحديات المستقبل وتأسيس غدٍ أفضل.
                </p>
                <div class="hero-actions">
                    <a href="news.php" class="btn btn-primary">
                        <i class="fas fa-newspaper"></i> تصفح الأخبار والإعلانات
                    </a>
                    <a href="contact.php" class="btn btn-outline">
                        <i class="fas fa-paper-plane"></i> تواصل مع الإدارة
                    </a>
                </div>
            </div>

            <!-- بطاقة التميز التفاعلية جانباً -->
            <div class="hero-card-preview">
                <div class="hero-card-header">
                    <h3><i class="fas fa-award" style="color: var(--secondary);"></i> إعلانات ومواعيد سريعة</h3>
                </div>
                <div class="stat-item-small">
                    <div class="stat-icon-wrap"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <h4 style="font-size: 1rem; color: #FFF;">افتتاح التسجيل النهائي</h4>
                        <span style="font-size: 0.85rem; color: #CBD5E1;">ابتداءً من يوم الاثنين القادم</span>
                    </div>
                </div>
                <div class="stat-item-small">
                    <div class="stat-icon-wrap" style="background: var(--primary);"><i class="fas fa-laptop-code"></i></div>
                    <div>
                        <h4 style="font-size: 1rem; color: #FFF;">أندية الروبوتيات والبرمجة</h4>
                        <span style="font-size: 0.85rem; color: #CBD5E1;">فتح باب الانضمام لجميع المستويات</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. إحصائيات وأرقام المدرسة (Stats Section) -->
<div class="stats-container">
    <div class="container">
        <div class="grid grid-4">
            <div class="stat-card">
                <div class="contact-icon" style="background: var(--primary-light); color: var(--primary);"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <div class="stat-num counter" data-target="1250">0</div>
                    <div class="stat-title">تلميذ وتلميذة</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="contact-icon" style="background: var(--secondary-light); color: var(--secondary);"><i class="fas fa-chalkboard-teacher"></i></div>
                <div>
                    <div class="stat-num counter" data-target="85">0</div>
                    <div class="stat-title">أستاذ ومؤطر تربوي</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="contact-icon" style="background: #FFE4E6; color: #E11D48;"><i class="fas fa-school"></i></div>
                <div>
                    <div class="stat-num counter" data-target="42">0</div>
                    <div class="stat-title">قاعة دراسية ومختبر</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="contact-icon" style="background: #FEF3C7; color: #D97706;"><i class="fas fa-trophy"></i></div>
                <div>
                    <div class="stat-num counter" data-target="99">0</div>
                    <div class="stat-title">% نسبة النجاح التفوق</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 3. قسم أحدث الأخبار والإعلانات (Latest News Section) -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag"><i class="fas fa-bullhorn"></i> مواكبة مستمرة</span>
            <h2 class="section-title">آخر الأخبار والإعلانات المدرسية</h2>
            <p class="section-desc">تابِع أحدث المستجدات والفعاليات والقرارات الإدارية الخاصة بمدرسة سيدي إبراهيم الرائدة.</p>
        </div>

        <div class="grid grid-3">
            <?php if (!empty($latestPosts)): ?>
                <?php foreach ($latestPosts as $post): ?>
                    <article class="post-card">
                        <div class="post-thumb">
                            <span class="post-category"><?php echo sanitize($post['category'] ?? 'أخبار'); ?></span>
                            <img src="<?php echo getPostImageUrl($post['image']); ?>" alt="<?php echo sanitize($post['title']); ?>" onerror="this.src='assets/images/hero.jpg'">
                        </div>
                        <div class="post-body">
                            <div class="post-meta">
                                <span><i class="far fa-calendar-alt"></i> <?php echo date('Y/m/d', strtotime($post['created_at'])); ?></span>
                                <span><i class="far fa-eye"></i> <?php echo (int)($post['views'] ?? 0); ?> مشاهدة</span>
                            </div>
                            <h3 class="post-title"><?php echo sanitize($post['title']); ?></h3>
                            <p class="post-excerpt">
                                <?php echo sanitize($post['excerpt'] ?? mb_substr(strip_tags($post['content']), 0, 120) . '...'); ?>
                            </p>
                            <div class="post-footer">
                                <a href="post.php?id=<?php echo $post['id']; ?>" class="btn-readmore">
                                    اقرأ المزيد <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 2rem;" class="alert alert-success">
                    لا تتوفر مقالات حالياً. يمكنك استخدام لوحة التحكم لإضافة مقالات جديدة!
                </div>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 2.5rem;">
            <a href="news.php" class="btn btn-outline" style="border-color: var(--primary); color: var(--primary);">
                عرض جميع الأخبار والإعلانات <i class="fas fa-list-ul"></i>
            </a>
        </div>
    </div>
</section>

<!-- 4. قسم نبذة سيرة ورسالة المدرسة (About Overview Section) -->
<section class="section" style="background: #FFF; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <div class="grid grid-2" style="align-items: center;">
            <div>
                <span class="section-tag">رؤيتنا ورسالتنا التربوية</span>
                <h2 class="section-title" style="margin-bottom: 1.5rem;">لماذا تختار مدرسة سيدي إبراهيم الرائدة؟</h2>
                <p style="font-size: 1.05rem; color: var(--dark-muted); line-height: 1.8; margin-bottom: 1.2rem;">
                    تعتبر مدرسة سيدي إبراهيم الرائدة نموذجاً متميزاً في الحقل التربوي والتعليمي، حيث تجمع بين الأصالة والمعاصرة وتطبيق أحدث المناهج العلمية المبتكرة.
                </p>
                <ul style="display: flex; flex-direction: column; gap: 0.9rem; margin-bottom: 2rem;">
                    <li style="display: flex; gap: 0.75rem; align-items: center;">
                        <i class="fas fa-check-circle" style="color: var(--secondary); font-size: 1.2rem;"></i>
                        <span>كادر تدريسي عالي الكفاءة ذو خبرة تربوية واسعة.</span>
                    </li>
                    <li style="display: flex; gap: 0.75rem; align-items: center;">
                        <i class="fas fa-check-circle" style="color: var(--secondary); font-size: 1.2rem;"></i>
                        <span>بيئة تعليمية مجهزة بأحدث وسائل التكنولوجيا والمختبرات.</span>
                    </li>
                    <li style="display: flex; gap: 0.75rem; align-items: center;">
                        <i class="fas fa-check-circle" style="color: var(--secondary); font-size: 1.2rem;"></i>
                        <span>اهتمام بالغ بالأنشطة الموازية (الروبوتيك، الثقافة، والرياضة).</span>
                    </li>
                </ul>
                <a href="about.php" class="btn btn-primary">تعرّف على المزيد حول المدرسة <i class="fas fa-arrow-left"></i></a>
            </div>

            <div style="position: relative;">
                <img src="assets/images/hero.jpg" alt="المدرسة" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
                <div style="position: absolute; bottom: -20px; right: -20px; background: var(--primary); color: #FFF; padding: 1.5rem; border-radius: var(--radius-md); box-shadow: var(--shadow-md); display: flex; align-items: center; gap: 1rem;">
                    <i class="fas fa-medal" style="font-size: 2.2rem; color: var(--secondary);"></i>
                    <div>
                        <h4 style="margin: 0; font-size: 1.1rem;">أكثر من 20 عاماً</h4>
                        <span style="font-size: 0.85rem; color: #CBD5E1;">من العطاء والتميز العلمي</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
