<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$hata = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kullanici_adi = $_POST['username'];
    $sifre = md5($_POST['password']);

    $sorgu = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $sorgu->execute([$kullanici_adi, $sifre]);
    $kullanici = $sorgu->fetch(PDO::FETCH_ASSOC);

    if ($kullanici) {
        $_SESSION['user_id'] = $kullanici['id'];
        $_SESSION['username'] = $kullanici['username'];
        $_SESSION['role'] = $kullanici['role'];
        if ($kullanici['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit;
    }else {
        $hata = "Hatalı kullanıcı adı veya şifre!";
    }
}
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
        margin-top: 80px;
    }
    .neon-title {
        font-size: 2.2em;
        color: #b026ff;
        text-shadow: 0 0 10px #b026ff, 0 0 25px #8c1aff;
        letter-spacing: 2px;
        text-align: center;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .neon-subtitle {
        color: #00f0ff;
        letter-spacing: 3px;
        text-shadow: 0 0 8px rgba(0, 240, 255, 0.5);
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 1px dashed #450082;
        padding-bottom: 20px;
        font-size: 1.1em;
    }
    .form-control {
        background-color: #0a0214 !important;
        border: 1px solid #450082 !important;
        color: #00f0ff !important;
        border-radius: 6px;
        font-family: 'Consolas', monospace;
        padding: 12px;
    }
    .form-control:focus {
        border-color: #b026ff !important;
        box-shadow: 0 0 15px rgba(176, 38, 255, 0.3) !important;
        outline: none;
    }
    .form-label {
        color: #a28bce !important;
        font-weight: bold;
        font-size: 1.1em;
        margin-bottom: 10px;
    }
    .form-label span {
        color: #00f0ff;
    }
    .btn-cyber {
        background: transparent;
        color: #b026ff;
        border: 1px solid #b026ff;
        border-radius: 6px;
        font-family: 'Consolas', monospace;
        font-weight: bold;
        font-size: 1.2em;
        padding: 15px;
        width: 100%;
        transition: 0.3s all;
        box-shadow: 0 0 15px rgba(176, 38, 255, 0.1);
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .btn-cyber:hover {
        background: #b026ff;
        color: #000;
        box-shadow: 0 0 25px rgba(176, 38, 255, 0.6);
        cursor: pointer;
    }
    .error-box {
        background: #1a0505;
        border: 1px solid #ff003c;
        padding: 15px;
        border-radius: 6px;
        color: #ff003c;
        text-align: center;
        margin-bottom: 25px;
        box-shadow: 0 0 15px rgba(255,0,60,0.2);
        font-weight: bold;
        letter-spacing: 1px;
    }
    .test-accounts {
        margin-top: 30px;
        padding: 15px;
        background: #0a0214;
        border: 1px dashed #450082;
        border-radius: 6px;
        color: #6a4b8c;
        font-size: 0.9em;
        text-align: center;
    }
    .test-accounts span {
        color: #00f0ff;
        font-weight: bold;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="neon-container">
            
            <div class="neon-title">&gt;_ SYSTEM_AUTH</div>
            <div class="neon-subtitle">locagen_pro.login</div>

            <?php if($hata): ?>
                <div class="error-box">[!] ACCESS_DENIED: <br> <?php echo $hata; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="form-label"><span>[+]</span> USER_ID:</label>
                    <input type="text" name="username" class="form-control" autocomplete="off" required>
                </div>
                <div class="mb-5">
                    <label class="form-label"><span>[+]</span> PASSWORD_KEY:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn-cyber">
                    [ INITIATE_LOGIN ]
                </button>
            </form>

            <div class="test-accounts">
                // AUTHORIZED_TEST_NODES //<br><br>
                Admin Auth : <span>admin</span> / <span>123456</span><br>
                Dev Auth &nbsp;: <span>dev_user</span> / <span>123456</span>
            </div>

        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>