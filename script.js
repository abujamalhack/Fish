// تهيئة الموقع بعد التحميل
document.addEventListener('DOMContentLoaded', function() {
    // إخفاء شاشة التحميل بعد تحميل الصفحة
    setTimeout(function() {
        const loader = document.querySelector('.loader');
        loader.classList.add('fade-out');
        
        // إظهار المحتوى الرئيسي بعد إخفاء الشاشة التحميل
        setTimeout(function() {
            loader.style.display = 'none';
            document.querySelector('.main-content').style.opacity = '1';
        }, 500);
    }, 2000);
    
    // تهيئة شريط التقدم في شاشة التحميل
    const progressBar = document.querySelector('.loader-progress');
    let width = 0;
    const interval = setInterval(function() {
        if (width >= 100) {
            clearInterval(interval);
        } else {
            width++;
            progressBar.style.width = width + '%';
        }
    }, 20);
    
    // تفعيل التنقل السلس
    initSmoothScroll();
    
    // تفعيل شريط التنقل الثابت
    initStickyNav();
    
    // تفعيل القائمة المتنقلة
    initMobileMenu();
    
    // تفعيل الرسوم المتحركة للعناصر عند التمرير
    initScrollAnimations();
    
    // تفعيل نماذج الاتصال
    initForms();
});

// التنقل السلس
function initSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 80;
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// شريط التنقل الثابت
function initStickyNav() {
    const header = document.querySelector('header');
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            header.classList.add('scrolled');
            navbar.style.padding = '10px 0';
        } else {
            header.classList.remove('scrolled');
            navbar.style.padding = '20px 0';
        }
    });
}

// القائمة المتنقلة
function initMobileMenu() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    const navLinks = document.querySelectorAll('.nav-link');
    
    hamburger.addEventListener('click', function() {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
    });
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        });
    });
}

// الرسوم المتحركة عند التمرير
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.service-card, .team-member, .stat-card, .contact-card, .feature');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(element);
    });
}

// نماذج الاتصال
function initForms() {
    const contactForm = document.querySelector('.message-form');
    const reportForm = document.querySelector('.report-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('شكراً لك! تم إرسال رسالتك بنجاح وسنقوم بالرد عليك في أقرب وقت ممكن.', 'success');
            this.reset();
        });
    }
    
    if (reportForm) {
        reportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('شكراً لك! تم استلام بلاغك وسنقوم بمراجعته واتخاذ الإجراءات اللازمة.', 'success');
            this.reset();
        });
    }
}

// إظهار الإشعارات
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // إضافة تنسيقات الإشعار
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? 'rgba(0, 204, 136, 0.9)' : 'rgba(255, 51, 102, 0.9)'};
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        z-index: 10000;
        transform: translateX(400px);
        transition: transform 0.4s ease;
        max-width: 400px;
    `;
    
    // إظهار الإشعار
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // إخفاء الإشعار بعد 5 ثوان
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 400);
    }, 5000);
}

// تأثير الكتابة للعناوين
function initTypewriter() {
    const elements = document.querySelectorAll('.typewriter');
    
    elements.forEach(element => {
        const text = element.textContent;
        element.textContent = '';
        
        let i = 0;
        const typeWriter = () => {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 100);
            }
        };
        
        // بدء تأثير الكتابة عندما يصبح العنصر مرئياً
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    typeWriter();
                    observer.unobserve(entry.target);
                }
            });
        });
        
        observer.observe(element);
    });
}

// تهيئة تأثيرات إضافية عند التمرير
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const parallaxElements = document.querySelectorAll('.parallax');
    
    parallaxElements.forEach(element => {
        const speed = element.dataset.speed || 0.5;
        element.style.transform = `translateY(${scrolled * speed}px)`;
    });
});

// تأثيرات إضافية للبطاقات عند التمرير
const cards = document.querySelectorAll('.cyber-card, .service-card, .team-member');
cards.forEach(card => {
    card.addEventListener('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const angleY = (x - centerX) / 20;
        const angleX = (centerY - y) / 20;
        
        this.style.transform = `perspective(1000px) rotateX(${angleX}deg) rotateY(${angleY}deg)`;
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
    });
});