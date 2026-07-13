# TodoSpace

TodoSpace adalah platform manajemen tugas kuliah berbasis web yang dirancang khusus untuk membantu mahasiswa mengorganisasi aktivitas akademik secara terstruktur. Sistem ini mengintegrasikan analisis prioritas tugas, visualisasi kalender kerja, dan modul pembagian kelompok otomatis untuk mendukung produktivitas harian.

---

<img width="1875" height="963" alt="image" src="https://github.com/user-attachments/assets/19fed00d-2e9b-4cb4-b3b6-5bdbe59d457b" />

---

<img width="1877" height="962" alt="image" src="https://github.com/user-attachments/assets/a518972d-2c7f-43f1-b2e5-c20537dd4c97" />

---

## Fitur Utama

*   **Dasbor Manajemen Tugas**: Pengelolaan daftar tugas kuliah aktif dengan pelacakan tenggat waktu (deadline).
*   **Analisis Prioritas AI**: Integrasi dengan Gemini API untuk membantu menentukan skala prioritas tugas secara cerdas berdasarkan tingkat urgensi.
*   **Group Gacha**: Modul pengacak kelompok mahasiswa dengan antarmuka yang dilengkapi fungsi ekspor data ke format PDF.
*   **Kalender Kerja**: Visualisasi jadwal dan batas waktu pengumpulan tugas untuk mempermudah perencanaan mingguan dan bulanan.

## Arsitektur & Teknologi

Proyek ini dibangun menggunakan arsitektur monolitik ringan yang berfokus pada performa dan kemudahan instalasi di lingkungan server lokal maupun produksi:

*   **Backend**: PHP (Native)
*   **Frontend**: HTML5, JavaScript (ES6), Tailwind CSS (via CDN)
*   **Database**: MySQL / MariaDB
*   **Integrasi API**: Google Gemini API (v1 / v1beta) melalui ekstensi PHP cURL

## Persyaratan Sistem

Sebelum menjalankan proyek ini di lingkungan lokal, pastikan server Anda memenuhi spesifikasi berikut:

*   PHP >= 8.0
*   Ekstensi PHP `curl` diaktifkan
*   Ekstensi PHP `mysqli` diaktifkan
*   MySQL >= 5.7 atau MariaDB >= 10.4

## Panduan Instalasi

1.  **Kloning Repositori**
    ```bash
    git clone [https://github.com/TeguhMF/todospace.git](https://github.com/TeguhMF/todospace.git)
    cd todospace
    ```

2.  **Konfigurasi Database**
    *   Buat database baru di MySQL server Anda (misal: `db_todospace`).
    *   Impor berkas struktur database SQL yang tersedia ke dalam database tersebut.
    *   Sesuaikan kredensial koneksi database pada berkas konfigurasi di dalam folder `config/database.php`.

3.  **Konfigurasi Environment Variables**
    Buat berkas bernama `.env` pada direktori akar (*root*) proyek Anda untuk menyimpan kredensial sensitif:
    ```env
    GEMINI_API_KEY=isi_api_key_gemini_anda_disini
    ```
    *Pastikan berkas `.env` ini tidak diunggah ke repositori publik (sudah tercantum di `.gitignore`).*

4.  **Jalankan Aplikasi**
    Pindahkan atau arahkan web server lokal Anda (XAMPP / Laragon / Apache) ke direktori proyek ini, lalu akses melalui peramban:
    ```text
    http://localhost/todospace/
    ```

---

## Keamanan & Pengodean

*   **Pemisahan Kredensial**: Seluruh API Key dan token eksternal wajib disimpan di dalam berkas `.env`. Penggunaan metode *hardcoded* pada kode sumber sangat dilarang untuk mencegah kebocoran data.
*   **Sanitasi Data**: Input dari pengguna disaring menggunakan fungsi standar PHP untuk mencegah celah keamanan dasar seperti SQL Injection dan Cross-Site Scripting (XSS).

## Kontributor

*   **teguh Maulana Firmansyah** - Pengembang & Perancang Sistem
*   

---
Dikembangkan untuk keperluan manajemen tugas pribadi dan kolaborasi akademik.
