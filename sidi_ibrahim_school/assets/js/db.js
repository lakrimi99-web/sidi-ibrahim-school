/**
 * ========================================================
 * محرك قاعدة البيانات المحلية (IndexedDB Local Database Engine)
 * مدرسة سيدي إبراهيم الرائدة
 * Database Name: SidiIbrahimSchoolDB
 * Store Name: posts
 * ========================================================
 */

const DB_NAME = 'SidiIbrahimSchoolDB';
const DB_VERSION = 1;

// البيانات الأولية الافتراضية لقاعدة البيانات
const defaultSeedPosts = [
    {
        id: 1,
        title: "انطلاق فعاليات معرض العلوم والابتكار للعام الدراسي 2026",
        category: "الأنشطة المدرسية",
        date: "2026/08/01",
        views: 142,
        image: "uploads/robotics.jpg",
        excerpt: "شهدت مدرسة سيدي إبراهيم الرائدة افتتاح معرض العلوم السنوي بمشاركة واسعة من التلاميذ ومشاريع مبتكرة في الذكاء الاصطناعي والروبوتات.",
        content: `<p>ببالغ الفخر والاعتزاز، افتتحت إدارة <strong>مدرسة سيدي إبراهيم الرائدة</strong> صباح اليوم فعاليات المعرض السنوي للعلوم والابتكار تحت شعار <em>"علماء الغد يبنون المستقبل"</em>.</p><p>وقد شمل المعرض أكثر من 40 مشروعاً علمياً قدمها طلاب المراحل المختلفة، تنوعت بين تطبيقات الذكاء الاصطناعي، ومشاريع الطاقة المتجددة، والابتكارات الروبوتية المتميزة التي لاقت إشادة كبيرة من لجنة التحكيم والزوار من أولياء الأمور والضيوف.</p><p>وأكد مدير المدرسة في كلمته الافتتاحية أن المدرسة تولي اهتماماً بالغاً بالتعليم التفاعلي وتنمية مهارات التفكير النقدي لدى الطلاب، موجهاً الشكر لكافة الأطر التربوية والإدارية على جهودهم الجبارة في إنجاح هذا الحدث العلمي المتميز.</p>`
    },
    {
        id: 2,
        title: "حفل تكريم المتفوقين في الأنشطة المدرسية والنتائج الدراسية",
        category: "تكريم وتفوق",
        date: "2026/08/05",
        views: 98,
        image: "uploads/honor.jpg",
        excerpt: "أقامت مدرسة سيدي إبراهيم الرائدة حفلها السنوي لتكريم التلاميذ المتفوقين دراسياً والمتميزين في الأنشطة والمسابقات الرياضية والثقافية.",
        content: `<p>في أجواء مفعمة بالفرح والاعتزاز، نظمت <strong>مدرسة سيدي إبراهيم الرائدة</strong> حفل التكريم السنوي لتتويج نخبة من تلامذتها المتفوقين الحاصلين على أعلى المراتب الدراسية خلال الفضل الدراسي المنصرم.</p><p>تضمن الحفل فقرات فنية وثقافية متنوعة قدمها كورال المدرسة، بالإضافة إلى عرض مرئي يوثق أبرز المحطات والأنشطة المدرسية على مدار العام. وتم توزيع شهادات التقدير والجوائز التشجيعية على المتفوقين بحضور أولياء الأمور والطاقم التدريسي.</p><p>نهنئ كافة ابنائنا وبناتنا المتفوقين، ونتمنى لهم دوام النجاح والتميز في مسيرتهم التعليمية.</p>`
    },
    {
        id: 3,
        title: "إعلان هام: فتح باب التسجيل وتحديث البيانات للعام الدراسي الجديد",
        category: "إعلانات الإدارة",
        date: "2026/08/08",
        views: 215,
        image: "assets/images/hero.jpg",
        excerpt: "تعلن إدارة مدرسة سيدي إبراهيم الرائدة عن فتح باب التسجيل للمستجدين وتحديث بيانات التلاميذ القدامى ابتداءً من الأسبوع القادم.",
        content: `<p>تنهي إدارة <strong>مدرسة سيدي إبراهيم الرائدة</strong> إلى علم كافة أولياء الأمور الكرام أن باب التسجيل وإعادة التسجيل للعام الدراسي الجديد سيكون مفتوحاً ابتداءً من يوم الاثنين القادم بمقر الإدارة.</p><p><strong>الوثائق المطلوبة للمستجدين:</strong></p><ul><li>نسخة من عقد الازدياد للتلميذ.</li><li>عدد 4 صور شمسية حديثة.</li><li>نسخة من البطاقة الوطنية لولي الأمر.</li><li>الملف الصحي مدرسي.</li></ul><p>يسعدنا استقبالكم طيلة أيام الأسبوع من الساعة 8:30 صباحاً وحتى 4:00 مساءً.</p>`
    }
];

