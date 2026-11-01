<?php
session_start();

if (!isset($_POST['jumlah_alternatif']) || !isset($_POST['jumlah_kriteria']) || !isset($_POST['judul_spk'])) {
    header("Location: custom.php");
    exit;
}

$jumlahAlternatif = max(2, min(10, intval($_POST['jumlah_alternatif'])));
$jumlahKriteria = max(2, min(10, intval($_POST['jumlah_kriteria'])));
$judulSPK = htmlspecialchars($_POST['judul_spk']);

$_SESSION['judul_spk'] = $judulSPK;
$_SESSION['jumlah_alternatif'] = $jumlahAlternatif;
$_SESSION['jumlah_kriteria'] = $jumlahKriteria;

// Sample default names
$defaultAltNames = [
    1 => 'Supermarket Together',
    2 => 'Brookhaven',
    3 => 'Plant vs Brainrot',
    4 => 'Grow a Garden',
    5 => 'Adopt Me',
    6 => 'Theme Park Tycoon',
    7 => 'Work at a Pizza Place',
    8 => 'Scuba Diving at Quill Lake',
    9 => 'Islands',
    10 => 'Restaurant Tycoon'
];

$defaultKritNames = [
    1 => 'Tingkat Keamanan Konten',
    2 => 'Nilai Edukasi & Pembelajaran',
    3 => 'Keamanan Interaksi Sosial',
    4 => 'Kompleksitas Gameplay',
    5 => 'Kesesuaian Umur Anak',
    6 => 'Kualitas Grafis & Desain',
    7 => 'Tingkat Kreativitas',
    8 => 'Kerjasama Tim (Co-op)',
    9 => 'Kemudahan Kendali',
    10 => 'Bebas dari Pay-to-Win'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai Kustom - <?= $judulSPK ?></title>
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

        .main-wrapper {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .input-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            padding: 45px;
            max-width: 1200px;
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
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .spk-title-badge {
            display: inline-block;
            background: #f4f8f1;
            color: var(--primary);
            font-weight: 700;
            padding: 8px 24px;
            border-radius: 50px;
            border: 1px solid rgba(75, 102, 48, 0.25);
            font-size: 15px;
            margin-top: 6px;
        }

        .section-box {
            background: #ffffff;
            border: 1px solid #e7eee0;
            border-radius: 20px;
            padding: 26px;
            margin-bottom: 30px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.03);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 12px;
            border-left: 4px solid var(--primary);
        }

        .form-control-custom {
            border-radius: 12px;
            border: 2px solid #dde5d7;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s ease;
            width: 100%;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(75, 102, 48, 0.15);
            outline: none;
        }

        .score-input {
            width: 100%;
            max-width: 80px;
            margin: 0 auto;
            text-align: center;
            font-weight: 700;
            color: var(--primary);
            border: 2px solid #dde5d7;
            border-radius: 10px;
            padding: 8px;
        }

        .score-input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .table-custom thead th {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            padding: 14px 12px;
            font-size: 13.5px;
            font-weight: 600;
            text-align: center;
            border: none;
        }

        .table-custom tbody td {
            padding: 12px;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #f2f4f0;
        }

        .btn-quick-fill {
            background: #fef8ee;
            border: 1px dashed #e2a03f;
            color: #b7791f;
            font-weight: 600;
            font-size: 13px;
            padding: 6px 16px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-quick-fill:hover {
            background: #e2a03f;
            color: #ffffff;
        }

        .button-bar {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 2px solid #f0f0f0;
        }

        .btn-action-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #ffffff;
            border-radius: 50px;
            padding: 13px 40px;
            font-size: 16px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(75, 102, 48, 0.3);
        }

        .btn-action-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(75, 102, 48, 0.4);
            color: #fff;
        }

        .btn-action-secondary {
            background: #ffffff;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 50px;
            padding: 11px 32px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-action-secondary:hover {
            background: var(--primary);
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .input-card {
                padding: 25px 18px;
            }
        }
    </style>
</head>
<body>

<?php require_once 'navbar.php'; ?>

<div class="main-wrapper">
    <div class="input-card">
        
        <div class="header-section">
            <h1>📝 Langkah 2: Beri Nama & Input Nilai Penilaian</h1>
            <div class="spk-title-badge">🎯 <?= $judulSPK ?></div>
            <div class="mt-3">
                <button type="button" class="btn-quick-fill" onclick="autoFillSampleData()">
                    ✨ Isi Data Contoh Otomatis
                </button>
            </div>
        </div>

        <form method="post" action="hasil_custom.php" id="formCustomScore">
            <input type="hidden" name="judul_spk" value="<?= $judulSPK ?>">
            <input type="hidden" name="jumlah_alternatif" value="<?= $jumlahAlternatif ?>">
            <input type="hidden" name="jumlah_kriteria" value="<?= $jumlahKriteria ?>">

            <!-- 1. Nama Alternatif -->
            <div class="section-box">
                <div class="section-title">
                    <span>🎮</span> Beri Nama Setiap Alternatif (<?= $jumlahAlternatif ?> Game/Opsi)
                </div>
                <div class="row g-3">
                    <?php for ($i = 1; $i <= $jumlahAlternatif; $i++): ?>
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label small fw-bold text-muted mb-1">Alternatif <?= $i ?> (A<?= $i ?>)</label>
                            <input type="text" 
                                   name="nama_alternatif[<?= $i ?>]" 
                                   id="alt_<?= $i ?>"
                                   class="form-control-custom" 
                                   placeholder="Alternatif <?= $i ?>" 
                                   value="<?= $defaultAltNames[$i] ?? 'Alternatif ' . $i ?>" 
                                   oninput="syncTableHeaders()"
                                   required>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- 2. Nama Kriteria -->
            <div class="section-box">
                <div class="section-title">
                    <span>📋</span> Beri Nama Setiap Kriteria Penilaian (<?= $jumlahKriteria ?> Kriteria)
                </div>
                <div class="row g-3">
                    <?php for ($k = 1; $k <= $jumlahKriteria; $k++): ?>
                        <div class="col-md-6 col-lg-4">
                            <label class="form-label small fw-bold text-muted mb-1">Kriteria <?= $k ?> (K<?= $k ?>)</label>
                            <input type="text" 
                                   name="nama_kriteria[<?= $k ?>]" 
                                   id="krit_<?= $k ?>"
                                   class="form-control-custom" 
                                   placeholder="Kriteria <?= $k ?>" 
                                   value="<?= $defaultKritNames[$k] ?? 'Kriteria ' . $k ?>" 
                                   oninput="syncTableRowLabels()"
                                   required>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- 3. Matriks Penilaian -->
            <div class="section-box">
                <div class="section-title">
                    <span>📊</span> Matriks Penilaian (Skala 1 - 5)
                </div>
                <div class="table-responsive">
                    <table class="table table-custom align-middle">
                        <thead>
                            <tr>
                                <th style="text-align: left; padding-left: 20px; min-width: 180px;">Kriteria</th>
                                <?php for ($i = 1; $i <= $jumlahAlternatif; $i++): ?>
                                    <th id="th_alt_<?= $i ?>"><?= $defaultAltNames[$i] ?? 'Alternatif ' . $i ?></th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($k = 1; $k <= $jumlahKriteria; $k++): ?>
                                <tr>
                                    <td style="text-align: left; padding-left: 20px; font-weight: 700; color: var(--primary);">
                                        <span class="badge bg-success-subtle text-success me-1">K<?= $k ?></span>
                                        <span id="td_krit_<?= $k ?>"><?= $defaultKritNames[$k] ?? 'Kriteria ' . $k ?></span>
                                    </td>
                                    <?php for ($i = 1; $i <= $jumlahAlternatif; $i++): ?>
                                        <td>
                                            <input type="number" 
                                                   name="nilai[<?= $k ?>][<?= $i ?>]" 
                                                   id="score_<?= $k ?>_<?= $i ?>"
                                                   class="score-input" 
                                                   min="1" 
                                                   max="10" 
                                                   value="<?= rand(2, 5) ?>" 
                                                   required>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="button-bar">
                <a href="custom.php" class="btn-action-secondary">
                    <span>← Kembali</span>
                </a>
                <button type="submit" class="btn-action-primary">
                    <span>🚀 Hitung Hasil PROMETHEE →</span>
                </button>
            </div>

        </form>

    </div>
</div>

<script src="bootstrap/bootstrap.bundle.min.js"></script>
<script>
function syncTableHeaders() {
    <?php for ($i = 1; $i <= $jumlahAlternatif; $i++): ?>
        const val_<?= $i ?> = document.getElementById('alt_<?= $i ?>').value || 'Alternatif <?= $i ?>';
        document.getElementById('th_alt_<?= $i ?>').textContent = val_<?= $i ?>;
    <?php endfor; ?>
}

function syncTableRowLabels() {
    <?php for ($k = 1; $k <= $jumlahKriteria; $k++): ?>
        const val_k_<?= $k ?> = document.getElementById('krit_<?= $k ?>').value || 'Kriteria <?= $k ?>';
        document.getElementById('td_krit_<?= $k ?>').textContent = val_k_<?= $k ?>;
    <?php endfor; ?>
}

function autoFillSampleData() {
    const sampleScores = [1, 2, 3, 4, 5];
    <?php for ($k = 1; $k <= $jumlahKriteria; $k++): ?>
        <?php for ($i = 1; $i <= $jumlahAlternatif; $i++): ?>
            document.getElementById('score_<?= $k ?>_<?= $i ?>').value = Math.floor(Math.random() * 4) + 2;
        <?php endfor; ?>
    <?php endfor; ?>
}
</script>
</body>
</html>
