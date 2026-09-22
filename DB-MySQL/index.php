<?php
$conn = mysqli_connect("localhost", "root", "", "siswa_smk");

// Hapus data
if (isset($_GET['delete']) && isset($_GET['id'])) {
    mysqli_query($conn, "DELETE FROM siswa WHERE id = " . (int) $_GET['id']);
    header("Location: tester_updt.php");
    exit;
}

// Simpan data (insert / update)
if (isset($_POST['save'])) {
    $id     = (int) $_POST['id'];
    $nis    = mysqli_real_escape_string($conn, $_POST['nis']);
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $nilai  = mysqli_real_escape_string($conn, $_POST['nilai']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    if ($id > 0) {
        // Update data lama
        mysqli_query($conn, "UPDATE siswa SET nis='$nis', nama='$nama', nilai='$nilai', alamat='$alamat' WHERE id=$id");
    } else {
        // Insert data baru
        mysqli_query($conn, "INSERT INTO siswa (nis, nama, nilai, alamat) VALUES ('$nis', '$nama', '$nilai', '$alamat')");
    }

    header("Location: tester_updt.php");
    exit;
}

// Tampilkan form edit jika baris dipilih lalu tombol Edit ditekan
$formData = ['id' => 0, 'nis' => '', 'nama' => '', 'nilai' => '', 'alamat' => ''];
$showForm = false;

if (isset($_GET['edit']) && isset($_GET['id'])) {
    $res = mysqli_query($conn, "SELECT * FROM siswa WHERE id = " . (int) $_GET['id']);
    $formData = mysqli_fetch_assoc($res);
    $showForm = true;
} elseif (isset($_GET['add'])) {
    // Form kosong untuk tambah data baru
    $showForm = true;
}

$result = mysqli_query($conn, "SELECT * FROM siswa");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
</head>
<body>

<form action="tester_updt.php" method="get">
    <table border="1">
        <tr>
            <th>Pilih</th><th>Id</th><th>Nis</th><th>Nama</th><th>Nilai</th><th>Alamat</th>
        </tr>
        <?php while ($data = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><input type="radio" name="id" value="<?= $data['id'] ?>" required></td>
            <td><?= $data['id'] ?></td>
            <td><?= htmlspecialchars($data['nis']) ?></td>
            <td><?= htmlspecialchars($data['nama']) ?></td>
            <td><?= htmlspecialchars($data['nilai']) ?></td>
            <td><?= htmlspecialchars($data['alamat']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <button type="submit" name="edit" value="1">Edit</button>
    <button type="submit" name="delete" value="1" onclick="return confirm('Hapus data yang dipilih?')">Hapus</button>
</form>

<br>
<a href="tester_updt.php?add=1"><button type="button">Tambah Data</button></a>

<?php if ($showForm): ?>
    <hr>
    <h3><?= $formData['id'] ? 'Edit Siswa' : 'Tambah Siswa' ?></h3>
    <form action="tester_updt.php" method="post">
        <input type="hidden" name="save" value="1">
        <input type="hidden" name="id" value="<?= $formData['id'] ?>">
        <input type="text" name="nis" placeholder="Nis" value="<?= htmlspecialchars($formData['nis']) ?>" required>
        <input type="text" name="nama" placeholder="Nama" value="<?= htmlspecialchars($formData['nama']) ?>" required>
        <input type="text" name="nilai" placeholder="Nilai" value="<?= htmlspecialchars($formData['nilai']) ?>" required>
        <input type="text" name="alamat" placeholder="Alamat" value="<?= htmlspecialchars($formData['alamat']) ?>" required>
        <button type="submit">Simpan</button>
        <a href="tester_updt.php">Batal</a>
    </form>
<?php endif; ?>

</body>
</html>
