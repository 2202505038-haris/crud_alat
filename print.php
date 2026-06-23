<?php
// Koneksi ke database
include_once("config.php");

// Mengambil semua data dari database
$result = mysqli_query($mysqli, "SELECT * FROM alat ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Alat Elektromedis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        h2, p {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2>Data Alat Elektromedis</h2>
    <p>Oleh: Haris Gunawan (2202505038)</p>
    <hr>

    <table>
        <tr>
            <th>Nama Alat</th>
            <th>Tahun</th>
            <th>Merek</th>
            <th>Lokasi</th>
        </tr>

        <?php  
        while($user_data = mysqli_fetch_array($result)) {         
            echo "<tr>";
            echo "<td>".$user_data['nama_alat']."</td>";
            echo "<td>".$user_data['tahun']."</td>";
            echo "<td>".$user_data['merek']."</td>";    
            echo "<td>".$user_data['lokasi']."</td>";    
            echo "</tr>";        
        }
        ?>
    </table>

    <script>
        window.print();
    </script>

</body>
</html>