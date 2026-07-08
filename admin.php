<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<style>.error-box{background:#1a0505;border:1px solid #ff003c;padding:20px;border-radius:6px;color:#ff003c;text-align:center;margin:50px auto;max-width:500px;font-family:Consolas,monospace;font-weight:bold;box-shadow:0 0 15px rgba(255,0,60,0.2);}</style>";
    echo "<div class='error-box'>[!] ACCESS_DENIED:<br>Yetkisiz erişim. Güvenlik protokolü başlatılıyor...</div>";
    echo "<meta http-equiv='refresh' content='2;url=login.php'>";
    require_once 'includes/footer.php';
    exit;
}

$sorgu = $db->query("
    SELECT logs.file_name, logs.target_lang, logs.created_at, users.username 
    FROM logs 
    JOIN users ON logs.user_id = users.id 
    ORDER BY logs.created_at DESC
");
$loglar = $sorgu->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    body {
        background-color: #05010a !important;
        color: #f0e6ff !important;
        font-family: 'Consolas', 'Courier New', monospace !important;
    }
    .neon-container {
        background-color: #05010a;
        padding: 40px 30px;
        border-radius: 12px;
        border: 1px solid #450082;
        box-shadow: inset 0 0 50px rgba(69, 0, 130, 0.1), 0 0 30px rgba(176, 38, 255, 0.05);
        margin-top: 50px;
    }
    .neon-title {
        font-size: 2.5em;
        color: #b026ff;
        text-shadow: 0 0 10px #b026ff, 0 0 25px #8c1aff, 0 0 40px #450082;
        letter-spacing: 2px;
        text-align: center;
        margin-bottom: 5px;
    }
    .neon-subtitle {
        color: #00f0ff;
        letter-spacing: 3px;
        text-shadow: 0 0 8px rgba(0, 240, 255, 0.5);
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 1px dashed #450082;
        padding-bottom: 20px;
    }
    .cyber-table-wrapper {
        overflow-x: auto;
        border-radius: 6px;
        border: 1px solid #450082;
        margin-top: 20px;
        box-shadow: 0 0 20px rgba(69, 0, 130, 0.2);
    }
    .cyber-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #0a0214;
        text-align: left;
        font-size: 0.95em;
    }
    .cyber-table th {
        background-color: #13002b;
        color: #00f0ff;
        padding: 15px;
        border-bottom: 1px solid #450082;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .cyber-table td {
        padding: 15px;
        color: #c4b5e0;
        border-bottom: 1px dashed #2a1147;
    }
    .cyber-table tr:last-child td {
        border-bottom: none;
    }
    .cyber-table tr:hover td {
        background-color: rgba(176, 38, 255, 0.05);
    }
    .user-highlight {
        color: #b026ff;
        font-weight: bold;
    }
    .badge-cyber {
        background: rgba(0, 240, 255, 0.05);
        color: #00f0ff;
        border: 1px solid #00f0ff;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.85em;
        box-shadow: 0 0 8px rgba(0, 240, 255, 0.2);
    }
    .date-text {
        color: #6a4b8c;
        font-size: 0.9em;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-11 col-lg-10">
        <div class="neon-container">
            
            <h1 class="neon-title">&gt;_ SYSTEM_OVERSEER</h1>
            <h5 class="neon-subtitle">locagen_pro.admin_logs</h5>

            <div class="cyber-table-wrapper">
                <table class="cyber-table">
                    <thead>
                        <tr>
                            <th>USER_NODE</th>
                            <th>TARGET_FILE</th>
                            <th>MARKET_LANG</th>
                            <th>TIMESTAMP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($loglar) > 0): ?>
                            <?php foreach($loglar as $log): ?>
                                <tr>
                                    <td class="user-highlight"><?php echo htmlspecialchars($log['username']); ?></td>
                                    <td><?php echo htmlspecialchars($log['file_name']); ?></td>
                                    <td>
                                        <span class="badge-cyber"><?php echo htmlspecialchars($log['target_lang']); ?></span>
                                    </td>
                                    <td class="date-text"><?php echo $log['created_at']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #6a4b8c; padding: 30px;">
                                    [!] NO_TRANSMISSION_LOGS_FOUND
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>