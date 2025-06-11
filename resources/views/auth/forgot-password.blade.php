<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Forgot Password</title>
  <script type="module">
    // Firebase config & init
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
    import { getAuth, sendPasswordResetEmail } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-auth.js";

    const firebaseConfig = {
      apiKey: "AIzaSyAuq0JEjnEOagJnONPemkMP0bbgqepiFp8",
      authDomain: "koperasimahasiswaapp.firebaseapp.com",
      projectId: "koperasimahasiswaapp",
      storageBucket: "koperasimahasiswaapp.firebasestorage.app",
      messagingSenderId: "812843080953",
      appId: "1:812843080953:web:9a931f89186182660bd628",
      measurementId: "G-ES6G76W66D"
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    window.resetPassword = async function() {
      const emailInput = document.getElementById('email');
      const errorMessage = document.getElementById('error-message');
      const btn = document.getElementById('reset-btn');
      errorMessage.textContent = '';
      btn.disabled = true;

      const email = emailInput.value.trim();
      if (!email) {
        errorMessage.textContent = 'Please enter your email address.';
        btn.disabled = false;
        return;
      }

      try {
        await sendPasswordResetEmail(auth, email);
        alert('Password reset link has been sent! Please check your email.');
        window.location.href = '/login';  // Redirect ke login
      } catch (error) {
        errorMessage.textContent = error.message || 'An error occurred. Please try again.';
      } finally {
        btn.disabled = false;
      }
    }
  </script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom, #2e7d32, #66bb6a, #a5d6a7);
      margin: 0; padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    h1 {
      color: #2e7d32;
      margin-bottom: 0.2rem;
    }
    p {
      color: #555;
      font-size: 14px;
      margin-bottom: 2rem;
    }
    input[type="email"] {
      width: 100%;
      padding: 0.75rem;
      font-size: 1rem;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-bottom: 1rem;
      box-sizing: border-box;
    }
    button {
      width: 100%;
      background-color: #2e7d32;
      border: none;
      color: white;
      font-size: 1rem;
      padding: 0.75rem;
      border-radius: 8px;
      cursor: pointer;
    }
    button:disabled {
      background-color: #9ccc65;
      cursor: not-allowed;
    }
    #error-message {
      color: red;
      margin-bottom: 1rem;
      min-height: 18px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Forgot Your Password?</h1>
    <p>Enter your email address below and we will send you a link to reset your password.</p>
    <input id="email" type="email" placeholder="Email Address" />
    <div id="error-message"></div>
    <button id="reset-btn" onclick="resetPassword()">Send Reset Link</button>
  </div>
</body>
</html>
