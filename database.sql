-- ═══════════════════════════════════════════════════════
--  Ranco Auto — Setup Database MySQL
--
--  ⚠️  CARA IMPORT YANG BENAR DI phpMyAdmin:
--  1. Klik database "ranco_auto" di panel KIRI dulu
--     (Jika belum ada: klik "Baru" → ketik ranco_auto → Buat)
--  2. Baru klik tab "SQL" atau "Impor" → jalankan file ini
-- ═══════════════════════════════════════════════════════

-- Buat tabel penyimpanan data
DROP TABLE IF EXISTS `app_data`;
CREATE TABLE `app_data` (
  `tbl_key`    VARCHAR(50)  NOT NULL,
  `tbl_value`  LONGTEXT     NOT NULL,
  `updated_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tbl_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed data awal
INSERT INTO `app_data` (`tbl_key`, `tbl_value`) VALUES
('users', '[{"id":"u1","username":"admin","password":"admin123","role":"admin","nama":"Administrator","aktif":true},{"id":"u2","username":"dayu","password":"dayu123","role":"hostlive","nama":"Dayu","aktif":true},{"id":"u3","username":"nia","password":"nia123","role":"hostlive","nama":"Nia","aktif":true},{"id":"u4","username":"amanda","password":"amanda123","role":"hostlive","nama":"Amanda","aktif":true},{"id":"u5","username":"packing","password":"pack123","role":"packing","nama":"Tim Packing","aktif":true}]'),
('stok', '[{"id":"s1","kode":"A1","nama":"STIR R14 SPARCO LIS KUNING","hargaBeli":130000,"hargaJual":225000,"hargaReseller":200000,"profit":95000,"jenis":"STIR LOKAL","warna":"KUNING","gambar":"","stok":10,"masuk":10,"keluar":0,"stokAkhir":10},{"id":"s2","kode":"A2","nama":"STIR R14 SPARCO LIS MERAH","hargaBeli":130000,"hargaJual":225000,"hargaReseller":200000,"profit":95000,"jenis":"STIR LOKAL","warna":"MERAH","gambar":"","stok":10,"masuk":10,"keluar":0,"stokAkhir":10},{"id":"s3","kode":"B1","nama":"STIR R13 MOMO HITAM IMPORT","hargaBeli":180000,"hargaJual":275000,"hargaReseller":250000,"profit":95000,"jenis":"STIR IMPORT","warna":"HITAM","gambar":"","stok":5,"masuk":5,"keluar":0,"stokAkhir":5},{"id":"s4","kode":"C1","nama":"BOSKIT T16","hargaBeli":55000,"hargaJual":94000,"hargaReseller":80000,"profit":39000,"jenis":"BOSKIT","warna":"-","gambar":"","stok":20,"masuk":20,"keluar":0,"stokAkhir":20}]'),
('penjualan',  '[]'),
('orderLive',  '[]'),
('packKeluar', '[]'),
('refundLog',  '[]'),
('potongan', '[{"id":"pt1","platform":"TikTok Ranco","adm":8,"cb":2,"ongkirFree":5.5,"ongkirCargo":10000,"label":500,"yield":3,"plastikLakbanDus":2000,"operasional":8,"biayaLayanan":1250,"biayaLogistik":5350,"perkiraanAdminManual":13.5,"pajak":0.5},{"id":"pt2","platform":"TikTok Kenan","adm":8,"cb":2,"ongkirFree":5.5,"ongkirCargo":10000,"label":500,"yield":3,"plastikLakbanDus":2000,"operasional":8,"biayaLayanan":1250,"biayaLogistik":5350,"perkiraanAdminManual":13.5,"pajak":0.5},{"id":"pt3","platform":"Shopee","adm":6,"cb":2,"ongkirFree":6,"ongkirCargo":10000,"label":500,"yield":3,"plastikLakbanDus":2000,"operasional":8,"biayaLayanan":1250,"biayaLogistik":5350,"perkiraanAdminManual":13.5,"pajak":0.5}]');

-- Selesai! Tabel dan data siap digunakan
