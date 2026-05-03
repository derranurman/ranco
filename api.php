<?php
// ═══════════════════════════════════════
//  Ranco Auto — API Backend
//  GET  api.php         → ambil semua data
//  POST api.php         → simpan satu tabel
//  POST api.php?action=reset → reset ke data awal
// ═══════════════════════════════════════

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'config.php';

// ── Koneksi DB ──
try {
    $dsn = "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Koneksi database gagal: ' . $e->getMessage(),
                      'hint'  => 'Pastikan XAMPP MySQL sudah aktif dan database ranco_auto sudah dibuat.']);
    exit;
}

$action = $_GET['action'] ?? '';

// ── GET: Ambil seluruh data ──
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT `tbl_key`, `tbl_value` FROM app_data");
    $rows = $stmt->fetchAll();
    $db   = [];
    foreach ($rows as $row) {
        $db[$row['tbl_key']] = json_decode($row['tbl_value'], true);
    }
    echo json_encode($db, JSON_UNESCAPED_UNICODE);
    exit;
}

// ── POST: Simpan satu tabel ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Reset ke data awal
    if ($action === 'reset') {
        $pdo->exec("DELETE FROM app_data");
        seedData($pdo);
        echo json_encode(['success' => true, 'message' => 'Data berhasil direset ke awal.']);
        exit;
    }

    $body = json_decode(file_get_contents('php://input'), true);

    if (!isset($body['key']) || !array_key_exists('data', $body)) {
        http_response_code(400);
        echo json_encode(['error' => 'Parameter key atau data tidak ditemukan.']);
        exit;
    }

    $allowedKeys = ['users','stok','penjualan','orderLive','packKeluar','refundLog','potongan'];
    if (!in_array($body['key'], $allowedKeys)) {
        http_response_code(400);
        echo json_encode(['error' => 'Key tidak diizinkan: ' . $body['key']]);
        exit;
    }

    $key   = $body['key'];
    $value = json_encode($body['data'], JSON_UNESCAPED_UNICODE);

    $stmt = $pdo->prepare(
        "INSERT INTO app_data (`tbl_key`, `tbl_value`) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE `tbl_value` = VALUES(`tbl_value`), `updated_at` = NOW()"
    );
    $stmt->execute([$key, $value]);

    echo json_encode(['success' => true, 'key' => $key, 'rows' => is_array($body['data']) ? count($body['data']) : 1]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method tidak diizinkan.']);

// ── Seed data awal ──
function seedData(PDO $pdo) {
    $init = [
        'users' => [
            ['id'=>'u1','username'=>'admin',  'password'=>'admin123', 'role'=>'admin',    'nama'=>'Administrator','aktif'=>true],
            ['id'=>'u2','username'=>'dayu',   'password'=>'dayu123',  'role'=>'hostlive', 'nama'=>'Dayu',         'aktif'=>true],
            ['id'=>'u3','username'=>'nia',    'password'=>'nia123',   'role'=>'hostlive', 'nama'=>'Nia',          'aktif'=>true],
            ['id'=>'u4','username'=>'amanda', 'password'=>'amanda123','role'=>'hostlive', 'nama'=>'Amanda',       'aktif'=>true],
            ['id'=>'u5','username'=>'packing','password'=>'pack123',  'role'=>'packing',  'nama'=>'Tim Packing',  'aktif'=>true],
        ],
        'stok' => [
            ['id'=>'s1','kode'=>'A1','nama'=>'STIR R14 SPARCO LIS KUNING','hargaBeli'=>130000,'hargaJual'=>225000,'hargaReseller'=>200000,'profit'=>95000,'jenis'=>'STIR LOKAL','warna'=>'KUNING','gambar'=>'','stok'=>10,'masuk'=>10,'keluar'=>0,'stokAkhir'=>10],
            ['id'=>'s2','kode'=>'A2','nama'=>'STIR R14 SPARCO LIS MERAH', 'hargaBeli'=>130000,'hargaJual'=>225000,'hargaReseller'=>200000,'profit'=>95000,'jenis'=>'STIR LOKAL','warna'=>'MERAH', 'gambar'=>'','stok'=>10,'masuk'=>10,'keluar'=>0,'stokAkhir'=>10],
            ['id'=>'s3','kode'=>'B1','nama'=>'STIR R13 MOMO HITAM IMPORT','hargaBeli'=>180000,'hargaJual'=>275000,'hargaReseller'=>250000,'profit'=>95000,'jenis'=>'STIR IMPORT','warna'=>'HITAM', 'gambar'=>'','stok'=>5, 'masuk'=>5, 'keluar'=>0,'stokAkhir'=>5],
            ['id'=>'s4','kode'=>'C1','nama'=>'BOSKIT T16',                 'hargaBeli'=>55000, 'hargaJual'=>94000, 'hargaReseller'=>80000, 'profit'=>39000,'jenis'=>'BOSKIT',    'warna'=>'-',    'gambar'=>'','stok'=>20,'masuk'=>20,'keluar'=>0,'stokAkhir'=>20],
        ],
        'penjualan'  => [],
        'orderLive'  => [],
        'packKeluar' => [],
        'refundLog'  => [],
        'potongan'   => [
            ['id'=>'pt1','platform'=>'TikTok Ranco','adm'=>8,'cb'=>2,'ongkirFree'=>5.5,'ongkirCargo'=>10000,'label'=>500,'yield'=>3,'plastikLakbanDus'=>2000,'operasional'=>8,'biayaLayanan'=>1250,'biayaLogistik'=>5350,'perkiraanAdminManual'=>13.5,'pajak'=>0.5],
            ['id'=>'pt2','platform'=>'TikTok Kenan','adm'=>8,'cb'=>2,'ongkirFree'=>5.5,'ongkirCargo'=>10000,'label'=>500,'yield'=>3,'plastikLakbanDus'=>2000,'operasional'=>8,'biayaLayanan'=>1250,'biayaLogistik'=>5350,'perkiraanAdminManual'=>13.5,'pajak'=>0.5],
            ['id'=>'pt3','platform'=>'Shopee',      'adm'=>6,'cb'=>2,'ongkirFree'=>6,  'ongkirCargo'=>10000,'label'=>500,'yield'=>3,'plastikLakbanDus'=>2000,'operasional'=>8,'biayaLayanan'=>1250,'biayaLogistik'=>5350,'perkiraanAdminManual'=>13.5,'pajak'=>0.5],
        ],
    ];

    $stmt = $pdo->prepare(
        "INSERT IGNORE INTO app_data (`tbl_key`, `tbl_value`) VALUES (?, ?)"
    );
    foreach ($init as $key => $value) {
        $stmt->execute([$key, json_encode($value, JSON_UNESCAPED_UNICODE)]);
    }
}
