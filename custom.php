<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan PROMETHEE</title>
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h3 class="mb-4">⚙️ Pengaturan Alternatif & Kriteria</h3>

    <form method="post" action="perhitungan.php">
        <div class="mb-3">
            <label class="form-label">Jumlah Alternatif</label>
            <input type="number" name="alternatif" class="form-control" min="2" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jumlah Kriteria</label>
            <input type="number" name="kriteria" class="form-control" min="2" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Lanjutkan Perhitungan
        </button>
    </form>
</div>

</body>
</html>