// فتح والاتصال بقاعدة البيانات المحلية IndexedDB
function initDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains('posts')) {
                const store = db.createObjectStore('posts', { keyPath: 'id' });
                defaultSeedPosts.forEach(post => store.add(post));
            }
        };

        request.onsuccess = (event) => {
            const db = event.target.result;
            // التحقق من تعبئة البيانات إذا كانت القاعدة فارغة
            const tx = db.transaction('posts', 'readonly');
            const store = tx.objectStore('posts');
            const countReq = store.count();

            countReq.onsuccess = () => {
                if (countReq.result === 0) {
                    const writeTx = db.transaction('posts', 'readwrite');
                    const writeStore = writeTx.objectStore('posts');
                    defaultSeedPosts.forEach(post => writeStore.add(post));
                }
                resolve(db);
            };
        };

        request.onerror = (event) => {
            console.error('Error opening IndexedDB:', event.target.error);
            reject(event.target.error);
        };
    });
}

// 1. جلب كافة المقالات المخزنة بداخل قاعدة البيانات المحلية (مترتبة من الأحدث إلى الأقدم)
async function getAllPostsFromDB() {
    try {
        const db = await initDB();
        return new Promise((resolve) => {
            const tx = db.transaction('posts', 'readonly');
            const store = tx.objectStore('posts');
            const request = store.getAll();

            request.onsuccess = () => {
                let posts = request.result || [];
                posts.sort((a, b) => b.id - a.id);
                resolve(posts);
            };

            request.onerror = () => {
                resolve(defaultSeedPosts);
            };
        });
    } catch (e) {
        return defaultSeedPosts;
    }
}

// 2. جلب مقال واحد بالمعرف ID
async function getPostByIdFromDB(id) {
    try {
        const db = await initDB();
        return new Promise((resolve) => {
            const tx = db.transaction('posts', 'readonly');
            const store = tx.objectStore('posts');
            const request = store.get(id);

            request.onsuccess = () => {
                resolve(request.result || null);
            };

            request.onerror = () => {
                resolve(null);
            };
        });
    } catch (e) {
        return null;
    }
}

// 3. إدخال مقال جديد بقاعدة البيانات المحلية
async function addPostToDB(postData) {
    const db = await initDB();
    const allPosts = await getAllPostsFromDB();
    const nextId = allPosts.length > 0 ? Math.max(...allPosts.map(p => p.id)) + 1 : 1;
    postData.id = nextId;

    return new Promise((resolve, reject) => {
        const tx = db.transaction('posts', 'readwrite');
        const store = tx.objectStore('posts');
        const request = store.add(postData);

        request.onsuccess = () => resolve(postData);
        request.onerror = (e) => reject(e.target.error);
    });
}

// 4. تحديث مقال موجود بقاعدة البيانات المحلية
async function updatePostInDB(postData) {
    const db = await initDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction('posts', 'readwrite');
        const store = tx.objectStore('posts');
        const request = store.put(postData);

        request.onsuccess = () => resolve(postData);
        request.onerror = (e) => reject(e.target.error);
    });
}

// 5. حذف مقال من قاعدة البيانات المحلية بواسطة ID
async function deletePostFromDB(id) {
    const db = await initDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction('posts', 'readwrite');
        const store = tx.objectStore('posts');
        const request = store.delete(id);

        request.onsuccess = () => resolve(true);
        request.onerror = (e) => reject(e.target.error);
    });
}
