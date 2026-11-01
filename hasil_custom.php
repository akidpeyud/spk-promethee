<?php
if (!isset($_POST['nilai']) || !isset($_POST['nama_alternatif']) || !isset($_POST['nama_kriteria'])) {
    header("Location: custom.php");
    exit;
}

$jumlahAlternatif = intval($_POST['jumlah_alternatif']);
$jumlahKriteria = intval($_POST['jumlah_kriteria']);
$namaAlternatif = $_POST['nama_alternatif'];
$namaKriteria = $_POST['nama_kriteria'];
$nilaiMatrix = $_POST['nilai'];
$judulSPK = isset($_POST['judul_spk']) ? htmlspecialchars($_POST['judul_spk']) : 'Sistem Pendukung Keputusan PROMETHEE';

// ==================================================
// HITUNG PREFERENSI (Usual Preference)
// ==================================================
$preferensi = [];

for ($i = 1; $i <= $jumlahAlternatif; $i++) {
    for ($j = 1; $j <= $jumlahAlternatif; $j++) {
        if ($i === $j) continue;

        $nilaiPreferensi = 0;
        for ($k = 1; $k <= $jumlahKriteria; $k++) {
            $selisih = (int)$nilaiMatrix[$k][$i] - (int)$nilaiMatrix[$k][$j];
            $nilaiPreferensi += ($selisih > 0) ? 1 : 0;
        }

        $preferensi[$i][$j] = $jumlahKriteria > 0 ? ($nilaiPreferensi / $jumlahKriteria) : 0;
    }
}

// ==================================================
// LEAVING FLOW, ENTERING FLOW, NET FLOW
// ==================================================
$leavingFlow  = [];
$enteringFlow = [];
$netFlow      = [];

