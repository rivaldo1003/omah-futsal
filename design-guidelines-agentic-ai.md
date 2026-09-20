# Design Guidelines — Agentic AI Web App

Dokumen ini adalah acuan tunggal untuk desain UI/UX. Tujuannya: tampilan simple, clean, minimalis, dan terasa dibuat dengan sengaja — bukan hasil template generative AI yang generik ("AI slop"). Baca ini sebelum membuat komponen atau halaman baru.

---

## 1. Prinsip Utama

1. **Fungsi dulu, dekorasi belakangan.** Setiap elemen visual harus punya alasan fungsional. Kalau tidak menambah kejelasan, hapus.
2. **Satu titik fokus per layar.** Jangan semua elemen bersaing menarik perhatian — pilih satu hal yang paling penting di tiap tampilan (biasanya: status agent, atau area kerja utama).
3. **Konsisten, bukan seragam.** Elemen sejenis (tombol, card, input) harus terasa satu keluarga, tapi hirarki visual tetap jelas (primary vs secondary vs tertiary).
4. **Tenang secara visual.** Agentic AI app sering menampilkan proses panjang (tool calls, reasoning, log). UI harus tetap tenang walau ada banyak aktivitas — bukan berkedip-kedip atau penuh animasi.
5. **Dipercaya, bukan "ajaib".** Hindari kesan gimmicky-AI (gradient ungu-biru berputar, partikel bertebaran, ikon bintang berkilau). Tampilkan proses AI apa adanya: jelas, transparan, bisa diaudit.

---

## 2. Yang HARUS Dihindari (ciri khas "AI Slop")

Hindari secara eksplisit pola-pola berikut, karena ini adalah default generative AI yang paling gampang dikenali:

- **Palet klise AI:** background krem hangat + font serif kontras tinggi + aksen terracotta/oranye (`#D97757`-ish), ATAU background hitam pekat dengan satu aksen neon (hijau/violet menyala).
- **Gradient sebagai dekorasi**, terutama gradient ungu→biru→pink di background, tombol, atau ikon "AI".
- **Ikon bintang/kilau (✨) atau efek "magic sparkle"** untuk menandakan fitur AI.
- **Kartu SaaS generik:** semua konten dipotong jadi card dengan border-radius sama rata, shadow abu-abu lembut yang sama di semua card, tanpa hirarki.
- **Chrome template yang template-y:** label eyebrow ALL-CAPS di atas setiap heading, teks meta yang disambung titik tengah ("A · B · C"), label bergaya "KATA — fragmen" pakai em dash panjang, font monospace untuk label kecil yang tidak perlu, tanda panah "→" ditempel di akhir semua tombol/link.
- **Animasi fade-slide-up otomatis di setiap section saat scroll**, dan hover transition seragam di semua card. Motion hanya untuk merespons aksi user (buka, tutup, konfirmasi, loading), bukan hiasan saat load halaman.
- **Bold/italic/warna berbeda hanya di satu kata** dalam headline untuk kesan "dramatis".
- **Numbering 01/02/03 dipaksakan** padahal isinya bukan proses berurutan.
- **Border-radius besar dan seragam di semua elemen** tanpa mempertimbangkan ukuran/fungsi elemen tersebut.

Kalau ragu apakah sebuah pilihan desain masuk "AI slop": tanyakan, "apakah ini keputusan spesifik untuk produk saya, atau ini yang akan keluar kalau saya minta AI bikin app apapun?" Kalau jawabannya yang kedua, ganti.

---

## 3. Warna

Gunakan palet netral yang tenang, dengan SATU warna aksen fungsional (bukan dekoratif).

| Token | Deskripsi | Contoh Hex (silakan sesuaikan brand) |
|---|---|---|
| `--bg` | Background utama | `#FFFFFF` / `#0E0E10` (dark mode) |
| `--surface` | Panel, card, sidebar | `#F7F7F8` / `#18181B` |
| `--border` | Garis pembatas, hairline | `#E5E5E7` / `#2A2A2E` |
| `--text-primary` | Teks utama | `#111113` / `#F2F2F3` |
| `--text-secondary` | Teks sekunder, caption, meta | `#6B6B70` / `#9A9AA0` |
| `--accent` | Satu warna aksi (tombol primary, link aktif, status "running") | pilih satu, pakai konsisten |
| `--success` / `--warning` / `--danger` | Status semantik (tool berhasil/gagal, error) | hijau/kuning/merah standar, jangan neon |

Aturan:
- Aksen HANYA untuk elemen interaktif atau status penting (tombol utama, indikator agent sedang berjalan, link aktif). Jangan pakai aksen sebagai dekorasi background atau border semua card.
- Jangan pakai gradient kecuali untuk kebutuhan fungsional yang sangat spesifik (misalnya progress bar). Kalau pakai, buat halus dan subtle, bukan mencolok.
- Dark mode dan light mode harus punya kontras teks yang sama-sama nyaman dibaca (cek rasio kontras WCAG AA minimal).

---

## 4. Tipografi

