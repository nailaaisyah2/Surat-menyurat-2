<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Persetujuan - Sistem Surat Menyurat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pending-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 500px;
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .icon-container {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .icon-container i {
            font-size: 3rem;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="pending-card">
                    <div class="icon-container">
                        <i class="bi bi-hourglass-split"></i>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success mb-4">
                        <h5 class="alert-heading mb-2"><i class="bi bi-check-circle"></i> Registrasi Berhasil!</h5>
                        <p class="mb-0"><strong>{{ session('success') }}</strong></p>
                    </div>
                    @endif

                    <h2 class="text-center mb-3">Menunggu Persetujuan</h2>
                    <p class="text-center text-muted mb-4">
                        Registrasi Anda telah berhasil! Akun Anda sedang menunggu persetujuan dari admin/petugas divisi Anda.
                    </p>

                    <div class="alert alert-info mb-3">
                        <h5 class="alert-heading"><i class="bi bi-info-circle"></i> Informasi</h5>
                        <hr>
                        <p class="mb-0">
                            Admin atau petugas dari divisi yang Anda pilih akan memverifikasi dan menyetujui akun Anda. 
                            Setelah disetujui, Anda akan dapat login ke sistem.
                        </p>
                    </div>

                    <div class="alert alert-warning mb-3">
                        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Penting</h5>
                        <hr>
                        <p class="mb-0">
                            <strong>Anda tidak dapat membuat lebih dari 1 akun.</strong> Harap tunggu persetujuan admin/petugas divisi Anda sebelum mencoba registrasi ulang.
                        </p>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Halaman Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

