<!DOCTYPE html>
<html>
<head>
    <title>Add Alat</title>
</head>
<body>
    <a href="index.php">Go to Home</a><br><br>

    <form action="add.php" method="post" name="form1">
        <table width="25%" border="0">
            <tr>
                <td>Nama Alat</td>
                <td><input type="text" name="nama_alat" required></td>
            </tr>
            <tr>
                <td>Tahun</td>
                <td><input type="text" name="tahun" required></td>
            </tr>
            <tr>
                <td>Merek</td>
                <td><input type="text" name="merek" required></td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td><input type="text" name="lokasi" required></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="Submit" value="Add"></td>
            </tr>
        </table>
    </form>

<?php
if(isset($_POST['Submit'])) {
    $nama_alat = $_POST['nama_alat'];
    $tahun = $_POST['tahun'];
    $merek = $_POST['merek'];
    $lokasi = $_POST['lokasi'];

    include_once("config.php");
    $result = mysqli_query($mysqli, "INSERT INTO alat(nama_alat,tahun,merek,lokasi) VALUES('$nama_alat','$tahun','$merek','$lokasi')");
    echo "Alat added successfully. <a href='index.php'>View Alat</a>";
}
?>
</body>
</html>