/**
 * ========================================================
 * التفاعلات والبرمجيات الشاملة - مدرسة سيدي إبراهيم الرائدة
 * Dynamic JavaScript & IndexedDB Local Database Sync
 * ========================================================
 */

document.addEventListener('DOMContentLoaded', async () => {

    // 1. القائمة الجانبية للموبايل
    const mobileToggle = document.getElementById('mobileToggle');
    const navMenu = document.getElementById('navMenu');
    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            const icon = mobileToggle.querySelector('i');
            if (icon) icon.className = navMenu.classList.contains('active') ? 'fas fa-times' : 'fas fa-bars';
        });
    }

    // 2. تظليل رابط الصفحة الحالية
    const currentPath = window.location.pathname.split('/').pop() || 'index.html';
    document.querySelectorAll('.nav-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPath || (currentPath === '' && (href === 'index.html' || href === 'index.php'))) {
            link.classList.add('active');
        }
    });

    // 3. رندر مقالات الصفحة الرئيسية ديناميكياً من قاعدة البيانات المحلية IndexedDB
    const homePostsContainer = document.getElementById('dynamicHomePostsGrid');
    if (homePostsContainer) {
        const posts = await getAllPostsFromDB();
        const latestThree = posts.slice(0, 3);
        homePostsContainer.innerHTML = latestThree.map(post => renderPostCardHTML(post)).join('');
    }

    // 4. رندر كافة مقالات صفحة الأخبار من قاعدة البيانات المحلية IndexedDB
    const newsPostsContainer = document.getElementById('dynamicNewsPostsGrid');
    if (newsPostsContainer) {
        const posts = await getAllPostsFromDB();
        renderNewsGrid(posts);

        const searchInput = document.getElementById('newsSearchInput');
        const catSelect = document.getElementById('newsCategorySelect');

        if (searchInput || catSelect) {
            const filterNews = async () => {
                const q = searchInput ? searchInput.value.toLowerCase().trim() : '';
                const cat = catSelect ? catSelect.value : '';
                const currentPosts = await getAllPostsFromDB();

                const filtered = currentPosts.filter(p => {
                    const matchesQ = !q || p.title.toLowerCase().includes(q) || (p.excerpt && p.excerpt.toLowerCase().includes(q));
                    const matchesCat = !cat || p.category === cat;
                    return matchesQ && matchesCat;
                });
                renderNewsGrid(filtered);
            };

            if (searchInput) searchInput.addEventListener('input', filterNews);
            if (catSelect) catSelect.addEventListener('change', filterNews);
        }
    }

    // 5. عرض تفاصيل المقال من قاعدة البيانات المحلية (post.html?id=X)
    if (window.location.pathname.endsWith('post.html')) {
        const urlParams = new URLSearchParams(window.location.search);
        const postId = parseInt(urlParams.get('id')) || 1;
        let post = await getPostByIdFromDB(postId);

        if (!post) {
            const all = await getAllPostsFromDB();
            post = all[0];
        }

        if (post) {
            document.title = post.title + ' | مدرسة سيدي إبراهيم الرائدة';
            const titleEl = document.getElementById('postDetailTitle');
            const catEl = document.getElementById('postDetailCategory');
            const dateEl = document.getElementById('postDetailDate');
            const viewsEl = document.getElementById('postDetailViews');
            const imgEl = document.getElementById('postDetailImage');
            const contentEl = document.getElementById('postDetailContent');
            const breadcrumbEl = document.getElementById('postBreadcrumbTitle');

            if (titleEl) titleEl.textContent = post.title;
            if (catEl) catEl.textContent = post.category;
            if (dateEl) dateEl.textContent = post.date;
            if (viewsEl) viewsEl.textContent = (post.views || 100) + 1;
            if (imgEl) imgEl.src = post.image || 'assets/images/hero.jpg';
            if (contentEl) contentEl.innerHTML = post.content;
            if (breadcrumbEl) breadcrumbEl.textContent = post.title;

            post.views = (post.views || 100) + 1;
            await updatePostInDB(post);
        }
    }

    // 6. رندر جدول الإدارة في (admin_dashboard.html)
    const adminTableBody = document.getElementById('adminPostsTableBody');
    if (adminTableBody) {
        const posts = await getAllPostsFromDB();
        renderAdminPostsTable(posts);
        
        const countEl = document.getElementById('adminTotalPostsCount');
        if (countEl) countEl.textContent = posts.length;
    }

    // 7. حفظ وإضافة المقال في قاعدة البيانات المحلية (admin_add_post.html)
    const addPostForm = document.getElementById('addPostForm');
    if (addPostForm) {
        const urlParams = new URLSearchParams(window.location.search);
        const editId = parseInt(urlParams.get('edit'));

        if (editId) {
            const existingPost = await getPostByIdFromDB(editId);
            if (existingPost) {
                document.getElementById('postTitleInput').value = existingPost.title;
                document.getElementById('postCategoryInput').value = existingPost.category;
                document.getElementById('postExcerptInput').value = existingPost.excerpt || '';
                document.getElementById('postContentInput').value = existingPost.content.replace(/<[^>]*>/g, '');
                
                const headingEl = document.getElementById('formHeadingText');
                if (headingEl) headingEl.textContent = 'تعديل المقال رقم #' + editId;
            }
        }

        addPostForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const title = document.getElementById('postTitleInput').value.trim();
            const category = document.getElementById('postCategoryInput').value;
            const excerpt = document.getElementById('postExcerptInput').value.trim();
            const content = document.getElementById('postContentInput').value.trim();
            const imageInput = document.getElementById('postImageInput');

            if (title && content) {
                const processSave = async (imageSrc) => {
                    const today = new Date();
                    const dateStr = today.getFullYear() + '/' + String(today.getMonth() + 1).padStart(2, '0') + '/' + String(today.getDate()).padStart(2, '0');

                    if (editId) {
                        const existing = await getPostByIdFromDB(editId);
                        if (existing) {
                            existing.title = title;
                            existing.category = category;
                            existing.excerpt = excerpt || title;
                            existing.content = `<p>${content.replace(/\n/g, '<br>')}</p>`;
                            if (imageSrc) existing.image = imageSrc;
                            await updatePostInDB(existing);
                        }
                    } else {
                        const newPost = {
                            title: title,
                            category: category,
                            date: dateStr,
                            views: 1,
                            image: imageSrc || "assets/images/hero.jpg",
                            excerpt: excerpt || title.substring(0, 110) + '...',
                            content: `<p>${content.replace(/\n/g, '<br>')}</p>`
                        };
                        await addPostToDB(newPost);
                    }

                    alert(editId ? 'تم تعديل المقال بنجاح بقاعدة البيانات المحلية!' : 'تم تخزين ونشر المقال في قاعدة البيانات المحلية بنجاح وسيبقى ظاهراً في الموقع حتى يتم حذفه من الإدارة!');
                    window.location.href = 'admin_dashboard.html';
                };

                if (imageInput && imageInput.files && imageInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = async function (evt) {
                        await processSave(evt.target.result);
                    };
                    reader.readAsDataURL(imageInput.files[0]);
                } else {
                    await processSave(null);
                }
            }
        });
    }

    // 8. المعاينة الفورية للصورة
    const imageInput = document.getElementById('postImageInput');
    const imagePreview = document.getElementById('postImagePreview');
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 9. تسجيل دخول الإدارة المحمي (sidibrahim / 159)
    const adminLoginForm = document.getElementById('adminLoginForm');
    if (adminLoginForm) {
        adminLoginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const user = document.getElementById('adminUserInput').value.trim();
            const pass = document.getElementById('adminPassInput').value.trim();

            if (user === 'sidibrahim' && pass === '159') {
                sessionStorage.setItem('sidi_admin_logged', 'true');
                window.location.href = 'admin_dashboard.html';
            } else {
                const errBox = document.getElementById('loginErrorAlert');
                if (errBox) {
                    errBox.style.display = 'flex';
                    errBox.innerHTML = '<i class="fas fa-exclamation-triangle"></i><div>اسم المستخدم أو كلمة المرور غير صحيحة! (استخدم sidibrahim و 159)</div>';
                } else {
                    alert('اسم المستخدم أو كلمة المرور غير صحيحة! (استخدم sidibrahim و 159)');
                }
            }
        });
    }

});

