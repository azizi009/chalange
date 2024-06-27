<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('desk-3139127_1280.jpg') no-repeat center center fixed; /* Menggunakan gambar sebagai background */
            background-size: cover; /* Menyesuaikan gambar dengan ukuran layar */
            color: white;
        }
        .container {
            background: rgba(255, 255, 255, 0.8); /* Warna latar belakang transparan */
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            padding: 20px;
            margin-top: 20px;
            color: black;
            max-width: 400px; /* Lebar maksimum container */
            margin: auto; /* Posisi tengah pada layar */
            transition: box-shadow 0.3s ease-in-out;
        }
        .container:hover {
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .form-control, .btn-primary, .btn-secondary {
            border-radius: 15px;
            transition: transform 0.3s ease;
        }
        .form-control:focus, .btn-primary:hover, .btn-secondary:hover {
            transform: scale(1.1);
        }
        .btn-primary {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            border: none;
        }
        .btn-secondary {
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            border: none;
        }
        h2 {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            margin-bottom: 20px; /* Jarak bawah untuk judul */
        }
        select[name="jenis_kelamin"]:focus, 
        select[name="angkatan"]:focus {
            transform: none;
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    include "koneksi.php"; // Pastikan file koneksi.php sudah diupdate untuk database penjualanbarang

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if (isset($_GET['kode_barang'])) {
        $kode_barang = input($_GET["kode_barang"]);
        $sql = "SELECT * FROM bahan WHERE kode_barang='$kode_barang'";
        $hasil = mysqli_query($kon, $sql);
        $data = mysqli_fetch_assoc($hasil);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $kode_barang = input($_POST["kode_barang"]);
        $nama_barang = input($_POST["nama_barang"]);
        $persediaan = input($_POST["persediaan"]);
        $harga_awal = input($_POST["harga_awal"]);
        $jumlah = input($_POST["jumlah"]);

        $sql = "UPDATE bahan SET nama_barang='$nama_barang', persediaan='$persediaan', harga_awal='$harga_awal', jumlah='$jumlah' WHERE kode_barang='$kode_barang'";
        $hasil = mysqli_query($kon, $sql);

        if ($hasil) {
            echo "<script>location.href='index.php';</script>";
        } else {
            echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";
        }
    }
    ?>
    <h2>Update Data Barang</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Kode Barang:</label>
            <input type="text" name="kode_barang" class="form-control" placeholder="Masukan Kode Barang" value="<?php echo htmlspecialchars($data['kode_barang']); ?>" required>
        </div>
        <div class="form-group">
            <label>Nama Barang:</label>
            <input type="text" name="nama_barang" class="form-control" placeholder="Masukan Nama Barang" value="<?php echo htmlspecialchars($data['nama_barang']); ?>" required>
        </div>
        <div class="form-group">
            <label>Persediaan:</label>
            <input type="text" name="persediaan" class="form-control" placeholder="Masukan Persediaan" value="<?php echo htmlspecialchars($data['persediaan']); ?>" required>
        </div>
        <div class="form-group">
            <label>Harga Awal:</label>
            <input type="text" name="harga_awal" class="form-control" placeholder="Masukan Harga Awal" value="<?php echo htmlspecialchars($data['harga_awal']); ?>" required>
        </div>
        <div class="form-group">
            <label>Jumlah:</label>
            <input type="text" name="jumlah" class="form-control" placeholder="Masukan Jumlah" value="<?php echo htmlspecialchars($data['jumlah']); ?>" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Seleseai</button>
        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Batal</button>
    </form>
</div>
</body>
</html>
