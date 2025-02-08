<?php include "header.php"; ?>

<?php 
//Uji Jika tombol simpan diklik
if (isset ($_POST['bsimpan'])) {
    $tgl = date('Y-m-d');
    // Data dari form tidak disanitasi
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $tujuan = $_POST['tujuan'];
    $nope = $_POST['nope'];

    $simpan = mysqli_query($koneksi, "INSERT INTO ttamu (tanggal, nama, alamat, tujuan, nope) VALUES ('$tgl', '$nama', '$alamat', '$tujuan', '$nope')");

    if($simpan) {
        // Reflected XSS vulnerability pada alert
        echo "<script> alert('Data untuk " . $_POST['nama'] . " berhasil disimpan!'); document.location = '?' </script>";
    } else {
        echo "<script> alert('Simpan data GAGAL !!!'); document.location = '?' </script>";
    }
}

// Fungsi pencarian yang rentan terhadap Reflected XSS
$search_results = [];
if (isset($_GET['search'])) {
    $search_input = $_GET['search'];
    if (preg_match("/<script.*?>.*?<\/script>/i", $search_input)) {
        // Log serangan XSS
        error_log("XSS Attack detected with input: " . $search_input, 3, "/var/log/xss_attack.log");
    }
    $query = "SELECT * FROM ttamu WHERE nama LIKE '%$search_input%' OR alamat LIKE '%$search_input%' OR tujuan LIKE '%$search_input%'";
    $search_results = mysqli_query($koneksi, $query);
}
?>

<!-- Head -->
<div class="head text-center">
    <h2 class="text-white">Sistem Informasi Buku Tamu</h2>
</div>

<!-- Tambahan div untuk kerentanan DOM XSS -->
<div id="welcomeMessage"></div>
<div id="userInput"></div>

<!-- Form pencarian -->
<div class="row mb-3">
    <div class="col-lg-12">
        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <input type="text" id="searchInput" name="search" class="form-control" 
                       placeholder="Cari pengunjung..."
                       value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </div>
        </form>
        <div id="searchResults"></div>
    </div>
</div>

