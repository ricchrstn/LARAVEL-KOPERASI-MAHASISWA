<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Koperasi Mahasiswa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      font-family: sans-serif;
      background-color: #f5f5f5;
    }

    header {
      background-color: #2e7d32;
      padding: 24px;
      border-bottom-left-radius: 40px;
      border-bottom-right-radius: 40px;
      text-align: center;
      color: white;
      position: relative;
    }

    header img {
      height: 100px;
      margin-bottom: 10px;
    }

    .logout-btn {
      background-color: white;
      color: #2e7d32;
      border: 2px solid #fff;
      padding: 8px 16px;
      border-radius: 12px;
      cursor: pointer;
      font-weight: bold;
      position: absolute;
      top: 24px;
      right: 24px;
    }

    .logout-btn:hover {
      background-color: #fff;
      color: #2e7d32;
    }

    nav {
      display: flex;
      justify-content: center;
      gap: 20px;
      background: #fff;
      padding: 16px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      border-radius: 0 0 20px 20px;
      margin-bottom: 32px;
    }

    nav a {
      text-decoration: none;
      font-weight: bold;
      color: #2e7d32;
      padding: 8px 12px;
      border-radius: 8px;
      transition: background 0.2s;
    }

    nav a:hover {
      background-color: #ffe7e0;
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
      padding: 0 24px 40px;
    }

    .card {
      background-color: white;
      border-radius: 16px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
      padding: 24px;
      margin-bottom: 24px;
      transition: transform 0.2s;
    }

    .card:hover {
      transform: translateY(-4px);
    }

    .card h3 {
      margin-top: 0;
      color: #2e7d32;
    }
  </style>
</head>
<body>

  <header>
    <img src="{{ asset('images/logokopma.png') }}" alt="Logo Koperasi">
    <h2>Selamat Datang di Dashboard</h2>
    <button class="logout-btn" onclick="logout()">Logout</button>
  </header>

  <nav>
    <a href="/pinjaman">Pinjaman</a>
    <a href="/simpanan">Simpanan</a>
    <a href="/profil">Profil</a>
    <a href="/feedback">Feedback</a>
    <a href="/about">Tentang Pembuat</a>
  </nav>

  <div class="container" id="dashboard-content">
    <div class="card">
      <h3>Informasi Akun</h3>
      <p><strong>Email:</strong> <span id="user-email">Memuat...</span></p>
      <p><strong>Role:</strong> <span id="user-role">Memuat...</span></p>
    </div>

    <div class="card">
      <h3>Transaksi Terbaru</h3>
      <ul id="transaction-list">
        <li>Memuat data...</li>
      </ul>
    </div>
  </div>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
    import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
    import { getFirestore, doc, getDoc, collection, query, orderBy, limit, getDocs } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

    const firebaseConfig = {
      apiKey: "AIzaSyAuq0JEjnEOagJnONPemkMP0bbgqepiFp8",
      authDomain: "koperasimahasiswaapp.firebaseapp.com",
      projectId: "koperasimahasiswaapp",
      storageBucket: "koperasimahasiswaapp.appspot.com",
      messagingSenderId: "812843080953",
      appId: "1:812843080953:web:9a931f89186182660bd628"
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const db = getFirestore(app);

    const userEmailSpan = document.getElementById("user-email");
    const userRoleSpan = document.getElementById("user-role");
    const transactionList = document.getElementById("transaction-list");

    onAuthStateChanged(auth, async (user) => {
      if (user) {
        userEmailSpan.textContent = user.email;

        const userDoc = await getDoc(doc(db, "users", user.uid));
        const role = userDoc.exists() ? userDoc.data().role : "mahasiswa";
        userRoleSpan.textContent = role;

        const transaksiRef = collection(db, "transaksi");
        const q = query(transaksiRef, orderBy("tanggal", "desc"), limit(5));
        const snapshot = await getDocs(q);

        transactionList.innerHTML = "";
        if (snapshot.empty) {
          transactionList.innerHTML = "<li>Tidak ada transaksi.</li>";
        } else {
          snapshot.forEach(doc => {
            const data = doc.data();
            const item = document.createElement("li");
            item.textContent = `${data.nama} - Rp${data.jumlah.toLocaleString()} (${new Date(data.tanggal.seconds * 1000).toLocaleDateString()})`;
            transactionList.appendChild(item);
          });
        }
      } else {
        window.location.href = "/login";
      }
    });

    window.logout = function() {
    signOut(auth).then(() => {
        localStorage.clear();
        window.location.href = "/login";
    }).catch((error) => {
        console.error("Logout gagal:", error);
    });
    };
  </script>

</body>
</html>
