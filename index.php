<?php
// Menyambungkan ke database menggunakan file konfigurasi
include_once("config.php");

// Mengambil semua data dari database (diurutkan dari yang terbaru)
$result = mysqli_query($mysqli, "SELECT * FROM alat ORDER BY id DESC");

// Menghitung jumlah total alat untuk widget statistik
$total_alat = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Homepage - Data Alat Elektromedis</title>
    <style>
        body {
            /* Menggunakan gambar background bertema teknologi medis / rumah sakit */
            background-image: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), 
                              url('https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?q=80&w=1920&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px;
            color: #333;
        }

        /* Pembungkus Header Utama (Profil + Widget Kanan) */
        .header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 25px;
        }

        h2 {
            margin-bottom: 5px;
            color: #006666;
        }

        p {
            margin-top: 0;
            color: #555;
            font-weight: 500;
        }

        /* Desain Tombol Menu */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-tambah {
            background-color: #008080;
            color: white;
        }

        .btn-tambah:hover {
            background-color: #005a5a;
        }

        .btn-cetak {
            background-color: #007bff;
            color: white;
        }

        .btn-cetak:hover {
            background-color: #0056b3;
        }

        /* Desain Tabel Modern Transparan */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.9); /* Membuat tabel semi transparan */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
        }

        th {
            background-color: #008080;
            color: white;
            padding: 12px 15px;
            text-align: left;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: rgba(0, 128, 128, 0.05); /* Efek sorot saat mouse lewat */
        }

        /* Tombol Aksi (Edit & Delete) */
        .action-link {
            text-decoration: none;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
        }

        .edit {
            color: #ff9800;
            background-color: rgba(255, 152, 0, 0.1);
        }

        .edit:hover {
            background-color: rgba(255, 152, 0, 0.2);
        }

        .delete {
            color: #f44336;
            background-color: rgba(244, 67, 54, 0.1);
            margin-left: 5px;
        }

        .delete:hover {
            background-color: rgba(244, 67, 54, 0.2);
        }

        /* Kontainer Profil (Sisi Kiri) */
        .profile-container {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .profile-img {
            width: 80px; 
            height: 80px; 
            border-radius: 50%; 
            object-fit: cover; 
            margin-right: 20px;
            border: 3px solid #008080; 
        }

        .profile-text h2 {
            margin: 0;
        }
        
        .profile-text p {
            margin: 5px 0 0 0;
        }

        /* Panel Sisi Kanan (Jam + Statistik) */
        .right-widgets {
            display: flex;
            gap: 15px;
        }

        .widget-box {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            text-align: right;
            min-width: 150px;
            border-right: 4px solid #008080;
        }

        .widget-box.time-box {
            border-right: 4px solid #007bff;
        }

        .widget-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #777;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .widget-value {
            font-size: 20px;
            font-weight: bold;
            color: #222;
        }

        .widget-sub {
            font-size: 11px;
            color: #666;
            margin-top: 2px;
        }
        
        #live-clock {
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
</head>
<body>

    <div class="header-wrapper">
        
        <div class="profile-container">
            <img src="foto.jpeg" alt="Foto Haris Gunawan" class="profile-img">
            <div class="profile-text">
                <h2>Data Alat Elektromedis</h2>
                <p>Oleh: Haris Gunawan (2202505038)</p>
            </div>
        </div>

        <div class="right-widgets">
            <div class="widget-box">
                <div class="widget-label">Total Inventaris</div>
                <div class="widget-value"><?php echo $total_alat; ?></div>
                <div class="widget-sub">Unit Alat Medis</div>
            </div>

            <div class="widget-box time-box">
                <div class="widget-label">Waktu Sistem SIM RS</div>
                <div id="live-clock" class="widget-value">00:00:00</div>
                <div id="live-date" class="widget-sub">Memuat...</div>
            </div>
        </div>

    </div>
    
    <a href="add.php" class="btn btn-tambah">Tambah Alat Baru</a>
    <a href="print.php" target="_blank" class="btn btn-cetak">Cetak PDF</a>
    <br><br>

    <table>
        <thead>
            <tr>
                <th>Nama Alat</th>
                <th>Tahun</th>
                <th>Merek</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php  
            while($user_data = mysqli_fetch_array($result)) {         
                echo "<tr>";
                echo "<td>".$user_data['nama_alat']."</td>";
                echo "<td>".$user_data['tahun']."</td>";
                echo "<td>".$user_data['merek']."</td>";    
                echo "<td>".$user_data['lokasi']."</td>";    
                echo "<td>
                        <a href='edit.php?id=$user_data[id]' class='action-link edit'>Edit</a> | 
                        <a href='delete.php?id=$user_data[id]' class='action-link delete' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Delete</a>
                      </td>";
                echo "</tr>";        
            }
            ?>
        </tbody>
    </table>

    <script>
        function updateClock() {
            const now = new Date();
            
            // Format Jam
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('live-clock').textContent = `${hours}:${minutes}:${seconds}`;
            
            // Format Tanggal Bahasa Indonesia
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('live-date').textContent = now.toLocaleDateString('id-ID', options);
        }

        // Jalankan waktu secara dinamis per 1 detik
        setInterval(updateClock, 1000);
        updateClock();
    </script>

</body>
</html>