<!-- Menampilkan hasil pencarian - Reflected XSS -->
<?php if(isset($_GET['search'])): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Hasil pencarian untuk: <?= $_GET['search'] ?></h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Tujuan</th>
                            <th>No. HP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($search_results) {
                            $no = 1;
                            while($row = mysqli_fetch_array($search_results)) {
                                echo "<tr>
                                    <td>$no</td>
                                    <td>$row[tanggal]</td>
                                    <td>$row[nama]</td>
                                    <td>$row[alamat]</td>
                                    <td>$row[tujuan]</td>
                                    <td>$row[nope]</td>
                                </tr>";
                                $no++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- awal -->
<div class="row mt-2">
    <!-- col lg-7 -->
    <div class="col-lg-7 mb-3">
        <div class="card shadow bg-gradient-light">
            <!-- card body -->
            <div class="card-body">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Identitas Pengunjung</h1>
                </div>
                <form class="user" method="POST" action="">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" name="nama" placeholder="Nama Pengunjung" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" name="alamat" placeholder="Alamat Pengunjung" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" name="tujuan" placeholder="Tujuan Pengunjung" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" name="nope" placeholder="No Hp Pengunjung" required>
                    </div>
                    <button type="submit" name="bsimpan" class="btn btn-primary btn-user btn-block">Simpan Data</button>
                </form>
                <hr>
                <div class="text-center">
                    <a class="small" href="#">by. Widya Revani Duwila | 2021 - <?= date('Y') ?></a>
                </div>
            </div>
        </div>
    </div>

    <!-- col lg-5 -->
    <div class="col-lg-5 mb-3">
        <div class="card shadow">
            <div class="card-body">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Statistik Pengunjung</h1>
                </div>
                <?php
                //deklarasi tanggal
                $tgl_sekarang = date('Y-m-d');
                $kemarin = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
                $seminggu = date('Y-m-d h:i:s', strtotime('-1 week +1 day', strtotime($tgl_sekarang))); 
                $sekarang = date('Y-m-d h:i:s');

                //Query statistik
                $tgl_sekarang = mysqli_fetch_array(mysqli_query(
                    $koneksi, 
                    "SELECT count(*) FROM ttamu where tanggal like '%$tgl_sekarang%'"
                ));
                $kemarin = mysqli_fetch_array(mysqli_query(
                    $koneksi, 
                    "SELECT count(*) FROM ttamu where tanggal like '%$kemarin%'"
                ));
                $seminggu = mysqli_fetch_array(mysqli_query(
                    $koneksi, 
                    "SELECT count(*) FROM ttamu where tanggal BETWEEN '$seminggu' and '$sekarang'"
                ));
                $bulan_ini = date('m');
                $sebulan = mysqli_fetch_array(mysqli_query(
                    $koneksi, 
                    "SELECT count(*) FROM ttamu where month(tanggal) = '$bulan_ini'"
                ));
                $keseluruhan = mysqli_fetch_array(mysqli_query(
                    $koneksi, 
                    "SELECT count(*) FROM ttamu"
                ));
                ?>
                <table class="table table-bordered">
                    <tr>
                        <td>Hari Ini</td>
                        <td>: <?= $tgl_sekarang[0] ?></td>
                    </tr>
                    <tr>
                        <td>Kemarin</td>
                        <td>: <?= $kemarin[0] ?></td>
                    </tr>
                    <tr>
                        <td>Minggu ini</td>
                        <td>: <?= $seminggu[0] ?></td>
                    </tr>
                    <tr>
                        <td>Bulan Ini</td>
                        <td>: <?= $sebulan[0] ?></td>
                    </tr>
                    <tr>
                        <td>Keseluruhan</td>
                        <td>: <?= $keseluruhan[0] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Pengunjung Hari Ini [<?= date('Y-m-d') ?>]</h6>
    </div>
    <div class="card-body">
        <a href="rekapitulasi.php" class="btn btn-success mb-3"><i class="fa fa-table"></i> Rekapitulasi Pengunjung</a>
        <a href="logout.php" class="btn btn-danger mb-3"><i class="fa fa-sign-out-alt"></i> Logout</a>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Nama Pengunjung</th>
                        <th>Alamat</th>
                        <th>Tujuan</th>
                        <th>NO. Hp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $tgl = date('Y-m-d');
                    $tampil = mysqli_query($koneksi, "SELECT * FROM ttamu where tanggal like '%$tgl%' order by id desc");
                    $no = 1;
                    while ($data = mysqli_fetch_array($tampil)){
                        echo "<tr>
                            <td>$no</td>
                            <td>$data[tanggal]</td>
                            <td>$data[nama]</td>
                            <td>$data[alamat]</td>
                            <td>$data[tujuan]</td>
                            <td>$data[nope]</td>
                        </tr>";
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script dengan kerentanan DOM-based XSS yang lebih eksplisit -->
<script>
// Fungsi untuk mendapatkan parameter dari URL
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Fungsi untuk memperbarui welcome message - kerentanan DOM XSS
function updateWelcomeMessage() {
    const name = getQueryParam('name');
    if (name) {
        document.getElementById('welcomeMessage').innerHTML = 
            '<div class="alert alert-info">Selamat datang, ' + decodeURIComponent(name) + '!</div>';
    }
}

// Fungsi untuk memperbarui user input - kerentanan DOM XSS
function updateUserInput() {
    const userInput = getQueryParam('input');
    if (userInput) {
        document.getElementById('userInput').innerHTML = 
            '<div class="alert alert-secondary">Input Anda: ' + decodeURIComponent(userInput) + '</div>';
    }
}

// Event listener untuk search input - kerentanan DOM XSS
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value;
    const resultsDiv = document.getElementById('searchResults');
    
    // Langsung memasukkan input pengguna ke DOM
    resultsDiv.innerHTML = `
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Pencarian Real-time</h5>
                <p>Anda mencari: ${searchTerm}</p>
                <div id="searchContent">${searchTerm}</div>
            </div>
        </div>
    `;

    // Tambahan untuk membuat kerentanan lebih mudah terdeteksi
    document.title = 'Pencarian: ' + searchTerm;
    
    if(searchTerm.length > 0) {
        // Membuat URL dengan parameter yang tidak di-encode
        const url = 'search_ajax.php?term=' + searchTerm;
        history.pushState({}, '', '?search=' + searchTerm);
        
        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById('searchContent').innerHTML += data;
            });
    }
});

// Hash fragment vulnerability
function processHash() {
    const hash = window.location.hash.slice(1);
    if (hash) {
        const div = document.createElement('div');
        div.innerHTML = `<div class="alert alert-warning">${decodeURIComponent(hash)}</div>`;
        document.body.insertBefore(div, document.body.firstChild);
    }
}

// Eksekusi fungsi saat halaman dimuat
window.onload = function() {
    updateWelcomeMessage();
    updateUserInput();
    processHash();
};

// Tambahan event listener untuk hash changes
window.addEventListener('hashchange', processHash);

// Fungsi tambahan untuk membuat kerentanan lebih mudah terdeteksi
function processUrlFragment() {
    const fragment = window.location.href.split('#')[1];
    if (fragment) {
        eval(decodeURIComponent(fragment));
    }
}

// Tambahan event listener untuk kerentanan DOM XSS
document.addEventListener('DOMContentLoaded', function() {
    processUrlFragment();
});
</script>

<?php include "footer.php"; ?>