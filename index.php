<?php
// Menyambungkan ke database menggunakan file konfigurasi
include_once("config.php");

// 1. Logika Ambil Parameter Pencarian & Filter dari URL (Metode GET)
$search = isset($_GET['search']) ? mysqli_real_escape_string($mysqli, $_GET['search']) : '';
$filter_lokasi = isset($_GET['filter_lokasi']) ? mysqli_real_escape_string($mysqli, $_GET['filter_lokasi']) : '';
$filter_kondisi = isset($_GET['filter_kondisi']) ? mysqli_real_escape_string($mysqli, $_GET['filter_kondisi']) : '';

// 2. Menyusun Query Dasar Dinamis (WHERE 1=1)
$query = "SELECT * FROM alat WHERE 1=1";

// Jika user mengisi kolom kata kunci pencarian (Nama Alat atau Merek)
if ($search != '') {
    $query .= " AND (nama_alat LIKE '%$search%' OR merek LIKE '%$search%')";
}

// Jika user memilih opsi filter lokasi tertentu
if ($filter_lokasi != '') {
    $query .= " AND lokasi = '$filter_lokasi'";
}

// Jika user memilih opsi filter kondisi tertentu
if ($filter_kondisi != '') {
    $query .= " AND kondisi = '$filter_kondisi'";
}

// Urutkan berdasarkan data terbaru yang diinput
$query .= " ORDER BY id DESC";

// Eksekusi query gabungan ke database MySQL
$result = mysqli_query($mysqli, $query);

