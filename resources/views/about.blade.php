<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tentang Pembuat - Koperasi Mahasiswa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      font-family: sans-serif;
    }
    .card {
      border-radius: 12px;
    }
    .developer-avatar {
      width: 40px;
      height: 40px;
      background: #e0e0e0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }
  </style>
</head>
<body>

  <nav class="navbar bg-white shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
      <h5 class="m-0 text-success fw-bold">Tentang Pembuat</h5>
      <dv></div> <!-- Spacer -->
    </div>
  </nav>

  <div class="container py-4">
    <div class="card shadow-sm mb-4">
      <div class="card-body text-center">
        <div class="text-primary mb-2" style="font-size: 50px;">
          <i class="bi bi-people-fill"></i>
        </div>
        <h5 class="fw-bold mb-0">Tim Pengembang</h5>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <h6 class="fw-bold">Anggota Tim:</h6>
        <hr>
        @php
          $members = [
            ['name' => 'Rafly Romeo', 'nim' => '2211102265'],
            ['name' => 'Rizky Cristian', 'nim' => '21102248'],
            ['name' => 'Rocky Justice Sibuea', 'nim' => '2211102260'],
            ['name' => 'Muhammad Ardhito', 'nim' => '2211102244'],
            ['name' => 'Dwyan Ramadhani Saputra', 'nim' => '2211102227'],
            ['name' => 'Zain Aufa Rahman', 'nim' => '2211102231'],
          ];
        @endphp

        @foreach($members as $member)
          <div class="d-flex align-items-center mb-3">
            <div class="developer-avatar me-3">
              <i class="bi bi-person-fill"></i>
            </div>
            <div>
              <div class="fw-medium">{{ $member['name'] }}</div>
              <div class="text-muted">NIM: {{ $member['nim'] }}</div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <footer class="position-fixed bottom-0 end-0 p-2 text-muted small">
    &copy; Syechan
  </footer>
</body>
</body>
</html>
