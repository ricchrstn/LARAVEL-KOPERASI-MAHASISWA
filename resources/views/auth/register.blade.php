<!DOCTYPE html>
<html>
<head>
  <title>Register - Kopma</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom, #2e7d32, #66bb6a);
      color: #fff;
      text-align: center;
      padding: 20px;
    }

    form {
      background: #fff;
      color: #000;
      padding: 20px;
      border-radius: 16px;
      max-width: 400px;
      margin: auto;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    input, button {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    button {
      background-color: #2e7d32;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    #error-message {
      color: red;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  <h1>Create Account</h1>
  <p>Join our koperasi community</p>

  <div id="error-message"></div>

  <form onsubmit="event.preventDefault(); registerUser();">
    <input type="text" id="name" placeholder="Full Name" required />
    <input type="text" id="nim" placeholder="NIM" required />
    <input type="text" id="faculty" placeholder="Faculty" required />
    <input type="text" id="phone" placeholder="Phone Number" required />
    <input type="email" id="email" placeholder="Email" required />
    <input type="password" id="password" placeholder="Password" required />
    <input type="password" id="confirmPassword" placeholder="Confirm Password" required />
    <button type="submit">REGISTER</button>
  </form>

  <p>Already have an account? <a href="/login" style="color: #fff; font-weight: bold;">Sign In</a></p>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
    import { getAuth, createUserWithEmailAndPassword, sendEmailVerification, updateProfile } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-auth.js";
    import { getFirestore, doc, setDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore.js";

    const firebaseConfig = {
      apiKey: "AIzaSyAuq0JEjnEOagJnONPemkMP0bbgqepiFp8",
      authDomain: "koperasimahasiswaapp.firebaseapp.com",
      projectId: "koperasimahasiswaapp",
      storageBucket: "koperasimahasiswaapp.appspot.com",
      messagingSenderId: "812843080953",
      appId: "1:812843080953:web:9a931f89186182660bd628",
      measurementId: "G-ES6G76W66D"
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const db = getFirestore(app);

    window.registerUser = async function () {
      const name = document.getElementById('name').value.trim();
      const nim = document.getElementById('nim').value.trim();
      const faculty = document.getElementById('faculty').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const errorBox = document.getElementById('error-message');
      errorBox.textContent = '';

      if (password !== confirmPassword) {
        errorBox.textContent = 'Passwords do not match.';
        return;
      }

      try {
        const userCredential = await createUserWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        await updateProfile(user, { displayName: name });

        await setDoc(doc(db, "users", user.uid), {
          name,
          nim,
          faculty,
          phone,
          email,
          role: "mahasiswa",
          createdAt: serverTimestamp(),
          updatedAt: serverTimestamp()
        });

        await sendEmailVerification(user);

        alert("Registration successful! Please verify your email.");
        window.location.href = "/login";
      } catch (error) {
        console.error(error);
        errorBox.textContent = error.message;
      }
    }
  </script>
</body>
</html>
