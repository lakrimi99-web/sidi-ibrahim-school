<?php
$pageTitle = 'عن المدرسة والطاقم التربوي';
require_once __DIR__ . '/includes/header.php';
?>

<!-- ترويسة صفحة عن المدرسة -->
<div style="background: linear-gradient(135deg, var(--primary), var(--dark)); color: #FFF; padding: 3.5rem 0; text-align: center; margin-bottom: 3rem;">
    <div class="container">
        <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 0.5rem;">عن مدرسة سيدي إبراهيم الرائدة</h1>
        <p style="font-size: 1.1rem; color: #CBD5E1;">تاريخ من التفوق والتميز التربوي والتعليمي</p>
    </div>
</div>

<div class="container" style="margin-bottom: 4rem;">
    
    <!-- قسم التاريخ والقصة -->
    <div class="grid grid-2" style="align-items: center; margin-bottom: 4rem;">
        <div>
            <span class="section-tag">مسيرة وتاريخ</span>
            <h2 class="section-title">قصة التأسيس والتطور</h2>
            <p style="font-size: 1.05rem; color: var(--dark-muted); line-height: 1.8; margin-bottom: 1.2rem;">
                تأسست مدرسة سيدي إبراهيم الرائدة لتكون صرحاً تعليمياً فريداً يجمع بين تقديم المناهج الرسمية بجودة عالية، وتنمية المهارات الشخصية والتقنية لدى المتعلمين.
            </p>
            <p style="font-size: 1.05rem; color: var(--dark-muted); line-height: 1.8;">
                نحن نؤمن بأن التعليم ليس مجرد تحصيل أكاديمي، بل هو بناء شامل لشخصية التلميذ تمكنه من الابتكار والإبداع والمشاركة الفاعلة في بناء المجتمع.
            </p>
        </div>
        <div>
            <img src="assets/images/hero.jpg" alt="المدرسة" style="border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
        </div>
    </div>

    <!-- قسم الهيكل الإداري والطاقم التربوي -->
    <div style="margin-bottom: 4rem;" id="admin">
        <div class="section-header">
            <span class="section-tag">الفريق القيادي</span>
            <h2 class="section-title">الإدارة والطاقم التربوي</h2>
            <p class="section-desc">نخبة من الكفاءات والأطر التربوية والإدارية المكرسة لخدمة تلامذتنا</p>
        </div>

        <div class="grid grid-3">
            <!-- المدير -->
            <div style="background: #FFF; border-radius: var(--radius-md); border: 1px solid var(--border-color); padding: 2rem; text-align: center; box-shadow: var(--shadow-sm);">
                <div style="width: 100px; height: 100px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: inline-flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 0.3rem;">أ. عبد الرحمن العلمي</h3>
                <span style="color: var(--secondary); font-weight: 700; font-size: 0.95rem;">مدير المؤسسة</span>
                <p style="color: var(--gray-text); font-size: 0.9rem; margin-top: 0.8rem;">خبرة تزيد عن 22 عاماً في الإشراف التربوي والتسيير الإداري.</p>
            </div>

            <!-- الناظر الإداري -->
            <div style="background: #FFF; border-radius: var(--radius-md); border: 1px solid var(--border-color); padding: 2rem; text-align: center; box-shadow: var(--shadow-sm);">
                <div style="width: 100px; height: 100px; border-radius: 50%; background: var(--secondary-light); color: var(--secondary); display: inline-flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 0.3rem;">أ. فاطمة الزهراء الإدريسي</h3>
                <span style="color: var(--secondary); font-weight: 700; font-size: 0.95rem;">الناظرة العامة للشؤون التربوية</span>
                <p style="color: var(--gray-text); font-size: 0.9rem; margin-top: 0.8rem;">متابعة الانضباط والأداء الأكاديمي والتواصل مع أولياء الأمور.</p>
            </div>

            <!-- رئيس قسم العلوم والابتكار -->
            <div style="background: #FFF; border-radius: var(--radius-md); border: 1px solid var(--border-color); padding: 2rem; text-align: center; box-shadow: var(--shadow-sm);">
                <div style="width: 100px; height: 100px; border-radius: 50%; background: #FFE4E6; color: #E11D48; display: inline-flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-microscope"></i>
                </div>
                <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 0.3rem;">د. طارق السلاوي</h3>
                <span style="color: var(--secondary); font-weight: 700; font-size: 0.95rem;">منسق الأنشطة والمختبرات</span>
                <p style="color: var(--gray-text); font-size: 0.9rem; margin-top: 0.8rem;">مؤطر نادي الذكاء الاصطناعي والروبوتيك والعلوم الدقيقة.</p>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
