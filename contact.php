<?php
$pageTitle = 'اتصل بنا والاتصال بالإدارة';
require_once __DIR__ . '/includes/header.php';

$successMsg = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? sanitize($_POST['subject']) : '';
    $message = isset($_POST['message']) ? sanitize($_POST['message']) : '';

    if (!empty($name) && !empty($email) && !empty($message)) {
        $successMsg = "نشكرك يا {$name} على تواصلك معنا! تم استقبال رسالتك بنجاح وسيتواصل معك فريق مدرسة سيدي إبراهيم الرائدة في أقرب وقت.";
    } else {
        $errorMsg = "يرجى ملء جميع الحقول المطلوبة بشكل صحيح.";
    }
}
?>

<!-- ترويسة صفحة اتصل بنا -->
<div style="background: linear-gradient(135deg, var(--primary), var(--dark)); color: #FFF; padding: 3.5rem 0; text-align: center; margin-bottom: 3rem;">
    <div class="container">
        <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 0.5rem;">تواصل مع إدارة المدرسة</h1>
        <p style="font-size: 1.1rem; color: #CBD5E1;">نحن هنا للإجابة على جميع استفساراتكم ومواكبة تطلع تلامذتنا وأولياء الأمور</p>
    </div>
</div>

<div class="container" style="margin-bottom: 4rem;">
    
    <!-- بطاقات المعلومات الرئيسية -->
    <div class="grid grid-3" style="margin-bottom: 3rem;">
        <div class="contact-card">
            <div class="contact-icon"><i class="fas fa-map-marked-alt"></i></div>
            <div>
                <h4 style="font-size: 1.15rem; color: var(--primary); margin-bottom: 0.3rem;">العنوان الجغرافي</h4>
                <p style="color: var(--gray-text); font-size: 0.95rem;">شارع التربية والتعليم، حي سيدي إبراهيم، المملكة المغربية</p>
            </div>
        </div>

        <div class="contact-card">
            <div class="contact-icon" style="background: var(--secondary-light); color: var(--secondary);"><i class="fas fa-phone-volume"></i></div>
            <div>
                <h4 style="font-size: 1.15rem; color: var(--primary); margin-bottom: 0.3rem;">أرقام الهاتف</h4>
                <p style="color: var(--gray-text); font-size: 0.95rem;">+212 522 123 456<br>+212 661 987 654</p>
            </div>
        </div>

        <div class="contact-card">
            <div class="contact-icon" style="background: #FFE4E6; color: #E11D48;"><i class="fas fa-envelope-open-text"></i></div>
            <div>
                <h4 style="font-size: 1.15rem; color: var(--primary); margin-bottom: 0.3rem;">البريد الإلكتروني</h4>
                <p style="color: var(--gray-text); font-size: 0.95rem;">contact@sidi-ibrahim.edu<br>info@sidi-ibrahim.edu</p>
            </div>
        </div>
    </div>

    <!-- شبكة النموذج والخريطة التوضيحية -->
    <div class="grid grid-2" id="enroll">
        
        <!-- 1. نموذج إرسال الرسالة -->
        <div style="background: #FFF; padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 1.5rem; position: relative; padding-bottom: 0.5rem;">
                <i class="fas fa-paper-plane" style="color: var(--secondary);"></i> أرسل لنا رسالة أو استفساراً
            </h3>

            <?php if (!empty($successMsg)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle" style="font-size: 1.4rem;"></i>
                    <div><?php echo $successMsg; ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle" style="font-size: 1.4rem;"></i>
                    <div><?php echo $errorMsg; ?></div>
                </div>
            <?php endif; ?>

            <form action="contact.php#enroll" method="POST">
                <div class="form-group">
                    <label class="form-label">الاسم الكامل *</label>
                    <input type="text" name="name" required class="form-control" placeholder="مثال: أسامة العلماني">
                </div>

                <div class="grid grid-2" style="gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">البريد الإلكتروني *</label>
                        <input type="email" name="email" required class="form-control" placeholder="name@domain.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">رقم الهاتف</label>
                        <input type="tel" name="phone" class="form-control" placeholder="06XXXXXXXX">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">موضوع الرسالة</label>
                    <input type="text" name="subject" class="form-control" placeholder="استفسار عن التسجيل / ملاحظة...">
                </div>

                <div class="form-group">
                    <label class="form-label">نص الرسالة *</label>
                    <textarea name="message" required class="form-control" placeholder="اكتب استفسارك بالتفصيل هنا..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                    <i class="fas fa-paper-plane"></i> إرسال الرسالة إلى إدارة المدرسة
                </button>
            </form>
        </div>

        <!-- 2. الخريطة التوضيحية الجغرافية -->
        <div style="background: #FFF; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); display: flex; flex-direction: column;">
            <h3 style="font-size: 1.5rem; color: var(--primary); margin-bottom: 1rem;">
                <i class="fas fa-map-pin" style="color: var(--secondary);"></i> موقع المدرسة الجغرافي
            </h3>
            <p style="color: var(--gray-text); margin-bottom: 1.5rem; font-size: 0.95rem;">
                تقع مدرسة سيدي إبراهيم الرائدة في موقع هادئ وسهل الوصول عبر جميع وسائط النقل بمحاذاة الشارع الرئيسي.
            </p>

            <!-- الخريطة التفصيلية الجغرافية -->
            <div style="flex-grow: 1; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-color); position: relative; min-height: 320px; background: #E2E8F0;">
                <iframe 
                    title="موقع مدرسة سيدي إبراهيم الرائدة"
                    width="100%" 
                    height="100%" 
                    style="border:0; min-height: 340px;" 
                    loading="lazy" 
                    allowfullscreen
                    src="https://maps.google.com/maps?q=Casablanca%20Sidi%20Ibrahim&t=&z=14&ie=UTF8&iwloc=&output=embed">
                </iframe>
            </div>

            <div style="margin-top: 1.2rem; background: var(--light-bg); padding: 1rem; border-radius: var(--radius-sm); font-size: 0.9rem; color: var(--dark-muted); display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-info-circle" style="color: var(--primary); font-size: 1.2rem;"></i>
                <span>ملاحظة: تتوفر خطوط المواصلات النقل المدرسي لكافة الأحياء المجاورة.</span>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
