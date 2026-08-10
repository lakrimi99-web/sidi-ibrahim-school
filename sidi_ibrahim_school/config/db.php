<?php
/**
 * ========================================================
 * ملف الاتصال بقاعدة البيانات - مدرسة سيدي إبراهيم الرائدة
 * Database Connection & Global Configuration
 * ========================================================
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// إعدادات متغيرات قاعدة البيانات
define('DB_HOST', 'localhost');
define('DB_NAME', 'sidi_ibrahim_school');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SITE_NAME', 'مدرسة سيدي إبراهيم الرائدة');

/**
 * إنشاء والتحقق من الاتصال بقاعدة البيانات PDO
 * @return PDO|null
 */
function getDB() {
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // في حالة عدم إنشاء قاعدة البيانات بعد، نقوم بمحاولة إنشائها وتغذيتها تلقائياً
        try {
            $rootDsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
            $rootPdo = new PDO($rootDsn, DB_USER, DB_PASS);
            $rootPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $rootPdo->exec("USE `" . DB_NAME . "`");
            
            // قراءة وتنفيذ ملف schema.sql إذا كان موجوداً
            $schemaFile = __DIR__ . '/../schema.sql';
            if (file_exists($schemaFile)) {
                $sql = file_get_contents($schemaFile);
                $rootPdo->exec($sql);
            }
            
            // إعادة محاولة الاتصال بعد الإنشاء
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            return $pdo;
        } catch (PDOException $ex) {
            // كود العودة بقيمة null في حال تعذر الاتصال بـ MySQL المحلي
            return null;
        }
    }
}

/**
 * دالة تعقيم المدخلات للحماية من XSS
 */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * الحصول على رابط الصورة البارزة مع وجود صورة افتراضية
 */
function getPostImageUrl($imageName) {
    if (!empty($imageName) && file_exists(__DIR__ . '/../uploads/' . $imageName)) {
        return 'uploads/' . $imageName;
    }
    if (!empty($imageName) && file_exists(__DIR__ . '/../assets/images/' . $imageName)) {
        return 'assets/images/' . $imageName;
    }
    return 'assets/images/hero.jpg';
}

/**
 * إنشاء اسم لطيف في الروابط (Slug Generator)
 */
function generateSlug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = trim($text, '-');
    $text = strtolower($text);
    return empty($text) ? 'post-' . time() : $text;
}

// قائمة الأخبار التجريبية الاحتياطية (Fallback) في حالة توقف السيرفر عن MySQL
function getFallbackPosts() {
    return [
        [
            'id' => 1,
            'title' => 'انطلاق فعاليات معرض العلوم والابتكار للعام الدراسي 2026',
            'slug' => 'science-fair-2026',
            'excerpt' => 'شهدت مدرسة سيدي إبراهيم الرائدة افتتاح معرض العلوم السنوي بمشاركة واسعة من التلاميذ ومشاريع مبتكرة في الذكاء الاصطناعي والروبوتات.',
            'content' => '<p>ببالغ الفخر والاعتزاز، افتتحت إدارة <strong>مدرسة سيدي إبراهيم الرائدة</strong> صباح اليوم فعاليات المعرض السنوي للعلوم والابتكار تحت شعار <em>"علماء الغد يبنون المستقبل"</em>.</p><p>وقد شمل المعرض أكثر من 40 مشروعاً علمياً قدمها طلاب المراحل المختلفة، تنوعت بين تطبيقات الذكاء الاصطناعي، ومشاريع الطاقة المتجددة، والابتكارات الروبوتية المتميزة التي لاقت إشادة كبيرة من لجنة التحكيم والزوار من أولياء الأمور والضيوف.</p><p>وأكد مدير المدرسة في كلمته الافتتاحية أن المدرسة تولي اهتماماً بالغاً بالتعليم التفاعلي وتنمية مهارات التفكير النقدي لدى الطلاب، موجهاً الشكر لكافة الأطر التربوية والإدارية على جهودهم الجبارة في إنجاح هذا الحدث العلمي المتميز.</p>',
            'image' => 'robotics.jpg',
            'category' => 'الأنشطة المدرسية',
            'created_at' => '2026-08-01 10:00:00',
            'views' => 142
        ],
        [
            'id' => 2,
            'title' => 'حفل تكريم المتفوقين في الأنشطة المدرسية والنتائج الدراسية',
            'slug' => 'honor-ceremony-2026',
            'excerpt' => 'أقامت مدرسة سيدي إبراهيم الرائدة حفلها السنوي لتكريم التلاميذ المتفوقين دراسياً والمتميزين في الأنشطة والمسابقات الرياضية والثقافية.',
            'content' => '<p>في أجواء مفعمة بالفرح والاعتزاز، نظمت <strong>مدرسة سيدي إبراهيم الرائدة</strong> حفل التكريم السنوي لتتويج نخبة من تلامذتها المتفوقين الحاصلين على أعلى المراتب الدراسية خلال الفضل الدراسي المنصرم.</p><p>تضمن الحفل فقرات فنية وثقافية متنوعة قدمها كورال المدرسة، بالإضافة إلى عرض مرئي يوثق أبرز المح المحطات والأنشطة المدرسية على مدار العام.</p>',
            'image' => 'honor.jpg',
            'category' => 'تكريم وتفوق',
            'created_at' => '2026-08-05 14:30:00',
            'views' => 98
        ],
        [
            'id' => 3,
            'title' => 'إعلان هام: فتح باب التسجيل وتحديث البيانات للعام الدراسي الجديد',
            'slug' => 'registration-open-2026',
            'excerpt' => 'تعلن إدارة مدرسة سيدي إبراهيم الرائدة عن فتح باب التسجيل للمستجدين وتحديث بيانات التلاميذ القدامى ابتداءً من الأسبوع القادم.',
            'content' => '<p>تنهي إدارة <strong>مدرسة سيدي إبراهيم الرائدة</strong> إلى علم كافة أولياء الأمور الكرام أن باب التسجيل وإعادة التسجيل للعام الدراسي الجديد سيكون مفتوحاً ابتداءً من يوم الاثنين القادم بمقر الإدارة.</p>',
            'image' => 'hero.jpg',
            'category' => 'إعلانات الإدارة',
            'created_at' => '2026-08-08 09:00:00',
            'views' => 215
        ]
    ];
}
