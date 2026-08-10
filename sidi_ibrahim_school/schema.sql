-- ========================================================
-- قاعدة بيانات مدرسة سيدي إبراهيم الرائدة
-- Sidi Ibrahim Pioneer School Database Schema
-- ========================================================

CREATE DATABASE IF NOT EXISTS `sidi_ibrahim_school` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `sidi_ibrahim_school`;

-- --------------------------------------------------------
-- 1. جدول المستخدمين والمشرفين (users)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `role` ENUM('admin', 'editor') DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. جدول المقالات والأخبار والإعلانات (posts)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `posts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `excerpt` TEXT NULL,
  `content` LONGTEXT NOT NULL,
  `image` VARCHAR(255) DEFAULT 'default_post.jpg',
  `category` VARCHAR(50) DEFAULT 'أخبار عامة',
  `author_id` INT DEFAULT 1,
  `views` INT DEFAULT 0,
  `status` ENUM('published', 'draft') DEFAULT 'published',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. إدراج المشرف المعتمد (اسم المستخدم: sidibrahim / كلمة المرور: 159)
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`) 
VALUES 
(1, 'sidibrahim', 'admin@sidi-ibrahim.edu', '$2y$10$e9H6g3.38m6T6J9v.q9nLe6o.Y8B/qj1M2Q0j/q4r1e5A6B7C8D9E', 'مدير النظام (سيدي إبراهيم)', 'admin')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- --------------------------------------------------------
-- 4. إدراج مقالات وأخبار مدرسية تجريبية افتتاحية
-- --------------------------------------------------------
INSERT INTO `posts` (`title`, `slug`, `excerpt`, `content`, `image`, `category`, `author_id`, `views`, `status`, `created_at`) VALUES
(
  'انطلاق فعاليات معرض العلوم والابتكار للعام الدراسي 2026', 
  'science-fair-2026', 
  'شهدت مدرسة سيدي إبراهيم الرائدة افتتاح معرض العلوم السنوي بمشاركة واسعة من التلاميذ ومشاريع مبتكرة في الذكاء الاصطناعي والروبوتات.', 
  '<p>ببالغ الفخر والاعتزاز، افتتحت إدارة <strong>مدرسة سيدي إبراهيم الرائدة</strong> صباح اليوم فعاليات المعرض السنوي للعلوم والابتكار تحت شعار <em>"علماء الغد يبنون المستقبل"</em>.</p><p>وقد شمل المعرض أكثر من 40 مشروعاً علمياً قدمها طلاب المراحل المختلفة، تنوعت بين تطبيقات الذكاء الاصطناعي، ومشاريع الطاقة المتجددة، والابتكارات الروبوتية المتميزة التي لاقت إشادة كبيرة من لجنة التحكيم والزوار من أولياء الأمور والضيوف.</p><p>وأكد مدير المدرسة في كلمته الافتتاحية أن المدرسة تولي اهتماماً بالغاً بالتعليم التفاعلي وتنمية مهارات التفكير النقدي لدى الطلاب، موجهاً الشكر لكافة الأطر التربوية والإدارية على جهودهم الجبارة في إنجاح هذا الحدث العلمي المتميز.</p>', 
  'robotics.jpg', 
  'الأنشطة المدرسية', 
  1, 
  142, 
  'published', 
  '2026-08-01 10:00:00'
),
(
  'حفل تكريم المتفوقين في الأنشطة المدرسية والنتائج الدراسية', 
  'honor-ceremony-2026', 
  'أقامت مدرسة سيدي إبراهيم الرائدة حفلها السنوي لتكريم التلاميذ المتفوقين دراسياً والمتميزين في الأنشطة والمسابقات الرياضية والثقافية.', 
  '<p>في أجواء مفعمة بالفرح والاعتزاز، نظمت <strong>مدرسة سيدي إبراهيم الرائدة</strong> حفل التكريم السنوي لتتويج نخبة من تلامذتها المتفوقين الحاصلين على أعلى المراتب الدراسية خلال الفضل الدراسي المنصرم.</p><p>تضمن الحفل فقرات فنية وثقافية متنوعة قدمها كورال المدرسة، بالإضافة إلى عرض مرئي يوثق أبرز المحطات والأنشطة المدرسية على مدار العام.</p>', 
  'honor.jpg', 
  'تكريم وتفوق', 
  1, 
  98, 
  'published', 
  '2026-08-05 14:30:00'
),
(
  'إعلان هام: فتح باب التسجيل وتحديث البيانات للعام الدراسي الجديد', 
  'registration-open-2026', 
  'تعلن إدارة مدرسة سيدي إبراهيم الرائدة عن فتح باب التسجيل للمستجدين وتحديث بيانات التلاميذ القدامى ابتداءً من الأسبوع القادم.', 
  '<p>تنهي إدارة <strong>مدرسة سيدي إبراهيم الرائدة</strong> إلى علم كافة أولياء الأمور الكرام أن باب التسجيل وإعادة التسجيل للعام الدراسي الجديد سيكون مفتوحاً ابتداءً من يوم الاثنين القادم بمقر الإدارة.</p>', 
  'hero.jpg', 
  'إعلانات الإدارة', 
  1, 
  215, 
  'published', 
  '2026-08-08 09:00:00'
);
