    <!-- تذييل الموقع الرئيسي Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <!-- العمود الأول: نبذة عن المدرسة -->
                <div class="footer-col">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.2rem;">
                        <img src="assets/images/logo.jpg" alt="Logo" style="width: 45px; height: 45px; border-radius: 50%;" onerror="this.style.display='none'">
                        <h3 style="color: #FFF; font-size: 1.25rem;">سيدي إبراهيم الرائدة</h3>
                    </div>
                    <p style="font-size: 0.95rem; line-height: 1.8;">
                        مؤسسة تعليمية تربوية رائدة تهدف إلى بناء أجيال متميزة ومبدعة، عبر توفير بيئة تعليمية محفزة تعتمد أحدث المناهج الوسائل التكنولوجية الحديثة.
                    </p>
                    <div style="display: flex; gap: 1rem; margin-top: 1.2rem;">
                        <a href="#" style="color: #FFF; background: rgba(255,255,255,0.1); width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" style="color: #FFF; background: rgba(255,255,255,0.1); width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: #FFF; background: rgba(255,255,255,0.1); width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: #FFF; background: rgba(255,255,255,0.1); width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <!-- العمود الثاني: روابط سريعة -->
                <div class="footer-col">
                    <h4>روابط سريعة</h4>
                    <ul class="footer-links">
                        <li><a href="index.php"><i class="fas fa-angle-left"></i> الصفحة الرئيسية</a></li>
                        <li><a href="about.php"><i class="fas fa-angle-left"></i> من نحن والإدارة</a></li>
                        <li><a href="news.php"><i class="fas fa-angle-left"></i> الأخبار والإعلانات</a></li>
                        <li><a href="gallery.php"><i class="fas fa-angle-left"></i> معرض الصور والأنشطة</a></li>
                        <li><a href="contact.php"><i class="fas fa-angle-left"></i> اتصل بنا</a></li>
                        <li><a href="admin/login.php"><i class="fas fa-angle-left"></i> دخول الإدارة</a></li>
                    </ul>
                </div>

                <!-- العمود الثالث: معلومات التواصل -->
                <div class="footer-col">
                    <h4>تواصل معنا</h4>
                    <ul style="display: flex; flex-direction: column; gap: 0.9rem; font-size: 0.95rem;">
                        <li style="display: flex; gap: 0.75rem; align-items: flex-start;">
                            <i class="fas fa-map-marker-alt" style="color: var(--secondary); margin-top: 0.3rem;"></i>
                            <span>شارع التربية والتعليم، حي سيدي إبراهيم، المملكة المغربية</span>
                        </li>
                        <li style="display: flex; gap: 0.75rem; align-items: center;">
                            <i class="fas fa-phone" style="color: var(--secondary);"></i>
                            <span>+212 522 123 456 / +212 661 987 654</span>
                        </li>
                        <li style="display: flex; gap: 0.75rem; align-items: center;">
                            <i class="fas fa-envelope" style="color: var(--secondary);"></i>
                            <span>contact@sidi-ibrahim.edu</span>
                        </li>
                    </ul>
                </div>

                <!-- العمود الرابع: النشرة البريدية -->
                <div class="footer-col">
                    <h4>النشرة الإخبارية</h4>
                    <p style="font-size: 0.9rem; margin-bottom: 1rem;">اشترك للحصول على آخر إعلانات وأخبار المدرسة فور نشرها.</p>
                    <form onsubmit="event.preventDefault(); alert('شكراً لاشتراكك في النشرة الإخبارية للمدرسة!');" style="display: flex; gap: 0.5rem;">
                        <input type="email" placeholder="بريدك الإلكتروني" required class="form-control" style="padding: 0.6rem; font-size: 0.9rem;">
                        <button type="submit" class="btn btn-primary" style="padding: 0.6rem 1rem;"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>

            <!-- حقوق النشر -->
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> جميع الحقوق محفوظة - <strong>مدرسة سيدي إبراهيم الرائدة</strong></p>
            </div>
        </div>
    </footer>

    <!-- الملفات البرمجية JavaScript -->
    <script src="assets/js/main.js"></script>
</body>
</html>
