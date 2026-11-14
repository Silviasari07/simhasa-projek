<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SIMHASA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #064e3b 0%, #059669 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: system-ui, -apple-system, sans-serif;
    }
    .auth-card {
      border-radius: 18px;
      border: none;
      max-width: 420px;
      width: 100%;
    }
  </style>
</head>
<body class="p-3">
  <div class="card auth-card shadow-lg p-4 p-md-5">
    <div class="text-center mb-4">
      <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-2" style="width: 56px; height: 56px; background-color: #dcfce7; color: #16a34a;">
        <i class="bi bi-asterisk fs-3"></i>
      </div>
      <h4 class="fw-bold text-dark mb-1">Masuk ke SIMHASA</h4>
      <p class="text-muted small mb-0">Sistem Informasi Monitoring Kelapa Sawit</p>
    </div>

    @if ($errors->any())
      <div class="alert alert-danger py-2 small">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label class="form-label small fw-semibold">Alamat Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="contoh@simhasa.com" required autofocus>
      </div>
      <div class="mb-4">
        <label class="form-label small fw-semibold">Password</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
        <i class="bi bi-box-arrow-in-right me-1"></i> Login
      </button>
    </form>

    <div class="text-center mt-4 small">
      <span class="text-muted">Belum punya akun?</span>
      <a href="{{ route('register') }}" class="text-success fw-semibold text-decoration-none">Daftar Sekarang</a>
    </div>
  </div>
</body>
</html>