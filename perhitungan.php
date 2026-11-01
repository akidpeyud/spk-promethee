<?php
require_once 'config.php';

$alternatifList = getAllAlternatif($conn);
$kriteriaList = getAllKriteria($conn);
$nilaiMatrix = getPenilaianMatrix($conn);

// Fallback jika database belum ada data
if (empty($alternatifList) || empty($kriteriaList)) {
    resetToDefault($conn);
    $alternatifList = getAllAlternatif($conn);
    $kriteriaList = getAllKriteria($conn);
    $nilaiMatrix = getPenilaianMatrix($conn);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai PROMETHEE - Game Roblox</title>
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

        .main-content-wrapper {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .input-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            padding: 45px;
            max-width: 1150px;
            width: 100%;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.25);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-section {
            text-align: center;
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
        }

        .header-section h1 {
            color: var(--primary);
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .header-section p {
            color: #666;
            font-size: 15px;
            max-width: 700px;
            margin: 0 auto;
        }

        .scale-badge-bar {
            margin-top: 15px;
            display: inline-flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            background: #f4f8f1;
            padding: 10px 20px;
            border-radius: 50px;
            border: 1px solid rgba(75, 102, 48, 0.2);
            font-size: 13px;
        }

        .scale-badge-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
            color: var(--primary);
        }

        .section-title {
            font-size: 19px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 12px;
            border-left: 4px solid var(--primary);
        }

        /* Modern Table Card */
        .card-table-wrapper {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid #e7eee0;
            margin-bottom: 25px;
        }

        .table-custom {
            margin-bottom: 0;
            width: 100%;
        }

        .table-custom thead th {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #ffffff;
            text-align: center;
            vertical-align: middle;
            font-weight: 600;
            padding: 16px 12px;
            border: none;
            font-size: 13.5px;
            letter-spacing: 0.3px;
        }

        .table-custom thead th:first-child {
            text-align: left;
            padding-left: 20px;
            min-width: 160px;
        }

        .table-custom tbody td {
            text-align: center;
            vertical-align: middle;
            padding: 14px 12px;
            border-bottom: 1px solid #f2f4f0;
        }

        .table-custom tbody tr:hover {
            background: #fbfdf9;
        }

        .table-custom tbody td:first-child {
            text-align: left;
            padding-left: 20px;
            font-weight: 700;
            color: var(--primary);
        }

        .input-score {
            width: 100%;
            max-width: 90px;
            margin: 0 auto;
            border-radius: 12px;
            border: 2px solid #dde5d7;
            padding: 8px;
            font-size: 15px;
            text-align: center;
            font-weight: 700;
            color: var(--primary);
            background: #ffffff;
            transition: all 0.25s ease;
        }

        .input-score:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(75, 102, 48, 0.15);
            outline: none;
            background: #fcfdfa;
        }

        /* Criteria Info Card */
        .kriteria-info-card {
            background: linear-gradient(135deg, #fbfdf9 0%, #f4f8f0 100%);
            border-radius: 20px;
            padding: 28px;
            border: 1px solid #e0e9d9;
            height: 100%;
        }

        .kriteria-item {
            background: #ffffff;
            border-radius: 14px;
            padding: 12px 16px;
            margin-bottom: 12px;
            border-left: 4px solid var(--primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            gap: 12px;
            transition: transform 0.2s ease;
        }

        .kriteria-item:hover {
            transform: translateX(4px);
            box-shadow: 0 6px 16px rgba(75, 102, 48, 0.1);
        }

        .kriteria-code-badge {
            background: var(--accent-light);
            color: var(--primary);
            font-weight: 800;
            font-size: 13px;
            padding: 4px 10px;
            border-radius: 8px;
            border: 1px solid var(--accent);
        }

        .kriteria-name {
            font-weight: 600;
            font-size: 14px;
            color: #333;
        }

        /* Submit Bar */
        .submit-bar {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid #f0f0f0;
        }

        .btn-calc-submit {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            border-radius: 50px;
            padding: 15px 45px;
            border: none;
            font-size: 16px;
            font-weight: 700;
            box-shadow: 0 10px 25px rgba(75, 102, 48, 0.35);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .btn-calc-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(75, 102, 48, 0.45);
            color: #fff;
        }

        .btn-calc-back {
            background: #ffffff;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 50px;
            padding: 13px 32px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-calc-back:hover {
            background: var(--primary);
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .input-container {
                padding: 25px 18px;
            }
            .header-section h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<?php require_once 'navbar.php'; ?>

<div class="main-content-wrapper">
    <div class="input-container">
        
        <div class="header-section">
            <h1>📝 Input Nilai Penilaian Map Roblox</h1>
            <p>Masukkan penilaian untuk setiap map game Roblox pada masing-masing kriteria edukasi dan keamanan anak.</p>
            <div class="scale-badge-bar">
                <span class="scale-badge-item">⭐ Skala 1 - 5:</span>
                <span class="scale-badge-item">1 = Sangat Rendah</span>
                <span class="scale-badge-item">|</span>
                <span class="scale-badge-item">2 = Rendah</span>
                <span class="scale-badge-item">|</span>
                <span class="scale-badge-item">3 = Cukup</span>
                <span class="scale-badge-item">|</span>
                <span class="scale-badge-item">4 = Baik</span>
                <span class="scale-badge-item">|</span>
                <span class="scale-badge-item">5 = Sangat Baik</span>
            </div>
        </div>

        <form method="post" action="hasil.php" id="prometheeForm">
            <div class="row g-4">
                
                <!-- TABEL PENILAIAN -->
                <div class="col-lg-8">
                    <div class="section-title">
                        <span>📊</span> Matriks Keputusan (Nilai Alternatif)
                    </div>

                    <div class="card-table-wrapper">
                        <div class="table-responsive">
                            <table class="table table-custom align-middle">
                                <thead>
                                    <tr>
                                        <th>Kriteria</th>
                                        <?php foreach ($alternatifList as $alt): ?>
                                            <th><?= htmlspecialchars($alt['nama']) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kriteriaList as $k): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-success-subtle text-success me-1"><?= htmlspecialchars($k['kode']) ?></span>
                                                <?= htmlspecialchars($k['nama']) ?>
                                            </td>
                                            <?php foreach ($alternatifList as $a): ?>
                                                <td>
                                                    <input type="number"
                                                           name="nilai[<?= $k['id'] ?>][<?= $a['id'] ?>]"
                                                           class="input-score"
                                                           min="1" 
                                                           max="10" 
                                                           value="<?= isset($nilaiMatrix[$k['id']][$a['id']]) ? (int)$nilaiMatrix[$k['id']][$a['id']] : 3 ?>"
                                                           required>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                        <small class="text-muted">💡 Nilai otomatis disimpan ke database saat perhitungan diproses.</small>
                    </div>
                </div>

                <!-- KETERANGAN KRITERIA -->
                <div class="col-lg-4">
                    <div class="section-title">
                        <span>📌</span> Informasi Kriteria
                    </div>

                    <div class="kriteria-info-card">
                        <?php foreach ($kriteriaList as $k): ?>
                            <div class="kriteria-item">
                                <span class="kriteria-code-badge"><?= htmlspecialchars($k['kode']) ?></span>
                                <div class="kriteria-name"><?= htmlspecialchars($k['nama']) ?></div>
                            </div>
                        <?php endforeach; ?>

                        <div class="mt-4 p-3 rounded-3 bg-white border">
                            <h6 class="fw-bold text-success mb-1">🎮 4 Map Terpilih:</h6>
                            <ul class="small text-muted mb-0 ps-3">
                                <li>Supermarket Together</li>
                                <li>Brookhaven</li>
                                <li>Plant vs Brainrot</li>
                                <li>Grow a Garden</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <div class="submit-bar">
                <a href="menu.php" class="btn-calc-back">
                    <span>← Kembali ke Menu</span>
                </a>
                <button type="submit" class="btn-calc-submit" id="submitBtn">
                    <span>🚀 Hitung PROMETHEE II</span>
                </button>
            </div>
        </form>

    </div>
</div>

<script src="bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
