# E-LAPOR System Design Document

- **Date**: 2026-10-31
- **Status**: Ready for Approval
- **Author**: Antigravity & User

---

## 1. Intent & The Why

E-LAPOR adalah sistem layanan aspirasi dan pengaduan sivitas akademika Universitas Airlangga (kampus Mojo, Sobo, Giri) yang dirancang untuk mempercepat koordinasi penyelesaian keluhan layanan, fasilitas, dan operasional kampus. Sistem ini menyediakan mekanisme pelaporan transparan dan berjenjang (Kabag, Kasubag, Petugas) serta menyediakan saluran pengaduan pelanggaran/whistleblowing secara anonim dan aman demi integritas institusi.

---

## 2. Goals, Non-Goals, & Anti-Goals

### 2.1 Goals
- Menyediakan formulir pelaporan terautentikasi (**Laporan Pelayanan**) bagi sivitas akademika (mahasiswa, dosen, tendik) dengan validasi email kampus (`@unair.ac.id`).
- Menyediakan formulir pelaporan anonim (**Whistleblowing**) tanpa login dengan kode tiket pelacakan publik.
- Mengatur alur koordinasi berjenjang yang disiplin: Kabag mendisposisi ke Kasubag atau Petugas Dekanat; Kasubag mendisposisi ke Petugas Unit; Petugas/Kasubag mengeksekusi laporan.
- Menyediakan verifikasi hasil pengerjaan (Kabag dapat menyetujui *Selesai*, meminta *Revisi*, atau *Menolak* laporan yang tidak valid).
- Mengakomodasi hasil pengerjaan *Terlaksana* maupun *Tertunda* (dengan catatan resmi kendala penundaan saat ditutup).
- Memberikan transparansi riwayat laporan dan fasilitas pemberian penilaian (*rating* bintang 1–5) oleh pelapor untuk laporan pelayanan yang telah selesai dan terlaksana.

### 2.2 Non-Goals
- Tidak mengintegrasikan Single Sign-On (SSO) OAuth pihak ketiga pada fase rilis ini (menggunakan registrasi mandiri dengan validasi domain email unair.ac.id).
- Tidak menyediakan live chat / direct messaging; komunikasi riwayat laporan berbasis catatan/komentar disposisi dan catatan pengerjaan.
- Tidak menyediakan sistem pembayaran atau integrasi perbankan.

### 2.3 Anti-Goals
- **Anti-bypassing authority**: Kabag tidak diperkenankan melompati wewenang dengan menugaskan langsung Petugas di bawah naungan Kasubag. Petugas Kasubag hanya bisa ditugaskan oleh Kasubag terkait.
- **Anti-leak identity**: Data identitas pelapor tidak boleh bocor atau terekspos pada laporan jenis Whistleblowing.
- **Anti-access leak**: Kasubag dan Petugas sama sekali tidak memiliki visibilitas ke data Laporan Whistleblowing (eksklusif hanya untuk Kabag).

---

## 3. Constraints

- **Platform Framework**: Laravel 13 (PHP 8.3+).
- **Database**: MySQL / MariaDB / SQLite (native Laravel PDO).
- **Domain Validasi**: Pendaftaran akun Pelapor dibatasi khusus domain `@unair.ac.id`.
- **Lokasi Kampus**: Dibatasi pada 3 pilihan: Kampus Mojo, Sobo, dan Giri.

---

## 4. Approaches & Architectural Tradeoffs

### Pendekatan yang Dipertimbangkan:
1. **Laravel Blade + Tailwind CSS + Alpine.js (Dipilih & Disepakati)**
   - *Alasan Pemilihan*: Menawarkan eksekusi paling cepat dan stabil pada arsitektur monolit Laravel, tidak membebani kompilasi aset yang rumit, sangat fleksibel dalam mengontrol logika kondisional wewenang (hak disposisi per peran), dan Alpine.js memberikan interaktivitas instan untuk modal popup disposisi, filter tab, dan preview file.
   - *Trade-off*: Navigasi antar halaman utama berupa request HTTP standar (bukan SPA penuh).
2. **Laravel + Inertia.js (Vue 3 / React)**
   - *Evaluasi*: Pengalaman SPA mulus tanpa reload, namun membutuhkan setup build Vite tambahan dan overhead pengelolaan state props Inertia yang berlebihan untuk sistem berbasis formulir dan tabel data administratif.
3. **Laravel + Livewire (TALL Stack)**
   - *Evaluasi*: Reaktivitas tinggi, namun handling upload file bukti berulang dan nesting modal disposisi lebih rentan state-conflict jika tidak dikonfigurasi secara cermat.

---

## 5. System Roles & Detailed Workflow Specifications

### 5.1 Unit Organisasi & Struktur Peran
1. **Kabag (Kepala Bagian / Dekanat)**:
   - Otoritas puncak administratif sistem.
   - Membawahi seluruh Kasubag dan memiliki unit **Petugas Kabag / Dekanat** tersendiri.
   - Mengelola akun Kasubag dan Petugas Kabag melalui menu manajemen.
