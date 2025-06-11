<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profil - Koperasi Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <style>
    :root { --koperasi-green: #388e3c; }
    body { background-color: #f8f9fa; }
    .card {
      border-radius: 1rem;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    .profile-avatar {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: var(--koperasi-green);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      color: white;
      margin: 0 auto 1rem;
    }
    .btn-koperasi {
      background-color: var(--koperasi-green);
      color: white;
      border: none;
    }
    .btn-koperasi:hover {
      background-color: #2e7d32;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card p-4">
        <div class="text-center mb-4">
          <div class="profile-avatar" id="userPhoto">
            <i class="fas fa-user"></i>
          </div>
          <h5 id="userEmail">Memuat...</h5>
          <small class="text-muted" id="userJoinDate"></small>
        </div>

        <h5 class="mb-3">Perbaharui Password</h5>
        <form id="passwordUpdateForm">
          <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" class="form-control" id="newPassword" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" id="confirmPassword" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-koperasi">
              <i class="fas fa-key me-2"></i> Update Password
            </button>
          </div>
        </form>
        <div id="message" class="mt-3 text-center"></div>
      </div>
    </div>
  </div>
</div>

<!-- Firebase SDK -->
<script type="module">
  // Import SDK
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-analytics.js";
  import { getAuth, onAuthStateChanged, updatePassword } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";

  // Konfigurasi Firebase
  const firebaseConfig = {
    apiKey: "AIzaSyAuq0JEjnEOagJnONPemkMP0bbgqepiFp8",
    authDomain: "koperasimahasiswaapp.firebaseapp.com",
    projectId: "koperasimahasiswaapp",
    storageBucket: "koperasimahasiswaapp.firebasestorage.app",
    messagingSenderId: "812843080953",
    appId: "1:812843080953:web:9a931f89186182660bd628",
    measurementId: "G-ES6G76W66D"
  };

  // Inisialisasi
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
  const auth = getAuth();

  // Tampilkan data pengguna
  onAuthStateChanged(auth, user => {
    if (user) {
      document.getElementById('userEmail').textContent = user.email;

      const createdAt = new Date(user.metadata.creationTime);
      const formattedDate = createdAt.toLocaleDateString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
      });
      document.getElementById('userJoinDate').textContent = `Bergabung sejak ${formattedDate}`;

      if (user.photoURL) {
        document.getElementById('userPhoto').innerHTML = `<img src="${user.photoURL}" class="rounded-circle" width="100" height="100" />`;
      }
    } else {
      window.location.href = "/login"; // redirect jika belum login
    }
  });

  // Update password
  const form = document.getElementById('passwordUpdateForm');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const messageDiv = document.getElementById('message');

    if (newPassword !== confirmPassword) {
      messageDiv.innerHTML = `<div class="text-danger">Password tidak cocok.</div>`;
      return;
    }

    try {
      const user = auth.currentUser;
      await updatePassword(user, newPassword);
      messageDiv.innerHTML = `<div class="text-success">Password berhasil diperbarui.</div>`;
      form.reset();
    } catch (error) {
      messageDiv.innerHTML = `<div class="text-danger">Gagal memperbarui password: ${error.message}</div>`;
    }
  });
</script>

</body>
</html>
