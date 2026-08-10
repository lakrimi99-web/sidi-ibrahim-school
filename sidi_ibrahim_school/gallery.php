<?php
$pageTitle = 'معرض الصور والأنشطة المدرسية';
require_once __DIR__ . '/includes/header.php';

$galleryItems = [
    [
        'title' => 'معرض العلوم والابتكار 2026',
        'category' => 'علوم برمجية',
        'image' => 'uploads/robotics.jpg'
    ],
    [
        'title' => 'حفل تكريم المتفوقين الأكاديمي',
        'category' => 'حفلات ومناسبات',
        'image' => 'uploads/honor.jpg'
    ],
    [
        'title' => 'الحرم المدرسي والملعب الرياضي',
        'category' => 'أنشطة رياضية',
        'image' => 'assets/images/hero.jpg'
    ]
];
?>

<!-- ترويسة معرض الصور -->
<div style="background: linear-gradient(135deg, var(--primary), var(--dark)); color: #FFF; padding: 3.5rem 0; text-align: center; margin-bottom: 3rem;">
    <div class="container">
        <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 0.5rem;">معرض الصور والأنشطة المدرسية</h1>
        <p style="font-size: 1.1rem; color: #CBD5E1;">لحظات توثق إبداعات وتفوق تلامذتنا في مختلف المجالات</p>
    </div>
</div>

<div class="container" style="margin-bottom: 4rem;">
    <div class="grid grid-3">
        <?php foreach ($galleryItems as $item): ?>
            <div style="background: #FFF; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); transition: var(--transition);" class="post-card">
                <div class="post-thumb" style="height: 240px;">
                    <span class="post-category"><?php echo $item['category']; ?></span>
                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" onerror="this.src='assets/images/hero.jpg'">
                </div>
                <div style="padding: 1.2rem; text-align: center;">
                    <h3 style="font-size: 1.15rem; color: var(--primary); font-weight: 700;"><?php echo $item['title']; ?></h3>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
