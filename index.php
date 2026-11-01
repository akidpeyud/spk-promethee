<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK PROMETHEE - Game Roblox Ramah & Edukatif Anak</title>
    <!-- Local & CDN Bootstrap 5 Fallback -->
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4b6630;
            --primary-light: #688c42;
            --primary-dark: #374d23;
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
            position: relative;
            overflow-x: hidden;
            color: #333;
        }

        /* Floating Background Decorations */
        .bg-deco-1, .bg-deco-2 {
            position: fixed;
            pointer-events: none;
            opacity: 0.08;
            z-index: 1;
            user-select: none;
        }

        .bg-deco-1 {
            font-size: 240px;
            top: 5%;
            left: 5%;
            animation: floatSlow 8s ease-in-out infinite;
        }

        .bg-deco-2 {
            font-size: 200px;
            bottom: 5%;
            right: 5%;
            animation: floatSlow 10s ease-in-out infinite reverse;
        }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(3deg); }
        }

        /* Cloud Tags */
        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 100px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            padding: 12px 30px;
            font-weight: 600;
            color: var(--primary);
            font-size: 15px;
            z-index: 2;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .cloud1 {
            top: 90px;
            animation: cloudFloat1 24s linear infinite;
        }

        .cloud2 {
            bottom: 40px;
            animation: cloudFloat2 28s linear infinite;
        }

        @keyframes cloudFloat1 {
            0% { left: -320px; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { left: calc(100% + 50px); opacity: 0; }
        }

        @keyframes cloudFloat2 {
            0% { right: -320px; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { right: calc(100% + 50px); opacity: 0; }
        }

        /* Main Container */
        .main-hero-wrapper {
            position: relative;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 72px);
            padding: 40px 20px;
        }

        .card-hero {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 36px;
            padding: 55px 50px;
            box-shadow: 0 35px 80px rgba(0, 0, 0, 0.28);
            max-width: 780px;
            width: 100%;
            text-align: center;
            position: relative;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            animation: heroIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes heroIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .card-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            border-radius: 36px 36px 0 0;
            background: linear-gradient(90deg, #4b6630, #d4bd8f, #688c42);
        }

        .hero-badge-top {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-light);
            color: var(--primary);
            border: 1px solid var(--accent);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 20px;
        }

        .main-icon {
            font-size: 72px;
            margin-bottom: 15px;
            display: inline-block;
            animation: bounceSoft 2.5s ease-in-out infinite;
        }

        @keyframes bounceSoft {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        .hero-title {
            color: var(--primary);
            font-weight: 800;
            font-size: 40px;
            line-height: 1.15;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .hero-title-highlight {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            color: #555;
            font-size: 17px;
            line-height: 1.6;
            margin-bottom: 25px;
            font-weight: 400;
        }

        .hero-subtitle strong {
            color: var(--primary);
            font-weight: 700;
        }

        /* Stat Badges */
        .feature-pills {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .feature-pill {
            background: #f8faf5;
            border: 1px solid rgba(98, 129, 65, 0.18);
            border-radius: 18px;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .feature-pill:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(98, 129, 65, 0.12);
            border-color: var(--primary);
            background: #ffffff;
        }

        .pill-icon {
            width: 42px;
            height: 42px;
            background: var(--accent-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .pill-text {
            text-align: left;
        }

        .pill-text .num {
            font-weight: 800;
            font-size: 16px;
            color: var(--primary);
            line-height: 1.1;
        }

        .pill-text .label {
            font-size: 12px;
            color: #777;
            font-weight: 500;
        }

        /* Game Preview Chips */
        .games-preview {
            background: #fdfcf9;
            border: 1px dashed #d4bd8f;
            border-radius: 20px;
            padding: 18px 20px;
            margin-bottom: 30px;
        }

        .games-preview-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .game-chips-list {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .game-chip {
            background: #ffffff;
            border: 1px solid #e0e5da;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }

        /* CTA Buttons */
        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #ffffff;
            border-radius: 50px;
            padding: 16px 42px;
            font-size: 17px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 12px 30px rgba(75, 102, 48, 0.4);
            transition: all 0.3s ease;
            border: none;
        }

        .btn-hero-primary:hover {
            color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(75, 102, 48, 0.5);
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
        }

        .btn-hero-secondary {
            background: #ffffff;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 50px;
            padding: 14px 34px;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-hero-secondary:hover {
            background: var(--primary);
            color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(75, 102, 48, 0.2);
        }

        /* Footer Credits */
        .footer-credits {
            margin-top: 30px;
            font-size: 13px;
            color: #888;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .card-hero {
                padding: 40px 24px;
            }
            .hero-title {
                font-size: 30px;
            }
            .main-icon {
                font-size: 55px;
            }
            .feature-pill {
                padding: 10px 14px;
            }
            .btn-hero-primary, .btn-hero-secondary {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<?php require_once 'navbar.php'; ?>

<!-- Animated Clouds -->
<div class="cloud cloud1 d-none d-md-inline-flex">
    <span>☁️</span> Andika Dwi Putra
</div>

<div class="cloud cloud2 d-none d-md-inline-flex">
    <span>☁️</span> Hayyin Sabila
</div>

<!-- Decorative Floating Background -->
<div class="bg-deco-1">⛰️</div>
<div class="bg-deco-2">🏔️</div>

<div class="main-hero-wrapper">
    <div class="card-hero">
        <div class="hero-badge-top">
            <span>✨</span> PROMETHEE II Decision Support System
        </div>

        <div class="main-icon">🎮</div>

        <h1 class="hero-title">
            SPK <span class="hero-title-highlight">PROMETHEE</span>
        </h1>

        <p class="hero-subtitle">
            Sistem Pendukung Keputusan Penentuan <strong>Game Roblox yang Aman & Edukatif untuk Anak-Anak</strong> menggunakan metode <strong>Preference Ranking Organization Method for Enrichment Evaluation (PROMETHEE)</strong>.
        </p>

        <!-- Feature Pills -->
        <div class="feature-pills">
            <div class="feature-pill">
                <div class="pill-icon">🎮</div>
                <div class="pill-text">
                    <div class="num">4 Game</div>
                    <div class="label">Alternatif Map</div>
                </div>
            </div>
            <div class="feature-pill">
                <div class="pill-icon">📊</div>
                <div class="pill-text">
                    <div class="num">5 Kriteria</div>
                    <div class="label">Aspek Penilaian</div>
                </div>
            </div>
            <div class="feature-pill">
                <div class="pill-icon">⚡</div>
                <div class="pill-text">
                    <div class="num">Net Flow</div>
                    <div class="label">Perangkingan Akurat</div>
                </div>
            </div>
        </div>

        <!-- Games Preview Chips -->
        <div class="games-preview">
            <div class="games-preview-title">
                <span>🎯</span> Alternatif Game Yang Dievaluasi
            </div>
            <div class="game-chips-list">
                <span class="game-chip">🛒 Supermarket Together</span>
                <span class="game-chip">🏡 Brookhaven</span>
                <span class="game-chip">🌱 Plant vs Brainrot</span>
                <span class="game-chip">🌻 Grow a Garden</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="hero-actions">
            <a href="menu.php" class="btn-hero-primary" id="startBtn">
                <span>🚀 Mulai Sekarang</span>
            </a>
            <a href="perhitungan.php" class="btn-hero-secondary">
                <span>⚡ Perhitungan Cepat</span>
            </a>
        </div>

        <div class="footer-credits">
            Pengembangan Sistem Keputusan Berbasis PROMETHEE &copy; <?= date('Y') ?>
        </div>
    </div>
</div>

<script src="bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
