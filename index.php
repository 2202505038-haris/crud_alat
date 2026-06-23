<?php
// Menyambungkan ke database
include_once("config.php");

// Mengambil data dari database
$result = mysqli_query($mysqli, "SELECT * FROM alat ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sim Rs - Data Alat</title>
    <style>
        .header { background-color: orange; color: white; }
        table { width: 80%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <b>Data Alat Elektromedis</b><br><br>
    <p>Haris Gunawan (2202505038)</p>
   <a href="add.php">Tambah Alat Baru</a> | <a href="print.php" target="_blank">Cetak PDF</a><br><br>

    <table>
        <tr class="header">
            <th>Nama Alat</th>
            <th>Merek</th>
            <th>Lokasi</th>
            <th>Aksi</th>
        </tr>

        <?php  
        // Melakukan perulangan untuk menampilkan data dari database ke baris tabel
        while($user_data = mysqli_fetch_array($result)) {         
            echo "<tr>";
            echo "<td>" . $user_data['nama_alat'] . "</td>";
            echo "<td>" . $user_data['merek'] . "</td>";
            echo "<td>" . $user_data['lokasi'] . "</td>";    
            echo "<td>
                    <a href='edit.php?id=" . $user_data['id'] . "'>Edit</a> | 
                    <a href='delete.php?id=" . $user_data['id'] . "'>Delete</a>
                  </td>";
            echo "</tr>";        
        }
        ?>
    </table>
</body>
</html>