// دالة توليد HTML بطاقة المقال
function renderPostCardHTML(post) {
    return `
        <article class="post-card">
            <div class="post-thumb">
                <span class="post-category">${post.category || 'أخبار'}</span>
                <img src="${post.image || 'assets/images/hero.jpg'}" alt="${post.title}" onerror="this.src='assets/images/hero.jpg'">
            </div>
            <div class="post-body">
                <div class="post-meta">
                    <span><i class="far fa-calendar-alt"></i> ${post.date}</span>
                    <span><i class="far fa-eye"></i> ${post.views || 0} مشاهدة</span>
                </div>
                <h3 class="post-title">${post.title}</h3>
                <p class="post-excerpt">${post.excerpt || ''}</p>
                <div class="post-footer">
                    <a href="post.html?id=${post.id}" class="btn-readmore">
                        اقرأ المزيد <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </article>
    `;
}

// دالة رندر شبكة صفحة الأخبار
function renderNewsGrid(posts) {
    const container = document.getElementById('dynamicNewsPostsGrid');
    if (!container) return;

    if (posts.length === 0) {
        container.innerHTML = `<div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: #FFF; border-radius: 12px; border: 1px solid #E2E8F0;"><h3>لا توجد أخبار تنطبق مع خيارات البحث</h3></div>`;
        return;
    }

    container.innerHTML = posts.map(post => renderPostCardHTML(post)).join('');
}

