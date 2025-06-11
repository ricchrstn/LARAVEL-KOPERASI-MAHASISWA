<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login | Koperasi Mahasiswa</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    /* ---------- GLOBAL ---------- */
    *{box-sizing:border-box;font-family:sans-serif}
    body{
      margin:0;
      min-height:100vh;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:flex-start;
      background:linear-gradient(to bottom right,#059669,#064e3b);
    }
    h2{margin:24px 0 8px;color:#fff;font-size:28px;font-weight:700}
    p.subtitle{margin:0 0 32px;color:rgba(255,255,255,.8);font-size:16px}

    /* ---------- LOGO BULAT ---------- */
    .logo-wrap{
      margin-top:40px;
      background:#fff;
      padding:24px;
      border-radius:50%;
      box-shadow:0 4px 10px rgba(0,0,0,.1);
    }
    .logo-wrap img{height:120px}

    /* ---------- CARD ---------- */
    .card{
      width:100%;
      max-width:450px;
      background:#fff;
      border-radius:16px;
      padding:24px;
      box-shadow:0 4px 10px rgba(0,0,0,.1);
    }
    input{
      width:100%;
      padding:12px 16px;
      margin-bottom:16px;
      border-radius:12px;
      border:1px solid #ccc;
      font-size:15px;
    }
    .checkbox-row{display:flex;align-items:center;margin-bottom:16px}
    .checkbox-row input{margin-right:8px}
    button{
      width:100%;
      padding:14px;
      background:#059669;
      color:#fff;
      border:none;
      border-radius:12px;
      font-weight:700;
      cursor:pointer;
      font-size:15px;
    }
    button:disabled{background:#ccc;cursor:default}
    .error{color:red;text-align:center;margin-bottom:12px}
    .links{margin-top:24px;text-align:center}
    .links a{
      color:#fff;
      font-weight:700;
      text-decoration:none;
      margin:0 4px;
    }
  </style>
</head>
<body>

  <!-- Logo -->
  <div class="logo-wrap">
    <img src="{{ asset('images/logokopma.png') }}" alt="Logo">
  </div>

  <!-- Heading -->
  <h2>Welcome&nbsp;Back</h2>
  <p class="subtitle">Sign in to your koperasi account</p>

  <!-- Card -->
  <div class="card">
    <div id="error" class="error"></div>

    <form id="firebase-login-form">
      <input type="email" id="email" placeholder="Email" required>
      <input type="password" id="password" placeholder="Password" required>

      <div class="checkbox-row">
        <label style="display: flex; align-items: center; font-size: 14px; color: #333;">
            <input type="checkbox" id="rememberMe" style="margin-right: 8px; accent-color: #059669;">
            Remember Me
        </label>
    </div>

      <button id="submit-btn">SIGN&nbsp;IN</button>
    </form>
  </div>

  <!-- Links -->
  <div class="links">
    <a href="{{ url('/register') }}">Sign&nbsp;Up</a>|
    <a href="{{ url('/forgot-password') }}">Forgot&nbsp;Password?</a>
  </div>

  <!-- Firebase & Logic -->
  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
    import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
    import { getFirestore, doc, getDoc } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

    /* --- CONFIG --- */
    const firebaseConfig = {
      apiKey: "AIzaSyAuq0JEjnEOagJnONPemkMP0bbgqepiFp8",
      authDomain: "koperasimahasiswaapp.firebaseapp.com",
      projectId: "koperasimahasiswaapp",
      storageBucket: "koperasimahasiswaapp.appspot.com",
      messagingSenderId: "812843080953",
      appId: "1:812843080953:web:9a931f89186182660bd628",
      measurementId: "G-ES6G76W66D"
    };

    const app  = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const db   = getFirestore(app);

    /* --- ELEMENTS --- */
    const form    = document.getElementById("firebase-login-form");
    const emailEl = document.getElementById("email");
    const passEl  = document.getElementById("password");
    const remember= document.getElementById("rememberMe");
    const errBox  = document.getElementById("error");
    const btn     = document.getElementById("submit-btn");

    /* --- Restore Remember Me --- */
    window.onload = () =>{
      if(localStorage.getItem("rememberMe")==="true"){
        emailEl.value    = localStorage.getItem("email")    || "";
        passEl.value     = localStorage.getItem("password") || "";
        remember.checked = true;
      }
    };

    /* --- Login handler --- */
    form.addEventListener("submit", async e => {
    e.preventDefault();
    errBox.textContent = "";
    btn.disabled = true;
    btn.textContent = "Loading...";

    try {
        const cred = await signInWithEmailAndPassword(auth, emailEl.value, passEl.value);

        if (remember.checked) {
        localStorage.setItem("email", emailEl.value);
        localStorage.setItem("password", passEl.value);
        localStorage.setItem("rememberMe", "true");
        } else {
        localStorage.removeItem("email");
        localStorage.removeItem("password");
        localStorage.setItem("rememberMe", "false");
        }

        const snap = await getDoc(doc(db, "users", cred.user.uid));
        const role = snap.exists() ? (snap.data().role || "mahasiswa") : "mahasiswa";

        console.log("Role:", role);

        window.location.href = role === "admin"
        ? "{{ url('/admin/dashboard') }}"
        : "{{ url('/dashboard') }}";
    } catch (err) {
        errBox.textContent = err.message;
    } finally {
        btn.disabled = false;
        btn.textContent = "SIGN IN";
    }
    });

  </script>
</body>
</html>