2. **Kasubag (Kepala Sub Bagian)**:
   - Terdiri dari 3 bidang utama:
     - *Sub Bagian Sarana & Prasarana*
     - *Sub Bagian SDM & Keuangan* (mencakup bidang Keuangan dan Kepegawaian)
     - *Sub Bagian Pendidikan & Kemahasiswaan* (mencakup Kemahasiswaan dan Ormawa)
   - Setiap Kasubag mengelola akun dan menugaskan **Petugas Bidang** masing-masing.
3. **Petugas**:
   - Eksekutor lapangan. Terbagi atas dua unit penempatan:
     - *Petugas Kabag* (melayani tujuan Dekanat / Departemen)
     - *Petugas Kasubag* (melayani tujuan sub-bagian terkait).
4. **Pelapor**:
   - Sivitas akademika (Mahasiswa, Dosen, Tenaga Kependidikan/Tendik).

---

### 5.2 Alur Laporan Pelayanan (Terautentikasi)

```
[Pelapor: Register & Login @unair.ac.id]
                   │
                   ▼
       [Buat Laporan Pelayanan]
  (Judul, Lokasi Kampus, Tujuan, Deskripsi, Bukti)
                   │ Status: diajukan
                   ▼
            [Dashboard KABAG]
                   │
    ┌──────────────┼────────────────────────┐
    │              │                        │
    ▼              ▼ (Tujuan Dekanat)       ▼ (Tujuan Sub-bagian)
[Tolak Laporan]  [Opsi Kabag: ]           [Opsi Kabag: ]
(Status: ditolak, ├─ Disposisi Petugas     ├─ Disposisi Kasubag terkait
 + Catatan)          Kabag                 └─ Kerjakan Sendiri
                 ├─ Disposisi Kasubag         │ (Koreksi tujuan jika keliru)
                 └─ Kerjakan Sendiri          │ Status: didisposisi
                    │ Status: didisposisi     ▼
                    │                   [Dashboard KASUBAG]
                    │                         │
                    │                   ┌─────┴─────────────────────┐
                    │                   │                           │
                    │                   ▼                           ▼
                    │             [Disposisi ke               [Kerjakan
                    │              Petugas Bidang]             Sendiri]
                    │             (Status: diterima)          (Status: proses)
                    │                   │                           │
                    │                   ▼                           │
                    │             [PETUGAS]                         │
                    │             (Klik Mulai -> proses)            │
                    │                   │                           │
                    │                   ▼                           │
                    │        [Selesai Pengerjaan:                   │
                    │         Upload Bukti, Catatan,                │
                    │         Pilih Terlaksana / Tertunda]          │
                    │                   │                           │
                    │                   ▼                           ▼
                    └─────────> [VERIFIKASI KABAG] <────────────────┘
                                        │
             ┌──────────────────────────┼──────────────────────────┐
             ▼                          ▼                          ▼
      [Verifikasi Selesai]     [Verifikasi Tertunda]       [Minta Revisi]
       (Hasil: Terlaksana)      (Hasil: Tertunda/Kendala)   (Catatan revisi dikirim
       (Status: selesai)        (Status: selesai + catatan)  ke eksekutor terkait)
             │                          │
             ▼                          ▼
    [Pelapor: Rating ⭐]        [Pelapor: Info Kendala]
```

#### Aturan Pengerjaan & Verifikasi Khusus:
1. **Kabag Kerjakan Sendiri**: Masuk ke menu *Laporan Saya*, mengunggah foto bukti penyelesaian dan catatan, lalu langsung berubah ke status `selesai` tanpa verifikasi pihak lain.
2. **Kasubag Kerjakan Sendiri**: Masuk ke menu *Laporan Saya*, status berubah ke `proses`, mengunggah bukti foto + catatan, memilih hasil `terlaksana` atau `tertunda`, lalu diteruskan ke Kabag untuk verifikasi.
3. **Hasil Tertunda**: Jika laporan memiliki kendala mendasar yang tidak memungkinkan diselesaikan segera (misal menunggu pengadaan anggaran khusus), Kabag memverifikasi penutupan laporan dengan status akhir `selesai` disertai catatan resmi penjelasan kendala.
4. **Opsi Penolakan (`ditolak`)**: Kabag dapat menolak laporan yang tidak valid, spam, atau di luar ranah kampus dengan memasukkan alasan penolakan. Laporan masuk ke riwayat pelapor berstatus `ditolak` dan tidak menampilkan prompt penilaian bintang.
5. **Rute Revisi**:
   - Jika laporan dikerjakan oleh Petugas Kabag $\rightarrow$ revisi langsung masuk ke tab pengerjaan Petugas Kabag.
   - Jika laporan dikerjakan mandiri oleh Kasubag $\rightarrow$ revisi kembali ke *Laporan Saya* Kasubag.
   - Jika laporan dikerjakan Petugas Kasubag $\rightarrow$ revisi masuk ke Kasubag untuk diarahkan kembali ke Petugas terkait beserta catatan perbaikan.
6. **Koreksi & Pengembalian Disposisi**:
   - Kabag dapat mengubah sub-bagian tujuan saat mendisposisikan jika pelapor salah memilih bidang.
   - Kasubag memiliki tombol *Kembalikan ke Kabag (Salah Disposisi)* untuk mengembalikan laporan yang salah alamat.

