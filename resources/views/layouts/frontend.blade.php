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
</head>
<body>
    <div class="shape circle" style="width:130px;height:130px;top:15%;left:12%;"></div>
    <div class="shape square" style="width:110px;height:110px;top:30%;right:15%;"></div>
    <div class="shape triangle" style="bottom:18%;left:20%;"></div>
    <div class="shape circle" style="width:90px;height:90px;bottom:15%;right:25%;"></div>

    @yield('navbar')

    <main>
        @yield('content')
    </main>

    @yield('footer-override')

    @unless(View::hasSection('footer-override'))
    <footer>
        <div>© 2025 SIMAS-FTMM. All Rights Reserved.</div>
        <div class="links">
            <span>📷 <a href="https://instagram.com/ftmm.unair" target="_blank">@ftmm.unair</a></span>
            <span>✉️ <a href="mailto:info@ftmm.unair.ac.id">info@ftmm.unair.ac.id</a></span>
            <span>📞 <a href="https://wa.me/62881036000830" target="_blank">+62 881-0360-00830 (Helpdesk)</a></span>
        </div>
    </footer>
    @endunless

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    @stack('scripts')
</body>
</html>