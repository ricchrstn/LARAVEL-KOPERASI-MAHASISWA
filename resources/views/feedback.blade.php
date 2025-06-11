<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Feedback – Koperasi Mahasiswa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root { --kopma:#388e3c; --kopma-light:#4caf50; }
    body  { background:#f8f9fa;font-family:sans-serif }
    header{ background:var(--kopma);color:#fff;padding:22px 16px;text-align:center }
    header h2{ margin:0;font-weight:700 }
    .btn-kopma{ background:var(--kopma);color:#fff;border:none }
    .btn-kopma:hover{ background:var(--kopma-light);color:#fff }
    textarea:focus{ border-color:var(--kopma);box-shadow:0 0 5px var(--kopma)/50% }
  </style>
</head>
<body>

<header>
  <h2>Feedback</h2>
</header>

<div class="container py-5">
  <div class="card p-4 shadow-sm">
    <h5 class="mb-3">Berikan Feedback Anda</h5>
    <p class="text-muted mb-3" style="font-size: 0.95rem;">
        Kami menghargai masukkan Anda untuk meningkatkan layanan kami
    </p>
    <form id="feedbackForm">
      <textarea id="feedbackInput" class="form-control mb-3" rows="5" placeholder="Masukkan masukan atau saran ..." required></textarea>
      <button type="submit" class="btn btn-kopma">Kirim Feedback</button>
    </form>
    <div class="alert alert-success mt-3 d-none" id="successMsg">
      Terima kasih! Feedback Anda berhasil dikirim.
    </div>
  </div>
</div>

<!-- Firebase -->
<script type="module">
  import { initializeApp }           from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
  import { getAuth, onAuthStateChanged, signOut }
          from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
  import { getFirestore, collection, addDoc, serverTimestamp }
          from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

  const firebaseConfig = {
    apiKey: "AIzaSyAuq0JEjnEOagJnONPemkMP0bbgqepiFp8",
    authDomain: "koperasimahasiswaapp.firebaseapp.com",
    projectId: "koperasimahasiswaapp",
    storageBucket: "koperasimahasiswaapp.firebasestorage.app",
    messagingSenderId: "812843080953",
    appId: "1:812843080953:web:9a931f89186182660bd628",
    measurementId: "G-ES6G76W66D"
  };

  const app  = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  const db   = getFirestore(app);

  const form      = document.getElementById("feedbackForm");
  const input     = document.getElementById("feedbackInput");
  const successEl = document.getElementById("successMsg");
  const logoutBtn = document.getElementById("logoutBtn");

  let uid = null;

  onAuthStateChanged(auth, user => {
    if (!user) {
      window.location.href = "/login";
      return;
    }
    uid = user.uid;
  });

  form.onsubmit = async (e) => {
    e.preventDefault();
    const text = input.value.trim();
    if (!text) return;

    await addDoc(collection(db, "feedback"), {
      userId   : uid,
      userEmail: auth.currentUser.email,
      message  : text,
      status   : "pending",
      reply    : "",
      createdAt: serverTimestamp()
    });

    input.value = "";
    successEl.classList.remove("d-none");
    setTimeout(() => successEl.classList.add("d-none"), 4000);
  };

  logoutBtn.onclick = () => {
    signOut(auth).then(() => {
      localStorage.clear();
      window.location.href = "/login";
    });
  };
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
