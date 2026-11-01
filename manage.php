<?php
require_once 'config.php';

$message = '';
$messageType = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_alternatif':
                $kode = trim($conn->real_escape_string($_POST['kode']));
                $nama = trim($conn->real_escape_string($_POST['nama']));
                if (!empty($kode) && !empty($nama)) {
                    $conn->query("INSERT INTO alternatif (kode, nama) VALUES ('$kode', '$nama')");
                    $message = "Alternatif baru '{$nama}' berhasil ditambahkan!";
                    $messageType = "success";
                }
                break;
                
            case 'add_kriteria':
                $kode = trim($conn->real_escape_string($_POST['kode']));
                $nama = trim($conn->real_escape_string($_POST['nama']));
                if (!empty($kode) && !empty($nama)) {
                    $conn->query("INSERT INTO kriteria (kode, nama) VALUES ('$kode', '$nama')");
                    $message = "Kriteria baru '{$nama}' berhasil ditambahkan!";
                    $messageType = "success";
                }
                break;
                
            case 'delete_alternatif':
                $id = intval($_POST['id']);
                $conn->query("DELETE FROM alternatif WHERE id = $id");
                $message = "Alternatif berhasil dihapus!";
                $messageType = "warning";
                break;
                
            case 'delete_kriteria':
                $id = intval($_POST['id']);
                $conn->query("DELETE FROM kriteria WHERE id = $id");
                $message = "Kriteria berhasil dihapus!";
                $messageType = "warning";
                break;
                
            case 'reset_penilaian':
                resetPenilaian($conn);
                $message = "Semua data nilai penilaian berhasil direset!";
                $messageType = "info";
                break;

            case 'restore_default':
                resetToDefault($conn);
                $message = "Seluruh database berhasil dipulihkan ke data default Roblox!";
                $messageType = "success";
                break;
        }
    }
}

