<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Tidak Ditemukan</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-body-tertiary d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="col-11 col-sm-8 col-md-6 col-lg-4 mx-auto">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body p-5">
                    <i class="fa-solid fa-box-open fs-1 text-primary opacity-75 mb-4 d-block"></i>
                    <h1 class="h5 fw-bold mb-2">Tracking Tidak Ditemukan</h1>
                    <p class="text-muted small mb-0">
                        <?= isset($message) ? htmlspecialchars($message) : 'Data yang kamu cari tidak tersedia.' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>