- Gunakan **satu font family** untuk seluruh UI (satu untuk heading + body). Kalau butuh dua, pastikan keduanya kontras jelas (misal: satu sans untuk UI, satu monospace HANYA untuk kode/data teknis — bukan untuk label dekoratif).
- Rekomendasi: font sans-serif netral yang sudah teruji untuk produk (system font stack, atau font UI populer yang readable di ukuran kecil). Hindari font display dramatis untuk heading kalau produknya adalah tools/dashboard kerja.
- Skala tipografi jelas dan terbatas (misalnya: 12 / 14 / 16 / 20 / 24 / 32px). Jangan bikin terlalu banyak ukuran.
- Line-length maksimal ~80 karakter untuk teks panjang (deskripsi, pesan chat, dokumentasi).
- Sentence case untuk semua label, tombol, heading — **jangan ALL CAPS** kecuali benar-benar untuk hal spesifik seperti kode status (`ERROR`, `200 OK`).
- Line-height cukup lega untuk teks body (1.4–1.6), lebih ketat untuk heading (1.1–1.3).

---

## 5. Layout & Spacing

- Gunakan spacing scale konsisten berbasis kelipatan (misal 4px: 4, 8, 12, 16, 24, 32, 48, 64).
- Grid/alignment jelas: pilih left-aligned untuk konten kerja/dashboard (lebih mudah dipindai), reserve center-aligned hanya untuk halaman landing/kosong (empty state, halaman login).
- Untuk aplikasi agentic AI, pertimbangkan struktur layout umum:
  - **Sidebar navigasi** (daftar percakapan/task/project) — tenang, tidak ramai.
  - **Area kerja utama** — tempat percakapan/output berlangsung, jadi fokus utama.
  - **Panel status/aktivitas agent** (opsional) — menunjukkan tool call, step, atau reasoning secara transparan, bukan disembunyikan di balik animasi "magic".
- Border-radius: pilih SATU skala kecil (misal: 6px untuk elemen kecil seperti button/input, 12px untuk card/panel besar). Jangan pakai radius besar (16px+) di semua elemen tanpa alasan.
- Shadow: gunakan sangat minim, hanya untuk elevasi fungsional (modal, dropdown, tooltip). Card statis dalam list biasanya cukup dengan border tipis, tanpa shadow.

---

## 6. Komponen Kunci untuk Agentic AI

**Chat / conversation area**
- Bedakan pesan user vs agent secara jelas tapi halus (bukan bubble warna-warni kontras tinggi). Bisa cukup dengan alignment atau warna background surface yang beda tipis.
- Tampilkan status agent (thinking, calling tool, done, error) dengan teks/ikon simpel dan konsisten — bukan spinner ber-glow atau animasi partikel.

**Tool calls / actions**
- Tampilkan sebagai blok yang bisa di-collapse/expand, dengan label jelas (nama tool, input, output/status). Transparansi > "sulap".
- Gunakan warna status semantik (success/warning/danger) secara konsisten, bukan warna random.

**Loading & progress**
- Skeleton loading atau progress indicator simpel (garis/dot), bukan animasi berat.
- Untuk proses panjang (multi-step agent task), gunakan step list dengan status per step (pending/running/done/error) — bukan satu spinner besar generik.

**Empty state & error**
- Tulis dari sudut pandang fungsi: jelaskan apa yang terjadi dan langkah berikutnya, bukan pesan generik ("Oops! Something went wrong 😅").
- Error tidak minta maaf berlebihan dan tidak samar soal apa yang salah.

**Tombol & aksi**
- Label tombol = kata kerja aktif spesifik ("Simpan perubahan", "Jalankan task"), bukan generik ("Submit", "Go").
- Satu tombol primary per konteks. Jangan banyak tombol dengan bobot visual sama.

---

## 7. Motion

- Motion hanya untuk merespons aksi user: buka/tutup panel, expand tool call, konfirmasi aksi, transisi status.
- Hindari animasi otomatis saat halaman load (fade-in berurutan di semua elemen).
- Durasi singkat (150–250ms), easing halus, hormati preferensi `prefers-reduced-motion`.

---

## 8. Checklist Sebelum Ship

- [ ] Tidak ada gradient dekoratif ungu/biru/pink atau efek "sparkle" AI.
- [ ] Hanya satu warna aksen fungsional, dipakai konsisten.
- [ ] Tidak ada ALL CAPS untuk label/eyebrow yang tidak perlu.
- [ ] Border-radius dan shadow konsisten, tidak seragam paksa di semua elemen.
- [ ] Motion hanya merespons aksi user, tidak ada animasi otomatis berlebihan.
- [ ] Status/proses agent ditampilkan transparan (bisa diaudit), bukan disamarkan jadi animasi generik.
- [ ] Copy ditulis spesifik dan fungsional, bukan generik/template.
- [ ] Kontras warna teks memenuhi standar aksesibilitas (WCAG AA).
- [ ] Layout tetap rapi & fokus meski konten dinamis (log panjang, banyak tool call).

---

*Simpan file ini sebagai acuan bersama tim/agent AI saat membangun atau mereview komponen UI baru.*
