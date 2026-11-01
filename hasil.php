<?php
require_once 'config.php';

// Validasi & Simpan ke database jika ada POST data
if (isset($_POST['nilai']) && is_array($_POST['nilai'])) {
    $nilaiInput = $_POST['nilai'];
    foreach ($nilaiInput as $id_kriteria => $alternatifs) {
        foreach ($alternatifs as $id_alternatif => $nilai) {
            updatePenilaian($conn, (int)$id_alternatif, (int)$id_kriteria, (int)$nilai);
        }
    }
}

// Ambil data terbaru dari database
$alternatifList = getAllAlternatif($conn);
$kriteriaList = getAllKriteria($conn);
$nilaiMatrix = getPenilaianMatrix($conn);

// Cek apakah data lengkap, jika tidak redirect ke perhitungan.php
if (!isPenilaianComplete($conn)) {
    header("Location: perhitungan.php");
    exit;
}

$totalAlternatif = count($alternatifList);
$totalKriteria   = count($kriteriaList);

$alternatifIds = array_keys($alternatifList);
$kriteriaIds = array_keys($kriteriaList);

// ==================================================
// HITUNG PREFERENSI (Usual Preference: P(a, b) = 1 jika a > b, else 0)
// ==================================================
$preferensi = [];
$preferensiDetail = [];

foreach ($alternatifIds as $i) {
    foreach ($alternatifIds as $j) {
        if ($i === $j) continue;

        $nilaiPreferensi = 0;
        foreach ($kriteriaIds as $k) {
            $valI = $nilaiMatrix[$k][$i] ?? 0;
            $valJ = $nilaiMatrix[$k][$j] ?? 0;
            $selisih = $valI - $valJ;
            $prefK = ($selisih > 0) ? 1 : 0;
            $nilaiPreferensi += $prefK;
            $preferensiDetail[$k][$i][$j] = [
                'd' => $selisih,
                'p' => $prefK
            ];
        }

        $preferensi[$i][$j] = $totalKriteria > 0 ? ($nilaiPreferensi / $totalKriteria) : 0;
    }
}

// ==================================================
// LEAVING FLOW, ENTERING FLOW, NET FLOW
// ==================================================
$leavingFlow  = [];
$enteringFlow = [];
$netFlow      = [];

foreach ($alternatifIds as $i) {
    $totalLeaving  = 0;
    $totalEntering = 0;

    foreach ($alternatifIds as $j) {
        if ($i === $j) continue;
        $totalLeaving  += $preferensi[$i][$j] ?? 0;
        $totalEntering += $preferensi[$j][$i] ?? 0;
    }

    $denom = max(1, $totalAlternatif - 1);
    $leavingFlow[$i]  = $totalLeaving / $denom;
    $enteringFlow[$i] = $totalEntering / $denom;
    $netFlow[$i]      = $leavingFlow[$i] - $enteringFlow[$i];
}

