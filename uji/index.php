<?php
session_start(); 
include "koneksi.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $kode_barang = input($_POST["kode_barang"]);
    $nama_barang = input($_POST["nama_barang"]);
    $persediaan = input($_POST["persediaan"]);
    $harga_awal = input($_POST["harga_awal"]);    
    $jumlah = input($_POST["jumlah"]);
  
    $sql = "INSERT INTO bahan (kode_barang, nama_barang, persediaan, harga_awal, jumlah) VALUES ('$kode_barang', '$nama_barang', '$persediaan', '$harga_awal', '$jumlah')";
    $hasil = mysqli_query($kon, $sql);

    if ($hasil) {
        if (mysqli_affected_rows($kon) > 0) {
            $_SESSION['pesan'] = "<div class='alert alert-success'>Data berhasil ditambahkan.</div>";
            header("Location: " . $_SERVER['PHP_SELF'] . "?pesan=berhasil");
            exit();
        } else {
            $_SESSION['pesan'] = "<div class='alert alert-warning'>Tidak ada data yang ditambahkan.</div>";
        }
    } else {
        $_SESSION['pesan'] = "<div class='alert alert-danger'>Data gagal ditambahkan.</div>";
    }
}

if (isset($_GET['hapus']) && isset($_GET['kode_barang'])) {
    $kode_barang = mysqli_real_escape_string($kon, $_GET['kode_barang']);
    
    $sql_delete = "DELETE FROM bahan WHERE kode_barang = '$kode_barang'";
    $hasil_delete = mysqli_query($kon, $sql_delete);

    if ($hasil_delete) {
        $_SESSION['pesan'] = "<div class='alert alert-success'>Data berhasil dihapus.</div>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['pesan'] = "<div class='alert alert-danger'>Data gagal dihapus.</div>";
    }
}


if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($kon, $_GET['keyword']);
    $sql = "SELECT * FROM bahan WHERE nama_barang LIKE '%$keyword%' OR nama_barang LIKE '%$keyword%'";
} else {
    $sql = "SELECT * FROM bahan";
}

$hasil = mysqli_query($kon, $sql);

if (!$hasil) {
    die("Query error: " . mysqli_error($kon));
}

$count = mysqli_num_rows($hasil);

if ($count == 0 && isset($_GET['keyword'])) {
    $pesan_cari = "<div class='alert alert-warning'>Data tidak ditemukan.</div>";
} else {
    $pesan_cari = "";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Barang Penjualan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('desk-3139127_1280.jpg') no-repeat center center fixed;
            background-size: cover; 
            color: white;
        }
        .running-text {
            font-family: 'Courier New', Courier, monospace;
            font-size: 30px;
            font-style: italic;
            white-space: nowrap; 
            overflow: hidden; 
            position: relative; 
            animation: slide-left 15s linear infinite, bling-bling 1s infinite;
            
        }

        @keyframes slide-left {
            from {
                left: 100%;
            }
            to {
                left: -100%;
            }
        }

        .container {
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead th {
            border-top: 2px solid #343a40;
        }

        .table-bordered tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-bordered tbody tr:last-child td {
            border-bottom: 2px solid #dee2e6;
        }

        .table-bordered:hover tr:hover {
            background-color: #f2f2f2;
        }

        h4 {
            color: #333333;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 30px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2);
            text-decoration: underline;
        }

        @keyframes bling-bling {
            0% { color: red; }
            25% { color: blue; }
            50% { color: green; }
            75% { color: orange; }
            100% { color: purple; }
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #dee2e6;
            text-align: center;
        }

        th {
            background-color: #343a40;
            color: #fff;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .btn-primary {
            border-radius: 20px;
            background-color: #1e90ff;
            border-color: #1e90ff;
            margin-right: 5px;
        }

        .btn-danger {
            border-radius: 20px;
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
            padding: 8px 16px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0a75c2;
            border-color: #0a75c2;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark justify-content-between">
    <span class="navbar-brand mb-0 h1 running-text">Selamat Datang di Challange Studi Kasus Azizi</span>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-center mb-4">Tabel Bahan Bangunan</h4>
            <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form-inline mb-3">
                <input class="form-control mr-sm-2" type="text" placeholder="Cari Nama Barang" name="keyword">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Cari</button>
            </form>
            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == 'berhasil') {
                    echo "<div class='alert alert-success'>Data berhasil ditambahkan.</div>";
                } elseif ($_GET['pesan'] == 'gagal') {
                    echo "<div class='alert alert-danger'>Data gagal ditambahkan.</div>";
                }
            }
            ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col">Kode Barang</th>
                            <th scope="col">Nama Barang</th>
                            <th scope="col">Persediaan</th>
                            <th scope="col">Harga Awal</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Operasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($data = mysqli_fetch_assoc($hasil)) {
                            ?>
                            <tr>
                                <td><?php echo $data["kode_barang"]; ?></td>
                                <td><?php echo $data["nama_barang"]; ?></td>
                                <td><?php echo $data["persediaan"]; ?></td>
                                <td><?php echo $data["harga_awal"]; ?></td>
                                <td><?php echo $data["jumlah"]; ?></td>
                                <td>
                                    <a href="update.php?kode_barang=<?php echo htmlspecialchars($data['kode_barang']); ?>" class="btn btn-primary" role="button">Ubah</a>
                                    <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?hapus=true&kode_barang=<?php echo $data['kode_barang']; ?>" class="btn btn-danger btn-sm" role="button" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php echo $pesan_cari; ?>
            <a href="create.php" class="btn btn-primary mt-4" role="button">Tambah Data</a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
