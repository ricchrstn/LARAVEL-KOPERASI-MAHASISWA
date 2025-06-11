<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Pinjaman - Koperasi Mahasiswa</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <style>
    body{margin:0;font-family:sans-serif;background:#f9f9f9}
    header{background:#2e7d32;padding:24px;border-bottom-left-radius:40px;border-bottom-right-radius:40px;text-align:center;color:#fff}
    .container{max-width:1000px;margin:40px auto;padding:0 24px}

    .btn-add{background:#2e7d32;color:#fff;padding:10px 18px;border:none;border-radius:8px;font-weight:700;cursor:pointer}
    .btn-edit{background:#ffc107;color:#000}
    .btn-del {background:#dc3545;color:#fff}
    .btn-status{background:#28a745;color:#fff}

    table{width:100%;border-collapse:collapse;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 8px rgba(0,0,0,.05)}
    th,td{padding:12px 16px;border-bottom:1px solid #eee}
    th{background:#2e7d32;color:#fff;text-align:left}
    .badge{padding:4px 10px;border-radius:12px;font-size:12px;font-weight:700;color:#fff}
    .b-p{background:#0288d1}.b-u{background:#7b1fa2}
    .s-menunggu{background:#ffc107;color:#000}.s-diterima{background:#28a745}.s-ditolak{background:#dc3545}

    .modal{position:fixed;inset:0;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;visibility:hidden;opacity:0;transition:.2s}
    .modal.show{visibility:visible;opacity:1}
    .modal-content{background:#fff;padding:24px;border-radius:12px;width:90%;max-width:400px}
    .modal-content h3{margin-top:0;color:#2e7d32}
    .modal-content input,select{width:100%;padding:10px;margin-bottom:12px;border:1px solid #ccc;border-radius:8px;font-size:15px}
  </style>
</head>
<body>

<header><h2>Data Pinjaman</h2></header>

<div class="container">
  <button class="btn-add" onclick="openModal()">+ Ajukan Pinjaman</button>

  <table>
    <thead>
      <tr><th>No</th><th>Tujuan</th><th>Jumlah</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody id="tbody"><tr><td colspan="6">Memuat…</td></tr></tbody>
  </table>
</div>

<div class="modal" id="modal">
  <div class="modal-content">
    <h3 id="modal-title">Ajukan Pinjaman</h3>

    <input id="tujuanInput" list="tujuanList" placeholder="Tujuan pinjaman" />
    <datalist id="tujuanList">
      <option value="Pribadi">
      <option value="Usaha">
    </datalist>

    <input id="jumlahInput" type="number" min="1" placeholder="Jumlah (rupiah)" />
    <input id="ketInput" placeholder="Keterangan (opsional)" />

    <button id="saveBtn" style="background:#0277bd;color:#fff">Simpan</button>
    <button onclick="closeModal()" style="margin-left:8px">Batal</button>
  </div>
</div>

<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
  import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
  import {
    getFirestore, collection, doc, addDoc, updateDoc, deleteDoc,
    query, where, onSnapshot, getDoc, serverTimestamp
  } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

  const firebaseConfig = {
    apiKey: "AIzaSyAuq0JEjnEOagJnONPemkMP0bbgqepiFp8",
    authDomain: "koperasimahasiswaapp.firebaseapp.com",
    projectId: "koperasimahasiswaapp",
    storageBucket: "koperasimahasiswaapp.appspot.com",
    messagingSenderId: "812843080953",
    appId: "1:812843080953:web:9a931f89186182660bd628"
  };

  const app  = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  const db   = getFirestore(app);

  const tbody       = document.getElementById("tbody");
  const modal       = document.getElementById("modal");
  const modalTitle  = document.getElementById("modal-title");
  const tujuanInput = document.getElementById("tujuanInput");
  const jumlahInput = document.getElementById("jumlahInput");
  const ketInput    = document.getElementById("ketInput");
  const saveBtn     = document.getElementById("saveBtn");

  let currentUid  = null;
  let currentRole = "mahasiswa";
  let editId      = null;

  onAuthStateChanged(auth, async user => {
    if (!user) { window.location.href = "/login"; return; }
    currentUid = user.uid;

    const usrSnap = await getDoc(doc(db,"users",currentUid));
    if (usrSnap.exists()) currentRole = usrSnap.data().role || "mahasiswa";

    const col = collection(db,"pinjaman");
    const q   = currentRole === "admin" ? col : query(col, where("userId","==",currentUid));

    onSnapshot(q, snap => {
      let html = "";
      let i    = 1;
      snap.forEach(d => { html += rowTemplate({...d.data(), id:d.id}, i++); });
      tbody.innerHTML = html || `<tr><td colspan="6">Tidak ada data</td></tr>`;
    });
  });

  function openModal(data=null){
    modal.classList.add("show");
    if (data){
      modalTitle.textContent = "Edit Pinjaman";
      tujuanInput.value  = data.tujuan;
      jumlahInput.value  = data.jumlah;
      ketInput.value     = data.keterangan || "";
      editId = data.id;
    } else {
      modalTitle.textContent = "Ajukan Pinjaman";
      tujuanInput.value  = "p";
      jumlahInput.value  = "";
      ketInput.value     = "";
      editId = null;
    }
  }
  function closeModal(){ modal.classList.remove("show"); }

  saveBtn.onclick = async () => {
    const nominal = Number(jumlahInput.value);
    if (isNaN(nominal) || nominal <= 0){
      alert("Jumlah harus lebih dari 0");
      return;
    }

    const payload = {
      userId     : currentUid,
      tujuan     : tujuanInput.value,
      jumlah     : nominal,
      keterangan : ketInput.value.trim(),
      status     : "Menunggu",
      tanggal    : new Date().toISOString().slice(0,10),
      createdAt  : serverTimestamp()
    };

    if (editId){
      await updateDoc(doc(db,"pinjaman",editId), payload);
    }else{
      await addDoc(collection(db,"pinjaman"), payload);
    }
    closeModal();
  };

  function rowTemplate(d,i){
    const tujuanCls = d.tujuan?.toLowerCase().includes("usaha") ? "b-u" : "b-p";
    const statCls  = d.status === "Menunggu" ? "s-menunggu"
                   : d.status === "Diterima" ? "s-diterima" : "s-ditolak";
    const tgl = d.createdAt?.seconds
              ? new Date(d.createdAt.seconds*1000).toLocaleDateString("id-ID")
              : d.tanggal || "-";

    const adminBtn = `
      <button class="btn-edit" onclick="editRow('${d.id}')">Edit</button>
      <button class="btn-status" onclick="toggleStatus('${d.id}','${d.status}')">Status</button>`;
    const delBtn = `<button class="btn-del" onclick="delRow('${d.id}')">Hapus</button>`;

    return `
      <tr>
        <td>${i}</td>
       <td><span class="badge ${tujuanCls}">${d.tujuan}</span></td>
        <td>Rp ${Number(d.jumlah).toLocaleString("id-ID")}</td>
        <td>${tgl}</td>
        <td><span class="badge ${statCls}">${d.status}</span></td>
        <td>${currentRole==="admin" ? adminBtn+delBtn : delBtn}</td>
      </tr>`;
  }

  window.editRow = async id => {
    if (currentRole !== "admin") return;
    const snap = await getDoc(doc(db,"pinjaman",id));
    if (snap.exists()) openModal({id, ...snap.data()});
  };
  window.delRow = id => {
    if (confirm("Hapus pinjaman ini?")) deleteDoc(doc(db,"pinjaman",id));
  };
  window.toggleStatus = (id,cur) => {
    if (currentRole !== "admin") return;
    const next = cur === "Menunggu" ? "Diterima"
               : cur === "Diterima" ? "Ditolak" : "Menunggu";
    updateDoc(doc(db,"pinjaman",id), {status:next});
  };

  window.openModal  = openModal;
  window.closeModal = closeModal;
</script>
</body>
</html>
