<?php include "header.php"?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4 mt-3">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rekapitulasi Pengunjung</h6>
            </div>
            <div class="card-body">
                <!-- Form pencarian yang rentan terhadap Reflected XSS -->
                <form method="GET" action="" class="mb-4">
                    <div class="form-group">
                        <input type="text" name="search" id="searchBox" class="form-control mb-3" 
                               placeholder="Cari pengunjung..."
                               value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Cari</button>
                </form>

                <!-- Hasil pencarian real-time (DOM-based XSS) -->
                <div id="searchResults"></div>

                <!-- Menampilkan hasil pencarian jika ada -->
                <?php 
                if(isset($_GET['search'])) {
                    $search = $_GET['search']; // Tidak ada sanitasi input
                    echo "<div class='alert alert-info'>Hasil pencarian untuk: $search</div>"; // Reflected XSS
                    
                    // Query pencarian
                    $query = "SELECT * FROM ttamu WHERE nama LIKE '%$search%' OR alamat LIKE '%$search%' OR tujuan LIKE '%$search%'";
                    $hasilCari = mysqli_query($koneksi, $query);
                    
                    if(mysqli_num_rows($hasilCari) > 0) {
                        echo "<div class='table-responsive mb-4'>";
                        echo "<table class='table table-bordered'>";
                        echo "<thead><tr><th>No.</th><th>Tanggal</th><th>Nama</th><th>Alamat</th><th>Tujuan</th><th>No HP</th></tr></thead>";
                        echo "<tbody>";
                        
                        $no = 1;
                        while($row = mysqli_fetch_array($hasilCari)) {
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
                        echo "</tbody></table></div>";
                    }
                }
                ?>

                <!-- Form tanggal yang sudah ada -->
                <form method="POST" action="" class="text-center">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Dari Tanggal</label>
                                <input class="form-control" type="date" name="tanggal1" value="<?= isset($_POST['tanggal1']) ? $_POST['tanggal1'] : date('Y-m-d')?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sampai Tanggal</label>
                                <input class="form-control" type="date" name="tanggal2" value="<?= isset($_POST['tanggal2']) ? $_POST['tanggal2'] : date('Y-m-d')?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-2">
                            <button class="btn btn-primary form-control" name="btampilkan"><i class="fa fa-search"></i> Tampilkan</button>
                        </div>
                        <div class="col-md-2">
                            <a href="admin.php" class="btn btn-danger form-control"><i class="fa fa-backward"></i> Kembali</a>
                        </div>
                    </div>
                </form>

                <!-- Tabel berdasarkan tanggal yang sudah ada -->
                <?php 
                if(isset($_POST['btampilkan'])) :
                    $tgl1 = $_POST['tanggal1'];
                    $tgl2 = $_POST['tanggal2'];
                ?>
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
                            $tampil = mysqli_query($koneksi, "SELECT * FROM ttamu where tanggal BETWEEN '$tgl1' and '$tgl2' order by id desc");
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
                    
                    <center>
                        <form method="POST" action="exportexcel.php">
                            <div class="col-md-4">
                                <input type="hidden" name="tanggala" value="<?=@$_POST['tanggal1']?>">
                                <input type="hidden" name="tanggalb" value="<?=@$_POST['tanggal2']?>">
                                <button class="btn btn-success form-control" name="bexport">
                                    <i class="fa fa-download"></i> Export Data Excel
                                </button>
                            </div>
                        </form>
                    </center>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk DOM-based XSS -->
<script>
// Fungsi pencarian real-time yang rentan terhadap DOM-based XSS
document.getElementById('searchBox').addEventListener('input', function() {
    var searchTerm = this.value;
    var resultsDiv = document.getElementById('searchResults');
    
    // Rentan terhadap DOM-based XSS karena menggunakan innerHTML tanpa sanitasi
    resultsDiv.innerHTML = `
        <div class="alert alert-info mb-3">
            Pencarian real-time untuk: ${searchTerm}
        </div>
    `;
});

// Fungsi tambahan untuk menampilkan parameter dari URL (DOM-based XSS)
function showFilterMessage() {
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('filter')) {
        const filterMsg = urlParams.get('filter');
        const div = document.createElement('div');
        // Rentan terhadap DOM-based XSS
        div.innerHTML = `<div class="alert alert-warning">${filterMsg}</div>`;
        document.querySelector('.card-body').insertBefore(div, document.querySelector('.card-body').firstChild);
    }
}

// Jalankan fungsi saat halaman dimuat
showFilterMessage();
</script>

<?php include "footer.php" ?>