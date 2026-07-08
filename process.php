<?php
session_start();
require_once 'includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Oturum süresi doldu. Lütfen tekrar giriş yapın."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['lang_file'])) {
    $hedef_dil = $_POST['target_lang'];
    $dosya_adi = $_FILES['lang_file']['name'];
    $gecici_yol = $_FILES['lang_file']['tmp_name'];
    $dosya_uzantisi = pathinfo($dosya_adi, PATHINFO_EXTENSION);

    if ($dosya_uzantisi != 'json') {
        echo json_encode(["status" => "error", "message" => "Lütfen sadece .json uzantılı dosya yükleyin!"]);
        exit;
    }

    $json_icerik = file_get_contents($gecici_yol);
    $dil_dizisi = json_decode($json_icerik, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["status" => "error", "message" => "Bozuk veya geçersiz bir JSON dosyası."]);
        exit;
    }

    $env_yolu = __DIR__ . '/.env';
    if (!file_exists($env_yolu)) {
        echo json_encode(["status" => "error", "message" => "Sistem Kritik Hatası: .env dosyası bulunamadı!"]);
        exit;
    }

    $env_verileri = parse_ini_file($env_yolu);
    $apiKey = $env_verileri['GEMINI_API_KEY'] ?? '';

    if (empty($apiKey)) {
        echo json_encode(["status" => "error", "message" => "Sistem Kritik Hatası: API anahtarı .env içinde bulunamadı!"]);
        exit;
    }

    $model = "gemini-2.5-flash";

    $json_metni = json_encode($dil_dizisi, JSON_UNESCAPED_UNICODE);

    $prompt = "Sen profesyonel bir oyun yerelleştirme uzmanısın. Aşağıdaki JSON verisindeki sadece değerleri (values) '$hedef_dil' diline çevir. 
    Oyun kelimelerini çevirirken karanlık ve taktiksel atmosferi yansıt. Robotik veya Google Translate gibi saçma çeviriler YAPMA. Kılıç, zırh, can iksiri gibi terimleri tam bir oyuncu ağzıyla çevir.
    KURALLAR:
    1. JSON anahtarlarına (keys) ASLA dokunma.
    2. Yanıtın SADECE geçerli bir JSON formatında olmalı.
    İşte JSON: \n" . $json_metni;

    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
    $url = preg_replace('/\s+/', '', $url);

    $data = [
        "contents" => [
            ["parts" => [["text" => $prompt]]]
        ],
        "generationConfig" => [
            "responseMimeType" => "application/json"
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); 
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1); 

    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo json_encode(["status" => "error", "message" => "cURL Hatası: " . curl_error($ch)]);
        exit;
    }
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['error'])) {
        echo json_encode(["status" => "error", "message" => "API Hatası: " . $result['error']['message']]);
        exit;
    }

    $cevrilmis_metin = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
    $cevrilmis_dizi = json_decode($cevrilmis_metin, true);

    if (!$cevrilmis_dizi || json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["status" => "error", "message" => "Yapay Zeka geçersiz bir veri döndürdü."]);
        exit;
    }

    $yeni_json = json_encode($cevrilmis_dizi, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    $yeni_dosya_adi = "translated_" . time() . ".json";
    $yeni_dosya_yolu = "uploads/" . $yeni_dosya_adi;
    file_put_contents($yeni_dosya_yolu, $yeni_json);
    
    $log_ekle = $db->prepare("INSERT INTO logs (user_id, file_name, target_lang) VALUES (?, ?, ?)");
    $log_ekle->execute([$_SESSION['user_id'], $dosya_adi, $hedef_dil]);

    echo json_encode([
        "status" => "success",
        "file_url" => $yeni_dosya_yolu,
        "target_lang" => $hedef_dil
    ]);
    exit;

} else {
    echo json_encode(["status" => "error", "message" => "Geçersiz istek metodu."]);
    exit;
}