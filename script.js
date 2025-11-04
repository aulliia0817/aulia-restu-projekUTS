let penyewas = [
  { no: 1, nama: "Jofan", telepon: "08588732455", waktu: "06.00-07.00", tanggal: "1/10/2024", lapangan: "B", tarif: 10000 },
  { no: 2, nama: "Aura", telepon: "08988769230", waktu: "09.00-10.00", tanggal: "1/10/2024", lapangan: "A", tarif: 10000 },
  { no: 3, nama: "Aulia", telepon: "08977947973", waktu: "10.00-11.30", tanggal: "1/10/2024", lapangan: "A", tarif: 15000 },
  { no: 4, nama: "Dita", telepon: "089543623776", waktu: "19.00-22.00", tanggal: "1/10/2024", lapangan: "A", tarif: 40000 },
  { no: 5, nama: "Intan", telepon: "08211367833", waktu: "16.00-17.30", tanggal: "1/10/2021", lapangan: "B", tarif: 15000 }
];

let antrian = [...penyewas];

function showSection(id) {
  document.querySelectorAll("section").forEach(sec => sec.classList.remove("active"));
  document.getElementById(id).classList.add("active");
}

function tampilkanData() {
  const tbody = document.querySelector("#tabelPenyewa tbody");
  tbody.innerHTML = "";
  penyewas.forEach(p => {
    tbody.innerHTML += `
      <tr>
        <td>${p.no}</td>
        <td>${p.nama}</td>
        <td>${p.telepon}</td>
        <td>${p.waktu}</td>
        <td>${p.tanggal}</td>
        <td>${p.lapangan}</td>
        <td>Rp ${p.tarif}</td>
      </tr>`;
  });
}

document.getElementById("formPenyewa").addEventListener("submit", e => {
  e.preventDefault();
  const baru = {
    no: parseInt(document.getElementById("no").value),
    nama: document.getElementById("nama").value,
    telepon: document.getElementById("telepon").value,
    waktu: document.getElementById("waktu").value,
    tanggal: document.getElementById("tanggal").value,
    lapangan: document.getElementById("lapangan").value,
    tarif: parseInt(document.getElementById("tarif").value)
  };
  penyewas.push(baru);
  antrian.push(baru);
  tampilkanData();
  alert(`Penyewa ${baru.nama} berhasil ditambahkan!`);
  e.target.reset();
});

function tampilkanAntrian() {
  const list = document.getElementById("listAntrian");
  list.innerHTML = "";
  antrian.forEach(p => {
    const li = document.createElement("li");
    li.textContent = `${p.nama} - Lapangan ${p.lapangan} (Rp ${p.tarif})`;
    list.appendChild(li);
  });
}

function prosesAntrian() {
  if (antrian.length > 0) {
    const diproses = antrian.shift();
    alert(`Penyewa ${diproses.nama} telah diproses.`);
    tampilkanAntrian();
  } else {
    alert("Antrian kosong!");
  }
}

function cariPenyewa() {
  const namaCari = document.getElementById("searchNama").value.toLowerCase();
  const hasil = penyewas.find(p => p.nama.toLowerCase() === namaCari);
  const div = document.getElementById("hasilCari");
  if (hasil) {
    div.innerHTML = `
      <p><b>Nama:</b> ${hasil.nama}</p>
      <p><b>Telepon:</b> ${hasil.telepon}</p>
      <p><b>Waktu:</b> ${hasil.waktu}</p>
      <p><b>Tanggal:</b> ${hasil.tanggal}</p>
      <p><b>Lapangan:</b> ${hasil.lapangan}</p>
      <p><b>Tarif:</b> Rp ${hasil.tarif}</p>`;
  } else {
    div.innerHTML = "<p>Penyewa tidak ditemukan.</p>";
  }
}

tampilkanData();
tampilkanAntrian();
