<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMAS-FTMM')</title>
    
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
    
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .content-wrapper {
            flex: 1; 
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        footer {
            margin-top: auto;
            width: 100%;
            padding: 20px 0;
            text-align: center;
            background-color: transparent;
            color: #ccc;
            font-size: 0.9rem;
            position: relative;
            z-index: 10;
        }
        main { width: 100%; }

        /* --- TAMBAHAN: EFEK HOVER UNTUK TOMBOL NAVBAR --- */
        nav a {
            transition: all 0.3s ease;
        }
        nav a:hover {
            background-color: #ffce00 !important; /* Warna Kuning */
            color: #0d0c3b !important; /* Teks Biru Gelap */
            border-color: #ffce00 !important; /* Border Kuning */
        }
        
        /* --- STYLE UNTUK STATUS --- */
        .status {
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }
        .status.approved { background-color: #00c853; color: #fff; }
        .status.rejected { background-color: #d50000; color: #fff; }
        .status.pending { background-color: #ffce00; color: #000; }
        .status.cancelled { background-color: #6c757d; color: #fff; } /* Dibatalkan */
    </style>
</head>
<body>
    <div class="shape circle" style="width:130px;height:130px;top:15%;left:12%;"></div>
    <div class="shape square" style="width:110px;height:110px;top:30%;right:15%;"></div>
    <div class="shape triangle" style="bottom:18%;left:20%;"></div>
    <div class="shape circle" style="width:90px;height:90px;bottom:15%;right:25%;"></div>

    @yield('navbar')

    <div class="content-wrapper">
        <main>
            @yield('content')
        </main>
    </div>

    @yield('footer-override')

    @unless(View::hasSection('footer-override'))
    <footer>
        <div>© 2025 SIMAS-FTMM. All Rights Reserved.</div>
        <div class="links" style="margin-top: 5px;">
            <span style="margin: 0 10px;">📷 <a href="https://www.instagram.com/ftmmunair" target="_blank" style="color:#ccc; text-decoration:none;">@ftmmunair</a></span>
            <span style="margin: 0 10px;">✉️ <a href="https://ftmm.unair.ac.id/" target="_blank" style="color:#ccc; text-decoration:none;">ftmm.unair.ac.id</a></span>
            <span style="margin: 0 10px;">📞 <a href="https://wa.me/62881036000830" target="_blank" style="color:#ccc; text-decoration:none;">+62 881-0360-00830 (Helpdesk)</a></span>
        </div>
    </footer>
    @endunless

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    @stack('scripts')
</body>
</html>