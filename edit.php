<?php
// Menyambungkan ke database
include_once("config.php");

// 1. MEMPROSES DATA SAAT TOMBOL UPDATE DIKLIK
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_alat = $_POST['nama_alat'];
    $merek = $_POST['merek'];
    $lokasi = $_POST['lokasi'];

    // Query UPDATE tanpa melibatkan kolom 'tahun'
    $result = mysqli_query($mysqli, "UPDATE alat SET nama_alat='$nama_alat', merek='$merek', lokasi='$lokasi' WHERE id=$id");
    
    // Redirect kembali ke halaman utama setelah sukses
    header("Location: index.php");
    exit();
}

// 2. MENGAMBIL DATA BERDASARKAN ID DI URL UNTUK DIMASUKKAN KE FORM
$id = $_GET['id'];
$result = mysqli_query($mysqli, "SELECT * FROM alat WHERE id=$id");

while($user_data = mysqli_fetch_array($result)) {
    $nama_alat = $user_data['nama_alat'];
    $merek = $user_data['merek'];
    $lokasi = $user_data['lokasi'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Alat</title>
</head>
<body>
    <a href="index.php">Go to Home</a><br><br>

    <form action="edit.php" method="post" name="update_alat">
        <table border="0">
            <tr>
                <td>Nama Alat</td>
                <td><input type="text" name="nama_alat" value="<?php echo $nama_alat; ?>" required></td>
            </tr>
            <tr>
                <td>Merek</td>
                <td><input type="text" name="merek" value="<?php echo $merek; ?>" required></td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td><input type="text" name="lokasi" value="<?php echo $lokasi; ?>" required></td>
            </tr>
            <tr>
                <td><input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"></td>
                <td><input type="submit" name="update" value="Update"></td>
            </tr>
        </table>
    </form>
</body>
</html>