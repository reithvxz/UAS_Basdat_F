<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAS-FTMM | Selamat Datang</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
  <div class="intro" id="intro">
    <div class="bg-shape circle" style="width:140px;height:140px;top:15%;left:10%;"></div>
    <div class="bg-shape square" style="width:110px;height:110px;top:25%;right:15%;"></div>
    <div class="envelope" id="envelope"></div>
    <h1>SIMAS-FTMM</h1>
    <div class="loading-ring" id="loadingRing"></div>
  </div>

  <div class="landing" id="landing">
    <div class="shape circle" style="width:140px;height:140px;top:15%;left:10%;"></div>
    <div class="particle" style="left:30%;bottom:0;"></div>
    <div class="particle" style="left:60%;bottom:0;"></div>

    <nav>
      <div class="logo">SIMAS-FTMM</div>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#features">Fitur</a></li>
        <li><a href="{{ route('login') }}">Login</a></li>
      </ul>
    </nav>

    <div class="hero">
      <div class="text">
        <h1 class="title">SIMAS-FTMM</h1>
        <p class="fullname">Sistem Manajemen Persuratan<br>Fakultas Teknologi Maju dan Multidisiplin</p>
        <p class="subtitle">Digitalisasi alur persuratan akademik di FTMM, memberikan kemudahan dalam proses pengajuan hingga persetujuan secara online.</p>
        <a href="{{ route('login') }}" class="btn-login">Log In</a>
      </div>
      <div class="illustration">
        <div class="big-envelope"></div>
      </div>
    </div>

    <section class="features" id="features">
      <h2>Fitur Utama</h2>
      <div class="features-grid">
        <div class="feature">
          <h3>Pengajuan Surat Online</h3>
          <p>Hemat waktu dan tenaga Anda dengan mengajukan berbagai jenis surat keperluan mahasiswa dan organisasi secara online, kapan saja dan di mana saja.</p>
        </div>
        <div class="feature">
          <h3>Tracking Status</h3>
          <p>Dapatkan kepastian dan pantau setiap tahapan persetujuan surat Anda secara transparan, mulai dari BEM hingga ke pimpinan fakultas.</p>
        </div>
      </div>
    </section>

    <footer>© 2025 SIMAS-FTMM. All Rights Reserved.</footer>
  </div>

  <script>
    const envelope = document.getElementById("envelope");
    const intro = document.getElementById("intro");
    const loadingRing = document.getElementById("loadingRing");
    envelope.addEventListener("click", () => {
        intro.classList.add("open");
        loadingRing.style.display = "block";
        setTimeout(() => { intro.classList.add("bye"); }, 800);
        setTimeout(() => { document.body.classList.add("show-landing"); loadingRing.style.display = "none"; }, 1200);
    });
  </script>
</body>
</html>