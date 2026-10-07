<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Magang — BPS Kabupaten Kolaka Utara</title>
    <meta name="description" content="Galeri dokumentasi kegiatan mahasiswa magang BPS Kabupaten Kolaka Utara. Program MBKM dan PKL.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            /* BPS Color Palette */
            --bps-blue: #1a5fa8;
            --bps-blue-dark: #0d3d6e;
            --bps-blue-light: #2b7fd4;
            --bps-green: #2e8b3e;
            --bps-green-light: #3aad50;
            --bps-orange: #e07b1a;
            --bps-orange-light: #f5921e;

            /* Derived */
            --bg: #f4f6fb;
            --white: #ffffff;
            --surface: #eef1f8;
            --text: #1a2340;
            --text-light: #4a5568;
            --muted: #8899aa;
            --border: #d8e2ef;

            /* Grad */
            --grad-blue: linear-gradient(135deg, #0d3d6e 0%, #1a5fa8 50%, #2b7fd4 100%);
            --grad-bps: linear-gradient(135deg, #1a5fa8 0%, #2e8b3e 50%, #e07b1a 100%);
        }

        html { scroll-behavior: smooth; }
        @php
            $defaultColors = ['#e07b1a', '#1a5fa8', '#2e8b3e', '#8b2e59', '#2b7fd4', '#d47f2b'];
            $initialColor = '#e07b1a';
            if(isset($interns) && $interns->count() > 0) {
                $firstIntern = $interns->first();
                $initialColor = !empty($firstIntern->gallery_color) ? $firstIntern->gallery_color : $defaultColors[0];
            }
        @endphp
        body { 
            font-family: 'Nunito', sans-serif; 
            background-color: {{ $initialColor }}; /* Initial color, matches first slide */
            transition: background-color 0.8s ease;
            color: rgba(255,255,255,0.85); 
            min-height: 100vh; overflow-x: hidden; letter-spacing: 0.01em; 
        }

        /* ─── CUSTOM SCROLLBAR ─── */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.25); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.5); }

        a { text-decoration: none; color: inherit; }

        /* ─── HEADER ─── */
        .site-header {
            position: absolute; top: 0; left: 0; right: 0; z-index: 100;
            background: transparent; padding: 1.5rem 0;
        }
        .header-inner {
            width: 100%; padding: 0 4%;
            display: flex; align-items: center; justify-content: space-between;
        }
        .header-logo {
            position: relative;
            display: inline-flex;
            overflow: hidden;
            padding: 6px; /* give room for shadow */
            border-radius: 8px;
        }
        .header-logo img {
            height: 52px; width: auto; object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.5));
            background: transparent;
            -webkit-box-reflect: below 2px linear-gradient(to bottom, rgba(0,0,0,0.0) 60%, rgba(255,255,255,0.4) 100%);
        }
        .header-logo::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 40%; height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.7) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-25deg);
            animation: logoShine 4s infinite;
        }
        @keyframes logoShine {
            0% { left: -100%; }
            15% { left: 200%; }
            100% { left: 200%; }
        }
        .nav-wrapper {
            display: flex; align-items: center; gap: 1rem;
        }
        .main-nav {
            display: flex; gap: 0.5rem;
            background: rgba(255,255,255,0.1); backdrop-filter: blur(12px);
            padding: 0.4rem 1rem; border-radius: 30px;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .nav-link {
            font-size: 0.85rem; font-weight: 700; color: rgba(255,255,255,0.85);
            letter-spacing: 0.05em; transition: all 0.3s;
            padding: 0.4rem 1rem; border-radius: 20px; text-transform: uppercase;
            border: 1px solid transparent;
        }
        .nav-link:hover, .nav-link.active {
            color: white; background: rgba(255,255,255,0.15); text-shadow: none;
            backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.4);
        }
        
        .header-right { display: flex; align-items: center; gap: 12px; }
        .hbtn {
            padding: 0.6rem 1.4rem; border-radius: 30px; font-size: 0.85rem;
            font-weight: 700; cursor: pointer; transition: all 0.3s; font-family: 'Nunito', sans-serif;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .hbtn-outline {
            border: 2px solid rgba(255,255,255,0.4); color: white;
            backdrop-filter: blur(5px);
        }
        .hbtn-outline:hover { background: white; color: #111; }
        .hbtn-solid {
            background: rgba(255,255,255,0.15); color: white;
            border: 1px solid rgba(255,255,255,0.4); backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .hbtn-solid:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.2); background: rgba(255,255,255,0.25); }

        /* ─── DYNAMIC INTERN SLIDER ─── */
        .hero-slider {
            position: relative; width: 100%; height: 90vh; min-height: 600px;
            overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .hs-content {
            position: absolute; inset: 0; z-index: 10; width: 100%;
        }
        
        .hs-text {
            margin-top: 1.5rem;
            max-width: 600px; z-index: 20;
            display: flex; align-items: center; gap: 1.5rem;
            text-align: left;
        }
        .hs-text-info { flex: 1; }
        .hs-text h1 {
            font-family: 'Fredoka One', cursive; font-size: 1.4rem; color: white;
            line-height: 1.2; margin-bottom: 0.2rem; text-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .hs-text p {
            font-size: 0.85rem; color: rgba(255,255,255,0.95); margin-bottom: 0; line-height: 1.5;
            text-shadow: 0 2px 10px rgba(0,0,0,0.4);
        }
        .hs-btn-login {
            display: inline-flex; align-items: center; gap: 10px; color: white;
            background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.4);
            padding: 1rem 2rem; border-radius: 30px; font-weight: 700;
            transition: all 0.3s; box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .hs-btn-login:hover { transform: translateY(-3px); box-shadow: 0 15px 40px rgba(0,0,0,0.2); background: rgba(255,255,255,0.25); }

        .hs-img {
            position: absolute; inset: 0; padding-top: 80px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: all 0.8s cubic-bezier(0.25, 1, 0.5, 1);
            transform: translateY(100px);
        }
        .hs-img.active { opacity: 1; pointer-events: auto; z-index: 5; transform: translateY(0); }
        .hs-img.prev { transform: translateY(-150px); opacity: 0; }
        .hs-img.next { transform: translateY(150px); opacity: 0; z-index: 4; }

        .hs-giant-text {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            font-family: 'Fredoka One', cursive; font-size: clamp(4rem, 12vw, 10rem);
            color: rgba(255,255,255,0.95); white-space: nowrap; z-index: 1;
            letter-spacing: -0.01em; user-select: none; pointer-events: none;
            text-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .hs-photo {
            position: relative; z-index: 5;
            width: 260px; height: 350px; border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5); overflow: hidden;
            background: white; transform: rotate(15deg);
            animation: float 6s ease-in-out infinite;
            cursor: pointer; border: 4px solid rgba(255,255,255,0.1);
        }
        .hs-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
        
        @keyframes float {
            0% { transform: rotate(15deg) translateY(0); }
            50% { transform: rotate(15deg) translateY(-20px); }
            100% { transform: rotate(15deg) translateY(0); }
        }

        .hs-nav-wrap {
            position: absolute; bottom: 3rem; left: 2rem; display: flex; gap: 1rem; z-index: 20;
        }
        .hs-nav-btn {
            width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.4);
            color: white; display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; cursor: pointer; transition: all 0.3s;
        }
        .hs-nav-btn:hover { background: white; color: #111; }
        
        .hs-dots-wrap {
            position: absolute; right: 2rem; top: 50%; transform: translateY(-50%);
            display: flex; flex-direction: column; gap: 10px; z-index: 20;
        }
        .hs-dot {
            width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.2);
            color: white; display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: all 0.3s;
        }
        .hs-dot.active { background: white; color: #111; transform: scale(1.2); }

        /* ─── MAIN CONTENT 2 COLUMNS ─── */
        .main-content {
            width: 100%; padding: 3rem 4%;
            display: grid; grid-template-columns: 1.5fr 1fr; gap: 3rem;
        }
        @media (max-width: 900px) { .main-content { grid-template-columns: 1fr; } }

        .col-title {
            font-family: 'Fredoka One', cursive;
            font-size: 1.6rem; font-weight: 700; color: white;
            margin-bottom: 1rem; padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(255,255,255,0.1);
            position: relative;
        }
        .col-title::after {
            content: '';
            position: absolute; bottom: -2px; left: 0;
            width: 50px; height: 2px;
            background: white;
        }

        /* ─── STANDALONE GALLERY ─── */
        .standalone-gallery {
            background: rgba(255,255,255,0.03); backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 4rem 4%; position: relative; overflow: hidden; color: white;
        }
        .standalone-gallery .gallery-inner {
            width: 100%; max-width: none; margin: 0 auto; position: relative; z-index: 10;
        }

        .standalone-gallery .gallery-filters {
            display: flex; gap: 10px; margin-bottom: 2rem; flex-wrap: wrap; justify-content: center;
        }
        .standalone-gallery .g-filter {
            background: transparent; color: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 0.5rem 1.2rem; border-radius: 20px;
            font-size: 0.75rem; font-weight: 700; cursor: pointer;
            transition: all 0.3s; text-transform: uppercase;
        }
        .standalone-gallery .g-filter:hover { border-color: rgba(255,255,255,0.5); color: white; background: rgba(255,255,255,0.1); }
        .standalone-gallery .g-filter.active { background: rgba(255,255,255,0.25); color: white; border-color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }

        .standalone-gallery .gallery-grid {
            column-count: 2; column-gap: 16px; margin-bottom: 1.5rem;
        }
        @media(min-width: 768px) { .standalone-gallery .gallery-grid { column-count: 3; } }
        @media(min-width: 1024px) { .standalone-gallery .gallery-grid { column-count: 4; } }
        @media(min-width: 1440px) { .standalone-gallery .gallery-grid { column-count: 6; } }

        @keyframes galleryPop {
            0% { transform: scale(0.6) translateY(40px); opacity: 0; }
            60% { transform: scale(1.05) translateY(-10px); opacity: 1; }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }
        .standalone-gallery .gallery-thumb {
            break-inside: avoid; display: inline-block; width: 100%; margin-bottom: 16px;
            overflow: hidden; border-radius: 12px; cursor: pointer; position: relative;
            border: 1px solid rgba(255,255,255,0.15);
            transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s; box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .standalone-gallery .gallery-thumb.is-hidden { opacity: 0; transform: scale(0.6) translateY(40px); }
        .standalone-gallery .gallery-thumb.pop-in { animation: galleryPop 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        .standalone-gallery .gallery-thumb:hover { transform: translateY(-4px); box-shadow: 0 15px 35px rgba(0,0,0,0.3); border-color: white; }
        .standalone-gallery .gallery-thumb img { width: 100%; display: block; object-fit: cover; }
        
        .standalone-gallery .gallery-thumb .th-overlay {
            position: absolute; inset: 0; background: rgba(0,0,0,0.6);
            opacity: 0; transition: opacity 0.25s;
            display: flex; align-items: flex-end; padding: 12px;
        }
        .standalone-gallery .gallery-thumb:hover .th-overlay { opacity: 1; }
        .standalone-gallery .th-overlay span { font-size: 0.8rem; color: white; font-weight: 600; line-height: 1.3; }
        .btn-more {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0.6rem 1.4rem; border-radius: 6px;
            font-size: 0.82rem; font-weight: 700;
            background: rgba(255,255,255,0.1); color: white;
            border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(5px);
            transition: all 0.2s; font-family: 'Nunito', sans-serif;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .btn-more:hover { background: rgba(255,255,255,0.2); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.3); }

        /* Activity/News column */
        .activity-list { display: flex; flex-direction: column; gap: 1rem; }
        .activity-item {
            display: flex; gap: 1rem; padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .activity-item:last-child { border-bottom: none; padding-bottom: 0; }
        .act-date {
            flex-shrink: 0; width: 44px; height: 44px;
            background: rgba(255,255,255,0.2); color: white; border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.4); backdrop-filter: blur(5px);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            font-size: 0.65rem; font-weight: 700; text-align: center; line-height: 1.1;
        }
        .act-date .day { font-size: 1.1rem; font-weight: 800; }
        .act-body h4 { font-size: 0.85rem; font-weight: 700; color: white; line-height: 1.3; margin-bottom: 3px; }
        .act-body p { font-size: 0.75rem; color: rgba(255,255,255,0.6); line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .act-body .act-intern { font-size: 0.68rem; color: #a5b4fc; font-weight: 600; margin-top: 4px; }
        .act-empty { color: rgba(255,255,255,0.5); font-size: 0.85rem; padding: 1rem 0; }

        /* Info column */
        .info-box {
            background: rgba(255,255,255,0.15); border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(15px); box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            overflow: hidden; margin-bottom: 1.25rem;
        }
        .info-box-head {
            padding: 0.7rem 1rem;
            font-size: 0.8rem; font-weight: 700; color: white;
            letter-spacing: 0.05em; text-transform: uppercase;
            background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .info-box-body { padding: 1rem; }
        .info-list { list-style: none; }
        .info-list li {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 0; font-size: 0.8rem; color: rgba(255,255,255,0.7);
            border-bottom: 1px dashed rgba(255,255,255,0.1);
        }
        .info-list li:last-child { border-bottom: none; }
        .info-list li::before { content: '›'; color: rgba(255,255,255,0.5); font-weight: 700; font-size: 1rem; }

        /* Intern cards mini */
        .intern-mini-list { display: flex; flex-direction: column; gap: 8px; }
        .intern-mini {
            display: flex; align-items: center; gap: 10px;
            padding: 8px; border-radius: 8px;
            background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.2s; cursor: pointer;
        }
        .intern-mini:hover { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.2); transform: translateX(3px); }
        .im-av {
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,0.2); color: white;
            border: 1px solid rgba(255,255,255,0.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; font-weight: 800; flex-shrink: 0;
            overflow: hidden;
        }
        .im-av img { width: 100%; height: 100%; object-fit: cover; }
        .im-info { min-width: 0; }
        .im-name { font-size: 0.78rem; font-weight: 700; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .im-div { font-size: 0.68rem; color: rgba(255,255,255,0.5); }

        /* ─── DIVIDER ─── */
        .section-divider {
            background: rgba(255,255,255,0.1); height: 1px;
            width: 92%; margin: 0 auto;
        }

        /* ─── FOOTER ─── */
        footer {
            background: rgba(0,0,0,0.3); backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.7); margin-top: 0;
        }
        .footer-main {
            width: 100%; padding: 2.5rem 4% 1.5rem;
            display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 2rem;
        }
        @media (max-width: 768px) { .footer-main { grid-template-columns: 1fr; } }
        .footer-brand { }
        .footer-brand h3 {
            font-family: 'Fredoka One', cursive;
            color: white; font-size: 1.1rem; margin-bottom: 0.5rem;
        }
        .footer-brand p { font-size: 0.8rem; line-height: 1.7; }
        .footer-col h4 { color: white; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 0.8rem; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 5px; }
        .footer-col ul li a { font-size: 0.8rem; color: rgba(255,255,255,0.65); transition: color 0.2s; }
        .footer-col ul li a:hover { color: white; }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center; padding: 1rem 2rem;
            font-size: 0.75rem; color: rgba(255,255,255,0.4);
        }
        .footer-bottom a { color: rgba(255,255,255,0.6); }
        .footer-bottom a:hover { color: var(--bps-orange-light); }

        /* Color tags */
        .tag { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 0.68rem; font-weight: 600; }
        .tag-blue { background: rgba(26,95,168,0.12); color: var(--bps-blue); }
        .tag-green { background: rgba(46,139,62,0.12); color: var(--bps-green); }
        .tag-orange { background: rgba(224,123,26,0.12); color: var(--bps-orange); }

        /* ─── LIGHTBOX ─── */
        .lightbox { display: none; position: fixed; inset: 0; z-index: 999; background: rgba(0,0,0,0.92); backdrop-filter: blur(20px); align-items: center; justify-content: center; padding: 1rem; }
        .lightbox.open { display: flex; animation: fadeIn 0.2s; }
        .lightbox-inner { max-width: 900px; width: 100%; position: relative; }
        .lightbox img { width: 100%; border-radius: 12px; max-height: 80vh; object-fit: contain; box-shadow: 0 30px 80px rgba(0,0,0,0.5); }
        .lb-close { position: absolute; top: -44px; right: 0; color: white; font-size: 1.5rem; cursor: pointer; opacity: 0.7; transition: opacity 0.2s; background: none; border: none; font-family: inherit; }
        .lb-close:hover { opacity: 1; }
        .lb-meta { margin-top: 1rem; text-align: center; }
        .lb-meta h3 { font-size: 1rem; font-weight: 700; color: white; }
        .lb-meta p { font-size: 0.82rem; color: rgba(255,255,255,0.6); margin-top: 4px; }
        .lb-meta .lb-date { color: var(--bps-orange-light); font-size: 0.75rem; margin-top: 4px; }

        /* ─── ANIMATIONS ─── */
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-up { animation: slideUp 0.6s ease both; }

        /* ─── MOBILE RESPONSIVENESS ─── */
        .mobile-toggle, .mobile-close { display: none; }

        /* ─── MOBILE RESPONSIVENESS ─── */
        @media (max-width: 768px) {
            .header-inner { flex-direction: row; justify-content: space-between; align-items: center; padding: 0 1rem; }
            .mobile-toggle { 
                display: block; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4);
                color: white; font-size: 1.5rem; padding: 0.2rem 0.6rem; border-radius: 8px; cursor: pointer;
            }
            .mobile-close {
                display: block; position: absolute; top: 1.5rem; right: 1.5rem; background: none; border: none;
                color: white; font-size: 2rem; cursor: pointer;
            }
            .nav-wrapper {
                position: fixed; top: 0; right: -100%; width: 280px; height: 100vh;
                background: rgba(0,0,0,0.9); backdrop-filter: blur(20px);
                flex-direction: column; align-items: flex-start; justify-content: center;
                transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1); z-index: 9999;
                padding: 3rem 2rem; border-left: 1px solid rgba(255,255,255,0.1);
                display: flex; gap: 2rem;
            }
            .nav-wrapper.active { right: 0; }
            .nav-wrapper .main-nav {
                flex-direction: column; width: 100%; background: transparent; border: none; box-shadow: none;
                align-items: flex-start; padding: 0; gap: 1.5rem;
            }
            .nav-wrapper .nav-link { font-size: 1.2rem; padding: 0; }
            .header-right { width: 100%; justify-content: flex-start; }
            .header-logo img { height: 40px; }
            .hero-slider { min-height: 650px; height: 100vh; }
            .hs-text h1 { font-size: 1.5rem; }
        }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--bps-blue); border-radius: 3px; }
    </style>
</head>
<body>

<!-- SITE HEADER -->
<header class="site-header">
    <div class="header-inner">
        <div class="header-logo">
            <img src="{{ asset('storage/logo/logo-bps-kolut.png') }}" alt="Logo BPS Kolaka Utara">
        </div>
        <button class="mobile-toggle" onclick="toggleMobileNav()"><i class="bi bi-list"></i></button>

        <div class="nav-wrapper" id="navWrapper">
            <button class="mobile-close" onclick="toggleMobileNav()"><i class="bi bi-x-lg"></i></button>
            <nav class="main-nav">
                <a href="{{ route('home') }}" class="nav-link active" onclick="toggleMobileNav()">Beranda</a>
                <a href="#gallery" class="nav-link" onclick="toggleMobileNav()">Galeri</a>
                <a href="#activities" class="nav-link" onclick="toggleMobileNav()">Aktivitas</a>
                <a href="#interns" class="nav-link" onclick="toggleMobileNav()">Tim Magang</a>
            </nav>

            <div class="header-right">
                @auth
                    @if(auth()->user()->role->name === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="hbtn hbtn-solid"><i class="bi bi-grid-fill"></i> Dashboard Admin</a>
                    @else
                        <a href="{{ route('intern.dashboard') }}" class="hbtn hbtn-solid"><i class="bi bi-grid-fill"></i> Dashboard Saya</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="hbtn hbtn-solid"><i class="bi bi-lock-fill"></i> Masuk Portal</a>
                @endauth
            </div>
        </div>
    </div>
</header>

<!-- DYNAMIC INTERN SLIDER -->
<section class="hero-slider" id="heroSlider">
    @php
        // Generate a set of vibrant elegant colors for the interns
        $colors = ['#e07b1a', '#1a5fa8', '#2e8b3e', '#8b2e59', '#2b7fd4', '#d47f2b'];
    @endphp

    @if($interns->count() > 0)
        <div class="hs-content">
            <!-- Slides -->
            @foreach($interns as $i => $intern)
            @php
                $internColor = !empty($intern->gallery_color) 
                    ? $intern->gallery_color 
                    : $colors[$i % count($colors)];
            @endphp
            <div class="hs-img {{ $i === 0 ? 'active' : ($i === 1 ? 'next' : '') }}" data-color="{{ $internColor }}">
                <!-- Giant Background Text -->
                <div class="hs-giant-text">{{ strtoupper(explode(' ', $intern->name)[0]) }}</div>
                
                <!-- Floating Image -->
                <div class="hs-photo" onclick="hsGo({{ $i }})">
                    @if(!empty($intern->avatar))
                        <img src="{{ asset($intern->avatar) }}" alt="{{ $intern->name }}">
                    @else
                        <div style="width:100%; height:100%; background: linear-gradient(135deg, {{ $internColor }}, #222); display:flex; align-items:center; justify-content:center; color:white; font-size:5rem; font-family:'Fredoka One',cursive;">
                            {{ strtoupper(substr($intern->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- Text Below Photo -->
                <div class="hs-text">
                    <div class="hs-text-info" style="text-align: center;">
                        <h1>{{ $intern->division ?? 'BPS Kolaka Utara' }}</h1>
                        <p>Mahasiswa dari {{ $intern->university ?? 'Universitas' }}. Bergabung dalam program magang untuk belajar dan berkontribusi langsung.</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Navigation Arrows -->
        <div class="hs-nav-wrap">
            <button class="hs-nav-btn" onclick="hsNext(-1)"><i class="bi bi-chevron-left"></i></button>
            <button class="hs-nav-btn" onclick="hsNext(1)"><i class="bi bi-chevron-right"></i></button>
        </div>

    @else
        <div class="hs-content" style="justify-content:center; text-align:center;">
            <div style="color:white;">
                <i class="bi bi-people" style="font-size:4rem; opacity:0.4;"></i>
                <h2 style="font-family:'Fredoka One',cursive; font-size:2rem; margin-top:1rem;">Tim Magang BPS</h2>
                <p>Belum ada data mahasiswa magang saat ini.</p>
            </div>
        </div>
    @endif
</section>

<!-- STANDALONE GALLERY SECTION -->
<section class="standalone-gallery" id="gallery">

    <div class="gallery-inner">
        <h2 style="text-align:center; font-family:'Fredoka One',cursive; margin-bottom:1.5rem; font-size:2rem; letter-spacing:0.02em; color: white;">Galeri Dokumentasi</h2>
        
        <div class="gallery-filters">
            <button class="g-filter active">Semua Aktivitas</button>
        </div>

        <!-- Dummy Data for Preview -->
        <div class="gallery-grid">
            @php
                $dummies = [
                    ['src' => 'https://picsum.photos/400/600?random=1', 'name' => 'Budi Santoso', 'date' => '12 Okt 2023'],
                    ['src' => 'https://picsum.photos/400/300?random=2', 'name' => 'Siti Aminah', 'date' => '14 Okt 2023'],
                    ['src' => 'https://picsum.photos/400/500?random=3', 'name' => 'Agus Setiawan', 'date' => '15 Okt 2023'],
                    ['src' => 'https://picsum.photos/400/400?random=4', 'name' => 'Dewi Lestari', 'date' => '18 Okt 2023'],
                    ['src' => 'https://picsum.photos/400/700?random=5', 'name' => 'Rina Melati', 'date' => '20 Okt 2023'],
                    ['src' => 'https://picsum.photos/400/350?random=6', 'name' => 'Anton Saputra', 'date' => '22 Okt 2023'],
                    ['src' => 'https://picsum.photos/400/450?random=7', 'name' => 'Fajar Nugroho', 'date' => '24 Okt 2023'],
                    ['src' => 'https://picsum.photos/400/550?random=8', 'name' => 'Dinda Kirana', 'date' => '26 Okt 2023'],
                    ['src' => 'https://picsum.photos/400/320?random=9', 'name' => 'Hendra Putra', 'date' => '28 Okt 2023'],
                    ['src' => 'https://picsum.photos/400/620?random=10', 'name' => 'Sinta Nur', 'date' => '30 Okt 2023'],
                    ['src' => 'https://picsum.photos/400/480?random=11', 'name' => 'Rizky Fadillah', 'date' => '02 Nov 2023'],
                    ['src' => 'https://picsum.photos/400/380?random=12', 'name' => 'Yuni Arta', 'date' => '05 Nov 2023']
                ];
            @endphp
            @foreach($dummies as $d)
            <div class="gallery-thumb"
                 data-src="{{ $d['src'] }}"
                 data-name="{{ $d['name'] }}"
                 data-title="Contoh Dokumentasi"
                 data-date="{{ $d['date'] }}"
                 onclick="openLightbox(this)">
                <img src="{{ $d['src'] }}" alt="{{ $d['name'] }}" loading="lazy">
                <div class="th-overlay">
                    <span>{{ $d['name'] }}<br>{{ $d['date'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- MAIN 2-COLUMN CONTENT -->
<div class="main-content">

    <!-- COL 2: Activities -->
    <div class="activity-col animate-up" id="activities" style="animation-delay:0.1s">
        <h2 class="col-title">Aktivitas Terbaru</h2>
        @if($recentActivities->count() > 0)
        <div class="activity-list">
            @foreach($recentActivities as $act)
            <div class="activity-item">
                <div class="act-date">
                    <span class="day">{{ \Carbon\Carbon::parse($act->date)->format('d') }}</span>
                    <span>{{ \Carbon\Carbon::parse($act->date)->format('M') }}</span>
                </div>
                <div class="act-body">
                    <h4>{{ $act->title }}</h4>
                    <p>{{ $act->description }}</p>
                    <span class="act-intern"><i class="bi bi-person-fill"></i> {{ $act->intern_name }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="act-empty">Belum ada aktivitas yang tercatat.</p>
        @endif
    </div>

    <!-- COL 3: Info & Interns -->
    <div class="info-col animate-up" id="interns" style="animation-delay:0.2s">

        <!-- Intern List -->
        @if($interns->count() > 0)
        <h2 class="col-title" style="font-size:1.2rem; margin-bottom:1rem;">Tim Magang</h2>
        <div class="intern-mini-list">
            @foreach($interns->take(5) as $intern)
            <div class="intern-mini">
                <div class="im-av">
                    @if($intern->avatar)
                        <img src="{{ asset($intern->avatar) }}" alt="{{ $intern->name }}">
                    @else
                        {{ strtoupper(substr($intern->name, 0, 1)) }}
                    @endif
                </div>
                <div class="im-info">
                    <div class="im-name">{{ $intern->name }}</div>
                    <div class="im-div">
                        {{ $intern->division ?? 'BPS Kolaka Utara' }}
                        @if($intern->university)
                            &middot; {{ $intern->university }}
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            @if($interns->count() > 5)
            <div style="text-align:center; margin-top:4px;">
                <span class="tag tag-blue">+{{ $interns->count() - 5 }} lainnya</span>
            </div>
            @endif
        </div>
        @endif

        <!-- Contact Info -->
        <div class="info-box" style="margin-top:1.5rem;">
            <div class="info-box-head orange"><i class="bi bi-telephone-fill"></i> Kontak</div>
            <div class="info-box-body">
                <ul class="info-list">
                    <li>Kab. Kolaka Utara, Sultra</li>
                    <li>bps7413@bps.go.id</li>
                    <li>https://kolutkab.bps.go.id/</li>
                </ul>
            </div>
        </div>
    </div>

    </div>
</div>

<!-- FOOTER -->
<div class="section-divider"></div>
<footer>
    <div class="footer-main">
        <div class="footer-brand">
            <h3>BPS Kabupaten Kolaka Utara</h3>
            <p>Badan Pusat Statistik Kabupaten Kolaka Utara adalah instansi pemerintah yang bertanggung jawab dalam penyelenggaraan kegiatan statistik resmi di wilayah Kabupaten Kolaka Utara, Sulawesi Tenggara.</p>
        </div>
        <div class="footer-col">
            <h4>Portal Magang</h4>
            <ul>
                <li><a href="{{ route('home') }}">Beranda Galeri</a></li>
                <li><a href="{{ route('login') }}">Login Mahasiswa</a></li>
                <li><a href="{{ route('login') }}">Login Admin</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Tautan BPS</h4>
            <ul>
                <li><a href="https://bps.go.id" target="_blank">BPS Pusat</a></li>
                <li><a href="https://sultra.bps.go.id" target="_blank">BPS Sultra</a></li>
                <li><a href="https://kolakautara.bps.go.id" target="_blank">BPS Kolaka Utara</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; {{ date('Y') }} BPS Kabupaten Kolaka Utara &middot;
        Sistem Informasi Magang &middot;
        <a href="{{ route('login') }}">Masuk Portal</a>
    </div>
</footer>

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox" onclick="if(event.target===this)closeLightbox()">
    <div class="lightbox-inner">
        <button class="lb-close" onclick="closeLightbox()"><i class="bi bi-x-lg"></i></button>
        <img id="lightboxImg" src="" alt="">
        <div class="lb-meta">
            <h3 id="lightboxName"></h3>
            <p id="lightboxTitle"></p>
            <p id="lightboxDate" class="lb-date"></p>
        </div>
    </div>
</div>

<script>
(function() {
    // ── Intern Slider ──
    var hsImgs = document.querySelectorAll('.hs-img');
    var hsDots = document.querySelectorAll('.hs-dot');
    var heroSlider = document.getElementById('heroSlider');
    var currentSlide = 0;
    var totalSlides = hsImgs.length;

    function hsGo(n) {
        if(totalSlides === 0) return;
        
        hsImgs[currentSlide].classList.remove('active');
        hsImgs[currentSlide].classList.remove('prev');
        hsImgs[currentSlide].classList.remove('next');
        if(hsDots[currentSlide]) hsDots[currentSlide].classList.remove('active');
        
        // Before changing, set the old one as prev
        hsImgs[currentSlide].classList.add('prev');
        
        currentSlide = (n + totalSlides) % totalSlides;
        
        // Clean all classes from others, and ensure new currentSlide doesn't have next/prev
        hsImgs.forEach((img, i) => {
            if(i !== currentSlide) {
                img.classList.remove('active');
                if(i !== (currentSlide - 1 + totalSlides) % totalSlides) img.classList.remove('prev');
                img.classList.remove('next');
            } else {
                img.classList.remove('next');
                img.classList.remove('prev');
            }
        });

        // Set active
        hsImgs[currentSlide].classList.add('active');
        if(hsDots[currentSlide]) hsDots[currentSlide].classList.add('active');
        
        // Set next
        let nextIndex = (currentSlide + 1) % totalSlides;
        if(hsImgs[nextIndex]) hsImgs[nextIndex].classList.add('next');
        
        // Change background color of body
        document.body.style.backgroundColor = hsImgs[currentSlide].getAttribute('data-color') || '#e07b1a';
    }

    function hsNext(dir) { hsGo(currentSlide + dir); }
    window.hsGo = hsGo;
    window.hsNext = hsNext;

    // Auto-advance
    var hsTimer = setInterval(function() { hsNext(1); }, 3000);
    if (heroSlider) {
        heroSlider.addEventListener('mouseenter', function() { clearInterval(hsTimer); });
        heroSlider.addEventListener('mouseleave', function() { hsTimer = setInterval(function() { hsNext(1); }, 3000); });
        
        if (totalSlides > 0) {
            document.body.style.backgroundColor = hsImgs[0].getAttribute('data-color') || '#e07b1a';
        }
    }

    // ── Lightbox ──
    window.openLightbox = function(el) {
        document.getElementById('lightboxImg').src = el.dataset.src;
        document.getElementById('lightboxName').textContent = el.dataset.name;
        document.getElementById('lightboxTitle').textContent = el.dataset.title;
        document.getElementById('lightboxDate').textContent = el.dataset.date;
        document.getElementById('lightbox').classList.add('open');
        document.body.style.overflow = 'hidden';
    };
    window.closeLightbox = function() {
        document.getElementById('lightbox').classList.remove('open');
        document.body.style.overflow = '';
    };
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeLightbox(); });

    // ── Mobile Nav Toggle ──
    window.toggleMobileNav = function() {
        document.getElementById('navWrapper').classList.toggle('active');
    };

    // ── Smooth nav scroll ──
    document.querySelectorAll('.nav-link[href^="#"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // ── Cute Gallery Pop Animation ──
    if ('IntersectionObserver' in window) {
        const galObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const thumbs = entry.target.querySelectorAll('.gallery-thumb');
                    thumbs.forEach((thumb, index) => {
                        setTimeout(() => {
                            thumb.classList.remove('is-hidden');
                            thumb.classList.add('pop-in');
                        }, index * 80); // 80ms stagger delay
                    });
                    galObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        const galSection = document.getElementById('gallery');
        if (galSection) {
            const thumbs = galSection.querySelectorAll('.gallery-thumb');
            thumbs.forEach(t => t.classList.add('is-hidden'));
            galObserver.observe(galSection);
        }
    }
})();
</script>
</body>
</html>