for ($i = 1; $i <= $jumlahAlternatif; $i++) {
    $totalLeaving  = 0;
    $totalEntering = 0;

    for ($j = 1; $j <= $jumlahAlternatif; $j++) {
        if ($i === $j) continue;
        $totalLeaving  += $preferensi[$i][$j] ?? 0;
        $totalEntering += $preferensi[$j][$i] ?? 0;
    }

    $denom = max(1, $jumlahAlternatif - 1);
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
$winnerName = $namaAlternatif[$winnerId];
$winnerScore = number_format($netFlow[$winnerId], 4);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Analisis - <?= $judulSPK ?></title>
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
        }

        .header-result h1 {
            color: var(--primary);
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .spk-badge {
            display: inline-block;
            background: #f4f8f1;
            color: var(--primary);
            font-weight: 700;
            padding: 6px 20px;
            border-radius: 50px;
            border: 1px solid rgba(75, 102, 48, 0.25);
            font-size: 14.5px;
            margin-bottom: 12px;
        }

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
        }

        .btn-action-pill.primary-pill {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            border: none;
        }

        /* Winner Card */
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

        .winner-score-badge {
            display: inline-block;
            background: var(--primary);
            color: #ffffff;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 50px;
            font-size: 13px;
        }

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
            color: #777;
            font-size: 12.5px;
            text-transform: uppercase;
            font-weight: 600;
            padding: 10px 18px;
            border: none;
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

        .flow-pill {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
        }

        .flow-positive { background: #eaf6ea; color: #27672a; }
        .flow-negative { background: #fdeeee; color: #c53030; }
        .flow-zero { background: #f1f3f5; color: #495057; }

        .card-detail-table {
            background: #ffffff;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #e7eee0;
            margin-bottom: 25px;
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

        .table-matrix tbody td {
            padding: 12px 14px;
            font-size: 13.5px;
            text-align: center;
            border-bottom: 1px solid #f2f4f0;
        }

        .conclusion-box {
            background: linear-gradient(135deg, #fbfdf9 0%, #f4f8f0 100%);
            border-left: 6px solid var(--primary);
            border-radius: 20px;
            padding: 30px 35px;
            font-size: 15px;
            line-height: 1.85;
            color: #444;
            border: 1px solid #e0e9d9;
        }

        .conclusion-box strong {
            color: var(--primary);
        }

        @media print {
            body { background: #fff !important; padding: 0 !important; }
            .spk-navbar, .top-action-bar, #btnToggleDetail { display: none !important; }
            .result-container { box-shadow: none !important; border-radius: 0 !important; max-width: 100% !important; padding: 0 !important; }
            #detailSection { display: block !important; }
        }

        @media (max-width: 768px) {
            .result-container { padding: 25px 18px; }
            .winner-card { flex-direction: column; text-align: center; }
            .top-action-bar { justify-content: center; }
        }
    </style>
</head>
<body>

<?php require_once 'navbar.php'; ?>

<div class="main-wrapper">
    <div class="result-container">
        
        <div class="header-result">
            <h1>📊 Hasil Analisis Kustom PROMETHEE II</h1>
            <div class="spk-badge">🎯 <?= $judulSPK ?></div>
            <div class="d-flex justify-content-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                    <?= $jumlahAlternatif ?> Alternatif
                </span>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1 rounded-pill">
                    <?= $jumlahKriteria ?> Kriteria
                </span>
            </div>
        </div>

        <div class="top-action-bar">
            <button onclick="window.print()" class="btn-action-pill">
                <span>🖨️</span> Cetak / PDF
            </button>
            <a href="custom.php" class="btn-action-pill">
                <span>⚙️</span> Konfigurasi Baru
            </a>
            <a href="menu.php" class="btn-action-pill primary-pill">
                <span>🧭</span> Menu Utama
            </a>
        </div>

        <!-- Winner Card -->
        <div class="winner-card">
            <div class="winner-trophy-box">🏆</div>
            <div class="winner-content">
                <h3>Pemenang / Rekomendasi Terbaik</h3>
                <div class="winner-title"><?= htmlspecialchars($winnerName) ?></div>
                <p>
                    Berdasarkan perhitungan PROMETHEE II untuk skenario <em>"<?= $judulSPK ?>"</em>, opsi <strong><?= htmlspecialchars($winnerName) ?></strong> menduduki peringkat teratas dengan skor <em>Net Flow</em> tertinggi <span class="winner-score-badge"><?= $winnerScore ?></span>.
                </p>
            </div>
        </div>

        <!-- Chart -->
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
                                <div class="fw-bold fs-6 text-dark"><?= htmlspecialchars($namaAlternatif[$altId]) ?></div>
                                <small class="text-muted">Alternatif A<?= $altId ?></small>
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

        <!-- Detailed Steps (Collapsible) -->
        <div class="section-box">
            <div class="section-heading">
                <span>🔍 Detail Perhitungan PROMETHEE</span>
                <button class="btn btn-sm btn-outline-success rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#detailSection" id="btnToggleDetail">
                    Tampilkan Detail ▼
                </button>
            </div>

            <div class="collapse" id="detailSection">
                <!-- 1. Matriks Penilaian -->
                <div class="mb-4">
                    <h6 class="fw-bold text-success mb-2">1. Matriks Penilaian Awal</h6>
                    <div class="card-detail-table">
                        <div class="table-responsive">
                            <table class="table table-matrix mb-0">
                                <thead>
                                    <tr>
                                        <th style="text-align: left; padding-left: 18px;">Kriteria</th>
                                        <?php for ($i = 1; $i <= $jumlahAlternatif; $i++): ?>
                                            <th><?= htmlspecialchars($namaAlternatif[$i]) ?></th>
                                        <?php endfor; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($k = 1; $k <= $jumlahKriteria; $k++): ?>
                                        <tr>
                                            <td style="text-align: left; padding-left: 18px; font-weight: 700; color: var(--primary);">
                                                K<?= $k ?> - <?= htmlspecialchars($namaKriteria[$k]) ?>
                                            </td>
                                            <?php for ($i = 1; $i <= $jumlahAlternatif; $i++): ?>
                                                <td><?= (int)$nilaiMatrix[$k][$i] ?></td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 2. Matriks Preferensi -->
                <div class="mb-4">
                    <h6 class="fw-bold text-success mb-2">2. Matriks Indeks Preferensi Multikriteria $\pi(a, b)$</h6>
                    <div class="card-detail-table">
                        <div class="table-responsive">
                            <table class="table table-matrix mb-0">
                                <thead>
                                    <tr>
                                        <th style="text-align: left; padding-left: 18px;">Alternatif</th>
                                        <?php for ($i = 1; $i <= $jumlahAlternatif; $i++): ?>
                                            <th><?= htmlspecialchars($namaAlternatif[$i]) ?></th>
                                        <?php endfor; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 1; $i <= $jumlahAlternatif; $i++): ?>
                                        <tr>
                                            <td style="text-align: left; padding-left: 18px; font-weight: 700; color: var(--primary);">
                                                <?= htmlspecialchars($namaAlternatif[$i]) ?>
                                            </td>
                                            <?php for ($j = 1; $j <= $jumlahAlternatif; $j++): ?>
                                                <td>
                                                    <?php if ($i === $j): ?>
                                                        <span class="text-muted">-</span>
                                                    <?php else: ?>
                                                        <?= number_format($preferensi[$i][$j], 4) ?>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endfor; ?>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conclusion -->
        <div class="section-box">
            <div class="section-heading">
                <span>📌 Kesimpulan Analisis</span>
            </div>
            <div class="conclusion-box">
                <p>
                    Berdasarkan analisis SPK dengan metode PROMETHEE II untuk skenario <strong>"<?= $judulSPK ?>"</strong>:
                </p>
                <ol class="mb-0 ps-3">
                    <li>Alternatif <strong><?= htmlspecialchars($winnerName) ?></strong> berhasil menempati urutan pertama dengan <em>Net Flow</em> tertinggi sebesar <strong><?= $winnerScore ?></strong>.</li>
                    <li>Sistem merekomendasikan <strong><?= htmlspecialchars($winnerName) ?></strong> sebagai keputusan paling optimal dari <?= $jumlahAlternatif ?> alternatif yang dibandingkan.</li>
                </ol>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="menu.php" class="btn-action-pill primary-pill px-4 py-2">
                <span>← Kembali ke Menu Utama</span>
            </a>
        </div>

    </div>
</div>

<script src="bootstrap/bootstrap.bundle.min.js"></script>
<script>
// Toggle button
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

// Chart.js
const rankingLabels = <?= json_encode(array_map(function($idx) use ($namaAlternatif) {
    return $namaAlternatif[$idx];
}, $rankingList)) ?>;

const rankingScores = <?= json_encode(array_map(function($idx) use ($netFlow) {
    return round($netFlow[$idx], 4);
}, $rankingList)) ?>;

const colors = [
    'rgba(212, 189, 143, 0.9)',
    'rgba(160, 174, 192, 0.9)',
    'rgba(197, 137, 98, 0.9)',
    'rgba(75, 102, 48, 0.85)'
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
                cornerRadius: 8
            }
        },
        scales: {
            y: {
                grid: { color: 'rgba(0,0,0,0.06)' },
                ticks: {
                    font: { family: 'Poppins', size: 12 },
                    callback: function(v) { return v.toFixed(3); }
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
