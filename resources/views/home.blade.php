<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen SDM JTI</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Body and Background Styling */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #FFC107, #FF9800, #FFC107);
            background-size: 400% 400%; /* Ukuran animasi background */
            animation: gradientBG 15s ease infinite; /* Efek animasi */
            color: #000;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }
    
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    
        /* Hover Effect Styling */
        #hover-effect {
            position: fixed;
            top: 0;
            left: 0;
            width: 150px;
            height: 150px;
            pointer-events: none;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 80%);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            mix-blend-mode: overlay;
            z-index: 9999;
        }
    
        /* Header Styling */
        header {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #000000;
            padding: 15px;
            z-index: 1000;
            display: flex;
            justify-content: center;
        }
    
        header nav a {
            color: #FFC107; /* Adjusted color */
            text-decoration: none;
            font-size: 1em;
            padding: 10px 15px;
            border-radius: 25px;
            transition: all 0.3s;
        }
    
        header nav a:hover {
            background: #FFC107; /* Adjusted hover color */
            color: #000000;
        }
    
        /* Hero Section Styling */
        .hero {
            margin-top: 100px;
            width: 100%;
            padding: 50px 20px;
        }
    
        .hero h1 {
            font-size: 3.5em;
            margin-bottom: 10px;
            color: #000;
        }
    
        .hero p {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #333;
            margin-bottom: 50px;
        }
    
        .hero .btn-primary {
            background: #000;
            border: none;
            padding: 15px 40px;
            color: #FFC107; /* Adjusted color */
            font-size: 1.2em;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            transition: transform 0.3s, background 0.3s;
        }
    
        .hero .btn-primary:hover {
            background: #000;
            color: #FFC107; /* Adjusted hover color */
            transform: scale(1.1);
            opacity: 50%;
        }
    
        /* Features Section */
        .features {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
            gap: 30px;
            padding: 50px 20px;
        }
    
        .feature-item {
            background: #FFF;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            max-width: 250px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s;
        }
    
        .feature-item:hover {
            transform: scale(1.05);
        }
    
        .feature-item i {
            font-size: 3em;
            margin-bottom: 10px;
            color: #FFC107; /* Adjusted color */
        }
    
        .feature-item p {
            font-size: 1.2em;
            margin-bottom: 5px;
            color: #000;
        }
    
/* Mobile Access Section */
.mobile-access {
    padding: 50px 20px;
    background: #FFF;
    border-radius: 20px;
    margin: 20px auto;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Kontainer untuk gambar agar dua gambar tetap berdampingan */
.mobile-access-images {
    display: flex;
    justify-content: center; /* Menjaga gambar agar berada di tengah */
    gap: 20px; /* Memberikan jarak antara gambar */
    margin-bottom: 20px; /* Memberikan jarak antara gambar dan tombol */
}

/* Gaya untuk gambar */
.mobile-access img {
    width: 200px; /* Ukuran gambar */
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
}

/* Tombol download */
.download-button {
    background: linear-gradient(135deg, #FFC107, #FF9800); /* Gradasi warna tombol */
    padding: 15px 40px;
    font-size: 1.2em;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: transform 0.3s, background 0.3s;
    color: #000;
    font-weight: bold;
}

/* Efek hover pada tombol */
.download-button:hover {
    background: #000;
    color: #FFC107; /* Mengubah warna teks saat hover */
    transform: scale(1.1); /* Membuat tombol sedikit membesar */
}
    
        /* Footer Section */
        footer {
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 20px;
            background: #000;
            color: #FFC107; /* Adjusted color */
            text-align: center;
            width: 100%;
            z-index: 1000;
        }
    
        /* Responsif */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5em;
            }
    
            .features {
                flex-direction: column;
            }
        }
    
        /* Desktop Styling */
        @media (min-width: 1024px) {
            .hero h1 {
                font-size: 4.5em; /* Larger font size for desktop */
            }
    
            .hero p {
                font-size: 1.5em; /* Larger font size for desktop */
            }
    
            .feature-item {
                max-width: 300px; /* Increase the size of the feature items */
            }
    
            .mobile-access img {
                width: 250px; /* Larger images for desktop */
            }
        }
    </style>    
    
</head>
<body>

<header>
    <nav>
        <a href="#">Home</a>
        <a href="#features">Features</a>
        <a href="#download">Download</a>
    </nav>
</header>

<div id="hover-effect"></div>

<div class="hero">
    <h1>Sistem Informasi Manajemen SDM JTI</h1>
    <p>Memudahkan segala bentuk integrasi pembagian tugas dan tanggung jawab dosen</p>
    <a href="{{ route('login') }}" class="btn-primary">Get Started</a>
</div>

<div id="features" class="features">
    <div class="feature-item">
        <i class="fas fa-tasks"></i>
        <p>Ajukan Tugas</p>
        <small>Pilih tugas yang Anda inginkan</small>
    </div>
    <div class="feature-item">
        <i class="fas fa-eye"></i>
        <p>Lihat Kegiatan</p>
        <small>Lihat seluruh kegiatan yang ada di Jurusan Teknologi Informasi</small>
    </div>
    <div class="feature-item">
        <i class="fas fa-chart-line"></i>
        <p>Pantau Beban Kerja</p>
        <small>Pantau beban kerja setiap individu</small>
    </div>
</div>

<div class="mobile-access">
    <h2>Akses Cepat Menggunakan Aplikasi Mobile</h2>
    <p>Aplikasi yang mudah digunakan oleh Pimpinan, Dosen, dan Admin dalam mengelola kegiatan dan beban kerja di Jurusan Teknologi Informasi</p>
    
    <!-- Kontainer untuk gambar -->
    <div class="mobile-access-images">
        <img src="{{ asset('image/screen1.png') }}" alt="Image 1">
        <img src="{{ asset('image/screen2.png') }}" alt="Image 2">
    </div>
    
    <!-- Tombol download -->
    <button class="download-button">Download</button>
</div>

<footer>
    <p>2024 &copy; Sistem Informasi Manajemen SDM JTI</p>
</footer>

<script>
    // Hover Effect Script
    const hoverEffect = document.getElementById('hover-effect');
    document.addEventListener('mousemove', (e) => {
        hoverEffect.style.top = `${e.clientY}px`;
        hoverEffect.style.left = `${e.clientX}px`;
    });
</script>

</body>
</html>
