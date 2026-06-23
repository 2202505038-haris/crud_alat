<?php
// Menyambungkan ke database menggunakan file konfigurasi
include_once("config.php");

// Mengambil semua data dari database (diurutkan dari yang terbaru)
$result = mysqli_query($mysqli, "SELECT * FROM alat ORDER BY id DESC");
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
    </style>
</head>
<body>

    <h2>Data Alat Elektromedis</h2>
    <p>Oleh: Haris Gunawan (2202505038)</p>
    
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

</body>
</html>