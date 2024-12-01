<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen SDM JTI</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .hero {
            background-image: url('https://pbs.twimg.com/media/C55fefGVMAAtu4b.jpg'); /* Replace with actual path */
            background-size: cover;
            text-align: center;
            padding: 100px 0;
        }
        .hero h1 {
            font-size: 3em;
            font-weight: bold;
            color: #FFD700;
        }
        .hero p {
            font-size: 1.2em;
            color: white;
        }
        .hero button {
            background-color: #FFD700;
            border: none;
            padding: 10px 20px;
            color: #333;
            border-radius: 25px; /* Makes the button rounded */
            font-size: 1em;
            cursor: pointer;
        }
        .features, .mobile-access {
            padding: 50px 20px;
            text-align: center;
        }
        .features h2, .mobile-access h2 {
            font-size: 2em;
            margin-bottom: 20px;
        }
        .features .feature-item, .mobile-access img {
            display: inline-block;
            margin: 20px;
        }
        .features .feature-item i {
            font-size: 2em;
            color: #FFD700;
        }
        .download-button {
            background-color: #FFC107; /* Yellow color */
            color: #000; /* Black text color */
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 25px; /* Makes the button rounded */
            cursor: pointer;
            text-align: center;
}

.download-button:hover {
    background-color: #e0a800; /* Slightly darker yellow on hover */
}

.mobile-access {
    text-align: center;
}

.mobile-access img {
    display: inline-block;
    margin: 10px; /* Add spacing between images */
}

.footer {
    background-color: #FFC107; /* Yellow color */
    color: #000; /* Black text color */
    text-align: center;
    padding: 2px 0; /* Adds some vertical padding */
    font-size: 14px;
    width: 100%;
    bottom: 0;
    left: 0;
}

    </style>
</head>
<body>

<div class="hero">
    <h1>Sistem Informasi Manajemen SDM JTI</h1>
    <p>Memudahkan segala bentuk integrasi pembagian tugas dan tanggung jawab mahasiswa</p>
    <button onclick="window.location.href='/login'">Login</button>
</div>

<div class="features">
    <h2>Fitur</h2>
    <p>Manajemen Tugas dan Monitoring Beban Kerja</p>
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

<div class="mobile-access" style="text-align: center;">
    <h2>Akses Cepat Menggunakan Aplikasi Mobile</h2>
    <p>Aplikasi yang mudah digunakan oleh Pimpinan, Dosen, dan Admin dalam mengelola kegiatan dan beban kerja di Jurusan Teknologi Informasi</p>
    
    <div class="images">
        <img src="{{ asset('images/phone1.png') }}" alt="App Screen 1">
        <img src="{{ asset('images/phone2.png') }}" alt="App Screen 2">
    </div>

    <div>
        <button class="download-button">Download</button>
    </div>
</div>


</body>

<footer class="footer">
    <p>© Kelompok 3 SIB 3C</p>
</footer>

</html>