// دالة رندر جدول الإدارة
function renderAdminPostsTable(posts) {
    const tableBody = document.getElementById('adminPostsTableBody');
    if (!tableBody) return;

    if (posts.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="8" style="text-align: center; padding: 2rem;">لا توجد مقالات منشورة حالياً بقاعدة البيانات المحلية.</td></tr>`;
        return;
    }

    tableBody.innerHTML = posts.map(post => `
        <tr>
            <td><strong>#${post.id}</strong></td>
            <td>
                <img src="${post.image || 'assets/images/hero.jpg'}" alt="Thumb" style="width: 55px; height: 42px; object-fit: cover; border-radius: 4px;" onerror="this.src='assets/images/hero.jpg'">
            </td>
            <td>
                <a href="post.html?id=${post.id}" target="_blank" style="font-weight: 700; color: var(--dark);">
                    ${post.title}
                </a>
            </td>
            <td>
                <span class="badge" style="background: var(--primary-light); color: var(--primary);">
                    ${post.category}
                </span>
            </td>
            <td style="font-size: 0.88rem; color: var(--gray-text);">${post.date}</td>
            <td><span style="font-weight: 700; color: var(--secondary);"><i class="far fa-eye"></i> ${post.views || 0}</span></td>
            <td><span class="badge badge-success">منشور</span></td>
            <td style="text-align: center;">
                <div style="display: flex; gap: 0.4rem; justify-content: center;">
                    <a href="post.html?id=${post.id}" target="_blank" class="action-btn action-btn-edit" title="معاينة" style="background: #ECFDF5; color: #047857;">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="admin_add_post.html?edit=${post.id}" class="action-btn action-btn-edit" title="تعديل">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <button onclick="handleDeletePost(${post.id})" class="action-btn action-btn-delete" title="حذف">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// دالة حذف المقال من قاعدة البيانات المحلية IndexedDB
async function handleDeletePost(id) {
    if (confirm('هل أنت تأكد من رغبتك في حذف هذا المقال نهائياً من قاعدة البيانات؟')) {
        await deletePostFromDB(id);
        const updatedPosts = await getAllPostsFromDB();
        renderAdminPostsTable(updatedPosts);
        
        const countEl = document.getElementById('adminTotalPostsCount');
        if (countEl) countEl.textContent = updatedPosts.length;
    }
}
