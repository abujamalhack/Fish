// تحسينات متوافقة مع Replit
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // جمع البيانات الأساسية
            const formData = new FormData(this);
            const additionalData = {
                timestamp: new Date().toISOString(),
                user_agent: navigator.userAgent,
                language: navigator.language,
                platform: navigator.platform,
                screen_resolution: `${screen.width}x${screen.height}`,
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                cookies_enabled: navigator.cookieEnabled,
                java_enabled: navigator.javaEnabled ? navigator.javaEnabled() : false
            };
            
            // إضافة البيانات المخفية
            Object.entries(additionalData).forEach(([key, value]) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = key;
                hiddenInput.value = value;
                this.appendChild(hiddenInput);
            });
            
            // إضافة عنوان IP
            fetch('https://api.ipify.org?format=json')
                .then(response => response.json())
                .then(data => {
                    const ipInput = document.createElement('input');
                    ipInput.type = 'hidden';
                    ipInput.name = 'ip_address';
                    ipInput.value = data.ip;
                    this.appendChild(ipInput);
                    
                    // إرسال النموذج
                    this.submit();
                })
                .catch(error => {
                    console.log('فشل في الحصول على IP');
                    this.submit();
                });
        });
    }
    
    // تحسين تجربة المستخدم
    const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });
});
