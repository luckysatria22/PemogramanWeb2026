// Fungsi generik: ambil JSON lalu render ke tabel
const DELAY_SIMULASI = 3000; // ms (dipakai juga di Latihan 3)

async function muatTabel(urlJson, daftarKunci) {
  const tbody = document.querySelector(".table-responsive table tbody");
  const loading = document.getElementById("loading-indicator");
  if (!tbody || !loading) return;

  loading.style.display = "block";
  tbody.innerHTML = "";

  try {
    await new Promise((resolve) => setTimeout(resolve, DELAY_SIMULASI));

    const res = await fetch(urlJson);
    if (!res.ok) {
      throw new Error("Gagal mengambil data (status " + res.status + ")");
    }
    const data = await res.json();

    data.forEach(function (item) {
      const tr = document.createElement("tr");

      // satu <td> per kunci
      daftarKunci.forEach(function (kunci) {
        const td = document.createElement("td");
        td.textContent = item[kunci];   // textContent: aman dari XSS
        tr.appendChild(td);
      });

      // kolom Aksi
      const tdAksi = document.createElement("td");
      tdAksi.innerHTML =
        '<button type="button">Edit</button> ' +
        '<button type="button" class="btn-hapus">Hapus</button>';
      tr.appendChild(tdAksi);

      tbody.appendChild(tr);
    });
  } catch (err) {
    const colspan = daftarKunci.length + 1;
    tbody.innerHTML =
      '<tr><td colspan="' + colspan + '">Gagal memuat data: ' + err.message + "</td></tr>";
  } finally {
    loading.style.display = "none";
  }
}