---

### 5.3 Alur Laporan Anonim / Whistleblowing

```
[Pelapor Publik]
       │
       ▼
[Form Laporan Anonim (Tanpa Login)]
(Judul, Lokasi Kampus, Tujuan, Deskripsi, Bukti Lampiran Opsional)
       │
       ▼ Submit
[Modal Popup: Menampilkan Kode Tiket Unik (Format: WB-YYYY-XXXXX)]
       │ (Status awal: diajukan)
       │
       ├──────────────────────────────────────────┐
       ▼                                          ▼
[Pelapor Publik: Cek Progres]             [Dashboard KABAG: Tab Whistleblowing]
(Masukkan Kode Tiket di Beranda)          (Akses Eksklusif Kabag Saja)
                                                  │
                                                  ▼
                                          [Kabag Proses Laporan]
                                          (Status berubah: proses)
                                                  │
                                                  ▼
                                          [Kabag Selesaikan Laporan]
                                          (Catatan tindak lanjut + bukti opsional)
                                                  │
                                                  ▼ (Status: selesai)
                                          [Terbaca oleh Pelapor via Kode Tiket]
```

---

### 5.4 Antarmuka & Navigasi Peran

1. **Portal Publik / Pelapor**:
   - *Beranda*: Opsi Laporan Pelayanan atau Whistleblowing, Form Pelacakan Kode Tiket Cepat.
   - *Autentikasi*: Login & Register (`name`, `identity_number` [NIM/NISN], `email` [`@unair.ac.id`], `position` [Mahasiswa/Dosen/Tendik], `password`).
   - *Dashboard Pelapor*: Form Buat Laporan, Tab Status Laporan (aktif), Tab Riwayat Laporan (selesai dengan rating bintang 1–5 untuk laporan terlaksana).
   - *Cek Tiket Whistleblowing*: Tampilan status progres (`diajukan` $\rightarrow$ `proses` $\rightarrow$ `selesai`) dan catatan akhir Kabag.
2. **Dashboard Kabag**:
   - Sidebar: Dashboard Ringkasan, Laporan Pelayanan (semua bidang), Laporan Whistleblowing (eksklusif), Verifikasi Laporan, Laporan Saya (pengerjaan mandiri), Manajemen Akun (Kasubag & Petugas Kabag), Riwayat Laporan.
   - Aksi Detail Laporan: Modal Disposisi (dengan opsi koreksi tujuan), Tombol Kerjakan Sendiri, Tombol Tolak Laporan (alasan penolakan).
   - Aksi Verifikasi: Form Verifikasi (Selesai Terlaksana, Selesai Tertunda dengan catatan penjelasan, atau Minta Revisi).
3. **Dashboard Kasubag**:
   - Sidebar: Dashboard Bidang, Laporan Masuk, Laporan Saya, Monitoring Status Laporan, Manajemen Petugas Bidang, Riwayat Laporan.
   - Aksi Detail Laporan: Disposisi ke Petugas Bidang, Kerjakan Sendiri, atau Kembalikan ke Kabag (Salah Disposisi).
4. **Dashboard Petugas**:
   - Sidebar: Dashboard Tugas, Laporan Masuk, Laporan Saya (aktif dikerjakan), Riwayat Laporan.
   - Aksi Pengerjaan: Tombol *Mulai Kerjakan* $\rightarrow$ Form Eksekusi (upload bukti foto/file, catatan, pilihan *Terlaksana* / *Tertunda*) $\rightarrow$ Kirim untuk Verifikasi.

---

## 6. What's Left to the Builder

1. **Skema Database & Relasi**:
   - `users`: Kolom `role` (`pelapor`, `kabag`, `kasubag`, `petugas`), `sub_department_id`, `identity_number`, `position`.
   - `sub_departments`: Master data unit (Dekanat, Sarpras, SDM & Keuangan, Pendidikan & Kemahasiswaan).
   - `reports`: Kolom tipe (`pelayanan`, `whistleblowing`), `ticket_code`, `title`, `campus_location`, `status` (`diajukan`, `didisposisi`, `diterima`, `proses`, `ditolak`, `selesai`), `execution_result` (`terlaksana`, `tertunda`), `rating` (1–5).
   - `report_dispositions`: Riwayat lengkap alur pergerakan tiket antar pengirim dan penerima.
   - `report_evidences`: Penyimpanan bukti laporan awal dan bukti hasil penyelesaian kerja.
2. **UI & Styling Tokens**:
   - Desain tata letak responsif bertema institusi kampus modern berbasis Tailwind CSS dan interaktivitas Alpine.js.
3. **Seeder Data Awal**:
   - Seeder otomatis untuk master sub_departments, akun Kabag awal, 3 Kasubag, perwakilan Petugas, dan akun contoh Pelapor.
4. **Validasi & Proteksi**:
   - Regex domain email `@unair.ac.id`.
   - Sanitasi upload file (tipe mime, limit size).
   - Rate limiting untuk pengajuan anonim publik.