// ==================================================
// RANKING
// ==================================================
arsort($netFlow);
$rankingList = array_keys($netFlow);
$winnerId = $rankingList[0];
$winnerName = $alternatifList[$winnerId]['nama'];
$winnerScore = number_format($netFlow[$winnerId], 4);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Analisis PROMETHEE - Game Roblox</title>
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        .result-container {
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

        .header-result {
            text-align: center;
            margin-bottom: 35px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f0f0;
            position: relative;
        }

        .header-result h1 {
            color: var(--primary);
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .header-result p {
            color: #666;
            font-size: 15px;
            margin-bottom: 10px;
        }

        /* Top Action Buttons */
        .top-action-bar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .btn-action-pill {
            background: #ffffff;
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            cursor: pointer;
        }

        .btn-action-pill:hover {
            background: var(--primary);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(75, 102, 48, 0.2);
        }

        .btn-action-pill.primary-pill {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            border: none;
        }

        .btn-action-pill.primary-pill:hover {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
        }

        /* Winner Showcase Card */
        .winner-card {
            background: linear-gradient(135deg, #fefbf3 0%, #faedd3 100%);
            border: 2px solid #ecd3a5;
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 35px;
            display: flex;
            align-items: center;
            gap: 25px;
            box-shadow: 0 15px 35px rgba(212, 189, 143, 0.25);
            position: relative;
            overflow: hidden;
        }

        .winner-trophy-box {
            font-size: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            width: 100px;
            height: 100px;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border: 2px solid #ecd3a5;
            flex-shrink: 0;
            animation: bounceSoft 2.5s infinite;
        }

        @keyframes bounceSoft {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .winner-content h3 {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9c6d1e;
            margin-bottom: 5px;
        }

        .winner-content .winner-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .winner-content p {
            margin: 0;
            color: #555;
            font-size: 14.5px;
            line-height: 1.6;
        }

        .winner-score-badge {
            display: inline-block;
            background: var(--primary);
            color: #ffffff;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 50px;
            font-size: 13px;
        }

        /* Section Styling */
        .section-box {
            margin-bottom: 40px;
        }

        .section-heading {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-left: 12px;
            border-left: 4px solid var(--primary);
        }

        /* Chart Card */
        .chart-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 30px;
            border: 1px solid #e7eee0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            margin-bottom: 35px;
        }

        .chart-canvas-container {
            position: relative;
            height: 380px;
        }

        /* Ranking Table */
        .ranking-table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .ranking-table-custom thead th {
            background: transparent;
            color: #777;
            font-size: 12.5px;
            text-transform: uppercase;
            font-weight: 600;
            padding: 10px 18px;
            border: none;
            letter-spacing: 0.5px;
        }

        .ranking-row {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            border: 1px solid #eef2e9;
            transition: all 0.25s ease;
        }

        .ranking-row:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(75, 102, 48, 0.12);
        }

        .ranking-row td {
            padding: 18px;
            vertical-align: middle;
            border: none;
        }

        .ranking-row td:first-child {
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
            width: 90px;
            text-align: center;
        }

        .ranking-row td:last-child {
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
            text-align: right;
        }

        .rank-medal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            font-size: 20px;
            font-weight: 800;
        }

        .rank-1 .rank-medal {
            background: #fff4d3;
            color: #b8860b;
            border: 2px solid #ecd3a5;
        }

        .rank-2 .rank-medal {
            background: #f0f2f5;
            color: #718096;
            border: 2px solid #cbd5e0;
        }

        .rank-3 .rank-medal {
            background: #fae6d9;
            color: #b75e2b;
            border: 2px solid #e4bca6;
        }

        .rank-other .rank-medal {
            background: #f8faf5;
            color: #688c42;
            border: 1px solid #dde5d7;
            font-size: 15px;
        }

        .alt-name-cell {
            font-size: 16px;
            font-weight: 700;
            color: #333;
        }

        .flow-pill {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
        }

        .flow-positive {
            background: #eaf6ea;
            color: #27672a;
        }

        .flow-negative {
            background: #fdeeee;
            color: #c53030;
        }

        .flow-zero {
            background: #f1f3f5;
            color: #495057;
        }

        /* Detailed Tables Section */
        .card-detail-table {
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #e7eee0;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .table-matrix thead th {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            padding: 12px 14px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            border: none;
        }

        .table-matrix thead th:first-child {
            text-align: left;
            padding-left: 18px;
        }

        .table-matrix tbody td {
            padding: 12px 14px;
            font-size: 13.5px;
            text-align: center;
            border-bottom: 1px solid #f2f4f0;
            vertical-align: middle;
        }

        .table-matrix tbody td:first-child {
            text-align: left;
            font-weight: 700;
            color: var(--primary);
            padding-left: 18px;
        }

        /* Conclusion Box */
        .conclusion-box {
            background: linear-gradient(135deg, #fbfdf9 0%, #f4f8f0 100%);
            border-left: 6px solid var(--primary);
            border-radius: 20px;
            padding: 30px 35px;
            font-size: 15px;
            line-height: 1.85;
            color: #444;
            border: 1px solid #e0e9d9;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
        }

        .conclusion-box strong {
            color: var(--primary);
        }

        /* Print Styling */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .spk-navbar, .top-action-bar, .btn-action-pill, #btnToggleDetail {
                display: none !important;
            }
            .result-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            #detailSection {
                display: block !important;
            }
        }

        @media (max-width: 768px) {
            .result-container {
                padding: 25px 18px;
            }
            .winner-card {
                flex-direction: column;
                text-align: center;
            }
            .top-action-bar {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<?php require_once 'navbar.php'; ?>

<div class="main-wrapper">
    <div class="result-container">
        
        <!-- Header -->
        <div class="header-result">
            <h1>📊 Hasil Analisis Metode PROMETHEE II</h1>
            <p>Keputusan Perangkingan Game Roblox yang Aman dan Edukatif untuk Anak-Anak</p>
            <div class="d-inline-flex gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                    <?= $totalAlternatif ?> Alternatif Game
                </span>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1 rounded-pill">
                    <?= $totalKriteria ?> Kriteria Penilaian
                </span>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="top-action-bar">
            <button onclick="window.print()" class="btn-action-pill">
                <span>🖨️</span> Cetak / Simpan PDF
            </button>
            <a href="perhitungan.php" class="btn-action-pill">
                <span>🔄</span> Ubah Nilai
            </a>
            <a href="menu.php" class="btn-action-pill primary-pill">
                <span>🧭</span> Menu Utama
            </a>
        </div>

        <!-- Winner Showcase -->
        <div class="winner-card">
            <div class="winner-trophy-box">🏆</div>
            <div class="winner-content">
                <h3>Rekomendasi Utama (Peringkat 1)</h3>
                <div class="winner-title"><?= htmlspecialchars($winnerName) ?></div>
                <p>
                    Berdasarkan perhitungan PROMETHEE II, map <strong><?= htmlspecialchars($winnerName) ?></strong> memperoleh <em>Net Outranking Flow</em> tertinggi yaitu <span class="winner-score-badge"><?= $winnerScore ?></span>. Menjadikannya pilihan paling aman, ramah, dan bernilai edukatif tinggi untuk anak-anak.
                </p>
            </div>
        </div>

        <!-- Ranking Chart -->
        <div class="section-box">
            <div class="section-heading">
                <span>🏆 Grafik Peringkat Alternatif</span>
            </div>
            <div class="chart-card">
                <div class="chart-canvas-container">
                    <canvas id="rankingChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Ranking Table -->
        <div class="section-box">
            <div class="section-heading">
                <span>🥇 Tabel Peringkat Final (Net Flow $\Phi$)</span>
            </div>
            <table class="ranking-table-custom">
                <thead>
                    <tr>
                        <th style="width: 100px;">Peringkat</th>
                        <th>Nama Alternatif</th>
                        <th>Leaving Flow ($\Phi^+$)</th>
                        <th>Entering Flow ($\Phi^-$)</th>
                        <th>Net Flow ($\Phi$)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1; 
                    foreach ($rankingList as $altId): 
                        $nf = $netFlow[$altId];
                        $lf = $leavingFlow[$altId];
                        $ef = $enteringFlow[$altId];
                        $rankClass = ($rank == 1) ? 'rank-1' : (($rank == 2) ? 'rank-2' : (($rank == 3) ? 'rank-3' : 'rank-other'));
                        $flowClass = ($nf > 0) ? 'flow-positive' : (($nf < 0) ? 'flow-negative' : 'flow-zero');
                    ?>
                        <tr class="ranking-row <?= $rankClass ?>">
                            <td>
                                <span class="rank-medal">
                                    <?php if ($rank == 1): ?>🥇
                                    <?php elseif ($rank == 2): ?>🥈
                                    <?php elseif ($rank == 3): ?>🥉
                                    <?php else: ?>#<?= $rank ?>
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td>
                                <div class="alt-name-cell"><?= htmlspecialchars($alternatifList[$altId]['nama']) ?></div>
                                <small class="text-muted">Kode: <?= htmlspecialchars($alternatifList[$altId]['kode']) ?></small>
                            </td>
                            <td><?= number_format($lf, 4) ?></td>
                            <td><?= number_format($ef, 4) ?></td>
                            <td>
                                <span class="flow-pill <?= $flowClass ?>">
                                    <?= ($nf > 0 ? '+' : '') . number_format($nf, 4) ?>
                                </span>
                            </td>
                        </tr>
                    <?php $rank++; endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Detailed Calculations (Collapsible) -->
        <div class="section-box">
            <div class="section-heading">
                <span>🔍 Detail Langkah Perhitungan PROMETHEE</span>
                <button class="btn btn-sm btn-outline-success rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#detailSection" aria-expanded="false" id="btnToggleDetail">
                    Tampilkan Detail ▼
                </button>
            </div>

            <div class="collapse" id="detailSection">
                <!-- 1. Matriks Nilai Awal -->
                <div class="mb-4">
                    <h6 class="fw-bold text-success mb-2">1. Matriks Keputusan (Evaluasi Awal)</h6>
                    <div class="card-detail-table">
                        <div class="table-responsive">
                            <table class="table table-matrix mb-0">
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
                                            <td><?= htmlspecialchars($k['kode']) ?> - <?= htmlspecialchars($k['nama']) ?></td>
                                            <?php foreach ($alternatifIds as $a): ?>
                                                <td><?= (int)$nilaiMatrix[$k['id']][$a] ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 2. Matriks Preferensi P(a, b) -->
                <div class="mb-4">
                    <h6 class="fw-bold text-success mb-2">2. Matriks Indeks Preferensi Multikriteria $\pi(a, b)$</h6>
                    <div class="card-detail-table">
                        <div class="table-responsive">
                            <table class="table table-matrix mb-0">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>
                                        <?php foreach ($alternatifList as $alt): ?>
                                            <th><?= htmlspecialchars($alt['nama']) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alternatifIds as $i): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($alternatifList[$i]['nama']) ?></td>
                                            <?php foreach ($alternatifIds as $j): ?>
                                                <td>
                                                    <?php if ($i === $j): ?>
                                                        <span class="text-muted">-</span>
                                                    <?php else: ?>
                                                        <?= number_format($preferensi[$i][$j], 4) ?>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <small class="text-muted">
                        * $\pi(a, b) = \frac{1}{k} \sum P_j(a, b)$ menggunakan tipe fungsi preferensi Usual (selisih > 0 bernilai 1, lainnya 0).
                    </small>
                </div>
            </div>
        </div>

        <!-- Conclusion -->
        <div class="section-box">
            <div class="section-heading">
                <span>📌 Kesimpulan & Rekomendasi</span>
            </div>
            <div class="conclusion-box">
                <p>
                    Berdasarkan evaluasi Sistem Pendukung Keputusan menggunakan metode <strong>PROMETHEE II (Complete Ranking)</strong> terhadap <strong><?= $totalAlternatif ?> alternatif map Roblox</strong> dan <strong><?= $totalKriteria ?> kriteria penilaian</strong>:
                </p>
                <ol class="mb-3 ps-3">
                    <li>
                        Alternatif <strong><?= htmlspecialchars($winnerName) ?></strong> terpilih sebagai <strong>peringkat pertama (rekomendasi terbaik)</strong> dengan nilai <em>Net Outranking Flow</em> ($\Phi$) sebesar <strong><?= $winnerScore ?></strong>.
                    </li>
                    <li>
                        Nilai <em>Leaving Flow</em> ($\Phi^+$) mengukur sejauh mana alternatif ini mengungguli alternatif lainnya, sedangkan <em>Entering Flow</em> ($\Phi^-$) mengukur kelemahannya terhadap alternatif lain. Nilai $\Phi$ yang paling positif menandakan dominasi performa terbaik.
                    </li>
                    <li>
                        Keputusan ini dapat dijadikan rujukan objektif bagi orang tua, pendidik, dan komunitas pemain dalam memilih game Roblox yang aman dari kekerasan, interaksi sosial terjaga, serta memiliki nilai edukasi yang positif.
                    </li>
                </ol>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <div class="text-center mt-4">
            <a href="menu.php" class="btn-action-pill primary-pill px-4 py-2">
                <span>← Kembali ke Menu Utama</span>
            </a>
        </div>

    </div>
</div>

<script src="bootstrap/bootstrap.bundle.min.js"></script>
<script>
// Toggle button text
const toggleBtn = document.getElementById('btnToggleDetail');
const detailSection = document.getElementById('detailSection');
if (toggleBtn && detailSection) {
    detailSection.addEventListener('show.bs.collapse', () => {
        toggleBtn.textContent = 'Sembunyikan Detail ▲';
    });
    detailSection.addEventListener('hide.bs.collapse', () => {
        toggleBtn.textContent = 'Tampilkan Detail ▼';
    });
}

// Chart.js Setup
const rankingLabels = <?= json_encode(array_map(function($idx) use ($alternatifList) {
    return $alternatifList[$idx]['nama'];
}, $rankingList)) ?>;

const rankingScores = <?= json_encode(array_map(function($idx) use ($netFlow) {
    return round($netFlow[$idx], 4);
}, $rankingList)) ?>;

const colors = [
    'rgba(212, 189, 143, 0.9)', // Emas #1
    'rgba(160, 174, 192, 0.9)', // Perak #2
    'rgba(197, 137, 98, 0.9)',  // Perunggu #3
    'rgba(75, 102, 48, 0.85)'   // Hijau #4+
];

const borderColors = [
    '#b8860b',
    '#718096',
    '#a0522d',
    '#4b6630'
];

const ctx = document.getElementById('rankingChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: rankingLabels,
        datasets: [{
            label: 'Net Flow (Φ)',
            data: rankingScores,
            backgroundColor: rankingScores.map((_, i) => colors[i] || colors[3]),
            borderColor: rankingScores.map((_, i) => borderColors[i] || borderColors[3]),
            borderWidth: 2,
            borderRadius: 12,
            maxBarThickness: 65
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(30, 41, 20, 0.9)',
                padding: 12,
                titleFont: { size: 14, family: 'Poppins', weight: 'bold' },
                bodyFont: { size: 13, family: 'Poppins' },
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return ' Nilai Net Flow: ' + context.parsed.y.toFixed(4);
                    }
                }
            }
        },
        scales: {
            y: {
                grid: { color: 'rgba(0,0,0,0.06)' },
                ticks: {
                    font: { family: 'Poppins', size: 12 },
                    callback: function(value) { return value.toFixed(3); }
                },
                title: {
                    display: true,
                    text: 'Net Outranking Flow (Φ)',
                    font: { family: 'Poppins', size: 13, weight: 'bold' },
                    color: '#4b6630'
                }
            },
            x: {
                grid: { display: false },
                ticks: {
                    font: { family: 'Poppins', size: 13, weight: '600' },
                    color: '#333'
                }
            }
        }
    }
});
</script>
</body>
</html>