$alternatifList = getAllAlternatif($conn);
$kriteriaList = getAllKriteria($conn);
$nilaiMatrix = getPenilaianMatrix($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Master Data - SPK PROMETHEE</title>
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

        .manage-container {
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

        .section-box {
            background: #ffffff;
            border: 1px solid #e7eee0;
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 30px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.03);
        }

        .section-title {
            font-size: 19px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 12px;
            border-left: 4px solid var(--primary);
        }

        .table-custom thead th {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            padding: 12px 14px;
            font-size: 13.5px;
            font-weight: 600;
            border: none;
        }

        .table-custom tbody td {
            padding: 12px 14px;
            font-size: 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f2f4f0;
        }

        .form-control-custom {
            border-radius: 12px;
            border: 2px solid #dde5d7;
            padding: 10px 16px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(75, 102, 48, 0.15);
            outline: none;
        }

        .btn-add-action {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-add-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(75, 102, 48, 0.3);
            color: #ffffff;
        }

        .btn-danger-custom {
            background: #e53e3e;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-danger-custom:hover {
            background: #c53030;
            color: #fff;
        }

        .danger-zone {
            background: #fdfaf7;
            border: 2px dashed #f6ad55;
            border-radius: 20px;
            padding: 25px;
        }

        .danger-zone h5 {
            color: #c05621;
            font-weight: 700;
            font-size: 17px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .manage-container {
                padding: 25px 18px;
            }
        }
    </style>
</head>
<body>

<?php require_once 'navbar.php'; ?>

<div class="main-wrapper">
    <div class="manage-container">
        
        <div class="header-section">
            <h1>🛠️ Kelola Master Data & Database</h1>
            <p>Atur alternatif map, kriteria evaluasi, dan kelola integritas database PROMETHEE.</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show rounded-4" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            
            <!-- MASTER ALTERNATIF -->
            <div class="col-lg-6">
                <div class="section-box h-100">
                    <div class="section-title">
                        <span>🎮</span> Master Data Alternatif Map (<?= count($alternatifList) ?>)
                    </div>

                    <form method="post" class="row g-2 mb-4">
                        <input type="hidden" name="action" value="add_alternatif">
                        <div class="col-4">
                            <input type="text" name="kode" class="form-control form-control-custom" placeholder="Kode (A5)" required>
                        </div>
                        <div class="col-5">
                            <input type="text" name="nama" class="form-control form-control-custom" placeholder="Nama Map Game" required>
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn-add-action w-100">+ Tambah</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">ID</th>
                                    <th style="width: 90px;">Kode</th>
                                    <th>Nama Alternatif</th>
                                    <th style="width: 80px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($alternatifList)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Belum ada alternatif map.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($alternatifList as $alt): ?>
                                        <tr>
                                            <td><?= $alt['id'] ?></td>
                                            <td><span class="badge bg-success-subtle text-success"><?= htmlspecialchars($alt['kode']) ?></span></td>
                                            <td class="fw-bold"><?= htmlspecialchars($alt['nama']) ?></td>
                                            <td class="text-center">
                                                <form method="post" onsubmit="return confirm('Hapus alternatif <?= htmlspecialchars($alt['nama']) ?>?')">
                                                    <input type="hidden" name="action" value="delete_alternatif">
                                                    <input type="hidden" name="id" value="<?= $alt['id'] ?>">
                                                    <button type="submit" class="btn-danger-custom">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- MASTER KRITERIA -->
            <div class="col-lg-6">
                <div class="section-box h-100">
                    <div class="section-title">
                        <span>📋</span> Master Data Kriteria (<?= count($kriteriaList) ?>)
                    </div>

                    <form method="post" class="row g-2 mb-4">
                        <input type="hidden" name="action" value="add_kriteria">
                        <div class="col-4">
                            <input type="text" name="kode" class="form-control form-control-custom" placeholder="Kode (K6)" required>
                        </div>
                        <div class="col-5">
                            <input type="text" name="nama" class="form-control form-control-custom" placeholder="Nama Kriteria" required>
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn-add-action w-100">+ Tambah</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th style="width: 70px;">ID</th>
                                    <th style="width: 90px;">Kode</th>
                                    <th>Nama Kriteria</th>
                                    <th style="width: 80px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($kriteriaList)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">Belum ada data kriteria.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($kriteriaList as $krit): ?>
                                        <tr>
                                            <td><?= $krit['id'] ?></td>
                                            <td><span class="badge bg-warning-subtle text-warning-emphasis"><?= htmlspecialchars($krit['kode']) ?></span></td>
                                            <td class="fw-bold"><?= htmlspecialchars($krit['nama']) ?></td>
                                            <td class="text-center">
                                                <form method="post" onsubmit="return confirm('Hapus kriteria <?= htmlspecialchars($krit['nama']) ?>?')">
                                                    <input type="hidden" name="action" value="delete_kriteria">
                                                    <input type="hidden" name="id" value="<?= $krit['id'] ?>">
                                                    <button type="submit" class="btn-danger-custom">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- ZONE RESET & RESTORE -->
        <div class="danger-zone mt-4">
            <h5>⚠️ Tindakan Pemulihan & Reset Database</h5>
            <div class="d-flex flex-wrap gap-3 align-items-center">
                <form method="post" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan semua nilai penilaian?')">
                    <input type="hidden" name="action" value="reset_penilaian">
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold">
                        🔄 Kosongkan Nilai Penilaian
                    </button>
                </form>

                <form method="post" onsubmit="return confirm('PERINGATAN: Seluruh database akan direset kembali ke data default Roblox (4 Game & 5 Kriteria). Lanjutkan?')">
                    <input type="hidden" name="action" value="restore_default">
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                        ⚡ Pulihkan Data Default Roblox
                    </button>
                </form>

                <a href="menu.php" class="btn btn-outline-secondary rounded-pill px-4 ms-auto">
                    ← Kembali ke Menu
                </a>
            </div>
        </div>

    </div>
</div>

<script src="bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
