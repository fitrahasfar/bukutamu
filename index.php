<!-- login.php - dengan XSS vulnerabilities -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Halaman Login | Sistem Informasi Buku Tamu</title>
    <link rel="icon" href="assets/img/1.jpeg" type="image/x-icon">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-success">
    <!-- Menambahkan DOM-based XSS vulnerability dengan parameter URL -->
    <div id="message"></div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5 text-center">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-lg-block bg-succes shadow-lg p-5 text-center">
                                <img src="assets/img/1.jpeg" width="250">
                                <!-- Reflected XSS vulnerability - menampilkan parameter error tanpa sanitasi -->
                                <?php if(isset($_GET['error'])): ?>
                                <div class="alert alert-danger">
                                    <?= $_GET['error'] ?>
                                </div>
                                <?php endif; ?>
                                <h3 class="text-dark">Sistem Informasi Buku Tamu</h3>
                                <h4 class="text-black"><small>Makassar, Sulawesi Selatan</small></h4>
                            </div> 
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <!-- DOM-based XSS vulnerability - menggunakan parameter welcome -->
                                        <h1 class="h4 text-gray-900 mb-4" id="welcome-message">Welcome Back!</h1>
                                    </div>
                                    <form class="user" action="cek_login.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user" id="exampleInputEmail" placeholder="username">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember Me</label>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block">Login</button>
                                        <hr>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="#">By.Widya Revani Duwila | 2021 - <?= date('Y')?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk DOM-based XSS -->
    <script>
    // DOM-based XSS vulnerability - menggunakan parameter dari URL
    var urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('welcome')) {
        document.getElementById('welcome-message').innerHTML = urlParams.get('welcome');
    }
    if(urlParams.has('message')) {
        document.getElementById('message').innerHTML = urlParams.get('message');
    }
    
    // Fungsi untuk menampilkan pesan tanpa sanitasi
    function showMessage(msg) {
        var div = document.createElement('div');
        div.innerHTML = msg; // DOM-based XSS vulnerability
        document.body.appendChild(div);
    }
    </script>

    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="assets/js/sb-admin-2.min.js"></script>
</body>
</html>
