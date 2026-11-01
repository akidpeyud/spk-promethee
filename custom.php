<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kustom Konfigurasi SPK - PROMETHEE</title>
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
            align-items: center;
        }

        .custom-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            padding: 45px;
            max-width: 850px;
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
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .header-section h1 {
            color: var(--primary);
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .header-section p {
            color: #666;
            font-size: 15px;
        }

        .input-group-custom {
            background: #fbfdf9;
            border: 1px solid #e7eee0;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .form-label-custom {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
            font-size: 15px;
            display: block;
        }

        .form-control-custom {
            border-radius: 14px;
            border: 2px solid #dde5d7;
            padding: 12px 18px;
            font-size: 15px;
            width: 100%;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(75, 102, 48, 0.15);
            outline: none;
        }

        /* Preset Chips */
        .preset-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-preset {
            background: #ffffff;
            border: 1px solid #dde5d7;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-preset:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }

        .slider-wrapper {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-top: 12px;
        }

        .slider-wrapper input[type="range"] {
            flex: 1;
            accent-color: var(--primary);
            height: 8px;
            cursor: pointer;
        }

        .counter-badge {
            min-width: 55px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(75, 102, 48, 0.25);
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
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px rgba(75, 102, 48, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
            gap: 6px;
            transition: all 0.25s ease;
        }

        .btn-action-secondary:hover {
            background: var(--primary);
            color: #ffffff;
        }
    </style>
</head>
<body>

<?php require_once 'navbar.php'; ?>

<div class="main-wrapper">
    <div class="custom-card">
        
        <div class="header-section">
            <h1>⚙️ Kustom Analisis SPK</h1>
            <p>Konfigurasikan judul skenario, jumlah alternatif, dan kriteria penilaian PROMETHEE sesuai kebutuhan.</p>
        </div>

        <form method="post" action="input_custom.php" id="customForm">
            
            <!-- JUDUL SPK -->
            <div class="input-group-custom">
                <label class="form-label-custom">📝 Judul Analisis SPK</label>
                <input type="text" 
                       class="form-control-custom" 
                       id="judulSPK" 
                       name="judul_spk" 
                       placeholder="Contoh: Analisis Map Roblox Paling Ramah Anak 2026"
                       value="Pemilihan Game Roblox yang Aman dan Edukatif untuk Anak-Anak"
                       required>
                
                <div class="mt-2">
                    <small class="text-muted fw-semibold">Pilih contoh template cepat:</small>
                    <div class="preset-container">
                        <button type="button" class="btn-preset" onclick="setJudul('Pemilihan Game Roblox Ramah Anak & Edukatif')">🎮 Game Roblox Ramah Anak</button>
                        <button type="button" class="btn-preset" onclick="setJudul('Evaluasi Map Petualangan Gunung Roblox')">⛰️ Map Gunung & Petualangan</button>
                        <button type="button" class="btn-preset" onclick="setJudul('Pemilihan Game Tycoon & Manajemen Edukatif')">🏭 Game Tycoon & Bisnis</button>
                    </div>
                </div>
            </div>

            <!-- JUMLAH ALTERNATIF -->
            <div class="input-group-custom">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label-custom mb-0">📊 Jumlah Alternatif Pilihan (2 - 10)</label>
                    <small class="text-muted">Jumlah opsi game/map yang dinilai</small>
                </div>
                <div class="slider-wrapper">
                    <input type="range" 
                           id="rangeAlt" 
                           name="jumlah_alternatif" 
                           min="2" 
                           max="10" 
                           value="4" 
                           oninput="document.getElementById('badgeAlt').textContent = this.value">
                    <div class="counter-badge" id="badgeAlt">4</div>
                </div>
            </div>

            <!-- JUMLAH KRITERIA -->
            <div class="input-group-custom">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label-custom mb-0">📋 Jumlah Kriteria Penilaian (2 - 10)</label>
                    <small class="text-muted">Jumlah indikator evaluasi</small>
                </div>
                <div class="slider-wrapper">
                    <input type="range" 
                           id="rangeKrit" 
                           name="jumlah_kriteria" 
                           min="2" 
                           max="10" 
                           value="5" 
                           oninput="document.getElementById('badgeKrit').textContent = this.value">
                    <div class="counter-badge" id="badgeKrit">5</div>
                </div>
            </div>

            <div class="button-bar">
                <a href="menu.php" class="btn-action-secondary">
                    <span>← Kembali</span>
                </a>
                <button type="submit" class="btn-action-primary">
                    <span>Langkah Selanjutnya: Input Nilai →</span>
                </button>
            </div>

        </form>

    </div>
</div>

<script src="bootstrap/bootstrap.bundle.min.js"></script>
<script>
function setJudul(txt) {
    document.getElementById('judulSPK').value = txt;
}
</script>
</body>
</html>
