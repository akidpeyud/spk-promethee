<?php
require_once 'config.php';

$alternatifList = getAllAlternatif($conn);
$kriteriaList = getAllKriteria($conn);
$matrix = getPenilaianMatrix($conn);

$countAlt = count($alternatifList);
$countKrit = count($kriteriaList);
$totalPenilaianExpected = $countAlt * $countKrit;

$resCount = $conn->query("SELECT COUNT(*) as cnt FROM penilaian");
$rowCount = $resCount ? $resCount->fetch_assoc() : ['cnt' => 0];
$countPenilaian = (int)$rowCount['cnt'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Utama - SPK PROMETHEE Roblox</title>
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4b6630;
            --primary-light: #688c42;
            --accent: #d4bd8f;
            --accent-light: #f7edd6;
            --bg-grad-1: #4b6630;
            --bg-grad-2: #72964b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-grad-1) 0%, var(--bg-grad-2) 100%);
            display: flex;
            flex-direction: column;
            color: #333;
        }

        .menu-main-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .menu-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            padding: 48px;
            max-width: 1100px;
            width: 100%;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.25);
            animation: fadeIn 0.6s ease;
            position: relative;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-menu {
            text-align: center;
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
        }

        .header-menu h1 {
            color: var(--primary);
            font-size: 34px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .header-menu p {
            color: #666;
            font-size: 15px;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Database Stats Bar */
        .db-stats-bar {
            background: linear-gradient(135deg, #f7faf3 0%, #edf4e8 100%);
            border: 1px solid rgba(75, 102, 48, 0.2);
            border-radius: 20px;
            padding: 16px 24px;
            margin-bottom: 35px;
            display: flex;
            align-items: center;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 15px;
        }

        .db-stat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            color: #444;
        }

        .db-stat-item strong {
            color: var(--primary);
            font-size: 16px;
        }

        /* Menu Card */
        .card-menu {
            background: #ffffff;
            border: 2px solid #e7eee0;
            border-radius: 24px;
            padding: 35px 28px;
            height: 100%;
            display: flex;
            flex-direction: column;
            text-align: center;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
        }

        .card-menu::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transform: scaleX(0);
            transition: transform 0.35s ease;
            transform-origin: left;
        }

        .card-menu:hover {
            transform: translateY(-8px);
            border-color: var(--primary-light);
            box-shadow: 0 20px 45px rgba(75, 102, 48, 0.18);
            background: #fafdf8;
        }

        .card-menu:hover::before {
            transform: scaleX(1);
        }

        .card-icon {
            font-size: 52px;
            margin-bottom: 16px;
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .card-menu:hover .card-icon {
            transform: scale(1.15) rotate(6deg);
        }

        .card-menu h5 {
            color: var(--primary);
            font-weight: 700;
            font-size: 21px;
            margin-bottom: 12px;
        }

        .card-menu p {
            color: #555;
            font-size: 14.5px;
            line-height: 1.65;
            margin-bottom: 25px;
            flex: 1;
        }

        .card-menu strong {
            color: var(--primary);
        }

        .card-tag {
            display: inline-block;
            align-self: center;
            background: var(--accent-light);
            color: var(--primary);
            border: 1px solid var(--accent);
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 16px;
        }

        .btn-menu-action {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #ffffff;
            border-radius: 50px;
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 20px rgba(75, 102, 48, 0.25);
            transition: all 0.3s ease;
            border: none;
        }

        .btn-menu-action:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(75, 102, 48, 0.35);
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
        }

        .btn-secondary-action {
            background: #ffffff;
            color: var(--primary);
            border: 2px solid var(--primary);
            box-shadow: none;
        }

        .btn-secondary-action:hover {
            background: var(--primary);
            color: #ffffff;
        }

        /* Method info */
        .info-card-bottom {
            margin-top: 35px;
            background: #fbfbf9;
            border-left: 4px solid var(--primary);
            border-radius: 14px;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .info-card-bottom .icon {
            font-size: 28px;
        }

        .info-card-bottom p {
            margin: 0;
            font-size: 13.5px;
            color: #555;
            line-height: 1.6;
        }

        .info-card-bottom strong {
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .menu-container {
                padding: 30px 20px;
            }
            .header-menu h1 {
                font-size: 26px;
            }
            .db-stats-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<?php require_once 'navbar.php'; ?>

<div class="menu-main-wrapper">
    <div class="menu-container">
        <div class="header-menu">
            <h1>🎮 Menu Utama SPK PROMETHEE</h1>
            <p>Pilih mode analisis keputusan untuk menentukan game Roblox yang aman, ramah, dan edukatif bagi anak-anak.</p>
        </div>

        <!-- Database Live Status Bar -->
        <div class="db-stats-bar">
            <div class="db-stat-item">
                <span>🗄️</span>
                <span>Database: <strong>promethee_db</strong></span>
            </div>
            <div class="db-stat-item">
                <span>🎮</span>
                <span>Alternatif: <strong><?= $countAlt ?> Map</strong></span>
            </div>
            <div class="db-stat-item">
                <span>📊</span>
                <span>Kriteria: <strong><?= $countKrit ?> Kriteria</strong></span>
            </div>
            <div class="db-stat-item">
                <span>📝</span>
                <span>Data Penilaian: <strong><?= $countPenilaian ?> / <?= $totalPenilaianExpected ?> Data</strong></span>
            </div>
        </div>

        <!-- Action Cards Grid -->
        <div class="row g-4 justify-content-center">
            <!-- Card 1: Default Mode -->
            <div class="col-lg-4 col-md-6">
                <div class="card-menu">
                    <span class="card-tag">Mode Standar</span>
                    <div class="card-icon">🎮</div>
                    <h5>Perhitungan Map Roblox</h5>
                    <p>
                        Gunakan data tersimpan dalam database untuk menilai <strong><?= $countAlt ?> Alternatif Map</strong> berdasarkan <strong><?= $countKrit ?> Kriteria</strong> aspek edukasi dan keamanan anak.
                    </p>
                    <a href="perhitungan.php" class="btn-menu-action">
                        <span>Mulai Analisis</span> <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Card 2: Custom Mode -->
            <div class="col-lg-4 col-md-6">
                <div class="card-menu">
                    <span class="card-tag">Mode Fleksibel</span>
                    <div class="card-icon">⚙️</div>
                    <h5>Kustom Alternatif & Kriteria</h5>
                    <p>
                        Tentukan sendiri <strong>judul SPK</strong>, jumlah <strong>alternatif (2-10)</strong>, serta <strong>kriteria (2-10)</strong> sesuai skenario penilaian yang Anda inginkan.
                    </p>
                    <a href="custom.php" class="btn-menu-action btn-secondary-action">
                        <span>Buka Mode Kustom</span> <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Card 3: Manage Database Mode -->
            <div class="col-lg-4 col-md-6">
                <div class="card-menu">
                    <span class="card-tag">Manajemen Data</span>
                    <div class="card-icon">🛠️</div>
                    <h5>Kelola Master Data</h5>
                    <p>
                        Tambah atau hapus alternatif map, kriteria penilaian, atau reset nilai matriks ke kondisi default pabrik secara langsung di database.
                    </p>
                    <a href="manage.php" class="btn-menu-action btn-secondary-action">
                        <span>Kelola Database</span> <span>→</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="info-card-bottom">
            <span class="icon">💡</span>
            <p>
                <strong>Metode PROMETHEE II</strong> menghasilkan perangkingan lengkap (total ranking) melalui perhitungan selisih nilai antar alternatif, indeks preferensi multimodal, serta <em>Net Outranking Flow</em> ($\Phi$).
            </p>
        </div>
    </div>
</div>

<script src="bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
