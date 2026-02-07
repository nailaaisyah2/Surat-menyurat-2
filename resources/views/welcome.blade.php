<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Surat Menyurat Antar Perusahaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e40af;
            --primary-hover: #1e3a8a;
            --muted: #6b7280;
            --border: #e5e7eb;
            --background: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--background);
            color: #1f2937;
            line-height: 1.6;
        }

        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }

        /* Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-box {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary) 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo-box span {
            color: white;
            font-weight: 700;
            font-size: 14px;
        }

        .logo-text {
            font-weight: 700;
            font-size: 18px;
            color: #1f2937;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 24px 80px;
            overflow: hidden;
            background: linear-gradient(to bottom right, #ffffff 0%, rgba(239, 246, 255, 0.3) 30%, #ffffff 100%);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .hero-bg-blur-1 {
            position: absolute;
            top: 80px;
            right: 40px;
            width: 288px;
            height: 288px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            filter: blur(80px);
        }

        .hero-bg-blur-2 {
            position: absolute;
            bottom: 160px;
            left: 40px;
            width: 384px;
            height: 384px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            filter: blur(80px);
        }

        .hero-bg-blur-3 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
            background: rgba(59, 130, 246, 0.05);
            border-radius: 50%;
            filter: blur(80px);
        }

        .hero-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.02;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23000000' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .hero-decorative-1 {
            position: absolute;
            top: 128px;
            left: 80px;
            width: 64px;
            height: 64px;
            border: 2px solid rgba(30, 64, 175, 0.2);
            border-radius: 8px;
            transform: rotate(12deg);
        }

        .hero-decorative-2 {
            position: absolute;
            bottom: 160px;
            right: 128px;
            width: 48px;
            height: 48px;
            border: 2px solid rgba(30, 64, 175, 0.15);
            border-radius: 50%;
        }

        .hero-decorative-3 {
            position: absolute;
            top: 33%;
            right: 80px;
            width: 32px;
            height: 32px;
            background: rgba(30, 64, 175, 0.1);
            border-radius: 4px;
            transform: rotate(45deg);
        }

        .hero-content {
            max-width: 1280px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(30, 64, 175, 0.1);
            color: var(--primary);
            font-size: 14px;
            font-weight: 500;
            border-radius: 9999px;
            margin-bottom: 24px;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 24px;
            color: #111827;
        }

        .hero-title-gradient {
            background: linear-gradient(to right, #1e40af, #2563eb, #1e40af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-description {
            font-size: 1.25rem;
            color: var(--muted);
            max-width: 42rem;
            margin-bottom: 48px;
            line-height: 1.75;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }

        .btn-hero {
            padding: 20px 32px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .btn-hero-primary {
            background: var(--primary);
            color: white;
        }

        .btn-hero-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(30, 64, 175, 0.3);
            color: white;
        }

        .btn-hero-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-hero-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .hero-image-wrapper {
            position: relative;
        }

        .hero-image-blur {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, rgba(30, 64, 175, 0.2), rgba(37, 99, 235, 0.2));
            border-radius: 16px;
            filter: blur(40px);
        }

        .hero-image-placeholder {
            position: relative;
            width: 100%;
            height: 500px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .hero-image-placeholder::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='200' height='200' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3ClinearGradient id='grad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:rgba(255,255,255,0.3);stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:rgba(255,255,255,0.1);stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='200' height='200' fill='url(%23grad)'/%3E%3E%3Cpath d='M50 50 L150 50 L150 150 L50 150 Z M70 70 L130 70 M70 100 L130 100 M70 130 L130 130' stroke='rgba(255,255,255,0.5)' stroke-width='3' fill='none'/%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .hero-image-placeholder i {
            font-size: 120px;
            color: rgba(255, 255, 255, 0.9);
            position: relative;
            z-index: 1;
        }

        /* Features Section */
        .features-section {
            padding: 80px 24px;
            background: linear-gradient(to bottom, #ffffff 0%, rgba(249, 250, 251, 0.5) 50%);
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .section-title {
            font-size: 2.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .section-description {
            font-size: 1.125rem;
            color: var(--muted);
            max-width: 42rem;
            margin: 0 auto;
        }

        .features-image-wrapper {
            position: relative;
            max-width: 768px;
            margin: 0 auto 64px;
        }

        .features-image-blur {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, rgba(30, 64, 175, 0.1), rgba(37, 99, 235, 0.1));
            border-radius: 16px;
            filter: blur(40px);
        }

        .features-image-placeholder {
            position: relative;
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .features-image-placeholder::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='200' height='200' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3ClinearGradient id='grad2' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:rgba(255,255,255,0.3);stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:rgba(255,255,255,0.1);stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='200' height='200' fill='url(%23grad2)'/%3E%3Ccircle cx='100' cy='100' r='40' stroke='rgba(255,255,255,0.5)' stroke-width='3' fill='none'/%3E%3Cpath d='M80 100 L100 120 L120 80' stroke='rgba(255,255,255,0.5)' stroke-width='3' fill='none'/%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .features-image-placeholder i {
            font-size: 100px;
            color: rgba(255, 255, 255, 0.9);
            position: relative;
            z-index: 1;
        }

        .feature-card {
            padding: 24px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: white;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .feature-icon i {
            font-size: 28px;
            color: white;
        }

        .feature-content {
            display: flex;
            gap: 16px;
        }

        .feature-text {
            flex: 1;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .feature-description {
            color: var(--muted);
            line-height: 1.75;
        }

        /* Trust Section */
        .trust-section {
            padding: 64px 24px;
            background: white;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .trust-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .trust-label {
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 32px;
        }

        .company-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .company-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .company-box {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: linear-gradient(to bottom right, #f9fafb, #f3f4f6);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .company-item:hover .company-box {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .company-initial {
            font-size: 20px;
            font-weight: 700;
            background: linear-gradient(to bottom right, #374151, #111827);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .company-name {
            font-size: 12px;
            color: var(--muted);
            text-align: center;
            font-weight: 500;
        }

        /* CTA Section */
        .cta-section {
            position: relative;
            padding: 80px 24px;
            background: linear-gradient(to bottom right, rgba(239, 246, 255, 1) 0%, #ffffff 50%, rgba(239, 246, 255, 0.5) 100%);
            overflow: hidden;
        }

        .cta-bg-blur-1 {
            position: absolute;
            top: 40px;
            left: 40px;
            width: 256px;
            height: 256px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            filter: blur(80px);
        }

        .cta-bg-blur-2 {
            position: absolute;
            bottom: 40px;
            right: 40px;
            width: 320px;
            height: 320px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            filter: blur(80px);
        }

        .cta-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .cta-image-wrapper {
            position: relative;
        }

        .cta-image-blur {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, rgba(30, 64, 175, 0.2), rgba(37, 99, 235, 0.2));
            border-radius: 16px;
            filter: blur(40px);
        }

        .cta-image-placeholder {
            position: relative;
            width: 100%;
            height: 450px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .cta-image-placeholder::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='200' height='200' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3ClinearGradient id='grad3' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' style='stop-color:rgba(255,255,255,0.3);stop-opacity:1' /%3E%3Cstop offset='100%25' style='stop-color:rgba(255,255,255,0.1);stop-opacity:1' /%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='200' height='200' fill='url(%23grad3)'/%3E%3Ccircle cx='60' cy='60' r='20' fill='rgba(255,255,255,0.3)'/%3E%3Ccircle cx='140' cy='80' r='15' fill='rgba(255,255,255,0.3)'/%3E%3Ccircle cx='100' cy='140' r='18' fill='rgba(255,255,255,0.3)'/%3E%3Cpath d='M60 60 L140 80 M140 80 L100 140 M100 140 L60 60' stroke='rgba(255,255,255,0.5)' stroke-width='2'/%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .cta-image-placeholder i {
            font-size: 100px;
            color: rgba(255, 255, 255, 0.9);
            position: relative;
            z-index: 1;
        }

        .cta-title {
            font-size: 2.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 24px;
        }

        .cta-description {
            font-size: 1.125rem;
            color: var(--muted);
            max-width: 42rem;
            margin-bottom: 32px;
        }

        .cta-features {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
            margin-bottom: 32px;
        }

        .cta-feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--muted);
        }

        .cta-feature-item i {
            color: var(--primary);
            font-size: 16px;
        }

        .cta-note {
            font-size: 14px;
            color: var(--muted);
            margin-top: 16px;
        }

        /* Footer */
        footer {
            border-top: 1px solid var(--border);
            background: linear-gradient(to bottom, #ffffff 0%, #f9fafb 100%);
            padding: 48px 24px 24px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 32px;
            margin-bottom: 32px;
        }

        .footer-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .footer-text {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.75;
        }

        .footer-link {
            display: block;
            font-size: 14px;
            color: var(--muted);
            text-decoration: none;
            margin-bottom: 8px;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: var(--primary);
        }

        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .footer-contact-item i {
            font-size: 16px;
        }

        .footer-bottom {
            padding-top: 32px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .footer-copyright {
            font-size: 14px;
            color: var(--muted);
        }

        /* Responsive */
        @media (min-width: 768px) {
            .hero-title {
                font-size: 4rem;
            }

            .company-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .footer-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .hero-decorative-1,
            .hero-decorative-2,
            .hero-decorative-3 {
                display: block;
            }
        }

        @media (min-width: 1024px) {
            .hero-title {
                font-size: 4.5rem;
            }

            .company-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        @media (max-width: 767px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-description {
                font-size: 1.125rem;
            }

            .btn-hero {
                width: 100%;
                justify-content: center;
            }

            .section-title {
                font-size: 1.875rem;
            }

            .hero-decorative-1,
            .hero-decorative-2,
            .hero-decorative-3 {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-content">
            <div class="logo-container">
                <div class="logo-box">
                    <span>PS</span>
                </div>
                <span class="logo-text">Platform Surat</span>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg-blur-1"></div>
        <div class="hero-bg-blur-2"></div>
        <div class="hero-bg-blur-3"></div>
        <div class="hero-pattern"></div>
        <div class="hero-decorative-1"></div>
        <div class="hero-decorative-2"></div>
        <div class="hero-decorative-3"></div>
        
        <div class="hero-content">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                    <div class="hero-badge">
                        Platform Digital Terpercaya
                    </div>
                    
                    <h1 class="hero-title font-serif">
                        <span>Platform Surat Menyurat</span><br>
                        <span class="hero-title-gradient">Antar Perusahaan</span>
                    </h1>

                    <p class="hero-description">
                        Kelola korespondensi bisnis dengan sistem yang aman, efisien, dan profesional.
                        Tingkatkan produktivitas komunikasi perusahaan Anda.
                    </p>

                    <div class="hero-buttons">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-hero btn-hero-primary">
                                <i class="bi bi-speedometer2"></i>
                                <span>Masuk ke Dashboard</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-hero btn-hero-outline">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-hero btn-hero-primary">
                                <i class="bi bi-box-arrow-in-right"></i>
                                <span>Masuk / Login</span>
                            </a>
                            <a href="{{ route('register') }}" class="btn-hero btn-hero-outline">
                                <i class="bi bi-person-plus"></i>
                                <span>Daftar Akun Baru</span>
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="hero-image-wrapper">
                        <div class="hero-image-blur"></div>
                        <div class="hero-image-placeholder">
                            <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.9;">
                                <rect x="30" y="30" width="140" height="180" rx="8" fill="rgba(255,255,255,0.2)" stroke="rgba(255,255,255,0.5)" stroke-width="2"/>
                                <line x1="50" y1="70" x2="150" y2="70" stroke="rgba(255,255,255,0.7)" stroke-width="3"/>
                                <line x1="50" y1="100" x2="150" y2="100" stroke="rgba(255,255,255,0.7)" stroke-width="3"/>
                                <line x1="50" y1="130" x2="120" y2="130" stroke="rgba(255,255,255,0.7)" stroke-width="3"/>
                                <circle cx="160" cy="50" r="15" fill="rgba(255,255,255,0.3)"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="features-container">
            <div class="section-header">
                <h2 class="section-title">Fitur Unggulan</h2>
                <p class="section-description">
                    Solusi lengkap untuk kebutuhan korespondensi bisnis modern
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-content">
                            <div class="feature-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="feature-text">
                                <h3 class="feature-title">Keamanan Terjamin</h3>
                                <p class="feature-description">
                                    Enkripsi end-to-end dan sistem keamanan berlapis untuk melindungi data korespondensi perusahaan Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-content">
                            <div class="feature-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="feature-text">
                                <h3 class="feature-title">Efisiensi Waktu</h3>
                                <p class="feature-description">
                                    Otomatisasi proses surat menyurat menghemat waktu hingga 70% dibandingkan metode konvensional.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-content">
                            <div class="feature-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="feature-text">
                                <h3 class="feature-title">Kolaborasi Tim</h3>
                                <p class="feature-description">
                                    Koordinasi yang lebih baik dengan fitur persetujuan multi-level dan tracking status real-time.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="feature-content">
                            <div class="feature-icon">
                                <i class="bi bi-file-check"></i>
                            </div>
                            <div class="feature-text">
                                <h3 class="feature-title">Arsip Digital</h3>
                                <p class="feature-description">
                                    Penyimpanan terpusat dengan sistem pencarian cepat dan backup otomatis untuk semua dokumen.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-bg-blur-1"></div>
        <div class="cta-bg-blur-2"></div>
        <div class="cta-container">
            <div class="row align-items-center">
                <div class="col-lg-6 d-none d-lg-block mb-5 mb-lg-0">
                    <div class="cta-image-wrapper">
                        <div class="cta-image-blur"></div>
                        <div class="cta-image-placeholder">
                            <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.9;">
                                <circle cx="60" cy="60" r="25" fill="rgba(255,255,255,0.3)" stroke="rgba(255,255,255,0.5)" stroke-width="2"/>
                                <circle cx="140" cy="80" r="20" fill="rgba(255,255,255,0.3)" stroke="rgba(255,255,255,0.5)" stroke-width="2"/>
                                <circle cx="100" cy="140" r="22" fill="rgba(255,255,255,0.3)" stroke="rgba(255,255,255,0.5)" stroke-width="2"/>
                                <path d="M60 60 L140 80 M140 80 L100 140 M100 140 L60 60" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="60" cy="60" r="8" fill="rgba(255,255,255,0.6)"/>
                                <circle cx="140" cy="80" r="6" fill="rgba(255,255,255,0.6)"/>
                                <circle cx="100" cy="140" r="7" fill="rgba(255,255,255,0.6)"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center text-lg-start">
                    <h2 class="cta-title">
                        Siap Meningkatkan Efisiensi<br>
                        Korespondensi Bisnis?
                    </h2>

                    <p class="cta-description">
                        Bergabunglah dengan ratusan perusahaan yang telah mempercayai platform kami
                        untuk mengelola komunikasi bisnis mereka.
                    </p>

                    <div class="cta-features">
                        <div class="cta-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Keamanan Terjamin</span>
                        </div>
                        <div class="cta-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Support 24/7</span>
                        </div>
                        <div class="cta-feature-item">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Implementasi Cepat</span>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-hero btn-hero-primary">
                            <i class="bi bi-speedometer2"></i>
                            <span>Masuk ke Dashboard</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-grid">
                <div>
                    <h3 class="footer-title">Platform Surat</h3>
                    <p class="footer-text">
                        Solusi terpercaya untuk mengelola korespondensi bisnis antar perusahaan
                        dengan aman dan efisien.
                    </p>
                </div>

                <div>
                    <h3 class="footer-title">Kontak</h3>
                    <div class="footer-contact-item">
                        <i class="bi bi-envelope"></i>
                        <a href="mailto:info@platformsurat.com" class="footer-link">info@platformsurat.com</a>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-telephone"></i>
                        <a href="tel:+62215551234" class="footer-link">+62 21 5551 234</a>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-geo-alt"></i>
                        <span>Karang Baru, Kuala Simpang, Indonesia</span>
                    </div>
                </div>

                <div>
                    <h3 class="footer-title">Legal</h3>
                    <a href="#" class="footer-link">Kebijakan Privasi</a>
                    <a href="#" class="footer-link">Syarat & Ketentuan</a>
                    <a href="#" class="footer-link">Keamanan</a>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="footer-copyright">
                    © {{ date('Y') }} Platform Surat Menyurat. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
