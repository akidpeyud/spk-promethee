<?php
// Shared navigation bar for SPK PROMETHEE Roblox
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg spk-navbar sticky-top">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <span class="brand-icon">🎮</span>
            <div class="brand-text">
                <span class="brand-title">SPK PROMETHEE</span>
                <span class="brand-subtitle">Roblox Safe & Edu Gaming</span>
            </div>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#spkNav" aria-controls="spkNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="spkNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" href="index.php">
                        <span>🏠</span> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'menu.php') ? 'active' : '' ?>" href="menu.php">
                        <span>🧭</span> Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= in_array($current_page, ['perhitungan.php', 'hasil.php']) ? 'active' : '' ?>" href="perhitungan.php">
                        <span>🎮</span> Perhitungan Map
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= in_array($current_page, ['custom.php', 'input_custom.php', 'hasil_custom.php']) ? 'active' : '' ?>" href="custom.php">
                        <span>⚙️</span> Mode Kustom
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'manage.php') ? 'active' : '' ?>" href="manage.php">
                        <span>🛠️</span> Kelola Data
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 d-flex align-items-center gap-2">
                    <span class="status-indicator"></span> Database Ready
                </span>
            </div>
        </div>
    </div>
</nav>

<style>
/* Shared Navbar Styles */
.spk-navbar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 2px solid rgba(98, 129, 65, 0.15);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    padding: 12px 0;
    transition: all 0.3s ease;
    z-index: 1050;
}

.brand-icon {
    font-size: 28px;
    display: inline-block;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

.brand-text {
    display: flex;
    flex-direction: column;
    text-align: left;
}

.brand-title {
    font-weight: 800;
    font-size: 18px;
    letter-spacing: -0.5px;
    color: #4b6630;
    line-height: 1.1;
}

.brand-subtitle {
    font-size: 11px;
    font-weight: 600;
    color: #8c8270;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

.spk-navbar .nav-link {
    font-weight: 600;
    font-size: 14.5px;
    color: #495057;
    padding: 8px 18px !important;
    border-radius: 50px;
    transition: all 0.25s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.spk-navbar .nav-link:hover {
    color: #4b6630;
    background: rgba(98, 129, 65, 0.08);
    transform: translateY(-1px);
}

.spk-navbar .nav-link.active {
    color: #ffffff !important;
    background: linear-gradient(135deg, #4b6630 0%, #688c42 100%);
    box-shadow: 0 4px 12px rgba(98, 129, 65, 0.25);
}

.status-indicator {
    width: 8px;
    height: 8px;
    background-color: #2e7d32;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 0 rgba(46, 125, 50, 0.7);
    animation: pulse-green 2s infinite;
}

@keyframes pulse-green {
    0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(46, 125, 50, 0.7);
    }
    70% {
        transform: scale(1);
        box-shadow: 0 0 0 6px rgba(46, 125, 50, 0);
    }
    100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(46, 125, 50, 0);
    }
}
</style>