// Menghitung jumlah total alat berdasarkan hasil filter/pencarian untuk widget statistik
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

        /* --- TAMPILAN KOTAK INFORMASI INSTITUSI KULIAH --- */
        .header-kampus {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-left: 5px solid #008080;
        }
        .header-kampus-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-kampus-table td {
            border: none !important;
            padding: 4px !important;
            background: transparent !important;
        }
        .logo-kampus {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }
        .text-kampus h2 {
            margin: 0;
            font-size: 13px;
            color: #666;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .text-kampus h1 {
            margin: 2px 0;
            font-size: 16px;
            color: #006666;
            font-weight: 700;
            text-transform: uppercase;
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

        /* Desain Baru Form Pencarian dan Filter */
        .search-filter-form {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        .input-search {
            flex: 1;
            min-width: 250px;
            padding: 9px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .select-filter {
            padding: 9px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: white;
            font-size: 14px;
            min-width: 160px;
        }

        .btn-cari {
            background-color: #008080;
            color: white;
            border: none;
            padding: 9px 20px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            transition: 0.2s;
        }

        .btn-cari:hover {
            background-color: #005a5a;
        }

        .btn-reset {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            padding: 9px 16px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.2s;
        }

        .btn-reset:hover {
            background-color: #5a6268;
        }

        /* Desain Tabel Modern Transparan */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-top: 10px;
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
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: rgba(0, 128, 128, 0.05);
        }

        /* --- BADGE STATUS WARNA LOKASI & KONDISI --- */
        .badge-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }
        .status-baik {
            background-color: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }
        .status-rusak {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }

        .badge-lokasi {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .lokasi-igd { background-color: #ffeef0; color: #f44336; }
        .lokasi-nicu { background-color: #e3f2fd; color: #0d47a1; }
        .lokasi-gigi { background-color: #e8f5e9; color: #1b5e20; }
        .lokasi-ok { background-color: #f3e5f5; color: #4a148c; }
        .lokasi-anak { background-color: #fff3e0; color: #e65100; }
        .lokasi-cssd { background-color: #ede7f6; color: #5e35b1; }
        .lokasi-default { background-color: #e0f7fa; color: #006064; }

        /* Tombol Aksi Lengkap (Detail, Edit & Delete) */
        .action-link {
            text-decoration: none;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }

        .detail {
            color: #00bcd4;
            background-color: rgba(0, 188, 212, 0.1);
            margin-right: 2px;
        }

        .detail:hover {
            background-color: rgba(0, 188, 212, 0.2);
        }

        .edit {
            color: #ff9800;
            background-color: rgba(255, 152, 0, 0.1);
            margin-left: 2px;
        }

        .edit:hover {
            background-color: rgba(255, 152, 0, 0.2);
        }

        .delete {
            color: #f44336;
            background-color: rgba(244, 67, 54, 0.1);
            margin-left: 4px;
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

    <div class="header-kampus">
        <table class="header-kampus-table">
            <tr>
                <td style="width: 75px; text-align: center; vertical-align: middle;">
                    <img src="logo_kampus.png" class="logo-kampus" alt="Logo" onerror="this.style.display='none'">
                </td>
                <td class="text-kampus" style="vertical-align: middle; padding-left: 8px;">
                    <h2>UJIAN AKHIR SEMESTER (UAS) — PEMROGRAMAN WEB</h2>
                    <h1>PROGRAM STUDI DIV TEKNOLOGI REKAYASA ELEKTROMEDIS</h1>
                    <h2 style="color: #555; font-weight: 500;">UMPKU SURAKARTA</h2>
                </td>
            </tr>
        </table>
    </div>

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

    <form method="GET" action="index.php" class="search-filter-form">
        <input type="text" name="search" class="input-search" placeholder="Cari Nama Alat atau Merek..." 
               value="<?php echo htmlspecialchars($search); ?>">

        <select name="filter_lokasi" class="select-filter">
            <option value="">-- Semua Lokasi --</option>
            <option value="Poli Gigi" <?php if($filter_lokasi == 'Poli Gigi') echo 'selected'; ?>>Poli Gigi</option>
            <option value="Poli" <?php if($filter_lokasi == 'Poli') echo 'selected'; ?>>Poli</option>
            <option value="Kamar OK1" <?php if($filter_lokasi == 'Kamar OK1') echo 'selected'; ?>>Kamar OK1</option>
            <option value="NICU" <?php if($filter_lokasi == 'NICU') echo 'selected'; ?>>NICU</option>
            <option value="RI.Anak" <?php if($filter_lokasi == 'RI.Anak') echo 'selected'; ?>>RI.Anak</option>
            <option value="IGD" <?php if($filter_lokasi == 'IGD') echo 'selected'; ?>>IGD</option>
            <option value="CSSD" <?php if($filter_lokasi == 'CSSD') echo 'selected'; ?>>CSSD</option>
            <option value="Kebidanan" <?php if($filter_lokasi == 'Kebidanan') echo 'selected'; ?>>Kebidanan</option>
        </select>

        <select name="filter_kondisi" class="select-filter">
            <option value="">-- Semua Kondisi --</option>
            <option value="Baik" <?php if($filter_kondisi == 'Baik') echo 'selected'; ?>>Baik</option>
            <option value="Rusak" <?php if($filter_kondisi == 'Rusak') echo 'selected'; ?>>Rusak</option>
        </select>

        <button type="submit" class="btn-cari">Cari</button>
        <?php if($search != '' || $filter_lokasi != '' || $filter_kondisi != ''): ?>
            <a href="index.php" class="btn-reset">Reset</a>
        <?php endif; ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nama Alat</th>
                <th>Tahun</th>
                <th>Merek</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
                <th style="text-align: center; width: 220px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php  
            if (mysqli_num_rows($result) > 0) {
                while($user_data = mysqli_fetch_array($result)) {         
                    
                    // 1. Logika Klasifikasi Kelas CSS Warna Badge Ruangan / Lokasi (Case Insensitive)
                    $lokasi_raw = strtolower(trim($user_data['lokasi']));
                    $class_lokasi = 'lokasi-default';
                    
                    if (strpos($lokasi_raw, 'igd') !== false) {
                        $class_lokasi = 'lokasi-igd';
                    } elseif (strpos($lokasi_raw, 'nicu') !== false) {
                        $class_lokasi = 'lokasi-nicu';
                    } elseif (strpos($lokasi_raw, 'gigi') !== false) {
                        $class_lokasi = 'lokasi-gigi';
                    } elseif (strpos($lokasi_raw, 'ok') !== false) {
                        $class_lokasi = 'lokasi-ok';
                    } elseif (strpos($lokasi_raw, 'anak') !== false) {
                        $class_lokasi = 'lokasi-anak';
                    } elseif (strpos($lokasi_raw, 'cssd') !== false) {
                        $class_lokasi = 'lokasi-cssd';
                    }

                    // Menyeragamkan penulisan string Lokasi di layar menjadi huruf besar semua (Data Cleaning)
                    $lokasi_clean = strtoupper($user_data['lokasi']);

                    // 2. Logika Klasifikasi Kelas CSS Warna Badge Kondisi Alat
                    $kondisi_alat = isset($user_data['kondisi']) ? $user_data['kondisi'] : 'Baik';
                    $class_kondisi = ($kondisi_alat == 'Rusak') ? 'status-rusak' : 'status-baik';

                    echo "<tr>";
                    echo "<td style='font-weight: 500;'>".$user_data['nama_alat']."</td>";
                    echo "<td>".$user_data['tahun']."</td>";
                    echo "<td>".$user_data['merek']."</td>";    
                    
                    // Render Badge Lokasi
                    echo "<td><span class='badge-lokasi ".$class_lokasi."'>".$lokasi_clean."</span></td>";    
                    
                    // Render Badge Kondisi Alat
                    echo "<td><span class='badge-status ".$class_kondisi."'>".$kondisi_alat."</span></td>";    
                    
                    // Render Tombol Menu Aksi Lengkap (Detail, Edit, Delete)
                    echo "<td style='text-align: center;'>
                            <a href='detail.php?id=$user_data[id]' class='action-link detail'>Detail</a>
                            <a href='edit.php?id=$user_data[id]' class='action-link edit'>Edit</a>
                            <a href='delete.php?id=$user_data[id]' class='action-link delete' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Delete</a>
                          </td>";
                    echo "</tr>";        
                }
            } else {
                echo "<tr><td colspan='6' style='text-align: center; color: #888; font-style: italic; padding: 25px;'>Data tidak ditemukan / tidak sesuai filter pencarian.</td></tr>";
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