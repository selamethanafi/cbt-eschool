# 📘 Aplikasi CBT eSchool - README

## Panduan Instalasi Aplikasi CBT eSchool

Aplikasi CBT eSchool adalah sistem Computer-Based Test berbasis **PHP 8** dan **JavaScript**. Panduan berikut membantu Anda melakukan instalasi di komputer lokal menggunakan **XAMPP**.

---

### 1. Install XAMPP (PHP 8)
- Unduh XAMPP dari situs resmi: [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html)
- Pilih versi XAMPP yang mendukung **PHP 8.x**
- Install seperti biasa (lokasi default: `C:\xampp`)
- Jalankan **XAMPP Control Panel** dan klik **Start** pada **Apache** dan **MySQL**

---

### 2. Salin Folder Aplikasi ke `htdocs`
- Ekstrak folder aplikasi (contoh: `cbt-eschool`)
- Pindahkan folder ke:  
  `C:\xampp\htdocs\cbt-eschool`

---

### 3. Akses Halaman Instalasi
- Buka browser dan akses:
http://localhost/cbt-eschool/install


---

### 4. Isi Form Database
- Gunakan data berikut:
- **Host**: `localhost`
- **Nama Database**: `cbt_eschool`
- **Username**: `root`
- **Password**: *(kosongkan jika default)*
- Klik **Lanjut / Install**
- Sistem akan otomatis membuat database dan tabel

---

### 5. Buat Akun Admin Aplikasi
- Masukkan informasi:
- **Username**: `administrator` *(atau sesuai keinginan)*
- **Password**: bebas
- **Nama Lengkap**: `Administrator`
- Klik **Simpan / Selesai**

---

### 6. Instalasi Selesai
- Akan muncul pesan bahwa instalasi berhasil
- Klik **Login**
- Login menggunakan akun admin yang telah dibuat

---

### 7. Hapus Folder `install` (Direkomendasikan)
- Demi keamanan, hapus folder:  
`C:\xampp\htdocs\cbt-eschool\install`

---

### 8. Akses Aplikasi
- Gunakan browser dan buka:
http://localhost/cbt-eschool


---

## ✅ Selamat Menggunakan Aplikasi CBT eSchool!

---

© 2025 — Dikembangkan oleh **Gludug Codelite**  
Semua hak cipta dilindungi undang-undang.
